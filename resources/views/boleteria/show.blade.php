@extends('template')

@section('title', 'Comprobante de Pasajes')

@section('content')
<style>
    /* 🖨️ REGLAS DE IMPRESIÓN: Oculta todo el entorno del sistema y deja solo los tickets */
    @media print {
        body * {
            visibility: hidden;
        }
        #zona-impresion, #zona-impresion * {
            visibility: visible;
        }
        #zona-impresion {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .ticket-item {
            page-break-inside: avoid;
            margin-bottom: 20px;
            border: 1px solid #d1d5db !important;
            box-shadow: none !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="max-w-lg mx-auto mt-4 space-y-6">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl text-sm font-bold text-center no-print">
            {{ session('success') }}
        </div>
    @endif

    <!-- Barra de acciones superior (Oculta al imprimir) -->
<div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-200 shadow-sm no-print">
    @can('venta.index')
        <a href="{{ route('boleteria.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900">
            ← Volver a Boletería
        </a>
    @else
   {{--      <a href="{{ route('pasajero.boletos.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900">
            ← Volver a Mis Pasajes
        </a> --}}
        <h2   class="text-sm font-bold text-blue-600 hover:text-blue-600">
             </h2>
    @endcan

    <button onclick="window.print()" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition">
        🖨️ Imprimir Tickets ({{ $boleto->venta->boletos->count() }})
    </button>
</div>


    <!-- 🎫 ZONA EXCLUSIVA DE IMPRESIÓN -->
    <div id="zona-impresion" class="space-y-6">
        @foreach($boleto->venta->boletos as $item)
            <div class="ticket-item bg-white rounded-3xl border border-gray-200 shadow-lg overflow-hidden">
                <div class="bg-blue-600 p-6 text-white flex justify-between items-center" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-blue-200">Transportes</span>
                        <h2 class="text-2xl font-black tracking-tight">El Gurí SRL</h2>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs text-blue-200">Boleto N°</span>
                        <span class="font-mono font-bold text-lg">#{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    <!-- Origen -> Destino -->
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-gray-400 block">Origen</span>
                            <span class="font-black text-gray-900 text-base">{{ $item->viaje->ruta->origen->nombre }}</span>
                        </div>
                        <span class="text-blue-600 font-black text-xl">➔</span>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase text-gray-400 block">Destino</span>
                            <span class="font-black text-gray-900 text-base">{{ $item->viaje->ruta->destino->nombre }}</span>
                        </div>
                    </div>

                    <!-- Datos del Pasajero y Servicio -->
                    <div class="grid grid-cols-2 gap-4 text-sm border-b border-dashed border-gray-200 pb-4">
                        <div>
                            <span class="text-xs text-gray-400 block">Pasajero Nominal</span>
                            <span class="font-bold text-gray-800">{{ $item->pasajero->apellido }}, {{ $item->pasajero->nombre }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Documento (DNI)</span>
                            <span class="font-bold text-gray-800">{{ $item->pasajero->dni }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Fecha y Hora Salida</span>
                            <span class="font-bold text-gray-800">{{ date('d/m/Y', strtotime($item->viaje->fecha)) }} • {{ date('H:i', strtotime($item->viaje->hora_salida)) }} hs</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Interno / Patente</span>
                            <span class="font-bold text-gray-800">{{ $item->viaje->colectivo->patente }}</span>
                        </div>
                    </div>

                    <!-- Butaca y Precio -->
                    <div class="flex justify-between items-center pt-1">
                        <div class="bg-yellow-50 border border-yellow-200 px-4 py-2 rounded-xl" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                            <span class="text-[10px] font-bold uppercase text-yellow-700 block">Butaca Asignada</span>
                            <span class="text-2xl font-black text-gray-900">N° {{ str_pad($item->asiento->numero, 2, '0', STR_PAD_LEFT) }} <small class="text-xs font-semibold text-gray-500">({{ $item->asiento->tipo }})</small></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 block">Tarifa Individual</span>
                            <span class="text-2xl font-black text-blue-600">${{ number_format($item->precio, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Auditoría de Emisión: Fecha, Hora, Boletero y Pagador -->
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-[11px] text-gray-500 space-y-1 text-center">
                        <div class="font-semibold text-gray-700">
                            Emitido el {{ date('d/m/Y', strtotime($boleto->venta->fecha_venta)) }} a las {{ date('H:i:s', strtotime($boleto->venta->fecha_venta)) }} hs
                        </div>
                        <div>
                            Atendido por: <strong class="text-gray-700">{{ $boleto->venta->vendedor->nombre }} {{ $boleto->venta->vendedor->apellido }}</strong>
                        </div>
                        <div>
                            Operación #{{ $boleto->venta->id }} • Pagado por {{ $boleto->venta->cliente->apellido }}, {{ $boleto->venta->cliente->nombre }} ({{ ucfirst($boleto->venta->metodo_pago) }})
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
