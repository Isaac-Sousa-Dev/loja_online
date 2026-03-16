<div class="bg-slate-50 h-screen">
    <div class="flex md:justify-center">
        <div class="h-[300px] pb-2 md:h-[330px] flex flex-col justify-between relative w-full bg-white rounded-b-[60px] md:rounded-b-[0px] md:shadow-none shadow-sm">

            <div class="w-[100%] md:w-full md:rounded-b-[80px] flex items-center justify-center">
    
                @if($bannerStore != null)
                    <div class="object-cover h-full w-full rounded-b-[100px] md:flex md:justify-center border-4 border-t-0 border-white">
                        <img src="{{$bannerStore}}" alt="" class="object-cover object-center md:h-[200px] md:max-w-[900px] md:min-w-[900px] h-[130px] w-full rounded-b-[100px]">
                    </div>
                @else
                    <div class="object-cover bg-gradient-to-r from-blue-700 to-blue-500 h-full w-full rounded-b-[100px] border-4 border-t-0 border-white">
                        <div class="object-cover object-center md:h-[200px] md:max-w-[900px] md:min-w-[900px] h-[130px] w-full rounded-b-[100px]"></div>
                    </div>
                @endif

                @if ($logoStore != null)
                    <div class="bg-slate-50 mt-24 md:mt-8 w-32 h-32 md:w-36 md:h-36 rounded-full flex justify-center items-center absolute" style="border: 4px solid white; overflow: hidden;">
                        <img src="{{ $logoStore }}" class="object-cover object-center w-full h-full" alt="Logo">
                    </div>
                @else
                    <div class="bg-white mt-24 md:mt-8 w-32 h-32 md:w-36 md:h-36 rounded-full flex justify-center items-center absolute" style="border: 4px solid rgb(239, 235, 235); overflow: hidden;">
                        <img src="/img/logo.png" class="object-cover" alt="">
                    </div>
                @endif
            </div>

            <div class=" mx-2 flex justify-center flex-col rounded-bg-lg">
                <div class="h-28 text-center w-full mb-0">
                    <h5 class="mt-2 text-black font-bold text-xl">{{ $store->store_name}}</h5>

                    <p class="text-black text-xs md:text-md">{{ $store->store_email }}</p>
                    {{-- <p class="text-black text-sm mask-phone">{{ $store->store_phone }}</p> --}}

                    @if ($itsOpen)
                        <div class="text-green-400 text-sm font-bold flex items-center gap-1 justify-center">
                            <span class="bg-green-400 h-2 w-2 rounded-full mt-[1px]"></span>Aberto agora
                        </div>
                    @else
                        <div class="text-red-400 text-sm font-bold flex items-center gap-1 justify-center mt-1">
                            Fechado agora
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="flex flex-col md:flex-row md:h-[calc(100%-330px)]">
        <div class="md:bg-slate-100">
            <div class="px-3 mt-4 md:mt-5 flex items-center justify-between h-12">
                <div class="w-full space-x-2 flex justify-end items-center relative">
                    <input type="text" class="inputSearchCatalog w-full h-11 rounded-full border border-gray-400 pl-7" placeholder="Pesquise aqui...">
                    <svg class="w-6 h-6 search-icon text-2xl absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg> 
                </div>
            </div>
            <div id="div-categorias" class="mt-4 py-2 px-3 flex space-x-3 w-full overflow-x-auto">
                <div class="flex space-x-3">
                    @if(count($categories) > 1)
                        <div data-categoryId="todos" style="letter-spacing: 1px" class="font-bold w-24 justify-center text-white rounded-full p-1 flex div-categoria bg-blue-600 cursor-pointer">
                            Todos
                        </div>
                    @endif
                    @foreach ($categories as $storeCategory)
                    <div style="letter-spacing: 1px" class="font-bold text-white w-24 justify-center rounded-full p-1 flex div-categoria bg-blue-600 cursor-pointer" data-categoryId="{{ $storeCategory->category->id }}">
                        {{ $storeCategory->category->name }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="list-products-by-category" class="grid grid-cols-2 px-3 gap-2 justify-between flex-wrap mt-3 mb-10">
        </div>
    </div>

</div>

<style>
    .search-icon {
        position: absolute;
        right: ;: 10px; /* Ajuste a posição horizontal */
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none; /* Para evitar que o ícone interfira na interação com o input */
    }

    .active {
        border-bottom: 1px solid black;
    }

    .active-category {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    }
</style>
