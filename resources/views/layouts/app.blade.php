<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#036">

        <title>{{ config('app.name', 'Motiv') }}</title>

        <!-- FONTS -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>

        {{-- TOAST --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        {{-- BOOTSTRAP --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


         {{-- SELECT2 --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>      --}}
        
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.price-mask').mask('000.000.000.000.000,00', {reverse: true});
                $('.year-manufacturer-mask').mask('0000/0000');
                $('.miliage-mask').mask('00000000');
                $('.renavam-mask').mask('00000000000');
                $('.phone-mask').mask('(00) 00000-0000');
                $('.cep-mask').mask('00000-000');
                $('.cpf-cnpj-mask').mask('00.000.000/0000-00', {
                    onKeyPress: function(value, e, field, options) {
                        var length = value.replace(/\D/g, '').length;
                        var mask = (length > 11) ? '00.000.000/0000-00' : '000.000.000-000';
                        field.mask(mask, options);
                    }
                });
                $('.license-plate-mask').mask('AAAAAAA', {
                    translation: {
                        'A': { pattern: /[A-Za-z0-9]/ }
                    }
                }).on('blur', function () {
                    const val = $(this).val().replace(/[^A-Za-z0-9]/g, '');
                }).on('input', function () {
                    this.value = this.value.toUpperCase();
                });

                $('.chassi-mask').mask('SSSSSSSSSSSSSSSSS', {
                    translation: {
                        'S': { pattern: /[A-Za-z0-9]/ }
                    }
                }).on('input', function () {
                    this.value = this.value.toUpperCase();
                });

                $('.engine-power-mask').mask('00,0', {reverse: true});
            });

        </script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="h-full bg-gray-100 overflow-hidden">
        <div id="global-loader" class=" flex justify-center items-center" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: none; background: rgba(255, 255, 255, 0.8); justify-content: center; align-items: center; z-index: 9999;">
            <svg class="size-10 animate-spin" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                <path d="M32 64a32 32 0 1 1 32-32h-4a28 28 0 1 0-28 28z" fill="currentColor" />
                <path d="M32 0a32 32 0 0 1 32 32h-4a28 28 0 0 0-28-28z" fill="currentColor" />
            </svg>
        </div>
        <script>
            function showLoader() {
                $("#global-loader").fadeIn();
            }

            function hideLoader() {
                $("#global-loader").fadeOut();
            }

            $(document).on('submit', 'form', function() {
                showLoader();
            });

            $(document).ready(function() {
                hideLoader();
            });
        </script>

        <div class="flex h-full">

            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main content area -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- Navbar -->
                <div class="bg-white h-16 flex items-center justify-between px-2 md:px-4 border-b shadow-sm fixed top-0 z-30 w-full md:left-64 md:w-[calc(100%-16rem)]">
                    @include('components.navbar')
                </div>

                <!-- Content area with scroll -->
                <main class="mt-16 overflow-auto flex-1 p-0 md:pl-64">
                    @yield('content')
                </main>

            </div>
        </div>

        <!-- Mobile Sidebar Toggle -->
        <script>
            $(document).ready(function () {
                $('#toggleSidebar').on('click', function () {
                    $('#sidebar').toggleClass('-translate-x-full');
                });

                // (Opcional) Clica fora para fechar
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#sidebar, #toggleSidebar').length && $('#sidebar').hasClass('-translate-x-full') === false && $(window).width() < 768) {
                        $('#sidebar').addClass('-translate-x-full');
                    }
                });
            });
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            @if(@session()->get('success'))
                toastr.success("{{ session('success') }}", "Sucesso!");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if(session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            @if(session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };
        </script> 

    </body>

    <script>
        // Validar campos obrigatórios
        function validateFields() {
            console.log('chegamos aqui')
            console.log($(this))
            $(this).find('.required').each(function() {
                let valor = $(this).val().trim();

                // Remove mensagens de erro antigas
                $(this).next('.error-message').remove();

                if (valor === '') {
                    isValid = false;
                    $(this).after('<span class="error-message" style="color: red; font-size: 12px;">Este campo é obrigatório</span>');
                }
            });
        }


        // Modal de confirmação genérico
        function showModal(modalId, itemId = null, deleteUrl = null) {
            console.log('teste delete modal', modalId, itemId, deleteUrl);
            const modal = document.getElementById(modalId);
            console.log(modal, 'MODAL');
            if (itemId) modal.dataset.itemId = itemId;
            if (deleteUrl) modal.dataset.deleteUrl = deleteUrl;
            modal.classList.remove('hidden');
        }

        function hideModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function confirmDelete(modalId) {
            const modal = document.getElementById(modalId);
            const itemId = modal.dataset.itemId;
            const deleteUrl = modal.dataset.deleteUrl;

            $.ajax({
                url: deleteUrl.replace('?', '/'),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response, 'RESPONSE');
                    hideModal(modalId);
                    toastr.success('Membro deletado com sucesso!');
                    
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                },
                error: function(xhr) {
                    toastr.error('Erro ao deletar o membro.');
                    console.error(xhr);
                }
            });

            if (!deleteUrl) {
                console.error('Delete URL não definida para o modal.');
                return;
            }
        }
    </script>

    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .license-plate-mask {
            text-transform: uppercase;
        }
    </style>

</html>
