<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div>

                <div class="flex justify-between items-center mt-4">
                    <div>
                        <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                            {{ __('Solicitações') }}
                        </h2>
    
                        <div class="leading-4 ml-1 text-sm font-semibold text-gray-600">
                            Aqui você pode acompanhar todas as solicitações de clientes para seus veículos que estão no catálogo e iniciar uma conversa no WhatsApp.
                        </div>
                    </div>
                </div>

                {{-- <div class="flex mt-3 justify-between items-center">
                    <div class="flex gap-2">
                        <div class="bg-gray-200 py-1 px-4 rounded-xl shadow-sm font-medium cursor-pointer border-1 border-black">
                            Todas
                        </div>
    
                        <div class="bg-green-500 py-1 px-4 rounded-xl shadow-sm font-medium text-white cursor-pointer">
                            Compras
                        </div>
    
                        <div class="bg-sky-500 py-1 px-4 rounded-xl shadow-sm font-medium text-white cursor-pointer">
                            Trocas
                        </div>
                    </div>

                    <div class="flex hidden md:flex items-center bg-gray-400">
                        Calendário
                    </div>
                </div> --}}


                <div class="mt-3 mb-2">
            
                    <div class="grid  w-full grid-cols-1 md:grid-cols-3 rounded-xl">

                        @if($requestsByStore->isEmpty())
                            <div class="shadow-sm rounded-xl h-32 py-2 border bg-white border-gray-300 mt-3 md:mr-1">
                                <div class="bg-slate-100 px-2 font-semibold">
                                    Nenhuma solicitação encontrada
                                </div>

                                <div class="px-2 text-sm mt-2">
                                    <span class="font-semibold">Dica:</span>
                                    <span>Compartilhe o link do seu estabelecimento para que seus clientes possam solicitar veículos.</span>
                                </div>
                            </div>
                        @endif
    
                        @foreach($requestsByStore as $request)
                            <div class="shadow-sm rounded-xl h-32 py-2 border bg-white border-gray-300 mt-3 md:mr-1">
                                <div class="bg-slate-100 px-2 font-semibold">
                                    {{$request->product->name}}
                                </div>
                                <div class="px-2 mt-2">
        
                                    <div class="text-xs mt-1 justify-between flex">
                                        <div class="flex gap-1">
                                            <div class="px-2 text-[10px] rounded-md font-semibold {{ $request->statusColor() }}">
                                                {{ $request->statusLabel() }}
                                            </div>
                                            @if($request->shift == 1)
                                                <div class="px-2 text-[10px] rounded-md font-semibold bg-sky-500 text-white">
                                                    Troca
                                                </div>
                                            @else
                                                <div class="px-2 rounded-md text-[10px] font-semibold bg-green-500 text-white">
                                                    Compra
                                                </div>
                                            @endif
                                        </div>
                                        {{ $request->created_at->format('d/m/Y - H:i') }}
                                    </div>
    
                                    <div class="mt-1 text-sm flex justify-between">
                                        <div>R$ {{$request->product->price}}</div>
                                        <div class="font-semibold">{{$request->client->name}}</div>
                                    </div>

                                    
                                </div>

                                <div class="px-2 flex justify-between mt-2">
                                    <div class="text-sm flex justify-between">
                                        <div class="font-medium text-xs">{{$request->finance == 1 ? 'Financiado' : 'À vista'}}</div>
                                    </div>

                                    <div>
                                        @if($request->status == 'in_open')        
                                            <div 
                                                data-requestid="{{$request->id}}" 
                                                data-clientname="{{$request->client->name}}"
                                                data-clientphone="{{$request->client->phone}}"
                                                data-productname="{{$request->product->name}}"
                                                data-storename="{{$request->store->store_name}}"
                                                class="btnInitRequest bg-blue-700 text-white py-1 px-3 font-medium rounded-xl shadow-sm cursor-pointer">
                                                Iniciar
                                            </div>
                                        @endif

                                        @if($request->status == 'in_progress')  
                                            <div class="flex gap-1">
                                                <div 
                                                    data-requestidsold="{{$request->id}}" 
                                                    data-productIdSold="{{$request->product->id}}" 
                                                    data-productname="{{$request->product->name}}"
                                                    data-clientname="{{$request->client->name}}"
                                                    onclick="showSoldConfirmationModal(event)" class="bg-blue-200 py-1 border-1 border-blue-500 px-3 text-sm font-medium rounded-xl shadow-sm cursor-pointer">
                                                    Finalizar venda
                                                </div>
                                            </div>
                                        @endif

                                        @if($request->status == 'sold')  
                                            <div class="flex gap-1">
                                                <div 
                                                    data-requestidsold="{{$request->id}}" 
                                                    data-productIdSold="{{$request->product->id}}" 
                                                    data-productname="{{$request->product->name}}"
                                                    data-clientname="{{$request->client->name}}"
                                                    class="py-1 text-sm font-medium text-green-500 flex items-center gap-1">
                                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                                                    </svg>
                                                    Venda concluída
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        @endforeach

                        <script>
                            let btnInitRequest = $('.btnInitRequest');
                            $(btnInitRequest).click(function(e) {
                                e.preventDefault();

                                // console.log('teste')
                                showLoader();
                                let clientName = $(this).data('clientname');
                                let storeName = $(this).data('storename');
                                let requestId = $(this).data('requestid');
                                let clientPhone = $(this).data('clientphone');
                                console.log(clientPhone, 'Telefone')
                                // let phoneFormatted = clientPhone.replace(/\D/g, '');
                                let productName = $(this).data('productname');

                                $.ajax({
                                    url: 'requests/init',
                                    type: 'POST',
                                    data: {
                                        requestId: requestId
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        hideLoader();
                                    }
                                });
                                
                                // Abrindo em nova janela
                                let url = `https://api.whatsapp.com/send/?phone=55${clientPhone}&text=Ol%C3%A1+${clientName},+sou+da+loja+${storeName}+e+vi+que+voce+teve+interesse+no+veiculo+${productName}.+Gostaria+de+mais+informa%C3%A7%C3%B5es%3F&app_absent=0`;
                                window.open(url, '_blank');

                                setTimeout(() => {
                                    window.location.reload();
                                }, 3000);


                            })
                        </script>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal de confirmação de venda -->
    <div id="soldConfirmationModalProduct" class="hidden fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center px-4">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden text-black" aria-hidden="true">&#8203;</span>
           
              

            <div class="inline-block align-bottom w-full md:w-1/4 mt-[60%] md:mt-[15%] bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="px-4 mb-1 py-4">
                    <svg class="closeModal w-6 h-6 hover:bg-gray-200 cursor-pointer text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 div-product-name" id="modal-headline">
                                
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Após a confirmação, o veículo será marcado como vendido ou cancelado e a solicitação será finalizada.
                                </p>
                            </div>

                            <div class="div-client-name mt-2"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex flex-col gap-2 md:flex-row justify-between">
                    <button onclick="cancelSale()" id="cancelSaleBtn" type="button"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-500 text-base font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar venda
                    </button>
                    <button onclick="confirmSale()" id="confirmSaleBtn" type="button"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-green-500 text-base font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar venda
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endsection

