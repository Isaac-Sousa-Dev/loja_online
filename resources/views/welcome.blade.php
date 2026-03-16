<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Motiv</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7537204439684763"
     crossorigin="anonymous"></script>

        <script>
            $(document).ready(function() {
                $('.price-mask').mask('000.000.000.000.000,00', {reverse: true});
                $('.year-manufacturer-mask').mask('0000/0000');
                $('.phone-mask').mask('(00) 00000-0000');
                $('.cep-mask').mask('00000-000');
                $('.cpf-cnpj-mask').mask('00.000.000/0000-00', { // Máscara inicial genérica para CPF/CNPJ
                    onKeyPress: function(value, e, field, options) {
                        // Remove todos os caracteres não numéricos
                        var length = value.replace(/\D/g, '').length;

                        
                        // Escolhe a máscara com base na quantidade de dígitos
                        var mask = (length > 11) ? '00.000.000/0000-00' : '000.000.000-000';
                        
                        // Aplica a máscara dinamicamente
                        field.mask(mask, options);
                    }
                });
            });

        </script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html, body {
              overflow-x: hidden;
            }

            body {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="bg-[#000051]">
            <header class="bg-white shadow-md z-10 flex fixed justify-between top-0 w-full px-3 md:px-32 py-3">
                
                <div class="flex gap-6 items-center">
                    <img src="/img/logos/logo.png" width="100" alt="">                   
                </div>

                <a href="{{ route('login') }}" class="text-white font-medium flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-2">
                    <div>Acesso</div>
                    <svg fill="white" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M416 448h-84c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h84c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32h-84c-6.6 0-12-5.4-12-12V76c0-6.6 5.4-12 12-12h84c53 0 96 43 96 96v192c0 53-43 96-96 96zm-47-201L201 79c-15-15-41-4.5-41 17v96H24c-13.3 0-24 10.7-24 24v96c0 13.3 10.7 24 24 24h136v96c0 21.5 26 32 41 17l168-168c9.3-9.4 9.3-24.6 0-34z"/></svg>
                </a>

            </header>


            <div class="pt-16 pb-2">

                <section id="section-1" class="bg-white px-3 md:px-32 py-10 md:py-32 md:flex">
                    <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="md:w-1/2 flex flex-col justify-center animate-fade-in-up delay-[400ms]">
                        <div class="text-3xl md:text-5xl text-blue-700 font-bold">
                            Solução para lojas de veículos
                        </div>
    
                        <div class="mt-2 text-md md:text-xl font-medium text-gray-500 md:w-3/4">
                            Gerencie sua loja de forma eficiente com ferramentas integradas: Gestão de Veículos, CRM Dedicado e um Catálogo Online.
                        </div>
    
                        <div class="mt-5">
                            {{-- <div class="text-xs md:text-xl font-medium ml-1">Aproveite o <span class="text-sky-700 font-semibold">Plano Test</span> por apenas <span class="text-sky-700 font-semibold">R$ 49,99</span></div>
                            <div class="ml-1 text-xs md:text-lg font-medium">
                                Durante 2 meses
                            </div> --}}

                            {{-- <button id="btnWantToEnjoy" class="px-8 md:text-2xl mt-2 rounded-xl text-white font-semibold py-3 bg-blue-700 hover:bg-blue-800 transition">
                                Criar minha loja grátis
                            </button> --}}

                            <div class="text-xs md:text-xl font-medium ml-1">
                                Crie sua loja agora e aproveite, <span class="text-sky-700 font-semibold">totalmente grátis</span>!
                              </div>
                              <div class="ml-1 text-xs md:text-lg font-medium mt-1">
                                <span class="text-red-600 font-semibold animate-pulse">⚠️ Promoção válida por tempo limitado!</span>
                                Não perca essa oportunidade.
                              </div>
                              <a href="/register-new-store">
                                  <button class="px-8 md:text-2xl mt-2 rounded-xl text-white font-semibold py-3 bg-blue-700 hover:bg-blue-800 transition">
                                    Criar minha loja grátis
                                  </button>
                              </a>
                              
                        </div>
                    </div>
                    <div class="md:w-1/2 md:flex mt-10 md:mt-0 md:pl-14 flex-col justify-center border-r-8 border-[#04017c] animate-fade-in-up delay-[400ms]">
                        <div class="flex">
                            <img src="/img/home/img-catalog-page.png" class="w-32 md:w-44" alt="">
                            <img src="/img/home/img-product-page.png" class="w-32 md:w-44" alt="">
                            <img src="/img/home/img-send-message-page.png" class="w-32 md:w-44" alt="">
                        </div>
                        <div class="text-xs flex gap-1 items-center">
                            <svg class="w-3 h-3 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>  
                            Imagens ilustrativas
                        </div>
                    </div>
                </section>

                <section id="section-2">
                    <div class="md:flex md:flex-col md:items-center">
                        <div class="md:w-1/2 md:px-24">  
                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="text-2xl md:text-3xl font-bold mt-14 md:mt-24 text-center px-10 text-blue-700">
                                <span class="text-white">Principais soluções</span> do sistema para lojas de veículos Motiv
                            </div>
        
                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="mt-5 px-6 text-center text-gray-300 font-medium">
                                Conte com as funcionalidades avançadas do sistema para lojas de veículos Motiv para ter uma gestão empresarial eficiente e otimizada.
                            </div>
                        </div>
                    </div>

                    <div class="md:px-40 md:flex md:justify-center">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 px-4 md:px-56">

                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="bg-[#04017c] shadow-md rounded-xl md:rounded-2xl p-5 md:py-10">
                                <div class="text-2xl text-white font-bold text-center items-center flex flex-col justify-center">
                                    <ion-icon class="w-14 h-14" name="book-outline"></ion-icon>
                                    <span>Catálogo Online</span>
                                </div>
                                <div class="mt-2 text-gray-400 font-medium text-center">
                                    Tenha um site profissional para a sua loja de veículos, com design moderno e responsivo.
                                </div>
                            </div>

                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="bg-[#04017c] shadow-md rounded-xl p-5 md:rounded-2xl md:py-10">
                                <div class="text-2xl text-white font-bold text-center flex flex-col items-center">
                                    <ion-icon class="w-14 h-14" name="car-sport-outline"></ion-icon>
                                    <span>Gestão de Veículos</span>
                                </div>
                                <div class="mt-2 text-gray-400 font-medium text-center">
                                    Cadastre, edite e gerencie os veículos da sua loja de forma prática e eficiente de forma fácil e intuitiva.
                                </div>
                            </div>

                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="bg-[#04017c] shadow-md rounded-xl p-5 md:rounded-2xl md:py-10">
                                <div class="text-2xl text-white font-bold text-center flex flex-col items-center">
                                    <ion-icon class="w-14 h-14" name="people-outline"></ion-icon>
                                    <span>CRM Dedicado</span>
                                </div>
                                <div class="mt-2 text-gray-400 font-medium text-center">
                                    Tenha um CRM dedicado para gerenciar os clientes da sua loja e aumentar as vendas.
                                </div>
                            </div>

                            <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000" class="bg-[#04017c] shadow-md rounded-xl p-5 md:rounded-2xl md:py-10">
                                <div class="text-2xl text-white font-bold text-center flex flex-col items-center">
                                    <ion-icon class="h-14 w-14" name="trophy-outline"></ion-icon>
                                    <span>Indique e Ganhe</span>
                                </div>
                                <div class="mt-2 text-gray-400 font-medium text-center">
                                    Indique o sistema Motiv e ganhe desconto na mensalidade por cada indicação.
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </section>

                <section id="section-3" class="md:mt-32 px-1 md:px-24 md:flex pb-10">
                    <div data-aos="fade-left" class="md:w-1/3 md:pt-2 md:pl-10 md:border-l-8 md:border-sky-400">
                        <div class="text-2xl md:text-5xl font-bold mt-14 px-4 text-blue-700">
                            <span class="text-white">Uma solução eficaz para ter o controle</span> da sua loja em suas mãos
                        </div> 
    
                        <div class="mt-8 px-4 md:text-lg text-gray-300 font-medium">
                            A hora de mudar a realidade da sua loja de veículos é agora.
                        </div>
                    </div>

                    <div class="mt-4 px-4 flex flex-col md:flex-row gap-4 md:w-2/3 md:items-start">
                        <div data-aos="fade-right" data-aos-delay="200" data-aos-duration="1000" class="border border-sky-700 md:h-[75%] bg-blue-50 rounded-3xl py-4 px-3 gap-2 flex flex-col items-center">
                            <div class="">
                                <svg class="w-12 h-12 text-sky-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h.01M8.99 9H9m12 3a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM6.6 13a5.5 5.5 0 0 0 10.81 0H6.6Z"/>
                                </svg>
                            </div>
                    
                            <div class="">
                                <div class="text-2xl font-semibold mt-1 text-center">Melhor experiência</div>
                                
                                <div>
                                    <div class="text-gray-500 font-medium text-center mt-2 px-10 md:px-4">
                                        Tenha a melhor experiência de gestão para a sua loja de veículos com o sistema Motiv.
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div data-aos="fade-left" data-aos-delay="200" data-aos-duration="1000" class="border border-sky-700 md:mt-24 md:h-[75%] bg-blue-50 rounded-3xl py-4 px-3 flex gap-2 flex-col items-center">
                            <div>
                                <svg class="w-12 h-12 text-sky-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 11.5 11 13l4-3.5M12 20a16.405 16.405 0 0 1-5.092-5.804A16.694 16.694 0 0 1 5 6.666L12 4l7 2.667a16.695 16.695 0 0 1-1.908 7.529A16.406 16.406 0 0 1 12 20Z"/>
                                </svg>                              
                            </div>
                    
                            <div>
                                <div class="text-2xl font-semibold mt-1 text-center">Segurança nos dados</div>
                                
                                <div>
                                    <div class="text-gray-500 font-medium text-center mt-2 px-10 md:px-4">
                                        Tenha segurança total dos dados da sua loja de veículos com o sistema Motiv.
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div data-aos="fade-right" data-aos-delay="200" data-aos-duration="1000" class="border border-sky-700 md:h-[75%] bg-blue-50 rounded-3xl py-4 px-3 flex flex-col gap-2 items-center">
                            <div>
                                <svg class="w-12 h-12 text-sky-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.37 7.657c2.063.528 2.396 2.806 3.202 3.87 1.07 1.413 2.075 1.228 3.192 2.644 1.805 2.289 1.312 5.705 1.312 6.705M20 15h-1a4 4 0 0 0-4 4v1M8.587 3.992c0 .822.112 1.886 1.515 2.58 1.402.693 2.918.351 2.918 2.334 0 .276 0 2.008 1.972 2.008 2.026.031 2.026-1.678 2.026-2.008 0-.65.527-.9 1.177-.9H20M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                                    
                            <div>
                                <div class="text-2xl font-semibold mt-1 text-center">Flexibilidade e acessibilidade</div>
                                
                                <div>
                                    <div class="text-gray-500 font-medium text-center mt-2 px-10 md:px-4">
                                        Tenha flexibilidade e acessibilidade para gerenciar de qualquer lugar com internet.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="section-4" class="md:mt-32 px-1 pt-10 bg-white">
                    <div class="text-2xl md:text-3xl font-bold mt-14 px-2 text-center">
                        <span class="text-blue-700 ">Assinatura/Planos</span>
                    </div> 

                    <div class="mt-3 px-4 md:text-lg text-gray-500 text-center font-medium">
                        Escolha o plano que melhor se encaixa com a sua loja de veículos.
                    </div>

                    <div class="mt-4 px-4 flex flex-col md:flex-row md:justify-center md:mt-16 gap-10">
                        <div data-aos="flip-up" data-aos-delay="200" data-aos-duration="1000" class="border border-[#04017c] pb-4 bg-white rounded-3xl flex justify-center flex-col">

                            <div class="flex gap-2 items-center">
                                <div class="font-semibold bg-[#04017c] px-5 w-full py-4 rounded-t-3xl text-white">
                                    <div class="text-2xl">Plano Test</div>
                                    <div class="text-xs">10 veículos</div>
                                </div>
                            </div>

                            
                            <div class="">

                                <div class="text-2xl bg-sky-300 font-bold py-2 px-2 text-center text-[#1104fc]">Grátis por tempo limitado</div>

                                <div class="text-gray-600 font-medium text-center mt-4 px-10 flex flex-col gap-2">

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>                                          
                                        1 Usuário
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>                                          
                                        Gestão de Veículos
                                    </div>

                                    <div class="line-through flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                          
                                        CRM Dedicado
                                    </div>

                                    <div class="line-through flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Gestão de Vendas
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        Catálogo Online  
                                    </div>

                                    <div class="flex gap-1 items-center line-through">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Indique e Ganhe   
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        1 CPF ou CNPJ  
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- <div data-aos="flip-up" data-aos-delay="200" data-aos-duration="1000" class="border border-[#04017c] pb-4 bg-white rounded-3xl flex justify-center flex-col">
                            <div class="flex gap-2 items-center">
                                <div class="font-semibold bg-[#04017c] px-5 w-full py-4 rounded-t-3xl text-white">
                                    <div class="text-2xl">Start Plus</div>
                                    <div class="text-xs">25 veículos</div>
                                </div>
                            </div>
                            
                            <div class="">

                                <div class="text-2xl bg-sky-300 font-bold py-2 text-center text-[#1104fc]">R$ 89,99/mês</div>

                                <div class="text-gray-600 font-medium mt-4 px-10 flex flex-col gap-2">

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>                                          
                                        1 Usuário
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>                                          
                                        Gestão de Veículos
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                          
                                        CRM Dedicado
                                    </div>

                                    <div class="flex gap-1 items-center line-through">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Gestão de Vendas
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        Catálogo Online   
                                    </div>

                                    <div class="flex gap-1 items-center line-through">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Indique e Ganhe   
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        1 CPF ou CNPJ 
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        {{-- <div data-aos="flip-up" data-aos-delay="200" data-aos-duration="1000" class="border border-[#04017c] pb-4 bg-white rounded-3xl flex justify-center flex-col">
                            <div class="flex items-center">
                                <div class="font-semibold bg-[#04017c] px-5 w-full py-4 rounded-t-3xl text-white">
                                    <div class="text-2xl">Advanced</div>
                                    <div class="text-xs">60 veículos</div>
                                </div>
                            </div>
                            
                            <div class="">

                                <div class="text-2xl bg-sky-300 font-bold py-2 text-center text-[#1104fc]">R$ 119,99/mês</div>

                                <div class="text-gray-600 font-medium mt-4 px-10 flex flex-col gap-2">

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>                                          
                                        1 Usuário
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>                                          
                                        Gestão de Veículos
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                          
                                        CRM Dedicado
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Gestão de Vendas
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        Catálogo Online   
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        Indique e Ganhe   
                                    </div>

                                    <div class="flex gap-1 items-center">
                                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg> 
                                        1 CPF ou CNPJ  
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </section>

                <section class="px-6 py-10 bg-[#04017c] flex justify-center items-center">
                    <div data-aos="zoom-in" data-aos-delay="200" data-aos-duration="1000" class="text-2xl md:w-1/3 font-semibold text-center text-white">
                        Proporcione a melhor experiência para os seus clientes com o sistema Motiv.
                    </div>
                </section>

                <section class="px-6 py-4 md:flex md:justify-center">                

                    <div class="flex justify-center">
                        <a href="/register-new-store">
                            <button class="px-10 md:text-2xl mt-2 rounded-xl text-white font-semibold py-3 bg-blue-700 hover:bg-blue-800 transition">
                              Criar minha loja grátis
                            </button>
                        </a>
                    </div>
                    {{-- <div class="mt-10 bg-white shadow-lg rounded-2xl md:w-[45%] py-4 px-3 flex flex-col">
                        <div class="text-center text-3xl flex gap-1 justify-center items-center font-medium text-black mt-4">   
                           Faça seu cadastro 
                            <svg class="w-8 h-8 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10.051 8.102-3.778.322-1.994 1.994a.94.94 0 0 0 .533 1.6l2.698.316m8.39 1.617-.322 3.78-1.994 1.994a.94.94 0 0 1-1.595-.533l-.4-2.652m8.166-11.174a1.366 1.366 0 0 0-1.12-1.12c-1.616-.279-4.906-.623-6.38.853-1.671 1.672-5.211 8.015-6.31 10.023a.932.932 0 0 0 .162 1.111l.828.835.833.832a.932.932 0 0 0 1.111.163c2.008-1.102 8.35-4.642 10.021-6.312 1.475-1.478 1.133-4.77.855-6.385Zm-2.961 3.722a1.88 1.88 0 1 1-3.76 0 1.88 1.88 0 0 1 3.76 0Z"/>
                              </svg>                                   
                        </div>

                        <div id="divSucessMessageInRegistrationUser" class="hidden">
                            <div class="flex flex-col items-center bg-green-500 rounded-xl p-2 text-center mt-6">
                                <div>
                                    <svg class="w-16 h-16 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                                    </svg>                                  
                                </div>
                                <div>
                                    <div class="text-xl text-white font-semibold leading-2">
                                        Solicitação enviada com sucesso!
                                    </div>
                                    <div class="text-xs mt-4 text-white font-medium">
                                        Recebemos sua solicitação e agradecemos seu interesse em tornar sua loja cada vez melhor para seus clientes, em breve nossa equipe entrará em contato com você para darmos continuidade.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="divErrorMessageInRegistrationUser" class="hidden">
                            <div class="flex flex-col items-center bg-red-500 rounded-xl p-2 text-center mt-6">
                                <div>
                                    <svg class="w-16 h-16 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>                                 
                                </div>
                                <div>
                                    <div class="text-xl text-white font-semibold leading-2">
                                        Erro ao enviar solicitação!
                                    </div>
                                    <div class="text-xs mt-4 text-white font-medium">
                                        O e-mail ou o telefone informado já existem em nossa base de dados. Acesse sua conta pelo botão abaixo.
                                    </div>

                                </div>
                                <div class="mt-6 mb-2">
                                    <a href="{{ route('login') }}" class="bg-white text-blue-700 px-4 py-2 mt-4 rounded-xl font-semibold">Acessar minha conta</a>
                                </div>
                            </div>
                        </div>

                        <div id="formUserRegistrationWelcome" class="block">
                            <div class="mt-6 flex flex-col gap-2">  
                                <div class="grid gap-2 md:grid-cols-2">
                                    <div>
                                        <label for="">Nome</label>
                                        <input type="text" id="name" class="w-full rounded-xl py-2 px-3 mt-1" placeholder="Informe seu nome">
                                    </div>
                                    <div>
                                        <label for="">WhatsApp</label>
                                        <input type="text" id="whatsapp" class="w-full phone-mask rounded-xl py-2 px-3 mt-1" placeholder="Seu telefone de contato">
                                    </div>
                                </div>
                                <div>
                                    <label for="">Email</label>
                                    <input type="text" id="email" class="w-full rounded-xl py-2 px-3 mt-1" placeholder="Seu melhor email">
                                </div>
                                <div class="grid md:grid-cols-2 gap-2">
                                    <div>
                                        <label for="">Nome da Loja</label>
                                        <input type="text" id="store_name" class="w-full rounded-xl py-2 px-3 mt-1" placeholder="Informe o nome da loja">
                                    </div>
                                    <div>
                                        <label for="qtd_vehicles_in_stock">Quantidade de veículos em estoque</label>
                                        <select id="qtd_vehicles_in_stock" class="w-full rounded-xl py-2 px-3 mt-1" name="" id="">
                                            <option value="10-vehicles">Até 10 veículos</option>
                                            <option value="25-vehicles">Até 25 veículos</option>
                                            <option value="60-vehicles">Até 60 veículos</option>
                                            <option value="plus-vehicles">Mais de 60 veículos</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-2">
                                    <div>
                                        <label for="plan">Assinatura/Plano</label>
                                        <select id="plan_slug" class="w-full rounded-xl py-2 px-3 mt-1">
                                            <option value="test">Plano Test - 1x R$ 49,99</option>
                                            <option value="start-plus">Start Plus - R$ 89,99/mês</option>
                                            <option value="advanced">Advanced - R$ 119,99/mês</option>
                                        </select>  
                                    </div>
                                    <div>
                                        <label for="payment_method">Forma de pagamento</label>
                                        <select id="payment_method" class="w-full rounded-xl py-2 px-3 mt-1">
                                            <option selected value="pix">Pix</option>
                                            <option value="credit">Crédito</option>
                                        </select>  
                                    </div>
                                </div>
    
                                <div class="mt-4">
                                    <button id="new-partner-solicitation" class="bg-blue-700 w-full px-4 py-2 text-white font-semibold rounded-xl">Enviar Solicitação</button>
                                </div>
                            </div>
                        </div>

                    </div> --}}
                </section>

               

            </div>

            <script>

                $(document).ready(function() {
                    // Mostrar o loader quando uma requisição AJAX começar
                    $(document).ajaxStart(function() {
                        $('#globalLoaderWelcome').fadeIn();
                    });
                
                    // Esconder o loader quando a requisição AJAX terminar
                    $(document).ajaxStop(function() {
                        $('#globalLoaderWelcome').fadeOut();
                    });
                
                    // Esconder o loader se houver um erro na requisição
                    $(document).ajaxError(function() {
                        $('#globalLoaderWelcome').fadeOut();
                    });
                });

                let btnWantToEnjoy = $('#btnWantToEnjoy');
                
                let formUserRegistrationWelcome = $('#formUserRegistrationWelcome');
                let divSucessMessageInRegistrationUser = $('#divSucessMessageInRegistrationUser');
                let divErrorMessageInRegistrationUser = $('#divErrorMessageInRegistrationUser');

                btnWantToEnjoy.click(function() {
                    $("html, body").animate({
                        scrollTop: formUserRegistrationWelcome.offset().top
                    }, 800); // Duração da animação em milissegundos
                });

                let btnNewPartnerSolicitation = document.getElementById('new-partner-solicitation');

                $(btnNewPartnerSolicitation).click(function() {
                    let name = $('#name').val();
                    let email = $('#email').val();
                    let whatsapp = $('#whatsapp').val();
                    let store_name = $('#store_name').val();
                    let qtd_vehicles_in_stock = $('#qtd_vehicles_in_stock').val();
                    let plan_slug = $('#plan_slug').val();
                    let payment_method = $('#payment_method').val();

                    $.ajax({
                        url: '/new-request-plan',
                        type: 'POST',
                        data: {
                            name: name,
                            email: email,
                            phone: whatsapp,
                            store_name: store_name,
                            qtd_vehicles_in_stock: qtd_vehicles_in_stock,
                            plan_slug: plan_slug,
                            payment_method: payment_method
                        },
                        success: function(response) {
                            formUserRegistrationWelcome.addClass('hidden');
                            divSucessMessageInRegistrationUser.removeClass('hidden');
                            divErrorMessageInRegistrationUser.addClass('hidden');
                        },
                        error: function(error) {
                            formUserRegistrationWelcome.addClass('hidden');
                            divSucessMessageInRegistrationUser.addClass('hidden');
                            divErrorMessageInRegistrationUser.removeClass('hidden');
                        }
                    });

                });
            </script>

        </div>

        <footer style="background-color: #f8f9fa; padding: 20px 0; text-align: center; font-family: sans-serif; border-top: 1px solid #ddd;">
            <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                {{-- <div style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                    <p style="margin: 0; font-size: 1.1em;"><strong>🌐 Siga-nos nas redes sociais:</strong></p>
                    <div style="display: flex; gap: 15px; justify-content: center; font-size: 1.3em;">
                        <a href="https://instagram.com" class="flex items-center text-red-700"  target="_blank" style="text-decoration: none;">
                            <svg class="w-6 h-6 text-red-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                            </svg>                               
                        </a>
                        <a href="https://facebook.com" class="flex items-center text-blue-600 gap-1" target="_blank" style="text-decoration: none;">
                            <svg class="w-6 h-6 text-white bg-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                            </svg>                              
                        </a>
                    </div>
                </div> --}}
        
                <hr style="margin: 20px 0; border: none; border-top: 1px solid #ccc;">
        
                <p style="font-size: 0.9em; color: #666;">© 2025 Motiv App. Todos os direitos reservados.</p>
            </div>
        </footer>
        

        <style>
            #section-1 {
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0% 100%);
            }

            #section-4 {
                padding-bottom: 50px;
                clip-path: polygon(0 0, 100% 5%, 100% 100%, 0 100%);
            }
        </style>
    </body>
</html>
