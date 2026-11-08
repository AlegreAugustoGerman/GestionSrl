<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Ingreso al Sistema</h2>
        {{-- <p class="text-sm text-gray-500 font-semibold tracking-wide">Plataforma Operativa Institucional</p> --}}
        <p class="text-sm text-gray-500">Transportes El Gurí SRL</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <!-- Label con fuente ampliada y tracking espaciado -->
            <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Correo Electrónico</label>
            <x-text-input id="email" class="block mt-1 w-full text-base p-3 rounded-xl border border-gray-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label class="block font-black text-xs uppercase tracking-widest text-gray-500 mb-1.5">Contraseña de Acceso</label>
            <x-text-input id="password" class="block mt-1 w-full text-base p-3 rounded-xl border border-gray-300" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm h-4 w-4 focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-semibold">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-500 hover:text-blue-600 transition font-bold" href="{{ route('password.request') }}">
                    ¿Olvidaste tu clave?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-600 text-white p-3.5 rounded-xl text-base font-black uppercase tracking-wider hover:bg-blue-700 shadow-md active:bg-blue-800 transition">
                Entrar al Tablero
            </button>
        </div>
    </form>
</x-guest-layout>
