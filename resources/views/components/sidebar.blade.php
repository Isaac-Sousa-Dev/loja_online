<!-- Sidebar -->

@php
    $modules = [];
    $user = Auth::user();
    if ($user && $user->role !== 'admin' && $user->partner?->store?->plan?->modules) {
        $modules = $user->partner->store->plan->modules->pluck('module')->toArray();
    }
@endphp

<div id="sidebar"
     class="fixed top-0 left-0 z-40 h-full w-64 bg-[#33363B] transform transition-transform duration-300 -translate-x-full md:translate-x-0 md:relative md:block border-r border-[#6A2BBA]/20 shadow-[inset_-1px_0_0_rgba(106,43,186,0.12)]">
    <div class="h-full overflow-y-auto">

        <div class="flex flex-col gap-2 items-center h-32 justify-center">
        
            @if(Auth::user()->role != 'admin')

                @if(Auth::user()->partner?->store?->logo)
                    <div class="h-20 w-20 flex border-2 border-[#6A2BBA]/50 bg-gray-300 rounded-full">
                        <img src="/storage/{{ Auth::user()->partner->store->logo }}" width="60" height="60" class="object-cover rounded-full w-full object-center" alt="">
                    </div>
                @else
                    <div class="h-20 w-20 flex">
                        <img src="/img/vistuu-logo.png" class="object-cover rounded-full w-full object-center" alt="">
                    </div>
                @endif
            
                <div class="font-bold text-white">
                    {{ Auth::user()->partner?->store?->store_name ?? Auth::user()->name }}
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

        <nav>
            <div class="">
                <div class="flex flex-col pb-12">
                    <div class="flex flex-col">
                    
        
                        <div>
                            <div class="uppercase font-bold text-xs text-white/90 px-3 mt-2 flex gap-1 bg-[#6A2BBA]/35 py-1 w-3/5 rounded-r-md">
                                Início
                            </div>

                            @if(Auth::user()->role != 'admin')
                                <div class="p-2 flex flex-col space-y-2" >

                                    @php
                                        $dashboardRoute = in_array('dashboard', $modules)
                                            ? route('dashboard')
                                            : route('upgrade.index');
                                    @endphp
                                    <x-nav-link 
                                        :href="$dashboardRoute" :active="request()->routeIs('dashboard')" 
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                        </svg>
                                        
                                        {{ __('Dashboard') }}
                                        @if(!in_array('dashboard', $modules))
                                            <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                        @endif
                                    </x-nav-link>


                                    {{-- @php
                                        $analyticsRoute = in_array('analytics', $modules)
                                            ? route('index.analytics')
                                            : route('upgrade.index');
                                    @endphp
                                    <x-nav-link :href="$analyticsRoute" :active="request()->routeIs('index.analytics')">
                                        <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5"/>
                                        </svg>
                                        
                                        
                                        {{ __('Analytics') }}
                                        @if(!in_array('analytics', $modules))
                                            <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                        @endif
                                        <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span>
                                    </x-nav-link> --}}
                
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
                            @endif


                            <div class="uppercase font-bold text-xs justify-between items-center text-white/90 px-3 mt-2 flex gap-1 bg-[#6A2BBA]/35 py-1 w-3/5 rounded-r-md">
                                <span>Atendimento</span>
                            </div>
                        
                            <div class="p-2 flex flex-col space-y-2" >
                                @if (Auth::user()->role == 'partner')

                                    
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

                                        @php
                                            $ordersRoute = (in_array('orders', $modules) || in_array('requests', $modules))
                                                ? route('orders.index')
                                                : route('upgrade.index');
                                        @endphp
                                        <x-nav-link :href="$ordersRoute" :active="request()->routeIs('orders.index')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                            </svg>
                                            {{ __('Pedidos') }}
                                            <span
                                                class="js-order-notify-badge hidden ml-1 min-h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"
                                                aria-live="polite"
                                                role="status">0</span>
                                            @if(!in_array('orders', $modules) && !in_array('requests', $modules))
                                                <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                            @endif
                                        </x-nav-link>

                                        @php
                                            $salesRoute = in_array('sales', $modules)
                                                ? route('index.sales')
                                                : route('upgrade.index');
                                        @endphp
                                        <x-nav-link :href="$salesRoute" :active="request()->routeIs('index.sales')">
                                            <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                            </svg>                                          
                                            
                                            {{ __('Vendas') }}
                                            @if(!in_array('sales', $modules))
                                                <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                            @endif
                                            {{-- <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span> --}}
                                        </x-nav-link>
                                        {{-- <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index') || request()->routeIs('clients.create') || request()->routeIs('clients.edit')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                            </svg>
                                            
                                            {{ __('Clientes') }}
                                        </x-nav-link> --}}

                                        @php
                                            $teamRoute = in_array('team', $modules)
                                                ? route('members.index')
                                                : route('upgrade.index');
                                        @endphp
                                        <x-nav-link :href="$teamRoute" :active="request()->routeIs('members.index')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                            </svg>
                                            
                                            
                                            {{ __('Equipe') }}
                                            @if(!in_array('team', $modules))
                                                <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                            @endif
                                            {{-- <span class="text-[10px] font-bold bg-[#FF914D] text-[#33363B] px-1.5 py-0.5 rounded-md">Em breve</span> --}}
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
                                @if (Auth::user()->role == 'partner')

                                    @php
                                        $categoriesRoute = in_array('categories', $modules)
                                            ? route('categories.index')
                                            : route('upgrade.index');
                                    @endphp
                                    <x-nav-link :href="$categoriesRoute" :active="(request()->routeIs('categories.index') || request()->routeIs('categories.create') || request()->routeIs('categories.edit'))">
                                        
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="white" d="M296.5 69.2C311.4 62.3 328.6 62.3 343.5 69.2L562.1 170.2C570.6 174.1 576 182.6 576 192C576 201.4 570.6 209.9 562.1 213.8L343.5 314.8C328.6 321.7 311.4 321.7 296.5 314.8L77.9 213.8C69.4 209.8 64 201.3 64 192C64 182.7 69.4 174.1 77.9 170.2L296.5 69.2zM112.1 282.4L276.4 358.3C304.1 371.1 336 371.1 363.7 358.3L528 282.4L562.1 298.2C570.6 302.1 576 310.6 576 320C576 329.4 570.6 337.9 562.1 341.8L343.5 442.8C328.6 449.7 311.4 449.7 296.5 442.8L77.9 341.8C69.4 337.8 64 329.3 64 320C64 310.7 69.4 302.1 77.9 298.2L112 282.4zM77.9 426.2L112 410.4L276.3 486.3C304 499.1 335.9 499.1 363.6 486.3L527.9 410.4L562 426.2C570.5 430.1 575.9 438.6 575.9 448C575.9 457.4 570.5 465.9 562 469.8L343.4 570.8C328.5 577.7 311.3 577.7 296.4 570.8L77.9 469.8C69.4 465.8 64 457.3 64 448C64 438.7 69.4 430.1 77.9 426.2z"/></svg>
                                        
                                        {{ __('Categorias') }}
                                        @if(!in_array('categories', $modules))
                                            <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                        @endif
                                    </x-nav-link>

                                    @php
                                        $brandsRoute = in_array('brands', $modules)
                                            ? route('brands.index')
                                            : route('upgrade.index');
                                    @endphp
                                    <x-nav-link :href="$brandsRoute" :active="(request()->routeIs('brands.index') || request()->routeIs('brands.create') || request()->routeIs('brands.edit'))">
                                        
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path fill="rgb(255, 255, 255)" d="M341.5 45.1C337.4 37.1 329.1 32 320.1 32C311.1 32 302.8 37.1 298.7 45.1L225.1 189.3L65.2 214.7C56.3 216.1 48.9 222.4 46.1 231C43.3 239.6 45.6 249 51.9 255.4L166.3 369.9L141.1 529.8C139.7 538.7 143.4 547.7 150.7 553C158 558.3 167.6 559.1 175.7 555L320.1 481.6L464.4 555C472.4 559.1 482.1 558.3 489.4 553C496.7 547.7 500.4 538.8 499 529.8L473.7 369.9L588.1 255.4C594.5 249 596.7 239.6 593.9 231C591.1 222.4 583.8 216.1 574.8 214.7L415 189.3L341.5 45.1z"/></svg>
                                        {{ __('Marcas') }}
                                        @if(!in_array('brands', $modules))
                                            <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                        @endif
                                    </x-nav-link>

                                    @php
                                        $vehiclesRoute = in_array('vehicles', $modules)
                                            ? route('products.index')
                                            : route('upgrade.index');
                                    @endphp
                                    <x-nav-link :href="$vehiclesRoute" :active="(request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.edit'))">
                                        
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path fill="rgb(255, 255, 255)" d="M112 208C138.5 208 160 186.5 160 160C160 133.5 138.5 112 112 112C85.5 112 64 133.5 64 160C64 186.5 85.5 208 112 208zM256 128C238.3 128 224 142.3 224 160C224 177.7 238.3 192 256 192L544 192C561.7 192 576 177.7 576 160C576 142.3 561.7 128 544 128L256 128zM256 288C238.3 288 224 302.3 224 320C224 337.7 238.3 352 256 352L544 352C561.7 352 576 337.7 576 320C576 302.3 561.7 288 544 288L256 288zM256 448C238.3 448 224 462.3 224 480C224 497.7 238.3 512 256 512L544 512C561.7 512 576 497.7 576 480C576 462.3 561.7 448 544 448L256 448zM112 528C138.5 528 160 506.5 160 480C160 453.5 138.5 432 112 432C85.5 432 64 453.5 64 480C64 506.5 85.5 528 112 528zM160 320C160 293.5 138.5 272 112 272C85.5 272 64 293.5 64 320C64 346.5 85.5 368 112 368C138.5 368 160 346.5 160 320z"/></svg>
                                        
                                        {{ __('Produtos') }}
                                        @if(!in_array('vehicles', $modules))
                                            <span class="text-[10px] font-bold bg-[#6A2BBA] text-white px-1.5 py-0.5 rounded-md">Upgrade</span>
                                        @endif
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
</div>

