@extends('template')

@section('title', 'Reportes Gerenciales')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 mt-2">

    <!-- 📅 FILTRO POR PERÍODO Y KPI PRINCIPAL (Fila 1) -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('gerencia.reportes.index') }}" method="GET" class="flex flex-wrap items-end gap-3 w-full md:w-auto">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="border border-gray-300 p-2 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Hasta</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="border border-gray-300 p-2 rounded-xl text-sm">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition">
                Filtrar Período
            </button>
        </form>

        <div class="bg-emerald-50 border border-emerald-200 px-6 py-3 rounded-2xl text-right w-full md:w-auto">
            <span class="text-xs font-bold uppercase text-emerald-700 block">Recaudación del Período</span>
            <span class="text-2xl font-black text-emerald-900">${{ number_format($totalRecaudadoPeriodo, 2, ',', '.') }}</span>
        </div>
    </div>

    <!-- 📈 GRÁFICOS GERENCIALES (Fila 2 independiente) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-4">Ingresos por Servicio ($)</h3>
            <div class="h-64">
                <canvas id="chartRecaudacion"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider mb-4">Asistencia / Embarque</h3>
            <div class="h-64 flex justify-center">
                <canvas id="chartEmbarque"></canvas>
            </div>
        </div>
    </div>


        <div class="bg-emerald-50 border border-emerald-200 px-6 py-3 rounded-2xl text-right w-full md:w-auto">
            <span class="text-xs font-bold uppercase text-emerald-700 block">Recaudación del Período</span>
            <span class="text-2xl font-black text-emerald-900">${{ number_format($totalRecaudadoPeriodo, 2, ',', '.') }}</span>
        </div>
    </div>

    <!-- 💰 RECAUDACIÓN POR VIAJE CONCRETO -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Recaudación y Ocupación por Viaje</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-sm">
                <thead>
                    <tr class="text-gray-400 uppercase text-xs border-b">
                        <th class="p-4">Fecha / Servicio</th>
                        <th class="p-4">Unidad</th>
                        <th class="p-4 text-center">Boletos Vendidos</th>
                        <th class="p-4 text-right">Total Recaudado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($recaudacionPorViaje as $viaje)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">
                                <span class="font-bold text-gray-900 block">{{ $viaje->ruta->origen->nombre }} ➔ {{ $viaje->ruta->destino->nombre }}</span>
                                <span class="text-xs text-gray-500">{{ date('d/m/Y', strtotime($viaje->fecha)) }} • {{ date('H:i', strtotime($viaje->hora_salida)) }} hs</span>
                            </td>
                            <td class="p-4 text-xs font-semibold text-gray-600">{{ $viaje->colectivo->patente }}</td>
                            <td class="p-4 text-center">
                                <span class="bg-blue-50 text-blue-700 font-bold px-2.5 py-1 rounded-lg text-xs">
                                    {{ $viaje->boletos_vendidos }} / {{ $viaje->colectivo->modelo->capacidad_asientos }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-black text-gray-900">
                                ${{ number_format($viaje->total_recaudado ?? 0, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🚌 Y 🪪 ACTIVIDAD POR COLECTIVO Y POR CHOFER -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Viajes por Colectivo</h3>
            </div>
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach($viajesPorColectivo as $bus)
                    <li class="p-4 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-gray-900 block">{{ $bus->patente }}</span>
                            <span class="text-xs text-gray-500">{{ $bus->modelo->marca->nombre }} {{ $bus->modelo->nombre }}</span>
                        </div>
                        <span class="bg-gray-100 text-gray-800 font-black px-3 py-1 rounded-xl text-xs">{{ $bus->viajes_count }} viajes</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Viajes por Chofer</h3>
            </div>
            <ul class="divide-y divide-gray-100 text-sm">
                @foreach($viajesPorChofer as $chofer)
                    <li class="p-4 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-gray-900 block">{{ $chofer->user->apellido }}, {{ $chofer->user->nombre }}</span>
                            <span class="text-xs text-gray-500">Licencia: {{ $chofer->licencia }}</span>
                        </div>
                        <span class="bg-gray-100 text-gray-800 font-black px-3 py-1 rounded-xl text-xs">{{ $chofer->viajes_count }} viajes</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>

<script>
 
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Gráfico de Barras: Recaudación por Viaje
        const ctxRec = document.getElementById('chartRecaudacion').getContext('2d');
        new Chart(ctxRec, {
            type: 'bar',
            data: {
                labels: {!! json_encode($recaudacionPorViaje->map(fn($v) => $v->ruta->origen->nombre . ' - ' . $v->ruta->destino->nombre . ' (' . date('d/m', strtotime($v->fecha)) . ')')) !!},
                datasets: [{
                    label: 'Recaudación ($)',
                    data: {!! json_encode($recaudacionPorViaje->map(fn($v) => $v->total_recaudado ?? 0)) !!},
                    backgroundColor: '#2563EB',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // 2. Gráfico de Dona: Control de Embarque Global
        const ctxEmb = document.getElementById('chartEmbarque').getContext('2d');
        new Chart(ctxEmb, {
            type: 'doughnut',
            data: {
                labels: ['Embarcados', 'Pendientes', 'Ausentes'],
                datasets: [{
                    data: [
                        {{ \App\Models\Boleto::where('embarque', 'embarcado')->count() }},
                        {{ \App\Models\Boleto::where('embarque', 'pendiente')->count() }},
                        {{ \App\Models\Boleto::where('embarque', 'ausente')->count() }}
                    ],
                    backgroundColor: ['#16A34A', '#FBBF24', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
 
</script>

@endsection