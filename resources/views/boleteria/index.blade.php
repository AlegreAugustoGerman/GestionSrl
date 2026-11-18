@extends('template')

@section('title', 'Terminal de Boletería')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 mt-2">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- 🚌 VIAJES DISPONIBLES PARA VENTA -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="font-black text-gray-800 text-base uppercase tracking-wider">Servicios Disponibles</h2>
                <p class="text-xs text-gray-500">Seleccioná un viaje para abrir el mapa de butacas</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-sm">
                <thead>
                    <tr class="text-gray-400 uppercase text-xs border-b bg-gray-50/50">
                        <th class="p-4">Trayecto</th>
                        <th class="p-4">Salida</th>
                        <th class="p-4">Unidad</th>
                        <th class="p-4">Tarifa</th>
                        <th class="p-4 text-center">Operación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($viajes as $viaje)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="p-4 font-bold text-gray-900">
                                {{ $viaje->ruta->origen->nombre }} <span class="text-blue-600">➔</span> {{ $viaje->ruta->destino->nombre }}
                            </td>
                            <td class="p-4 text-xs">
                                <span class="font-bold block text-gray-800">{{ date('d/m/Y', strtotime($viaje->fecha)) }}</span>
                                <span class="text-gray-500">{{ date('H:i', strtotime($viaje->hora_salida)) }} hs</span>
                            </td>
                            <td class="p-4 text-xs font-semibold text-gray-600">
                                Interno {{ $viaje->colectivo->patente }}
                            </td>
                            <td class="p-4 font-black text-blue-600 text-base">
                                ${{ number_format($viaje->precio, 2, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('boleteria.create', $viaje->id) }}" class="inline-flex items-center gap-1 bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-700 shadow-sm transition">
                                    <span>Seleccionar Butaca</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 font-medium">No hay viajes programados próximos para la venta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🧾 ÚLTIMAS OPERACIONES EN MOSTRADOR -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Últimos Boletos Emitidos</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-xs">
                <thead>
                    <tr class="text-gray-400 uppercase border-b">
                        <th class="p-3">Ticket #</th>
                        <th class="p-3">Pasajero</th>
                        <th class="p-3">Viaje / Asiento</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-600">
                    @foreach($ultimasVentas as $venta)
                        @foreach($venta->boletos as $boleto)
                        <tr>
                            <td class="p-3 font-bold text-gray-900">#{{ str_pad($boleto->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-3">{{ $venta->cliente->apellido }}, {{ $venta->cliente->nombre }} (DNI: {{ $venta->cliente->dni }})</td>
                            <td class="p-3 font-medium">
                                {{ $boleto->viaje->ruta->origen->nombre }} ➔ {{ $boleto->viaje->ruta->destino->nombre }} 
                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold ml-1">Butaca {{ $boleto->asiento->numero }}</span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded font-bold uppercase {{ $venta->estado === 'completada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $venta->estado }}
                                </span>
                            </td>
                            <td class="p-3 text-center space-x-2">
                                <a href="{{ route('boleteria.show', $boleto->id) }}" class="text-blue-600 font-bold hover:underline">Ver Ticket</a>
                                @if($venta->estado === 'completada')
                                <form action="{{ route('boleteria.destroy', $venta->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Anular esta venta y liberar la butaca?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 font-bold hover:underline">Anular</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
