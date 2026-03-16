@extends('layouts.app')

@section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                {{ __('Dashboard') }}
            </h2>

            @if($user->first_login == 1 && $store->configured_store == 0)
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <div class="flex items">
                        <div class="ml-3">
                            <p class="font-bold">Bem-vindo(a) ao sistema!</p>
                            <p class="text-sm">Você está no seu primeiro acesso. Para começar a usar o sistema, clique no botão abaixo para configurar sua loja.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button id="configuredStore" data-storeid="{{$store->id}}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                            Configurar loja
                        </button>
                    </div>
                </div>
            @elseif($categoriesByStore->isEmpty())
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                    <div class="flex items">
                        <div class="ml-3">
                            <p class="font-bold">Vamos para o próximo passo!</p>
                            <p class="text-sm">Cadastre uma ou mais categorias para seus veículos como por exemplo: Carros, Motos etc.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{route('categories.create')}}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Cadastrar categoria
                        </a>
                    </div>
                </div>
            @elseif($subcategoriesByStore->isEmpty())
                <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 mb-4" role="alert">
                    <div class="flex items">
                        <div class="ml-3">
                            <p class="font-bold">Cadastrar Marca de Veículos!</p>
                            <p class="text-sm">Cadastre uma ou mais marcas para seus veículos como por exemplo: Honda, Toyota etc.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{route('subcategories.create')}}" class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600">
                            Cadastrar marca
                        </a>
                    </div>
                </div>
            @elseif($quantityStockVehicles <= 0)
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4" role="alert">
                    <div class="flex items">
                        <div class="ml-3">
                            <p class="font-bold">Finalizando nosso Tour!</p>
                            <p class="text-sm">Cadastre seu primeiro veículo para que seus clientes possam ver no seu catálogo online.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{route('products.create')}}" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">
                            Cadastrar veículo
                        </a>
                    </div>
                </div>
            @endif


            <div class="rounded-md gap-2 grid grid-cols-2 md:grid-cols-4 mt-4">
                <div class="bg-white w-full h-36 rounded-xl shadow-sm text-center py-2">
                    <div class="font-bold text-gray-500">
                        Veículos no estoque
                    </div>
                    <div class="mt-3 flex justify-center">
                        <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M500 176h-59.9l-16.6-41.6C406.4 91.6 365.6 64 319.5 64h-127c-46.1 0-86.9 27.6-104 70.4L71.9 176H12C4.2 176-1.5 183.3 .4 190.9l6 24C7.7 220.3 12.5 224 18 224h20.1C24.7 235.7 16 252.8 16 272v48c0 16.1 6.2 30.7 16 41.9V416c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h256v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-54.1c9.8-11.3 16-25.8 16-41.9v-48c0-19.2-8.7-36.3-22.1-48H494c5.5 0 10.3-3.8 11.6-9.1l6-24c1.9-7.6-3.8-14.9-11.7-14.9zm-352.1-17.8c7.3-18.2 24.9-30.2 44.6-30.2h127c19.6 0 37.3 12 44.6 30.2L384 208H128l19.9-49.8zM96 319.8c-19.2 0-32-12.8-32-31.9S76.8 256 96 256s48 28.7 48 47.9-28.8 16-48 16zm320 0c-19.2 0-48 3.2-48-16S396.8 256 416 256s32 12.8 32 31.9-12.8 31.9-32 31.9z"/></svg>
                    </div>
                    <div class="mt-3 text-2xl font-semibold">
                        {{$quantityStockVehicles}}
                    </div>
                </div>
                <div class="bg-white w-full h-36 rounded-xl shadow-sm text-center py-2">
                    <div class="font-bold text-gray-500">
                        Vendas
                    </div>
                    <div class="mt-3 flex justify-center">
                        <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M352 288h-16v-88c0-4.4-3.6-8-8-8h-13.6c-4.7 0-9.4 1.4-13.3 4l-15.3 10.2a8 8 0 0 0 -2.2 11.1l8.9 13.3a8 8 0 0 0 11.1 2.2l.5-.3V288h-16c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h64c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zM608 64H32C14.3 64 0 78.3 0 96v320c0 17.7 14.3 32 32 32h576c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zM48 400v-64c35.4 0 64 28.7 64 64H48zm0-224v-64h64c0 35.4-28.7 64-64 64zm272 192c-53 0-96-50.2-96-112 0-61.9 43-112 96-112s96 50.1 96 112c0 61.9-43 112-96 112zm272 32h-64c0-35.4 28.7-64 64-64v64zm0-224c-35.4 0-64-28.7-64-64h64v64z"/></svg>
                    </div>
                    <div class="mt-3 text-2xl font-semibold">
                        {{$quantitySales}}
                    </div>  
                </div>
                <div class="bg-white h-36 rounded-xl shadow-sm text-center py-2">
                    <div class="font-bold text-gray-500">
                        Solicitações
                    </div>
                    <div class="mt-4 flex justify-center">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M336 0H48C21.5 0 0 21.5 0 48v464l192-112 192 112V48c0-26.5-21.5-48-48-48zm0 428.4l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.3 0 6 2.7 6 6V428.4z"/></svg>
                    </div>
                    <div class="mt-3 text-2xl font-semibold">
                        {{$quantityRequests}}
                    </div>
                </div>
                <div class="bg-white h-36 rounded-xl shadow-sm text-center py-2">
                    <div class="font-bold text-gray-500">
                        Clientes
                    </div>
                    <div class="mt-4 flex justify-center">
                        <svg class="w-8 h-8 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                        </svg>                          
                    </div>
                    <div class="mt-3 text-2xl font-semibold">
                        {{$quantityClients}}
                    </div>
                </div>
            </div>


            <div class="mt-3 grid grid-cols-1 md:grid-cols-2">
                <div class="md:w-[95%]">
                    <div class="flex flex-col gap mb-2 px-2">
                        <div class="flex gap-2 items-center">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1 .9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9 .7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"/></svg>
                            <span class="font-semibold">Últimas solicitações</span>
                        </div>
                        {{-- <span class="text-xs">Use para conversar com os usuários do sistema da sua loja</span> --}}
                        <span class="text-xs">Acompanhe as últimas solicitações na sua loja.</span>
                    </div>
                    

                    <div class="bg-white mt-4 rounded-md p-2 gap-2 flex flex-col">

                        @if($requestsByStore->count() == 0)
                            <div class="text-center text-gray-500">
                                Não há solicitações no momento
                            </div>
                        @endif

                        @foreach($requestsByStore as $request)
                            <div class="bg-gray-100 h-1/4 rounded-md p-2 shadow-sm">
                                <div class="flex gap-1 justify-between items-center">
                                    <div class="flex gap-1">
                                        @if($request->shift == 1)
                                            <div class="px-2 text-xs rounded-md font-semibold bg-sky-500 text-white">
                                                Troca
                                            </div>
                                        @else
                                            <div class="px-2 text-xs rounded-md font-semibold bg-green-500 text-white">
                                                Compra
                                            </div>
                                        @endif

                                        @if($request->product->type == 'consigned')
                                            <div class="bg-gray-700 font-medium text-white text-xs px-2 rounded">
                                                Consignado
                                            </div>
                                        @else
                                            <div class="bg-gray-700 font-medium text-white text-xs px-2 rounded">
                                                Próprio
                                            </div>
                                        @endif
                                        
                                    </div>

                                    <div class="font-medium text-black text-xs px-1 rounded">
                                        {{ $request->created_at->format('d/m/Y - H:i') }}
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <div class="mt-2">   
                                        <div class="font-semibold text-md text-blue-700">
                                           {{$request->product->name}}
                                        </div> 
                                        <div class="font-semibold text-md italic text-gray-700">
                                            {{$request->product->subcategory->name}}
                                        </div>                               
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-2 md:mt-0">
        
                    <div class="bg-white rounded-md p-2 flex flex-col">
                        <div class="w-full text-center font-semibold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/>
                              </svg>
                            <div>
                                Cadastros recentes
                            </div>
                        </div>

                        <div class="mt-2">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col" style="width: 50%">Veículo</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Ano</th>
                                  </tr>
                                </thead>
                                <tbody>

                                    @if($latestProducts->count() == 0)
                                        <tr>
                                            <td colspan="3" class="text-center">Nenhum veículo cadastrado</td>
                                        </tr>
                                    @endif
                                    
                                    @foreach($latestProducts as $latestProduct)
                                        <tr>
                                            <td>{{$latestProduct->name}}</td>
                                            <td>{{$latestProduct->properties->license_plate}}</td>
                                            <td>{{$latestProduct->properties->year_of_manufacture}}</td>
                                        </tr>
                                    @endforeach
                                  
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
        <!-- Blade View -->

    </div>

    <script>
        $('#configuredStore').click(function(e) {
            e.preventDefault();

            console.log('teste')

            let storeId = this.dataset.storeid;
            
            $.ajax({
                url: `/configured-store/${storeId}`,
                method: 'POST',
                data: {
                    configured_store: 1
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoader();
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.status);
                    hideLoader();
                }
            }).always(function() {
                window.location.href = '{{ route('store.edit') }}';
            })
        })
    </script>

    <style>
        /* .box-message {
            clip-path: polygon(1% 25%, 0 20%, 1% 15%, 1% 0%, 100% 0%, 100% 100%, 75% 100%, 50% 100%, 1% 100%);
        } */
    </style>
@endsection
