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
        *{box-sizing:border-box}
        body{font-family:'Montserrat',sans-serif;background:#f8fafc}
        .thumb-slide{cursor:pointer;opacity:.55;transition:opacity .2s}
        .thumb-slide.swiper-slide-thumb-active{opacity:1}
        .main-swiper{border-radius:16px;overflow:hidden;background:#fff;cursor:zoom-in}
        .main-swiper .swiper-slide img{width:100%;height:420px;object-fit:contain;background:#f1f5f9}
        @media(max-width:640px){.main-swiper .swiper-slide img{height:280px}}
        .thumb-swiper .swiper-slide img{height:72px;width:72px;object-fit:cover;border-radius:10px;border:2px solid transparent}
        .thumb-swiper .swiper-slide-thumb-active img{border-color:#1d4ed8}
        .qty-btn{width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:8px;background:#f1f5f9;font-size:18px;cursor:pointer;user-select:none;transition:background .15s}
        .qty-btn:hover{background:#e2e8f0}
        .qty-btn:disabled{opacity:.35;cursor:not-allowed}
        @media(min-width:1024px){.sticky-sidebar{position:sticky;top:80px}}
        .swiper-button-next,.swiper-button-prev{color:#1d4ed8!important}
        .swiper-pagination-bullet-active{background:#1d4ed8!important}
        .info-chip{display:inline-flex;align-items:center;gap:4px;background:#f1f5f9;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:600;color:#475569}
        .variant-btn{border:2px solid #e2e8f0;border-radius:10px;padding:6px 14px;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;background:#fff;color:#374151}
        .variant-btn.selected{border-color:#1d4ed8;background:#eff6ff;color:#1d4ed8}
        .variant-btn:disabled{opacity:.4;cursor:not-allowed}
        .color-swatch{width:32px;height:32px;border-radius:50%;border:3px solid transparent;cursor:pointer;transition:all .15s;box-shadow:0 0 0 2px #e2e8f0}
        .color-swatch.selected{box-shadow:0 0 0 3px #1d4ed8}
        .payment-btn{border:2px solid #e2e8f0;border-radius:12px;padding:10px 14px;cursor:pointer;transition:all .15s;background:#fff;text-align:left}
        .payment-btn.selected{border-color:#1d4ed8;background:#eff6ff}
        .related-card{background:#fff;border-radius:16px;overflow:hidden;border:1px solid #f1f5f9;transition:box-shadow .2s}
        .related-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.08)}

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
    <svg class="w-10 h-10 animate-spin text-blue-700" viewBox="0 0 64 64"><path d="M32 64a32 32 0 1 1 32-32h-4a28 28 0 1 0-28 28z" fill="currentColor"/><path d="M32 0a32 32 0 0 1 32 32h-4a28 28 0 0 0-28-28z" fill="currentColor"/></svg>
</div>

<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-sm h-16 flex items-center px-4 md:px-8 justify-between">
    <div class="flex items-center gap-3">
        @if($logoStore)
            <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-gray-100 flex-shrink-0">
                <img src="{{ $logoStore }}" class="w-full h-full object-cover" alt="Logo">
            </div>
        @endif
        <span class="font-bold text-gray-800 text-base">{{ $partner->store->store_name }}</span>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openCart()" class="relative p-2 rounded-xl hover:bg-gray-100 transition">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
            <span id="cartBadge" class="hidden absolute -top-1 -right-1 bg-blue-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">0</span>
        </button>
        <a href="{{ route('catalog.index', $partner->partner_link) }}" class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-700 transition">
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
@endphp

<main class="pt-20 px-4 md:px-8 max-w-6xl mx-auto">
<div class="lg:grid lg:grid-cols-2 lg:gap-12">

    <!-- Images -->
    <div>
        <div class="main-swiper swiper swiper-main shadow-sm" id="mainSwiperContainer">
            <div class="swiper-wrapper">
                @if(empty($variantsByColor[0]['images']) || $variantsByColor[0]['images']->isEmpty())
                    <div class="swiper-slide"><img src="/img/image-not-found.png" alt="Sem imagem"></div>
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
        <div class="thumb-swiper swiper mt-3 px-1" id="thumbSwiperContainer">
            <div class="swiper-wrapper">
                @if(!empty($variantsByColor[0]['images']) && $variantsByColor[0]['images']->count() > 1)
                    @foreach($variantsByColor[0]['images'] as $image)
                        <div class="swiper-slide thumb-slide w-auto">
                            <img src="{{ asset('storage/' . str_replace('public/', '', $image->url)) }}" alt="">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="sticky-sidebar mt-6 lg:mt-0 flex flex-col gap-4">

        <!-- Card principal -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

            {{-- Chips --}}
            <div class="flex flex-wrap gap-2 mb-3">
                @if($category)<span class="info-chip"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>{{ $category->name }}</span>@endif
                @if($product->brand)<span class="info-chip">{{ $product->brand->name }}</span>@endif
                @if($product->gender)@php $gm=['M'=>'Masculino','F'=>'Feminino','U'=>'Unissex'];@endphp<span class="info-chip">{{ $gm[$product->gender] ?? $product->gender }}</span>@endif
            </div>

            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>

            {{-- Estoque dinâmico --}}
            <div class="mt-2" id="stockBadge">
                @if(!$hasVariants && $product->stock !== null)
                    @if($product->stock > 5)<span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Em estoque ({{ $product->stock }} un.)</span>
                    @elseif($product->stock > 0)<span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Últimas {{ $product->stock }} unidades</span>
                    @else<span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">Sem estoque</span>@endif
                @endif
            </div>

            {{-- Preços (dinâmico via JS quando há variantes) --}}
            <div class="mt-4 space-y-1" id="priceBlock">
                @if($product->price_promotional && $product->price_promotional > 0 && $product->price_promotional < $product->price)
                    <div class="flex items-end gap-2">
                        <span id="mainPrice" class="text-3xl font-extrabold text-blue-700">R$ {{ number_format($product->price_promotional, 2, ',', '.') }}</span>
                        <span class="text-base text-gray-400 line-through mb-0.5">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        @php $disc=round((1-$product->price_promotional/$product->price)*100);@endphp
                        <span class="text-xs font-bold text-white bg-red-500 px-2 py-0.5 rounded-full mb-0.5">-{{ $disc }}%</span>
                    </div>
                @else
                    <div class="flex items-end gap-2">
                        <span id="mainPrice" class="text-3xl font-extrabold text-blue-700">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    </div>
                    @if($product->old_price && $product->price < $product->old_price)
                        <div class="text-sm text-gray-400 line-through">R$ {{ number_format($product->old_price, 2, ',', '.') }}</div>
                    @endif
                @endif
            </div>

            {{-- Atacado --}}
            @if($product->price_wholesale && $product->price_wholesale > 0)
            <div class="mt-2" id="wholesaleInfo">
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full inline-block">
                    Atacado: R$ {{ number_format($product->price_wholesale, 2, ',', '.') }}
                    @if($wholesaleMinQty)
                        — a partir de {{ $wholesaleMinQty }} peças
                    @endif
                </span>
            </div>
            @endif

            {{-- Seleção de COR --}}
            @if($hasColors)
            <div class="mt-5 pt-4 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Cor: <span id="selectedColorLabel" class="text-gray-800 normal-case font-semibold"></span></p>
                <div class="flex flex-wrap gap-2" id="colorSwatches">
                    @foreach($colors as $v)
                        <button type="button"
                            class="color-swatch @if($loop->first) selected @endif"
                            data-color="{{ $v->color }}"
                            data-hex="{{ $v->color_hex ?? '#cccccc' }}"
                            style="background-color: {{ $v->color_hex ?? '#cccccc' }}"
                            title="{{ $v->color }}">
                        </button>
                    @endforeach
                </div>
                <p id="errColor" class="hidden text-xs text-red-500 mt-1">Selecione uma cor</p>
            </div>
            @elseif($product->color)
            <div class="mt-3"><span class="info-chip">{{ $product->color }}</span></div>
            @endif

            {{-- Seleção de TAMANHO --}}
            @if($hasSizes)
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Tamanho</p>
                <div class="flex flex-wrap gap-2" id="sizeButtons">
                    @foreach($sizes as $sz)
                        <button type="button" class="variant-btn" data-size="{{ $sz }}">{{ $sz }}</button>
                    @endforeach
                </div>
                <p id="errSize" class="hidden text-xs text-red-500 mt-1">Selecione um tamanho</p>
            </div>
            @elseif($product->size)
            <div class="mt-3"><span class="info-chip">Tam: {{ $product->size }}</span></div>
            @endif

            {{-- Forma de pagamento --}}
            <div class="mt-5 pt-4 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Forma de pagamento</p>
                <div class="grid grid-cols-1 gap-2" id="paymentOptions">
                    @if($product->discount_pix && $product->discount_pix > 0)
                        @php $pixPrice = $basePrice * (1 - $product->discount_pix / 100); @endphp
                        <button type="button" class="payment-btn selected" data-method="pix">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M11.9 2C6.4 2 2 6.4 2 11.9s4.4 9.9 9.9 9.9 9.9-4.4 9.9-9.9S17.4 2 11.9 2zm4.3 13.4l-2.4-2.4c-.3.1-.6.2-.9.2s-.6-.1-.9-.2l-2.4 2.4c-.2.2-.4.3-.7.3s-.5-.1-.7-.3c-.4-.4-.4-1 0-1.4l2.4-2.4c-.1-.3-.2-.6-.2-.9s.1-.6.2-.9L8.2 7.4c-.4-.4-.4-1 0-1.4s1-.4 1.4 0l2.4 2.4c.3-.1.6-.2.9-.2s.6.1.9.2l2.4-2.4c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-2.4 2.4c.1.3.2.6.2.9s-.1.6-.2.9l2.4 2.4c.4.4.4 1 0 1.4-.2.2-.4.3-.7.3s-.5-.1-.7-.3z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">PIX — {{ $product->discount_pix }}% de desconto</p>
                                    <p class="text-xs text-green-600 font-semibold">R$ {{ number_format($pixPrice, 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </button>
                    @endif
                    @if($product->installments && $product->installments > 1)
                        @php $installPrice = $basePrice / $product->installments; @endphp
                        <button type="button" class="payment-btn" data-method="credit_card">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Cartão de crédito</p>
                                    <p class="text-xs text-blue-600 font-semibold">{{ $product->installments }}x de R$ {{ number_format($installPrice, 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </button>
                    @endif
                    <button type="button" class="payment-btn" data-method="cash">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-800">À vista</p>
                        </div>
                    </button>
                </div>
                <p id="errPayment" class="hidden text-xs text-red-500 mt-1">Selecione uma forma de pagamento</p>
            </div>

            {{-- Descrição --}}
            @if($product->description)
            <div class="mt-5 pt-4 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Descrição</p>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $product->description }}</p>
            </div>
            @endif
        </div>

        {{-- Entrega --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Entrega</p>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <button type="button" id="btnPickup" onclick="setDelivery('pickup')"
                    class="delivery-opt selected flex flex-col items-center gap-2 border-2 border-blue-600 bg-blue-50 rounded-xl p-3 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="text-xs font-bold text-blue-700">Retirar na loja</span>
                </button>
                <button type="button" id="btnDelivery" onclick="setDelivery('delivery')"
                    class="delivery-opt flex flex-col items-center gap-2 border-2 border-gray-200 bg-white rounded-xl p-3 transition hover:border-blue-300">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span class="text-xs font-bold text-gray-600">Entrega</span>
                </button>
            </div>

            <div id="deliveryFields" class="hidden space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">CEP <span class="text-red-500">*</span></label>
                        <input type="text" id="deliveryZip" placeholder="00000-000" class="cep-mask w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Endereço <span class="text-red-500">*</span></label>
                        <input type="text" id="deliveryAddress" placeholder="Rua, número" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Cidade <span class="text-red-500">*</span></label>
                        <input type="text" id="deliveryCity" placeholder="Cidade" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Estado</label>
                        <input type="text" id="deliveryState" placeholder="UF" maxlength="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition uppercase">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Complemento</label>
                        <input type="text" id="deliveryComplement" placeholder="Apto, bloco..." class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>
                <p id="errDelivery" class="hidden text-xs text-red-500">Preencha o endereço de entrega</p>
            </div>
        </div>

        {{-- Dimensões --}}
        @php $hasDim = $product->width || $product->height || $product->length || $product->weight; @endphp
        @if($hasDim || $product->size)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Especificações</p>
            <div class="grid grid-cols-3 gap-2">
                @if($product->size)<div class="bg-gray-50 rounded-xl p-2.5 text-center"><p class="text-[10px] text-gray-400 font-semibold uppercase">Tamanho</p><p class="font-bold text-gray-800 mt-0.5">{{ $product->size }}</p></div>@endif
                @if($product->width)<div class="bg-gray-50 rounded-xl p-2.5 text-center"><p class="text-[10px] text-gray-400 font-semibold uppercase">Largura</p><p class="font-bold text-gray-800 mt-0.5">{{ $product->width }}cm</p></div>@endif
                @if($product->height)<div class="bg-gray-50 rounded-xl p-2.5 text-center"><p class="text-[10px] text-gray-400 font-semibold uppercase">Altura</p><p class="font-bold text-gray-800 mt-0.5">{{ $product->height }}cm</p></div>@endif
                @if($product->length)<div class="bg-gray-50 rounded-xl p-2.5 text-center"><p class="text-[10px] text-gray-400 font-semibold uppercase">Comprimento</p><p class="font-bold text-gray-800 mt-0.5">{{ $product->length }}cm</p></div>@endif
                @if($product->weight)<div class="bg-gray-50 rounded-xl p-2.5 text-center"><p class="text-[10px] text-gray-400 font-semibold uppercase">Peso</p><p class="font-bold text-gray-800 mt-0.5">{{ $product->weight }}kg</p></div>@endif
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Produtos relacionados --}}
@if($related->count() > 0)
<div class="mt-10 mb-4">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Produtos relacionados</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach($related as $rel)
        <a href="{{ route('catalog.productPage', [$partner->partner_link, $rel->id]) }}" class="related-card block">
            <div class="aspect-square overflow-hidden bg-gray-100">
                @if($rel->images->isNotEmpty())
                    <img src="{{ asset('storage/' . str_replace('public/', '', $rel->images->first()->url)) }}"
                         class="w-full h-full object-cover object-center" alt="{{ $rel->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>
            <div class="p-3">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $rel->name }}</p>
                <p class="text-sm font-extrabold text-blue-700 mt-0.5">R$ {{ number_format($rel->price, 2, ',', '.') }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

</main>

<!-- Barra fixa inferior -->
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] px-4 py-3">
    <div class="max-w-6xl mx-auto flex items-center gap-2">
        <button id="btnAddToCart" class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
            Adicionar ao carrinho
        </button>
        <button onclick="openCart()" class="relative flex items-center justify-center border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-3.5 px-4 rounded-xl transition flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
            <span id="cartBadgeBar" class="hidden absolute -top-1.5 -right-1.5 bg-blue-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
        </button>
        <a href="https://wa.me/55{{ $storePhone }}?text={{ urlencode('Olá! Tenho interesse no produto ' . $product->name . '. Poderia me passar mais informações?') }}"
            target="_blank" class="flex items-center justify-center bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 px-4 rounded-xl transition flex-shrink-0">
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
            <span id="cartTotal" class="text-xl font-extrabold text-blue-700">R$ 0,00</span>
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
    const PRODUCT_STOCK   = {{ $product->stock ?? 0 }};
    const WHOLESALE_PRICE = {{ $product->price_wholesale ?? 0 }};
    const WHOLESALE_MIN   = {{ $wholesaleMinQty ?? 0 }};
    const DISCOUNT_PIX    = {{ $product->discount_pix ?? 0 }};

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
        const el = document.getElementById('mainPrice');
        if (el) el.textContent = 'R$ ' + formatPrice(price);
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
            btnDelivery.classList.add('border-blue-600','bg-blue-50'); btnDelivery.classList.remove('border-gray-200','bg-white');
            btnPickup.classList.remove('border-blue-600','bg-blue-50'); btnPickup.classList.add('border-gray-200','bg-white');
        } else {
            fields.classList.add('hidden');
            btnPickup.classList.add('border-blue-600','bg-blue-50'); btnPickup.classList.remove('border-gray-200','bg-white');
            btnDelivery.classList.remove('border-blue-600','bg-blue-50'); btnDelivery.classList.add('border-gray-200','bg-white');
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
                        ${paymentLabel ? `<p class="text-xs text-blue-600 font-semibold mt-0.5">${paymentLabel}</p>` : ''}
                        <p class="text-blue-700 font-bold text-sm mt-0.5">R$ ${formatPrice(unitPrice)}${isWholesale ? ' <span class="text-indigo-600 text-[10px] font-semibold">(atacado)</span>' : ''}</p>
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
