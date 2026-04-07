<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <div class="py-2 px-2 flex justify-center pb-24 md:pb-0">

            <div class="flex flex-col w-full max-w-[900px]">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-1 px-1" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <a href="{{ route('categories.index') }}" class="hover:text-blue-600 transition-colors">Categorias</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span
                        class="font-semibold text-gray-700 truncate max-w-[200px]">{{ $storeCategory->category->name }}</span>
                </nav>

                {{-- Title + Back button --}}
                <div class="flex items-center gap-3 mt-2 mb-1 px-1">
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-400 hover:shadow-md transition-all"
                        title="Voltar para Categorias">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                    <h2 class="font-display font-semibold text-2xl text-[#33363B] leading-tight">
                        {{ $storeCategory->category->name }}
                    </h2>
                </div>

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
                                    <x-text-input id="name-category-update" class="required"
                                        placeholder="Camisetas, Jeans..." value="{{ $storeCategory->category->name }}"
                                        name="name-category-update" type="text" autofocus autocomplete="name" />
                                    {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                                </div>
                            </div>

                            <div class="md:flex mb-0">
                                <div class="w-full mb-3">
                                    <x-input-label for="description" :value="__('Descrição')" />
                                    <x-textarea id="description-category-update" class="required"
                                        placeholder="Descreva algo..." name="description-category-update" type="text"
                                        autofocus autocomplete="name">
                                        {{ $storeCategory->description }}
                                    </x-textarea>
                                    {{-- <x-input-error class="mt-2" :messages="$errors->get('description')" /> --}}
                                </div>
                            </div>

                        </div>

                        {{-- Imagem da Categoria --}}
                        <div class="rounded-xl shadow-md pt-3 pb-4 mb-4 flex flex-col bg-white px-3">
                            <div class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fa-solid fa-camera text-blue-600 mr-2"></i> Imagem (Opcional)
                            </div>
                            <div class="w-full">
                                <label for="image-category"
                                    class="w-full md:w-1/2 flex flex-col items-center justify-center p-4 border-2 border-dashed border-sky-500 rounded-lg cursor-pointer bg-blue-50 text-gray-600 relative overflow-hidden h-40">
                                    <div id="image-preview-container"
                                        class="absolute inset-0 {{ $storeCategory->image_url ? '' : 'hidden' }}">
                                        <img id="image-preview"
                                            src="{{ $storeCategory->image_url ? asset('storage/' . $storeCategory->image_url) : '' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div id="image-placeholder"
                                        class="flex flex-col items-center {{ $storeCategory->image_url ? 'hidden' : '' }}">
                                        <svg class="w-8 h-8 mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="currentColor"
                                                d="M149.1 64.8L138.7 96H64C28.7 96 0 124.7 0 160V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H373.3L362.9 64.8C356.4 45.2 338.1 32 317.4 32H194.6c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                                        </svg>
                                        <span class="text-sm font-semibold text-center">Adicionar foto da categoria</span>
                                    </div>
                                </label>
                                <input type="file" id="image-category" name="image-category" accept="image/*"
                                    class="hidden" onchange="previewImageCategory(event)">
                            </div>
                        </div>
                    </form>

                </div>

                <div
                    class="fixed bottom-0 md:rounded-2xl left-0 w-full z-20 bg-white border-t border-gray-200 p-3 md:static md:bg-transparent md:border-none md:p-0 flex md:justify-end md:mb-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:shadow-none">
                    <div class="flex w-full md:w-[280px] justify-between gap-3 md:py-1 md:px-3 bg-white rounded-xl">
                        <x-secondary-button id="" class="w-full justify-center md:w-auto">
                            <a href="{{ route('categories.index') }}" class="w-full text-center">
                                {{ __('Cancelar') }}
                            </a>
                        </x-secondary-button>

                        <x-primary-button id="btnUpdateDataCategory"
                            class="w-full justify-center md:w-auto">{{ __('Salvar') }}</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    @endsection


</x-app-layout>


<script>
    function previewImageCategory(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview-container').classList.remove('hidden');
                document.getElementById('image-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    $('#btnUpdateDataCategory').click(function(e) {
        e.preventDefault();
        showLoader();

        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('name', $('#name-category-update').val());
        formData.append('description', $('#description-category-update').val());
        formData.append('category_id', '{{ $storeCategory->category->id }}');

        const imageFile = $('#image-category')[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        // Envie a solicitação AJAX
        $.ajax({
            url: '{{ route('categories.update', $storeCategory->category->id) }}',
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
                                input.parent().after(
                                    `<span class="error-message" style="color: red; font-size: 12px;">${messages[0]}</span>`
                                );
                            } else {
                                input.after(
                                    `<span class="error-message" style="color: red; font-size: 12px;">${messages[0]}</span>`
                                );
                            }
                        }
                    });
                }

                toastr.error(xhr.responseJSON.message);
                hideLoader();
            }
        });
    })


    $('#btn-add-images').click(function(e) {
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
            transform: translateY(-100%);
            /* Começa fora da tela (acima) */
        }

        to {
            opacity: 1;
            transform: translateY(0);
            /* Entra no centro */
        }
    }

    /* Animação de saída */
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
            /* Começa no centro */
        }

        to {
            opacity: 0;
            transform: translateY(-100%);
            /* Sai para fora da tela (acima) */
        }
    }

    /* Classe personalizada para o Toast */
    .custom-toast {
        animation: slideIn 0.5s ease-out, slideOut 0.5s ease-in 3.5s;
        /* Entrada e saída */
        will-change: transform, opacity;
        /* Melhor performance */
    }
</style>
