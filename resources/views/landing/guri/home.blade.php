<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportes El Gurí SRL - Inicio</title>
    
 <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" /> <!--Replace with your tailwind.css once created-->

    <!-- Google Font/Icons CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


     


    
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 leading-normal tracking-normal">

    <!-- 🧭 Navbar Simple -->
    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <!-- Llamada al componente nativo de Blade sin romper atributos -->
                <x-application-logo class="w-14 h-auto" />
                <span class="text-xl font-black text-blue-700 tracking-tight">El Gurí <span class="text-gray-800 font-medium">SRL</span></span>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Ir al Sistema</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm transition">Registrarse</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- 🚀 Hero Section -->
    <header class="max-w-7xl mx-auto px-6 py-20 md:py-32 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Conectando la provincia</span>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight leading-none">
                Viajá seguro, cómodo y a tiempo con <span class="text-blue-600">El Gurí</span>
            </h1>
            <p class="text-gray-600 text-lg max-w-md">
                Centralizamos la gestión de flota, viajes y pasajes para brindarte el mejor servicio en rutas provinciales con total transparencia y seguridad.
            </p>
            <div class="pt-4 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white font-bold px-6 py-3.5 rounded-xl hover:bg-blue-700 text-center shadow-md hover:shadow-lg transition">
                    Comprar Pasajes
                </a>
                <a href="#servicios" class="bg-white border border-gray-200 text-gray-700 font-semibold px-6 py-3.5 rounded-xl hover:bg-gray-50 text-center transition">
                    Conocer Rutas
                </a>
            </div>
        </div>
        <div class="hidden md:flex justify-center relative">
            <div class="w-96 h-96 bg-blue-600 rounded-3xl transform rotate-12 shadow-2xl opacity-10 absolute inset-0 m-auto"></div>
            <div class="w-80 h-80 bg-blue-700 rounded-3xl transform -rotate-6 shadow-xl flex flex-col justify-between p-6 text-white">
                <div class="flex justify-between items-start">
                    <x-application-logo class="w-14 h-auto" />
                    <span class="bg-blue-500 text-xs font-bold px-2 py-1 rounded">Misiones</span>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-xl">Flota Moderna</h3>
                    <p class="text-blue-100 text-sm">Unidades Marcopolo Paradiso equipadas con el máximo confort para tu viaje.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- 📌 Características de Control Integrado -->
    <section id="servicios" class="bg-white py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-12">Comprometidos con la Calidad de Servicio</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🛡️</div>
                    <h3 class="font-bold text-lg text-gray-800">Cero Duplicados</h3>
                    <p class="text-gray-600 text-sm">Nuestro sistema valida atómicamente cada butaca, asegurando que ningún asiento se venda dos veces.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🗺️</div>
                    <h3 class="font-bold text-lg text-gray-800">Rutas Trazadas</h3>
                    <p class="text-gray-600 text-sm">Control geográfico estricto por localidades provinciales, optimizando distancias y tiempos de arribo.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 space-y-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-xl">👨‍✈️</div>
                    <h3 class="font-bold text-lg text-gray-800">Personal Calificado</h3>
                    <p class="text-gray-600 text-sm">Asignación rigurosa de choferes profesionales validados bajo licencias únicas nacionales.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 📋 Footer -->
    <footer class="bg-gray-900 text-gray-400 text-sm py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} Transportes El Gurí SRL. Todos los derechos reservados. Posadas, Misiones.</p>
        </div>
    </footer>

</body>
</html>
