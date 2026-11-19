<?php

namespace App\Http\Controllers;


use App\Models\Boleto;
use App\Models\Viaje;
use App\Models\Venta;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;


class BoletoController extends Controller
{
    /**
     * Panel inicial: Lista los viajes programados disponibles para la venta.
     */
    public function index()
    {
        $viajes = Viaje::with(['ruta.origen', 'ruta.destino', 'colectivo.modelo.marca'])
            ->where('estado', 'programado')
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha')
            ->orderBy('hora_salida')
            ->get();

        // Traemos las últimas 10 ventas para auditoría rápida en mostrador
        $ultimasVentas = Venta::with(['cliente', 'boletos.viaje.ruta.origen', 'boletos.viaje.ruta.destino', 'boletos.asiento'])
            ->latest()
            ->take(10)
            ->get();

        return view('boleteria.index', compact('viajes', 'ultimasVentas'));
    }

    public function buscarPorDni($dni)
{
    $usuario = User::where('dni', $dni)->first(['nombre', 'apellido', 'telefono']);
    return response()->json($usuario);
} 

    /**
     * Muestra el mapa visual de asientos del colectivo para un viaje concreto.
     */
    public function create(Viaje $viaje)
    {
        $viaje->load(['ruta.origen', 'ruta.destino', 'colectivo.asientos']);

        // Obtenemos los IDs de los asientos que YA están vendidos en este viaje
        $asientosOcupadosIds = Boleto::where('viaje_id', $viaje->id)
            ->where('estado', 'vendido')
            ->pluck('asiento_id')
            ->toArray();

        return view('boleteria.create', compact('viaje', 'asientosOcupadosIds'));
    }

    /**
     * ALGORITMO TRANSACCIONAL: Registra la Venta, el Pasajero (si es nuevo) y el Boleto.
     */
  public function store(Request $request)
{
    $request->validate([
        'viaje_id'               => 'required|exists:viajes,id',
        'metodo_pago'            => 'required|string|in:efectivo,tarjeta,transferencia',
        'comprador_dni'          => 'required|numeric|digits_between:7,10',
        'comprador_nombre'       => 'required|string|max:50',
        'comprador_apellido'     => 'required|string|max:50',
        'pasajeros'              => 'required|array|min:1',
        'pasajeros.*.asiento_id' => 'required|exists:asientos,id',
        'pasajeros.*.dni'        => 'required|numeric|digits_between:7,10|distinct',
        'pasajeros.*.nombre'     => 'required|string|max:50',
        'pasajeros.*.apellido'   => 'required|string|max:50',
    ], [
        'pasajeros.*.dni.distinct' => 'No se puede asignar el mismo DNI a dos asientos en el mismo viaje.',
    ]);

    $viaje = Viaje::findOrFail($request->viaje_id);

    try {
        $primerBoleto = DB::transaction(function () use ($request, $viaje) {
            
            // 1. Registrar o recuperar al Comprador
            $comprador = User::firstOrCreate(
                ['dni' => $request->comprador_dni],
                [
                    'nombre'   => ucwords(strtolower($request->comprador_nombre)),
                    'apellido' => ucwords(strtolower($request->comprador_apellido)),
                    'email'    => $request->comprador_dni . '@guri.com',
                    'telefono' => $request->comprador_telefono,
                    'password' => Hash::make($request->comprador_dni),
                ]
            );
            if ($comprador->wasRecentlyCreated) $comprador->assignRole('pasajero');

            // 2. Crear la Venta (el total se actualizará al sumar cada asiento real)
            $venta = Venta::create([
                'cliente_id'  => $comprador->id,
                'vendedor_id' => Auth::id(),
                'fecha_venta' => now(),
                'total'       => 0,
                'metodo_pago' => $request->metodo_pago,
                'estado'      => 'completada',
            ]);

            $totalVenta = 0;
            $primerBoletoCreado = null;

            // 3. Emitir cada Boleto con el precio exacto según el tipo de asiento en DB
            foreach ($request->pasajeros as $item) {
                $asientoFisico = $viaje->colectivo->asientos()
                    ->where('id', $item['asiento_id'])
                    ->where('estado', 'habilitado')
                    ->firstOrFail();

                // Cálculo real según categoría del asiento en base de datos
                $precioAsiento = match ($asientoFisico->tipo) {
                    'cama'  => round($viaje->precio * 1.25, 2), // +25% ejecutivo
                    'comun' => round($viaje->precio * 0.90, 2), // -10% común
                    default => $viaje->precio,                  // semicama base
                };

                $pasajero = User::firstOrCreate(
                    ['dni' => $item['dni']],
                    [
                        'nombre'   => ucwords(strtolower($item['nombre'])),
                        'apellido' => ucwords(strtolower($item['apellido'])),
                        'email'    => $item['dni'] . '@guri.com',
                        'password' => Hash::make($item['dni']),
                    ]
                );
                if ($pasajero->wasRecentlyCreated) $pasajero->assignRole('pasajero');

                $boleto = $venta->boletos()->create([
                    'user_id'    => $pasajero->id,
                    'viaje_id'   => $viaje->id,
                    'asiento_id' => $asientoFisico->id,
                    'precio'     => $precioAsiento,
                    'estado'     => 'vendido',
                ]);

                $totalVenta += $precioAsiento;
                if (!$primerBoletoCreado) $primerBoletoCreado = $boleto;
            }

            // Guardamos el total real sumado en la cabecera de Venta
            $venta->update(['total' => $totalVenta]);

            return $primerBoletoCreado;
        });

        return redirect()->route('boleteria.show', $primerBoleto->id)
            ->with('success', '¡Venta procesada! Boletos emitidos según categoría de butaca.');

    } catch (QueryException $e) {
        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
            return redirect()->back()->withInput()
                ->with('error', '⚠️ Una de las butacas seleccionadas ya fue vendida hace un instante.');
        }
        return redirect()->back()->withInput()->with('error', 'Error al procesar la operación.');
    }
}


    /**
     * Comprobante / Ticket del Boleto emitido.
     */
    public function show(Boleto $boleto)
    {
        $boleto->load(['venta.vendedor', 'pasajero', 'viaje.ruta.origen', 'viaje.ruta.destino', 'viaje.colectivo', 'asiento']);
        return view('boleteria.show', compact('boleto'));
    }

    /**
     * Anulación de venta (Libera el asiento).
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $venta = Venta::with('boletos')->findOrFail($id);
            $venta->update(['estado' => 'anulada']);

            // Eliminamos físicamente el boleto para liberar la restricción UNIQUE(viaje_id, asiento_id)
            // Opcional: si querés guardar historial de anulados, habría que ajustar el índice único en MySQL
            $venta->boletos()->delete();
        });

        return redirect()->route('boleteria.index')
            ->with('success', 'Venta anulada correctamente. La butaca volvió a quedar libre.');
    }
}
 
