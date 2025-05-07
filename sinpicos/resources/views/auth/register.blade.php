<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nombre -->
            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" 
                         type="text" name="name" :value="old('name')" 
                         required autofocus autocomplete="name" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" 
                         type="email" name="email" :value="old('email')" 
                         required autocomplete="username" />
            </div>

            <!-- Tipo de Diabetes -->
            <div class="mt-4">
                <x-label for="tipo_diabetes" value="{{ __('Tipo de Diabetes') }}" />
                <select id="tipo_diabetes" name="tipo_diabetes"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('tipo_diabetes') border-red-500 @enderror"
                        required>
                    <option value="" disabled {{ old('tipo_diabetes') ? '' : 'selected' }}>
                        {{ __('Selecciona tipo de diabetes') }}
                    </option>
                    <option value="Tipo 1"       {{ old('tipo_diabetes')=='Tipo 1'       ? 'selected':'' }}>
                        Tipo 1
                    </option>
                    <option value="Tipo 2"       {{ old('tipo_diabetes')=='Tipo 2'       ? 'selected':'' }}>
                        Tipo 2
                    </option>
                    <option value="Gestacional" {{ old('tipo_diabetes')=='Gestacional' ? 'selected':'' }}>
                        Gestiacional
                    </option>
                </select>
                @error('tipo_diabetes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" 
                         type="password" name="password" required 
                         autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" 
                         value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" 
                         class="block mt-1 w-full" 
                         type="password" 
                         name="password_confirmation" required 
                         autocomplete="new-password" />
            </div>

            <!-- Rol oculto por defecto "Usuario" -->
            <input type="hidden" name="rol" value="Usuario">

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2 text-sm text-gray-600">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline hover:text-gray-900">'.__('Terms of Service').'</a>',
                                    'privacy_policy'  => '<a target="_blank" href="'.route('policy.show').'" class="underline hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" 
                   href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
