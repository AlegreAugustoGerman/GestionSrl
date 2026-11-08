@extends('template')

@section('title', 'Red de Rutas')

@section('content')
<div class="w-full space-y-6 mt-2">

    <!-- 📥 FORMULARIO DINÁMICO (Alta / Edición de Caminos) -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            {{ $rutaEdit ? '📝 Modificar Tramo de Conexión' : '🗺️ Trazar Nueva Ruta Comercial' }}
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

        <form action="{{ $rutaEdit ? route('rutas.update', $rutaEdit->id) : route('rutas.store') }}" method="POST" class="space-y-4">
            @csrf
            @if($rutaEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Localidad de Origen</label>
                    <select name="origen_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Seleccione terminal de partida...</option>
                        @foreach($localidades as $loc)
                            <option value="{{ $loc->id }}" {{ (old('origen_id', $rutaEdit?->origen_id) == $loc->id) ? 'selected' : '' }}>
                                {{ $loc->nombre }} ({{ $loc->provincia->nombre }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Localidad de Destino</label>
                    <select name="destino_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Seleccione terminal de arribo...</option>
                        @foreach($localidades as $loc)
                            <option value="{{ $loc->id }}" {{ (old('destino_id', $rutaEdit?->destino_id) == $loc->id) ? 'selected' : '' }}>
                                {{ $loc->nombre }} ({{ $loc->provincia->nombre }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Distancia Absoluta (Km)</label>
                    <input type="number" step="0.01" name="distancia" value="{{ old('distancia', $rutaEdit?->distancia) }}" placeholder="Ej: 300.50" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Duración Estimada (HH:MM:SS)</label>
                    <input type="text" name="duracion_estimada" value="{{ old('duracion_estimada', $rutaEdit?->duracion_estimada ?? '00:00:00') }}" placeholder="05:30:00" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Estado de la Ruta</label>
                    <select name="estado" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="activa" {{ old('estado', $rutaEdit?->estado) === 'activa' ? 'selected' : '' }}>Activa / Operable</option>
                        <option value="inactiva" {{ old('estado', $rutaEdit?->estado) === 'inactiva' ? 'selected' : '' }}>Inactiva / Bloqueada</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                @if($rutaEdit)
                    <a href="{{ route('rutas.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 text-sm font-bold transition">Cancelar Edición</a>
                @endif
                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 text-sm font-bold shadow-sm transition">
                    {{ $rutaEdit ? 'Actualizar Trayecto' : 'Trazar Red Comercial' }}
                </button>
            </div>
        </form>
    </div>

    <!-- 📊 TABLA DE LISTADO GENERAL -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">Líneas de Recorrido Habilitadas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-sm">
                <thead>
                    <tr class="text-gray-400 uppercase text-xs border-b bg-gray-50/50">
                        <th class="p-4">Origen ➔ Destino</th>
                        <th class="p-4">Distancia</th>
                        <th class="p-4">Duración Promedio</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-100">
                    @foreach($rutas as $ruta)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="p-4 font-bold text-gray-900">
                                {{ $ruta->origen->nombre }} <span class="text-blue-500 font-normal">➔</span> {{ $ruta->destino->nombre }}
                            </td>
                            <td class="p-4 font-semibold text-gray-500">{{ $ruta->distancia }} Km</td>
                            <td class="p-4 font-medium tracking-wide">⏱️ {{ $ruta->duracion_estimada }} hs</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                                    {{ $ruta->estado === 'activa' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    {{ $ruta->estado }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-3 font-semibold">
                                <a href="{{ route('rutas.edit', $ruta->id) }}" class="text-amber-600 hover:text-amber-800 transition">Editar</a>
                                <form action="{{ route('rutas.destroy', $ruta->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro desea remover esta ruta?')">
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
