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
        .main-swiper{border-radius:16px;overflow:hidden;background:#fff}
        .main-swiper .swiper-slide img{width:100%;height:420px;object-fit:contain;background:#f1f5f9}
        @media(max-width:640px){.main-swiper .swiper-slide img{height:280px}}
        .thumb-swiper .swiper-slide img{height:72px;width:72px;object-fit:cover;border-radius:10px;border:2px solid transparent}
        .thumb-swiper .swiper-slide-thumb-active img{border-color:#1d4ed8}
        .qty-btn{width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:8px;background:#f1f5f9;font-size:18px;cursor:pointer;user-select:none;transition:background .15s}
        .qty-btn:hover{background:#e2e8f0}
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
        <a href="{{ route('orders.index', $partner->partner_link) }}" class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-700 transition">
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
@endphp

<main class="pt-20 px-4 md:px-8 max-w-6xl mx-auto">
<div class="lg:grid lg:grid-cols-2 lg:gap-12">

    <!-- Images -->
    <div>
        <div class="main-swiper swiper swiper-main shadow-sm">
            <div class="swiper-wrapper">
                @if(empty($variantsByColor[0]['images']))
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
        @if(!empty($variantsByColor[0]['images']) && $variantsByColor[0]['images']->count() > 1)
        <div class="thumb-swiper swiper mt-3 px-1">
            <div class="swiper-wrapper">
                @foreach($variantsByColor[0]['images'] as $image)
                    <div class="swiper-slide thumb-slide w-auto">
                        <img src="{{ asset('storage/' . str_replace('public/', '', $image->url)) }}" alt="">
                    </div>
                @endforeach
            </div>
        </div>
        @endif
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

            {{-- Estoque --}}
            @if($product->stock !== null)
                <div class="mt-2">
                    @if($product->stock > 5)<span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">✓ Em estoque ({{ $product->stock }} un.)</span>
                    @elseif($product->stock > 0)<span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">⚠ Últimas {{ $product->stock }} unidades</span>
                    @else<span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">✗ Sem estoque</span>@endif
                </div>
            @endif

            {{-- Preços --}}
            <div class="mt-4 space-y-1">
                @if($product->price_promotional && $product->price_promotional > 0 && $product->price_promotional < $product->price)
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-extrabold text-blue-700">R$ <span class="price-mask">{{ $product->price_promotional }}</span></span>
                        <span class="text-base text-gray-400 line-through mb-0.5">R$ <span class="price-mask">{{ $product->price }}</span></span>
                        @php $disc=round((1-$product->price_promotional/$product->price)*100);@endphp
                        <span class="text-xs font-bold text-white bg-red-500 px-2 py-0.5 rounded-full mb-0.5">-{{ $disc }}%</span>
                    </div>
                @else
                    <div class="text-3xl font-extrabold text-blue-700">R$ <span class="price-mask">{{ $product->price }}</span></div>
                    @if($product->old_price && $product->price < $product->old_price)
                        <div class="text-sm text-gray-400 line-through">R$ <span class="price-mask">{{ $product->old_price }}</span></div>
                    @endif
                @endif
                @if($product->price_wholesale && $product->price_wholesale > 0)
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full inline-block mt-1">Atacado: R$ <span class="price-mask">{{ $product->price_wholesale }}</span></span>
                @endif
            </div>

            {{-- Seleção de COR --}}
            @if($hasColors)
            <div class="mt-5 pt-4 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Cor: <span id="selectedColorLabel" class="text-gray-800 normal-case font-semibold"></span></p>
                <div class="flex flex-wrap gap-2" id="colorSwatches">
                    @foreach($colors as $v)

                        <button type="button"
                            class="color-swatch @selected($v->color === $variantsByColor[0]['variants'][0]->color)"
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
                        <button type="button" class="variant-btn @selected($sz == $variantsByColor[0]['variants'][0]->size)" data-size="{{ $sz }}">{{ $sz }}</button>
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
        <a href="{{ route('orders.productPage', [$partner->partner_link, $rel->id]) }}" class="related-card block">
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

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
// Swiper
const mainSwiper = new Swiper('.swiper-main', {
    loop: {{ $images->count() > 1 ? 'true' : 'false' }},
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    pagination: { el: '.swiper-pagination', clickable: true },
});
@if(!$images->isEmpty() && $images->count() > 1)
const thumbSwiper = new Swiper('.thumb-swiper', { spaceBetween: 8, slidesPerView: 'auto', watchSlidesProgress: true, slideToClickedSlide: true });
mainSwiper.thumbs.swiper = thumbSwiper; mainSwiper.thumbs.init(); mainSwiper.thumbs.update();
@endif

$(document).ready(function() {
    $('.phone-mask').mask('(00) 00000-0000');
    $('.cep-mask').mask('00000-000');
    $('.price-mask').mask('000.000.000.000.000,00', { reverse: true });
});
$(document).ajaxStart(() => $('#globalLoaderPdp').css('display','flex'));
$(document).ajaxStop(() => $('#globalLoaderPdp').hide());

// Variants data from server
const VARIANTS = @json($variants->values());
const HAS_COLORS = {{ $hasColors ? 'true' : 'false' }};
const HAS_SIZES  = {{ $hasSizes  ? 'true' : 'false' }};

// State
let selectedColor   = null;
let selectedSize    = null;
let selectedPayment = null;
let deliveryType    = 'pickup';

// Color swatches
document.querySelectorAll('.color-swatch').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        selectedColor = this.dataset.color;
        document.getElementById('selectedColorLabel').textContent = selectedColor;
        // Update available sizes for this color
        updateSizesForColor(selectedColor);
    });
});

function updateSizesForColor(color) {
    if (!HAS_SIZES) return;
    const availableSizes = VARIANTS.filter(v => v.color === color && v.size).map(v => v.size);
    document.querySelectorAll('.variant-btn[data-size]').forEach(btn => {
        const inStock = availableSizes.includes(btn.dataset.size);
        btn.disabled = !inStock;
        if (!inStock && btn.classList.contains('selected')) {
            btn.classList.remove('selected');
            selectedSize = null;
        }
    });
}

// Size buttons
document.querySelectorAll('.variant-btn[data-size]').forEach(btn => {
    btn.addEventListener('click', function() {
        if (this.disabled) return;
        document.querySelectorAll('.variant-btn[data-size]').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        selectedSize = this.dataset.size;
    });
});

// Payment
document.querySelectorAll('.payment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        selectedPayment = this.dataset.method;
    });
});

