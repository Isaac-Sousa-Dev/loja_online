<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#036">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

        <title>Produto | Motiv</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7537204439684763"
     crossorigin="anonymous"></script>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

        <script>
            $(document).ready(function() {
                $('.phone-mask').mask('(00) 00000-0000');
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
            body {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            .carousel-item {
                position: absolute;
                width: 100%;
                transition: transform 0.5s ease;
            }
            .carousel-item.active {
                position: relative;
                transform: translateX(0);
            }
            .carousel-item.inactive {
                display: none;
            }
        </style>
    </head>
    <body class="bg-white">

        <!-- Loader Global -->
        <div id="globalLoaderPdp" class=" flex justify-center items-center" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; background: rgba(255, 255, 255, 0.8); justify-content: center; align-items: center; z-index: 9999;">
            <svg class="size-10 animate-spin" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                <path d="M32 64a32 32 0 1 1 32-32h-4a28 28 0 1 0-28 28z" fill="currentColor" />
                <path d="M32 0a32 32 0 0 1 32 32h-4a28 28 0 0 0-28-28z" fill="currentColor" />
            </svg>
        </div>

        <div class="block md:hidden">
            @include('orders.product-page.mobile')
        </div>

        <div class="hidden md:block">
            @include('orders.product-page.desktop')
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const swiper = new Swiper('.swiper-container', {
                    loop: true, // Permite loop infinito
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    autoplay: {
                        delay: 3000, // Troca de slide a cada 3 segundos
                        disableOnInteraction: false,
                    },
                });
            });
        </script>

    </body>
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        $('.price-mask').mask('000.000.000.000.000,00', {reverse: true});
    </script>

    <style>

        html {
            font-family: Arial;
            font-size: 16px;
        }

        .input-radio {
            box-shadow: 0 0 0 1px var(--color);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #0ea5e9;
            border-radius: 50%;
            outline: none;
            box-shadow: 0 0 0 1px var(--color);
            cursor: pointer;
        }

        .input-radio:hover {
            border-width: 0;
        }

        .input-radio:checked {
            box-shadow: 0 0 0 1px var(--checked-color);
            background-color: var(--checked-color);
            border-width: 0.2rem;
        }

        .active-seller {
            border: 2px solid #0ea5e9;
        }

        .carousel-mobile {
            height: 280px;
            padding-top: 5px;
        }

        .carousel {
            height: 500px;
            padding-top: 5px;
        }

        .image-slide {
            height: 275px;
            width: 100%;
            /* border-radius: 10px; */
        }

        .btn-circle {
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            border-radius: 50%;
            padding: 15px;
            text-align: center;
            text-decoration: none;
        }

    </style>
</html>
