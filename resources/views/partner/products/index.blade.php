<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <div class="p-2 flex justify-center">
            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-1 px-1" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-semibold text-gray-700">Produtos</span>
                </nav>

                <div>

                    <div class="flex flex-col md:justify-between">

                        <div class="flex items-center justify-between">
                            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                                {{ __('Produtos') }}
                            </h2>
                            <button class="flex" href="javascript:void(0)">
                                <a href="{{ route('products.create') }}"
                                    class="inline-flex md:items-center gap-1 md:gap-2 px-4 py-[11px] md:px-2 md:py-[8px] border border-transparent text-sm leading-5 font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>
                                        {{ __('Novo Produto') }}
                                    </span>
                                </a>
                            </button>
                        </div>

                        <div class="flex flex-wrap md:items-center mt-3 gap-2">

                            <div class="w-full flex items-center gap-1 justify-between">
                                <div></div>
                            </div>

                            <div class="w-full flex justify-between items-center mt-2">
                                <div class="flex items-center gap-1 md:gap-2"></div>
                            </div>

                        </div>

                        <div class="font-medium text-gray-500 mt-0">
                            {{ count($products) }} Produtos
                        </div>

                    </div>

                    <!-- component -->
                    @if (count($products) === 0)
                        <div class="text-center mt-4 py-2">
                            <p class="text-gray-500 font-medium text-lg">Nenhum produto cadastrado</p>
                        </div>
                    @else
                        <div class="overflow-auto  md:overflow-hidden rounded-lg border border-gray-200 shadow-sm mt-3">

                            <div class="hidden md:block">
                                <div class="px-2 py-1 bg-gray-50 shadow-sm flex gap-3">
                                    <div class="font-semibold w-[37%]">
                                        Produto
                                    </div>
                                    <div class="font-semibold w-[10%]">
                                        Gênero
                                    </div>

                                    <div class="font-semibold w-[14%]">
                                        Preço
                                    </div>

                                    <div class="font-semibold w-[30%] px-1">
                                        Promocional
                                    </div>

                                    <div class="font-semibold w-[15%]">
                                        Ações
                                    </div>
                                </div>
                            </div>
                            <div id="listProductStatic" class="bg-white">

                                @foreach ($products as $product)
                                    <div class="listProductStatic py-2 border-b-gray-200 border-b md:flex">
                                        <div class="flex md:w-1/3 px-1 md:px-2">
                                            <div class="w-24 h-14 md:w-24 md:h-16 flex">

                                                @if ($product->images->isEmpty())
                                                    <img class="w-14 h-14 md:w-16 md:h-16 rounded-xl object-cover object-center"
                                                        src="/img/image-not-found.png" alt="" />
                                                @else
                                                    <img class="w-14 h-14 md:w-16 md:h-16 rounded-xl object-cover object-center"
                                                        src="{{ asset('storage/' . str_replace('public/', '', $product->images[0]->url)) }}"
                                                        alt="" />
                                                @endif
                                            </div>
                                            <div class="ml-2 w-full">
                                                <div class=" font-bold text-blue-600 cursor-pointer hover:text-blue-900">
                                                    <a href="{{ route('products.edit', $product->id) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </div>
                                                <div class="italic text-sm md:text-md font-medium">
                                                    {{ $product->brand->name }}
                                                </div>
                                            </div>

                                            <div class="md:hidden flex justify-end">
                                                <div class="px-2 flex gap-1">
                                                    <div>
                                                        <button id="dropdown-button-{{ $product->id }}"
                                                            class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                                            aria-expanded="false" aria-haspopup="true">
                                                            <svg class="w-5 h-5 text-gray-800" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-width="2"
                                                                    d="M7.926 10.898 15 7.727m-7.074 5.39L15 16.29M8 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm12 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0-11a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                                            </svg>

                                                        </button>
                                                    </div>

                                                    <div class="delete-action-container" data-id="{{ $product->id }}">
                                                        <button
                                                            class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                                            onclick="showDeleteConfirmationModal(event)"
                                                            x-data="{ tooltip: 'Delete' }">
                                                            <svg class="w-5 h-5 text-gray-800" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                            </svg>

                                                        </button>
                                                        <form action="{{ route('products.destroy', $product->id) }}"
                                                            class="deleteFormProduct" method="POST">
                                                            @method('DELETE')
                                                            @csrf
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class=" md:w-2/3 flex mt-0">
                                            <div class="flex px-3 w-full">
                                                <div class="w-full md:w-4/5 flex">
                                                    <div class="w-1/4 md:w-1/5 mt-[5px] md:mt-0">
                                                        <div class="text-xs font-medium text-gray-500 md:hidden">Gênero
                                                        </div>
                                                        <div class="text-sm font-medium mt-1">
                                                            {{ $product->gender == 'masculine' ? 'Masculino' : 'Feminino' }}
                                                        </div>
                                                    </div>

                                                    <div class="w-3/4 md:w-4/5 flex gap-2">

                                                        <div class="">
                                                            <label class="text-xs font-medium text-gray-500 mb-1 md:hidden"
                                                                for="">Preço</label>

                                                            <div class="relative md:w-full"
                                                                id="contentInputPrice{{ $product->id }}">
                                                                <span
                                                                    class="absolute text-xs inset-y-0 left-2 flex items-center pointer-events-none text-gray-400 mt-[2px]">
                                                                    R$
                                                                </span>
                                                                <input type="text"
                                                                    data-oldValuePrice="{{ $product->price }}"
                                                                    data-productId="{{ $product->id }}"
                                                                    class="inputPrice price-mask max-w-32 md:max-w-36 h-8 border border-gray-500 rounded-xl pl-7"
                                                                    value="{{ $product->price }}" />
                                                                <span id="spanIconPrice{{ $product->id }}"
                                                                    style="display: none"
                                                                    class="absolute bg-white h-[80%] mt-[6px] inset-y-0 right-2 flex items-center pointer-events-none">
                                                                    <svg class="w-5 h-5 text-green-500" aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" fill="none"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke="currentColor" stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <label class="text-xs font-medium text-gray-500 mb-1 md:hidden"
                                                                for="">Promocional</label>
                                                            <div class="relative md:w-full"
                                                                id="contentInputPricePromotional{{ $product->id }}">
                                                                <span
                                                                    class="absolute text-xs inset-y-0 left-2 flex items-center pointer-events-none text-gray-400 mt-[2px]">
                                                                    R$
                                                                </span>
                                                                <input type="text"
                                                                    data-oldValue="{{ $product->price_promotional }}"
                                                                    data-productId="{{ $product->id }}"
                                                                    class="inputPricePromotional price-mask max-w-32 md:max-w-36 h-8 border border-gray-500 rounded-xl pl-7"
                                                                    value="{{ $product->price_promotional }}" />
                                                                <span id="spanIconPricePromotional{{ $product->id }}"
                                                                    style="display: none"
                                                                    class="absolute bg-white h-[80%] inset-y-0 right-2 flex items-center pointer-events-none mt-[6px]">
                                                                    <svg class="w-5 h-5 text-green-500" aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" fill="none"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke="currentColor" stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="hidden md:block md:w-1/5">
                                                    <div class="flex gap-2">
                                                        <div>
                                                            <button id="dropdown-button-{{ $product->id }}"
                                                                class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                                                aria-expanded="false" aria-haspopup="true">
                                                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-width="2"
                                                                        d="M7.926 10.898 15 7.727m-7.074 5.39L15 16.29M8 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm12 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0-11a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                                                </svg>

                                                            </button>
                                                        </div>

                                                        <div class="delete-action-container"
                                                            data-id="{{ $product->id }}">
                                                            <button
                                                                class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                                                onclick="showDeleteConfirmationModal(event)"
                                                                x-data="{ tooltip: 'Delete' }">
                                                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                                </svg>

                                                            </button>
                                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                                class="deleteFormProduct" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            <div id="listProductDinamic" style="display: none" class="bg-white"></div>

                        </div>
                    @endif

                    @if (count($products) > 0)
                        <div class="flex justify-center mb-4 mt-4">
                            <div class="flex flex-col justify-center">
                                {{ $productsPaginator->links('partner.pagination') }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- SCRIPT - ATUALIZAÇÃO DE PREÇOS --}}
        <script>
            let inputPrice = $('.inputPrice');
            $(inputPrice).blur(function(e) {
                e.preventDefault();
                let oldInputValue = e.target.dataset.oldvalueprice;
                let newInputValue = $(this).val();

                let formattedNewInputValue = newInputValue.replace(/\./g, '').replace(',', '.');
                let spanIconPrice = document.querySelector('#spanIconPrice' + $(this).data('productid'));

                if (oldInputValue === formattedNewInputValue) {
                    return;
                } else {
                    // Mostra o span
                    spanIconPrice.style.display = 'block';

                    // Esconde o span após 3 segundos
                    setTimeout(() => {
                        spanIconPrice.style.display = 'none';
                    }, 6000); // 3000ms = 3 segundos

                    let productId = $(this).data('productid');
                    let price = $(this).val();

                    $.ajax({
                        url: 'products/update-price',
                        type: 'POST',
                        data: {
                            productId: productId,
                            price: price
                        },
                        success: function(response) {
                            console.log(response);
                        }
                    });
                }

            });


            let inputPricePromotional = $('.inputPricePromotional');
            $(inputPricePromotional).blur(function(e) {
                e.preventDefault();
                let oldInputValue = e.target.dataset.oldvalue;
                let newInputValue = $(this).val();

                let formattedNewInputValue = newInputValue.replace(/\./g, '').replace(',', '.');
                let spanIconPricePromotional = document.querySelector('#spanIconPricePromotional' + $(this).data(
                    'productid'));

                if (oldInputValue === formattedNewInputValue) {
                    return;
                } else {
                    // Mostra o span
                    spanIconPricePromotional.style.display = 'block';

                    // Esconde o span após 3 segundos
                    setTimeout(() => {
                        spanIconPricePromotional.style.display = 'none';
                    }, 6000); // 3000ms = 3 segundos

                    let productId = $(this).data('productid');
                    let pricePromotional = $(this).val();

                    $.ajax({
                        url: 'products/update-price-promotional',
                        type: 'POST',
                        data: {
                            productId: productId,
                            pricePromotional: pricePromotional
                        }
                    });
                }

            });
        </script>


        <!-- Modal de confirmação -->
        <div id="deleteConfirmationModalProduct" class="hidden fixed z-10 inset-0 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center mt-72 justify-center md:mt-0 px-4">

                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom md:w-1/4 bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 pt-2 pb-2">
                        <div class="mt-1">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <!-- Heroicon name: exclamation -->
                                <svg class="w-6 h-6 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>

                            </div>
                            <div class="mt-2 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    Excluir veículo
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Tem certeza de que deseja excluir este veículo? Esta ação não pode ser desfeita.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 flex gap-2 justify-between">
                        <div>
                            <button onclick="cancelDeleteButton()" type="button"
                                class="w-full justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </button>
                        </div>
                        <div class="">
                            <button onclick="confirmDeleteButton()" id="confirmDeleteButton" type="button"
                                class="w-full justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

