<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} | {{ $partner->store->store_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand-blue: #1e3a5f;
            --brand-blue-light: #2563eb;
            --brand-blue-dark: #10243a;
            --brand-blue-soft: rgba(37, 99, 235, 0.12);
            --brand-accent: #f59e0b;
            --surface: #f8fafc;
            --surface-strong: #eef4fb;
            --border-soft: rgba(148, 163, 184, 0.18);
            --text-main: #0f172a;
            --text-soft: #64748b;
            --success: #059669;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #f1f5f9 100%);
            color: var(--text-main);
        }

        .pdp-header {
            background: rgba(255, 255, 255, 0.88);
            border-bottom: 1px solid rgba(226, 232, 240, 0.92);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .pdp-card {
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 16px 42px rgba(15, 23, 42, 0.08);
        }

        .pdp-hero-card {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 30%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.96));
        }

        .pdp-gallery-shell {
            padding: 20px;
            overflow: hidden;
        }

        .pdp-gallery-frame {
            border-radius: 24px;
            padding: 12px;
            background: linear-gradient(180deg, #ffffff 0%, var(--surface-strong) 100%);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .pdp-gallery-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 16px;
        }

        .pdp-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .pdp-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(226, 232, 240, 0.95);
            font-size: 0.72rem;
            font-weight: 700;
            color: #475569;
        }

        .thumb-slide {
            cursor: pointer;
            opacity: .58;
            transition: opacity .2s ease, transform .2s ease;
        }

        .thumb-slide:hover {
            opacity: 1;
            transform: translateY(-1px);
        }

        .thumb-slide.swiper-slide-thumb-active {
            opacity: 1;
        }

        .main-swiper {
            border-radius: 22px;
            overflow: hidden;
            background: #fff;
            cursor: zoom-in;
            box-shadow: inset 0 0 0 1px rgba(226, 232, 240, 0.9);
        }

        .main-swiper .swiper-slide img {
            width: 100%;
            height: 520px;
            object-fit: contain;
            background: linear-gradient(180deg, #ffffff, #eef4fb);
        }

        @media(max-width: 640px) {
            .main-swiper .swiper-slide img {
                height: 320px;
            }
        }

        .thumb-swiper .swiper-slide img {
            height: 78px;
            width: 78px;
            object-fit: cover;
            border-radius: 16px;
            border: 2px solid transparent;
            background: #fff;
        }

        .thumb-swiper .swiper-slide-thumb-active img {
            border-color: var(--brand-blue-light);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.18);
        }

        .sticky-sidebar {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        @media(min-width: 1024px) {
            .sticky-sidebar {
                position: sticky;
                top: 88px;
            }
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--brand-blue-light) !important;
        }

        .swiper-pagination-bullet-active {
            background: var(--brand-blue-light) !important;
        }

        .info-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(239, 246, 255, 0.95);
            border: 1px solid rgba(191, 219, 254, 0.95);
            font-size: 0.73rem;
            font-weight: 700;
            color: var(--brand-blue-light);
        }

        .pdp-title {
            font-size: clamp(2rem, 2.7vw, 3rem);
            line-height: 1.05;
            font-weight: 800;
            color: var(--text-main);
        }

        .pdp-lead {
            color: var(--text-soft);
            font-size: 0.96rem;
            line-height: 1.75;
        }

        .pdp-price-wrap {
            padding: 18px 20px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(16, 36, 58, 0.98), rgba(37, 99, 235, 0.95));
            color: #fff;
            box-shadow: 0 18px 36px rgba(30, 58, 95, 0.2);
        }

        .pdp-price-wrap .pdp-price-caption {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.68);
        }

        .pdp-price-wrap #mainPrice {
            color: #fff;
        }

        .pdp-price-support {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.78);
        }

        .pdp-highlight-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(245, 158, 11, 0.18);
            color: #fef3c7;
            border: 1px solid rgba(253, 224, 71, 0.22);
            font-size: 0.72rem;
            font-weight: 800;
        }

        .pdp-trust-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 12px;
        }

        @media(min-width: 640px) {
            .pdp-trust-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .pdp-trust-card {
            padding: 18px;
            border-radius: 22px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .pdp-trust-card__icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(239, 246, 255, 0.95);
            color: var(--brand-blue-light);
        }

        .pdp-trust-card h3 {
            margin: 14px 0 0;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .pdp-trust-card p {
            margin: 8px 0 0;
            font-size: 0.82rem;
            line-height: 1.6;
            color: var(--text-soft);
        }

        .pdp-section-eyebrow {
            margin: 0 0 10px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .variant-btn {
            min-height: 44px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 8px 15px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s ease;
            background: #fff;
            color: #334155;
        }

        .variant-btn.selected {
            border-color: var(--brand-blue-light);
            background: rgba(239, 246, 255, 0.95);
            color: var(--brand-blue-light);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.12);
        }

        .variant-btn:disabled {
            opacity: .38;
            cursor: not-allowed;
        }

        .color-swatch {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 4px solid rgba(255, 255, 255, 0.95);
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease;
            box-shadow: 0 0 0 2px #dbe3ef;
        }

        .color-swatch:hover {
            transform: translateY(-1px);
        }

        .color-swatch.selected {
            box-shadow: 0 0 0 3px var(--brand-blue-light), 0 10px 18px rgba(37, 99, 235, 0.18);
        }

        .payment-btn {
            border: 2px solid #e2e8f0;
            border-radius: 18px;
            padding: 14px;
            cursor: pointer;
            transition: all .18s ease;
            background: #fff;
            text-align: left;
        }

        .payment-btn:hover {
            border-color: rgba(59, 130, 246, 0.35);
            transform: translateY(-1px);
        }

        .payment-btn.selected {
            border-color: var(--brand-blue-light);
            background: rgba(239, 246, 255, 0.92);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.1);
        }

        .pdp-help-list {
            display: grid;
            gap: 10px;
        }

        .pdp-help-item {
            display: flex;
            align-items: start;
            gap: 12px;
            padding: 14px;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.92));
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .pdp-help-item strong {
            display: block;
            font-size: 0.88rem;
            color: var(--text-main);
        }

        .pdp-help-item span {
            display: block;
            margin-top: 2px;
            font-size: 0.78rem;
            line-height: 1.55;
            color: var(--text-soft);
        }

        .delivery-opt {
            border-radius: 18px;
            transition: all .18s ease;
        }

        .pdp-spec-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        @media(min-width: 640px) {
            .pdp-spec-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .pdp-spec-item {
            padding: 15px;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.92));
            border: 1px solid rgba(226, 232, 240, 0.95);
            min-height: 100%;
        }

        .pdp-spec-item span {
            display: block;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .pdp-spec-item strong {
            display: block;
            margin-top: 8px;
            font-size: 0.98rem;
            color: var(--text-main);
        }

        .pdp-story-card {
            padding: 24px;
        }

        .pdp-story-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 14px;
        }

        @media(min-width: 768px) {
            .pdp-story-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .pdp-story-block {
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.94));
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        .pdp-story-block p {
            color: var(--text-soft);
            font-size: 0.92rem;
            line-height: 1.8;
        }

        .pdp-related-section {
            margin-top: 40px;
        }

        .related-card {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            overflow: hidden;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .related-card:hover {
            transform: translateY(-4px);
            border-color: rgba(59, 130, 246, 0.25);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
        }

        .related-card__media {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #f8fafc, #e2e8f0);
        }

        .related-card__media img {
            transition: transform .35s ease;
        }

        .related-card:hover .related-card__media img {
            transform: scale(1.03);
        }

        .related-card__body {
            padding: 14px;
        }

        .pdp-primary-btn {
            background: linear-gradient(135deg, var(--brand-blue), var(--brand-blue-light));
            color: #fff;
            box-shadow: 0 16px 32px rgba(37, 99, 235, 0.22);
        }

        .pdp-primary-btn:hover {
            filter: brightness(1.02);
        }

        .pdp-outline-btn {
            border: 1px solid rgba(203, 213, 225, 0.95);
            background: rgba(255, 255, 255, 0.92);
            color: #334155;
        }

        .pdp-outline-btn:hover {
            border-color: rgba(59, 130, 246, 0.35);
            background: rgba(239, 246, 255, 0.92);
            color: var(--brand-blue-light);
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f1f5f9;
            font-size: 18px;
            cursor: pointer;
            user-select: none;
            transition: background .15s;
        }

        .qty-btn:hover {
            background: #e2e8f0;
        }

        .qty-btn:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .pdp-bottom-bar {
            background: rgba(255, 255, 255, 0.92);
            border-top: 1px solid rgba(226, 232, 240, 0.92);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 -8px 30px rgba(15, 23, 42, 0.08);
        }

        .pdp-bottom-bar__price {
            min-width: 0;
        }

        .pdp-bottom-bar__price span {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .pdp-bottom-bar__price strong {
            display: block;
            font-size: 1.1rem;
            line-height: 1.1;
            color: var(--brand-blue-dark);
        }

        @media(max-width: 767px) {
            .pdp-bottom-bar__price {
                display: none;
            }
        }

        @keyframes pdp-fade-up {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pdp-reveal {
            opacity: 0;
            transform: translateY(18px);
        }

        .pdp-reveal.is-visible {
            animation: pdp-fade-up .5s ease forwards;
        }

        /* Lightbox */
        .lightbox-overlay{position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9998;display:none;align-items:center;justify-content:center;flex-direction:column}
        .lightbox-overlay.active{display:flex}
        .lightbox-close{position:absolute;top:16px;right:16px;z-index:9999;background:rgba(255,255,255,.15);border:none;color:#fff;width:40px;height:40px;border-radius:50%;font-size:22px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}
        .lightbox-close:hover{background:rgba(255,255,255,.3)}
        .lightbox-img-wrap{position:relative;max-width:90vw;max-height:80vh;overflow:hidden;display:flex;align-items:center;justify-content:center}
        .lightbox-img-wrap img{max-width:90vw;max-height:80vh;object-fit:contain;transition:transform .3s ease;cursor:grab;touch-action:none}
        .lightbox-img-wrap img.zoomed{cursor:move}
        .lightbox-nav{display:flex;gap:12px;margin-top:16px}
        .lightbox-nav button{background:rgba(255,255,255,.15);border:none;color:#fff;width:44px;height:44px;border-radius:50%;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}
        .lightbox-nav button:hover{background:rgba(255,255,255,.3)}
        .lightbox-counter{color:rgba(255,255,255,.6);font-size:13px;margin-top:8px;font-weight:600}

        /* Toast */
        .pdp-toast{position:fixed;bottom:100px;left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;padding:10px 20px;border-radius:12px;font-size:13px;font-weight:600;z-index:9999;opacity:0;transition:opacity .3s;pointer-events:none}
        .pdp-toast.show{opacity:1}
    </style>
</head>
<body class="pb-28">

<div id="globalLoaderPdp" class="fixed inset-0 bg-white/80 z-[9999] hidden items-center justify-center">
    <svg class="w-10 h-10 animate-spin text-[var(--brand-blue-light)]" viewBox="0 0 64 64"><path d="M32 64a32 32 0 1 1 32-32h-4a28 28 0 1 0-28 28z" fill="currentColor"/><path d="M32 0a32 32 0 0 1 32 32h-4a28 28 0 0 0-28-28z" fill="currentColor"/></svg>
</div>

<!-- Header -->
<header class="pdp-header fixed top-0 left-0 right-0 z-50 h-16 flex items-center px-4 md:px-8 justify-between">
    <div class="flex items-center gap-3 min-w-0">
        @if($logoStore)
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                <img src="{{ $logoStore }}" class="w-full h-full object-cover" alt="Logo da loja">
            </div>
        @endif
        <div class="min-w-0">
            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Loja oficial</p>
            <span class="block truncate font-bold text-slate-800 text-base">{{ $partner->store->store_name }}</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openCart()" class="pdp-outline-btn relative p-2.5 rounded-2xl transition">
            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
            <span id="cartBadge" class="hidden absolute -top-1 -right-1 bg-[var(--brand-blue-light)] text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">0</span>
        </button>
        <a href="{{ route('catalog.index', $partner->partner_link) }}" class="flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-[var(--brand-blue-light)] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>
</header>

@php
    $variants      = $product->variants;
    $hasVariants   = $variants->count() > 0;
    $colors        = $variants->whereNotNull('color')->unique('color')->values();
    $sizes         = $variants->whereNotNull('size')->pluck('size')->unique()->values();
    $hasColors     = $colors->count() > 0;
    $hasSizes      = $sizes->count() > 0;
    $storePhone    = preg_replace('/\D/', '', $partner->store->store_phone ?? '');
    $basePrice     = ($product->price_promotional && $product->price_promotional > 0 && $product->price_promotional < $product->price)
                        ? $product->price_promotional : $product->price;
    $defaultImage  = $images->isNotEmpty()
                        ? asset('storage/' . str_replace('public/', '', $images->first()->url))
                        : '/img/image-not-found.png';
    $descriptionText = trim(strip_tags((string) ($product->description ?? '')));
    $shortDescription = $descriptionText !== ''
                        ? \Illuminate\Support\Str::limit($descriptionText, 180)
                        : 'Uma selecao apresentada com foco em clareza, confianca e decisao rapida para o cliente.';
    $referencePrice = ($product->old_price && $product->old_price > $basePrice) ? $product->old_price : $product->price;
    $hasReferencePrice = $referencePrice && $referencePrice > $basePrice;
    $discountPercent = $hasReferencePrice ? (int) round((1 - ($basePrice / $referencePrice)) * 100) : null;
    $pixPrice = ($product->discount_pix && $product->discount_pix > 0) ? $basePrice * (1 - $product->discount_pix / 100) : null;
    $installmentValue = ($product->installments && $product->installments > 1) ? $basePrice / $product->installments : null;
@endphp

<main class="pt-24 px-4 md:px-8 max-w-screen-2xl mx-auto">
    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(340px,460px)]">
        <div class="space-y-5">
            <div class="pdp-card pdp-hero-card pdp-gallery-shell pdp-reveal">
                <div class="pdp-gallery-meta">
                    <div class="flex flex-wrap gap-2">
                        <span class="pdp-pill">Galeria do produto</span>
                        <span class="pdp-pill">Toque para ampliar</span>
                    </div>
                    <span class="pdp-eyebrow">Experiencia visual mais intuitiva</span>
                </div>

                <div class="pdp-gallery-frame">
                    <div class="main-swiper swiper swiper-main shadow-sm" id="mainSwiperContainer">
                        <div class="swiper-wrapper">
                            @if(empty($variantsByColor[0]['images']) || $variantsByColor[0]['images']->isEmpty())
                                <div class="swiper-slide"><img src="/img/image-not-found.png" alt="Sem imagem do produto"></div>
                            @else
                                @foreach($variantsByColor[0]['images'] as $image)
                                    <div class="swiper-slide"><img src="{{ asset('storage/' . str_replace('public/', '', $image->url)) }}" alt="{{ $product->name }}"></div>
                                @endforeach
                            @endif
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <div class="thumb-swiper swiper mt-4 px-1" id="thumbSwiperContainer">
                        <div class="swiper-wrapper">
                            @if(!empty($variantsByColor[0]['images']) && $variantsByColor[0]['images']->count() > 1)
                                @foreach($variantsByColor[0]['images'] as $image)
                                    <div class="swiper-slide thumb-slide w-auto">
                                        <img src="{{ asset('storage/' . str_replace('public/', '', $image->url)) }}" alt="Miniatura do produto {{ $product->name }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="pdp-trust-grid">
                <article class="pdp-trust-card pdp-reveal" style="animation-delay: 0.04s;">
                    <div class="pdp-trust-card__icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.314 0-6 2.239-6 5 0 2.761 2.686 5 6 5s6-2.239 6-5c0-2.761-2.686-5-6-5zm0 0V5m0 13v3m7-8h3M2 13h3"/></svg>
                    </div>
                    <h3>Visual forte e organizado</h3>
                    <p>Galeria destacada, leitura mais limpa e decisoes importantes reunidas perto da compra.</p>
                </article>

                <article class="pdp-trust-card pdp-reveal" style="animation-delay: 0.08s;">
                    <div class="pdp-trust-card__icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.314 0-6 2.239-6 5 0 2.761 2.686 5 6 5s6-2.239 6-5c0-2.761-2.686-5-6-5zm0 0V5m0 13v3m7-8h3M2 13h3"/></svg>
                    </div>
                    <h3>Escolha intuitiva</h3>
                    <p>Cores, tamanhos e pagamentos ficam agrupados em blocos simples para acelerar a conversao.</p>
                </article>

                <article class="pdp-trust-card pdp-reveal" style="animation-delay: 0.12s;">
                    <div class="pdp-trust-card__icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3>Confiança na compra</h3>
                    <p>Informacoes de estoque, entrega e atendimento aparecem de forma consistente com o visual do catalogo.</p>
                </article>
            </div>
        </div>

        <div class="sticky-sidebar">
            <section class="pdp-card pdp-hero-card p-6 md:p-7 pdp-reveal">
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($category)<span class="info-chip"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>{{ $category->name }}</span>@endif
                    @if($product->brand)<span class="info-chip">{{ $product->brand->name }}</span>@endif
                    @if($product->gender)@php $gm=['M'=>'Masculino','F'=>'Feminino','U'=>'Unissex'];@endphp<span class="info-chip">{{ $gm[$product->gender] ?? $product->gender }}</span>@endif
                </div>

                <div class="pdp-eyebrow mb-3">Pagina de produto</div>
                <h1 class="pdp-title">{{ $product->name }}</h1>
                <p class="pdp-lead mt-4">{{ $shortDescription }}</p>

                <div class="mt-4" id="stockBadge">
                    @if(!$hasVariants && $product->stock !== null)
                        @if($product->stock > 5)<span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full inline-flex">Em estoque ({{ $product->stock }} un.)</span>
                        @elseif($product->stock > 0)<span class="text-xs font-semibold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full inline-flex">Ultimas {{ $product->stock }} unidades</span>
                        @else<span class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1.5 rounded-full inline-flex">Sem estoque</span>@endif
                    @endif
                </div>

                <div class="pdp-price-wrap mt-5" id="priceBlock">
                    <span class="pdp-price-caption">Preco principal</span>
                    <div class="mt-2 flex flex-wrap items-end gap-3">
                        <span id="mainPrice" class="text-4xl font-extrabold">R$ {{ number_format($basePrice, 2, ',', '.') }}</span>
                        @if($hasReferencePrice)
                            <span id="referencePrice" class="text-base text-white/60 line-through mb-1">R$ {{ number_format($referencePrice, 2, ',', '.') }}</span>
                        @endif
                        @if($discountPercent)
                            <span id="discountBadge" class="pdp-highlight-badge mb-1">-{{ $discountPercent }}%</span>
                        @endif
                    </div>

                    <div class="pdp-price-support">
                        @if($pixPrice)
                            <span id="pixSummary">Pix por R$ {{ number_format($pixPrice, 2, ',', '.') }}</span>
                        @endif
                        @if($installmentValue)
                            <span id="installmentSummary">{{ $product->installments }}x de R$ {{ number_format($installmentValue, 2, ',', '.') }}</span>
                        @endif
                        <span>Compra orientada via WhatsApp</span>
                    </div>
                </div>

                @if($product->price_wholesale && $product->price_wholesale > 0)
                    <div class="mt-4" id="wholesaleInfo">
                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700">
                            Atacado: R$ {{ number_format($product->price_wholesale, 2, ',', '.') }}
                            @if($wholesaleMinQty)
                                — a partir de {{ $wholesaleMinQty }} pecas
                            @endif
                        </span>
                    </div>
                @endif

                @if($hasColors)
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="pdp-section-eyebrow">Cor selecionada</p>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <p class="text-sm font-semibold text-slate-800">Escolha a variacao ideal</p>
                            <span id="selectedColorLabel" class="text-sm font-semibold text-[var(--brand-blue-light)]"></span>
                        </div>
                        <div class="flex flex-wrap gap-3" id="colorSwatches">
                            @foreach($colors as $v)
                                <button type="button"
                                    class="color-swatch @if($loop->first) selected @endif"
                                    data-color="{{ $v->color }}"
                                    data-hex="{{ $v->color_hex ?? '#cccccc' }}"
                                    style="background-color: {{ $v->color_hex ?? '#cccccc' }}"
                                    title="{{ $v->color }}"
                                    aria-label="Selecionar cor {{ $v->color }}">
                                </button>
                            @endforeach
                        </div>
                        <p id="errColor" class="hidden text-xs text-red-500 mt-2">Selecione uma cor</p>
                    </div>
                @elseif($product->color)
                    <div class="mt-4"><span class="info-chip">{{ $product->color }}</span></div>
                @endif

                @if($hasSizes)
                    <div class="mt-5">
                        <p class="pdp-section-eyebrow">Tamanho</p>
                        <div class="flex flex-wrap gap-2" id="sizeButtons">
                            @foreach($sizes as $sz)
                                <button type="button" class="variant-btn" data-size="{{ $sz }}">{{ $sz }}</button>
                            @endforeach
                        </div>
                        <p id="errSize" class="hidden text-xs text-red-500 mt-2">Selecione um tamanho</p>
                    </div>
                @elseif($product->size)
                    <div class="mt-4"><span class="info-chip">Tam: {{ $product->size }}</span></div>
                @endif

                <div class="mt-6 pt-5 border-t border-slate-100">
                    <p class="pdp-section-eyebrow">Forma de pagamento</p>
                    <div class="grid grid-cols-1 gap-3" id="paymentOptions">
                        @if($product->discount_pix && $product->discount_pix > 0)
                            <button type="button" class="payment-btn selected" data-method="pix">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M11.9 2C6.4 2 2 6.4 2 11.9s4.4 9.9 9.9 9.9 9.9-4.4 9.9-9.9S17.4 2 11.9 2zm4.3 13.4l-2.4-2.4c-.3.1-.6.2-.9.2s-.6-.1-.9-.2l-2.4 2.4c-.2.2-.4.3-.7.3s-.5-.1-.7-.3c-.4-.4-.4-1 0-1.4l2.4-2.4c-.1-.3-.2-.6-.2-.9s.1-.6.2-.9L8.2 7.4c-.4-.4-.4-1 0-1.4s1-.4 1.4 0l2.4 2.4c.3-.1.6-.2.9-.2s.6.1.9.2l2.4-2.4c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-2.4 2.4c.1.3.2.6.2.9s-.1.6-.2.9l2.4 2.4c.4.4.4 1 0 1.4-.2.2-.4.3-.7.3s-.5-.1-.7-.3z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">PIX com desconto</p>
                                        <p id="pixPaymentLabel" class="text-xs text-emerald-600 font-semibold">
                                            {{ $product->discount_pix }}% off — R$ {{ number_format($pixPrice, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @endif

                        @if($product->installments && $product->installments > 1)
                            <button type="button" class="payment-btn" data-method="credit_card">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Cartao de credito</p>
                                        <p id="installmentPaymentLabel" class="text-xs text-blue-600 font-semibold">
                                            {{ $product->installments }}x de R$ {{ number_format($installmentValue, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </button>
                        @endif

                        <button type="button" class="payment-btn" data-method="cash">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Pagamento a vista</p>
                                    <p class="text-xs text-slate-500 font-semibold">Confirme condicoes direto com a loja</p>
                                </div>
                            </div>
                        </button>
                    </div>
                    <p id="errPayment" class="hidden text-xs text-red-500 mt-2">Selecione uma forma de pagamento</p>
                </div>
            </section>

            <section class="pdp-card p-5 md:p-6 pdp-reveal" style="animation-delay: 0.06s;">
                <p class="pdp-section-eyebrow">Entrega e retirada</p>
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <button type="button" id="btnPickup" onclick="setDelivery('pickup')"
                        class="delivery-opt selected flex flex-col items-center gap-2 border-2 border-[var(--brand-blue-light)] bg-blue-50 rounded-2xl p-3 transition">
                        <svg class="w-6 h-6 text-[var(--brand-blue-light)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="text-xs font-bold text-[var(--brand-blue)]">Retirar na loja</span>
                    </button>
                    <button type="button" id="btnDelivery" onclick="setDelivery('delivery')"
                        class="delivery-opt flex flex-col items-center gap-2 border-2 border-gray-200 bg-white rounded-2xl p-3 transition hover:border-blue-300">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <span class="text-xs font-bold text-gray-600">Entrega</span>
                    </button>
                </div>

                <div class="pdp-help-list mb-4">
                    <div class="pdp-help-item">
                        <svg class="w-5 h-5 mt-0.5 text-[var(--brand-blue-light)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <strong>Defina como deseja receber</strong>
                            <span>O cliente consegue escolher rapidamente entre retirada e entrega sem sair da PDP.</span>
                        </div>
                    </div>
                </div>

                <div id="deliveryFields" class="hidden space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">CEP <span class="text-red-500">*</span></label>
                            <input type="text" id="deliveryZip" placeholder="00000-000" class="cep-mask w-full border border-gray-200 rounded-2xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Endereco <span class="text-red-500">*</span></label>
                            <input type="text" id="deliveryAddress" placeholder="Rua, numero" class="w-full border border-gray-200 rounded-2xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Cidade <span class="text-red-500">*</span></label>
                            <input type="text" id="deliveryCity" placeholder="Cidade" class="w-full border border-gray-200 rounded-2xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Estado</label>
                            <input type="text" id="deliveryState" placeholder="UF" maxlength="2" class="w-full border border-gray-200 rounded-2xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition uppercase">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Complemento</label>
                            <input type="text" id="deliveryComplement" placeholder="Apto, bloco..." class="w-full border border-gray-200 rounded-2xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>
                    <p id="errDelivery" class="hidden text-xs text-red-500">Preencha o endereco de entrega</p>
                </div>
            </section>
        </div>
    </section>

    @php $hasDim = $product->width || $product->height || $product->length || $product->weight; @endphp
    <section class="grid gap-6 mt-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
        <article class="pdp-card pdp-story-card pdp-reveal" style="animation-delay: 0.08s;">
            <p class="pdp-section-eyebrow">Detalhes que ajudam a decidir</p>
            <div class="pdp-story-grid">
                <div class="pdp-story-block">
                    <h2 class="text-2xl font-extrabold text-slate-900">Uma PDP mais convidativa, sem fugir do seu padrao</h2>
                    <p class="mt-4">
                        {{ $descriptionText !== '' ? $descriptionText : 'Este produto agora ganha uma apresentacao com mais respiro visual, foco no preco, destaque para variacoes e uma leitura mais natural para o cliente chegar mais rapido na decisao de compra.' }}
                    </p>
                </div>

                <div class="pdp-story-block">
                    <h3 class="text-lg font-extrabold text-slate-900">O que o cliente encontra aqui</h3>
                    <div class="pdp-help-list mt-4">
                        <div class="pdp-help-item">
                            <svg class="w-5 h-5 mt-0.5 text-[var(--brand-blue-light)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <div><strong>Informacao priorizada</strong><span>Preco, estoque e selecao aparecem logo no primeiro bloco de decisao.</span></div>
                        </div>
                        <div class="pdp-help-item">
                            <svg class="w-5 h-5 mt-0.5 text-[var(--brand-blue-light)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <div><strong>Fluxo mais rapido</strong><span>Menos friccao para escolher variacao, forma de pagamento e seguir para o carrinho.</span></div>
                        </div>
                        <div class="pdp-help-item">
                            <svg class="w-5 h-5 mt-0.5 text-[var(--brand-blue-light)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-1 0h12a1 1 0 011 1v9a1 1 0 01-1 1H6a1 1 0 01-1-1v-9a1 1 0 011-1z"/></svg>
                            <div><strong>Compra com mais confianca</strong><span>Entrega, especificacoes e suporte comercial ficam estruturados em cards coerentes com o catalogo.</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <div class="space-y-6">
            @if($hasDim || $product->size)
                <section class="pdp-card p-5 md:p-6 pdp-reveal" style="animation-delay: 0.1s;">
                    <p class="pdp-section-eyebrow">Especificacoes</p>
                    <div class="pdp-spec-grid">
                        @if($product->size)<div class="pdp-spec-item"><span>Tamanho base</span><strong>{{ $product->size }}</strong></div>@endif
                        @if($product->width)<div class="pdp-spec-item"><span>Largura</span><strong>{{ $product->width }} cm</strong></div>@endif
                        @if($product->height)<div class="pdp-spec-item"><span>Altura</span><strong>{{ $product->height }} cm</strong></div>@endif
                        @if($product->length)<div class="pdp-spec-item"><span>Comprimento</span><strong>{{ $product->length }} cm</strong></div>@endif
                        @if($product->weight)<div class="pdp-spec-item"><span>Peso</span><strong>{{ $product->weight }} kg</strong></div>@endif
                    </div>
                </section>
            @endif

            <section class="pdp-card p-5 md:p-6 pdp-reveal" style="animation-delay: 0.14s;">
                <p class="pdp-section-eyebrow">Atendimento e suporte</p>
                <div class="pdp-help-list">
                    <div class="pdp-help-item">
                        <svg class="w-5 h-5 mt-0.5 text-[var(--brand-blue-light)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <div>
                            <strong>Contato rapido com a loja</strong>
                            <span>O cliente pode tirar duvidas e concluir o pedido pelo WhatsApp com o contexto do produto selecionado.</span>
                        </div>
                    </div>
                    <div class="pdp-help-item">
                        <svg class="w-5 h-5 mt-0.5 text-[var(--brand-blue-light)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.314 0-6 2.239-6 5s2.686 5 6 5 6-2.239 6-5-2.686-5-6-5zm0 0V5m0 13v3"/></svg>
                        <div>
                            <strong>Pagamento explicado na tela</strong>
                            <span>Pix, cartao e compra a vista ficam claros antes mesmo do cliente abrir o carrinho.</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>

    @if($related->count() > 0)
        <section class="pdp-related-section mb-4">
            <div class="flex flex-col gap-2 mb-5 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="pdp-section-eyebrow mb-2">Continue explorando</p>
                    <h2 class="text-2xl font-extrabold text-slate-900">Produtos relacionados</h2>
                </div>
                <p class="text-sm text-slate-500">Mais opcoes com o mesmo cuidado visual do catalogo.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                @foreach($related as $rel)
                    <a href="{{ route('catalog.productPage', [$partner->partner_link, $rel->id]) }}" class="related-card block pdp-reveal" style="animation-delay: {{ min(($loop->index + 1) * 0.04, 0.2) }}s;">
                        <div class="related-card__media aspect-square">
                            @if($rel->images->isNotEmpty())
                                <img src="{{ asset('storage/' . str_replace('public/', '', $rel->images->first()->url)) }}"
                                     class="w-full h-full object-cover object-center" alt="{{ $rel->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="related-card__body">
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $rel->brand?->name ?? 'Colecao' }}</p>
                            <p class="text-sm font-semibold text-slate-800 mt-2 truncate">{{ $rel->name }}</p>
                            <div class="mt-3 flex items-center justify-between gap-3">
                                <p class="text-base font-extrabold text-[var(--brand-blue)]">R$ {{ number_format($rel->price, 2, ',', '.') }}</p>
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full text-white bg-gradient-to-br from-[var(--brand-blue)] to-[var(--brand-blue-light)] shadow-[0_10px_20px_rgba(37,99,235,0.22)]">+</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</main>

<!-- Barra fixa inferior -->
<div class="pdp-bottom-bar fixed bottom-0 left-0 right-0 z-40 px-4 py-3">
    <div class="max-w-screen-2xl mx-auto flex items-center gap-2 md:gap-3">
        <div class="pdp-bottom-bar__price">
            <span>Preco atual</span>
            <strong id="stickyBarPrice">R$ {{ number_format($basePrice, 2, ',', '.') }}</strong>
        </div>
        <button id="btnAddToCart" class="pdp-primary-btn flex-1 flex items-center justify-center gap-2 font-bold py-3.5 rounded-2xl transition text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
            Adicionar ao carrinho
        </button>
        <button onclick="openCart()" class="pdp-outline-btn relative flex items-center justify-center font-bold py-3.5 px-4 rounded-2xl transition flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
            <span id="cartBadgeBar" class="hidden absolute -top-1.5 -right-1.5 bg-[var(--brand-blue-light)] text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
        </button>
        <a href="https://wa.me/55{{ $storePhone }}?text={{ urlencode('Olá! Tenho interesse no produto ' . $product->name . '. Poderia me passar mais informações?') }}"
            target="_blank" class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 px-4 rounded-2xl transition flex-shrink-0">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
    </div>
</div>

<!-- Cart Drawer -->
<div id="cartOverlay" class="fixed inset-0 bg-black/40 z-[60] hidden" onclick="closeCart()"></div>
<div id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-sm bg-white z-[70] shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-900">Seu carrinho</h2>
        <button onclick="closeCart()" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div id="cartItems" class="flex-1 overflow-y-auto px-5 py-4 space-y-3"></div>
    <div id="cartEmpty" class="flex-1 flex flex-col items-center justify-center text-center px-6 hidden">
        <svg class="w-14 h-14 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
        <p class="text-gray-400 font-semibold text-sm">Carrinho vazio</p>
    </div>
    <div id="cartFooter" class="hidden px-5 py-4 border-t border-gray-100 space-y-3">
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">Total</span>
            <span id="cartTotal" class="text-xl font-extrabold text-[var(--brand-blue)]">R$ 0,00</span>
        </div>
        <input type="text" id="cartName" placeholder="Seu nome completo" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <p id="errCartName" class="hidden text-xs text-red-500 -mt-1">Campo obrigatório</p>
        <input type="text" id="cartPhone" placeholder="(00) 00000-0000" class="phone-mask w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <p id="errCartPhone" class="hidden text-xs text-red-500 -mt-1">Campo obrigatório</p>
        <button id="btnCheckout" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Finalizar via WhatsApp
        </button>
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox-overlay" id="lightbox" role="dialog" aria-modal="true" aria-label="Galeria de imagens">
    <button class="lightbox-close" id="lightboxClose" aria-label="Fechar galeria">&times;</button>
    <div class="lightbox-img-wrap" id="lightboxWrap">
        <img id="lightboxImg" src="" alt="Zoom" draggable="false">
    </div>
    <div class="lightbox-nav">
        <button id="lightboxPrev" aria-label="Anterior">&#8249;</button>
        <button id="lightboxNext" aria-label="Próxima">&#8250;</button>
    </div>
    <div class="lightbox-counter" id="lightboxCounter"></div>
</div>

<!-- Toast -->
<div class="pdp-toast" id="pdpToast"></div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
(function() {
    // ── Server data ──
    const VARIANTS        = @json($variants->values());
    const IMAGES_BY_COLOR = @json($imagesByColor);
    const HAS_COLORS      = {{ $hasColors ? 'true' : 'false' }};
    const HAS_SIZES       = {{ $hasSizes  ? 'true' : 'false' }};
    const HAS_VARIANTS    = {{ $hasVariants ? 'true' : 'false' }};
    const STORE_KEY       = 'pdp_cart_{{ $partner->store->id }}';
    const STORE_ID        = {{ $partner->store->id }};
    const STORE_PHONE     = '{{ $storePhone }}';
    const PRODUCT_ID      = {{ $product->id }};
    const PRODUCT_NAME    = @json($product->name);
    const DEFAULT_IMG     = @json($defaultImage);
    const BASE_PRICE      = {{ $basePrice }};
    const REFERENCE_PRICE = {{ $referencePrice ?? 0 }};
    const PRODUCT_STOCK   = {{ $product->stock ?? 0 }};
    const WHOLESALE_PRICE = {{ $product->price_wholesale ?? 0 }};
    const WHOLESALE_MIN   = {{ $wholesaleMinQty ?? 0 }};
    const DISCOUNT_PIX    = {{ $product->discount_pix ?? 0 }};
    const INSTALLMENTS_COUNT = {{ $product->installments ?? 0 }};

    // ── State ──
    let selectedColor   = null;
    let selectedSize    = null;
    let selectedPayment = null;
    let deliveryType    = 'pickup';
    let currentImages   = [];

    // ── Helpers ──
    function formatPrice(v) {
        return v.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function showToast(msg) {
        const el = document.getElementById('pdpToast');
        el.textContent = msg;
        el.classList.add('show');
        setTimeout(() => el.classList.remove('show'), 2500);
    }

    function initRevealAnimations() {
        const items = document.querySelectorAll('.pdp-reveal');
        if (!items.length) return;

        if (!('IntersectionObserver' in window)) {
            items.forEach(item => item.classList.add('is-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px',
        });

        items.forEach(item => observer.observe(item));
    }

    // ── Swiper ──
    let mainSwiper, thumbSwiper;

    function initSwipers() {
        const mainEl = document.getElementById('mainSwiperContainer');
        const thumbEl = document.getElementById('thumbSwiperContainer');

        if (mainSwiper) { mainSwiper.destroy(true, true); mainSwiper = null; }
        if (thumbSwiper) { thumbSwiper.destroy(true, true); thumbSwiper = null; }

        const hasMultiple = mainEl.querySelectorAll('.swiper-slide').length > 1;

        mainSwiper = new Swiper(mainEl, {
            loop: hasMultiple,
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            pagination: { el: '.swiper-pagination', clickable: true },
        });

        if (hasMultiple) {
            thumbSwiper = new Swiper(thumbEl, {
                spaceBetween: 8,
                slidesPerView: 'auto',
                watchSlidesProgress: true,
                slideToClickedSlide: true,
            });
            mainSwiper.thumbs.swiper = thumbSwiper;
            mainSwiper.thumbs.init();
            mainSwiper.thumbs.update();
        }
    }

    function rebuildSwiperSlides(urls) {
        currentImages = urls && urls.length ? urls : ['/img/image-not-found.png'];

        const mainWrapper = document.querySelector('#mainSwiperContainer .swiper-wrapper');
        const thumbWrapper = document.querySelector('#thumbSwiperContainer .swiper-wrapper');

        mainWrapper.innerHTML = currentImages.map(u =>
            `<div class="swiper-slide"><img src="${u}" alt="${PRODUCT_NAME}"></div>`
        ).join('');

        thumbWrapper.innerHTML = currentImages.length > 1
            ? currentImages.map(u =>
                `<div class="swiper-slide thumb-slide w-auto"><img src="${u}" alt=""></div>`
            ).join('')
            : '';

        initSwipers();
    }

    // ── Lightbox ──
    let lightboxIndex = 0;
    let zoomLevel = 1;
    const lightboxOverlay = document.getElementById('lightbox');
    const lightboxImg     = document.getElementById('lightboxImg');
    const lightboxCounter = document.getElementById('lightboxCounter');

    function openLightbox(index) {
        if (!currentImages.length || currentImages[0] === '/img/image-not-found.png') return;
        lightboxIndex = index || 0;
        renderLightbox();
        lightboxOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightboxOverlay.classList.remove('active');
        document.body.style.overflow = '';
        resetZoom();
    }

    function renderLightbox() {
        lightboxImg.src = currentImages[lightboxIndex];
        lightboxCounter.textContent = `${lightboxIndex + 1} / ${currentImages.length}`;
        resetZoom();
    }

    function resetZoom() {
        zoomLevel = 1;
        lightboxImg.style.transform = 'scale(1)';
        lightboxImg.classList.remove('zoomed');
    }

    document.getElementById('lightboxClose').addEventListener('click', closeLightbox);
    document.getElementById('lightboxPrev').addEventListener('click', () => {
        lightboxIndex = (lightboxIndex - 1 + currentImages.length) % currentImages.length;
        renderLightbox();
    });
    document.getElementById('lightboxNext').addEventListener('click', () => {
        lightboxIndex = (lightboxIndex + 1) % currentImages.length;
        renderLightbox();
    });

    lightboxOverlay.addEventListener('click', function(e) {
        if (e.target === lightboxOverlay) closeLightbox();
    });

    document.addEventListener('keydown', function(e) {
        if (!lightboxOverlay.classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') { lightboxIndex = (lightboxIndex - 1 + currentImages.length) % currentImages.length; renderLightbox(); }
        if (e.key === 'ArrowRight') { lightboxIndex = (lightboxIndex + 1) % currentImages.length; renderLightbox(); }
    });

    lightboxImg.addEventListener('wheel', function(e) {
        e.preventDefault();
        zoomLevel = Math.min(4, Math.max(1, zoomLevel + (e.deltaY < 0 ? 0.3 : -0.3)));
        lightboxImg.style.transform = `scale(${zoomLevel})`;
        lightboxImg.classList.toggle('zoomed', zoomLevel > 1);
    }, { passive: false });

    let touchDist = 0;
    lightboxImg.addEventListener('touchstart', function(e) {
        if (e.touches.length === 2) {
            touchDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
        }
    });
    lightboxImg.addEventListener('touchmove', function(e) {
        if (e.touches.length === 2) {
            e.preventDefault();
            const newDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
            const scale = newDist / touchDist;
            zoomLevel = Math.min(4, Math.max(1, zoomLevel * scale));
            lightboxImg.style.transform = `scale(${zoomLevel})`;
            lightboxImg.classList.toggle('zoomed', zoomLevel > 1);
            touchDist = newDist;
        }
    }, { passive: false });
    lightboxImg.addEventListener('dblclick', function() {
        if (zoomLevel > 1) { resetZoom(); } else { zoomLevel = 2.5; lightboxImg.style.transform = 'scale(2.5)'; lightboxImg.classList.add('zoomed'); }
    });

    document.querySelector('#mainSwiperContainer').addEventListener('click', function(e) {
        if (e.target.closest('.swiper-button-next') || e.target.closest('.swiper-button-prev')) return;
        const activeIndex = mainSwiper ? mainSwiper.realIndex : 0;
        openLightbox(activeIndex);
    });

    // ── Variant UI ──
    function findVariant() {
        if (!VARIANTS.length) return null;
        return VARIANTS.find(v => {
            const colorMatch = HAS_COLORS ? v.color === selectedColor : true;
            const sizeMatch  = HAS_SIZES  ? v.size  === selectedSize  : true;
            return colorMatch && sizeMatch;
        }) || null;
    }

    function getVariantStock() {
        const v = findVariant();
        if (v) return v.stock;
        if (HAS_VARIANTS && selectedColor && !HAS_SIZES) {
            return VARIANTS.filter(v => v.color === selectedColor).reduce((s, v) => s + v.stock, 0);
        }
        return PRODUCT_STOCK;
    }

    function getVariantPrice() {
        const v = findVariant();
        return (v && v.price_override) ? v.price_override : BASE_PRICE;
    }

    function refreshStockBadge() {
        const stock = getVariantStock();
        const el = document.getElementById('stockBadge');
        if (!HAS_VARIANTS && PRODUCT_STOCK === null) { el.innerHTML = ''; return; }
        if (HAS_VARIANTS && !selectedColor && HAS_COLORS) { el.innerHTML = ''; return; }

        if (stock > 5)      el.innerHTML = `<span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Em estoque (${stock} un.)</span>`;
        else if (stock > 0) el.innerHTML = `<span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Últimas ${stock} unidades</span>`;
        else                el.innerHTML = `<span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">Sem estoque</span>`;
    }

    function refreshPrice() {
        const price = getVariantPrice();
        const formattedPrice = 'R$ ' + formatPrice(price);
        const el = document.getElementById('mainPrice');
        const stickyPrice = document.getElementById('stickyBarPrice');
        const referencePrice = document.getElementById('referencePrice');
        const discountBadge = document.getElementById('discountBadge');
        const pixSummary = document.getElementById('pixSummary');
        const installmentSummary = document.getElementById('installmentSummary');
        const pixPaymentLabel = document.getElementById('pixPaymentLabel');
        const installmentPaymentLabel = document.getElementById('installmentPaymentLabel');

        if (el) el.textContent = formattedPrice;
        if (stickyPrice) stickyPrice.textContent = formattedPrice;

        if (referencePrice && REFERENCE_PRICE > price) {
            referencePrice.textContent = 'R$ ' + formatPrice(REFERENCE_PRICE);
            referencePrice.classList.remove('hidden');
        } else if (referencePrice) {
            referencePrice.classList.add('hidden');
        }

        if (discountBadge) {
            if (REFERENCE_PRICE > price) {
                const discount = Math.round((1 - (price / REFERENCE_PRICE)) * 100);
                discountBadge.textContent = `-${discount}%`;
                discountBadge.classList.remove('hidden');
            } else {
                discountBadge.classList.add('hidden');
            }
        }

        if (DISCOUNT_PIX > 0) {
            const pixValue = price * (1 - (DISCOUNT_PIX / 100));
            const pixText = `${DISCOUNT_PIX}% off — R$ ${formatPrice(pixValue)}`;
            if (pixSummary) pixSummary.textContent = `Pix por R$ ${formatPrice(pixValue)}`;
            if (pixPaymentLabel) pixPaymentLabel.textContent = pixText;
        }

        if (INSTALLMENTS_COUNT > 1) {
            const installmentValue = price / INSTALLMENTS_COUNT;
            const installmentText = `${INSTALLMENTS_COUNT}x de R$ ${formatPrice(installmentValue)}`;
            if (installmentSummary) installmentSummary.textContent = installmentText;
            if (installmentPaymentLabel) installmentPaymentLabel.textContent = installmentText;
        }
    }

    function refreshVariantUi() {
        refreshStockBadge();
        refreshPrice();
    }

    function updateSizesForColor(color) {
        if (!HAS_SIZES) return;
        const variantsOfColor = VARIANTS.filter(v => v.color === color);
        document.querySelectorAll('.variant-btn[data-size]').forEach(btn => {
            const match = variantsOfColor.find(v => v.size === btn.dataset.size);
            const available = match && match.stock > 0;
            btn.disabled = !available;
            if (!available && btn.classList.contains('selected')) {
                btn.classList.remove('selected');
                selectedSize = null;
            }
        });
    }

    // ── Color swatches ──
    document.querySelectorAll('.color-swatch').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            selectedColor = this.dataset.color;
            document.getElementById('selectedColorLabel').textContent = selectedColor;

            const urls = IMAGES_BY_COLOR[selectedColor] || [];
            rebuildSwiperSlides(urls);
            updateSizesForColor(selectedColor);
            refreshVariantUi();
        });
    });

    // ── Size buttons ──
    document.querySelectorAll('.variant-btn[data-size]').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            document.querySelectorAll('.variant-btn[data-size]').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            selectedSize = this.dataset.size;
            refreshVariantUi();
        });
    });

    // ── Payment ──
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            selectedPayment = this.dataset.method;
        });
    });

    // ── Delivery ──
    window.setDelivery = function(type) {
        deliveryType = type;
        const fields = document.getElementById('deliveryFields');
        const btnPickup   = document.getElementById('btnPickup');
        const btnDelivery = document.getElementById('btnDelivery');
        if (type === 'delivery') {
            fields.classList.remove('hidden');
            btnDelivery.classList.add('border-[var(--brand-blue-light)]','bg-blue-50'); btnDelivery.classList.remove('border-gray-200','bg-white');
            btnPickup.classList.remove('border-[var(--brand-blue-light)]','bg-blue-50'); btnPickup.classList.add('border-gray-200','bg-white');
        } else {
            fields.classList.add('hidden');
            btnPickup.classList.add('border-[var(--brand-blue-light)]','bg-blue-50'); btnPickup.classList.remove('border-gray-200','bg-white');
            btnDelivery.classList.remove('border-[var(--brand-blue-light)]','bg-blue-50'); btnDelivery.classList.add('border-gray-200','bg-white');
        }
    };

    // ── Validate ──
    function validateSelections() {
        let valid = true;
        if (HAS_COLORS && !selectedColor) { $('#errColor').removeClass('hidden'); valid = false; } else { $('#errColor').addClass('hidden'); }
        if (HAS_SIZES  && !selectedSize)  { $('#errSize').removeClass('hidden');  valid = false; } else { $('#errSize').addClass('hidden'); }
        if (!selectedPayment) { $('#errPayment').removeClass('hidden'); valid = false; } else { $('#errPayment').addClass('hidden'); }
        if (deliveryType === 'delivery') {
            const addr = $('#deliveryAddress').val().trim();
            const city = $('#deliveryCity').val().trim();
            const zip  = $('#deliveryZip').val().trim();
            if (!addr || !city || !zip) { $('#errDelivery').removeClass('hidden'); valid = false; } else { $('#errDelivery').addClass('hidden'); }
        }
        return valid;
    }

    // ── Cart ──
    let cart     = JSON.parse(localStorage.getItem(STORE_KEY) || '[]');
    let cartMeta = JSON.parse(localStorage.getItem(STORE_KEY + '_meta') || '{}');

    function saveCart() {
        localStorage.setItem(STORE_KEY, JSON.stringify(cart));
        localStorage.setItem(STORE_KEY + '_meta', JSON.stringify(cartMeta));
    }

    function updateBadges() {
        const qty = cart.reduce((s, i) => s + i.qty, 0);
        ['#cartBadge','#cartBadgeBar'].forEach(sel => {
            const el = $(sel);
            qty > 0 ? el.text(qty).removeClass('hidden').addClass('flex') : el.addClass('hidden').removeClass('flex');
        });
    }

    function computeItemPrice(item) {
        if (WHOLESALE_PRICE > 0 && WHOLESALE_MIN > 0 && item.qty >= WHOLESALE_MIN) {
            return WHOLESALE_PRICE;
        }
        return item.retail_price;
    }

    function renderCart() {
        const container = $('#cartItems'), empty = $('#cartEmpty'), footer = $('#cartFooter');
        container.empty();
        if (!cart.length) { container.addClass('hidden'); empty.removeClass('hidden'); footer.addClass('hidden'); return; }
        container.removeClass('hidden'); empty.addClass('hidden'); footer.removeClass('hidden');
        let total = 0;
        cart.forEach((item, idx) => {
            const unitPrice = computeItemPrice(item);
            const lineTotal = unitPrice * item.qty;
            total += lineTotal;
            const variantInfo = [item.color, item.size].filter(Boolean).join(' · ');
            const paymentLabel = { pix: 'PIX', credit_card: 'Cartão', cash: 'À vista' }[item.payment] || '';
            const isWholesale = WHOLESALE_PRICE > 0 && WHOLESALE_MIN > 0 && item.qty >= WHOLESALE_MIN;
            const maxReached  = item.max_stock > 0 && item.qty >= item.max_stock;
            container.append(`
                <div class="flex gap-3 items-start bg-gray-50 rounded-xl p-3">
                    <img src="${item.image}" class="w-14 h-14 object-cover object-center rounded-lg flex-shrink-0 bg-white border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">${item.name}</p>
                        ${variantInfo ? `<p class="text-xs text-gray-500 mt-0.5">${variantInfo}</p>` : ''}
                        ${paymentLabel ? `<p class="text-xs font-semibold mt-0.5" style="color: var(--brand-blue-light);">${paymentLabel}</p>` : ''}
                        <p class="font-bold text-sm mt-0.5" style="color: var(--brand-blue);">R$ ${formatPrice(unitPrice)}${isWholesale ? ' <span class="text-indigo-600 text-[10px] font-semibold">(atacado)</span>' : ''}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="changeQty(${idx},-1)" class="qty-btn text-gray-600">−</button>
                            <span class="text-sm font-semibold w-5 text-center">${item.qty}</span>
                            <button onclick="changeQty(${idx},1)" class="qty-btn text-gray-600" ${maxReached ? 'disabled' : ''}>+</button>
                        </div>
                        ${maxReached ? '<p class="text-[10px] text-amber-600 mt-1">Estoque máximo atingido</p>' : ''}
                    </div>
                    <button onclick="removeItem(${idx})" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-300 hover:text-red-500 transition mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>`);
        });
        $('#cartTotal').text('R$ ' + formatPrice(total));
    }

    window.changeQty = function(idx, delta) {
        const item = cart[idx];
        const newQty = item.qty + delta;
        if (newQty < 1) return;
        if (item.max_stock > 0 && newQty > item.max_stock) {
            showToast(`Estoque máximo: ${item.max_stock} unidades`);
            return;
        }
        item.qty = newQty;
        saveCart(); updateBadges(); renderCart();
    };

    window.removeItem = function(idx) { cart.splice(idx, 1); saveCart(); updateBadges(); renderCart(); };
    window.openCart    = function() { renderCart(); $('#cartDrawer').removeClass('translate-x-full'); $('#cartOverlay').removeClass('hidden'); };
    window.closeCart   = function() { $('#cartDrawer').addClass('translate-x-full'); $('#cartOverlay').addClass('hidden'); };

    $('#btnAddToCart').click(function() {
        if (!validateSelections()) {
            const firstErr = document.querySelector('.text-red-500:not(.hidden)');
            if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const variant  = findVariant();
        const maxStock = variant ? variant.stock : PRODUCT_STOCK;
        const price    = (variant && variant.price_override) ? variant.price_override : BASE_PRICE;
        const variantId = variant ? variant.id : null;
        const key      = `${PRODUCT_ID}_v${variantId || 'base'}`;
        const idx      = cart.findIndex(i => i.key === key);

        const imgForCart = currentImages.length && currentImages[0] !== '/img/image-not-found.png'
            ? currentImages[0] : DEFAULT_IMG;

        if (idx >= 0) {
            if (maxStock > 0 && cart[idx].qty >= maxStock) {
                showToast(`Estoque máximo: ${maxStock} unidades`);
                return;
            }
            cart[idx].qty++;
        } else {
            if (maxStock === 0) {
                showToast('Produto sem estoque');
                return;
            }
            cart.push({
                key,
                id: PRODUCT_ID,
                name: PRODUCT_NAME,
                retail_price: price,
                qty: 1,
                max_stock: maxStock,
                image: imgForCart,
                color: selectedColor,
                size: selectedSize,
                payment: selectedPayment,
                variant_id: variantId,
            });
        }

        cartMeta = {
            payment_method:      selectedPayment,
            delivery_type:       deliveryType,
            delivery_address:    $('#deliveryAddress').val(),
            delivery_city:       $('#deliveryCity').val(),
            delivery_state:      $('#deliveryState').val(),
            delivery_zip:        $('#deliveryZip').val(),
            delivery_complement: $('#deliveryComplement').val(),
        };
        saveCart(); updateBadges(); openCart();
    });

    $('#btnCheckout').click(function() {
        const name  = $('#cartName').val().trim();
        const phone = $('#cartPhone').val().trim();
        let valid = true;
        $('#errCartName, #errCartPhone').addClass('hidden');
        if (!name)  { $('#errCartName').removeClass('hidden');  valid = false; }
        if (!phone) { $('#errCartPhone').removeClass('hidden'); valid = false; }
        if (!valid || !cart.length) return;

        const paymentLabel = { pix: 'PIX', credit_card: 'Cartão de crédito', cash: 'À vista' };
        const deliveryLabel = cartMeta.delivery_type === 'delivery'
            ? `Entrega em: ${cartMeta.delivery_address}, ${cartMeta.delivery_city} - ${cartMeta.delivery_state}, CEP ${cartMeta.delivery_zip}`
            : 'Retirada na loja';

        let msg = `Olá! Sou *${name}* e gostaria de finalizar meu pedido:\n\n`;
        let grandTotal = 0;
        cart.forEach(i => {
            const v = [i.color, i.size].filter(Boolean).join(', ');
            const up = computeItemPrice(i);
            const lt = up * i.qty;
            grandTotal += lt;
            msg += `• ${i.name}${v ? ` (${v})` : ''} x${i.qty} — R$ ${formatPrice(lt)}\n`;
        });
        msg += `\n*Total: R$ ${formatPrice(grandTotal)}*`;
        msg += `\n*Pagamento:* ${paymentLabel[cartMeta.payment_method] || cartMeta.payment_method || '—'}`;
        msg += `\n*${deliveryLabel}*`;

        const items = cart.map(i => ({
            product_id: i.id, quantity: i.qty,
            color: i.color, size: i.size, variant_id: i.variant_id,
        }));

        $.ajax({
            url: @json(route('orders.storeCart')),
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                name, phone, store_id: STORE_ID, items, message: msg,
                payment_method:      cartMeta.payment_method,
                delivery_type:       cartMeta.delivery_type,
                delivery_address:    cartMeta.delivery_address,
                delivery_city:       cartMeta.delivery_city,
                delivery_state:      cartMeta.delivery_state,
                delivery_zip:        cartMeta.delivery_zip,
                delivery_complement: cartMeta.delivery_complement,
            }),
            complete: function() {
                window.open(`https://wa.me/55${STORE_PHONE}?text=${encodeURIComponent(msg)}`, '_blank');
                cart = []; cartMeta = {}; saveCart(); updateBadges(); closeCart();
            }
        });
    });

    // ── Init ──
    $(document).ready(function() {
        $('.phone-mask').mask('(00) 00000-0000');
        $('.cep-mask').mask('00000-000');
        initRevealAnimations();
    });
    $(document).ajaxStart(() => $('#globalLoaderPdp').css('display','flex'));
    $(document).ajaxStop(() => $('#globalLoaderPdp').hide());

    // Sync initial state from already-selected Blade elements
    const initialColorBtn = document.querySelector('.color-swatch.selected');
    if (initialColorBtn) {
        selectedColor = initialColorBtn.dataset.color;
        const label = document.getElementById('selectedColorLabel');
        if (label) label.textContent = selectedColor;
        currentImages = IMAGES_BY_COLOR[selectedColor] || [];
        updateSizesForColor(selectedColor);
    }

    const initialSizeBtn = document.querySelector('.variant-btn[data-size].selected');
    if (initialSizeBtn) {
        selectedSize = initialSizeBtn.dataset.size;
    }

    const initialPaymentBtn = document.querySelector('.payment-btn.selected');
    if (initialPaymentBtn) {
        selectedPayment = initialPaymentBtn.dataset.method;
    }

    if (!currentImages.length) {
        currentImages = Object.values(IMAGES_BY_COLOR)[0] || [DEFAULT_IMG];
    }

    initSwipers();
    refreshVariantUi();
    updateBadges();
})();
</script>
</body>
</html>
