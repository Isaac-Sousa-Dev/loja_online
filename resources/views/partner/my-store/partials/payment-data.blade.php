<div class="container bg-white p-3 rounded-xl">
    <h2 class="font-semibold">Forma de Pagamento</h2>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}


    <div class="grid grid-cols-2 gap-2">
        <div class="bg-white border-1 border-gray-500 mt-2 gap-2 px-4 py-2 rounded-lg">
            <div class="font-semibold text-blue-700 text-xl">
                Cartão
            </div>
            <div class="flex justify-between items-center">
                <div class="font-semibold text-gray-700">
                    **** 0816
                </div>
                <div>
                    <img src="/img/cards-flags/mastercard.png" width="50" height="10" alt="">
                </div>
            </div>
        </div>
        <div class="bg-gray-200 flex mt-2 gap-2 px-4 p-2 border-1 hover:border-gray-800 rounded-lg cursor-pointer hover:bg-gray-300 duration-300 ease-in-out transition-all" >
            <div class="">
                <div class="font-semibold text-gray-700 text-sm">
                    Pagar com 
                </div>
                <div class="font-semibold text-gray-900 text-2xl">
                    PIX
                </div>
            </div>
 
            <div class="flex items-center justify-center ">
                <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.6 488.6C280.3 518.1 231.1 518.1 200.8 488.6L103.3 391.2H112.6C132.6 391.2 151.5 383.4 165.7 369.2L242.4 292.5zM262.5 218.9C256.1 224.4 247.9 224.5 242.4 218.9L165.7 142.2C151.5 127.1 132.6 120.2 112.6 120.2H103.3L200.7 22.8C231.1-7.6 280.3-7.6 310.6 22.8L407.8 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112.6 142.7C126.4 142.7 139.1 148.3 149.7 158.1L226.4 234.8C233.6 241.1 243 245.6 252.5 245.6C261.9 245.6 271.3 241.1 278.5 234.8L355.5 157.8C365.3 148.1 378.8 142.5 392.6 142.5H430.3L488.6 200.8C518.9 231.1 518.9 280.3 488.6 310.6L430.3 368.9H392.6C378.8 368.9 365.3 363.3 355.5 353.5L278.5 276.5C264.6 262.6 240.3 262.6 226.4 276.6L149.7 353.2C139.1 363 126.4 368.6 112.6 368.6H80.8L22.8 310.6C-7.6 280.3-7.6 231.1 22.8 200.8L80.8 142.7H112.6z"/></svg>                  
            </div>
        </div>
    </div>

    <div class="mt-2 mb-1">
        <div class="text-sm flex gap-1">
            <svg class="w-6 h-6 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>          
            <span class="mt-[2px] text-gray-600 font-medium">A cobrança será feita de forma recorrente no cartão cadastrado ou de imediato no Pix.</span>
        </div>
    </div>

    {{-- <div class="mt-3">
        <button class="btn bg-primary text-white rounded-xl px-4 py-2 font-semibold">Alterar</button>
    </div> --}}
</div>
