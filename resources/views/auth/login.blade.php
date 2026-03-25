<x-guest-layout>
<div class="min-h-screen flex">

    <!-- Left Panel - Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 flex-col justify-between p-12 relative overflow-hidden">
        <!-- Background decorative circles -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white opacity-5 rounded-full"></div>
            <div class="absolute top-1/2 -right-32 w-80 h-80 bg-white opacity-5 rounded-full"></div>
            <div class="absolute -bottom-20 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full"></div>
        </div>

        <!-- Logo -->
        <div class="relative z-10">
            <img src="/img/logos/logo.png" width="160" alt="Logo" class="brightness-0 invert opacity-90">
        </div>

        <!-- Center content -->
        <div class="relative z-10 space-y-6">
            <h2 class="text-4xl font-bold text-white leading-tight">
                Bem-vindo de<br>volta.
            </h2>
            <p class="text-blue-100 text-lg leading-relaxed max-w-sm">
                Acesse sua conta e gerencie tudo em um só lugar com segurança e praticidade.
            </p>
        </div>

        <!-- Bottom tagline -->
        <div class="relative z-10">
            <p class="text-blue-200 text-sm">© {{ date('Y') }} Todos os direitos reservados.</p>
        </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Mobile logo -->
            <div class="flex justify-center mb-10 lg:hidden">
                <img src="/img/logos/logo.png" width="150" alt="Logo">
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            @if($firstLogin)
                <!-- First Login -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Primeiro acesso</h1>
                    <p class="text-gray-500 mt-2 text-sm">Defina sua senha para ativar sua conta.</p>
                </div>

                <form method="POST" action="{{ route('login', ['first_login' => 1]) }}" class="space-y-5">
                    @csrf

                    <!-- Verification Code -->
                    <div>
                        <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Código de verificação
                        </label>
                        <input
                            id="verification_code"
                            type="text"
                            name="verification_code"
                            placeholder="Ex: 123456"
                            autofocus
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        >
                        <x-input-error :messages="$errors->get('verification_code')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nova senha
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Mínimo 8 caracteres"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        >
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar senha
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Repita a senha"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        >
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <label for="remember_me" class="flex items-center gap-3 cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Lembrar-me neste dispositivo</span>
                    </label>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-xl transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md">
                        Ativar conta
                    </button>
                </form>

            @else
                <!-- Regular Login -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Entrar</h1>
                    <p class="text-gray-500 mt-2 text-sm">Informe suas credenciais para acessar.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            E-mail
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="seu@email.com"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Senha
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        >
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <label for="remember_me" class="flex items-center gap-3 cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-600">Lembrar-me neste dispositivo</span>
                    </label>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-xl transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-md">
                        Entrar
                    </button>

                    {{-- Uncomment to enable password reset
                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition">
                                Esqueceu sua senha?
                            </a>
                        </div>
                    @endif
                    --}}
                </form>
            @endif

        </div>
    </div>

</div>
</x-guest-layout>
