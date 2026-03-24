<div class="h-[100vh] flex flex-col items-center">
    <!-- Cabeçalho fixo -->
    <div class="h-12 flex items-center py-5 justify-between shadow-sm w-full bg-white top-0 z-10">
        <div class="text-lg flex gap-1 items-center font-bold text-gray-700 ml-5">
            @if ($logoStore != null)
                <div class="bg-slate-200 rounded-full w-8 h-8 flex justify-center items-center"
                    style="border: 4px solid white; overflow: hidden;">
                    <img src="{{ $logoStore }}" class="object-cover object-center w-full h-full" alt="Logo">
                </div>
            @endif
            {{ $partner->store->store_name }}
        </div>

        <div class="mr-5 btn-back-product-page flex items-center gap-2 cursor-pointer">
            <ion-icon name="chevron-back-outline" class="text-black"></ion-icon>
            Voltar
        </div>
    </div>
    <script>
        $('.btn-back-product-page').click(function() {
            window.history.back();
        });
    </script>

    {{-- <div class="flex justify-center"> --}}
    <!-- Container principal -->
    <div class="container h-[100vh] max-w-[1350px]">
        <!-- Section Left (com scroll) -->
        <div class="section-left h-[100vh]">
            <div class="flex flex-col bg-white font-bold w-full">
                {{-- <div class="text-xl break-words uppercase text-blue-700">
                        {{$product->subcategory->name}}
                    </div> --}}
                <div class="w-full text-2xl break-words uppercase text-gray-700">
                    {{ $product->name }}
                </div>
            </div>

            <!-- Carrossel de imagens -->
            <div class="carousel swiper-container w-full relative overflow-hidden">
                <div class="swiper-wrapper">
                    @if ($images->isEmpty())
                        <img src="/img/image-not-found.png" alt="Imagem do produto"
                            class="w-full h-full object-cover object-center" />
                    @else
                        @foreach ($images as $image)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $image->url) }}" alt="Imagem do produto"
                                    class="w-full h-full object-cover object-center" />
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Botões de navegação -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Paginação -->
                <div class="swiper-pagination"></div>
            </div>

            <!-- Demais conteúdos da section-left -->
            @if ($product->color != null)
                <div class="px-4 mt-4">
                    <div class="font-normal">
                        Cor: <span class="font-bold">{{ $product->color }}</span>
                    </div>
                </div>
            @endif

            <div class="px-4 py-4 text-3xl font-bold flex flex-col border-b border-gray-200">
                <div class="items-center ">
                    <span class="text-sm text-gray-700">R$</span>
                    <span class="price-mask text-blue-900">{{ $product->price }}</span>
                </div>
                @if ($product->price < $product->old_price)
                    <div class="space-x-1 flex line-through">
                        <span class="text-sm text-gray-500">R$</span>
                        <span class="text-sm text-gray-500 price-mask">{{ $product->old_price }}</span>
                    </div>
                @endif
            </div>

            <div class="px-4 mt-4 flex flex-col gap-2 mb-7">
                <div class="font-bold text-blue-700 text-sm">
                    Propriedades
                </div>
                {{-- <div class="flex">
                    <div class="w-1/3">
                        <label class="text-sm text-gray-500">Ano</label>
                        <div class="font-bold">{{ $product->properties->year_of_manufacture }}</div>
                    </div>
                    <div class="w-1/3">
                        <label class="text-sm text-gray-500">Combustível</label>
                        <div class="font-bold">{{ $product->properties->fuel }}</div>
                    </div>
                    <div class="w-1/3">
                        <label class="text-sm text-gray-500">Placa final</label>
                        <div class="font-bold">{{ $product->properties->license_plate }}</div>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/3">
                        <label class="text-sm text-gray-500">KM</label>
                        <div class="font-bold">{{ $product->properties->miliage }}</div>
                    </div>
                    <div class="w-1/3">
                        <label class="text-sm text-gray-500">Câmbio</label>
                        <div class="font-bold">{{ $product->properties->exchange }}</div>
                    </div>
                    <div class="w-1/3">
                        <label class="text-sm text-gray-500">Carroceria</label>
                        <div class="font-bold">{{ $product->properties->bodywork }}</div>
                    </div>
                </div> --}}
            </div>

            <div class="p-4 mt-4">
                <div class="font-bold text-blue-700 text-sm">
                    Descrição
                </div>

                <div class="break-words">
                    {{ $product->description }}
                </div>
            </div>
        </div>

        <!-- Section Right (fixa) -->
        <div class="section-right">

            <div class="card-send-desktop bg-white shadow-md p-4 rounded-xl">
                <div class="flex justify-between items-center">
                    <div class="text-lg font-bold text-gray-700">
                        Adicionar ao carrinho
                    </div>
                    {{-- <div class="close-modal-send cursor-pointer">
                            <ion-icon name="close-outline" class="text-2xl text-gray-700"></ion-icon>
                        </div> --}}
                </div>
                <div class="mt-4">
                    <div class="font-bold text-gray-700">
                        Nome *
                    </div>
                    <input type="text" id="inputModalPdpNameDesktop" placeholder="Seu nome"
                        class="w-full border border-gray-300 rounded-lg p-2 mt-1">
                    <span id="spanModalPdpNameDesktop" class="hidden">
                        <div class="gap-1 items-center text-sm text-red-500 flex">
                            <svg class="w-4 h-4 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Campo nome é obrigatório
                        </div>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="font-bold text-gray-700">
                        Telefone *
                    </div>
                    <input id="inputModalPdpPhoneDesktop" type="text" placeholder="Seu telefone"
                        class="phone-mask w-full border border-gray-300 rounded-lg p-2 mt-1">
                    <span id="spanModalPdpPhoneDesktop" class="hidden">
                        <div class="gap-1 items-center text-sm text-red-500 flex">
                            <svg class="w-4 h-4 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Campo telefone é obrigatório
                        </div>
                    </span>
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-500" for="Gostaria de (opcional)">Gostaria de (opcional)</label>
                    <div class="flex gap-3">
                        <div class="mt-1 flex items-center gap-2">
                            <input id="replacement-dektop" type="checkbox" class="rounded input-checkbox">
                            <label for="replacement-desktop">Desejo negociar / Oferecer produto</label>
                        </div>
                        <div class="mt-1 flex items-center gap-2">
                            <input id="finance-desktop" type="checkbox" class="rounded input-checkbox">
                            <label for="finance-desktop">Financiar</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="font-bold text-gray-700">
                        Mensagem *
                    </div>
                    <textarea name="" id="" cols="30" rows="5"
                        class="w-full border border-gray-300 rounded-lg p-2 mt-1">
