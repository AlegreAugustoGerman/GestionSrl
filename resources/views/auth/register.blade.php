<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-black text-gray-900 tracking-tight">Registro de Pasajeros</h2>
        <p class="text-xs text-gray-500 font-medium">Creá tu cuenta institucional</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="apellido" value="Apellido" />
                <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="dni" value="DNI / Documento" />
                <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni')" required autocomplete="id-number" />
                <x-input-error :messages="$errors->get('dni')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="telefono" value="Teléfono" />
                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" placeholder="Ej: 3764123456" required autocomplete="tel" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="underline text-sm text-gray-500 hover:text-blue-600 transition font-medium" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
