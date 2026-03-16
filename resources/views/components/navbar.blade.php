<div class="flex items-center justify-between w-full md:mx-4">
    <!-- Left (toggle + title) -->
    <div class="flex items-center gap-3">
        <button id="toggleSidebar" class="md:hidden p-2 text-gray-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-xl font-semibold text-gray-700">Painel</h1>
    </div>

    <!-- Right (user info) -->
    <div class="flex items-center gap-4">

        <div class="flex items-center gap-2">
            <div class="float-end">
                <div>
                    {{ Auth::user()->name }}
                </div>
                <div class="text-xs">
                    {{Auth::user()->email}}
                </div>
            </div>
            <!-- Botão de ativação do dropdown -->
           <button id="menu-button" class="flex items-center gap-2 focus:outline-none">
               <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" class="w-10 h-10 rounded-full border-2 border-gray-300" alt="Avatar">
           </button>
        </div>


        <div id="dropdown-menu-profile" class="absolute right-0 z-10 mt-44 w-44 mr-5 origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
            <a href="/my-store-page" class="px-4 flex items-center gap-1 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">
                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12c.263 0 .524-.06.767-.175a2 2 0 0 0 .65-.491c.186-.21.333-.46.433-.734.1-.274.15-.568.15-.864a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 12 9.736a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 16 9.736c0 .295.052.588.152.861s.248.521.434.73a2 2 0 0 0 .649.488 1.809 1.809 0 0 0 1.53 0 2.03 2.03 0 0 0 .65-.488c.185-.209.332-.457.433-.73.1-.273.152-.566.152-.861 0-.974-1.108-3.85-1.618-5.121A.983.983 0 0 0 17.466 4H6.456a.986.986 0 0 0-.93.645C5.045 5.962 4 8.905 4 9.736c.023.59.241 1.148.611 1.567.37.418.865.667 1.389.697Zm0 0c.328 0 .651-.091.94-.266A2.1 2.1 0 0 0 7.66 11h.681a2.1 2.1 0 0 0 .718.734c.29.175.613.266.942.266.328 0 .651-.091.94-.266.29-.174.537-.427.719-.734h.681a2.1 2.1 0 0 0 .719.734c.289.175.612.266.94.266.329 0 .652-.091.942-.266.29-.174.536-.427.718-.734h.681c.183.307.43.56.719.734.29.174.613.266.941.266a1.819 1.819 0 0 0 1.06-.351M6 12a1.766 1.766 0 0 1-1.163-.476M5 12v7a1 1 0 0 0 1 1h2v-5h3v5h7a1 1 0 0 0 1-1v-7m-5 3v2h2v-2h-2Z"/>
                </svg>  
                <span class="text-md font-semibold">Minha loja</span>                                
            </a>
            <a href="/profile" class="px-4 flex items-center gap-1 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">
                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>  
                <span class="text-md font-semibold">Meu perfil</span>                                
            </a>
            <form method="POST" action="{{ route('logout') }}" role="none">
                @csrf
                <button type="submit" class="px-4 flex items-center gap-1 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-3">
                    <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                    </svg>
                    <span class="text-md font-semibold">Sair</span> 
                </button>
            </form>
            </div>
        </div>

        <script>
                        $(document).ready(function () {
                            const menuButton = $('#menu-button');
                            const dropdownMenu = $('#dropdown-menu-profile');

                            // Abre ou fecha o menu ao clicar no botão
                            menuButton.on('click', function (e) {
                                e.stopPropagation(); // Impede que o clique seja propagado para o documento
                                dropdownMenu.toggleClass('hidden');
                            });

                            // Fecha o menu ao clicar fora dele
                            $(document).on('click', function () {
                                if (!dropdownMenu.hasClass('hidden')) {
                                    dropdownMenu.addClass('hidden');
                                }
                            });
                        });
                    </script>
        {{-- <span class="text-sm text-gray-600 hidden md:block">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-red-600 hover:underline text-sm px-2">Sair</button>
        </form> --}}
    </div>
</div>
