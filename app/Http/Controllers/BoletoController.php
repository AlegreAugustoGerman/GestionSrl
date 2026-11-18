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
            'viaje_id'    => 'required|exists:viajes,id',
            'asiento_id'  => 'required|exists:asientos,id',
            'dni'         => 'required|numeric|digits_between:7,10',
            'nombre'      => 'required|string|max:50',
            'apellido'    => 'required|string|max:50',
            'telefono'    => 'nullable|numeric|digits_between:8,15',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia',
        ], [
            'asiento_id.required' => 'Debe seleccionar una butaca disponible en el mapa.',
            'dni.numeric'         => 'El DNI debe contener únicamente números sin puntos.',
        ]);

        $viaje = Viaje::findOrFail($request->viaje_id);

        // Verificamos previamente que el asiento pertenezca realmente al colectivo de este viaje
        $asientoValido = $viaje->colectivo->asientos()
            ->where('id', $request->asiento_id)
            ->where('estado', 'habilitado')
            ->exists();

        if (!$asientoValido) {
            return redirect()->back()->with('error', 'La butaca seleccionada no pertenece a esta unidad o está inhabilitada.');
        }

        try {
            // 🔒 INICIO DE TRANSACCIÓN ATÓMICA
            $boleto = DB::transaction(function () use ($request, $viaje) {
                
                // 1. Buscar al pasajero por DNI; si no existe en el sistema, lo registramos al vuelo
                $pasajero = User::firstOrCreate(
                    ['dni' => $request->dni],
                    [
                        'nombre'   => ucwords(strtolower($request->nombre)),
                        'apellido' => ucwords(strtolower($request->apellido)),
                        'email'    => $request->dni . '@://guri.com', // Email temporal único si compra en ventanilla
                        'telefono' => $request->telefono,
                        'password' => Hash::make($request->dni),
                    ]
                );

                // Aseguramos que tenga el rol básico si fue recién creado
                if ($pasajero->wasRecentlyCreated) {
                    $pasajero->assignRole('pasajero');
                }

                // 2. Creamos la cabecera de la Venta (Auditoría comercial)
                $venta = Venta::create([
                    'cliente_id'  => $pasajero->id,
                    'vendedor_id' => Auth::id(), // El boletero logueado actualmente
                    'fecha_venta' => now(),
                    'total'       => $viaje->precio,
                    'metodo_pago' => $request->metodo_pago,
                    'estado'      => 'completada',
                ]);

                // 3. Emitimos el Boleto. Si otro operador lo vendió 1ms antes, MySQL lanzará error 1062 acá
                return $venta->boletos()->create([
                    'user_id'    => $pasajero->id,
                    'viaje_id'   => $viaje->id,
                    'asiento_id' => $request->asiento_id,
                    'precio'     => $viaje->precio,
                    'estado'     => 'vendido',
                ]);
            });

            // Redirigimos directo al comprobante listo para imprimir
            return redirect()->route('boleteria.show', $boleto->id)
                ->with('success', '¡Pasaje emitido con éxito! Butaca bloqueada y confirmada.');

        } catch (QueryException $e) {
            // Código 23000 / 1062 = Violación de restricción UNIQUE (viaje_id, asiento_id)
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', '⚠️ ¡Asiento ocupado! Otro operador acaba de vender esta butaca hace un instante. Por favor elegí otra.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error inesperado al procesar la transacción.');
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
 
