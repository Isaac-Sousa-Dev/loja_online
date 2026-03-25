<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <div class="flex md:justify-center pb-24 md:pb-0">

            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Início</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition-colors">Produtos</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-semibold text-gray-700 truncate max-w-[200px]">{{ $product->name }}</span>
                </nav>

                {{-- Title + Back button --}}
                <div class="flex items-center gap-3 mt-2 mb-1 px-1">
                    <a href="{{ route('products.index') }}"
                       class="flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-400 hover:shadow-md transition-all"
                       title="Voltar para Produtos">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                    <h2 class="font-semibold text-2xl text-gray-800 truncate">
                        {{ $product->name }}
                    </h2>
                </div>

                <div class="p-1 mt-3 md:flex gap-4 lg:gap-6">

                    <form class="md:w-[70%]" action="{{ route('products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- Dados do produto --}}
                        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col mb-4">
                            <div class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class="fa-solid fa-box text-sm"></i>
                                </div> 
                                Dados do produto
                            </div>
                            <div class="md:flex mb-0 gap-3">
                                <div class="w-full mb-3">
                                    <x-input-label for="category_id" :value="__('Categoria *')" />
                                    <div class="w-full">
                                        <select id="category_id"
                                            class="select-category border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full">
                                            @foreach ($categoriesByPartner as $storeCategory)
                                                <option value="{{ $storeCategory->category->id }}"
                                                    @selected($storeCategory->category->id == $product->category_id)>
                                                    {{ $storeCategory->category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <script>
                                            new TomSelect(".select-category", {
                                                create: false,
                                                sortField: {
                                                    field: "text",
                                                    direction: "asc"
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="w-full mb-3">
                                    <x-input-label for="brand_id" :value="__('Marca *')" />
                                    <div class="w-full">
                                        <select id="brand_id"
                                            class="select-brands border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full">
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" @selected($brand->id == $product->brand_id)>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <script>
                                            new TomSelect(".select-brands", {
                                                create: false,
                                                sortField: {
                                                    field: "text",
                                                    direction: "asc"
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>

                            <div class="md:flex mb-3">
                                <div class="w-full">
                                    <x-input-label for="name" :value="__('Nome *')" />
                                    <x-text-input id="name" placeholder="Camisa Polo Azul" name="name"
                                        type="text" value="{{ $product->name }}" class="required" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                            </div>

                            <div class="md:flex mb-0">
                                <div class="w-full mb-3">
                                    <x-input-label for="description" :value="__('Sobre este produto *')" />
                                    <x-textarea id="description" class="required" placeholder="Descreva algo..."
                                        name="description" type="text" autofocus
                                        autocomplete="name">{{ $product->description }}</x-textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Fotos --}}
                        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col mb-4">
                            <div class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                                    <i class="fa-solid fa-camera text-sm"></i>
                                </div>
                                Fotos
                                <div class="flex gap-1 items-center ml-2 border-l border-gray-200 pl-3">
                                    <i class="fa-solid fa-info-circle text-blue-500 text-xs"></i>
                                    <span class="text-gray-500 text-xs font-normal">As fotos aparecem no seu catálogo.</span>
                                </div>
                            </div>

                            <div class="py-1 md:flex md:flex-row items-center gap-2 space-y-2 md:space-y-0">
                                <div class="grid grid-cols-2 gap-1 md:w-1/2">
                                    <label for="" class="w-full">
                                        <div
                                            class="OpenModalImagesProducts relative bg-blue-50 w-full h-32 cursor-pointer rounded-lg border-dashed border-2 border-sky-500 flex flex-col px-0">
                                            <div class="font-semibold text-xs mt-1 flex z-10 ml-2">
                                                <div
                                                    class="bg-gray-200 border-1 pb-[2px] border-gray-500 flex items-center px-1 rounded-full gap-1">
                                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 576 512">
                                                        <path
                                                            d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
                                                    </svg>
                                                    principal
                                                </div>
                                            </div>
                                            <div class="h-2/3 flex flex-col justify-center items-center">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 448 512">
                                                    <path
                                                        d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                                </svg>
                                                <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                    Adicione a melhor foto do produto
                                                </div>
                                            </div>
                                            <img src="" style="display: none"
                                                class="previewsImagesInProductPage object-cover h-full w-full absolute rounded-lg"
                                                alt="">
                                        </div>
                                    </label>

                                    <label for="" class="w-full">
                                        <div
                                            class="OpenModalImagesProducts relative bg-blue-50 w-full h-32 cursor-pointer rounded-lg border-dashed border-2 border-sky-500 flex flex-col justify-center items-center">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                <path
                                                    d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                            </svg>
                                            <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                Mostre a qualidade do produto
                                            </div>
                                            <img src="" style="display: none" alt=""
                                                class="previewsImagesInProductPage object-cover h-full w-full absolute rounded-lg">
                                        </div>
                                    </label>
                                </div>

                                <div class="grid grid-cols-2 gap-1 md:w-1/2">
                                    <label for="" class="w-full">
                                        <div
                                            class="OpenModalImagesProducts relative bg-blue-50 w-full h-32 cursor-pointer rounded-lg border-dashed border-2 border-sky-500 flex flex-col justify-center items-center">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                <path
                                                    d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                            </svg>
                                            <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                Dê vida ao seu produto
                                            </div>
                                            <img src="" style="display: none" alt=""
                                                class="previewsImagesInProductPage object-cover h-full w-full absolute rounded-lg">
                                        </div>
                                    </label>

                                    <label for="" class="w-full">
                                        <div
                                            class="OpenModalImagesProducts relative opacity-85 bg-blue-50 w-full h-32 cursor-pointer rounded-lg border-dashed border-2 border-sky-500 flex flex-col justify-center items-center">
                                            <div id="fourTextImageProductAfter" class="flex items-center flex-col">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 448 512">
                                                    <path
                                                        d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                                </svg>
                                                <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                    Mostre fotos nítidas
                                                </div>
                                            </div>
                                            <div id="fourTextImageProductBefore" style="display: none"
                                                class="leading-3 z-10 mt-2 text-center text-2xl font-semibold px-2">
                                                + <span id="quantityImagesProductAvailable"></span>
                                            </div>
                                            <img src="" id="previewSecondImage" style="display: none"
                                                alt=""
                                                class="previewsImagesInProductPage object-cover h-full w-full absolute opacity-50 rounded-lg">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Preços --}}
                        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col mb-4">
                            <div class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <i class="fa-solid fa-tag text-sm"></i>
                                </div>
                                Preços
                            </div>

                            <div id="divCostGreaterPrice" class="hidden">
                                <div class="bg-sky-300 px-2 py-1 rounded-lg mb-2 flex items-center gap-1">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span id="msgCostGreaterPrice" class="text-white font-medium text-sm"></span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="w-full flex gap-2">
                                    <div class="w-1/3">
                                        <x-input-label for="price" :value="__('Preço de venda *')" />
                                        <x-text-input id="price" placeholder="R$ 0.00" name="price" type="text"
                                            value="{{ $product->price }}" class="price-mask required" />
                                    </div>
                                    <div class="w-1/3">
                                        <x-input-label for="price_wholesale" :value="__('Preço de atacado *')" />
                                        <x-text-input id="price_wholesale" placeholder="R$ 0.00" name="price_wholesale"
                                            type="text" value="{{ $product->price_wholesale }}" class="price-mask" />
                                    </div>
                                    <div class="w-1/3">
                                        <x-input-label for="price_promotional" :value="__('Preço promocional')" />
                                        <x-text-input id="price_promotional" placeholder="R$ 0.00"
                                            name="price_promotional" type="text"
                                            value="{{ $product->price_promotional }}" class="price-mask" />
                                        <x-input-error class="mt-2" :messages="$errors->get('price_promotional')" />
                                    </div>
                                </div>

                                <div class="w-full flex gap-2">
                                    <div class="w-1/2">
                                        <x-input-label for="cost" :value="__('Custo')" />
                                        <x-text-input id="cost" placeholder="R$ 0.00" name="cost" type="text"
                                            value="{{ $product->cost }}" class="price-mask" />
                                    </div>
                                    <div class="w-1/2">
                                        <x-input-label for="profit" :value="__('Margem de lucro')" />
                                        <x-text-input disabled id="profit" data-profit="{{ $product }}"
                                            placeholder="--" name="profit" type="text"
                                            value="{{ $product->profit }}" class="bg-gray-200" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            let dataProfit = $('#profit').data('profit');
                            let priceValue = $('#price');
                            let costValue = $('#cost');
                            let divProfit = $('#profit');

                            let currentProfit = dataProfit.profit;
                            divProfit[0].value = currentProfit + '%';

                            $(priceValue).blur(function(e) {
                                let priceInputValue = parseFloat(priceValue.val().replace(/\./g, '').replace(',', '.'));
                                let costInputValue = parseFloat(costValue.val().replace(/\./g, '').replace(',', '.'));
                                if (costInputValue > priceInputValue) {
                                    $('#divCostGreaterPrice').removeClass('hidden');
                                    $('#msgCostGreaterPrice').text('Você não está lucrando com esses valores!');
                                } else {
                                    $('#divCostGreaterPrice').addClass('hidden');
                                }
                                if (costInputValue > 0) {
                                    let result = Math.floor((priceInputValue - costInputValue) / costInputValue * 100);
                                    divProfit[0].value = result + '%';
                                }
                            });

                            $(costValue).blur(function(e) {
                                let priceInputValue = parseFloat(priceValue.val().replace(/\./g, '').replace(',', '.'));
                                let costInputValue = parseFloat(costValue.val().replace(/\./g, '').replace(',', '.'));
                                if (costInputValue > priceInputValue) {
                                    $('#divCostGreaterPrice').removeClass('hidden');
                                    $('#msgCostGreaterPrice').text('Você não está lucrando com esses valores!');
                                } else {
                                    $('#divCostGreaterPrice').addClass('hidden');
                                }
                                if (costInputValue > 0) {
                                    let result = Math.floor((priceInputValue - costInputValue) / costInputValue * 100);
                                    divProfit[0].value = result + '%';
                                }
                            });
                        </script>

                    </form>

                    {{-- Coluna direita --}}
                    <div class="md:w-[30%]">

                        {{-- Propriedades --}}
                        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col mb-4">
                            <div class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center">
                                    <i class="fa-solid fa-list-ul text-sm"></i>
                                </div>
                                Propriedades
                            </div>
                            <div class="flex flex-col gap-3">
                                <div class="w-full">
                                    <x-input-label for="gender" :value="__('Gênero')" />
                                    <select id="gender" name="gender"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                        <option value="masculine" @selected($product->gender == 'masculine')>Masculino</option>
                                        <option value="feminine" @selected($product->gender == 'feminine')>Feminino</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="size" :value="__('Tamanho')" />
                                    <select id="size" name="size"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                        <option value="P" @selected($product->size == 'P')>P</option>
                                        <option value="M" @selected($product->size == 'M')>M</option>
                                        <option value="G" @selected($product->size == 'G')>G</option>
                                        <option value="GG" @selected($product->size == 'GG')>GG</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('size')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="color" :value="__('Cor')" />
                                    <x-text-input id="color" placeholder="Ex: Preto" name="color" type="text"
                                        value="{{ $product->color }}"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('color')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="stock" :value="__('Estoque')" />
                                    <x-text-input id="stock" placeholder="Ex: 10" name="stock" type="number"
                                        value="{{ $product->stock }}"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                                </div>
                            </div>
                        </div>

                        {{-- Dimensões --}}
                        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col mb-4">
                            <div class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <i class="fa-solid fa-ruler-combined text-sm"></i>
                                </div>
                                Dimensões
                            </div>
                            <div class="flex flex-col gap-3">
                                <div class="w-full">
                                    <x-input-label for="weight" :value="__('Peso (kg)')" />
                                    <x-text-input id="weight" placeholder="Ex: 0.300" name="weight" type="number"
                                        step="0.001" value="{{ $product->weight }}"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                                </div>

                                <div class="grid grid-cols-3 gap-2 w-full">
                                    <div class="w-full">
                                        <x-input-label for="width" :value="__('Largura')" />
                                        <x-text-input id="width" placeholder="cm" name="width" type="number"
                                            value="{{ $product->width }}"
                                            class="border-gray-300 pt-1.5 px-2 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    </div>
                                    <div class="w-full">
                                        <x-input-label for="height" :value="__('Altura')" />
                                        <x-text-input id="height" placeholder="cm" name="height" type="number"
                                            value="{{ $product->height }}"
                                            class="border-gray-300 pt-1.5 px-2 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    </div>
                                    <div class="w-full">
                                        <x-input-label for="length" :value="__('Cpmto.')" />
                                        <x-text-input id="length" placeholder="cm" name="length" type="number"
                                            value="{{ $product->length }}"
                                            class="border-gray-300 pt-1.5 px-2 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pagamento --}}
                        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col mb-4">
                            <div class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center">
                                    <i class="fa-solid fa-credit-card text-sm"></i>
                                </div>
                                Pagamento
                            </div>
                            <div class="flex flex-col gap-3">
                                <div class="w-full">
                                    <x-input-label for="installments" :value="__('Parcelamento em até')" />
                                    <select id="installments" name="installments"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                        <option value="1" @selected($product->installments == 1)>1x (À vista)</option>
                                        <option value="2" @selected($product->installments == 2)>2x sem juros</option>
                                        <option value="3" @selected($product->installments == 3)>3x sem juros</option>
                                        <option value="4" @selected($product->installments == 4)>4x sem juros</option>
                                        <option value="5" @selected($product->installments == 5)>5x sem juros</option>
                                        <option value="6" @selected($product->installments == 6)>6x sem juros</option>
                                        <option value="10" @selected($product->installments == 10)>10x sem juros</option>
                                        <option value="12" @selected($product->installments == 12)>12x sem juros</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('installments')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="discount_pix" :value="__('Desconto no PIX (%)')" />
                                    <x-text-input id="discount_pix" placeholder="Ex: 5" name="discount_pix"
                                        type="number" value="{{ $product->discount_pix }}"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('discount_pix')" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Painel de Variantes (Cor + Tamanho) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800">Variantes do produto</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Defina combinações de cor e tamanho com estoque individual.</p>
                        </div>
                        <button type="button" onclick="document.getElementById('addVariantForm').classList.toggle('hidden')"
                            class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-2 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Adicionar variante
                        </button>
                    </div>

                    {{-- Form nova variante --}}
                    <div id="addVariantForm" class="hidden bg-gray-50 rounded-xl p-4 mb-4 space-y-3">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Cor</label>
                                <input type="text" id="vColor" placeholder="Ex: Vermelho" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Hex da cor</label>
                                <div class="flex gap-2 items-center">
                                    <input type="color" id="vColorHex" value="#3b82f6" class="w-10 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                                    <input type="text" id="vColorHexText" value="#3b82f6" placeholder="#000000" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Tamanho</label>
                                <input type="text" id="vSize" placeholder="Ex: M, G, 42" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Estoque <span class="text-red-500">*</span></label>
                                <input type="number" id="vStock" placeholder="0" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Preço diferenciado (opcional)</label>
                                <input type="text" id="vPrice" placeholder="Deixe vazio para usar o preço base" class="price-mask w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button type="button" onclick="document.getElementById('addVariantForm').classList.add('hidden')"
                                class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 rounded-xl border border-gray-200 transition">Cancelar</button>
                            <button type="button" id="btnSaveVariant"
                                class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl transition">Salvar variante</button>
                        </div>
                    </div>

                    {{-- Lista de variantes --}}
                    <div id="variantsList" class="space-y-2">
                        <p class="text-xs text-gray-400 text-center py-4" id="noVariantsMsg">Nenhuma variante cadastrada.</p>
                    </div>
                </div>

                {{-- Botões de ação (fixo mobile, estático desktop) --}}
                <div
                    class="fixed bottom-0 md:rounded-2xl left-0 w-full z-20 bg-white border-t border-gray-200 p-3 md:static md:bg-transparent md:border-none md:p-0 flex md:justify-end md:mb-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:shadow-none">
                    <div class="flex w-full md:w-[280px] justify-between gap-3 md:py-1 md:px-3 bg-white rounded-xl">
                        <x-secondary-button id="" class="w-full justify-center md:w-auto">
                            <a href="{{ route('products.index') }}" class="w-full text-center">
                                {{ __('Cancelar') }}
                            </a>
                        </x-secondary-button>

                        <x-primary-button id="btnUpdateDataProduct"
                            class="w-full justify-center md:w-auto">{{ __('Salvar') }}</x-primary-button>
                    </div>
                </div>

            </div>
        </div>
    @endsection

</x-app-layout>


<!-- Fundo Opaco -->
<div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

<script>
// ---- Variantes ----
const PRODUCT_ID_EDIT = {{ $product->id }};

function loadVariants() {
    $.get(`/products/${PRODUCT_ID_EDIT}/variants`, function(data) {
        const list = $('#variantsList');
        const msg  = $('#noVariantsMsg');
        list.find('.variant-row').remove();
        if (!data.length) { msg.show(); return; }
        msg.hide();
        data.forEach(v => {
            const colorDot = v.color_hex ? `<span class="w-4 h-4 rounded-full inline-block border border-gray-200 flex-shrink-0" style="background:${v.color_hex}"></span>` : '';
            list.append(`
                <div class="variant-row flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                    ${colorDot}
                    <div class="flex-1 flex flex-wrap gap-x-4 gap-y-1 text-sm">
                        ${v.color ? `<span class="font-semibold text-gray-700">${v.color}</span>` : ''}
                        ${v.size  ? `<span class="text-gray-500">Tam: <strong>${v.size}</strong></span>` : ''}
                        <span class="text-gray-500">Estoque: <strong>${v.stock}</strong></span>
                        ${v.price_override ? `<span class="text-blue-600 font-semibold">R$ ${parseFloat(v.price_override).toLocaleString('pt-BR',{minimumFractionDigits:2})}</span>` : ''}
                    </div>
                    <button type="button" onclick="deleteVariant(${v.id})" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-300 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>`);
        });
    });
}

function deleteVariant(id) {
    if (!confirm('Remover esta variante?')) return;
    $.ajax({ url: `/products/variants/${id}`, type: 'DELETE', success: loadVariants });
}

// Sync color picker <-> text input
$('#vColorHex').on('input', function() { $('#vColorHexText').val(this.value); });
$('#vColorHexText').on('input', function() { if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) $('#vColorHex').val(this.value); });

$('#btnSaveVariant').click(function() {
    const stock = parseInt($('#vStock').val());
    if (isNaN(stock) || stock < 0) { alert('Informe o estoque.'); return; }
    const priceRaw = $('#vPrice').val().replace(/\./g,'').replace(',','.');
    $.ajax({
        url: `/products/${PRODUCT_ID_EDIT}/variants`,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            color:          $('#vColor').val() || null,
            color_hex:      $('#vColorHexText').val() || null,
            size:           $('#vSize').val() || null,
            stock:          stock,
            price_override: priceRaw ? parseFloat(priceRaw) : null,
        }),
        success: function() {
            $('#vColor').val(''); $('#vSize').val(''); $('#vStock').val(''); $('#vPrice').val('');
            document.getElementById('addVariantForm').classList.add('hidden');
            loadVariants();
        }
    });
});

$(document).ready(function() { loadVariants(); });
</script>

<!-- Slider Photos of Product -->
<div id="images-product-slider"
    class="fixed top-0 right-0 h-full w-[390px] bg-white text-black shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">

    <div class="header px-4 py-1">
        <button id="close-images-product-slider"
            class="mt-4 px-2 py-2 bg-white rounded-lg border-1 hover:border-gray-500 text-white hover:bg-gray-500">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                <path
                    d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z" />
            </svg>
        </button>
    </div>
    <div class="p-4">
        <h3 class="text-3xl font-bold">Fotos</h3>
        <p class="mb-0 text-sm font-semibold text-gray-500">Selecione e ordene as fotos do produto:</p>
        <div class="flex gap-1 items-center mb-2">
            <svg class="w-4 h-4 text-blue-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <p class="text-sm font-semibold text-blue-700">Máximo de 8 fotos.</p>
        </div>

        <div id="info-max-images" style="display: none"
            class="bg-yellow-50 p-2 rounded-lg mb-2 text-center border-1 border-yellow-300 text-sm font-semibold text-yellow-700">
        </div>
        <div class="gallery-slide overflow-y-auto md:h-[600px]">
            <form id="image-upload-form">
                <div id="previewImages" class="justify-between grid grid-cols-2 flex-wrap space-x-1 md:mb-44 mt-4">
                </div>
            </form>
        </div>
    </div>

    <div class="fixed bottom-0 w-full px-4 py-2">
        <label for="label-for-images-product" class="label-for-images-product-edit w-full cursor-pointer">
            <div
                class="OpenModalImagesProducts bg-blue-50 w-full h-14 cursor-pointer rounded-lg border-dashed border-2 border-sky-500 flex flex-col px-1">
                <div class="h-full flex justify-center gap-2 items-center">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path
                            d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                    </svg>
                    <div class="font-semibold">Adicionar foto</div>
                </div>
            </div>
        </label>
        <input type="file" id="label-for-images-product" name="product-images[]" multiple class="hidden">
    </div>
</div>

<style>
    .gallery-slide::-webkit-scrollbar {
        display: none;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    let previewsImagesInProductPage = document.querySelectorAll('.previewsImagesInProductPage');
    let deleteFiles = [];
    let imagesArray = {!! json_encode($images) !!};
    let newsFiles = [];
    let currentFiles = [];
    let labelForImagesProductEdit = $('.label-for-images-product-edit');

    const notificationSlider = document.getElementById("images-product-slider");
    const closeButton = document.getElementById("close-images-product-slider");
    const backdrop = document.getElementById("backdrop");
    const inputImagesProduct = document.getElementById("label-for-images-product");

    $('.OpenModalImagesProducts').click(function(e) {
        e.preventDefault();

        $('#previewImages').empty();

        notificationSlider.classList.remove("translate-x-full");
        notificationSlider.classList.add("translate-x-0");
        backdrop.classList.remove("hidden");

        if (imagesArray.length < 8) {
            inputImagesProduct.click();
        }

        if (imagesArray.length == 8) {
            labelForImagesProductEdit[0].style.display = 'none';
        }

        imagesArray.forEach(image => {
            $('#previewImages').append(`
                <div class="rounded-md relative h-32 static mb-2" data-divimageid="${image.index}">
                    <div class="absolute">
                        <button type="button" data-imageid="${image.id}" class="btn-remove-image-product text-xs bg-gray-200 text-white px-2 py-1 rounded-md mt-2 ml-2">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0 -12-12h-24a12 12 0 0 0 -12 12v216a12 12 0 0 0 12 12zM432 80h-82.4l-34-56.7A48 48 0 0 0 274.4 0H173.6a48 48 0 0 0 -41.2 23.3L98.4 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0 -16-16zM171.8 50.9A6 6 0 0 1 177 48h94a6 6 0 0 1 5.2 2.9L293.6 80H154.4zM368 464H80V128h288zm-212-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0 -12-12h-24a12 12 0 0 0 -12 12v216a12 12 0 0 0 12 12z"/></svg>
                        </button>
                    </div>
                    <img src="/storage/${image.url.replace('public/', '')}" class="w-full h-32 shadow-md rounded-md mb-2 object-cover">
                </div>
            `);
        });
    });


    $(document).ready(function() {
        let fourTextImageProductAfter = $('#fourTextImageProductAfter');
        let fourTextImageProductBefore = $('#fourTextImageProductBefore');
        let quantityImagesProductAvailable = $('#quantityImagesProductAvailable');

        if (imagesArray.length == 4) fourTextImageProductAfter[0].style.display = "none";
        if (imagesArray.length > 4) {
            fourTextImageProductAfter[0].style.display = "none";
            fourTextImageProductBefore[0].style.display = "block";
            quantityImagesProductAvailable[0].innerText = imagesArray.length - 4;
        }
        if (imagesArray[0]) {
            previewsImagesInProductPage[0].src = '/storage/' + imagesArray[0].url.replace('public/', '');
            previewsImagesInProductPage[0].style.display = "block";
        }
        if (imagesArray[1]) {
            previewsImagesInProductPage[1].src = '/storage/' + imagesArray[1].url.replace('public/', '');
            previewsImagesInProductPage[1].style.display = "block";
        }
        if (imagesArray[2]) {
            previewsImagesInProductPage[2].src = '/storage/' + imagesArray[2].url.replace('public/', '');
            previewsImagesInProductPage[2].style.display = "block";
        }
        if (imagesArray[3]) {
            previewsImagesInProductPage[3].src = '/storage/' + imagesArray[3].url.replace('public/', '');
            previewsImagesInProductPage[3].style.display = "block";
        }
        $(notificationSlider).removeClass("translate-x-0").addClass("translate-x-full");
        backdrop.classList.add("hidden");
    });


    const closeSlider = () => {
        let fourTextImageProductAfter = $('#fourTextImageProductAfter');
        let fourTextImageProductBefore = $('#fourTextImageProductBefore');
        let quantityImagesProductAvailable = $('#quantityImagesProductAvailable');

        if (imagesArray.length == 4) fourTextImageProductAfter[0].style.display = "none";
        if (imagesArray.length > 4) {
            fourTextImageProductAfter[0].style.display = "none";
            fourTextImageProductBefore[0].style.display = "block";
            quantityImagesProductAvailable[0].innerText = imagesArray.length - 4;
        } else {
            fourTextImageProductBefore[0].style.display = "none";
        }

        if (imagesArray[0]) {
            previewsImagesInProductPage[0].src = '/storage/' + imagesArray[0].url.replace('public/', '');
            previewsImagesInProductPage[0].style.display = "block";
        } else {
            previewsImagesInProductPage[0].style.display = "none";
        }

        if (imagesArray[1]) {
            previewsImagesInProductPage[1].src = '/storage/' + imagesArray[1].url.replace('public/', '');
            previewsImagesInProductPage[1].style.display = "block";
        }
        if (imagesArray[2]) {
            previewsImagesInProductPage[2].src = '/storage/' + imagesArray[2].url.replace('public/', '');
            previewsImagesInProductPage[2].style.display = "block";
        } else {
            previewsImagesInProductPage[2].style.display = "none";
        }
        if (imagesArray[3]) {
            previewsImagesInProductPage[3].src = '/storage/' + imagesArray[3].url.replace('public/', '');
            previewsImagesInProductPage[3].style.display = "block";
        } else {
            previewsImagesInProductPage[3].style.display = "none";
            fourTextImageProductAfter[0].style.display = "flex";
        }

        $(notificationSlider).removeClass("translate-x-0").addClass("translate-x-full");
        backdrop.classList.add("hidden");
    };

    $(closeButton).click(function(e) {
        e.preventDefault();
        closeSlider();
    });
    $(backdrop).click(function(e) {
        e.preventDefault();
        closeSlider();
    });


    $(inputImagesProduct).change(function(e) {
        const files = e.target.files;
        const remainingSlots = 8 - imagesArray.length;
        const filesToProcess = Math.min(files.length, remainingSlots);

        for (let i = 0; i < filesToProcess; i++) {
            const file = files[i];
            const reader = new FileReader();

            let fileFormatted = {
                id: null,
                product_id: null,
                url: null,
                index: imagesArray.length,
                indexNewFile: newsFiles.length
            };

            reader.onload = function(e) {
                file.src = e.target.result;
                $('#previewImages').append(`
                    <div class="rounded-md h-32 relative static mb-2" data-indexnewfile="${fileFormatted.indexNewFile}" data-divimageid="${fileFormatted.index}">
                        <div class="absolute">
                            <button type="button" class="btn-remove-image-product text-xs bg-gray-200 text-white px-2 py-1 rounded-md mt-2 ml-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0 -12-12h-24a12 12 0 0 0 -12 12v216a12 12 0 0 0 12 12zM432 80h-82.4l-34-56.7A48 48 0 0 0 274.4 0H173.6a48 48 0 0 0 -41.2 23.3L98.4 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0 -16-16zM171.8 50.9A6 6 0 0 1 177 48h94a6 6 0 0 1 5.2 2.9L293.6 80H154.4zM368 464H80V128h288zm-212-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0 -12-12h-24a12 12 0 0 0 -12 12v216a12 12 0 0 0 12 12z"/></svg>
                            </button>
                        </div>
                        <img src="${e.target.result}" class="w-full object-cover h-32 shadow-md rounded-md mb-2">
                    </div>
                `);
                fileFormatted.url = e.target.result;
            }

            imagesArray.push(fileFormatted);
            if (imagesArray.length == 8) labelForImagesProductEdit[0].style.display = 'none';
            newsFiles.push(file);
            reader.readAsDataURL(file);
        }

        if (files.length > remainingSlots) {
            let infoMaxImages = $('#info-max-images');
            infoMaxImages[0].innerText =
                `Você tentou carregar ${files.length} imagens, mas só ${remainingSlots} foram adicionadas. O limite máximo é de 8 imagens.`;
            infoMaxImages[0].style.display = 'block';
            labelForImagesProductEdit[0].style.display = 'none';
        }
    });


    $('#previewImages').on('click', '.btn-remove-image-product', function(e) {
        e.preventDefault();
        const imageId = $(this).data('imageid');
        if (imageId == undefined) {
            const divImageId = $(this).parent().parent().data('divimageid');
            imagesArray = imagesArray.filter(image => image.index !== divImageId);
            const indexNewFile = $(this).parent().parent().data('indexnewfile');
            newsFiles.splice(indexNewFile, 1);
        } else {
            imagesArray = imagesArray.filter(image => image.id !== imageId);
        }
        if (imagesArray.length < 8) {
            $('#info-max-images')[0].style.display = 'none';
            labelForImagesProductEdit[0].style.display = 'block';
        }
        deleteFiles.push(imageId);
        $(this).parent().parent().remove();
    });


    const previewImages = document.getElementById("previewImages");
    const sortable = new Sortable(previewImages, {
        animation: 150,
        ghostClass: 'bg-gray-300',
        onSort: function() {
            const newOrder = [];
            $('#previewImages > div').each(function() {
                const divImageId = $(this).data('divimageid');
                const imageData = imagesArray.find(image => image.index === divImageId);
                if (imageData) newOrder.push(imageData);
            });
            imagesArray = newOrder;
        },
    });

    function clearBordersInputs() {
        document.querySelectorAll('.required').forEach(input => {
            input.style.border = '1px solid #e5e7eb';
        });
    }


    $('#btnUpdateDataProduct').click(function(e) {
        e.preventDefault();
        clearBordersInputs();

        currentFiles = [];
        imagesArray.forEach((image) => {
            currentFiles.push(image.id);
        });

        const formData = new FormData();
        formData.append('brand_id', $('#brand_id').val());
        formData.append('category_id', $('#category_id').val());
        formData.append('gender', $('#gender').val());
        formData.append('size', $('#size').val());
        formData.append('name', $('#name').val());
        formData.append('description', $('#description').val());
        formData.append('price', $('#price').val());
        formData.append('price_wholesale', $('#price_wholesale').val());
        formData.append('price_promotional', $('#price_promotional').val());
        formData.append('cost', $('#cost').val());
        formData.append('profit', $('#profit').val().replace('%', ''));
        formData.append('color', $('#color').val());
        formData.append('gender', $('#gender').val());
        formData.append('size', $('#size').val());
        formData.append('stock', $('#stock').val());
        formData.append('installments', $('#installments').val());
        formData.append('discount_pix', $('#discount_pix').val());
        formData.append('weight', $('#weight').val());
        formData.append('width', $('#width').val());
        formData.append('height', $('#height').val());
        formData.append('length', $('#length').val());

        currentFiles.forEach(currentFile => {
            formData.append('current-images[]', currentFile);
        });
        newsFiles.forEach(newFile => {
            formData.append('news-images[]', newFile);
        });
        deleteFiles.forEach(deleteFile => {
            formData.append('delete-images[]', deleteFile);
        });

        $.ajax({
            url: '{{ route('products.update', $product->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                window.location.href = '{{ route('products.edit', $product->id) }}';
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $(".error-message").remove();
                    $.each(errors, function(field, messages) {
                        let input = $(`[name="${field}"]`);
                        if (input.length > 0) {
                            input.css('border', '1px solid red');
                            if (input.parent().hasClass('relative')) {
                                input.parent().after(
                                    `<span class="error-message" style="color: red; font-size: 12px;">${messages[0]}</span>`
                                );
                            } else {
                                input.after(
                                    `<span class="error-message" style="color: red; font-size: 12px;">${messages[0]}</span>`
                                );
                            }
                        }
                    });
                }
                toastr.error(xhr.responseJSON.message);
            }
        });
    });
</script>

<style>
    .gallery-slide::-webkit-scrollbar {
        display: none;
    }

    .fade-in {
        animation: fadeIn ease 0.5s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-100%);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-100%);
        }
    }

    .custom-toast {
        animation: slideIn 0.5s ease-out, slideOut 0.5s ease-in 3.5s;
        will-change: transform, opacity;
    }
</style>
