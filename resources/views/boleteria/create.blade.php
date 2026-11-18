@extends('template')

@section('title', 'Emisión de Pasaje')

@section('content')
<div class="max-w-5xl mx-auto mt-2">

    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('boleteria.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf
        <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

        <!-- 💺 COLUMNA IZQUIERDA (7 cols): MAPA INTERACTIVO DEL COLECTIVO -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100">
                <div>
                    <h2 class="font-black text-gray-800 text-lg">Seleccioná una Butaca</h2>
                    <p class="text-xs text-gray-500">Unidad {{ $viaje->colectivo->patente }} • {{ $viaje->colectivo->asientos->count() }} plazas</p>
                </div>
                <div class="flex space-x-3 text-xs font-bold">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-gray-100 border border-gray-300 inline-block"></span> Libre</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-100 border border-red-300 inline-block"></span> Vendido</span>
                </div>
            </div>

            <!-- Frente del Colectivo -->
            <div class="bg-gray-100 text-center py-2 rounded-t-2xl text-xs font-bold uppercase tracking-widest text-gray-500 mb-4 border border-gray-200">
                🛞 Cabina de Conducción (Frente)
            </div>

            <!-- Grilla de 4 columnas simulando pasillo central -->
            <div class="grid grid-cols-4 gap-3 max-w-md mx-auto">
                @foreach($viaje->colectivo->asientos as $asiento)
                    @php
                        $ocupado = in_array($asiento->id, $asientosOcupadosIds);
                        $inhabilitado = $asiento->estado !== 'habilitado';
                    @endphp

                    <label class="relative cursor-pointer select-none">
                        <input type="radio" name="asiento_id" value="{{ $asiento->id }}" class="peer sr-only" {{ ($ocupado || $inhabilitado) ? 'disabled' : '' }} required>
                        
                        <div class="p-3 rounded-xl border text-center transition
                            {{ $ocupado ? 'bg-red-50 border-red-200 text-red-400 cursor-not-allowed' : 
                               ($inhabilitado ? 'bg-gray-200 border-gray-300 text-gray-400 cursor-not-allowed' : 
                               'bg-gray-50 border-gray-200 text-gray-800 hover:border-blue-400 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md') }}">
                            <span class="block font-black text-base">{{ str_pad($asiento->numero, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="block text-[10px] uppercase tracking-wider opacity-75">
                                {{ $ocupado ? 'Ocupado' : $asiento->tipo }}
                            </span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 🧾 COLUMNA DERECHA (5 cols): DATOS DEL PASAJERO Y PAGO -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-between space-y-6">
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                    <span class="text-xs font-bold uppercase text-blue-600 block">Resumen del Servicio</span>
                    <h3 class="font-black text-gray-900 text-base mt-0.5">{{ $viaje->ruta->origen->nombre }} ➔ {{ $viaje->ruta->destino->nombre }}</h3>
                    <p class="text-xs text-gray-600 mt-1">📅 {{ date('d/m/Y', strtotime($viaje->fecha)) }} — 🕒 {{ date('H:i', strtotime($viaje->hora_salida)) }} hs</p>
                    <div class="mt-3 pt-2 border-t border-blue-200/60 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-600">Total a Cobrar:</span>
                        <span class="text-xl font-black text-blue-700">${{ number_format($viaje->precio, 2, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">DNI del Pasajero (Solo números)</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" pattern="[0-9]{7,10}" placeholder="Ej: 38456123" class="w-full border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}" class="w-full border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Teléfono (Opcional)</label>
                        <input type="tel" name="telefono" value="{{ old('telefono') }}" pattern="[0-9]{8,15}" placeholder="3764123456" class="w-full border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Método de Pago</label>
                        <select name="metodo_pago" class="w-full border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta Débito/Crédito</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex space-x-3">
                <a href="{{ route('boleteria.index') }}" class="w-1/3 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Volver</a>
                <button type="submit" class="w-2/3 bg-blue-600 text-white py-3 rounded-xl font-black uppercase tracking-wider text-sm hover:bg-blue-700 shadow-md transition">
                    Confirmar y Emitir
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
