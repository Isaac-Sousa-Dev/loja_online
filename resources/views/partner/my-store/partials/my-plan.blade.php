<div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center gap-2 mb-1">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-crown text-sm"></i>
        </div>
        <h2 class="font-semibold text-lg text-gray-800">Minha Assinatura</h2>
    </div>

    <div class="py-1 rounded-lg mt-2">
        @if ($partner->subscription->status == 'active')
            <div
                class="font-semibold text-green-400 text-md bg-green-50 p-2 flex justify-between items-center rounded-lg">
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
                {{ $partner->subscription->plan->name }}
            </div>
            <div class="font-semibold text-gray-700 text-sm md:text-md">
                R$ {{ number_format($partner->subscription->plan->price, 2, ',', '.') }}
                @if ($partner->subscription->appellant)
                    /mês
                @endif
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
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                1 Usuário
            </div>

            <div class="flex gap-1 items-center text-sm">
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Gestão de Produtos
            </div>

            <div class="line-through flex gap-1 text-sm items-center">
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                CRM Dedicado
            </div>

            <div class="flex gap-1 items-center text-sm">
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Gestão de Vendas
            </div>

            <div class="flex gap-1 items-center text-sm">
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Site Profissional
            </div>

            <div class="flex gap-1 items-center line-through text-sm">
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Indique e Ganhe
            </div>

            <div class="flex gap-1 items-center text-sm">
                <svg class="w-4 h-4 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                1 CNPJ
            </div>
        </div>
    </div>

</div>
