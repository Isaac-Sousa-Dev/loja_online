<div class="">
    <div class="h-12 flex items-center py-5 justify-between shadow-sm fixed w-full bg-white top-0 z-10">
        <div class="text-lg flex gap-1 items-center font-bold text-gray-700 ml-5">
            @if ($logoStore != null)
                <div class=" bg-slate-200 rounded-full w-8 h-8 flex justify-center items-center"
                    style="border: 4px solid white; overflow: hidden;">
                    <img src="{{ $logoStore }}" class="object-cover object-center w-full h-full" alt="Logo">
                </div>
            @endif
            {{ $partner->store->store_name }}
        </div>

        <div class="mr-5 btn-back-product-page flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
            Voltar
        </div>
    </div>
    <script>
        $('.btn-back-product-page').click(function() {
            window.location.href = '{{ route('orders.index', $partner->partner_link) }}';
        });
    </script>

    <div class="mt-14 mb-20">
        <div class="px-4 flex flex-col font-bold h-full w-full">
            {{-- <div class="text-md break-words uppercase text-blue-700">
                {{$product->subcategory->name}} 
            </div> --}}
            <div class="w-full text-xl break-words uppercase text-gray-700">
                {{ $product->name }}
            </div>
        </div>

        <!-- Carrossel de imagens -->
        <div class="carousel-mobile swiper-container w-full relative overflow-hidden">
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
            </div> --}}
            {{-- <div class="flex">
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


    <div class="bg-white fixed bottom-0 w-full p-3 flex justify-center z-10">
        <button class="btn-send-message-pdp bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Adicionar ao
            carrinho</button>
    </div>

    {{-- Modal send Message --}}
    <div class="modal-send-message hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div
            class="modal-send w-11/12 md:w-1/2 bg-white p-4 rounded-lg absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex justify-between items-center">
                <div class="text-lg font-bold text-gray-700">
                    Adicionar ao carrinho
                </div>
                <div class="close-modal-send cursor-pointer">
                    <ion-icon name="close-outline" class="text-2xl text-gray-700"></ion-icon>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-bold text-gray-700">
                    Nome *
                </div>
                <input type="text" id="inputModalPdpName" placeholder="Seu nome"
                    class="w-full border border-gray-300 rounded-lg p-2 mt-1">
                <span id="spanModalPdpName" class="hidden">
                    <div class="gap-1 items-center text-sm text-red-500 flex">
                        <svg class="w-4 h-4 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Campo nome é obrigatório
                    </div>
                </span>
            </div>
            <div class="mt-4">
                <div class="font-bold text-gray-700">
                    Telefone *
                </div>
                <input id="inputModalPdpPhone" type="text" placeholder="Seu telefone"
                    class="phone-mask w-full border border-gray-300 rounded-lg p-2 mt-1">
                <span id="spanModalPdpPhone" class="hidden">
                    <div class="gap-1 items-center text-sm text-red-500 flex">
                        <svg class="w-4 h-4 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Campo telefone é obrigatório
                    </div>
                </span>
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-500" for="Gostaria de (opcional)">Gostaria de (opcional)</label>
                <div class="flex gap-3">
                    <div class="mt-1 flex items-center gap-2">
                        <input id="replacement" type="checkbox" class="rounded input-checkbox">
                        <label for="replacement">Desejo negociar / Oferecer produto</label>
                    </div>
                    <div class="mt-1 flex items-center gap-2">
                        <input id="finance" type="checkbox" class="rounded input-checkbox">
                        <label for="finance">Financiar</label>
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
                <button id="btnSendSolicitationPdp"
                    class="bg-blue-700 text-white font-bold px-4 py-2 rounded-lg w-full">Enviar</button>
            </div>
        </div>
    </div>
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

    let btnSendMessagePdp = $('.btn-send-message-pdp');
    let btnCloseModalSendMessage = $('.close-modal-send');
    let btnSendSolicitationPdp = $('#btnSendSolicitationPdp');
    let spanModalPdpName = $('#spanModalPdpName');
    let spanModalPdpPhone = $('#spanModalPdpPhone');

    btnCloseModalSendMessage.click(function() {
        $('.modal-send-message').addClass('hidden');
    });

    btnSendMessagePdp.click(function() {
        $('.modal-send-message').removeClass('hidden');
    });

    function validateEmptyFields() {
        let name = $('.modal-send input[type="text"]').eq(0).val();
        let phone = $('.modal-send input[type="text"]').eq(1).val();

        if (name == '') spanModalPdpName.removeClass('hidden');
        if (phone == '') spanModalPdpPhone.removeClass('hidden');

        if (name == '' || phone == '') return false;

        return true;
    }

    btnSendSolicitationPdp.click(function(e) {
        e.preventDefault();
        // showLoader();
        spanModalPdpName.addClass('hidden');
        spanModalPdpPhone.addClass('hidden');

        let name = $('.modal-send input[type="text"]').eq(0).val();
        let phone = $('.modal-send input[type="text"]').eq(1).val();
        let message = $('.modal-send textarea').val();
        let replacement = $('#replacement').is(':checked');
        let finance = $('#finance').is(':checked');

        let teste = validateEmptyFields();
        console.log(teste, 'Validação');

        if (!validateEmptyFields()) {
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
                    $('.modal-send-message').addClass('hidden');
                    window.location.href =
                        '{{ route('catalog.message_sent', $partner->partner_link) }}';
                },
                error: function(error) {
                    if (error.status == 409) {
                        window.location.href =
                            '{{ route('catalog.message_sent', $partner->partner_link) }}';
                    }
                }
            });
        }

    });
</script>
