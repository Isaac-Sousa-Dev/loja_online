<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modelos') }}
        </h2>
    </x-slot>

    <div class="py-2">

        <div class="mx-10">
            <div>

                <div class="flex justify-between items-center mt-4">
                    <div class="text-gray-900 bg-slate-100 rounded-full h-6 font-semibold">
                        {{ __('Adicionar Modelo') }}
                    </div>

                    <button class="btn-sm">
                        <a href="{{ route('modelos.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </button>
                </div>

                <!-- component -->
                <form action="{{ route('modelos.store')}}" method="POST">
                    @csrf
                    <div class="pt-6 pb-8 mb-4 flex flex-col">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 px-3 mb-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Nome *
                                </label>
                                <input
                                    class="@if($errors->has('name')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-2 px-3"
                                    id="grid-first-name" type="text" placeholder="Digite o nome" name="name" @if (old('name')) value="{{ old('name') }}" @endif
                                >
                                @if ($errors->has('name'))
                                    <span class="text-red-500"><small>{{ $errors->first('name') }}</small></span>
                                @endif
                            </div>
                            <div class="md:w-1/2 px-3 mb-3">
                                
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Subcategoria *
                                </label>
                                <div class="flex items-center">
                                    <select name="subcategory_id" id="subcategoriesSelect"
                                        class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-2 px-3 mb-3">
                                        @if ($subcategories->isEmpty())
                                            <option value="">Não há subcategorias</option>
                                        @else
                                            <option value="">Selecione uma categoria</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                   {{--  <button
                                        class="inline-flex -mt-3 items-center px-4 py-3 border border-transparent text-sm leading-5 rounded-md text-green-600 font-extrabold border-green-600 bg-white--600 hover:text-white hover:bg-green-600 focus:outline-none focus:border-green-700 focus:shadow-outline-blue active:bg-green-700 transition ease-in-out duration-150"
                                        id="btnOpenModalSubcategory" x-data="{ tooltip: 'Nova Subcategoria' }">
                                        {{ __('Nova') }}
                                    </button> --}}
                                </div>
    
                        
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            

                            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Descrição
                                </label>
                                <textarea
                                    rows="4"
                                    class="@if($errors->has('description')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-2 px-3"
                                    id="grid-first-name" type="text" placeholder="Digite a descrição" name="description">@if (old('description')) {{ old('description') }} @endif</textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-red-500"><small>{{ $errors->first('description') }}</small></span>
                                    @endif
                            </div>

                        </div>

                    </div>


                    <div class="w-[80%] justify-end flex mb-4 fixed bottom-0">
                        <button
                            class="w-full md:w-[25%] justify-center h-12 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-blue active:bg-green-700 transition ease-in-out duration-150">
                            {{ __('Salvar') }}
                        </button>
                    </div>


                </form>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="newCategoryModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full fade-in"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="bg-slate-100 rounded-md text-center text-lg font-extrabold">Nova Categoria</h3>
                    <form id="newCategoryForm"
                        class="mt-4 form-group">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                            <input type="text" placeholder="Digite o nome da categoria" name="name"
                                id="name" autocomplete="name"
                                class="mt-1 focus border-indigo-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full sm:text-sm">
                        </div>
                        <div class="mt-3">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea name="description" placeholder="Insira a descrição da categoria"
                                class="mt-1 focus border-indigo-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full sm:text-sm"
                                id="" cols="30" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button id="confirmNewCategory" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Salvar
                    </button>
                    <button id="btnCancelNewCategory" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    $('#btnOpenModalSubcategory').click(function(e){
        e.preventDefault();
        $('#newCategoryModal').removeClass('hidden');
    });

    $('#btnCancelNewCategory').click(function(e){
        $('#newCategoryModal').addClass('hidden');
    });

    $('#confirmNewCategory').click(function(e){
        e.preventDefault();
        const form = $('#newCategoryForm').serialize();
        $.ajax({
            type: "POST",
            url: "/categories/store",
            data: form,
            success: function (data) {
                console.log(data.categories);
                showSuccessToast("Categoria criada com sucesso!");
                $('#newCategoryModal').addClass('hidden');
                $('#newCategoryForm').trigger('reset');

                // Atualiza o select de categorias
                $('#subcategoriesSelect').empty();
                $('#subcategoriesSelect').append('<option value="">Selecione uma categoria</option>');
                data.categories.forEach(category => {
                    $('#subcategoriesSelect').append(`<option value="${category.id}">${category.name}</option>`);
                });

            },
            error: function (error) {
                console.log(error);
                showErrorToast("Erro ao criar categoria!");
            }
        });
    });

    function showSuccessToast(message) {
        showToast(message, 'success');
    }


    function showErrorToast(message) {
        showToast(message, 'error');
    }

    function showToast(message, type) {
        Toastify({
            text: message,
            duration: 4000,
            gravity: 'top', 
            stopOnFocus: true,
            position: 'right', 
            style: {
                background: type === 'success' ? 'green' : 'red',
                height: '50px',
                color: 'white',
                display: 'flex',
                margin: '10px',
                marginTop: '45px',
                padding: '20px',
                alignItems: 'center',
                justifyContent: 'center',
                position: 'absolute',
                right: '0',
                borderRadius: '10px',
                boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)',
                animation: 'slideInRight 0.5s',
                overflow: 'hidden',
            }
        }).showToast();
    }

</script>

@if (session('success'))
    <script>
        showSuccessToast("{{ session('success') }}");
    </script>
@endif
