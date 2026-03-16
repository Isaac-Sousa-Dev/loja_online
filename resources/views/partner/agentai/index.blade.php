<x-app-layout>

    @section('content')
    <div class="flex md:justify-center">
        <div class="ml-3 mr-3 flex w-full md:w-[900px]">
            <div class="w-full md:min-w-[900px] max-w-[900px]">

                <div class="flex flex-col md:justify-between mt-4">

                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                            {{ __('Agente IA') }}
                        </h2>
                    </div>

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

            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
