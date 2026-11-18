@extends('template')

@section('title', 'Comprobante de Pasaje')

@section('content')
<div class="max-w-lg mx-auto mt-4">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl mb-4 text-sm font-bold text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- 🎫 DISEÑO DE TICKET DE EMBARQUE -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-blue-200">Transportes</span>
                <h2 class="text-2xl font-black tracking-tight">El Gurí SRL</h2>
            </div>
            <div class="text-right">
                <span class="block text-xs text-blue-200">Boleto N°</span>
                <span class="font-mono font-bold text-lg">#{{ str_pad($boleto->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <!-- Origen -> Destino -->
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
                <div>
                    <span class="text-[10px] font-bold uppercase text-gray-400 block">Origen</span>
                    <span class="font-black text-gray-900 text-base">{{ $boleto->viaje->ruta->origen->nombre }}</span>
                </div>
                <span class="text-blue-600 font-black text-xl">➔</span>
                <div class="text-right">
                    <span class="text-[10px] font-bold uppercase text-gray-400 block">Destino</span>
                    <span class="font-black text-gray-900 text-base">{{ $boleto->viaje->ruta->destino->nombre }}</span>
                </div>
            </div>

            <!-- Datos del Pasajero -->
            <div class="grid grid-cols-2 gap-4 text-sm border-b border-dashed border-gray-200 pb-4">
                <div>
                    <span class="text-xs text-gray-400 block">Pasajero</span>
                    <span class="font-bold text-gray-800">{{ $boleto->pasajero->apellido }}, {{ $boleto->pasajero->nombre }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Documento (DNI)</span>
                    <span class="font-bold text-gray-800">{{ $boleto->pasajero->dni }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Fecha y Hora Salida</span>
                    <span class="font-bold text-gray-800">{{ date('d/m/Y', strtotime($boleto->viaje->fecha)) }} • {{ date('H:i', strtotime($boleto->viaje->hora_salida)) }} hs</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Interno / Patente</span>
                    <span class="font-bold text-gray-800">{{ $boleto->viaje->colectivo->patente }}</span>
                </div>
            </div>

            <!-- Butaca y Precio Destacados -->
            <div class="flex justify-between items-center pt-1">
                <div class="bg-yellow-50 border border-yellow-200 px-4 py-2 rounded-xl">
                    <span class="text-[10px] font-bold uppercase text-yellow-700 block">Butaca Asignada</span>
                    <span class="text-2xl font-black text-gray-900">N° {{ str_pad($boleto->asiento->numero, 2, '0', STR_PAD_LEFT) }} <small class="text-xs font-semibold text-gray-500">({{ $boleto->asiento->tipo }})</small></span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400 block">Importe Abonado ({{ ucfirst($boleto->venta->metodo_pago) }})</span>
                    <span class="text-2xl font-black text-blue-600">${{ number_format($boleto->precio, 2, ',', '.') }}</span>
                </div>
            </div>

            <div class="text-[11px] text-gray-400 text-center pt-2">
                Emitido el {{ date('d/m/Y H:i', strtotime($boleto->venta->fecha_venta)) }} por {{ $boleto->venta->vendedor->nombre }} {{ $boleto->venta->vendedor->apellido }}
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('boleteria.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900">← Volver a Boletería</a>
            <button onclick="window.print()" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition">
                🖨️ Imprimir Ticket
            </button>
        </div>
    </div>
</div>
@endsection
