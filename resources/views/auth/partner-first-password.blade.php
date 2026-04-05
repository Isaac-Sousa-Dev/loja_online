<x-guest-layout>
    <div class="relative min-h-screen flex overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(106,43,186,0.14),transparent),radial-gradient(ellipse_45%_40%_at_100%_30%,rgba(209,49,163,0.08),transparent)] motion-reduce:bg-none"
            aria-hidden="true"></div>

        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-[#33363B] p-12 lg:flex">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-[#6A2BBA]/45 via-transparent to-[#D131A3]/35"
                aria-hidden="true"></div>
            <div class="relative z-10">
                <a href="{{ route('welcome') }}"
                    class="inline-flex rounded-2xl transition-transform duration-300 hover:scale-[1.02] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2 focus-visible:ring-offset-[#33363B] motion-reduce:hover:scale-100"
                    aria-label="Vistuu — página inicial">
                    <img src="{{ asset('img/vistuu-logo.png') }}" width="140" height="46"
                        class="h-10 w-auto brightness-0 invert opacity-95" alt="Logotipo Vistuu">
                </a>
            </div>
            <div class="relative z-10 max-w-md space-y-6">
                <h2 class="font-display text-4xl font-extrabold leading-tight tracking-tight text-white">
                    Defina sua senha
                </h2>
                <p class="text-lg font-medium leading-relaxed text-white/80">
                    Por segurança, troque a senha provisória antes de usar o painel da loja.
                </p>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-white/55">© {{ date('Y') }} Vistuu. Todos os direitos reservados.</p>
            </div>
        </div>

        <div class="relative z-[1] flex w-full items-center justify-center px-6 py-12 lg:w-1/2 lg:py-16">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-center gap-6 lg:hidden">
                    <a href="{{ route('welcome') }}"
                        class="rounded-2xl transition-opacity hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2"
                        aria-label="Vistuu — página inicial">
                        <img src="{{ asset('img/vistuu-logo.png') }}" width="130" height="43" class="h-9 w-auto"
                            alt="Logotipo Vistuu">
                    </a>
                </div>

                <div
                    class="rounded-3xl border border-[#33363B]/8 bg-white/90 p-8 shadow-xl shadow-[#6A2BBA]/5 ring-1 ring-[#33363B]/5 backdrop-blur-sm sm:p-10">
                    <div class="mb-8">
                        <h1 class="font-display text-3xl font-extrabold tracking-tight text-[#33363B]">Nova senha</h1>
                        <p class="mt-2 text-sm font-medium text-[#33363B]/65">A senha usada no login já vem preenchida abaixo.
                            Confira, escolha uma nova senha forte e salve.</p>
                    </div>

                    <form method="POST" action="{{ route('partner.first-password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <x-password-field label="Senha provisória atual" id="current_password" name="current_password"
                            :value="$currentPasswordPrefill" autocomplete="current-password" :required="true"
                            :autofocus="true" />

                        <x-password-field label="Nova senha" id="partner_new_password" name="password"
                            :value="old('password')" placeholder="Mínimo de caracteres exigido pela política de segurança"
                            autocomplete="new-password" :required="true" />

                        <x-password-field label="Confirmar nova senha" id="partner_new_password_confirmation"
                            name="password_confirmation" :value="old('password_confirmation')" placeholder="Repita a nova senha"
                            autocomplete="new-password" :required="true" />

                        <button type="submit"
                            class="flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#6A2BBA]/25 transition hover:opacity-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                            Salvar e continuar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
