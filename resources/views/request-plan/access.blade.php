<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitar acesso — {{ $selectedPlan['name'] }} · Vistoo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-jakarta min-h-screen antialiased bg-[#F8F9FC] text-[#33363B] selection:bg-[#D131A3]/25">
    <header class="border-b border-[#6A2BBA]/10 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-3xl items-center justify-between gap-4 px-4 py-4">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2 rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                <img src="{{ asset('img/vistoo-logo.png') }}" width="120" height="40" class="h-8 w-auto" alt="Vistoo — início">
            </a>
            <a href="{{ route('login') }}" class="text-sm font-bold text-[#6A2BBA] hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] rounded-lg px-2 py-1">Já tenho conta</a>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-10 md:py-14" id="conteudo-principal">
        @if (session('status') === 'received')
            <div class="mb-8 rounded-2xl border border-[#6A2BBA]/20 bg-white p-5 shadow-sm ring-1 ring-[#33363B]/5" role="status">
                <p class="font-display text-lg font-bold text-[#6A2BBA]">Recebemos sua solicitação</p>
                <p class="mt-2 text-sm font-medium leading-relaxed text-[#33363B]/75">
                    Em breve nossa equipe entra em contato pelo e-mail e WhatsApp informados para concluir a liberação do acesso e combinar o pagamento do plano <strong>{{ $selectedPlan['name'] }}</strong>.
                </p>
                <a href="{{ route('welcome') }}" class="mt-4 inline-flex text-sm font-bold text-[#D131A3] underline-offset-2 hover:underline">Voltar à página inicial</a>
            </div>
        @endif

        <p class="text-center text-xs font-bold uppercase tracking-wider text-[#D131A3]">Solicitação de acesso</p>
        <h1 class="font-display mt-2 text-center text-3xl font-extrabold text-[#33363B] md:text-4xl">Plano {{ $selectedPlan['name'] }}</h1>
        <p class="mx-auto mt-3 max-w-xl text-center text-sm font-medium text-[#33363B]/70">
            Preencha os dados abaixo. O pagamento e a ativação serão tratados com nosso time — em breve também pelo site, de forma automática.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-2 text-sm font-bold" role="tablist" aria-label="Escolher plano">
            @foreach ($allPlans as $key => $meta)
                <a
                    href="{{ route('request.plan.form', ['plan' => $key]) }}"
                    class="rounded-full px-4 py-2 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 {{ $key === $selectedPlanKey ? 'bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] text-white shadow-md' : 'bg-white text-[#6A2BBA] ring-1 ring-[#6A2BBA]/20 hover:bg-[#6A2BBA]/5' }}"
                    @if ($key === $selectedPlanKey) aria-current="page" @endif
                >
                    {{ $meta['name'] }} · {{ $meta['price_label'] }}{{ $meta['period'] }}
                </a>
            @endforeach
        </div>

        @unless (session('status') === 'received')
        <div class="mt-10 overflow-hidden rounded-[1.75rem] border border-[#33363B]/8 bg-white p-6 shadow-xl shadow-[#6A2BBA]/5 md:p-8">
            <div class="mb-6 rounded-2xl bg-gradient-to-r from-[#EDE9FE]/80 to-[#FCE7F3]/60 p-4 text-center">
                <p class="text-sm font-semibold text-[#33363B]/80">Valor de referência</p>
                <p class="font-display mt-1 text-2xl font-extrabold text-[#6A2BBA]">{{ $selectedPlan['price_label'] }}<span class="text-base font-bold text-[#33363B]/50">{{ $selectedPlan['period'] }}</span></p>
            </div>

            <form method="post" action="{{ route('request.plan.store') }}" class="space-y-5" novalidate>
                @csrf
                <input type="hidden" name="plan_slug" value="{{ $selectedPlanKey }}">

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="mb-1.5 block text-sm font-bold text-[#33363B]">Nome completo <span class="text-[#D131A3]">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                            class="w-full rounded-2xl border border-[#33363B]/15 bg-[#F8F9FC] px-4 py-3 text-sm font-medium text-[#33363B] shadow-inner outline-none transition focus:border-[#6A2BBA] focus:bg-white focus:ring-2 focus:ring-[#6A2BBA]/25 @error('name') border-red-400 ring-2 ring-red-200 @enderror"
                            placeholder="Seu nome">
                        @error('name')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-bold text-[#33363B]">E-mail <span class="text-[#D131A3]">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                            class="w-full rounded-2xl border border-[#33363B]/15 bg-[#F8F9FC] px-4 py-3 text-sm font-medium outline-none transition focus:border-[#6A2BBA] focus:bg-white focus:ring-2 focus:ring-[#6A2BBA]/25 @error('email') border-red-400 ring-2 ring-red-200 @enderror"
                            placeholder="contato@empresa.com">
                        @error('email')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1.5 block text-sm font-bold text-[#33363B]">WhatsApp <span class="text-[#D131A3]">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required inputmode="numeric" autocomplete="tel"
                            class="w-full rounded-2xl border border-[#33363B]/15 bg-[#F8F9FC] px-4 py-3 text-sm font-medium outline-none transition focus:border-[#6A2BBA] focus:bg-white focus:ring-2 focus:ring-[#6A2BBA]/25 @error('phone') border-red-400 ring-2 ring-red-200 @enderror"
                            placeholder="(11) 99999-0000">
                        @error('phone')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="store_name" class="mb-1.5 block text-sm font-bold text-[#33363B]">Nome da loja / marca <span class="text-[#D131A3]">*</span></label>
                        <input type="text" name="store_name" id="store_name" value="{{ old('store_name') }}" required autocomplete="organization"
                            class="w-full rounded-2xl border border-[#33363B]/15 bg-[#F8F9FC] px-4 py-3 text-sm font-medium outline-none transition focus:border-[#6A2BBA] focus:bg-white focus:ring-2 focus:ring-[#6A2BBA]/25 @error('store_name') border-red-400 ring-2 ring-red-200 @enderror"
                            placeholder="Como aparecerá no catálogo">
                        @error('store_name')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="qtd_vehicles_in_stock" class="mb-1.5 block text-sm font-bold text-[#33363B]">Volume aproximado de produtos</label>
                        <select name="qtd_vehicles_in_stock" id="qtd_vehicles_in_stock"
                            class="w-full rounded-2xl border border-[#33363B]/15 bg-[#F8F9FC] px-4 py-3 text-sm font-medium outline-none transition focus:border-[#6A2BBA] focus:bg-white focus:ring-2 focus:ring-[#6A2BBA]/25 @error('qtd_vehicles_in_stock') border-red-400 @enderror">
                            <option value="">Selecione (opcional)</option>
                            <option value="ate-50" @selected(old('qtd_vehicles_in_stock') === 'ate-50')>Até 50 produtos</option>
                            <option value="51-200" @selected(old('qtd_vehicles_in_stock') === '51-200')>De 51 a 200 produtos</option>
                            <option value="201-plus" @selected(old('qtd_vehicles_in_stock') === '201-plus')>Mais de 200 produtos</option>
                        </select>
                        @error('qtd_vehicles_in_stock')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="notes" class="mb-1.5 block text-sm font-bold text-[#33363B]">Mensagem ou observações</label>
                        <textarea name="notes" id="notes" rows="4" maxlength="2000"
                            class="w-full resize-y rounded-2xl border border-[#33363B]/15 bg-[#F8F9FC] px-4 py-3 text-sm font-medium outline-none transition focus:border-[#6A2BBA] focus:bg-white focus:ring-2 focus:ring-[#6A2BBA]/25 @error('notes') border-red-400 @enderror"
                            placeholder="Horário preferido para contato, CNPJ, integrações desejadas, etc.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <p class="text-xs font-medium leading-relaxed text-[#33363B]/55">
                    Ao enviar, você confirma que os dados são verdadeiros. Não realizamos cobrança automática nesta etapa; o time Vistoo orientará sobre pagamento e contrato antes da ativação.
                </p>

                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] py-4 text-center text-base font-bold text-white shadow-lg shadow-[#6A2BBA]/25 transition hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2">
                    Enviar solicitação de acesso
                </button>
            </form>
        </div>
        @endunless
    </main>

    <footer class="border-t border-[#33363B]/10 bg-white px-4 py-8 text-center text-xs font-semibold text-[#33363B]/50">
        © {{ date('Y') }} Vistoo
    </footer>

    <script>
        (function () {
            var input = document.getElementById('phone');
            if (!input || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            input.addEventListener('input', function () {
                var d = this.value.replace(/\D/g, '').slice(0, 11);
                if (d.length <= 10) {
                    this.value = d.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    this.value = d.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
                }
            });
        })();
    </script>
</body>
</html>