</x-app-layout>

<script>
    const inputSearchName = document.getElementById('inputSearchProduct');

    $(inputSearchName).on('input', function() {
        const search = $(this).val().toLowerCase();

        $.ajax({
            url: 'products/search',
            type: 'GET',
            data: {
                search: search
            },
            success: function(response) {
                console.log(response);
                updateProductList(response.data);
            }
        });
    });


    function updateProductList(products) {
        const listProductStatic = $('#listProductStatic');
        listProductStatic.empty();

        const listProductDinamic = $('#listProductDinamic');
        listProductDinamic.empty();
        listProductDinamic[0].style.display = 'block';

        products.forEach(product => {

            const row = `
                <div class="py-2 border-b-gray-200 border-b md:flex">

                    <div class="flex md:w-[55%] md:px-2">
                        <div class="w-[22%] flex justify-center md:w-[25%]">
                        <img
                            class="w-14 h-14 md:w-16 md:h-16 rounded-xl object-cover object-center"
                            src="/storage/${product.image_main}"
                            alt=""
                        />
                        </div>
                        <div class="w-[53%] md:ml-2">
                            <div class="w-full md:w-full font-medium text-blue-600 cursor-pointer hover:text-blue-900">
                            <a href="/products/${product.id}/edit"> 
                                ${product.name}
                            </a>
                            </div>
                            <div class="italic text-sm md:text-md font-medium">
                                ${product.brand_name}
                            </div>
                        </div>
                    
                        <div class="w-[25%] md:hidden flex justify-end">
                        <div class="px-2 flex gap-1">
                            <div>
                                <button
                                    id="dropdown-button-${product.id}"
                                    class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                >
                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M7.926 10.898 15 7.727m-7.074 5.39L15 16.29M8 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm12 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0-11a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                                    </svg>
                                    
                                </button>
                            </div>

                            <div class="delete-action-container" data-id="${product.id}">
                                <button
                                    class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                    onclick="showDeleteConfirmationModal(event)" x-data="{ tooltip: 'Delete' }"
                                >
                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>
                                    
                                </button>
                                <form action="/products/${product.id}" class="deleteFormProduct" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>

                        </div>
                        </div>
                    </div>
                    
                    <div class="md:px-1 px-3 flex mt-0 md:w-[70%]">
                        <div class=" flex md:w-full">
                            <div class="w-1/3 md:w-[20%]">
                                <div class="text-xs font-medium text-gray-500 md:hidden">Classif.</div>
                                <div class="text-sm font-medium mt-1">${product.type == 'owner' ? 'Próprio' : 'Consignado'}</div>
                            </div>
                        
                            <div class="w-2/3 flex gap-2">
                                <div class="w-1/2 md:ml-2">
                                    <label class="text-xs font-medium text-gray-500 mb-1 md:hidden" for="">Preço</label>

                                    <div class="relative md:w-full" id="contentInputPrice${product.id}">
                                        <span class="absolute text-xs inset-y-0 left-2 flex items-center pointer-events-none text-gray-400 mt-[2px]">
                                            R$                                              
                                        </span>
                                        <input
                                            type="text"
                                            data-oldValuePrice="${product.price}"
                                            data-productId="${product.id}"
                                            class="inputPrice price-mask w-full h-8 border border-gray-500 rounded-xl pl-7"
                                            value="${product.price}"
                                        />
                                        <span id="spanIconPrice${product.id}" style="display: none" class="absolute bg-white h-[80%] mt-[6px] inset-y-0 right-2 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>                                              
                                        </span>
                                    </div>
                                </div>
                        
                                <div class="w-1/2">
                                    <label class="text-xs font-medium text-gray-500 mb-1 md:hidden" for="">Promocional</label>
                                    <div class="relative md:w-full" id="contentInputPricePromotional${product.id}">
                                        <span class="absolute text-xs inset-y-0 left-2 flex items-center pointer-events-none text-gray-400 mt-[2px]">
                                            R$                                              
                                        </span>
                                        <input
                                            type="text"
                                            data-oldValue="${product.price_promotional}"
                                            data-productId="${product.id}"
                                            class="inputPricePromotional price-mask w-full h-8 border border-gray-500 rounded-xl pl-7"
                                            value="${product.price_promotional}"
                                        />
                                        <span id="spanIconPricePromotional${product.id}" style="display: none" class="absolute bg-white h-[80%] inset-y-0 right-2 flex items-center pointer-events-none mt-[6px]">
                                            <svg class="w-5 h-5 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>                                              
                                        </span>
                                    </div>
                                </div>
                        </div>
                        </div>
                    </div>

                    <div class="w-1/4 px-10 hidden md:block">
                        <div class="px-2 flex gap-2">
                            <div>
                                <button
                                    id="dropdown-button-${product.id}"
                                    class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                >
                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M7.926 10.898 15 7.727m-7.074 5.39L15 16.29M8 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm12 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0-11a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                                    </svg>
                                    
                                </button>
                            </div>

                            <div class="delete-action-container" data-id="${product.id}">
                                <button
                                    class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                    onclick="showDeleteConfirmationModal(event)" x-data="{ tooltip: 'Delete' }"
                                >
                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>
                                    
                                </button>
                                <form action="/products/${product.id}" class="deleteFormProduct" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>

                        </div>
                    </div>

                </div>   
            `;
            listProductDinamic.append(row);
        });
    }


    // Função para mostrar o modal de confirmação
    function showDeleteConfirmationModal(event) {
        const productId = event.target.closest('.delete-action-container').dataset.id;
        document.querySelector('#deleteConfirmationModalProduct').dataset.productId = productId;
        document.getElementById('deleteConfirmationModalProduct').classList.remove('hidden');
    }

    // Função para ocultar o modal de confirmação
    function hideDeleteConfirmationModal() {
        document.getElementById('deleteConfirmationModalProduct').classList.add('hidden');
    }

    // Evento de clique no botão de confirmar exclusão
    function confirmDeleteButton() {
        const productId = document.querySelector('#deleteConfirmationModalProduct').dataset.productId;
        const form = document.querySelector('.deleteFormProduct');
        form.action = `/products/destroy/${productId}`;
        form.submit();
    }

    // Evento de clique no botão de cancelar exclusão
    function cancelDeleteButton() {
        hideDeleteConfirmationModal();
    }
</script>

<style>
    /* Animação de entrada */
    @keyframes slideInRight {
        from {
            transform: translateX(1%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
