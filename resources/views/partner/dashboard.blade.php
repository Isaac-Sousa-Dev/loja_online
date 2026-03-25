@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <div class="p-2 flex md:justify-center pb-24 md:pb-0">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1" aria-label="breadcrumb">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span>Início</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="font-semibold text-gray-700">Visão Geral</span>
            </nav>

            <h2 class="font-semibold text-3xl mb-4 mt-1 text-gray-800 leading-tight px-1">
                {{ __('Dashboard') }}
            </h2>

            {{-- Onboarding Messages --}}
            @if ($user->first_login == 1 && $store->configured_store == 0)
                <div
                    class="bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                            <i class="fa-solid fa-flag-checkered"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Bem-vindo(a) ao sistema!</p>
                            <p class="text-sm mt-1 text-green-700">Você está no seu primeiro acesso. Para começar a usar o
                                sistema, configure os dados essenciais da sua loja.</p>
                        </div>
                    </div>
                    <div>
                        <button id="configuredStore" data-storeid="{{ $store->id }}"
                            class="w-full md:w-auto bg-green-600 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-green-700 transition shadow-sm whitespace-nowrap">
                            Configurar Loja
                        </button>
                    </div>
                </div>
            @elseif($categoriesByStore->isEmpty())
                <div
                    class="bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Vamos para o próximo passo!</p>
                            <p class="text-sm mt-1 text-blue-700">Cadastre uma ou mais categorias para organizar o seu
                                catálogo (ex: Celulares, Tênis, Moveis).</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('categories.create') }}"
                            class="block w-full text-center md:w-auto bg-blue-600 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm whitespace-nowrap">
                            Cadastrar Categoria
                        </a>
                    </div>
                </div>
            @elseif($subcategoriesByStore->isEmpty())
                <div
                    class="bg-purple-50 border border-purple-200 text-purple-800 rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0">
                            <i class="fa-solid fa-copyright"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Cadastrar Marca de Produtos!</p>
                            <p class="text-sm mt-1 text-purple-700">Cadastre as marcas que compõem o seu estoque (ex: Nike,
                                Adidas, Zara).</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('brands.create') }}"
                            class="block w-full text-center md:w-auto bg-purple-600 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-purple-700 transition shadow-sm whitespace-nowrap">
                            Cadastrar Marca
                        </a>
                    </div>
                </div>
            @elseif($quantityStockProducts <= 0)
                <div
                    class="bg-orange-50 border border-orange-200 text-orange-800 rounded-2xl p-4 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div
                            class="mt-1 w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Finalizando nosso Tour!</p>
                            <p class="text-sm mt-1 text-orange-700">Cadastre seu primeiro produto para que seus clientes o
                                vejam online no seu catálogo público.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('products.create') }}"
                            class="block w-full text-center md:w-auto bg-orange-600 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-orange-700 transition shadow-sm whitespace-nowrap">
                            Cadastrar Produto
                        </a>
                    </div>
                </div>
            @endif

            {{-- Metric Widgets --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

                {{-- Estoque --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Produtos no estoque</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quantityStockProducts }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                            fill="currentColor"><!--!Font Awesome Free 6.6.0...-->
                            <path
                                d="M500 176h-59.9l-16.6-41.6C406.4 91.6 365.6 64 319.5 64h-127c-46.1 0-86.9 27.6-104 70.4L71.9 176H12C4.2 176-1.5 183.3 .4 190.9l6 24C7.7 220.3 12.5 224 18 224h20.1C24.7 235.7 16 252.8 16 272v48c0 16.1 6.2 30.7 16 41.9V416c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h256v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-54.1c9.8-11.3 16-25.8 16-41.9v-48c0-19.2-8.7-36.3-22.1-48H494c5.5 0 10.3-3.8 11.6-9.1l6-24c1.9-7.6-3.8-14.9-11.7-14.9zm-352.1-17.8c7.3-18.2 24.9-30.2 44.6-30.2h127c19.6 0 37.3 12 44.6 30.2L384 208H128l19.9-49.8zM96 319.8c-19.2 0-32-12.8-32-31.9S76.8 256 96 256s48 28.7 48 47.9-28.8 16-48 16zm320 0c-19.2 0-48 3.2-48-16S396.8 256 416 256s32 12.8 32 31.9-12.8 31.9-32 31.9z" />
                        </svg>
                    </div>
                </div>

                {{-- Vendas --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Vendas Concluídas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quantitySales }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                            fill="currentColor"><!--!Font Awesome Free 6.6.0...-->
                            <path
                                d="M352 288h-16v-88c0-4.4-3.6-8-8-8h-13.6c-4.7 0-9.4 1.4-13.3 4l-15.3 10.2a8 8 0 0 0 -2.2 11.1l8.9 13.3a8 8 0 0 0 11.1 2.2l.5-.3V288h-16c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h64c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zM608 64H32C14.3 64 0 78.3 0 96v320c0 17.7 14.3 32 32 32h576c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zM48 400v-64c35.4 0 64 28.7 64 64H48zm0-224v-64h64c0 35.4-28.7 64-64 64zm272 192c-53 0-96-50.2-96-112 0-61.9 43-112 96-112s96 50.1 96 112c0 61.9-43 112-96 112zm272 32h-64c0-35.4 28.7-64 64-64v64zm0-224c-35.4 0-64-28.7-64-64h64v64z" />
                        </svg>
                    </div>
                </div>

                {{-- Solicitações --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Novos Pedidos</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quantityRequests }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"
                            fill="currentColor"><!--!Font Awesome Free 6.6.0...-->
                            <path
                                d="M336 0H48C21.5 0 0 21.5 0 48v464l192-112 192 112V48c0-26.5-21.5-48-48-48zm0 428.4l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.3 0 6 2.7 6 6V428.4z" />
                        </svg>
                    </div>
                </div>

                {{-- Clientes --}}
                <div
                    class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-1">Meus Clientes</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $quantityClients }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Lower Panels --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                {{-- Left: Últimos Pedidos --}}
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-between mb-1 px-1">
                        <div class="flex gap-2 items-center">
                            <i class="fa-solid fa-list-ol text-gray-700"></i>
                            <h3 class="font-semibold text-lg text-gray-800">Últimos Pedidos</h3>
                        </div>
                        <a href="{{ route('requests.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition px-1">
                            Ver todos →
                        </a>
                    </div>
                    <p class="text-sm text-gray-500 mb-3 px-1">Pedidos recentes recebidos pelo catálogo.</p>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1 overflow-hidden">
                        @if ($requestsByStore->count() == 0)
                            <div class="flex flex-col items-center justify-center p-10 text-gray-400">
                                <i class="fa-solid fa-inbox text-4xl mb-3 opacity-40"></i>
                                <span class="font-medium text-sm">Nenhum pedido recebido ainda.</span>
                                <span class="text-xs mt-1 text-gray-300">Os pedidos do seu catálogo aparecerão aqui.</span>
                            </div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach ($requestsByStore as $request)
                                    <div class="px-4 py-3.5 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between gap-2">

                                            {{-- Status badge --}}
                                            <div class="flex flex-wrap gap-1.5 items-center">
                                                @php
                                                    $statusMap = [
                                                        'in_open'     => ['label' => 'Em aberto',    'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
                                                        'in_progress' => ['label' => 'Em andamento', 'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                                                        'sold'        => ['label' => 'Vendido',       'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                                                        'canceled'    => ['label' => 'Cancelado',     'class' => 'bg-red-100 text-red-600 border-red-200'],
                                                    ];
                                                    $s = $statusMap[$request->status] ?? ['label' => 'Desconhecido', 'class' => 'bg-gray-100 text-gray-500 border-gray-200'];
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold border {{ $s['class'] }}">
                                                    {{ $s['label'] }}
                                                </span>

                                                @if ($request->shift == 1)
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700 border border-sky-200">Negociação</span>
                                                @endif
                                                @if ($request->finance == 1)
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">Financiamento</span>
                                                @endif
                                            </div>

                                            <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0 mt-0.5">
                                                <i class="fa-regular fa-clock mr-0.5"></i>
                                                {{ $request->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                <p class="font-bold text-gray-800 text-sm truncate">{{ $request->product->name }}</p>
                                                <div class="flex items-center gap-3 mt-0.5">
                                                    @if ($request->client)
                                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                                            <i class="fa-regular fa-user text-[10px]"></i>
                                                            {{ $request->client->name }}
                                                        </span>
                                                        @if ($request->client->phone)
                                                            <span class="text-xs text-gray-400 flex items-center gap-1">
                                                                <i class="fa-solid fa-phone text-[10px]"></i>
                                                                {{ $request->client->phone }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">Cliente não identificado</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Preço do produto --}}
                                            @if ($request->product->price)
                                                <span class="text-sm font-extrabold text-blue-700 flex-shrink-0">
                                                    R$ {{ number_format($request->product->price, 2, ',', '.') }}
                                                </span>
                                            @endif
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
                            <i class="fa-solid fa-layer-group text-gray-700"></i>
                            <h3 class="font-semibold text-lg text-gray-800">Produtos Recentes</h3>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition px-1">
                            Ver todos →
                        </a>
                    </div>
                    <p class="text-sm text-gray-500 mb-3 px-1">Últimos produtos adicionados ao catálogo.</p>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1 overflow-hidden">
                        @if ($latestProducts->count() == 0)
                            <div class="flex flex-col items-center justify-center p-10 text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 opacity-40"></i>
                                <span class="font-medium text-sm">Nenhum produto cadastrado.</span>
                                <a href="{{ route('products.create') }}" class="mt-3 text-xs font-semibold text-blue-600 hover:underline">
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
                                            <p class="text-sm font-extrabold text-blue-700">
                                                R$ {{ number_format($product->price, 2, ',', '.') }}
                                            </p>
                                            @if ($product->price < $product->old_price && $product->old_price > 0)
                                                <p class="text-xs text-gray-400 line-through">
                                                    R$ {{ number_format($product->old_price, 2, ',', '.') }}
                                                </p>
                                            @endif
                                            {{-- MOCK: avaliação — implementar em breve --}}
                                            <div class="flex items-center justify-end gap-0.5 mt-0.5" title="Avaliação (em breve)">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fa-solid fa-star text-[9px] text-gray-200"></i>
                                                @endfor
                                            </div>
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
