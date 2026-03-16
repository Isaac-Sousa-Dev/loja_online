<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div>

                <div class="flex justify-between items-center mt-4">
                    <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                        {{ __('Vendas') }}
                    </h2>
                </div>

                <div class="overflow-auto md:overflow-hidden rounded-lg mt-3">

                    <div class="flex flex-col items-center justify-center text-center p-8 bg-white rounded-lg border border-dashed border-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-2.209 0-4 1.791-4 4v3h8v-3c0-2.209-1.791-4-4-4zM4 20h16" />
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-700 mb-2">Em Desenvolvimento</h2>
                        <p class="text-gray-500 text-lg">Estamos trabalhando para trazer esta funcionalidade em breve para você!</p>
                    </div>
                    
                </div>

                {{-- <div class="mt-3 mb-2">
            
                    <div class="grid  w-full grid-cols-1 md:grid-cols-3 rounded-xl">

                        @if($salesByStore->isEmpty())
                            <div class="shadow-sm rounded-xl h-32 py-2 border bg-white border-gray-300 mt-3 md:mr-1">
                                <div class="bg-slate-100 px-2 font-semibold flex justify-between">
                                    <div class="text-xs">
                                        Domingo, 27 de abril de 2025
                                    </div>

                                    <div class="text-xs">
                                        N° 200
                                    </div>
                                </div>

                                <div class="px-2 text-sm mt-2 flex justify-between items-center">
                                    <div>
                                        <div class="text-lg font-semibold">
                                            Isaac Freitas de Sousa
                                        </div>
                                        <span class="text-sm">
                                            isaac.sousa.1202@gmail.com
                                        </span>
                                    </div>

                                    <button class="bg-info px-2 py-1 text-white rounded-md font-semibold">
                                        Ver detalhes
                                    </button>
                                </div>

                                <div class="px-2 mt-1 flex justify-between">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-6 h-6 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                                        </svg>
                                        <span class="text-xs bg-purple-500 px-2 py-1 rounded-md text-white font-semibold">
                                            Financiamento
                                        </span>                                          
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="text-gray-800 text-xs">
                                            Valor total
                                        </div>
                                        <div class="font-medium">
                                            R$ 23.000,00
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                    </div>

                </div> --}}
            </div>
        </div>
    </div>
    @endsection

</x-app-layout>