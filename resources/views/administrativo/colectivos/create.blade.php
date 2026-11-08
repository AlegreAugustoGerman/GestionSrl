@extends('template')

@section('title', 'Alta de Colectivo')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Registrar Nueva Unidad</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('colectivos.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-gray-700 font-medium mb-1">Patente (Dominio)</label>
            <input type="text" name="patente" value="{{ old('patente') }}" placeholder="Ej: AB123CD" class="w-full border p-2 rounded-md uppercase" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Modelo / Plantilla de Distribución</label>
            <select name="modelo_id" class="w-full border p-2 rounded-md" required>
                <option value="">Seleccione una opción...</option>
                @foreach($modelos as $modelo)
                    <option value="{{ $modelo->id }}">{{ $modelo->marca->nombre }} - {{ $modelo->nombre }} ({{ $modelo->capacidad_asientos }} asientos)</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Año de Fabricación</label>
                <input type="number" name="anio" value="{{ old('anio', date('Y')) }}" class="w-full border p-2 rounded-md" required>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1">Estado Operativo</label>
                <select name="estado" class="w-full border p-2 rounded-md" required>
                    <option value="activo">Activo</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="baja">Baja</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('colectivos.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 transition">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold transition">Guardar y Crear Asientos</button>
        </div>
    </form>
</div>
@endsection
