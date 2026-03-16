<x-app-layout>

    <div class="py-2 px-2 flex justify-center">

        <div class="w-full max-w-[900px] mt-4">

            <h2 class="font-semibold text-3xl px-1 text-gray-800 leading-tight">
                {{ $storeSubcategory->subcategory->name }}
            </h2>

            <div class="p-1 mt-3">

                <form id="brandFormUpdate" action="{{ route('subcategories.update', $storeSubcategory->subcategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded-xl shadow-md pt-3 pb-2 mb-4 flex flex-col bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Nome
                        </div>
                        <div>
                            <div class="w-full mb-3">
                                <x-input-label for="name" :value="__('Nome *')" />
                                <x-text-input id="name-subcategory" value="{{$storeSubcategory->subcategory->name}}" class="required" placeholder="Honda, Chevrolet..." name="name" type="text" autofocus autocomplete="name" />
                                {{-- <x-input-error class="mt-2" :messages="$errors->get('name')" /> --}}
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            <div class="w-full justify-end flex mb-4 gap-2">
                <div class="flex w-full md:w-2/6 justify-between gap-3 py-2 px-3 bg-white rounded-xl">
                    <x-secondary-button id="">
                        <a href="{{ route('subcategories.index') }}">
                            {{ __('Cancelar') }}
                        </a>          
                    </x-secondary-button>
                    
                    <x-primary-button id="btnUpdateDataSubcategory">{{ __('Salvar') }}</x-primary-button>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>


<script>

    // Prevenir o envio do formulário ao pressionar "Enter"
    document.getElementById('brandFormUpdate').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    $('#btnUpdateDataSubcategory').click(function(e) {
        e.preventDefault();
        showLoader();

        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('name', $('#name-subcategory').val());
        formData.append('subcategory_id', '{{ $storeSubcategory->subcategory->id }}');

        // Envie a solicitação AJAX
        $.ajax({
            url: '{{ route('subcategories.update', $storeSubcategory->subcategory->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                hideLoader();
                window.location.href = '{{ route('subcategories.index') }}';
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) { // Erros de validação
                    let errors = xhr.responseJSON.errors;

                    // Remover mensagens de erro antigas
                    $(".error-message").remove();

                    // Exibir erros para cada campo
                    $.each(errors, function(field, messages) {
                        let input = $(`[name="${field}"]`);

                        if (input.length > 0) {
                            input.css('border', '1px solid red'); // Destacar o campo com erro
                            input.after(`<span class="error-message text-red-500 text-sm">${messages[0]}</span>`); // Exibir mensagem de erro
                        }
                    });
                } else {
                    // Exibir erro global (se houver)
                    toastr.error(xhr.responseJSON.message || 'Ocorreu um erro inesperado.');
                }
                hideLoader();
            }
        });
    })

</script>

<style>
    /* Classe personalizada para o Toast */
    .custom-toast {
        animation: slideIn 0.5s ease-out, slideOut 0.5s ease-in 3.5s; /* Entrada e saída */
        will-change: transform, opacity; /* Melhor performance */
    }
</style>
