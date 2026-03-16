<x-app-layout>

    <div class="py-2 px-2 flex justify-center">

        <div class="w-full max-w-[900px] mt-4">

            <h2 class="font-semibold text-3xl px-1 text-gray-800 leading-tight">
                {{ __('Nova Categoria') }}
            </h2>

            <div class="p-1 mt-3">

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Nome e descrição
                        </div>
                        <div>
                            <div class="w-full mb-3">
                                <x-input-label for="name" :value="__('Nome *')" />
                                <x-text-input id="name-category" class="required" placeholder="Carros, Motos..." name="name-category" type="text" autofocus autocomplete="name" />
                                {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                            </div>
                        </div>

                        <div class="md:flex mb-0">
                            <div class="w-full mb-3">
                                <x-input-label for="description" :value="__('Descrição')" />
                                <x-textarea id="description-category" class="required" placeholder="Descreva algo..." name="description-category" type="text" autofocus autocomplete="name" ></x-textarea>
                                {{-- <x-input-error class="mt-2" :messages="$errors->get('description')" /> --}}
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            <div class="w-full justify-end flex mb-4 gap-2">
                <div class="flex w-full md:w-2/6 justify-between gap-3 py-2 px-3 bg-white rounded-xl">
                    <x-secondary-button id="">
                        <a href="{{ route('categories.index') }}">
                            {{ __('Cancelar') }}
                        </a>          
                    </x-secondary-button>
                    
                    <x-primary-button id="btnSaveDataCategory">{{ __('Salvar') }}</x-primary-button>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>


<script>

    $('#btnSaveDataCategory').click(function(e) {
        e.preventDefault();
        showLoader();

        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('name', $('#name-category').val());
        formData.append('description', $('#description-category').val()); 

        // Envie a solicitação AJAX
        $.ajax({
            url: '{{ route('categories.store') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoader();
                window.location.href = '{{ route('categories.index') }}';
            },
            error: function(xhr, status, error) {

                console.log(xhr.status);

                if (xhr.status === 422) { // Código de erro para validação do Laravel
                    let errors = xhr.responseJSON.errors;

                    // Remover mensagens de erro antigas antes de exibir novas
                    $(".error-message").remove();

                    $.each(errors, function(field, messages) {
                        let input = $(`[name="${field}"]`);
    
                        if (input.length === 0) {
                            console.warn(`Campo não encontrado: ${field}`);
                        }


                        if (input.length > 0) {
                            input.css('border', '1px solid red');

                            // Se o input estiver encapsulado, encontrar o elemento correto para inserir a mensagem
                            if (input.parent().hasClass('relative')) {
                                input.parent().after(`<span class="error-message" style="color: red; font-size: 12px;">${messages[0]}</span>`);
                            } else {
                                input.after(`<span class="error-message" style="color: red; font-size: 12px;">${messages[0]}</span>`);
                            }
                        }
                    });
                }

                toastr.error(xhr.responseJSON.message);               
                hideLoader();
            }
        });
    })


    $('#btn-add-images').click(function(e){
        e.preventDefault();
        $('input[type="file"]').click();
    });

</script>

<style>
    /* Efeito de fade-in para o modal */
    .fade-in {
        animation: fadeIn ease 0.5s;
        -webkit-animation: fadeIn ease 0.5s;
        -moz-animation: fadeIn ease 0.5s;
        -o-animation: fadeIn ease 0.5s;
        -ms-animation: fadeIn ease 0.5s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-moz-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-webkit-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-o-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @-ms-keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }




    /* Animação de entrada */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-100%); /* Começa fora da tela (acima) */
        }
        to {
            opacity: 1;
            transform: translateY(0); /* Entra no centro */
        }
    }

    /* Animação de saída */
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0); /* Começa no centro */
        }
        to {
            opacity: 0;
            transform: translateY(-100%); /* Sai para fora da tela (acima) */
        }
    }

    /* Classe personalizada para o Toast */
    .custom-toast {
        animation: slideIn 0.5s ease-out, slideOut 0.5s ease-in 3.5s; /* Entrada e saída */
        will-change: transform, opacity; /* Melhor performance */
    }


</style>
