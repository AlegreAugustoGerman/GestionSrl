@extends('template')

@section('title', 'Mis Pasajes')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 mt-2">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    @forelse($misBoletos as $boleto)
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase text-blue-600">Ticket #{{ str_pad($boleto->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-md
                        {{ $boleto->embarque === 'embarcado' ? 'bg-green-100 text-green-700' : ($boleto->embarque === 'ausente' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $boleto->embarque }}
                    </span>
                </div>
                <h3 class="font-black text-gray-900 text-lg">{{ $boleto->viaje->ruta->origen->nombre }} ➔ {{ $boleto->viaje->ruta->destino->nombre }}</h3>
                <p class="text-xs text-gray-500">
                    📅 {{ date('d/m/Y', strtotime($boleto->viaje->fecha)) }} • 🕒 {{ date('H:i', strtotime($boleto->viaje->hora_salida)) }} hs • 🚌 Interno {{ $boleto->viaje->colectivo->patente }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-end">
                <div class="bg-yellow-50 border border-yellow-200 px-3 py-1.5 rounded-xl text-center">
                    <span class="text-[10px] font-bold uppercase text-yellow-700 block">Butaca</span>
                    <span class="font-black text-gray-900 text-base">N° {{ str_pad($boleto->asiento->numero, 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                <!-- Botón Reimprimir Ticket -->
                <a href="{{ route('pasajero.boletos.show', $boleto->id) }}" class="bg-gray-900 text-white px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition">
                    🖨️ Reimprimir
                </a>

                <!-- Botón Reportar que no puede viajar -->
                @if($boleto->embarque === 'pendiente')
                    <form action="{{ route('mis-boletos.ausencia', $boleto->id) }}" method="POST" onsubmit="return confirm('¿Confirmás que no vas a poder abordar este viaje?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-red-50 text-red-600 border border-red-200 px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-red-100 transition">
                            No puedo viajar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white p-10 rounded-2xl border border-gray-200 text-center text-gray-500 text-sm">
            Todavía no tenés boletos adquiridos en tu historial.
        </div>
    @endforelse
</div>
@endsection