// Delivery
function setDelivery(type) {
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
}

// Validate selections before adding to cart
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

// Find matching variant
function findVariant() {
    if (!VARIANTS.length) return null;
    return VARIANTS.find(v => {
        const colorMatch = !v.color || v.color === selectedColor;
        const sizeMatch  = !v.size  || v.size  === selectedSize;
        return colorMatch && sizeMatch;
    }) || null;
}

// Cart
const STORE_KEY   = 'pdp_cart_{{ $partner->store->id }}';
const STORE_ID    = {{ $partner->store->id }};
const STORE_PHONE = '{{ $storePhone }}';
const PRODUCT_ID  = {{ $product->id }};
const PRODUCT_NAME  = @json($product->name);
const PRODUCT_IMG   = '{{ $images->isNotEmpty() ? asset("storage/" . str_replace("public/", "", $images->first()->url)) : "/img/image-not-found.png" }}';
const BASE_PRICE    = {{ $basePrice }};

let cart = JSON.parse(localStorage.getItem(STORE_KEY) || '[]');
// Also store delivery/payment per cart session
let cartMeta = JSON.parse(localStorage.getItem(STORE_KEY + '_meta') || '{}');

function saveCart() {
    localStorage.setItem(STORE_KEY, JSON.stringify(cart));
    localStorage.setItem(STORE_KEY + '_meta', JSON.stringify(cartMeta));
}
function formatPrice(v) { return v.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

function updateBadges() {
    const qty = cart.reduce((s, i) => s + i.qty, 0);
    ['#cartBadge','#cartBadgeBar'].forEach(sel => {
        const el = $(sel);
        qty > 0 ? el.text(qty).removeClass('hidden').addClass('flex') : el.addClass('hidden').removeClass('flex');
    });
}

function renderCart() {
    const container = $('#cartItems'), empty = $('#cartEmpty'), footer = $('#cartFooter');
    container.empty();
    if (!cart.length) { container.addClass('hidden'); empty.removeClass('hidden'); footer.addClass('hidden'); return; }
    container.removeClass('hidden'); empty.addClass('hidden'); footer.removeClass('hidden');
    let total = 0;
    cart.forEach((item, idx) => {
        total += item.price * item.qty;
        const variantInfo = [item.color, item.size].filter(Boolean).join(' · ');
        const paymentLabel = { pix: 'PIX', credit_card: 'Cartão', cash: 'À vista' }[item.payment] || '';
        container.append(`
            <div class="flex gap-3 items-start bg-gray-50 rounded-xl p-3">
                <img src="${item.image}" class="w-14 h-14 object-cover object-center rounded-lg flex-shrink-0 bg-white border border-gray-100">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">${item.name}</p>
                    ${variantInfo ? `<p class="text-xs text-gray-500 mt-0.5">${variantInfo}</p>` : ''}
                    ${paymentLabel ? `<p class="text-xs text-blue-600 font-semibold mt-0.5">${paymentLabel}</p>` : ''}
                    <p class="text-blue-700 font-bold text-sm mt-0.5">R$ ${formatPrice(item.price)}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <button onclick="changeQty(${idx},-1)" class="qty-btn text-gray-600">−</button>
                        <span class="text-sm font-semibold w-5 text-center">${item.qty}</span>
                        <button onclick="changeQty(${idx},1)" class="qty-btn text-gray-600">+</button>
                    </div>
                </div>
                <button onclick="removeItem(${idx})" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-300 hover:text-red-500 transition mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>`);
    });
    $('#cartTotal').text('R$ ' + formatPrice(total));
}

function changeQty(idx, delta) { cart[idx].qty = Math.max(1, cart[idx].qty + delta); saveCart(); updateBadges(); renderCart(); }
function removeItem(idx) { cart.splice(idx, 1); saveCart(); updateBadges(); renderCart(); }
function openCart() { renderCart(); $('#cartDrawer').removeClass('translate-x-full'); $('#cartOverlay').removeClass('hidden'); }
function closeCart() { $('#cartDrawer').addClass('translate-x-full'); $('#cartOverlay').addClass('hidden'); }

$('#btnAddToCart').click(function() {
    if (!validateSelections()) {
        // Scroll to first error
        const firstErr = document.querySelector('.text-red-500:not(.hidden)');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    const variant = findVariant();
    const price   = variant?.price_override || BASE_PRICE;
    const key     = `${PRODUCT_ID}_${selectedColor || ''}_${selectedSize || ''}`;
    const idx     = cart.findIndex(i => i.key === key);

    if (idx >= 0) {
        cart[idx].qty++;
    } else {
        cart.push({
            key, id: PRODUCT_ID, name: PRODUCT_NAME, price, qty: 1,
            image: PRODUCT_IMG, color: selectedColor, size: selectedSize,
            payment: selectedPayment, variant_id: variant?.id || null,
        });
    }
    // Save delivery meta (shared for the session)
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
    cart.forEach(i => {
        const v = [i.color, i.size].filter(Boolean).join(', ');
        msg += `• ${i.name}${v ? ` (${v})` : ''} x${i.qty} — R$ ${formatPrice(i.price * i.qty)}\n`;
    });
    msg += `\n*Total: R$ ${formatPrice(cart.reduce((s,i) => s + i.price * i.qty, 0))}*`;
    msg += `\n*Pagamento:* ${paymentLabel[cartMeta.payment_method] || cartMeta.payment_method || '—'}`;
    msg += `\n*${deliveryLabel}*`;

    const items = cart.map(i => ({
        product_id: i.id, quantity: i.qty,
        color: i.color, size: i.size, variant_id: i.variant_id,
    }));

    $.ajax({
        url: '/requests/store-cart',
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

updateBadges();
</script>
</body>
</html>
