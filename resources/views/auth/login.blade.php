<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex justify-center">
        <img src="/img/logos/logo.png" width="200" alt="">
    </div>

    

    @if($firstLogin) 
        <div class="text-center font-semibold text-black pb-3 rounded-md text-xl">
            Faça seu primeiro acesso.
        </div>
        <form method="POST" action="{{ route('login', ['first_login' => 1]) }}">
            @csrf

            {{-- <div>
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" disabled placeholder="email" class="block mt-1 w-full" type="text" name="email" autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div> --}}

            <!-- Verification Code -->
            <div class="mt-4">
                <x-input-label for="verification_code" :value="__('Código de verificação')" />
                <x-text-input id="verification_code" placeholder="Código de verificação" class="block mt-1 w-full" type="text" name="verification_code" autofocus />
                <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Senha')" />

                <x-text-input id="password" placeholder="*************" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Password Confirmation -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmação de senha')" />

                <x-text-input id="password_confirmation" placeholder="*************" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="mt-4 flex gap-1">
                <label for="remember_me" class="inline-flex items-center w-2/3">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm">{{ __('Lembrar-me') }}</span>
                </label>

                <button class="ms-3 w-1/3 font-semibold text-white bg-blue-500 py-2 px-5 rounded-xl">
                    {{ __('Log in') }}
                </button>
            </div>

        </form>
    @else
        <div class="text-center font-semibold text-black pb-3 rounded-md text-xl">
            Faça seu Login.
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" placeholder="seu@email.com" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Senha')" />

                <x-text-input id="password" placeholder="*************" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="mt-4 flex gap-1">
                <label for="remember_me" class="inline-flex items-center w-2/3">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm">{{ __('Lembrar-me') }}</span>
                </label>

                <button class="ms-3 w-1/3 font-semibold text-white bg-blue-500 py-2 px-5 rounded-xl">
                    {{ __('Log in') }}
                </button>
            </div>

            {{-- <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Esqueceu sua senha?') }}
                    </a>
                @endif
            </div> --}}
        </form>
    @endif
</x-guest-layout>
