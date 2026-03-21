<x-app-layout>
    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <div class="flex md:justify-center pb-24 md:pb-0">

            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

                <h2 class="font-semibold ml-1 text-2xl mb-3 mt-3 text-gray-800">
                    {{ __('Novo Produto') }}
                </h2>

                <div class="p-1 mt-3 md:flex gap-2">

                    <form class="md:w-[70%]" action="{{ route('products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                            <div class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fa-solid fa-box text-blue-600 mr-2"></i> Dados do produto
                            </div>
                            <div class="md:flex mb-0 gap-3">
                                <div class="w-full mb-3">
                                    <x-input-label for="name" :value="__('Categoria *')" />
                                    <div class="w-full ">
                                        <select id="model_id"
                                            class="select-models border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full">
                                            @foreach ($categoriesByPartner as $storeCategory)
                                                <option value="{{ $storeCategory->category->id }}">
                                                    {{ $storeCategory->category->name }}</option>
                                            @endforeach
                                        </select>

                                        <script>
                                            new TomSelect(".select-models", {
                                                create: true,
                                                sortField: {
                                                    field: "text",
                                                    direction: "asc"
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="w-full mb-3">
                                    <x-input-label for="name" :value="__('Marca *')" />
                                    <div class="w-full ">
                                        <select id="brand_id"
                                            class="select-brands border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full">
                                            @foreach ($brandsByPartner as $brandByPartner)
                                                <option value="{{ $brandByPartner->id }}">{{ $brandByPartner->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <script>
                                            new TomSelect(".select-brands", {
                                                create: true,
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
                                        type="text" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                            </div>

                            <div class="md:flex mb-0">
                                <div class="w-full mb-3">
                                    <x-input-label for="description" :value="__('Sobre este produto *')" />
                                    <x-textarea id="description" class="required" placeholder="Descreva algo..."
                                        name="description" type="text" autofocus autocomplete="name"></x-textarea>
                                </div>
                            </div>

                        </div>

                        <div class="rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                            <div class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fa-solid fa-camera text-blue-600 mr-2"></i> Fotos
                                <div class="flex gap-1">
                                    <svg class="w-4 h-4 text-blue-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="text-blue-700 text-xs">As fotos aparecem no seu catálogo.</span>
                                </div>
                            </div>

                            <div class="py-1 md:flex md:flex-row items-center gap-1 space-y-1 md:space-y-0">
                                <div class="grid grid-cols-2 gap-1 md:w-1/2">
                                    <label for="" class="w-full">
                                        <div
                                            class="OpenModalImagesProducts relative bg-blue-50 w-full h-32 cursor-pointer rounded-lg border-dashed border-2 border-sky-500 flex flex-col px-0">
                                            <div class="font-semibold text-xs mt-1 flex z-10 ml-2">
                                                <div
                                                    class="bg-gray-200 border-1 pb-[2px] border-gray-500 flex items-center px-1 rounded-full gap-1">
                                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                        <path
                                                            d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
                                                    </svg>
                                                    principal
                                                </div>
                                            </div>
                                            <div class="h-2/3 flex flex-col justify-center items-center">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path
                                                        d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                                </svg>
                                                <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                    Adicione a melhor foto do seu produto
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
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                <path
                                                    d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                            </svg>
                                            <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                Mostre a qualidade do seu produto
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
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
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
                                                    viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path
                                                        d="M352 240v32c0 6.6-5.4 12-12 12h-88v88c0 6.6-5.4 12-12 12h-32c-6.6 0-12-5.4-12-12v-88h-88c-6.6 0-12-5.4-12-12v-32c0-6.6 5.4-12 12-12h88v-88c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v88h88c6.6 0 12 5.4 12 12zm96-160v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z" />
                                                </svg>
                                                <div class="leading-3 mt-2 text-center text-xs font-semibold px-2">
                                                    Mostre a fotos nítidas
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

                        <div class="rounded-xl shadow-md pt-3 mb-4 flex flex-col pb-3 bg-white px-3">
                            <div class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fa-solid fa-tag text-blue-600 mr-2"></i> Preços
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
                                    <div class="w-1/2">
                                        <x-input-label for="price" :value="__('Preço de venda *')" />
                                        <x-text-input id="price" placeholder="R$ 0.00" name="price" type="text"
                                            class="price-mask required" />
                                    </div>

                                    <div class="w-1/2">
                                        <x-input-label for="price_promotional" :value="__('Preço promocional')" />
                                        <x-text-input id="price_promotional" placeholder="R$ 0.00"
                                            name="price_promotional" type="text" class="price-mask" autofocus
                                            autocomplete="name" />
                                        <x-input-error class="mt-2" :messages="$errors->get('price_promotional')" />
                                    </div>

                                </div>

                                <div class="w-full flex gap-2">
                                    <div class="w-1/2">
                                        <x-input-label for="cost" :value="__('Custo')" />
                                        <x-text-input id="cost" placeholder="R$ 0.00" name="cost" type="text"
                                            class="price-mask" autofocus autocomplete="name" />
                                        {{-- <x-input-error class="mt-2" :messages="$errors->get('cost')" /> --}}
                                    </div>

                                    <div class="w-1/2">
                                        <x-input-label for="profit" :value="__('Margem de lucro')" />
                                        <x-text-input disabled id="profit" placeholder="--" name="profit"
                                            type="text" class="bg-gray-200" autofocus autocomplete="name" />
                                        {{-- <x-input-error class="mt-2" :messages="$errors->get('profit')" /> --}}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <script>
                            let priceValue = $('#price');
                            let costValue = $('#cost');
                            let profit = $('#profit');

                            $(priceValue).blur(function(e) {
                                let priceInputValue = parseFloat(priceValue.val().replace(/\./g, '').replace(',', '.'));
                                let costInputValue = parseFloat(costValue.val().replace(/\./g, '').replace(',', '.'));

                                if (costInputValue > priceInputValue) {
                                    console.log('Não está lucrando')
                                    $('#divCostGreaterPrice').removeClass('hidden');
                                    $('#msgCostGreaterPrice').text('Você não está lucrando com esses valores!')
                                } else {
                                    $('#divCostGreaterPrice').addClass('hidden');
                                }

                                if (costInputValue > 0) {
                                    // Calcula o resultado e arredonda para o menor inteiro
                                    let result = Math.floor((priceInputValue - costInputValue) / costInputValue * 100);
                                    profit[0].value = result + '%';
                                }
                            });

                            $(costValue).blur(function(e) {
                                let priceInputValue = parseFloat(priceValue.val().replace(/\./g, '').replace(',', '.'));
                                let costInputValue = parseFloat(costValue.val().replace(/\./g, '').replace(',', '.'));

                                if (costInputValue > priceInputValue) {
                                    $('#divCostGreaterPrice').removeClass('hidden');
                                    $('#msgCostGreaterPrice').text('Você não está lucrando com esses valores!')
                                } else {
                                    $('#divCostGreaterPrice').addClass('hidden');
                                }

                                if (costInputValue > 0) {
                                    // Calcula o resultado e arredonda para o menor inteiro
                                    let result = Math.floor((priceInputValue - costInputValue) / costInputValue * 100);
                                    profit[0].value = result + '%';
                                }
                            });
                        </script>

                    </form>

                    <div class="md:w-[30%]">
                        <div
                            class="text-lg h-[31.5%] text-gray-800 rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                            <div class="mb-3 font-semibold flex items-center">
                                <i class="fa-solid fa-list-ul text-blue-600 mr-2"></i> Propriedades
                            </div>
                            <div class="flex flex-col gap-3">
                                <div class="w-full">
                                    <x-input-label for="gender" :value="__('Genêro')" />
                                    <select id="gender" placeholder="Não" name="gender" type="text"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                        <option value="masculine">Masculino</option>
                                        <option value="feminine">Feminino</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="size" :value="__('Tamanho')" />
                                    <select id="size" placeholder="Não" name="size" type="text"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                        <option value="P">P</option>
                                        <option value="M">M</option>
                                        <option value="G">G</option>
                                        <option value="GG">GG</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('size')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="color" :value="__('Cor')" />
                                    <x-text-input id="color" placeholder="Ex: Preto" name="color" type="text"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('color')" />
                                </div>

                            </div>

                        </div>

                        <div class="text-lg text-gray-800 rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                            <div class="mb-3 font-semibold flex items-center">
                                <i class="fa-solid fa-ruler-combined text-blue-600 mr-2"></i> Dimensões
                            </div>
                            <div class="flex flex-col gap-3 pb-3">
                                <div class="w-full">
                                    <x-input-label for="weight" :value="__('Peso (kg)')" />
                                    <x-text-input id="weight" placeholder="Ex: 0.300" name="weight" type="number"
                                        step="0.001"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                                </div>

                                <div class="grid grid-cols-3 gap-2 w-full">
                                    <div class="w-full">
                                        <x-input-label for="width" :value="__('Largura')" />
                                        <x-text-input id="width" placeholder="cm" name="width" type="number"
                                            class="border-gray-300 pt-1.5 px-2 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    </div>
                                    <div class="w-full">
                                        <x-input-label for="height" :value="__('Altura')" />
                                        <x-text-input id="height" placeholder="cm" name="height" type="number"
                                            class="border-gray-300 pt-1.5 px-2 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    </div>
                                    <div class="w-full">
                                        <x-input-label for="length" :value="__('Cpmto.')" />
                                        <x-text-input id="length" placeholder="cm" name="length" type="number"
                                            class="border-gray-300 pt-1.5 px-2 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-lg text-gray-800 rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                            <div class="mb-3 font-semibold flex items-center">
                                <i class="fa-solid fa-credit-card text-blue-600 mr-2"></i> Pagamento
                            </div>
                            <div class="flex flex-col gap-3 pb-3">
                                <div class="w-full">
                                    <x-input-label for="installments" :value="__('Parcelamento em até')" />
                                    <select id="installments" name="installments"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                        <option value="1">1x (À vista)</option>
                                        <option value="2">2x sem juros</option>
                                        <option value="3">3x sem juros</option>
                                        <option value="4">4x sem juros</option>
                                        <option value="5">5x sem juros</option>
                                        <option value="6">6x sem juros</option>
                                        <option value="10">10x sem juros</option>
                                        <option value="12">12x sem juros</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('installments')" />
                                </div>

                                <div class="w-full">
                                    <x-input-label for="discount_pix" :value="__('Desconto no PIX (%)')" />
                                    <x-text-input id="discount_pix" placeholder="Ex: 5" name="discount_pix"
                                        type="number"
                                        class="border-gray-300 pt-1.5 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('discount_pix')" />
                                </div>

                            </div>

                        </div>


                    </div>

                </div>
                <div
                    class="fixed bottom-0 md:rounded-2xl left-0 w-full z-40 bg-white border-t border-gray-200 p-4 md:static md:bg-transparent md:border-none md:p-0 flex md:justify-end md:mb-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:shadow-none">
                    <div class="flex w-full md:w-[280px] justify-between gap-3 md:py-1 md:px-3 bg-white rounded-xl">
                        <x-secondary-button id="" class="w-full justify-center md:w-auto">
                            <a href="{{ route('products.index') }}" class="w-full text-center">
                                {{ __('Cancelar') }}
                            </a>
                        </x-secondary-button>

                        <x-primary-button id="btnSaveDataProduct"
                            class="w-full justify-center md:w-auto">{{ __('Salvar') }}</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>




<!-- Fundo Opaco -->
<div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40">
</div>
<!-- Slider Photos of Product -->
<div id="images-product-slider"
    class="fixed top-0 right-0 h-full w-[390px] bg-white text-black shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50">

    <div class="header px-4 py-1">
        <!-- Botão Fechar -->
        <button id="close-images-product-slider"
            class="mt-4 px-2 py-2 bg-white rounded-lg border-1 hover:border-gray-500 text-white hover:bg-gray-500">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 256 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path
                    d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z" />
            </svg>
        </button>
    </div>
    <div class="p-4">
        <h3 class="text-3xl font-bold">Fotos</h3>
        <p class="mb-0 text-sm font-semibold text-gray-500">Selecione e orderne as fotos do produto:</p>
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
            <!-- fORMULÁRIO DE UPLOAD -->
            <form id="image-upload-form">

                <!-- PREVIEW DAS IMAGENS -->
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
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    let previewsImagesInProductPage = $('.previewsImagesInProductPage')
    let labelForImagesProductEdit = $('.label-for-images-product-edit');

    const notificationSlider = document.getElementById("images-product-slider");
    const closeButton = document.getElementById("close-images-product-slider");
    const backdrop = document.getElementById("backdrop");
    const inputImagesProduct = document.getElementById("label-for-images-product");

    $('.OpenModalImagesProducts').click(function(e) {
        e.preventDefault();
        notificationSlider.classList.remove("translate-x-full");
        notificationSlider.classList.add("translate-x-0");
        backdrop.classList.remove("hidden");

        inputImagesProduct.click();
    });

    // Fechar o slider e o fundo opaco
    const closeSlider = () => {

        console.log(imagesArray, 'Array')

        let fourTextImageProductAfter = $('#fourTextImageProductAfter');
        let fourTextImageProductBefore = $('#fourTextImageProductBefore');
        let quantityImagesProductAvailable = $('#quantityImagesProductAvailable');

        if (imagesArray.length == 4) {
            fourTextImageProductAfter[0].style.display = "none"
        }

        if (imagesArray.length > 4) {
            console.log('Maio que 4')
            fourTextImageProductAfter[0].style.display = "none"
            fourTextImageProductBefore[0].style.display = "block"
            quantityImagesProductAvailable[0].innerText = imagesArray.length - 4
        }

        if (imagesArray[0]) {
            previewsImagesInProductPage[0].src = imagesArray[0].src
            previewsImagesInProductPage[0].style.display = "block"
        }

        if (imagesArray[1]) {
            previewsImagesInProductPage[1].src = imagesArray[1].src
            previewsImagesInProductPage[1].style.display = "block"
        }

        if (imagesArray[2]) {
            previewsImagesInProductPage[2].src = imagesArray[2].src
            previewsImagesInProductPage[2].style.display = "block"
        }

        if (imagesArray[3]) {
            previewsImagesInProductPage[3].src = imagesArray[3].src
            previewsImagesInProductPage[3].style.display = "block"
        }

        console.log(previewsImagesInProductPage[0], 'teste')

        $(notificationSlider).removeClass("translate-x-0").addClass("translate-x-full");
        backdrop.classList.add("hidden");
    };

    $(closeButton).click(function(e) {
        e.preventDefault();
        closeSlider();
    });

    // Fechar o slider ao clicar fora dele (no backdrop)
    $(backdrop).click(function(e) {
        e.preventDefault();
        closeSlider();
    });


    // UPLOAD DE IMAGENS
    let imagesArray = [];
    let teste = [];
    let previewsOrdem = [];
    $(inputImagesProduct).change(function(e) {
        const files = e.target.files;

        let previewFirstImage = $('#previewFirstImage');
        let previewSecondImage = $('#previewSecondImage');

        // Calcula quantas imagens ainda podem ser adicionadas
        const remainingSlots = 8 - imagesArray.length;
        const filesToProcess = Math.min(files.length, remainingSlots);

        for (let i = 0; i < filesToProcess; i++) {
            const file = files[i];

            const reader = new FileReader();

            reader.onload = function(e) {

                file.src = e.target.result
                file.index = i

                $('#previewImages').append(`
                    <div class="rounded-md h-32 static mb-2" data-name="${file.name}">
                        <div class="absolute" data-index="${file.index}">
                            <button type="button" class="btn-remove-image-product text-xs bg-gray-200 text-white px-2 py-1 rounded-md mt-2 ml-2">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0 -12-12h-24a12 12 0 0 0 -12 12v216a12 12 0 0 0 12 12zM432 80h-82.4l-34-56.7A48 48 0 0 0 274.4 0H173.6a48 48 0 0 0 -41.2 23.3L98.4 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0 -16-16zM171.8 50.9A6 6 0 0 1 177 48h94a6 6 0 0 1 5.2 2.9L293.6 80H154.4zM368 464H80V128h288zm-212-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0 -12-12h-24a12 12 0 0 0 -12 12v216a12 12 0 0 0 12 12z"/></svg>
                            </button>
                        </div>
                        <img src="${e.target.result}" id="image-${i}" class="w-full h-32 object-cover object-contain shadow-md rounded-md mb-2">
                    </div>
                `);

            }

            // Adicione ao array
            imagesArray.push(file);
            if (imagesArray.length == 8) {
                // alert('Você só pode carregar no máximo 8 imagens.');
                labelForImagesProductEdit[0].style.display = 'none';
            }

            reader.readAsDataURL(file);

            // Se o usuário tentar carregar mais imagens do que o permitido, exibe um alerta
            if (files.length > remainingSlots) {
                let infoMaxImages = $('#info-max-images');

                infoMaxImages[0].innerText =
                    `Você tentou carregar ${files.length} imagens, mas só ${remainingSlots} foram adicionadas. O limite máximo é de 8 imagens.`;
                infoMaxImages[0].style.display = 'block';

                let labelForImagesProductEdit = $('.label-for-images-product-edit');
                labelForImagesProductEdit[0].style.display = 'none';
            }
        }


    });


    // REMOVENDO IMAGEN(S)
    $('#previewImages').on('click', '.btn-remove-image-product', function(e) {
        e.preventDefault();
        const imageIndex = $(this).closest('div').data('index');

        // Remova a imagem do array
        imagesArray = imagesArray.filter(image => image.index !== imageIndex);

        if (imagesArray.length < 8) {
            labelForImagesProductEdit[0].style.display = 'block';
        }

        $(this).parent().parent().remove();
    });

    const previewImages = document.getElementById("previewImages");
    const sortable = new Sortable(previewImages, {
        animation: 150,
        ghostClass: 'bg-gray-300', // Classe para o item "fantasma"
        onSort: function() {
            // Atualize o array `imagesArray` com base na nova ordem dos elementos no DOM
            const newOrder = [];

            $('#previewImages > div').each(function(e, index) {
                console.log(index, 'Indice')
                console.log($(this), 'THIS');
                const imageName = $(this).data(
                    'name'); // Pegue o nome da imagem do atributo `data-name`
                const imageData = imagesArray.find(image => image.name ===
                    imageName); // Encontre a imagem correspondente no array
                if (imageData) {
                    newOrder.push(imageData);
                }
            });

            imagesArray = newOrder; // Atualize o array para refletir a nova ordem
            console.log(imagesArray, "imagesArray");
        },
    });

    function clearBordersInputs() {
        let allInputsWithRequiredClass = document.querySelectorAll('.required');
        allInputsWithRequiredClass.forEach(input => {
            input.style.border = '1px solid #e5e7eb';
        });
    }


    $('#btnSaveDataProduct').click(function(e) {
        e.preventDefault();
        clearBordersInputs()

        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('brand_id', $('#brand_id').val());
        formData.append('model_id', $('#model_id').val());
        formData.append('name', $('#name').val());
        formData.append('description', $('#description').val());
        formData.append('price', $('#price').val());
        formData.append('price_promotional', $('#price_promotional').val());
        formData.append('cost', $('#cost').val());
        formData.append('profit', $('#profit').val().replace('%', ''));
        formData.append('year_of_manufacture', $('#year_of_manufacture').val());
        formData.append('fuel', $('#fuel').val());
        formData.append('license_plate', $('#license_plate').val());
        formData.append('miliage', $('#miliage').val());
        formData.append('exchange', $('#exchange').val());
        formData.append('bodywork', $('#bodywork').val());
        formData.append('color', $('#color').val());
        formData.append('accept_exchange', $('#accept_exchange').val());
        formData.append('review_done', $('#review_done').val());
        formData.append('reindeer', $('#reindeer').val());
        formData.append('chassis', $('#chassis').val());
        formData.append('engine', $('#engine').val());
        formData.append('type', $('#type').val());
        formData.append('crlv', $('#crlv')[0].files[0] || null);
        formData.append('dut', $('#dut')[0].files[0] || null);
        formData.append('invoice', $('#purchase_invoice')[0].files[0] || null);
        imagesArray.forEach(image => {
            formData.append('product-images[]', image);
        });

        // Envie a solicitação AJAX
        $.ajax({
            url: '{{ route('products.store') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoader();
                window.location.href = '{{ route('products.index') }}';
            },
            error: function(xhr, status, error) {

                console.log(xhr.status);

                if (xhr.status === 422) { // Código de erro para validação do Laravel
                    let errors = xhr.responseJSON.errors;

                    // Remover mensagens de erro antigas antes de exibir novas
                    $(".error-message").remove();

                    $.each(errors, function(field, messages) {
                        let input = $(`[name="${field}"]`);

                        if (input.length === 0) {
                            console.warn(`Campo não encontrado: ${field}`);
                        }


                        if (input.length > 0) {
                            input.css('border', '1px solid red');

                            // Se o input estiver encapsulado, encontrar o elemento correto para inserir a mensagem
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
                hideLoader();
            }
        });
    })


    $('#btn-add-images').click(function(e) {
        e.preventDefault();
        $('input[type="file"]').click();
    });
</script>

<style>
    /* Efeito de fade-in para o modal */
    .fade-in {
        animation: fadeIn ease 0.5s;
        -webkit-animation: fadeIn ease 0.5s;
        -moz-animation: fadeIn ease 0.5s;
        -o-animation: fadeIn ease 0.5s;
        -ms-animation: fadeIn ease 0.5s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-moz-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-webkit-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-o-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-ms-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }




    /* Animação de entrada */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-100%);
            /* Começa fora da tela (acima) */
        }

        to {
            opacity: 1;
            transform: translateY(0);
            /* Entra no centro */
        }
    }

    /* Animação de saída */
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
            /* Começa no centro */
        }

        to {
            opacity: 0;
            transform: translateY(-100%);
            /* Sai para fora da tela (acima) */
        }
    }

    /* Classe personalizada para o Toast */
    .custom-toast {
        animation: slideIn 0.5s ease-out, slideOut 0.5s ease-in 3.5s;
        /* Entrada e saída */
        will-change: transform, opacity;
        /* Melhor performance */
    }


    .ts-wrapper .option .title {
        display: block;
    }

    .ts-wrapper .option .url {
        font-size: 12px;
        display: block;
        color: #a0a0a0;
    }
</style>
