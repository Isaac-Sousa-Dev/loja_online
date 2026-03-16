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
    <script>
        $(document).ready(function() {
            $('.phone-mask').mask('(00) 00000-0000');
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
                <a href="/register-new-store"
                    class="text-white font-medium flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-2">
                    <div>Voltar</div>
                </a>
            </div>
        </header>

        <div class="pt-20 pb-10">

            <div class="flex px-2">

                <div class="hidden md:block w-1/2 bg-blue-600 rounded-xl">
                    
                </div>

                <div class="bg-white pt-6 w-full md:w-1/2 md:px-10 flex flex-col">

                    <section class="w-full mb-6">
                        <div class="font-semibold text-gray-700 text-3xl">
                            Você já deu o primeiro passo rumo ao sucesso.
                        </div>
                        <div class="font-medium text-gray-700 mt-2">
                            Agora precisamos de alguns dados do seu negócio
                            <span class="font-semibold">para te ajudar da melhor maneira.</span>
                        </div>

                        <div class="bg-white w-full rounded-xl mt-6 flex flex-col gap-4">
                            
                            <div>
                                <label for="plan">CPF ou CNPJ</label>
                                <input type="text" id="cpf_cnpj"
                                    class="w-full cpf-cnpj-mask rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                                <div id="cpfCnpjError" class="text-red-600 font-semibold mt-1 hidden text-xs">
                                    CPF ou CNPJ inválido.
                                </div>
                            </div>

                            <div>
                                <label for="plan">Seu nome (Opcional)</label>
                                <input type="text" id="name"
                                    class="w-full rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                            </div>

                            <div>
                                <label for="plan">WhatsApp (com DDD) (Opcional)</label>
                                <input type="text" id="phone"
                                    class="w-full phone-mask rounded-xl py-2 px-3 mt-1 font-medium text-blue-700">
                            </div>

                            <div>
                                <label for="plan">Perfil do instagran (Opcional)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-[25px] transform -translate-y-1/2 text-blue-700 font-semibold">@</span>
                                    <input type="text" id="instagran_profile"
                                           class="w-full pl-8 rounded-xl py-2 px-3 mt-1 font-medium text-blue-700" />
                                </div>
                            </div>

                            <div>
                                <label for="plan">Página do Facebook (Opcional)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-[25px] transform -translate-y-1/2 text-blue-700 font-semibold">www.facebook.com/</span>
                                    <input type="text" id="facebook_page"
                                           class="w-full pl-[185px] rounded-xl py-2 px-3 mt-1 font-medium text-blue-700" />
                                </div>
                            </div>

                        </div>
                    </section>

                    <div class="hidden">
                        <div class="bg-white bottom-0 w-full py-3 md:py-0 flex justify-center z-10">
                            <a href="/login" class="w-full">
                                <button disabled class="btnFinishRegister bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Acessar minha loja na Motiv
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="bg-white md:hidden fixed bottom-0 w-full p-3 flex justify-center z-10">
                <a href="/login" class="w-full">
                    <button disabled class="btnFinishRegister bg-blue-700 px-4 py-3 w-full rounded-lg font-bold text-white">Acessar minha loja na Motiv
                    </button>
                </a>
            </div>
        </div>

    </div>

    <script>

        function validarCpfCnpj(valor) {
            valor = valor.replace(/\D/g, '');

            if (valor.length === 11) {
                // Valida CPF
                let soma = 0;
                let resto;
                if (/^(\d)\1+$/.test(valor)) return false;
                for (let i = 1; i <= 9; i++) soma += parseInt(valor.substring(i - 1, i)) * (11 - i);
                resto = (soma * 10) % 11;
                if (resto === 10 || resto === 11) resto = 0;
                if (resto !== parseInt(valor.substring(9, 10))) return false;
                soma = 0;
                for (let i = 1; i <= 10; i++) soma += parseInt(valor.substring(i - 1, i)) * (12 - i);
                resto = (soma * 10) % 11;
                if (resto === 10 || resto === 11) resto = 0;
                return resto === parseInt(valor.substring(10, 11));
            } else if (valor.length === 14) {
                // Valida CNPJ
                let tamanho = valor.length - 2;
                let numeros = valor.substring(0, tamanho);
                let digitos = valor.substring(tamanho);
                let soma = 0;
                let pos = tamanho - 7;
                for (let i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) pos = 9;
                }
                let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado !== parseInt(digitos.charAt(0))) return false;
                tamanho += 1;
                numeros = valor.substring(0, tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (let i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) pos = 9;
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                return resultado === parseInt(digitos.charAt(1));
            }

            return false;
        }

        function checkCpfCnpjAndEnableButton() {
            const value = $('#cpf_cnpj').val().replace(/\D/g, '');
            const $btn = $('.btnFinishRegister');
            const $error = $('#cpfCnpjError');

            if (!value || !validarCpfCnpj(value)) {
                $btn.prop('disabled', true).addClass('opacity-50');
                $error.removeClass('hidden');
                return;
            }

            $error.addClass('hidden'); // Oculta erro se válido

            // Verifica se já existe no banco
            $.ajax({
                url: '/verificar-cpf-cnpj',
                type: 'POST',
                data: { cpf_cnpj: value },
                success: function(res) {
                    if (res.exists) {
                        $btn.prop('disabled', true).addClass('opacity-50');
                        $error.removeClass('hidden').text('Já existe uma loja com esse CPF ou CNPJ.');
                    } else {
                        $btn.prop('disabled', false).removeClass('opacity-50');
                        $error.addClass('hidden');
                    }
                },
                error: function() {
                    $btn.prop('disabled', true).addClass('opacity-50');
                    $error.removeClass('hidden').text('Erro ao verificar CPF/CNPJ.');
                }
            });
        }


        // Dispara ao sair do campo
        $('#cpf_cnpj').on('blur', checkCpfCnpjAndEnableButton);

        $('.btnFinishRegister').click(function(e) {
            e.preventDefault();
            
            const data = {
                cpf_cnpj: $('#cpf_cnpj').val(),
                name: $('#name').val(),
                phone: $('#phone').val(),
                instagran_profile: $('#instagran_profile').val(),
                facebook_page: $('#facebook_page').val()
            }

            let dataStepOne = JSON.parse(localStorage.getItem('stepOne'))
            const completedData = {...dataStepOne, ...data};
            
            $.ajax({
                url: '/register-store',
                type: 'POST',
                data: completedData,
                success: function (res) {
                    console.log(res)
                    localStorage.removeItem('stepOne');
                    window.location.href = '/dashboard';
                },
                error: function (err) {
                    alert('Erro ao cadastrar');
                    console.log(err);
                }
            });
            

        })
    </script>

</body>
<style>
    .btnFinishRegister:disabled{
        opacity: 0.6;
    }
</style>

</html>
