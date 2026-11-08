<div class="flex flex-col w-full h-full bg-gray-900 border-r border-gray-800">
    <nav aria-label="nav" class="h-full">
        <div class="flex flex-col h-full text-left justify-between py-4 items-center">
            
            <!-- 🚌 Logo Central -->
            <div class="flex justify-center items-center pb-6 border-b border-gray-800 w-full" id="logo_container">
                <a class="text-2xl tracking-wider font-black text-white hover:no-underline flex items-center justify-center w-full" href="{{ route('dashboard') }}">
                    <x-application-logo class="w-14 h-auto" />
                </a>
            </div>

            <!-- 🧭 Sidebar Nav Links (Navegación Activa en Bloque) -->
            @auth
            <ul class="flex flex-col space-y-4 px-2 flex-grow justify-start items-center pt-8 w-full">
                
                <!-- Inicio / Dashboard -->
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('dashboard') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('dashboard') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Tablero Principal">
                        <span class="material-symbols-outlined text-xl">home</span>
                    </a>
                </li>

                <!-- Gestión de Flota (Colectivos) -->
                @can('colectivo.index')
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('colectivos.*') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('colectivos.index') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Control de Flota">
                        <span class="material-symbols-outlined text-xl">directions_bus</span>
                    </a>
                </li>
                @endcan

                <!-- Gestión de Personal (Choferes) -->
                @can('chofer.index')
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('choferes.*') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('choferes.index') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Legajo de Conductores">
                        <span class="material-symbols-outlined text-xl">badge</span>
                    </a>
                </li>
                @endcan

                <!-- Gestión de Rutas -->
                @can('ruta.index')
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('rutas.*') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('rutas.index') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Red de Rutas">
                        <span class="material-symbols-outlined text-xl">distance</span>
                    </a>
                </li>
                @endcan

                <!-- Programación de Viajes -->
                @can('viaje.index')
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('viajes.*') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('viajes.index') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Cronograma de Salidas">
                        <span class="material-symbols-outlined text-xl">schedule</span>
                    </a>
                </li>
                @endcan

                <!-- Terminal de Boletería -->
                @can('venta.store')
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('boleteria.*') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('boleteria.index') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Venta de Pasajes">
                        <span class="material-symbols-outlined text-xl">confirmation_number</span>
                    </a>
                </li>
                @endcan

                <!-- Perfil del Usuario Logueado -->
                <li class="w-12 h-12 rounded-2xl flex overflow-hidden transition-all {{ Route::is('profile.*') ? 'bg-blue-600' : 'bg-gray-800 hover:bg-yellow-500' }}">
                    <a href="{{ route('profile.edit') }}" class="flex justify-center items-center w-full h-full text-white hover:text-white" title="Mi Perfil">
                        <span class="material-symbols-outlined text-xl">settings</span>
                    </a>
                </li>
            </ul>
            @endauth

            <!-- 🚪 Botón de Salida Seguro -->
            @auth
            <div class="w-full flex justify-center border-t border-gray-800 pt-4">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0 flex justify-center w-full">
                    @csrf
                    <button type="submit" class="w-12 h-12 rounded-2xl flex justify-center items-center bg-gray-800 hover:bg-red-600 text-white transition-all" title="Cerrar Sesión">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>
</div>
