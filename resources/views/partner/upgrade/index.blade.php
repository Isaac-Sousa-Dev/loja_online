<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div class="mt-4">

                <div class="flex flex-col md:flex-row gap-3">
                    <div class="bg-white p-3 rounded-xl md:w-1/3">
                        <h2 class="text-lg font-semibold">Meu Plano</h2>
    
                        <div class="py-1 rounded-lg mt-2">
                            @if($partner->subscription->status == 'active') 
                                <div class="font-semibold text-green-400 text-md bg-green-50 p-2 flex justify-between items-center rounded-lg">
                                    <div>
                                        Assinatura Ativa
                                    </div>
                                    
                                    {{-- <div class="text-gray-700 text-sm">
                                        Até {{ \Carbon\Carbon::parse($partner->subscription->end_date)->format('d/m/Y') }}
                                    </div>  --}}
                                </div>   
                            @else
                                <div class="font-semibold text-red-400 text-md bg-red-50 p-2 flex justify-between items-center rounded-lg">
                                    <div>
                                        Assinatura Inativa
                                    </div>
    
                                    <button class="text-white text-sm bg-blue-600 px-4 py-1 rounded-xl">
                                        Reativar
                                    </button> 
                                </div> 
                            @endif
                            {{-- <div class="mt-1 px-2 flex gap-1"> 
                                <svg class="w-5 h-5 text-gray-800 items-start" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>              
                                <div class="text-gray-700 text-xs mt-0.5">
                                    3 dias antes do vencimento, você receberá um e-mail com o link para renovação.
                                </div>
                            </div> --}}
                        </div>
    
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-blue-50 mt-2 gap-2 px-4 p-2 rounded-lg">
                                <div class="font-semibold text-blue-700 text-xl">
                                    {{$partner->subscription->plan->name}}
                                </div>
                                <div class="font-semibold text-gray-700 text-sm md:text-md">
                                    {{-- R$ {{ number_format($partner->subscription->plan->price, 2, ',', '.') }}
                                    @if($partner->subscription->appellant)
                                        /mês
                                    @endif --}}
                                    Gratuito
                                </div>
                            </div>
                            <div class="bg-gray-200 mt-2 gap-2 px-4 p-2 rounded-lg">
                                <div class="font-semibold text-gray-700 text-sm">
                                    Inicio
                                </div>
                                <div class="font-semibold text-gray-700 text-xl md:text-2xl">
                                    {{ \Carbon\Carbon::parse($partner->subscription->start_date)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
    
                        <div class="mt-2">
                            <div class="text-gray-600 font-medium text-center flex flex-col gap-2">
    
                                <div class="flex gap-1 items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>                                          
                                    1 Usuário
                                </div>
    
                                <div class="flex gap-1 items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>                                          
                                    Gestão de Veículos
                                </div>
    
                                <div class="line-through flex gap-1 text-sm items-center">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    
                                    CRM Dedicado
                                </div>
    
                                <div class="line-through flex gap-1 items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    Gestão de Vendas
                                </div>
    
                                <div class="flex gap-1 items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg> 
                                    Site Profissional   
                                </div>
    
                                <div class="flex gap-1 items-center line-through text-sm">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    Indique e Ganhe   
                                </div>
    
                                <div class="flex gap-1 items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg> 
                                    1 CNPJ  
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-xl md:w-2/3">
                        <div class="text-lg font-semibold">
                            Atualizar plano
                        </div>

                        <div>
                            Selecione um plano abaixo
                        </div>

                        <div class="flex flex-col md:flex-row mt-3 gap-2 h-full">

                            <div class="md:w-1/3 cursor-pointer">
                                <div id="card_plan_startplus" class="card_plan bg-gray-50 w-full rounded-lg p-2 border-2 border-blue-600 hover:bg-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div class="text-lg font-medium">
                                            Start Plus
                                        </div>
                                        <div>
                                            <input class="input_plan_radio" type="radio" checked name="upgrade" id="plan_startplus">
                                        </div>
                                    </div>


                                    <div class="text-lg font-semibold text-blue-700">
                                        R$ 89,99
                                    </div>

                                    <div class="mt-2">
                                        <div class="text-gray-600 font-medium text-center flex flex-col gap-2">
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>                                          
                                                1 Usuário
                                            </div>
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>                                          
                                                Gestão de Veículos
                                            </div>
                
                                            <div class="line-through flex gap-1 text-sm items-center">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                
                                                CRM Dedicado
                                            </div>
                
                                            <div class="line-through flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Gestão de Vendas
                                            </div>
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg> 
                                                Site Profissional   
                                            </div>
                
                                            <div class="flex gap-1 items-center line-through text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Indique e Ganhe   
                                            </div>
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg> 
                                                1 CNPJ  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            


                            <div class="md:w-1/3 cursor-pointer">
                                <div id="card_plan_advanced" class="card_plan bg-gray-100 w-full rounded-lg p-2">
                                    <div class="flex justify-between items-center">
                                        <div class="text-lg font-medium">
                                            Advanced
                                        </div>
                                        <div>
                                            <input class="input_plan_radio" type="radio" name="upgrade" id="plan_advanced">
                                        </div>
                                    </div>
    
                                    <div class="text-lg font-semibold text-blue-700">
                                        R$ 119,99
                                    </div>
    
                                    <div class="mt-2">
                                        <div class="text-gray-600 font-medium text-center flex flex-col gap-2">
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>                                          
                                                1 Usuário
                                            </div>
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>                                          
                                                Gestão de Veículos
                                            </div>
                
                                            <div class="line-through flex gap-1 text-sm items-center">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                
                                                CRM Dedicado
                                            </div>
                
                                            <div class="line-through flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Gestão de Vendas
                                            </div>
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg> 
                                                Site Profissional   
                                            </div>
                
                                            <div class="flex gap-1 items-center line-through text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                Indique e Ganhe   
                                            </div>
                
                                            <div class="flex gap-1 items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg> 
                                                1 CNPJ  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $('.card_plan').click(function (e) { 
            e.preventDefault();
            
            let element = e.target;
            let cardsPlan = $('.card_plan').toArray();
            cardsPlan.forEach(card => {
                $(card).removeClass('border-blue-600');
                $(card).removeClass('border-2');
                console.log(card, 'Meu card')
            });
            console.log(cardsPlan);
            console.log(element, 'Element')
        });
    </script>
    @endsection
</x-app-layout>
