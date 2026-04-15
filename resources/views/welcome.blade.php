<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Vistuu — catálogo online de moda para sua loja. Gestão de produtos, vendas e vitrine mobile-first.">
    <title>Vistuu — Catálogo online de moda para sua loja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="font-jakarta antialiased bg-[#F8F9FC] text-[#33363B] overflow-x-hidden selection:bg-[#D131A3]/25 selection:text-[#33363B]">
    <a href="#conteudo-principal" class="absolute left-[-10000px] top-0 z-[100] h-px w-px overflow-hidden focus:left-4 focus:top-4 focus:h-auto focus:w-auto focus:overflow-visible focus:rounded-2xl focus:bg-white focus:px-4 focus:py-2 focus:shadow-lg focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-[#6A2BBA]">Pular para o conteúdo principal</a>

    {{-- Header --}}
    <header id="site-header" class="sticky top-0 z-50 border-b border-[#6A2BBA]/10 bg-white/85 backdrop-blur-lg transition-[box-shadow,border-color] duration-300 motion-reduce:transition-none">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 md:px-6">
            <a href="{{ route('welcome') }}" class="flex shrink-0 items-center gap-2 rounded-2xl transition-transform duration-300 hover:scale-[1.02] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 motion-reduce:hover:scale-100" aria-label="Vistoo — página inicial">
                <img src="{{ asset('img/vistuu-logo.png') }}" width="110" height="30" alt="Logotipo Vistoo">
            </a>
            <nav class="hidden items-center gap-2 text-sm font-semibold text-[#33363B]/80 md:flex" aria-label="Seções da página">
                <a href="#como-funciona" class="relative rounded-xl px-3 py-2 transition-colors duration-200 hover:text-[#6A2BBA] after:pointer-events-none after:absolute after:bottom-1 after:left-3 after:right-3 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-gradient-to-r after:from-[#6A2BBA] after:to-[#D131A3] after:transition-transform after:duration-300 hover:after:scale-x-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2">Como funciona</a>
                <a href="#funcionalidades" class="relative rounded-xl px-3 py-2 transition-colors duration-200 hover:text-[#6A2BBA] after:pointer-events-none after:absolute after:bottom-1 after:left-3 after:right-3 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-gradient-to-r after:from-[#6A2BBA] after:to-[#D131A3] after:transition-transform after:duration-300 hover:after:scale-x-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2">Funcionalidades</a>
                <a href="#planos" class="relative rounded-xl px-3 py-2 transition-colors duration-200 hover:text-[#6A2BBA] after:pointer-events-none after:absolute after:bottom-1 after:left-3 after:right-3 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-gradient-to-r after:from-[#6A2BBA] after:to-[#D131A3] after:transition-transform after:duration-300 hover:after:scale-x-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2">Planos</a>
                <a href="#parceiros" class="relative rounded-xl px-3 py-2 transition-colors duration-200 hover:text-[#6A2BBA] after:pointer-events-none after:absolute after:bottom-1 after:left-3 after:right-3 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-gradient-to-r after:from-[#6A2BBA] after:to-[#D131A3] after:transition-transform after:duration-300 hover:after:scale-x-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2">Parceiros</a>
            </nav>
            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="rounded-full px-3 py-2 text-sm font-bold text-[#33363B] transition-all duration-200 hover:bg-[#6A2BBA]/10 hover:scale-[1.03] active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 motion-reduce:hover:scale-100 sm:px-4">Entrar</a>
                <a href="{{ route('request.plan.form', ['plan' => 'essencial']) }}" class="relative overflow-hidden rounded-full bg-gradient-to-r from-[#6A2BBA] via-[#D131A3] to-[#FF914D] px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-[#6A2BBA]/30 transition-all duration-300 hover:shadow-xl hover:shadow-[#6A2BBA]/40 hover:scale-[1.03] hover:brightness-105 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2 motion-reduce:hover:scale-100 sm:px-5 sm:text-sm">
                    <span class="relative z-10">Começar com Essencial</span>
                    <span class="pointer-events-none absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent motion-reduce:hidden animate-vistoo-shine" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <div class="md:hidden" aria-hidden="false">
            <nav class="flex gap-2 overflow-x-auto px-4 pb-3 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" aria-label="Atalhos da página">
                <a href="#como-funciona" class="shrink-0 rounded-full bg-[#6A2BBA]/10 px-3 py-1.5 text-xs font-bold text-[#6A2BBA] transition-transform active:scale-95 hover:bg-[#6A2BBA]/18">Como funciona</a>
                <a href="#funcionalidades" class="shrink-0 rounded-full bg-[#6A2BBA]/10 px-3 py-1.5 text-xs font-bold text-[#6A2BBA] transition-transform active:scale-95 hover:bg-[#6A2BBA]/18">Funcionalidades</a>
                <a href="#planos" class="shrink-0 rounded-full bg-[#6A2BBA]/10 px-3 py-1.5 text-xs font-bold text-[#6A2BBA] transition-transform active:scale-95 hover:bg-[#6A2BBA]/18">Planos</a>
                <a href="#parceiros" class="shrink-0 rounded-full bg-[#6A2BBA]/10 px-3 py-1.5 text-xs font-bold text-[#6A2BBA] transition-transform active:scale-95 hover:bg-[#6A2BBA]/18">Parceiros</a>
            </nav>
        </div>
    </header>

    <main id="conteudo-principal">
        {{-- Hero --}}
        <section class="relative overflow-hidden px-4 pb-16 pt-10 md:pb-24 md:pt-14" aria-labelledby="hero-heading">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(106,43,186,0.22),transparent),radial-gradient(ellipse_50%_40%_at_100%_20%,rgba(209,49,163,0.12),transparent),radial-gradient(ellipse_40%_35%_at_0%_80%,rgba(99,102,241,0.08),transparent)] motion-safe:animate-vistoo-breathe motion-reduce:animate-none" aria-hidden="true"></div>
            <div class="relative mx-auto max-w-6xl">
                <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#6A2BBA]/20 bg-white/80 px-4 py-1.5 text-xs font-bold uppercase tracking-wide text-[#6A2BBA] shadow-sm backdrop-blur-sm motion-safe:transition motion-safe:duration-500 motion-safe:hover:border-[#D131A3]/40 motion-safe:hover:shadow-md md:text-sm" data-aos="fade-down" data-aos-duration="600">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-[#D131A3] motion-reduce:animate-none" aria-hidden="true"></span>
                    Catálogo online · Moda · Mobile-first
                </p>
                <h1 id="hero-heading" class="font-display max-w-4xl text-4xl font-extrabold leading-[1.1] tracking-tight text-[#33363B] md:text-5xl lg:text-6xl" data-aos="fade-up" data-aos-duration="700" data-aos-delay="80">
                    Seu catálogo de moda
                    <span class="mt-1 block bg-gradient-to-r from-[#5B4FCF] via-[#6A2BBA] to-[#D131A3] bg-[length:200%_auto] bg-clip-text pb-0.5 text-transparent motion-safe:animate-vistoo-gradient motion-reduce:bg-none motion-reduce:bg-clip-border motion-reduce:text-[#6A2BBA] md:mt-0 md:inline-block md:bg-[length:200%_auto]">bonito, rápido e que vende</span>
                </h1>
                <p class="mt-5 max-w-2xl text-lg font-medium leading-relaxed text-[#33363B]/75 md:text-xl" data-aos="fade-up" data-aos-duration="700" data-aos-delay="160">
                    A plataforma para lojistas montarem vitrine profissional, organizarem produtos e acompanharem pedidos — com a cara da sua marca e foco em conversão.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center" data-aos="fade-up" data-aos-duration="700" data-aos-delay="220">
                    <a href="{{ route('request.plan.form', ['plan' => 'essencial']) }}" class="group relative inline-flex items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] px-8 py-4 text-center text-base font-bold text-white shadow-xl shadow-[#6A2BBA]/30 transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-[#6A2BBA]/35 hover:brightness-105 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2 motion-reduce:hover:scale-100">
                        <span class="relative z-10">Começar com Essencial</span>
                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 motion-reduce:group-hover:opacity-0" aria-hidden="true"></span>
                    </a>
                    <a href="#como-funciona" class="inline-flex items-center justify-center rounded-2xl border-2 border-[#6A2BBA]/30 bg-white px-8 py-4 text-center text-base font-bold text-[#6A2BBA] shadow-sm transition-all duration-300 hover:border-[#D131A3] hover:bg-[#6A2BBA]/5 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 motion-reduce:hover:scale-100">
                        Ver como funciona
                    </a>
                </div>
                <ul class="mt-10 flex flex-wrap gap-4 text-sm font-semibold text-[#33363B]/70" role="list" data-aos="fade-up" data-aos-duration="700" data-aos-delay="280">
                    <li class="group flex items-center gap-2 rounded-2xl bg-white/90 px-4 py-2 shadow-sm ring-1 ring-[#33363B]/5 transition duration-300 hover:-translate-y-0.5 hover:shadow-md motion-reduce:hover:translate-y-0">
                        <svg class="h-5 w-5 shrink-0 text-[#6A2BBA] transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Cadastro em minutos
                    </li>
                    <li class="group flex items-center gap-2 rounded-2xl bg-white/90 px-4 py-2 shadow-sm ring-1 ring-[#33363B]/5 transition duration-300 hover:-translate-y-0.5 hover:shadow-md motion-reduce:hover:translate-y-0">
                        <svg class="h-5 w-5 shrink-0 text-[#D131A3] transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Catálogo otimizado para celular
                    </li>
                    <li class="group flex items-center gap-2 rounded-2xl bg-white/90 px-4 py-2 shadow-sm ring-1 ring-[#33363B]/5 transition duration-300 hover:-translate-y-0.5 hover:shadow-md motion-reduce:hover:translate-y-0">
                        <svg class="h-5 w-5 shrink-0 text-[#FF914D] transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Gestão centralizada
                    </li>
                </ul>

                {{-- Hero visual --}}
                <div class="mt-14 grid gap-6 lg:grid-cols-2 lg:items-center">
                    <div class="relative order-2 lg:order-1" data-aos="fade-right" data-aos-duration="900" data-aos-delay="100">
                        <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-[#6A2BBA]/20 via-[#D131A3]/10 to-[#6366f1]/15 blur-2xl motion-safe:animate-vistoo-float motion-reduce:animate-none" aria-hidden="true"></div>
                        <div class="relative grid grid-cols-3 gap-3 sm:gap-4">
                            <div class="group col-span-2 row-span-2 overflow-hidden rounded-3xl bg-white p-2 shadow-xl ring-1 ring-[#33363B]/5 transition duration-500 hover:-translate-y-1 hover:shadow-2xl motion-reduce:hover:translate-y-0">
                                <img src="/img/home/homepage.png" class="h-full w-full rounded-2xl object-cover object-top transition duration-700 ease-out group-hover:scale-[1.02] motion-reduce:group-hover:scale-100" width="400" height="480" alt="Prévia da página de catálogo da loja no celular">
                            </div>
                            <div class="group overflow-hidden rounded-3xl bg-white p-2 shadow-lg ring-1 ring-[#33363B]/5 transition duration-500 hover:-translate-y-1 hover:shadow-xl motion-reduce:hover:translate-y-0">
                                <img src="/img/home/pdp_catalog.png" class="w-full h-full rounded-2xl transition duration-700 group-hover:scale-105 motion-reduce:group-hover:scale-100" alt="Prévia da ficha de produto">
                            </div>
                            <div class="group overflow-hidden rounded-3xl bg-white p-2 shadow-lg ring-1 ring-[#33363B]/5 transition duration-500 hover:-translate-y-1 hover:shadow-xl motion-reduce:hover:translate-y-0">
                                <img src="/img/home/cart_page.png" class="w-full rounded-2xl object-cover transition duration-700 group-hover:scale-105 motion-reduce:group-hover:scale-100" alt="Prévia do contato com cliente">
                            </div>
                        </div>
                        <p class="mt-3 flex items-center gap-1.5 text-xs text-[#33363B]/50">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            Imagens ilustrativas da interface
                        </p>
                    </div>
                    <div class="order-1 grid grid-cols-2 gap-4 lg:order-2">
                        <div class="rounded-3xl border border-white/60 bg-gradient-to-br from-white to-[#EDE9FE]/40 p-6 shadow-lg backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl motion-reduce:hover:translate-y-0" data-aos="zoom-in" data-aos-duration="700">
                            <p class="font-display text-3xl font-extrabold text-[#6A2BBA]">24/7</p>
                            <p class="mt-1 text-sm font-bold text-[#33363B]">Catálogo no ar</p>
                            <p class="mt-2 text-xs font-medium text-[#33363B]/60">Sua vitrine acessível quando o cliente quiser.</p>
                        </div>
                        <div class="rounded-3xl border border-white/60 bg-gradient-to-br from-white to-[#FCE7F3]/50 p-6 shadow-lg backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl motion-reduce:hover:translate-y-0" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="120">
                            <p class="font-display text-3xl font-extrabold text-[#D131A3]">100%</p>
                            <p class="mt-1 text-sm font-bold text-[#33363B]">Foco em moda</p>
                            <p class="mt-2 text-xs font-medium text-[#33363B]/60">Variantes, marcas e categorias do jeito certo.</p>
                        </div>
                        <div class="col-span-2 rounded-3xl bg-[#33363B] p-6 text-white shadow-xl transition duration-300 hover:-translate-y-0.5 hover:shadow-2xl motion-reduce:hover:translate-y-0" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
                            <p class="text-sm font-bold text-white/90">Link público da loja</p>
                            <p class="mt-2 font-mono text-sm text-[#FF914D] motion-safe:transition-colors motion-safe:hover:text-[#ffb078]">vistuu.app/loja/sua-marca</p>
                            <p class="mt-3 text-xs font-medium text-white/65">Compartilhe no WhatsApp, Instagram e bio.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Como funciona --}}
        <section id="como-funciona" class="scroll-mt-24 border-t border-[#33363B]/5 bg-white px-4 py-16 md:py-24" aria-labelledby="como-funciona-heading">
            <div class="mx-auto max-w-6xl">
                <p class="text-center text-sm font-bold uppercase tracking-wider text-[#D131A3]" data-aos="fade-up">Simples e rápido</p>
                <h2 id="como-funciona-heading" class="font-display mt-2 text-center text-3xl font-extrabold text-[#33363B] md:text-4xl" data-aos="fade-up" data-aos-delay="60">Do cadastro ao primeiro pedido</h2>
                <p class="mx-auto mt-4 max-w-2xl text-center text-base font-medium text-[#33363B]/70" data-aos="fade-up" data-aos-delay="100">Fluxo pensado para lojistas: menos cliques, mais clareza.</p>
                <ol class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-4" role="list">
                    @foreach ([
                        ['n' => '1', 't' => 'Crie sua conta', 'd' => 'Cadastro rápido e acesso ao painel em poucos minutos.'],
                        ['n' => '2', 't' => 'Monte o catálogo', 'd' => 'Produtos, fotos, variantes, marcas e categorias organizadas.'],
                        ['n' => '3', 't' => 'Publique o link', 'd' => 'Sua loja pública com URL própria, pronta para divulgar.'],
                        ['n' => '4', 't' => 'Gerencie pedidos', 'd' => 'Acompanhe solicitações e converse com clientes em um só lugar.'],
                    ] as $step)
                    <li class="group relative rounded-3xl border border-[#6A2BBA]/10 bg-gradient-to-b from-[#F8F9FC] to-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D131A3]/25 hover:shadow-lg motion-reduce:hover:translate-y-0" data-aos="fade-up" data-aos-delay="{{ 100 + $loop->index * 110 }}">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#6A2BBA] to-[#D131A3] text-lg font-extrabold text-white shadow-md transition duration-300 group-hover:scale-110 group-hover:shadow-lg motion-reduce:group-hover:scale-100" aria-hidden="true">{{ $step['n'] }}</span>
                        <h3 class="mt-4 text-lg font-bold text-[#33363B]">{{ $step['t'] }}</h3>
                        <p class="mt-2 text-sm font-medium leading-relaxed text-[#33363B]/65">{{ $step['d'] }}</p>
                    </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- Funcionalidades --}}
        <section id="funcionalidades" class="scroll-mt-24 px-4 py-16 md:py-24" aria-labelledby="funcionalidades-heading">
            <div class="mx-auto max-w-6xl">
                <p class="text-center text-sm font-bold uppercase tracking-wider text-[#6A2BBA]" data-aos="fade-up">Tudo que você precisa</p>
                <h2 id="funcionalidades-heading" class="font-display mt-2 text-center text-3xl font-extrabold text-[#33363B] md:text-4xl" data-aos="fade-up" data-aos-delay="50">Funcionalidades que escalam com a loja</h2>
                <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ([
                        ['title' => 'Catálogo público', 'body' => 'Vitrine responsiva, busca e navegação pensadas para conversão no mobile.', 'icon' => 'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z'],
                        ['title' => 'Gestão de produtos', 'body' => 'Cadastro completo com variantes (cor, tamanho), estoque e precificação.', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                        ['title' => 'Pedidos e vendas', 'body' => 'Acompanhe solicitações, status e histórico sem planilhas paralelas.', 'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m0 0h-9.75m9.75 0c0 .621-.504 1.125-1.125 1.125h-9.75c-.621 0-1.125-.504-1.125-1.125m9.75 0v-9.75c0-.621-.504-1.125-1.125-1.125h-9.75c-.621 0-1.125.504-1.125 1.125v9.75'],
                        ['title' => 'Marcas e categorias', 'body' => 'Estruture o catálogo com marcas, categorias e subcategorias.', 'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6.75 7.5a.75.75 0 100-1.5.75.75 0 000 1.5z'],
                        ['title' => 'Equipe e permissões', 'body' => 'Convide colaboradores e mantenha a operação organizada.', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.646-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                        ['title' => 'Agente IA (em breve)', 'body' => 'Base preparada para integrações inteligentes no seu fluxo de trabalho.', 'icon' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z'],
                    ] as $feat)
                    <div class="group rounded-3xl border border-[#33363B]/5 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#D131A3]/35 hover:shadow-lg motion-reduce:hover:translate-y-0" data-aos="fade-up" data-aos-delay="{{ $loop->index * 90 }}">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#5B4FCF]/15 to-[#D131A3]/15 text-[#6A2BBA] transition duration-300 group-hover:scale-110 group-hover:from-[#6A2BBA]/25 group-hover:to-[#FF914D]/25 group-hover:shadow-md motion-reduce:group-hover:scale-100">
                            <svg class="h-6 w-6 motion-safe:transition-transform motion-safe:duration-300 group-hover:rotate-6 motion-reduce:group-hover:rotate-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon'] }}"/></svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-[#33363B]">{{ $feat['title'] }}</h3>
                        <p class="mt-2 text-sm font-medium leading-relaxed text-[#33363B]/65">{{ $feat['body'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Planos --}}
        <section id="planos" class="scroll-mt-24 border-y border-[#6A2BBA]/10 bg-gradient-to-b from-[#EDE9FE]/40 via-white to-[#FCE7F3]/30 px-4 py-16 md:py-24" aria-labelledby="planos-heading">
            <div class="mx-auto max-w-6xl">
                <p class="text-center text-sm font-bold uppercase tracking-wider text-[#6A2BBA]" data-aos="fade-up">Planos</p>
                <h2 id="planos-heading" class="font-display mt-2 text-center text-3xl font-extrabold text-[#33363B] md:text-4xl" data-aos="fade-up" data-aos-delay="50">Escolha o plano ideal para sua operação</h2>
                <p class="mx-auto mt-4 max-w-2xl text-center text-base font-medium text-[#33363B]/70" data-aos="fade-up" data-aos-delay="90">Do catálogo enxuto ao crescimento com mais produtos e recursos. Cancele quando quiser.</p>
                <div class="mt-14 grid gap-8 lg:grid-cols-2 lg:gap-10">
                    {{-- Plano Essencial --}}
                    <article class="flex flex-col rounded-[2rem] border-2 border-[#33363B]/10 bg-white p-8 shadow-xl ring-1 ring-[#33363B]/5 transition duration-300 hover:-translate-y-1 hover:shadow-2xl motion-reduce:hover:translate-y-0" data-aos="fade-up" data-aos-duration="750">
                        <h3 class="font-display text-xl font-extrabold text-[#33363B]">Essencial</h3>
                        <p class="mt-2 text-sm font-medium text-[#33363B]/65">Para quem está começando com vitrine e gestão no dia a dia.</p>
                        <p class="mt-6 flex items-baseline gap-1">
                            <span class="text-sm font-bold text-[#33363B]/60">R$</span>
                            <span class="text-5xl font-extrabold tracking-tight text-[#33363B]">49,99</span>
                            <span class="text-sm font-bold text-[#33363B]/50">/mês</span>
                        </p>
                        <ul class="mt-8 flex flex-col gap-3 text-sm font-semibold text-[#33363B]/80" role="list">
                            @foreach (['Catálogo online público', 'Gestão de produtos e variantes', 'Marcas e categorias', 'Até 50 produtos ativos', 'Suporte por e-mail'] as $item)
                            <li class="flex gap-2">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#6A2BBA]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('request.plan.form', ['plan' => 'essencial']) }}" class="mt-10 inline-flex w-full items-center justify-center rounded-2xl border-2 border-[#6A2BBA] bg-white py-4 text-center text-base font-bold text-[#6A2BBA] transition-all duration-300 hover:scale-[1.02] hover:bg-[#6A2BBA]/8 active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 motion-reduce:hover:scale-100">Começar com Essencial</a>
                    </article>
                    {{-- Plano Profissional (destaque) --}}
                    <article class="relative flex flex-col overflow-hidden rounded-[2rem] border-2 border-transparent bg-[#33363B] p-8 text-white shadow-2xl shadow-[#6A2BBA]/20 transition duration-300 hover:-translate-y-1 hover:shadow-[0_25px_50px_-12px_rgba(106,43,186,0.35)] motion-reduce:hover:translate-y-0" data-aos="fade-up" data-aos-duration="750" data-aos-delay="120">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#6A2BBA]/40 via-transparent to-[#D131A3]/30 pointer-events-none" aria-hidden="true"></div>
                        <div class="relative">
                            <span class="inline-block rounded-full bg-[#FF914D] px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-[#33363B]">Mais popular</span>
                            <h3 class="font-display mt-4 text-xl font-extrabold">Profissional</h3>
                            <p class="mt-2 text-sm font-medium text-white/75">Para lojas que querem escalar catálogo, time e vendas.</p>
                            <p class="mt-6 flex items-baseline gap-1">
                                <span class="text-sm font-bold text-white/60">R$</span>
                                <span class="text-5xl font-extrabold tracking-tight">79,99</span>
                                <span class="text-sm font-bold text-white/50">/mês</span>
                            </p>
                            <ul class="mt-8 flex flex-col gap-3 text-sm font-semibold text-white/90" role="list">
                                @foreach (['Tudo do plano Essencial', 'Até 200 produtos ativos', 'Gestão de pedidos e vendas', 'Membros da equipe', 'Relatórios e analytics', 'Prioridade no suporte'] as $item)
                                <li class="flex gap-2">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#FF914D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    {{ $item }}
                                </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('request.plan.form', ['plan' => 'profissional']) }}" class="mt-10 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#FF914D] to-[#D131A3] py-4 text-center text-base font-bold text-white shadow-lg transition-all duration-300 hover:scale-[1.02] hover:brightness-110 hover:shadow-xl active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[#33363B] motion-reduce:hover:scale-100">Começar com Profissional</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- Parceiros --}}
        <section id="parceiros" class="scroll-mt-24 bg-white px-4 py-16 md:py-24" aria-labelledby="parceiros-heading">
            <div class="mx-auto max-w-6xl">
                <p class="text-center text-sm font-bold uppercase tracking-wider text-[#D131A3]" data-aos="fade-up">Integrações</p>
                <h2 id="parceiros-heading" class="font-display mt-2 text-center text-3xl font-extrabold text-[#33363B] md:text-4xl" data-aos="fade-up" data-aos-delay="40">Lojas e marcas parceiras</h2>
                <p class="mx-auto mt-4 max-w-2xl text-center text-base font-medium text-[#33363B]/70" data-aos="fade-up" data-aos-delay="80">Conecte sua operação ao ecossistema de moda e varejo que seus clientes já conhecem.</p>
                <ul class="mt-14 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5 lg:gap-6" role="list">
                    @foreach ([
                        ['name' => 'Renner', 'hint' => 'Varejo multimarca'],
                        ['name' => 'C&A', 'hint' => 'Moda acessível'],
                        ['name' => 'Riachuelo', 'hint' => 'Estilo brasileiro'],
                        ['name' => 'Zara', 'hint' => 'Fast fashion'],
                        ['name' => 'Arezzo', 'hint' => 'Calçados e acessórios'],
                    ] as $partner)
                    <li data-aos="flip-left" data-aos-delay="{{ $loop->index * 100 }}" data-aos-duration="600">
                        <div class="group flex h-full flex-col items-center justify-center rounded-3xl border border-[#33363B]/8 bg-gradient-to-b from-[#F8F9FC] to-white p-6 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[#6A2BBA]/30 hover:shadow-lg motion-reduce:hover:translate-y-0">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#6A2BBA] to-[#D131A3] text-xl font-extrabold text-white shadow-md transition duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-xl motion-reduce:group-hover:scale-100 motion-reduce:group-hover:rotate-0" aria-hidden="true">{{ mb_substr($partner['name'], 0, 1) }}</span>
                            <p class="mt-4 text-base font-extrabold text-[#33363B]">{{ $partner['name'] }}</p>
                            <p class="mt-1 text-xs font-semibold text-[#33363B]/55">{{ $partner['hint'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <p class="mt-10 text-center text-sm font-medium text-[#33363B]/55">Marcas exibidas como referência de parceria; disponibilidade de integrações conforme roadmap do produto.</p>
            </div>
        </section>

        {{-- CTA final --}}
        <section class="px-4 py-16 md:py-20" aria-labelledby="cta-final-heading">
            <div class="mx-auto max-w-4xl overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#6A2BBA] via-[#D131A3] to-[#6366f1] p-1 shadow-2xl shadow-[#6A2BBA]/25" data-aos="zoom-in" data-aos-duration="800">
                <div class="rounded-[1.85rem] bg-[#33363B] px-8 py-12 text-center md:px-14 md:py-16">
                    <h2 id="cta-final-heading" class="font-display text-2xl font-extrabold text-white md:text-3xl">Pronto para turbinar sua vitrine?</h2>
                    <p class="mx-auto mt-4 max-w-xl text-base font-medium text-white/75">Cadastre-se, personalize sua loja e compartilhe o link com seus clientes hoje mesmo.</p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ route('request.plan.form', ['plan' => 'essencial']) }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-white px-8 py-4 text-base font-extrabold text-[#6A2BBA] shadow-lg transition-all duration-300 hover:scale-[1.03] hover:bg-[#F8F9FC] hover:shadow-xl active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF914D] focus-visible:ring-offset-2 focus-visible:ring-offset-[#33363B] motion-reduce:hover:scale-100 sm:w-auto">Começar com Essencial</a>
                        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-2xl border-2 border-white/40 px-8 py-4 text-base font-bold text-white transition-all duration-300 hover:scale-[1.03] hover:bg-white/10 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[#33363B] motion-reduce:hover:scale-100 sm:w-auto">Já tenho conta</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-[#33363B]/10 bg-white px-4 py-10" role="contentinfo">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 md:flex-row">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/vistuu-logo.png') }}" width="100" height="34" class="h-12 w-auto opacity-90" alt="">
                <p class="text-sm font-semibold text-[#33363B]/60">© {{ date('Y') }} Vistuu. Todos os direitos reservados.</p>
            </div>
            <nav class="flex flex-wrap items-center justify-center gap-4 text-sm font-bold text-[#6A2BBA]" aria-label="Rodapé">
                <a href="{{ route('login') }}" class="rounded-xl px-2 py-1 transition hover:text-[#D131A3] hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3]">Login</a>
                <a href="{{ route('request.plan.form', ['plan' => 'essencial']) }}" class="rounded-xl px-2 py-1 transition hover:text-[#D131A3] hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3]">Solicitar acesso</a>
            </nav>
        </div>
    </footer>
    <script>
        (function () {
            var header = document.getElementById('site-header');
            if (!header || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            function onScroll() {
                var y = window.scrollY > 16;
                header.classList.toggle('shadow-lg', y);
                header.classList.toggle('border-[#6A2BBA]/20', y);
                header.classList.toggle('bg-white/95', y);
            }
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();
    </script>
</body>
</html>
