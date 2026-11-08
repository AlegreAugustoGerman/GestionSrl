<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - El Gurí SRL</title>
    
   <!-- favicon + tailwind-->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  

    <!-- Google Font/Icons CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

 

    <style>
        body { font-family: "Poppins", sans-serif; background-color: #f3f4f6; }
        ::-webkit-scrollbar { width: 0px; color: transparent; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="leading-normal tracking-normal overflow-hidden h-screen w-screen">

    <!-- 🏗️ Contenedor Principal en Flex/Grid (Indestructible) -->
    <div class="flex h-screen w-screen bg-gray-100 overflow-hidden">

        <!-- 📟 Menú Lateral (Ancho fijo de 80px para los iconos compactos) -->
        <aside class="hidden md:block w-20 h-screen flex-shrink-0">
            @include('base.nav-menu')
        </aside>

        <!-- 📱 Menú Superior Móvil (Solo visible en pantallas chicas) -->
        <div class="md:hidden fixed top-0 left-0 right-0 h-16 bg-gray-900 flex items-center px-4 justify-between z-50 shadow-md">
            <span class="text-white font-bold tracking-wider">EL GURÍ SRL</span>
            <!-- Podés incluir un nav-menu-mobile compacto acá si lo requerís -->
        </div>

        <!-- 💻 Área de Contenido Principal (Ocupa todo el espacio restante de forma fluida) -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden pt-16 md:pt-0">
            
            <!-- 📋 Pizarron Interior con Scroll Independiente -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-100">
                
                <!-- 🎯 Encabezado del Pizarrón Dinámico -->
                <div class="w-full flex items-center justify-between pb-6 border-b border-gray-200 mb-6">
                    <div class="flex items-center space-x-3">
                        @if (url()->current() != url('dashboard'))
                            <a href="{{ url()->previous() }}" class="p-2 rounded-xl bg-white border border-gray-200 shadow-xs text-gray-700 hover:bg-gray-50 transition flex items-center justify-center">
                                <span class="material-symbols-outlined text-md">arrow_back</span>
                            </a>
                        @endif
                        <h1 class="text-2xl font-black text-gray-800 tracking-tight">@yield('title')</h1>
                    </div>

                    <!-- Perfil Rápido de Auditoría en Esquina Superior -->
                    <div class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 shadow-xs">
                        👤 {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
                    </div>
                </div>

                <!-- 🚀 Inyección Atómica del Contenido del CRUD -->
                <div class="w-full">
                    @yield('content')
                </div>

            </main>
        </div>

    </div>

</body>
</html>
