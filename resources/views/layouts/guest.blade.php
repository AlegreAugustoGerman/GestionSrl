<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>El Gurí SRL - Autenticación</title>
    
    {{-- <link rel="stylesheet" href="https://jsdelivr.net"/> --}}
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-900 antialiased bg-gray-100 min-h-screen w-screen overflow-hidden">

    <!-- 🏗️ Contenedor Pantalla Completa Dividida -->
    <div class="w-full h-screen flex flex-col md:flex-row">
        
        <!-- 🎨 Mitad Izquierda/Diagonal: Tu nueva imagen abstracta corporativa de caminos -->
        <div class="hidden md:block md:w-1/2 h-full bg-[#2563EB] relative overflow-hidden">
            <!-- Colocá la imagen generada en tu carpeta public/assets/img/bg-login.jpg -->
            <img src="{{ asset('assets/img/bg-login.jpg') }}" alt="Caminos El Gurí" class="w-full h-full object-cover">
            <!-- Capa sutil encima para asegurar el tono corporativo -->
            <div class="absolute inset-0 bg-blue-900 opacity-10"></div>
        </div>

        <!-- 📑 Mitad Derecha: Tu formulario limpio de alta legibilidad -->
        <div class="w-full md:w-1/2 h-full flex items-center justify-center bg-gray-50 px-4 sm:px-8 md:px-16 overflow-y-auto">
            <div class="w-full sm:max-w-md bg-white border border-gray-200 shadow-2xl rounded-2xl p-8 flex flex-col items-center">
                
                <!-- Componente del Logo Adaptado -->
                <div class="w-44 h-16 mb-4 flex items-center justify-center">
                    <a href="/" class="w-full h-full block">
                        <x-application-logo class="w-full h-full" />
                    </a>
                </div>

                <!-- Formulario Inyectado (Login, Registro, etc.) -->
                <div class="w-full text-base">
                    {{ $slot }}
                </div>
                
            </div>
        </div>

    </div>

</body>
</html>