</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    const inputSearchName = document.getElementById('input-search-name');

    $(inputSearchName).on('input', function() {
        const search = $(this).val().toLowerCase();

        $.ajax({
            url: 'products/search',
            type: 'GET',
            data: {
                search: search
            },
            success: function(response) {
                updateProductTable(response.data);
            }
        });
    });

    $(document).ready(function() {
        $('.price-mask').mask('000.000.000.000.000,00', {reverse: true});
    });


    // Função para mostrar o modal de confirmação
    function showSoldConfirmationModal(event) {
        const productId = event.target.dataset.productidsold;
        const requestId = event.target.dataset.requestidsold;

        document.querySelector('#soldConfirmationModalProduct').dataset.productId = productId;
        document.querySelector('#soldConfirmationModalProduct').dataset.requestId = requestId;
        document.querySelector('.div-product-name').innerText = event.target.dataset.productname;
        document.querySelector('.div-client-name').innerHTML = `<span class="font-semibold">Cliente:</span> ${event.target.dataset.clientname}`;

        document.getElementById('soldConfirmationModalProduct').classList.remove('hidden');
    }

    $('.closeModal').click(function(e) {
        e.preventDefault();
        document.getElementById('soldConfirmationModalProduct').classList.add('hidden');
    })

    function confirmSale(event) {
        const modal = document.querySelector('#soldConfirmationModalProduct');

        const productId = modal.dataset.productId;
        const requestId = modal.dataset.requestId;

        console.log('Confirmando venda de:', productId, requestId);

        $.ajax({
            url: 'requests/sold',
            type: 'POST',
            data: {
                productId: productId,
                requestId: requestId
            },
            success: function(response) {
                console.log(response);
                modal.classList.add('hidden');
                toastr.success("Venda confirmada!", "Sucesso!");
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });

        modal.classList.add('hidden');
    }


    function cancelSale() {
        const modal = document.querySelector('#soldConfirmationModalProduct');

        const productId = modal.dataset.productId;
        const requestId = modal.dataset.requestId;

        $.ajax({
            url: 'requests/unsold',
            type: 'POST',
            data: {
                productId: productId,
                requestId: requestId
            },
            success: function(response) {
                toastr.error("Venda cancelada!");
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });

        modal.classList.add('hidden');
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
