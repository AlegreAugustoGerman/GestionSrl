<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Viaje;
use App\Models\Venta;
use App\Models\Colectivo;
use App\Models\Chofer;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // Rango de fechas por defecto: el mes corriente
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin    = $request->input('fecha_fin', now()->endOfMonth()->toDateString());

        // 1. Recaudación por Período (Ventas completadas en el rango)
        $ventasPeriodo = Venta::with(['cliente', 'vendedor', 'boletos.viaje.ruta.origen', 'boletos.viaje.ruta.destino'])
            ->where('estado', 'completada')
            ->whereBetween('fecha_venta', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->latest('fecha_venta')
            ->get();

        $totalRecaudadoPeriodo = $ventasPeriodo->sum('total');

        // 2. Recaudación desglosada por Viaje Concreto
        $recaudacionPorViaje = Viaje::with(['ruta.origen', 'ruta.destino', 'colectivo'])
            ->withCount(['boletos as boletos_vendidos' => function ($query) {
                $query->where('estado', 'vendido');
            }])
            ->withSum(['boletos as total_recaudado' => function ($query) {
                $query->where('estado', 'vendido');
            }], 'precio')
            ->orderByDesc('fecha')
            ->get();

        // 3. Reporte operativo: Viajes por Colectivo y por Chofer
        $viajesPorColectivo = Colectivo::with('modelo.marca')->withCount('viajes')->get();
        $viajesPorChofer    = Chofer::with('user')->withCount('viajes')->get();

        return view('gerencia.reportes.index', compact(
            'fechaInicio',
            'fechaFin',
            'ventasPeriodo',
            'totalRecaudadoPeriodo',
            'recaudacionPorViaje',
            'viajesPorColectivo',
            'viajesPorChofer'
        ));
    }
}
