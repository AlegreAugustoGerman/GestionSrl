@extends('template')

@section('title', 'Control de Personal')

@section('content')
<div class="w-full space-y-6 mt-2">

    <!-- 📥 FORMULARIO DINÁMICO (Habilitar / Editar Chofer) -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
            {{ $choferEdit ? '📝 Modificar Legajo de Conductor' : '🪪 Habilitar Nuevo Conductor' }}
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

        <form action="{{ $choferEdit ? route('choferes.update', $choferEdit->id) : route('choferes.store') }}" method="POST" class="space-y-4">
            @csrf
            @if($choferEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Seleccionar Usuario</label>
                    @if($choferEdit)
                        <!-- Bloqueado en edición para proteger la integridad 1:1 -->
                        <input type="text" class="w-full border border-gray-200 bg-gray-50 p-2.5 rounded-xl text-gray-500 font-medium" value="{{ $choferEdit->user->nombre }} {{ $choferEdit->user->apellido }} (DNI: {{ $choferEdit->user->dni }})" disabled>
                    @else
                        <select name="user_id" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                            <option value="">Seleccione un usuario para promover...</option>
                            @foreach($usuariosDisponibles as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->apellido }}, {{ $user->nombre }} (DNI: {{ $user->dni }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Número de Licencia Única</label>
                    <input type="text" name="licencia" value="{{ old('licencia', $choferEdit?->licencia) }}" placeholder="Ej: N-37128377" class="w-full border border-gray-300 p-2.5 rounded-xl uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Estado Laboral</label>
                    <select name="estado" class="w-full border border-gray-300 p-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        <option value="activo" {{ old('estado', $choferEdit?->estado) === 'activo' ? 'selected' : '' }}>Activo / Habilitado</option>
                        <option value="inactivo" {{ old('estado', $choferEdit?->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        <option value="suspendido" {{ old('estado', $choferEdit?->estado) === 'suspendido' ? 'selected' : '' }}>Suspendido / Licencia Médica</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                @if($choferEdit)
                    <a href="{{ route('choferes.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 text-sm font-bold transition">Cancelar Edición</a>
                @endif
                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 text-sm font-bold shadow-sm transition">
                    {{ $choferEdit ? 'Actualizar Legajo' : 'Habilitar Conductor' }}
                </button>
            </div>
        </form>
    </div>

    <!-- 📊 TABLA DE LISTADO GENERAL DE CONDUCTORES -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">Conductores Registrados</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left text-sm">
                <thead>
                    <tr class="text-gray-400 uppercase text-xs border-b bg-gray-50/50">
                        <th class="p-4">Conductor</th>
                        <th class="p-4">DNI</th>
                        <th class="p-4">N° Licencia</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-100">
                    @foreach($choferes as $chofer)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="p-4 font-bold text-gray-900">
                                {{ $chofer->user->apellido }}, {{ $chofer->user->nombre }}
                            </td>
                            <td class="p-4 font-medium text-gray-500">{{ $chofer->user->dni }}</td>
                            <td class="p-4 font-bold tracking-wider text-blue-600">{{ $chofer->licencia }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                                    {{ $chofer->estado === 'activo' ? 'bg-green-50 text-green-700 border border-green-200' : ($chofer->estado === 'inactivo' ? 'bg-gray-50 text-gray-700 border border-gray-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200') }}">
                                    {{ $chofer->estado }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-3 font-semibold">
                                <a href="{{ route('choferes.edit', $chofer->id) }}" class="text-amber-600 hover:text-amber-800 transition">Editar</a>
                                <form action="{{ route('choferes.destroy', $chofer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro desea revocar la habilitación de este conductor?')">
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
