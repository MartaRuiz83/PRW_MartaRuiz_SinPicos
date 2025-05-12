<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">
                        {{ __('Remember me') }}
                    </span>
                </label>
            </div>

            <div class="flex flex-col items-end mt-4 space-y-3">
                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="underline text-sm text-gray-600 hover:text-gray-900"
                    >
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif

                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="underline text-sm text-gray-600 hover:text-gray-900"
                    >
                        {{ __('¿Aún no tienes cuenta? Regístrate') }}
                    </a>
                @endif

                <x-button
                    class="w-full mt-2 text-white font-medium rounded flex items-center justify-center py-2"
                    style="background-color: #7e43ee;"
                >
                    {{ __('Iniciar sesión') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
