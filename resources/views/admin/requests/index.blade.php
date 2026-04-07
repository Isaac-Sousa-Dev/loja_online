<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div>

                <div class="flex items-center md:justify-between mt-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                            {{ __('Solicitações de Planos') }}
                        </h2>
                    </div>
                </div>


                <!-- component -->
                <div class="overflow-auto rounded-lg border border-gray-200 shadow-md mt-8">
                    <div class="hidden md:block">
                        <div class="px-4 py-2 bg-gray-100 shadow-sm grid grid-cols-4 gap-4">
                            <div class="font-semibold">Motivado</div>
                            <div class="font-semibold">Dados</div>
                            <div class="font-semibold">Pagamento</div>
                            <div class="font-semibold">Ações</div>
                        </div>
                    </div>
                
                    <div id="listProductStatic" class="bg-white w-full">
                        @if($allRequestPlans->isEmpty())
                            <div class="flex justify-center items-center h-14">
                                <div class="text-gray-400 text-lg font-semibold">
                                    Não há solicitações no momento
                                </div>
                            </div>
                        @else
                            @foreach ($allRequestPlans as $requestPlan)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-2 md:px-4 py-2 border-b border-gray-200 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div>
                                        <div class="font-bold text-gray-600">
                                            <div href="#">{{ $requestPlan->name }}</div>
                                        </div>
                                        <div class="text-xs">
                                            <div>{{$requestPlan->email}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div>
                                        <div class="font-semibold">
                                            <div class="text-blue-700">
                                                <span class="text-gray-600 text-sm md:text-md">{{ $requestPlan->plan_name }}</span> - 
                                                R$ <span class="price-mask text-sm md:text-md">{{ $requestPlan->plan_price }}</span>
                                            </div>
                                        </div>
                                        <div class="font-medium text-gray-500 phone-mask">
                                            {{ $requestPlan->phone }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center text-sm md:text-md font-medium">
                                    @switch($requestPlan->payment_method)
                                        @case('pix')
                                            <span>Pix</span>
                                            @break
                                        @case('credit')
                                            <span>Crédito</span>
                                            @break
                                        @case('pendente')
                                            <span>A combinar</span>
                                            @break
                                        @default
                                            <span>{{ $requestPlan->payment_method ?: '—' }}</span>
                                    @endswitch
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" data-requestplan="{{$requestPlan}}" class="btnCompleteRegistration text-sm py-1 md:text-md bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] hover:brightness-105 transition ease-in-out duration-200 px-3 rounded-md font-semibold text-white shadow-sm">Concluir Cadastro</button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    
                </div>
                <div class="mt-2">
                    {{-- Renderiza os links de paginação --}}
                    {{ $allRequestPlans->links('components.pagination') }}
                </div>
                
            </div>
        </div>
    </div>


    <!-- Modal de confirmação -->
    <div id="completePartnerRegistration" class="hidden fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center h-screen w-screen">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all mx-2">
                <div class="bg-white px-4 py-2 pb-4">
                    <div>
                        <div class="mt-1">

                            <div class="mt-2">
                                <div class="grid md:grid-cols-3 gap-2">
                                    <div class="bg-gray-50 border p-2 rounded-xl">    
                                        <div class="font-semibold text-blue-700">Motivado:</div>
                                        <div class="flex flex-col">
                                            <span class="spanClientName"></span>
                                            <span class="spanClientEmail text-sm text-gray-500"></span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 border p-2 rounded-xl">    
                                        <div class="font-semibold text-blue-700">Plano:</div>
                                        <div class="gap-2 grid grid-cols-2">
                                            <div class="flex flex-col">
                                                <span class="spanPlanName">Advanced</span>
                                            </div>
                                            <div class="bg-[#EDE9FE] rounded-xl px-2 py-1 flex justify-center text-[#6A2BBA]">
                                                <div class="text-lg font-semibold">
                                                    <span class="text-xs font-semibold">R$</span>
                                                    <span class="spanPlanPrice price-mask"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 border p-2 rounded-xl">    
                                        <div class="font-semibold text-blue-700">Pagamento:</div>
                                        <div class="flex flex-col">
                                            <span class="spanMethodPayment"></span>
                                            <div class="text-sm text-gray-500">
                                                Data inicio: <span class="spanInitialDate"></span>
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                <div class="flex flex-col md:flex-row gap-3 mt-3">
                                    <div class="flex flex-col">    
                                        <label class="text-sm font-medium text-gray-400" for="">Dia do próximo pagamento</label>
                                        <input type="date" placeholder="Ex: 20, 25, 30" class="inputDayNextPayment rounded-xl" >
                                    </div>
                                    <div class="flex flex-col gap-1">    
                                        <label class="text-sm font-medium text-gray-400">Comprovante de pagamento <span class="text-xs">(obrigatório)</span></label>
                                        {{-- <label for="file" class="bg-gray-100 hover:bg-gray-200 cursor-pointer transition ease-in-out duration-300 font-medium py-2 px-2 rounded-xl">Carregar arquivo</label> --}}
                                        <input id="file" type="file" class="" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <div class="flex gap-2">
                        <button onclick="btnCancelCompletePartnerRegistration()"  type="button"
                            class="bg-white px-3 py-1 shadow-sm rounded-lg font-semibold text-gray-500">
                            Cancelar
                        </button>
                        <button type="button" onclick="btnConfirmCompletePartnerRegistration(event)"
                            class="btnConfirmCompletePartnerRegistration bg-green-400 px-3 py-1 text-white shadow-sm font-semibold rounded-lg">
                            Concluir
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endsection
</x-app-layout>


<script>

    let requestPlan;
    let clientName;
    let clientEmail;
    let planName;
    let planPrice;
    let paymentMethod;
    let initialDate;
    let dayNextPayment

    function formattedNextDayPayment(initialDate, planDuration) {
        dayNextPayment = new Date(initialDate);
        dayNextPayment.setDate(dayNextPayment.getDate() + planDuration);
        dayNextPayment = dayNextPayment.toLocaleDateString('pt-BR');
        dayNextPayment = dayNextPayment.replace(/\//g, '-');
        dayNextPayment = dayNextPayment.split('-').reverse().join('-'); 
        $('.inputDayNextPayment')[0].value = dayNextPayment;
    }

    $('.btnCompleteRegistration').click(function() {
        requestPlan = $(this).data('requestplan');
        clientName = requestPlan.name;
        clientEmail = requestPlan.email;
        planName = requestPlan.plan_name;
        planPrice = requestPlan.plan_price; 
        paymentMethod = requestPlan.payment_method;
        initialDate = requestPlan.created_at;
        formattedNextDayPayment(initialDate, requestPlan.plan_duration);
        initialDate = new Date(initialDate).toLocaleDateString('pt-BR');

        document.querySelector('.spanClientName').innerHTML = clientName;
        document.querySelector('.spanClientEmail').innerHTML = clientEmail;
        document.querySelector('.spanPlanName').innerHTML = planName;
        document.querySelector('.spanPlanPrice').innerHTML = planPrice;
        document.querySelector('.spanMethodPayment').innerHTML = paymentMethod;
        document.querySelector('.spanInitialDate').innerHTML = initialDate;

        document.getElementById('completePartnerRegistration').classList.remove('hidden');
    });

    function btnConfirmCompletePartnerRegistration(event) {
        event.preventDefault();   
        console.log('testeeee');     
        const file = document.getElementById('file').files[0];
        if(!file) {
            toastr.error('É necessário anexar o comprovante de pagamento');
        } else {
            showLoader()
            const dayNextPayment = document.querySelector('.inputDayNextPayment').value;
            const formData = new FormData();

            formData.append('manual_receipt', file);
            formData.append('requestPlanId', requestPlan.id);   
            formData.append('due_date', dayNextPayment);
            formData.append('name', clientName);
            formData.append('email', clientEmail);
            formData.append('phone', requestPlan.phone);
            formData.append('planName', planName);
            formData.append('amount', planPrice);
            formData.append('payment_method', paymentMethod);
            formData.append('start_date', initialDate);
            formData.append('plan_id', requestPlan.plan_id);
            formData.append('store_name', requestPlan.store_name);
            formData.append('appellant', 1);
            formData.append('is_testing', 0)
    
            $.ajax({
                url: '{{ route('new.subscribe.user') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    document.getElementById('completePartnerRegistration').classList.add('hidden');
                    window.location.href = '{{ route('list.request.plans') }}';
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        toastr.error(response.message);
                    } else {
                        toastr.error('Ocorreu um erro ao processar a solicitação.');
                    }
                },
                finally: function() {
                    hideLoader();
                }
            });
        }

    }


    // Função para ocultar o modal de confirmação
    function hideDeleteConfirmationModal() {
        document.getElementById('completePartnerRegistration').classList.add('hidden');
    }

    // Evento de clique no botão de confirmar exclusão
    

    // Evento de clique no botão de cancelar exclusão
    function btnCancelCompletePartnerRegistration() {
        hideDeleteConfirmationModal();
    }
</script>
