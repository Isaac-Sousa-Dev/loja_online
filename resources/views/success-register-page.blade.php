<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Mensagem enviada | Motiv</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

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

        <div class="px-4 mt-44 flex flex-col items-center">

            <div>
                <svg class="w-24 h-24 text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                  </svg>
                  
            </div>

            <div class="text-2xl text-blue-700 mt-2 text-center font-semibold">
                Cadastro realizado com sucesso!
            </div>

            <div class="text-center mt-8">
                O vendedor já recebeu seus dados e retornará o contato em breve.
            </div>
        </div>

        
        <div class="bg-white fixed bottom-0 w-full p-3 flex justify-center z-10">
            <a class="w-full" href="{{route('orders.index', $storePartnerLink)}}">
                <button class="btn-back-initial-page bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Voltar para início</button>
            </a>
        </div>

    </body>

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

        .carousel {
            height: 280px;
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
