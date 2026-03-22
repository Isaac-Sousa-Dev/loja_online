<div class="bg-gray-100">  
    
    <div class="py-2 bg-white flex items-center px-20 justify-between shadow-sm mb-2">

        <div class="flex gap-60 items-center">
            <div class="flex gap-2 items-center">
                <div>
                    <label for="logo" class="">
                        <div id="div-logo" class="bg-gray-300 flex items-center justify-center border-2 border-white relative w-14 rounded-full h-14">
                            @if($logoStore != null && $logoStore != '/storage/')
                                <img width="30" id="logoStorePreview" accept="image/*" src="{{ $logoStore }}" class="rounded-full w-full h-full bg-gray-300 object-cover"> 
                            @else
                                <img id="logoStorePreview" src="/img/logos/logo.png" class="rounded-full hidden w-full h-full cursor-pointer bg-gray-300 "> 
                            @endif
                        </div>
                    </label>
                </div>
                <div>
                    <h1 class="text-black font-bold text-lg">{{ $store->store_name}}</h1>
                    @if ($itsOpen)
                        <div class="text-green-400 text-sm font-bold flex items-center gap-1 justify-center">
                            <span class="bg-green-400 h-2 w-2 rounded-full mt-[1px]"></span>Aberto agora
                        </div>
                    @else
                        <div class="text-red-400 text-xs font-bold gap-1">
                            Fechado agora
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex gap-10 items-center">
                {{-- <div>
                    Comprar
                </div>

                <div>
                    Vender
                </div>

                <div>
                    Financiamento
                </div> --}}
            </div>
        </div>
        

        <div class="bg-red-200 w-96 h-11 rounded-full relative">
            <ion-icon name="search-outline" class="search-icon text-2xl absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></ion-icon>
            <input type="text" class="w-full h-11 rounded-full border border-gray-400 pl-14" placeholder="Busque por marca ou modelo...">
        </div>
    </div>

    <div id="content-body" class="flex px-20 pt-8 h-[calc(100vh-0px)] overflow-hidden">
        <div class="section-left w-1/4 rounded-2xl h-[90%] sticky top-0 overflow-hidden">

            <div class="px-4 bg-white border border-gray-100 py-2 rounded-2xl overflow-y-auto scrollbar-hidden">
                <div class="text-black font-bold text-lg mb-3">
                    Informações da loja
                </div>

                {{-- <div class="div-logo-banner-catalog">
                    <div class="w-full relative flex justify-center items-center">
                        <label for="logo" class="flex justify-center items-center absolute">
                            <div id="div-logo" class="bg-gray-300 flex items-center justify-center border-2 border-white relative w-28 rounded-full h-28">
        
                                @if($logoStore != null && $logoStore != '/storage/')
                                    <img id="logoStorePreview" accept="image/*" src="{{ $logoStore }}" class="rounded-full w-full h-full bg-gray-300 object-cover"> 
                                @else
                                    <img id="logoStorePreview" src="/img/logos/logo.png" class="rounded-full hidden w-full h-full cursor-pointer bg-gray-300 "> 
                                @endif
                            </div>
                        </label>
        
                        
                        <label for="banner-store" class="w-full h-full">
        
                            <div id="div-banner" class="flex justify-center items-center h-full">     
                                @if($bannerStore != null && $bannerStore != '/storage/')
                                    <img id="storeBannerPreview" style="max-height: 140px; width: 100%;" class="object-cover rounded-b-[80px] rounded-t-xl" accept="image/*" src="{{ $bannerStore }}"> 
                                @else
                                <div class="bg-gray-300 h-36 w-full rounded-b-[80px]">
                                    <img id="storeBannerPreview" style="max-height: 140px; width: 100%;" class="hidden bg-red-500 h-96 object-cover object-center" accept="image/*" src="/img/banner.jpg">
                                </div>
                                @endif
        
                            </div>
                        </label>
                    </div>
                </div> --}}
                <div class="flex flex-col rounded-bg-lg mt-3">
                    <div class="py-2 w-full mb-0 bg-slate-50 border rounded-2xl px-2 mt-4">
                        <h5 class="text-black font-bold text-lg">Contato</h5>
                        <p class="text-black text-sm">email: {{ $store->store_email }}</p>
                        <p class="text-black text-sm mask-phone">telefone: {{ $store->store_phone }}</p>
                    </div>

                    <div class="py-2 w-full mb-0 bg-slate-50 border rounded-2xl px-2 mt-4">
                        <h5 class="text-black font-bold text-lg mb-2">Localização</h5>
                        <iframe
                            src="https://www.google.com/maps?q={{ urlencode('Rua Padre Cícero, Planalto Ayrton Senna, 920, Fortaleza, CE') }}&output=embed"
                            width="100%"
                            height="200"
                            class="rounded-2xl"
                            style="border:0"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>

                    <div class="py-2 w-full mb-0 bg-slate-50 border rounded-2xl px-2 mt-4">
                        <h5 class="text-black font-bold text-lg">Horários</h5>
                        <div class="flex flex-col gap-2">
                            <p class="text-black text-sm">Seg à Sex: {{ $store->store_email }}</p>
                            <p class="text-black text-sm mask-phone">Sábado: {{ $store->store_phone }}</p>
                            <p class="text-black text-sm mask-phone">Domingo: {{ $store->store_phone }}</p>
                        </div>
                    </div>
                </div>

                <div id="div-categorias"  class="mt-4 py-2 px-3 flex space-x-3 w-full overflow-x-auto">
                    <div class="flex space-x-3">
                        @if(count($categories) > 1)
                            <div data-categoryId="todos" style="letter-spacing: 1px" class="font-bold w-48 justify-center text-white rounded-full p-1 flex div-categoria bg-blue-600 cursor-pointer">
                                Todos Produtos
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
        <div class="w-3/4">

            <div class="mx-3 rounded-lg mb-4">
                <span class="text-black font-bold">
                    {{$qtdProducts}}
                </span>
                produtos encontrados
            </div>

            <div class="section-center h-[calc(100vh-160px)] overflow-y-auto scrollbar-hidden">
                <div id="list-products-by-category-desktop" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 px-3 gap-2 justify-between flex-wrap mb-10">
                </div>
            </div>

            <div class="mx-3 px-2 py-2 mt-2 bg-white border border-gray-100 rounded-lg mb-4">
                Paginação
            </div>
        </div>

        {{-- <div class="section-right w-1/6 h-full px-3 py-2 bg-white rounded-2xl overflow-y-auto">
            <div class="text-black font-bold text-lg mb-3">
                Filtros
            </div>
        </div> --}}
    </div>
</div>

<style>
    /* .section-right {
        -webkit-box-shadow: inset 3px 3px 12px -9px rgba(163,150,163,1);
-moz-box-shadow: inset 3px 3px 12px -9px rgba(163,150,163,1);
box-shadow: inset 3px 3px 12px -9px rgba(163,150,163,1);
    } */

    .scrollbar-hidden::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Edge */
    }

    #div-categorias::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Edge */
    }
</style>