<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <a href="/" class="flex items-center gap-2">
                <x-authentication-card-logo />
                <span class="text-lg font-semibold bg-gradient-to-r from-violet-400 to-violet-200 bg-clip-text text-transparent">{{ config('app.name', 'Gestior') }}</span>
            </a>
        </x-slot>

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold tracking-tight bg-gradient-to-r from-violet-400 to-violet-200 bg-clip-text text-transparent">Crear cuenta</h1>
            <p class="mt-1 text-sm text-gray-300">Comienza hoy y gestiona tu negocio</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" class="text-gray-200" />
                <x-input id="name" class="block mt-1 w-full bg-white/5 border-white/10 text-white placeholder-gray-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-200" />
                <x-input id="email" class="block mt-1 w-full bg-white/5 border-white/10 text-white placeholder-gray-400" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-200" />
                <x-input id="password" class="block mt-1 w-full bg-white/5 border-white/10 text-white placeholder-gray-400" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-200" />
                <x-input id="password_confirmation" class="block mt-1 w-full bg-white/5 border-white/10 text-white placeholder-gray-400" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div>
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2 text-gray-300">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-300 hover:text-white">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-300 hover:text-white">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 pt-2">
                <a class="px-4 py-2 rounded-lg btn-glass text-sm" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-0 btn-primary rounded-lg text-sm font-semibold normal-case tracking-normal">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
