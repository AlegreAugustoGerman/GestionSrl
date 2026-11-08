@extends('template')

@section('title', 'Gestión de Flota')

@section('content')
<div class="w-full space-y-6 mt-2">

    <!-- 📥 FORMULARIO DINÁMICO (Alta / Edición en la misma pantalla) -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            {{ $colectivoEdit ? '📝 Modificar Unidad Financiera' : '🚌 Registrar Nueva Unidad de Flota' }}
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

        <!-- Cambia la ruta y el método automáticamente según el estado -->
        <form action="{{ $colectivoEdit ? route('colectivos.update', $colectivoEdit->id) : route('colectivos.store') }}" method="POST" class="space-y-4">
            @csrf
            @if($colectivoEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Patente / Dominio</label>
                    <input type="text" name="patente" value="{{ old('patente', $colectivoEdit?->patente) }}" placeholder="Ej: AB123CD" class="w-full border border-gray-300 p-2.5 rounded-xl uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Modelo / Capacidad Fija</label>
                    <select name="modelo_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="">Seleccione el modelo corporativo...</option>
                        @foreach($modelos as $modelo)
                            <option value="{{ $modelo->id }}" {{ (old('modelo_id', $colectivoEdit?->modelo_id) == $modelo->id) ? 'selected' : '' }}>
                                {{ $modelo->marca->nombre }} - {{ $modelo->nombre }} ({{ $modelo->capacidad_asientos }} asientos)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Año de Fabricación</label>
                    <input type="number" name="anio" value="{{ old('anio', $colectivoEdit?->anio ?? date('Y')) }}" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Estado de Operación</label>
                    <select name="estado" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="activo" {{ old('estado', $colectivoEdit?->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="mantenimiento" {{ old('estado', $colectivoEdit?->estado) === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="baja" {{ old('estado', $colectivoEdit?->estado) === 'baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                @if($colectivoEdit)
                    <a href="{{ route('colectivos.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 text-sm font-bold transition">Cancelar Edición</a>
                @endif
                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 text-sm font-bold shadow-sm transition">
                    {{ $colectivoEdit ? 'Actualizar Cambios' : 'Guardar y Fabricar Asientos' }}
                </button>
            </div>
        </form>
    </div>

    <!-- 📊 TABLA DE LISTADO GENERAL -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">Unidades Activas en el Sistema</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-sm">
                <thead>
                    <tr class="text-gray-400 uppercase text-xs border-b bg-gray-50/50">
                        <th class="p-4">Patente</th>
                        <th class="p-4">Marca / Distribución</th>
                        <th class="p-4">Año</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones Operativas</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-100">
                    @foreach($colectivos as $colectivo)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="p-4 font-bold text-gray-900 tracking-wide">{{ $colectivo->patente }}</td>
                            <td class="p-4 text-gray-500">{{ $colectivo->modelo->marca->nombre }} - {{ $colectivo->modelo->nombre }}</td>
                            <td class="p-4 font-medium">{{ $colectivo->anio }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                                    {{ $colectivo->estado === 'activo' ? 'bg-green-50 text-green-700 border border-green-200' : ($colectivo->estado === 'mantenimiento' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                    {{ $colectivo->estado }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-3 font-semibold">
                                <a href="{{ route('colectivos.edit', $colectivo->id) }}" class="text-amber-600 hover:text-amber-800 transition">Editar</a>
                                <form action="{{ route('colectivos.destroy', $colectivo->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro desea eliminar esta unidad?')">
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