Olá, tenho interesse no produto {{ $product->name }}. Gostaria de receber mais informações sobre o produto. Poderia entrar em contato?
                        </textarea>
                </div>
                <div class="mt-4">
                    <button id="btnSendSolicitationPdpDesktop"
                        class="bg-blue-700 text-white font-bold px-4 py-2 rounded-lg w-full">Enviar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
</div>

<script>
    $(document).ready(function() {
        // Mostrar o loader quando uma requisição AJAX começar
        $(document).ajaxStart(function() {
            $('#globalLoaderPdp').fadeIn();
        });

        // Esconder o loader quando a requisição AJAX terminar
        $(document).ajaxStop(function() {
            $('#globalLoaderPdp').fadeOut();
        });

        // Esconder o loader se houver um erro na requisição
        $(document).ajaxError(function() {
            $('#globalLoaderPdp').fadeOut();
        });
    });

    let btnSendSolicitationPdpDesktop = $('#btnSendSolicitationPdpDesktop');
    let spanModalPdpNameDesktop = $('#spanModalPdpNameDesktop');
    let spanModalPdpPhoneDesktop = $('#spanModalPdpPhoneDesktop');

    function validateEmptyFieldsDesktop() {
        let name = $('.card-send-desktop input[type="text"]').eq(0).val();
        let phone = $('.card-send-desktop input[type="text"]').eq(1).val();

        if (name == '') spanModalPdpNameDesktop.removeClass('hidden');
        if (phone == '') spanModalPdpPhoneDesktop.removeClass('hidden');

        if (name == '' || phone == '') return false;

        return true;
    }

    btnSendSolicitationPdpDesktop.click(function(e) {
        e.preventDefault();
        console.log('Desktop')
        // showLoader();
        spanModalPdpNameDesktop.addClass('hidden');
        spanModalPdpPhoneDesktop.addClass('hidden');

        let name = $('.card-send-desktop input[type="text"]').eq(0).val();
        let phone = $('.card-send-desktop input[type="text"]').eq(1).val();
        let message = $('.card-send-desktop textarea').val();
        let replacement = $('#replacement-desktop').is(':checked');
        let finance = $('#finance-desktop').is(':checked');

        let teste = validateEmptyFieldsDesktop();
        console.log(teste);

        if (!validateEmptyFieldsDesktop()) {
            return;
        } else {
            console.log('Criando solicitação');
            $.ajax({
                url: '/requests/store',
                type: 'POST',
                data: {
                    name: name,
                    phone: phone,
                    message: message,
                    shift: replacement,
                    finance: finance,
                    product_id: {{ $product->id }},
                    store_id: {{ $partner->store->id }}
                },
                success: function(response) {
                    // hideLoader();
                    window.location.href =
                        '{{ route('catalog.message_sent', $partner->partner_link) }}';
                },
                error: function(error) {
                    // hideLoader();
                    alert(`Error: ${error}`);
                }
            });
        }

    });
</script>

<style>
    /* Container principal */
    .container {
        display: flex;
        gap: 20px;
        padding: 0px 50px
    }

    /* Section Left (com scroll) */
    .section-left {
        height: calc(100vh - 50px);
        /* Altura da viewport menos cabeçalho e rodapé */
        overflow-y: auto;
        /* Habilita o scroll vertical */
        padding: 20px;
        width: 50%
    }

    .section-left::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Edge */
    }

    /* Section Right (fixa) */
    .section-right {
        width: 50%;
        /* Largura fixa */
        position: sticky;
        top: 80px;
        /* Distância do topo (altura do cabeçalho) */
        height: calc(100vh - 160px);
        /* Altura da viewport menos cabeçalho e rodapé */
        padding: 20px;
        border-radius: 20px;
        /* Borda arredondada */
    }
</style>
