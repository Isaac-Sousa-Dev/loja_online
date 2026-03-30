<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            <button class="btn-info p-1 rounded-md" id="generatePix">Gerar Pix</button>
            <div id="pixQrCode"></div>

            <script>
            document.getElementById('generatePix').addEventListener('click', async () => {
                const response = await fetch('/pix/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ amount: 100.50 })
                });
                
                const data = await response.json();
                document.getElementById('pixQrCode').innerHTML = 
                    `<img src="${data.qrcode}" alt="QR Code Pix">`;
            });
            </script>

            <h2 class="font-display font-semibold text-2xl mb-3 mt-3 text-[#33363B]">
                {{ __('Dashboard') }}
            </h2>

            <div class="rounded-md gap-2 grid grid-cols-2 md:grid-cols-4">
                <div class="bg-white w-full h-36 rounded-xl shadow-sm border border-[#33363B]/8 text-center py-2">
                    <div class="font-bold text-[#33363B]/55">
                        Motivados
                    </div>
                    <div class="mt-3 flex justify-center">
                        <svg class="w-10 h-10 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-2xl font-semibold text-[#33363B]">
                        {{$quantityPartners}}
                    </div>
                </div>
                <div class="bg-white w-full h-36 rounded-xl shadow-sm border border-[#33363B]/8 text-center py-2">
                    <div class="font-bold text-[#33363B]/55">
                        Armazenamento
                    </div>
                    <div class="mt-3 flex justify-center">
                        <svg class="w-10 h-10 text-[#D131A3]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 7.205c4.418 0 8-1.165 8-2.602C20 3.165 16.418 2 12 2S4 3.165 4 4.603c0 1.437 3.582 2.602 8 2.602ZM12 22c4.963 0 8-1.686 8-2.603v-4.404c-.052.032-.112.06-.165.09a7.75 7.75 0 0 1-.745.387c-.193.088-.394.173-.6.253-.063.024-.124.05-.189.073a18.934 18.934 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.073a10.143 10.143 0 0 1-.852-.373 7.75 7.75 0 0 1-.493-.267c-.053-.03-.113-.058-.165-.09v4.404C4 20.315 7.037 22 12 22Zm7.09-13.928a9.91 9.91 0 0 1-.6.253c-.063.025-.124.05-.189.074a18.935 18.935 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.074a10.163 10.163 0 0 1-.852-.372 7.816 7.816 0 0 1-.493-.268c-.055-.03-.115-.058-.167-.09V12c0 .917 3.037 2.603 8 2.603s8-1.686 8-2.603V7.596c-.052.031-.112.059-.165.09a7.816 7.816 0 0 1-.745.386Z"/>
                        </svg>                          
                    </div>
                    <div class="mt-3 text-2xl font-semibold text-[#33363B]">
                        20%
                    </div>
                </div>
                <div class="bg-white h-36 rounded-xl shadow-sm border border-[#33363B]/8 text-center py-2">
                    <div class="font-bold text-[#33363B]/55">
                        Chamados
                    </div>
                    <div class="mt-4 flex justify-center text-[#FF914D]">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M336 0H48C21.5 0 0 21.5 0 48v464l192-112 192 112V48c0-26.5-21.5-48-48-48zm0 428.4l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.3 0 6 2.7 6 6V428.4z"/></svg>
                    </div>
                    <div class="mt-3 text-2xl font-semibold text-[#33363B]">
                        3
                    </div>
                </div>
                <div class="bg-white h-36 rounded-xl shadow-sm border border-[#33363B]/8 text-center py-2">
                    <div class="font-bold text-[#33363B]/55">
                        Saldo no mês
                    </div>
                    <div class="mt-4 flex justify-center text-[#33363B]">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M461.2 128H80c-8.8 0-16-7.2-16-16s7.2-16 16-16h384c8.8 0 16-7.2 16-16 0-26.5-21.5-48-48-48H64C28.7 32 0 60.7 0 96v320c0 35.4 28.7 64 64 64h397.2c28 0 50.8-21.5 50.8-48V176c0-26.5-22.8-48-50.8-48zM416 336c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
                    </div>
                    <div class="mt-3 text-2xl font-semibold text-[#33363B]">
                        R$ 20.235,00
                    </div>
                </div>
            </div>


            <div class="mt-3 grid grid-cols-1 md:grid-cols-2">
                <div class="md:w-[95%]">
                    <div class="flex flex-col gap mb-2 px-2">
                        <div class="flex gap-2 items-center">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1 .9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9 .7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"/></svg>
                            <span class="font-semibold text-[#33363B]">Último chamados</span>
                        </div>
                        {{-- <span class="text-xs">Use para conversar com os usuários do sistema da sua loja</span> --}}
                        <span class="text-xs">Acompanhe os últimos chamados.</span>
                    </div>
                    

                    <div class="bg-white mt-4 rounded-xl border border-[#33363B]/8 h-[400px] p-2 gap-2 flex flex-col">

                        <div class="bg-gray-100 h-1/4 rounded-md p-2 shadow-sm">
                            <div class="flex gap-1 justify-between items-center">
                                <div class="flex gap-1">
                                    <div class="bg-green-500 font-medium text-white text-xs px-1 rounded">
                                        Em aberto
                                    </div>
                                    <div class="bg-gray-700 font-medium text-white text-xs px-1 rounded">
                                        Melhoria
                                    </div>
                                </div>

                                <div class="font-medium text-black text-xs px-1 rounded">
                                    30/01 - 14:30
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <div class="mt-1">   
                                    <div class="font-semibold text-md text-[#6A2BBA]">
                                        Sistema muito fácil de usar
                                    </div> 
                                    <div class="font-semibold text-xs italic text-gray-700">
                                        Mas acredito que seja preciso melhorar o layout da página inicial
                                    </div>                               
                                </div>

                            </div>

                        </div>

                        <div class="bg-gray-100 h-1/4 rounded-md p-2 shadow-sm">
                            <div class="flex gap-1 justify-between items-center">
                                <div class="flex gap-1">
                                    <div class="bg-sky-500 font-medium text-white text-xs px-1 rounded">
                                        Atendido
                                    </div>
                                    <div class="bg-gray-700 font-medium text-white text-xs px-1 rounded">
                                        Melhoria
                                    </div>
                                </div>

                                <div class="font-medium text-black text-xs px-1 rounded">
                                    
                                    29/01 - 11:45
                                </div>
                            </div>

                            <div class="mt-1">
                                <div class="font-semibold text-md text-[#6A2BBA]">
                                    Sistema muito fácil de usar
                                </div>

                                <div class="font-semibold text-xs italic text-gray-700">
                                    Mas acredito que seja preciso melhorar o layout da página inicial
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-100 h-1/4 rounded-md p-2 shadow-sm">
                            <div class="flex gap-1 justify-between items-center">
                                <div class="flex gap-1">
                                    <div class="bg-sky-500 font-medium text-white text-xs px-1 rounded">
                                        Atendido
                                    </div>
                                    <div class="bg-gray-700 font-medium text-white text-xs px-1 rounded">
                                        Melhoria
                                    </div>
                                </div>

                                <div class="font-medium text-black text-xs px-1 rounded">
                                    
                                    29/01 - 11:45
                                </div>
                            </div>

                            <div class="mt-1">
                                <div class="font-semibold text-md text-[#6A2BBA]">
                                    Sistema muito fácil de usar
                                </div>

                                <div class="font-semibold text-xs italic text-gray-700">
                                    Mas acredito que seja preciso melhorar o layout da página inicial
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-100 h-1/4 rounded-md p-2 shadow-sm">
                            <div class="flex gap-1 justify-between items-center">
                                <div class="flex gap-1">
                                    <div class="bg-sky-500 font-medium text-white text-xs px-1 rounded">
                                        Atendido
                                    </div>
                                    <div class="bg-gray-700 font-medium text-white text-xs px-1 rounded">
                                       Melhoria
                                    </div>
                                </div>

                                <div class="font-medium text-black text-xs px-1 rounded">
                                    
                                    29/01 - 11:45
                                </div>
                            </div>

                            <div class="mt-1">
                                <div class="font-semibold text-md text-[#6A2BBA]">
                                    Sistema muito fácil de usar
                                </div>

                                <div class="font-semibold text-xs italic text-gray-700">
                                    Mas acredito que seja preciso melhorar o layout da página inicial
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-2 md:mt-0">

                    <div class="bg-white rounded-xl border border-[#33363B]/8 p-2 flex flex-col">
                        <div class="w-full text-center font-semibold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/>
                              </svg>
                            <div>
                                Motivados recentes
                            </div>
                        </div>

                        <div class="mt-2">
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col" style="width: 50%">Nome</th>
                                    <th scope="col">Telefone</th>
                                    <th scope="col">Plano</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($allPartners as $user)
                                        <tr>
                                            <td class="text-sm">{{$user->name}}</td>
                                            <td class="text-sm">{{$user->phone}}</td>
                                            <td class="text-sm">{{$user->partner->subscription->plan->name}}</td>
                                        </tr>
                                    @endforeach
                                  
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endsection
    
</x-app-layout>
