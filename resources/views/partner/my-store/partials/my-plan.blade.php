<div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-[#33363B]/8">
    <div class="flex items-center gap-2 mb-1">
        <div class="w-8 h-8 rounded-full bg-[#EDE9FE] flex items-center justify-center text-[#6A2BBA]">
            <i class="fa-solid fa-crown text-sm"></i>
        </div>
        <h2 class="font-semibold text-lg text-[#33363B]">Minha Assinatura</h2>
    </div>

    <div class="py-1 rounded-lg mt-2">
        @if ($partner->subscription->status == 'active')
            <div
                class="font-semibold text-sm bg-[#ecfdf5] text-emerald-800 p-2 flex justify-between items-center rounded-xl border border-emerald-200/80">
                <div>
                    Assinatura Ativa
                </div>

                {{-- <div class="text-gray-700 text-sm">
                    Até {{ \Carbon\Carbon::parse($partner->subscription->end_date)->format('d/m/Y') }}
                </div>  --}}
            </div>
        @else
            <div class="font-semibold text-sm bg-red-50 text-red-700 p-2 flex justify-between items-center rounded-xl border border-red-100">
                <div>
                    Assinatura Inativa
                </div>

                <button type="button" class="text-white text-sm bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] px-4 py-1.5 rounded-xl font-semibold shadow-sm">
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
        <div class="bg-[#EDE9FE]/70 mt-2 gap-2 px-4 p-2 rounded-xl border border-[#6A2BBA]/15">
            <div class="font-semibold text-[#6A2BBA] text-xl">
                {{ $partner->subscription->plan->name }}
            </div>
            <div class="font-semibold text-[#33363B] text-sm md:text-md">
                R$ {{ number_format($partner->subscription->plan->price, 2, ',', '.') }}
                @if ($partner->subscription->appellant)
                    /mês
                @endif
            </div>
        </div>
        <div class="bg-[#F8F9FC] mt-2 gap-2 px-4 p-2 rounded-xl border border-[#33363B]/8">
            <div class="font-semibold text-[#33363B]/70 text-sm">
                Inicio
            </div>
            <div class="font-semibold text-[#33363B] text-xl md:text-2xl">
                {{ \Carbon\Carbon::parse($partner->subscription->start_date)->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <div class="mt-2">
        <div class="text-[#33363B]/75 font-medium text-center flex flex-col gap-2">

            <div class="flex gap-1 items-center text-sm justify-center">
                <svg class="w-4 h-4 shrink-0 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                1 Usuário
            </div>

            <div class="flex gap-1 items-center text-sm justify-center">
                <svg class="w-4 h-4 shrink-0 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Gestão de Produtos
            </div>

            <div class="line-through flex gap-1 text-sm items-center justify-center text-[#33363B]/45">
                <svg class="w-4 h-4 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                CRM Dedicado
            </div>

            <div class="flex gap-1 items-center text-sm justify-center">
                <svg class="w-4 h-4 shrink-0 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Gestão de Vendas
            </div>

            <div class="flex gap-1 items-center text-sm justify-center">
                <svg class="w-4 h-4 shrink-0 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Site Profissional
            </div>

            <div class="flex gap-1 items-center line-through text-sm justify-center text-[#33363B]/45">
                <svg class="w-4 h-4 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Indique e Ganhe
            </div>

            <div class="flex gap-1 items-center text-sm justify-center">
                <svg class="w-4 h-4 shrink-0 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                1 CNPJ
            </div>
        </div>
    </div>

</div>
