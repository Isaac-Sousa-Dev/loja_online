<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <div class="p-2 flex justify-center pb-24 md:pb-4">
            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Início</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-semibold text-gray-700">Produtos</span>
                </nav>

                {{-- Header --}}
                <div class="flex items-center justify-between mt-1 mb-3 px-1">
                    <h2 class="font-semibold text-2xl text-gray-800">Produtos</h2>
                    <a href="{{ route('products.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors shadow-sm">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span class="">Novo Produto</span>
                    </a>
                </div>

                {{-- Stats bar --}}
                <div class="flex items-center gap-3 mb-3 px-1">
                    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 bg-gray-100 rounded-full px-3 py-1">
                        <i class="fa-solid fa-box text-xs text-blue-500"></i>
                        {{ count($products) }} {{ count($products) === 1 ? 'produto' : 'produtos' }}
                    </span>
                </div>

                @if (count($products) === 0)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
                        <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-box-open text-blue-400 text-xl"></i>
                        </div>
                        <p class="text-gray-700 font-semibold text-lg">Nenhum produto cadastrado</p>
                        <p class="text-gray-400 text-sm mt-1">Adicione seu primeiro produto para aparecer aqui.</p>
                        <a href="{{ route('products.create') }}"
                            class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium transition-colors">
                            <i class="fa-solid fa-plus text-xs"></i> Adicionar produto
                        </a>
                    </div>
                @else
                    {{-- Table Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                        {{-- Desktop header --}}
                        <div class="hidden md:grid grid-cols-12 gap-2 px-4 py-2.5 bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <div class="col-span-5">Produto</div>
                            <div class="col-span-2 text-center">Estoque</div>
                            <div class="col-span-2">Preço</div>
                            <div class="col-span-2">Promocional</div>
                            <div class="col-span-1 text-right">Ações</div>
                        </div>

                        <div id="listProductStatic">
                            @foreach ($products as $product)
                                @php
                                    $stock = $product->stock ?? 0;
                                    $stockClass = $stock > 10 ? 'bg-emerald-50 text-emerald-700' : ($stock > 0 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-600');
                                    $stockIcon = $stock > 10 ? 'fa-circle-check' : ($stock > 0 ? 'fa-triangle-exclamation' : 'fa-circle-xmark');
                                @endphp
                                <div class="listProductStatic border-b border-gray-100 last:border-b-0 md:grid md:grid-cols-12 md:gap-2 md:items-center px-3 md:px-4 py-3 hover:bg-gray-50 transition-colors">

                                    {{-- Product info (mobile = full row, desktop = col-span-5) --}}
                                    <div class="col-span-5 flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if ($product->images->isEmpty())
                                                <img class="w-12 h-12 rounded-xl object-cover bg-gray-100" src="/img/image-not-found.png" alt="" />
                                            @else
                                                <img class="w-12 h-12 rounded-xl object-cover" src="{{ asset('storage/' . str_replace('public/', '', $product->images[0]->url)) }}" alt="{{ $product->name }}" />
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('products.edit', $product->id) }}" class="font-semibold text-gray-800 hover:text-blue-600 transition-colors text-sm leading-snug line-clamp-1">
                                                {{ $product->name }}
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                @if($product->brand)
                                                    <span class="text-xs text-gray-400">{{ $product->brand->name }}</span>
                                                @endif
                                                @if($product->category)
                                                    <span class="text-[10px] bg-blue-50 text-blue-600 rounded-full px-2 py-0.5 font-medium">{{ $product->category->name }}</span>
                                                @endif
                                            </div>
                                            {{-- Stock badge (mobile only) --}}
                                            <div class="md:hidden mt-1">
                                                <span class="inline-flex items-center gap-1 text-xs font-medium {{ $stockClass }} rounded-full px-2 py-0.5">
                                                    <i class="fa-solid {{ $stockIcon }} text-[10px]"></i>
                                                    {{ $stock }} em estoque
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Stock (desktop) --}}
                                    <div class="col-span-2 hidden md:flex justify-center">
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $stockClass }} rounded-full px-2.5 py-1">
                                            <i class="fa-solid {{ $stockIcon }} text-[10px]"></i>
                                            {{ $stock }}
                                        </span>
                                    </div>

                                    {{-- Prices --}}
                                    <div class="col-span-4 flex gap-2 mt-2 md:mt-0">
                                        {{-- Price --}}
                                        <div class="flex-1">
                                            <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide md:hidden">Preço</label>
                                            <div class="relative" id="contentInputPrice{{ $product->id }}">
                                                <span class="absolute text-xs inset-y-0 left-2 flex items-center pointer-events-none text-gray-400">R$</span>
                                                <input type="text"
                                                    data-oldValuePrice="{{ $product->price }}"
                                                    data-productId="{{ $product->id }}"
                                                    class="inputPrice price-mask w-full max-w-[120px] h-8 text-sm border border-gray-200 rounded-lg pl-7 focus:border-blue-400 focus:ring-1 focus:ring-blue-100 outline-none transition"
                                                    value="{{ $product->price }}" />
                                                <span id="spanIconPrice{{ $product->id }}" style="display:none" class="absolute inset-y-0 right-1.5 flex items-center">
                                                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Promo price --}}
                                        <div class="flex-1">
                                            <label class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide md:hidden">Promo</label>
                                            <div class="relative" id="contentInputPricePromotional{{ $product->id }}">
                                                <span class="absolute text-xs inset-y-0 left-2 flex items-center pointer-events-none text-gray-400">R$</span>
                                                <input type="text"
                                                    data-oldValue="{{ $product->price_promotional }}"
                                                    data-productId="{{ $product->id }}"
                                                    class="inputPricePromotional price-mask w-full max-w-[120px] h-8 text-sm border border-gray-200 rounded-lg pl-7 focus:border-blue-400 focus:ring-1 focus:ring-blue-100 outline-none transition"
                                                    value="{{ $product->price_promotional }}" />
                                                <span id="spanIconPricePromotional{{ $product->id }}" style="display:none" class="absolute inset-y-0 right-1.5 flex items-center">
                                                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="col-span-1 flex items-center justify-end gap-1.5 mt-2 md:mt-0">
                                        <a href="{{ route('products.edit', $product->id) }}" title="Editar"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <div class="delete-action-container" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                            <button onclick="showDeleteConfirmationModal(event)" title="Excluir"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                            <form action="{{ route('products.destroy', $product->id) }}" class="deleteFormProduct" method="POST">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <div id="listProductDinamic" style="display: none"></div>

                    </div>

                    {{-- Pagination --}}
                    <div class="flex justify-center mt-4 mb-2">
                        <div class="flex flex-col justify-center">
                            {{ $productsPaginator->links('partner.pagination') }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div id="deleteConfirmationModalProduct"
            class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="cancelDeleteButton()"></div>

            {{-- Modal Card --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-modalIn">

                {{-- Icon --}}
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fa-solid fa-trash text-red-500 text-xl"></i>
                    </div>
                </div>

                {{-- Text --}}
                <div class="text-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Excluir produto?</h3>
                    <p class="text-sm text-gray-500">Você está prestes a excluir <strong id="deleteModalProductName" class="text-gray-700"></strong>. Esta ação <strong>não pode ser desfeita</strong>.</p>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button onclick="cancelDeleteButton()" type="button"
                        class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-medium text-sm hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button onclick="confirmDeleteButton()" id="confirmDeleteButton" type="button"
                        class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-sm">
                        <i class="fa-solid fa-trash mr-1.5 text-xs"></i> Excluir
                    </button>
                </div>
            </div>
        </div>

    @endsection

</x-app-layout>

{{-- Scripts --}}
<script>
    {{-- Price inline-edit --}}
    let inputPrice = $('.inputPrice');
    $(inputPrice).blur(function(e) {
        e.preventDefault();
        let oldInputValue = e.target.dataset.oldvalueprice;
        let newInputValue = $(this).val();
        let formattedNewInputValue = newInputValue.replace(/\./g, '').replace(',', '.');
        let spanIconPrice = document.querySelector('#spanIconPrice' + $(this).data('productid'));

        if (oldInputValue === formattedNewInputValue) return;

        spanIconPrice.style.display = 'flex';
        setTimeout(() => { spanIconPrice.style.display = 'none'; }, 6000);

        $.ajax({
            url: 'products/update-price',
            type: 'POST',
            data: { productId: $(this).data('productid'), price: $(this).val() },
        });
    });

    let inputPricePromotional = $('.inputPricePromotional');
    $(inputPricePromotional).blur(function(e) {
        e.preventDefault();
        let oldInputValue = e.target.dataset.oldvalue;
        let newInputValue = $(this).val();
        let formattedNewInputValue = newInputValue.replace(/\./g, '').replace(',', '.');
        let spanIconPricePromotional = document.querySelector('#spanIconPricePromotional' + $(this).data('productid'));

        if (oldInputValue === formattedNewInputValue) return;

        spanIconPricePromotional.style.display = 'flex';
        setTimeout(() => { spanIconPricePromotional.style.display = 'none'; }, 6000);

        $.ajax({
            url: 'products/update-price-promotional',
            type: 'POST',
            data: { productId: $(this).data('productid'), pricePromotional: $(this).val() },
        });
    });

    {{-- Search --}}
    const inputSearchName = document.getElementById('inputSearchProduct');
    if (inputSearchName) {
        $(inputSearchName).on('input', function() {
            const search = $(this).val().toLowerCase();
            $.ajax({
                url: 'products/search', type: 'GET', data: { search },
                success: function(response) { updateProductList(response.data); }
            });
        });
    }

    function updateProductList(products) {
        const listProductStatic = $('#listProductStatic');
        listProductStatic.empty();
        const listProductDinamic = $('#listProductDinamic');
        listProductDinamic.empty();
        listProductDinamic[0].style.display = 'block';

        products.forEach(product => {
            const stock = product.stock ?? 0;
            const stockClass = stock > 10 ? 'bg-emerald-50 text-emerald-700' : (stock > 0 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-600');
            const stockIcon = stock > 10 ? 'fa-circle-check' : (stock > 0 ? 'fa-triangle-exclamation' : 'fa-circle-xmark');

            const row = `
                <div class="border-b border-gray-100 last:border-b-0 md:grid md:grid-cols-12 md:gap-2 md:items-center px-3 md:px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="col-span-5 flex items-center gap-3">
                        <img class="w-12 h-12 rounded-xl object-cover bg-gray-100" src="/storage/${product.image_main}" alt="" />
                        <div class="min-w-0">
                            <a href="/products/${product.id}/edit" class="font-semibold text-gray-800 hover:text-blue-600 text-sm line-clamp-1">${product.name}</a>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-xs text-gray-400">${product.brand_name}</span>
                            </div>
                            <div class="md:hidden mt-1">
                                <span class="inline-flex items-center gap-1 text-xs font-medium ${stockClass} rounded-full px-2 py-0.5">
                                    <i class="fa-solid ${stockIcon} text-[10px]"></i> ${stock} em estoque
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2 hidden md:flex justify-center">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold ${stockClass} rounded-full px-2.5 py-1">
                            <i class="fa-solid ${stockIcon} text-[10px]"></i> ${stock}
                        </span>
                    </div>
                    <div class="col-span-4 flex gap-2 mt-2 md:mt-0">
                        <div class="flex-1 relative" id="contentInputPrice${product.id}">
                            <span class="absolute text-xs inset-y-0 left-2 flex items-center text-gray-400">R$</span>
                            <input type="text" data-oldValuePrice="${product.price}" data-productId="${product.id}"
                                class="inputPrice price-mask w-full max-w-[120px] h-8 text-sm border border-gray-200 rounded-lg pl-7 outline-none"
                                value="${product.price}" />
                            <span id="spanIconPrice${product.id}" style="display:none" class="absolute inset-y-0 right-1.5 flex items-center">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                            </span>
                        </div>
                        <div class="flex-1 relative" id="contentInputPricePromotional${product.id}">
                            <span class="absolute text-xs inset-y-0 left-2 flex items-center text-gray-400">R$</span>
                            <input type="text" data-oldValue="${product.price_promotional}" data-productId="${product.id}"
                                class="inputPricePromotional price-mask w-full max-w-[120px] h-8 text-sm border border-gray-200 rounded-lg pl-7 outline-none"
                                value="${product.price_promotional}" />
                            <span id="spanIconPricePromotional${product.id}" style="display:none" class="absolute inset-y-0 right-1.5 flex items-center">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-span-1 flex items-center justify-end gap-1.5 mt-2 md:mt-0">
                        <a href="/products/${product.id}/edit" title="Editar"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition-all">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <div class="delete-action-container" data-id="${product.id}" data-name="${product.name}">
                            <button onclick="showDeleteConfirmationModal(event)" title="Excluir"
                                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-all">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <form action="/products/destroy/${product.id}" class="deleteFormProduct" method="POST">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            `;
            listProductDinamic.append(row);
        });
    }

    {{-- Modal --}}
    function showDeleteConfirmationModal(event) {
        const container = event.target.closest('.delete-action-container');
        const productId = container.dataset.id;
        const productName = container.dataset.name || 'este produto';
        document.querySelector('#deleteConfirmationModalProduct').dataset.productId = productId;
        document.getElementById('deleteModalProductName').textContent = productName;
        document.getElementById('deleteConfirmationModalProduct').classList.remove('hidden');
    }

    function cancelDeleteButton() {
        document.getElementById('deleteConfirmationModalProduct').classList.add('hidden');
    }

    function confirmDeleteButton() {
        const productId = document.querySelector('#deleteConfirmationModalProduct').dataset.productId;
        const form = document.querySelector('.deleteFormProduct');
        form.action = `/products/destroy/${productId}`;

        const btn = document.getElementById('confirmDeleteButton');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Excluindo...';

        form.submit();
    }
</script>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.92) translateY(12px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modalIn { animation: modalIn 0.25s cubic-bezier(0.34,1.26,0.64,1) both; }
</style>
