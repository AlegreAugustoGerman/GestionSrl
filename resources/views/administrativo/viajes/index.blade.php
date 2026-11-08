@extends('template')

@section('title', 'Cronograma de Viajes')

@section('content')
<div class="w-full space-y-6 mt-2">

    <!-- 📥 FORMULARIO DINÁMICO (Programar / Editar Salidas) -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            {{ $viajeEdit ? '📝 Modificar Parámetros de Salida' : '⏳ Programar Nueva Salida de Línea' }}
        </h2>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-xl mb-4 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-4 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl mb-4 text-sm">
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $viajeEdit ? route('viajes.update', $viajeEdit->id) : route('viajes.store') }}" method="POST" class="space-y-4">
            @csrf
            @if($viajeEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Ruta Comercial</label>
                    <select name="ruta_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Seleccione trayecto...</option>
                        @foreach($rutas as $ruta)
                            <option value="{{ $ruta->id }}" {{ (old('ruta_id', $viajeEdit?->ruta_id) == $ruta->id) ? 'selected' : '' }}>
                                {{ $ruta->origen->nombre }} ➔ {{ $ruta->destino->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Colectivo Asignado</label>
                    <select name="colectivo_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Seleccione unidad interna...</option>
                        @foreach($colectivos as $bus)
                            <option value="{{ $bus->id }}" {{ (old('colectivo_id', $viajeEdit?->colectivo_id) == $bus->id) ? 'selected' : '' }}>
                                {{ $bus->modelo->marca->nombre }} - Patente: {{ $bus->patente }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Conductor Responsable</label>
                    <select name="chofer_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Seleccione chofer habilitado...</option>
                        @foreach($choferes as $chofer)
                            <option value="{{ $chofer->id }}" {{ (old('chofer_id', $viajeEdit?->choferes->first()?->id) == $chofer->id) ? 'selected' : '' }}>
                                {{ $chofer->user->apellido }}, {{ $chofer->user->nombre }} (Lic: {{ $chofer->licencia }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Fecha de Salida</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $viajeEdit?->fecha ?? date('Y-m-d')) }}" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Hora Partida</label>
                    <input type="time" name="hora_salida" value="{{ old('hora_salida', $viajeEdit?->hora_salida ? date('H:i', strtotime($viajeEdit->hora_salida)) : '') }}" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Hora Arribo (Est.)</label>
                    <input type="time" name="hora_llegada" value="{{ old('hora_llegada', $viajeEdit?->hora_llegada ? date('H:i', strtotime($viajeEdit->hora_llegada)) : '') }}" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Precio Pasaje ($)</label>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio', $viajeEdit?->precio) }}" placeholder="0.00" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Estado de Salida</label>
                    <select name="estado" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="programado" {{ old('estado', $viajeEdit?->estado) === 'programado' ? 'selected' : '' }}>Programado</option>
                        <option value="en curso" {{ old('estado', $viajeEdit?->estado) === 'en curso' ? 'selected' : '' }}>En Curso / En Ruta</option>
                        <option value="finalizado" {{ old('estado', $viajeEdit?->estado) === 'finalizado' ? 'selected' : '' }}>Finalizado / Arribado</option>
                        <option value="cancelado" {{ old('estado', $viajeEdit?->estado) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                @if($viajeEdit)
                    <a href="{{ route('viajes.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 text-sm font-bold transition">Cancelar Edición</a>
                @endif
                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 text-sm font-bold shadow-sm transition">
                    {{ $viajeEdit ? 'Actualizar Salida' : 'Programar Salida' }}
                </button>
            </div>
        </form>
    </div>

    <!-- 📊 TABLA DEL CRONOGRAMA DE SALIDAS -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">Cronograma General de Salidas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-sm">
                <thead>
                    <tr class="text-gray-400 uppercase text-xs border-b bg-gray-50/50">
                        <th class="p-4">Servicio / Recorrido</th>
                        <th class="p-4">Fecha y Horarios</th>
                        <th class="p-4">Interno / Conductor</th>
                        <th class="p-4">Precio</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-100">
                    @foreach($viajes as $viaje)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="p-4 font-bold text-gray-900">
                                {{ $viaje->ruta->origen->nombre }} <span class="text-blue-500 font-normal">➔</span> {{ $viaje->ruta->destino->nombre }}
                            </td>
                            <td class="p-4 text-xs font-medium space-y-0.5">
                                <div class="text-gray-900 font-bold">📅 {{ date('d/m/Y', strtotime($viaje->fecha)) }}</div>
                                <div class="text-gray-500">🛫 Salida: {{ date('H:i', strtotime($viaje->hora_salida)) }} hs</div>
                                <div class="text-gray-500">🛬 Arribo: {{ date('H:i', strtotime($viaje->hora_llegada)) }} hs</div>
                            </td>
                            <td class="p-4 text-xs space-y-0.5">
                                <div class="font-bold text-gray-700">🚌 Bus: {{ $viaje->colectivo->patente }}</div>
                                <div class="text-gray-500 font-medium">👤 Cond: {{ $viaje->choferes->first()?->user->apellido ?? 'No asignado' }}</div>
                            </td>
                            <td class="p-4 font-bold text-gray-900 text-base">${{ number_format($viaje->precio, 2, ',', '.') }}</td>
                            <td class="p-4">

                           {{--  --}} 

                        
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                                    {{ $viaje->estado === 'programado' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($viaje->estado === 'en curso' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : ($viaje->estado === 'finalizado' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200')) }}">
                                    {{ $viaje->estado }}
                                </span>
                            </td>
                            <!-- ⚙️ COLUMNA DE BOTONES DE ACCIÓN COMPLETA -->
                            <td class="p-4 text-center space-x-3 font-semibold text-sm">
                                <a href="{{ route('viajes.edit', $viaje->id) }}" class="text-amber-600 hover:text-amber-800 transition">Editar</a>
                                <form action="{{ route('viajes.destroy', $viaje->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro desea cancelar y remover esta salida?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
