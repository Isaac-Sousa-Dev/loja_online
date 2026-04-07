<div class="z-40 w-[250px] overflow-y-auto bg-[#33363B] text-white desktop-navigation border-r border-[#6A2BBA]/20">

    <div class="flex flex-col bg-[#33363B] gap-2 mt-4 items-center h-32 justify-center">
        
        @if(Auth::user()->role != 'admin')

            @if(Auth::user()->partner->store->logo)
                <div class="h-20 w-20 flex border-2 border-[#6A2BBA]/50 bg-gray-300 rounded-full">
                    {{-- <img src="/storage/{{Auth::user()->partner->store->logo}}" width="60" height="60" class="object-cover rounded-full w-full object-center" alt=""> --}}
                    <img src="/storage/{{Auth::user()->partner->store->logo}}" width="60" height="60" class="object-cover rounded-full w-full object-center" alt="">
                </div>
            @else
                <div class="h-20 w-20 flex">
                    <img src="/img/logo.png" class="object-cover rounded-full w-full object-center" alt="">
                </div>
            @endif
        
            <div class="font-bold text-white">
                {{Auth::user()->partner->store->store_name}}
            </div>
        @else
            <div class="p-2">
                <img src="/img/logos/logo.png" width="150" height="150" class="object-contain rounded-xl" alt="">
            </div> 

            <div class="font-bold text-white">
                {{Auth::user()->name}}
            </div>
        @endif
    </div>

    <!-- Navegação -->
    <nav x-data="{ open: false }" class="h-[93%]">
        <div class="">
            <div class="flex flex-col pb-12">
                <div class="flex flex-col">
                   
    
                    <div>
                        <div class="uppercase font-bold text-xs text-white/90 px-3 mt-2 flex gap-1 bg-[#6A2BBA]/35 py-1 w-3/5 rounded-r-md">
                            Início
                        </div>
                        <!-- Navigation Links -->
                        <div class="p-2 flex flex-col space-y-2" >
        
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                </svg>
                                  
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            <x-nav-link :href="route('index.analytics')" :active="request()->routeIs('index.analytics')">
                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5"/>
                                </svg>
                                  
                                  
                                {{ __('Analytics') }}
                                <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span>
                            </x-nav-link>
        
                            @if (Auth::user()->role == 'admin')
                               
                                <x-nav-link :href="route('partners.index')" :active="request()->routeIs('partners.index') || request()->routeIs('partners.create') || request()->routeIs('partners.edit')" >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    {{ __('Motivados') }}
                                </x-nav-link>

                                <x-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.index') || request()->routeIs('plans.create') || request()->routeIs('plans.edit')" >
                                    <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                                      </svg>                                      
                                    {{ __('Planos') }}
                                </x-nav-link>
                            @endif
                        </div>


                        <div class="uppercase font-bold text-xs justify-between items-center text-white/90 px-3 mt-2 flex gap-1 bg-[#6A2BBA]/35 py-1 w-3/5 rounded-r-md">
                            <span>Atendimento</span>
                        </div>
                       
                        <div class="p-2 flex flex-col space-y-2" >
                            @if (Auth::user()->role == 'partner')
                                <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                    </svg>
                                    {{ __('Pedidos') }}
                                    <span class="js-order-notify-badge hidden ml-1 text-[10px] font-bold bg-red-500 text-white min-w-[1.25rem] h-5 px-1 rounded-full items-center justify-center" aria-live="polite" role="status">0</span>
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route('list.request.plans')" :active="request()->routeIs('list.request.plans')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                    </svg>
                                    {{ __('Solicitações (Admin)') }}
                                </x-nav-link>
                            @endif
                            <x-nav-link :href="route('index.agent_ai')" :active="request()->routeIs('index.agent_ai')">
                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                                    <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
                                </svg>
                                  
                                {{ __('Agente IA') }}
                                <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span>
                            </x-nav-link>
                        </div>


                        @if(Auth::user()->role != 'admin')
                            <div class="uppercase font-bold text-xs justify-between items-center text-white/90 px-3 mt-2 flex gap-1 bg-[#6A2BBA]/35 py-1 w-3/5 rounded-r-md">
                                <span>Gestão</span>
                            </div>
                            <!-- Navigation Links -->
                            <div class="p-2 flex flex-col space-y-2" >
                                @if (Auth::user()->role == 'partner')
                                    <x-nav-link :href="route('index.sales')" :active="request()->routeIs('index.sales')">
                                        <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                        </svg>                                          
                                        
                                        {{ __('Vendas') }}
                                        <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span>
                                    </x-nav-link>
                                    {{-- <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index') || request()->routeIs('clients.create') || request()->routeIs('clients.edit')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                        
                                        {{ __('Clientes') }}
                                    </x-nav-link> --}}
                                    <x-nav-link :href="route('index.sellers')" :active="request()->routeIs('index.sellers')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                        </svg>
                                        
                                        
                                        {{ __('Equipe') }}
                                        <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span>
                                    </x-nav-link>
                                @endif
                            </div>
                        @endif        
        
                        @if(Auth::user()->role != 'admin')
                        <div class="uppercase font-bold text-xs justify-between items-center text-white/90 px-3 mt-2 flex gap-1 bg-[#6A2BBA]/35 py-1 w-3/5 rounded-r-md">
                            <span>Catálogo</span>
                        </div>
                        <!-- Navigation Links -->
                        <div class="p-2 flex flex-col space-y-2" >
        
                            <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index') || request()->routeIs('categories.create') || request()->routeIs('categories.edit')">
                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
                                  </svg>
                                                           
                                  
                                {{ __('Categorias') }}
                            </x-nav-link>
        
                            @if (Auth::user()->role == 'partner')
                                <x-nav-link :href="route('subcategories.index')" :active="request()->routeIs('subcategories.index') || request()->routeIs('subcategories.create') || request()->routeIs('subcategories.edit')">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 17h6m-3 3v-6M4.857 4h4.286c.473 0 .857.384.857.857v4.286a.857.857 0 0 1-.857.857H4.857A.857.857 0 0 1 4 9.143V4.857C4 4.384 4.384 4 4.857 4Zm10 0h4.286c.473 0 .857.384.857.857v4.286a.857.857 0 0 1-.857.857h-4.286A.857.857 0 0 1 14 9.143V4.857c0-.473.384-.857.857-.857Zm-10 10h4.286c.473 0 .857.384.857.857v4.286a.857.857 0 0 1-.857.857H4.857A.857.857 0 0 1 4 19.143v-4.286c0-.473.384-.857.857-.857Z"/>
                                      </svg>                                  
                                      
                                    {{ __('Marcas') }}
                                </x-nav-link>
        
                                <x-nav-link :href="route('products.index')" :active="(request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit'))">
                                      
                                      <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path fill="white" d="M500 176h-59.9l-16.6-41.6C406.4 91.6 365.6 64 319.5 64h-127c-46.1 0-86.9 27.6-104 70.4L71.9 176H12C4.2 176-1.5 183.3 .4 190.9l6 24C7.7 220.3 12.5 224 18 224h20.1C24.7 235.7 16 252.8 16 272v48c0 16.1 6.2 30.7 16 41.9V416c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h256v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-54.1c9.8-11.3 16-25.8 16-41.9v-48c0-19.2-8.7-36.3-22.1-48H494c5.5 0 10.3-3.8 11.6-9.1l6-24c1.9-7.6-3.8-14.9-11.7-14.9zm-352.1-17.8c7.3-18.2 24.9-30.2 44.6-30.2h127c19.6 0 37.3 12 44.6 30.2L384 208H128l19.9-49.8zM96 319.8c-19.2 0-32-12.8-32-31.9S76.8 256 96 256s48 28.7 48 47.9-28.8 16-48 16zm320 0c-19.2 0-48 3.2-48-16S396.8 256 416 256s32 12.8 32 31.9-12.8 31.9-32 31.9z"/>
                                      </svg>
                                      
                                      
                                    {{ __('Produtos') }}
                                </x-nav-link>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </nav>
</div>

<style>
    .desktop-navigation {
        max-height: 100vh; /* altura máxima igual a altura da tela */
        overflow-y: auto;  /* cria o scroll se ultrapassar */
        scrollbar-width: thin; /* Firefox */
        scrollbar-color: #6A2BBA transparent; /* Firefox */
    }
</style>
