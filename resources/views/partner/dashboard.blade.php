@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <div class="p-2 flex md:justify-center pb-24 md:pb-0">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm text-[#33363B]/55 mt-4 mb-2 px-1" aria-label="breadcrumb">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#6A2BBA] transition-colors">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span>Início</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[10px] text-[#33363B]/35"></i>
                <span class="font-semibold text-[#33363B]">Visão Geral</span>
            </nav>

            <h2 class="font-display font-semibold text-3xl mb-4 mt-1 text-[#33363B] leading-tight px-1">
                {{ __('Dashboard') }}
            </h2>

            {{-- Onboarding Messages --}}
            @if ($user->first_login == 1 && $store->configured_store == 0)
                <div
                    class="bg-[#EDE9FE]/50 border border-[#6A2BBA]/20 text-[#33363B] rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-gradient-to-br from-[#6A2BBA] to-[#D131A3] flex items-center justify-center text-white flex-shrink-0 shadow-md">
                            <i class="fa-solid fa-flag-checkered"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Bem-vindo(a) ao sistema!</p>
                            <p class="text-sm mt-1 text-[#33363B]/75">Você está no seu primeiro acesso. Para começar a usar o
                                sistema, configure os dados essenciais da sua loja.</p>
                        </div>
                    </div>
                    <div>
                        <button id="configuredStore" data-storeid="{{ $store->id }}" type="button"
                            class="w-full md:w-auto bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] text-white font-semibold px-5 py-2.5 rounded-xl hover:brightness-105 transition shadow-md shadow-[#6A2BBA]/25 whitespace-nowrap">
                            Configurar Loja
                        </button>
                    </div>
                </div>
            @elseif($categoriesByStore->isEmpty())
                <div
                    class="bg-white border border-[#6A2BBA]/15 text-[#33363B] rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm ring-1 ring-[#33363B]/5">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-[#EDE9FE] flex items-center justify-center text-[#6A2BBA] flex-shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Vamos para o próximo passo!</p>
                            <p class="text-sm mt-1 text-[#33363B]/70">Cadastre uma ou mais categorias para organizar o seu
                                catálogo (ex: Celulares, Tênis, Moveis).</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('categories.create') }}"
                            class="block w-full text-center md:w-auto bg-gradient-to-r from-[#6A2BBA] to-[#8B3DC7] text-white font-semibold px-5 py-2.5 rounded-xl hover:brightness-105 transition shadow-md shadow-[#6A2BBA]/20 whitespace-nowrap">
                            Cadastrar Categoria
                        </a>
                    </div>
                </div>
            @elseif($brands->isEmpty())
                <div
                    class="bg-[#FCE7F3]/40 border border-[#D131A3]/25 text-[#33363B] rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-gradient-to-br from-[#D131A3]/20 to-[#FF914D]/25 flex items-center justify-center text-[#D131A3] flex-shrink-0">
                            <i class="fa-solid fa-copyright"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Cadastrar Marca de Produtos!</p>
                            <p class="text-sm mt-1 text-[#33363B]/70">Cadastre as marcas que compõem o seu estoque (ex: Nike,
                                Adidas, Zara).</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('brands.create') }}"
                            class="block w-full text-center md:w-auto bg-gradient-to-r from-[#D131A3] to-[#FF914D] text-white font-semibold px-5 py-2.5 rounded-xl hover:brightness-105 transition shadow-md whitespace-nowrap">
                            Cadastrar Marca
                        </a>
                    </div>
                </div>
            @elseif($quantityStockProducts <= 0)
                <div
                    class="bg-[#FFF7ED] border border-[#FF914D]/30 text-[#33363B] rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-[#FF914D]/20 flex items-center justify-center text-[#FF914D] flex-shrink-0">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Finalizando nosso Tour!</p>
                            <p class="text-sm mt-1 text-[#33363B]/70">Cadastre seu primeiro produto para que seus clientes o
                                vejam online no seu catálogo público.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('products.create') }}"
                            class="block w-full text-center md:w-auto bg-[#FF914D] text-[#33363B] font-semibold px-5 py-2.5 rounded-xl hover:brightness-105 transition shadow-md whitespace-nowrap">
                            Cadastrar Produto
                        </a>
                    </div>
                </div>
            @endif

            {{-- Metric Widgets: 2 colunas no mobile, 4 a partir de lg --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">

                {{-- Estoque --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-[#33363B]/8 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-[#33363B]/55 mb-1">Produtos no estoque</p>
                        <p class="text-3xl font-bold text-[#33363B]">{{ $quantityStockProducts }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#EDE9FE] flex items-center justify-center text-[#6A2BBA]">
                        <svg class="h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M320.2 176C364.4 176 400.2 140.2 400.2 96L453.7 96C470.7 96 487 102.7 499 114.7L617.6 233.4C630.1 245.9 630.1 266.2 617.6 278.7L566.9 329.4C554.4 341.9 534.1 341.9 521.6 329.4L480.2 288L480.2 512C480.2 547.3 451.5 576 416.2 576L224.2 576C188.9 576 160.2 547.3 160.2 512L160.2 288L118.8 329.4C106.3 341.9 86 341.9 73.5 329.4L22.9 278.6C10.4 266.1 10.4 245.8 22.9 233.3L141.5 114.7C153.5 102.7 169.8 96 186.8 96L240.3 96C240.3 140.2 276.1 176 320.3 176z"/></svg>
                    </div>
                </div>

                {{-- Vendas --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-[#33363B]/8 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-[#33363B]/55 mb-1">Vendas Concluídas</p>
                        <p class="text-2xl sm:text-3xl font-bold text-[#33363B] tabular-nums">{{ $completedSalesThisMonth }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#ecfdf5] flex items-center justify-center text-emerald-600">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                            fill="currentColor"><!--!Font Awesome Free 6.6.0...-->
                            <path
                                d="M352 288h-16v-88c0-4.4-3.6-8-8-8h-13.6c-4.7 0-9.4 1.4-13.3 4l-15.3 10.2a8 8 0 0 0 -2.2 11.1l8.9 13.3a8 8 0 0 0 11.1 2.2l.5-.3V288h-16c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h64c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zM608 64H32C14.3 64 0 78.3 0 96v320c0 17.7 14.3 32 32 32h576c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zM48 400v-64c35.4 0 64 28.7 64 64H48zm0-224v-64h64c0 35.4-28.7 64-64 64zm272 192c-53 0-96-50.2-96-112 0-61.9 43-112 96-112s96 50.1 96 112c0 61.9-43 112-96 112zm272 32h-64c0-35.4 28.7-64 64-64v64zm0-224c-35.4 0-64-28.7-64-64h64v64z" />
                        </svg>
                    </div>
                </div>

                {{-- Solicitações --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-[#33363B]/8 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-[#33363B]/55 mb-1">Novos Pedidos</p>
                        <p class="text-2xl sm:text-3xl font-bold text-[#33363B] tabular-nums">{{ $newOrdersThisMonth }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#FFF8E7] flex items-center justify-center text-[#FF914D]">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"
                            fill="currentColor"><!--!Font Awesome Free 6.6.0...-->
                            <path
                                d="M336 0H48C21.5 0 0 21.5 0 48v464l192-112 192 112V48c0-26.5-21.5-48-48-48zm0 428.4l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.3 0 6 2.7 6 6V428.4z" />
                        </svg>
                    </div>
                </div>

                {{-- Clientes --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-[#33363B]/8 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-[#33363B]/55 mb-1">Saldo no mês</p>
                        <p class="text-lg sm:text-2xl lg:text-3xl font-bold text-[#33363B] tabular-nums leading-tight">
                            R$ {{ number_format((float) $monthlySalesTotal, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#FCE7F3]/60 flex items-center justify-center text-[#D131A3]">
                        <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM320 224C373 224 416 267 416 320C416 373 373 416 320 416C267 416 224 373 224 320C224 267 267 224 320 224zM512 248C512 252.4 508.4 256.1 504 255.5C475 251.9 452.1 228.9 448.5 200C448 195.6 451.6 192 456 192L504 192C508.4 192 512 195.6 512 200L512 248zM128 392C128 387.6 131.6 383.9 136 384.5C165 388.1 187.9 411.1 191.5 440C192 444.4 188.4 448 184 448L136 448C131.6 448 128 444.4 128 440L128 392zM136 255.5C131.6 256 128 252.4 128 248L128 200C128 195.6 131.6 192 136 192L184 192C188.4 192 192.1 195.6 191.5 200C187.9 229 164.9 251.9 136 255.5zM504 384.5C508.4 384 512 387.6 512 392L512 440C512 444.4 508.4 448 504 448L456 448C451.6 448 447.9 444.4 448.5 440C452.1 411 475.1 388.1 504 384.5z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Lower Panels --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                {{-- Left: Últimos Pedidos --}}
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-1 px-1">
                        <div class="flex gap-2 items-center">
                            <i class="fa-solid fa-list-ol text-[#33363B]/70"></i>
                            <h3 class="font-semibold text-lg text-[#33363B]">Últimos Pedidos</h3>
                        </div>
                        <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-[#6A2BBA] hover:text-[#D131A3] transition px-1">
                            Ver todos →
                        </a>
                    </div>
                    <p class="text-sm text-[#33363B]/55 mb-3 px-1">Pedidos recentes recebidos pelo catálogo.</p>

                    <div class="bg-white rounded-2xl shadow-sm border border-[#33363B]/8 flex-1 overflow-hidden">
                        @if ($ordersByStore->count() == 0)
                            <div class="flex flex-col items-center justify-center p-10 text-gray-400">
                                <i class="fa-solid fa-inbox text-4xl mb-3 opacity-40"></i>
                                <span class="font-medium text-sm">Nenhum pedido recebido ainda.</span>
                                <span class="text-xs mt-1 text-gray-300">Os pedidos do seu catálogo aparecerão aqui.</span>
                            </div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach ($ordersByStore as $order)
                                    <div class="px-4 py-3.5 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between gap-2">

                                           

                                            <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0 mt-0.5">
                                                <i class="fa-regular fa-clock mr-0.5"></i>
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                @php
                                                    $fi = $order->items->first();
                                                    $lineTitle = $order->items->count() > 1
                                                        ? $order->items->count().' itens · '.$order->itemsVariationSummary()
                                                        : ($fi?->product?->name ?? '—');
                                                @endphp
                                                <div class="flex gap-2 items-center">
                                                    <p class="font-bold text-gray-800 text-sm truncate">{{ $lineTitle }}</p>
                                                    {{-- Status badge --}}
                                                    <div class="flex flex-wrap gap-1.5 items-center">
                                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold border {{ $order->status->badgeClasses() }}">
                                                            {{ $order->status->label() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-3 mt-0.5">
                                                    @if ($order->client)
                                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                                            <i class="fa-regular fa-user text-[10px]"></i>
                                                            {{ $order->client->name }}
                                                        </span>
                                                        @if ($order->client->phone)
                                                            <span class="text-xs text-gray-400 flex items-center gap-1">
                                                                <i class="fa-solid fa-phone text-[10px]"></i>
                                                                {{ $order->client->phone }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">Cliente não identificado</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <span class="text-sm font-extrabold text-[#6A2BBA] flex-shrink-0">
                                                R$ {{ number_format((float) $order->total, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right: Cadastros Recentes --}}
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-1 px-1">
                        <div class="flex gap-2 items-center">
                            <i class="fa-solid fa-layer-group text-[#33363B]/70"></i>
                            <h3 class="font-semibold text-lg text-[#33363B]">Produtos Recentes</h3>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-[#6A2BBA] hover:text-[#D131A3] transition px-1">
                            Ver todos →
                        </a>
                    </div>
                    <p class="text-sm text-[#33363B]/55 mb-3 px-1">Últimos produtos adicionados ao catálogo.</p>

                    <div class="bg-white rounded-2xl shadow-sm border border-[#33363B]/8 flex-1 overflow-hidden">
                        @if ($latestProducts->count() == 0)
                            <div class="flex flex-col items-center justify-center p-10 text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 opacity-40"></i>
                                <span class="font-medium text-sm">Nenhum produto cadastrado.</span>
                                <a href="{{ route('products.create') }}" class="mt-3 text-xs font-semibold text-[#6A2BBA] hover:text-[#D131A3] hover:underline">
                                    Cadastrar primeiro produto →
                                </a>
                            </div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach ($latestProducts as $product)
                                    <div class="px-4 py-3.5 hover:bg-gray-50 transition flex items-center gap-3">

                                        {{-- Thumbnail --}}
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-100">
                                            @if ($product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . str_replace('public/', '', $product->images->first()->url)) }}"
                                                    class="w-full h-full object-cover" alt="{{ $product->name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <i class="fa-solid fa-image text-lg"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-800 text-sm truncate">{{ $product->name }}</p>
                                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                                @if ($product->brand)
                                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                                        <i class="fa-solid fa-tag text-[10px] text-gray-400"></i>
                                                        {{ $product->brand->name }}
                                                    </span>
                                                @endif
                                                @if ($product->color)
                                                    <span class="text-xs text-gray-400">· {{ $product->color }}</span>
                                                @endif
                                                @if ($product->stock !== null)
                                                    <span class="text-xs {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-500' }} font-semibold">
                                                        · {{ $product->stock > 0 ? $product->stock . ' em estoque' : 'Sem estoque' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Price --}}
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-sm font-extrabold text-[#6A2BBA]">
                                                R$ {{ number_format($product->price, 2, ',', '.') }}
                                            </p>
                                            @if ($product->price < $product->old_price && $product->old_price > 0)
                                                <p class="text-xs text-gray-400 line-through">
                                                    R$ {{ number_format($product->old_price, 2, ',', '.') }}
                                                </p>
                                            @endif
                                            {{-- MOCK: avaliação — implementar em breve --}}
                                            {{-- <div class="flex items-center justify-end gap-0.5 mt-0.5" title="Avaliação (em breve)">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fa-solid fa-star text-[9px] text-gray-200"></i>
                                                @endfor
                                            </div> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $('#configuredStore').click(function(e) {
            e.preventDefault();

            let storeId = this.dataset.storeid;

            $.ajax({
                url: `/configured-store/${storeId}`,
                method: 'POST',
                data: {
                    configured_store: 1
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    if (typeof hideLoader === 'function') hideLoader();
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.status);
                    if (typeof hideLoader === 'function') hideLoader();
                }
            }).always(function() {
                window.location.href = '{{ route('store.edit') }}';
            })
        })
    </script>
@endsection
