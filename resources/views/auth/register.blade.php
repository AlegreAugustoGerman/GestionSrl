<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-black text-gray-900 tracking-tight">Registro de Pasajeros</h2>
        <p class="text-sm text-gray-500 ">Creá tu cuenta institucional</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Nombre</label> 
                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus placeholder="Nombre" autocomplete="given-name" />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <div>
                <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Apellido</label> 
                <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido')" required placeholder="Apellido" autocomplete="family-name" />
                <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Dni</label> 
                <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni')" required placeholder="34567954 " autocomplete="id-number" />
                <x-input-error :messages="$errors->get('dni')" class="mt-2" />
            </div>
            <div>
                <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Telefono</label> 
                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" placeholder="3764123456" required  autocomplete="tel" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>
        </div>

        <div>
            <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Correo Electrónico</label> 
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="Ejemplo@hotmail.com " autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Contraseña</label> 
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required placeholder="Ingrese contraseña" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Repita Contraseña</label> 
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required placeholder="Ingrese contraseña" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="underline text-sm text-gray-500 hover:text-blue-600 transition font-bold" href="{{ route('login') }}">
                {{ __('Ya Registrado?') }}
            </a>

        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-600 text-white p-3.5 rounded-xl text-base font-black uppercase tracking-wider hover:bg-blue-700 shadow-md active:bg-blue-800 transition">
                {{ __('Register') }}
            </button>
        </div>

        </div>
    </form>
</x-guest-layout>
