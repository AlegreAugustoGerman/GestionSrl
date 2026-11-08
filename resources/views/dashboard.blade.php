@extends('template')

@section('title', 'Panel de Control')

@section('content')
<div class="w-full p-4 space-y-6">
    <!-- Encabezado de Bienvenida -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold">¡Hola, {{ Auth::user()->nombre }}! 👋</h2>
            <p class="text-blue-100 text-sm mt-1">Bienvenido al Sistema de Gestión de Pasajes de Transportes El Gurí SRL.</p>
        </div>
        <div class="bg-white bg-opacity-15 px-4 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider">
            Rol actual: 
            @if(Auth::user()->hasRole('administrativo')) Administrativo
            @elseif(Auth::user()->hasRole('boletero')) Personal de Boletería
            @elseif(Auth::user()->hasRole('gerente')) Gerencia Comercial
            @else Pasajero
            @endif
        </div>
    </div>

    <!-- 🗺️ MATRIZ DE MÓDULOS DEL SISTEMA -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Módulo 1: Flota (Exclusivo Administrativo) -->
        @can('colectivo.index')
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <x-application-logo class="w-14 h-auto" />
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-lg">Infraestructura</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Control de Flota</h3>
                <p class="text-gray-500 text-xs">Administración de colectivos, estados operativos y auditoría interna de asientos.</p>
            </div>
            <a href="{{ route('colectivos.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 pt-2">
                Gestionar unidades <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endcan

        <!-- Módulo 2: Personal (Exclusivo Administrativo) -->
        @can('chofer.index')
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-3xl">🪪</span>
                    <span class="bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-lg">Recursos Humanos</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Conductores y Legajos</h3>
                <p class="text-gray-500 text-xs">Validación de licencias nacionales únicas, estados de actividad y asignación laboral.</p>
            </div>
            <a href="{{ route('choferes.index') }}" class="text-sm font-bold text-purple-600 hover:text-purple-800 flex items-center gap-1 pt-2">
                Gestionar choferes <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endcan

        <!-- Módulo 3: Rutas (Exclusivo Administrativo) -->
        @can('ruta.index')
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-3xl">🗺️</span>
                    <span class="bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">Logística</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Red de Rutas</h3>
                <p class="text-gray-500 text-xs">Trazado de trayectos entre localidades, control de distancias y tiempos estimados.</p>
            </div>
            <a href="{{ route('rutas.index') }}" class="text-sm font-bold text-green-600 hover:text-green-800 flex items-center gap-1 pt-2">
                Gestionar caminos <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endcan

        <!-- Módulo 4: Viajes (Administrativo, Boletero y Gerente) -->
        @can('viaje.index')
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-3xl">⏳</span>
                    <span class="bg-amber-50 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-lg">Operaciones</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Cronograma de Viajes</h3>
                <p class="text-gray-500 text-xs">Programación de salidas concretas, asignación de buses, choferes y control de precios.</p>
            </div>
            <a href="{{ route('viajes.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-800 flex items-center gap-1 pt-2">
                Ver cronogramas <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endcan

        <!-- Módulo 5: Boletería (Boletero, Administrativo y Gerente) -->
        @can('venta.store')
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-3xl">🎫</span>
                    <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-lg">Comercial</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Terminal de Boletería</h3>
                <p class="text-gray-500 text-xs">Punto de venta y emisión atómica de pasajes con mapa de asientos libres en tiempo real.</p>
            </div>
            <a href="{{ route('boleteria.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1 pt-2">
                Abrir boletería <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endcan

        <!-- Módulo 6: Reportes (Gerencia y Administrativo de control) -->
        @can('reporte.recaudacion')
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-3xl">📊</span>
                    <span class="bg-red-50 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg">Auditoría</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Reportes de Recaudación</h3>
                <p class="text-gray-500 text-xs">Consultas consolidadas de ingresos por viaje, períodos fiscales e historial de operaciones.</p>
            </div>
            <a href="#" class="text-sm font-bold text-red-600 hover:text-red-800 flex items-center gap-1 pt-2">
                Ver auditorías <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endcan

        <!-- 👤 MÓDULO EXCLUSIVO DE PASAJEROS (Visible si no es empleado de control) -->
        @if(!Auth::user()->hasRole('administrativo') && !Auth::user()->hasRole('boletero') && !Auth::user()->hasRole('gerente'))
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between h-48 col-span-1 md:col-span-2 lg:col-span-3">
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-3xl">🎟️</span>
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-lg">Mis Viajes</span>
                </div>
                <h3 class="font-bold text-lg text-gray-800">Historial de Pasajes</h3>
                <p class="text-gray-500 text-xs">Consultá tus boletos adquiridos para las líneas de El Gurí SRL, horarios de embarque y números de butacas asignadas.</p>
            </div>
            <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 pt-2">
                Ver mis boletos <span class="material-symbols-outlined text-sm translate-y-0.5">arrow_forward</span>
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
