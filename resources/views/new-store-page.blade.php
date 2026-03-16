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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
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
    <div class="bg-white">
        <header class="bg-white shadow-md z-10 fixed justify-between top-0 w-full pt-3 pb-3">
            <div class="flex justify-between w-full px-3 md:px-32">
                <div class="flex gap-6 items-center">
                    <img src="/img/logos/logo.png" width="100" alt="">
                </div>

                <a href="/"
                    class="text-white font-medium flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-2">
                    <div>Voltar</div>
                </a>
            </div>
        </header>

        <div class="pt-20 pb-10">
            <div class="flex px-2 w-full">

                <div class="hidden rounded-xl md:block w-1/2 bg-blue-600">
                    
                </div>

                <div class="bg-white px-2 pt-6 w-full md:w-1/2 md:px-10 flex flex-col">

                    <section class="w-full mb-6">
                        <div class="font-semibold text-gray-700 text-3xl">
                            Crie sua loja
                        </div>
                        <div class="flex leading-5 font-medium text-gray-700 mt-1">
                            Comece já - é grátis por 90 dias. Não é necessário cartão de crédito.
                        </div>

                        <div class="bg-white w-full rounded-xl mt-8 flex flex-col gap-4">

                            <div id="alert-error" class="hidden">
                                <div class="bg-red-300 p-2 flex gap-2 rounded-lg font-semibold">
                                    <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <div id="message-error">
                                        As senhas não conferem
                                    </div> 
                                </div>
                            </div>

                            <div>
                                <label for="plan">E-mail</label>
                                <input type="text" id="email" placeholder="nome@exemplo.com.br"
                                    class="w-full rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                            </div>

                            <div>
                                <label for="plan">Senha</label>
                                <input type="password" id="password" placeholder="Defina sua senha"
                                    class="w-full rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                            </div>

                            <div>
                                <label for="plan">Confirme sua senha</label>
                                <input type="password" id="password_confirmation" placeholder="Confirmação de senha"
                                    class="w-full rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                            </div>

                            <div>
                                <label for="plan">Nome da sua marca</label>
                                <input type="text" id="store_name" placeholder="Exemplo: Loja Motiv"
                                    class="w-full rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                            </div>

                            <div>
                                <input type="checkbox" id="accept_terms_and_politics" class="h-5 w-5 rounded-md">
                                <label for="" class="ml-1">Aceito os Termos e Condições e a Política de
                                    Privacidade da Motiv.
                                </label>
                            </div>
                        </div>
                    </section>

                    <div class="hidden">
                        <div class="bg-white bottom-0 w-full py-3 md:py-0 flex justify-center mt-5">
                            <a href="/new-store-info" class="w-full">
                                <button disabled class="newStore bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Continuar
                                </button>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
            <div class="bg-white fixed md:hidden bottom-0 w-full p-3 flex justify-center z-10">
                <a href="/new-store-info" class="w-full">
                    <button disabled class="newStore bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Continuar
                    </button>
                </a>
            </div>
        </div>

    </div>

    <script>

        let existEmail = false;

        function checkFormValidity() {
            console.log('cheguei aqui')
            const email = $('#email').val().trim();
            const password = $('#password').val().trim();
            const password_confirmation = $('#password_confirmation').val().trim();
            const store_name = $('#store_name').val().trim();
            const termsAccepted = $('#accept_terms_and_politics').is(':checked');

            const isValid = email && password && password_confirmation && store_name && termsAccepted;

            if (isValid) {
                console.log('Valid form')
                $('.newStore').prop('disabled', false).removeClass('opacity-50');
            } else {
                console.log('teteee')
                $('.newStore').prop('disabled', true).addClass('opacity-50');
            }
        }

        // Monitora alterações nos campos
        $('#email, #password, #password_confirmation, #store_name').on('input', checkFormValidity);
        $('#accept_terms_and_politics').on('change', checkFormValidity);

        // Executa a verificação ao carregar (caso tenha valores preenchidos vindos do localStorage, por exemplo)
        $(document).ready(checkFormValidity);
        $(document).ready(function () {
            const savedData = localStorage.getItem('stepOne');

            if (savedData) {
                const data = JSON.parse(savedData);

                $('#email').val(data.email || '');
                $('#password').val(data.password || '');
                $('#password_confirmation').val(data.password_confirmation || '');
                $('#store_name').val(data.store_name || '');

                if (data.termsAndPolitics === "on" || data.termsAndPolitics === true) {
                    $('#accept_terms_and_politics').prop('checked', true);
                }
            }
        });

        function validateForm(data) {
            if (!data.email || !data.password || !data.password_confirmation || !data.store_name) {
                showAlertError('Por favor, preencha todos os campos.');
                return false;
            }

            if (!$('#accept_terms_and_politics').is(':checked')) {
                showAlertError('Você precisa aceitar os Termos e a Política de Privacidade.');
                return false;
            }

            if (data.password !== data.password_confirmation) {
                showAlertError('As senhas não conferem.');
                return false;
            }

            return true;
        }

        function hideAlertError(){
            $('#alert-error').addClass('hidden')
        }

        function showAlertError(message) {
            $('#alert-error').removeClass('hidden')
            $('#message-error').text(message)
        }

        $('.newStore').click(function (e) { 
            e.preventDefault();

            const data = {
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
                email: $('#email').val(),
                store_name: $('#store_name').val(),
                termsAndPolitics: $('#accept_terms_and_politics').val()
            }

            if(validateForm(data) && !existEmail){

                if(existEmail){
                    showAlertError('Este e-mail já está em uso. Por favor, tente outro ou acesse sua conta.');
                }

                localStorage.setItem('stepOne', JSON.stringify(data));
                window.location.href = '/new-store-info';
            }
            
        });

        $('#email').on('blur', function () {
            const email = $(this).val().trim();

            if (email === '') return;

            $.ajax({
                url: '/check-email',
                type: 'POST',
                data: {
                    email: email,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.exists) {
                        existEmail = true;
                        showAlertError('Este e-mail já está em uso. Por favor, tente outro ou acesse sua conta.');
                    } else {
                        existEmail = false;
                        hideAlertError()
                    }
                },
                error: function () {
                    showAlertError('Erro ao verificar o e-mail. Tente novamente.');
                }
            });
        });

    </script>

</body>

<style>
    .newStore:disabled{
        opacity: 0.6;
    }
</style>

</html>
