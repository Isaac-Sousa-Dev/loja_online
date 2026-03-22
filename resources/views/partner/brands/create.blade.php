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
                    <a href="{{ route('brands.index') }}" class="hover:text-blue-600 transition-colors">Marcas</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-semibold text-gray-700">Nova Marca</span>
                </nav>

                {{-- Title + Back button --}}
                <div class="flex items-center gap-3 mt-2 mb-1 px-1">
                    <a href="{{ route('brands.index') }}"
                        class="flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-400 hover:shadow-md transition-all"
                        title="Voltar para Marcas">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                    </a>
                    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                        {{ __('Nova Marca') }}
                    </h2>
                </div>

                <div class="p-1 mt-3">

                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                            <div class="text-lg font-semibold text-gray-800 mb-3">
                                Informações da Marca
                            </div>
                            <div>
                                <div class="w-full mb-3">
                                    <x-input-label for="name" :value="__('Nome *')" />
                                    <x-text-input id="name-brand" class="mt-1 block w-full required" placeholder="Nome da marca"
                                        name="name-brand" type="text" autofocus autocomplete="name" />
                                </div>
                            </div>

                        </div>

                        {{-- Imagem da Marca --}}
                        <div class="rounded-xl shadow-md pt-3 pb-4 mb-4 flex flex-col bg-white px-3">
                            <div class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fa-solid fa-camera text-blue-600 mr-2"></i> Logo (Opcional)
                            </div>
                            <div class="w-full">
                                <label for="logo_brand"
                                    class="w-full md:w-1/2 flex flex-col items-center justify-center p-4 border-2 border-dashed border-sky-500 rounded-lg cursor-pointer bg-blue-50 text-gray-600 relative overflow-hidden h-40">
                                    <div id="image-preview-container" class="absolute inset-0 hidden">
                                        <img id="image-preview" class="w-full h-full object-cover">
                                    </div>
                                    <div id="image-placeholder" class="flex flex-col items-center">
                                        <svg class="w-8 h-8 mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="currentColor"
                                                d="M149.1 64.8L138.7 96H64C28.7 96 0 124.7 0 160V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H373.3L362.9 64.8C356.4 45.2 338.1 32 317.4 32H194.6c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                                        </svg>
                                        <span class="text-sm font-semibold text-center">Adicionar logo da marca</span>
                                    </div>
                                </label>
                                <input type="file" id="logo_brand" name="logo_brand" accept="image/*"
                                    class="hidden" onchange="previewImageBrand(event)">
                            </div>
                        </div>
                    </form>

                </div>

                <div
                    class="fixed bottom-0 md:rounded-2xl left-0 w-full z-20 bg-white border-t border-gray-200 p-3 md:static md:bg-transparent md:border-none md:p-0 flex md:justify-end md:mb-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:shadow-none">
                    <div class="flex w-full md:w-[280px] justify-between gap-3 md:py-1 md:px-3 bg-white rounded-xl">
                        <x-secondary-button id="" class="w-full justify-center md:w-auto">
                            <a href="{{ route('brands.index') }}" class="w-full text-center">
                                {{ __('Cancelar') }}
                            </a>
                        </x-secondary-button>

                        <x-primary-button id="btnSaveDataBrand"
                            class="w-full justify-center md:w-auto">{{ __('Salvar') }}</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    @endsection


</x-app-layout>

<script>
    function previewImageBrand(event) {
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

    $('#btnSaveDataBrand').click(function(e) {
        e.preventDefault();
        showLoader();

        const formData = new FormData();
        formData.append('name', $('#name-brand').val());

        const imageFile = $('#logo_brand')[0].files[0];
        if (imageFile) {
            formData.append('logo_brand', imageFile);
        }

        $.ajax({
            url: '{{ route('brands.store') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoader();
                window.location.href = '{{ route('brands.index') }}';
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

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                }
                
                hideLoader();
            }
        });
    })

</script>
