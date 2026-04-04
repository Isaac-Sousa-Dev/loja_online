<x-guest-layout>
<div class="relative min-h-screen flex overflow-hidden">
    {{-- Fundo sutil (mesma linguagem do hero da welcome) --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(106,43,186,0.14),transparent),radial-gradient(ellipse_45%_40%_at_100%_30%,rgba(209,49,163,0.08),transparent),radial-gradient(ellipse_40%_35%_at_0%_90%,rgba(99,102,241,0.06),transparent)] motion-reduce:bg-none" aria-hidden="true"></div>

    <!-- Painel marca -->
    <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-[#33363B] p-12 lg:flex">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-[#6A2BBA]/45 via-transparent to-[#D131A3]/35" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0 opacity-40 motion-reduce:opacity-25" aria-hidden="true">
            <div class="absolute -left-24 -top-24 h-96 w-96 rounded-full bg-[#6A2BBA]/30 blur-3xl"></div>
            <div class="absolute -right-20 top-1/2 h-80 w-80 -translate-y-1/2 rounded-full bg-[#D131A3]/25 blur-3xl"></div>
            <div class="absolute -bottom-16 left-1/4 h-72 w-72 rounded-full bg-[#6366f1]/20 blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <a href="{{ route('welcome') }}" class="inline-flex rounded-2xl transition-transform duration-300 hover:scale-[1.02] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2 focus-visible:ring-offset-[#33363B] motion-reduce:hover:scale-100" aria-label="Vistuu — página inicial">
                <img src="{{ asset('img/vistuu-logo.png') }}" width="140" height="46" class="h-10 w-auto brightness-0 invert opacity-95" alt="Logotipo Vistuu">
            </a>
        </div>

        <div class="relative z-10 max-w-md space-y-6">
            <h2 class="font-display text-4xl font-extrabold leading-tight tracking-tight text-white">
                Bem-vindo de<br>volta.
            </h2>
            <p class="text-lg font-medium leading-relaxed text-white/80">
                Acesse sua conta e gerencie catálogo, produtos e pedidos em um só lugar.
            </p>
            <p class="flex items-center gap-2 text-sm font-semibold text-[#FF914D]">
                <span class="inline-flex h-2 w-2 animate-pulse rounded-full bg-[#FF914D] motion-reduce:animate-none" aria-hidden="true"></span>
                Vistuu — moda e vitrine mobile-first
            </p>
        </div>

        <div class="relative z-10">
            <p class="text-sm font-medium text-white/55">© {{ date('Y') }} Vistuu. Todos os direitos reservados.</p>
        </div>
    </div>

    <!-- Formulário -->
    <div class="relative z-[1] flex w-full items-center justify-center px-6 py-12 lg:w-1/2 lg:py-16">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-center gap-6 lg:hidden">
                <a href="{{ route('welcome') }}" class="rounded-2xl transition-opacity hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2" aria-label="Vistuu — página inicial">
                    <img src="{{ asset('img/vistuu-logo.png') }}" width="130" height="43" class="h-9 w-auto" alt="Logotipo Vistuu">
                </a>
            </div>

            <div class="rounded-3xl border border-[#33363B]/8 bg-white/90 p-8 shadow-xl shadow-[#6A2BBA]/5 ring-1 ring-[#33363B]/5 backdrop-blur-sm sm:p-10">
                <x-auth-session-status class="mb-6" :status="session('status')" />

                @if($firstLogin)
                    <div class="mb-8">
                        <h1 class="font-display text-3xl font-extrabold tracking-tight text-[#33363B]">Primeiro acesso</h1>
                        <p class="mt-2 text-sm font-medium text-[#33363B]/65">Defina sua senha para ativar sua conta.</p>
                    </div>

                    <form method="POST" action="{{ route('login', ['first_login' => 1]) }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="verification_code" class="mb-1 block text-sm font-semibold text-[#33363B]">
                                Código de verificação
                            </label>
                            <input
                                id="verification_code"
                                type="text"
                                name="verification_code"
                                placeholder="Ex: 123456"
                                autofocus
                                class="w-full rounded-2xl border border-[#33363B]/12 bg-white px-4 py-3 text-[#33363B] shadow-sm placeholder:text-[#33363B]/40 transition focus:border-[#6A2BBA]/40 focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35"
                            >
                            <x-input-error :messages="$errors->get('verification_code')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password" class="mb-1 block text-sm font-semibold text-[#33363B]">
                                Nova senha
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Mínimo 8 caracteres"
                                autocomplete="new-password"
                                class="w-full rounded-2xl border border-[#33363B]/12 bg-white px-4 py-3 text-[#33363B] shadow-sm placeholder:text-[#33363B]/40 transition focus:border-[#6A2BBA]/40 focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-[#33363B]">
                                Confirmar senha
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Repita a senha"
                                autocomplete="new-password"
                                class="w-full rounded-2xl border border-[#33363B]/12 bg-white px-4 py-3 text-[#33363B] shadow-sm placeholder:text-[#33363B]/40 transition focus:border-[#6A2BBA]/40 focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35"
                            >
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>

                        <label for="remember_me" class="flex cursor-pointer select-none items-center gap-3">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-[#33363B]/25 text-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/40 focus:ring-offset-0">
                            <span class="text-sm font-medium text-[#33363B]/70">Lembrar-me neste dispositivo</span>
                        </label>

                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] py-3.5 text-center text-base font-bold text-white shadow-lg shadow-[#6A2BBA]/25 transition duration-300 hover:brightness-105 hover:shadow-xl hover:shadow-[#6A2BBA]/30 active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2 motion-reduce:active:scale-100">
                            Ativar conta
                        </button>
                    </form>

                @else
                    <div class="mb-8">
                        <h1 class="font-display text-3xl font-extrabold tracking-tight text-[#33363B]">Entrar</h1>
                        <p class="mt-2 text-sm font-medium text-[#33363B]/65">Informe suas credenciais para acessar o painel.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-1 block text-sm font-semibold text-[#33363B]">
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
                                class="w-full rounded-2xl border border-[#33363B]/12 bg-white px-4 py-3 text-[#33363B] shadow-sm placeholder:text-[#33363B]/40 transition focus:border-[#6A2BBA]/40 focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password" class="mb-1 block text-sm font-semibold text-[#33363B]">
                                Senha
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-2xl border border-[#33363B]/12 bg-white px-4 py-3 text-[#33363B] shadow-sm placeholder:text-[#33363B]/40 transition focus:border-[#6A2BBA]/40 focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <label for="remember_me" class="flex cursor-pointer select-none items-center gap-3">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-[#33363B]/25 text-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/40 focus:ring-offset-0">
                            <span class="text-sm font-medium text-[#33363B]/70">Lembrar-me neste dispositivo</span>
                        </label>

                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] py-3.5 text-center text-base font-bold text-white shadow-lg shadow-[#6A2BBA]/25 transition duration-300 hover:brightness-105 hover:shadow-xl hover:shadow-[#6A2BBA]/30 active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2 motion-reduce:active:scale-100">
                            Entrar
                        </button>

                        {{-- Uncomment to enable password reset
                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-sm font-bold text-[#6A2BBA] transition hover:text-[#D131A3] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2 rounded-lg">
                                    Esqueceu sua senha?
                                </a>
                            </div>
                        @endif
                        --}}
                    </form>
                @endif

                <p class="mt-8 text-center text-sm font-medium text-[#33363B]/55">
                    <a href="{{ route('welcome') }}" class="font-bold text-[#6A2BBA] transition hover:text-[#D131A3] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 rounded-lg">← Voltar ao site</a>
                </p>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>
