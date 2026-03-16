<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="flex justify-between items-center mt-4">
                    <div class="px-4 text-gray-900 bg-slate-100 ml-5 rounded-full h-6 font-semibold">
                        {{ __('Editar Produto') }}
                    </div>

                    <button class="btn-sm">
                        <a href="{{ route('products.index') }}"
                            class="mr-5 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </button>
                </div>

                <!-- component -->
                <form id="form-update-product" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 
                    <div class="bg-white rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-[50%] w-full px-3 mb-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Nome *
                                </label>
                                <input
                                    class="@if ($errors->has('name')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4"
                                    id="grid-first-name" type="text" placeholder="Digite o nome" name="name"
                                    @if (old('name')) value="{{ old('name') }}" @endif value="{{ $product->name }}">
                                @if ($errors->has('name'))
                                    <span class="text-red-500"><small>{{ $errors->first('name') }}</small></span>
                                @endif
                            </div>
                            <div class="md:w-[25%] px-3 mb-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Valor (R$) *
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-2 price-mask"
                                    id="grid-first-name" type="text" placeholder="R$ 00,00" name="price"
                                    @if (old('email')) value="{{ old('email') }}" @endif value="{{ $product->price }}">
                            </div>
                            <div class="md:w-[25%] px-3 mb-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Quantidade *
                                </label>
                                <input
                                    type="number"
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-2"
                                    id="grid-first-name" type="text" placeholder="Quantidade" name="stock"
                                    @if (old('email')) value="{{ old('email') }}" @endif value="{{ $product->stock }}">
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Modelo *
                                </label>
                                <div class="flex items-center space-x-3 md:space-x-2">
                                    <select name="modelo_id" id=""
                                        class="appearance-none block w-[85%] bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                        @if ($modelos->isEmpty())
                                            <option value="">Não há modelos</option>
                                        @else
                                            @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->id }}" {{ $product->modelo->id == $modelo->id ? 'selected' : '' }}>
                                                    {{ $modelo->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <button
                                        class="inline-flex -mt-3 items-center px-4 py-3 border border-transparent text-sm leading-5 rounded-md text-green-600 font-extrabold border-green-600 bg-white--600 hover:text-white hover:bg-green-600 focus:outline-none focus:border-green-700 focus:shadow-outline-blue active:bg-green-700 transition ease-in-out duration-150"
                                        onclick="newCategoryModal(event)" x-data="{ tooltip: 'Nova Categoria' }">
                                        {{ __('Nova') }}
                                    </button>
                                </div>

                            </div>

                            <div class="md:w-1/3 px-3 mb-6">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Tags
                                </label>
                                <input
                                    class="appearance-none block mb-2 w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Ex: tag1, tag2, tag3 ..."
                                    name="tags"
                                    @if (old('zip_code')) value="{{ old('zip_code') }}" @endif value="{{ $product->tags }}">
                                <p class="text-red text-xs italic">Separe as tags por vírgula.</p>
                            </div>


                            <div class="md:w-1/3 px-3 mb-6">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Cor
                                </label>
                                <input
                                    class="appearance-none block mb-2 w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Ex: tag1, tag2, tag3 ..."
                                    name="color"
                                    @if (old('color')) value="{{ old('color') }}" @endif value="{{ $product->color }}">
                            </div>

                        </div>

                        <div class="-mx-3 md:flex mb-6">

                            <div class="md:w-1/3 px-3 mb-5">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Propriedades
                                </label>

                                <div class="flex flex-col gap-2">

                                    <div class="flex gap-1 w-full">
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Ano
                                            </label>
                                            <input type="text" placeholder="Ano" class="w-full rounded-md" name="year" value="{{$product->properties->year}}">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Combustível
                                            </label>
                                            <input type="text" placeholder="Combustível" class="w-full rounded-md" name="fuel" value="{{$product->properties->fuel}}">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Placa final
                                            </label>
                                            <input type="text" placeholder="Placa final" class="w-full rounded-md" name="end_plate" value="{{$product->properties->end_plate}}">
                                        </div>
                                    </div>

                                    <div class="flex gap-1 w-full">

                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                KM
                                            </label>
                                            <input type="text" placeholder="KM" class="w-full rounded-md" name="km" value="{{$product->properties->km}}">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Câmbio
                                            </label>
                                            <input type="text" placeholder="Câmbio" class="w-full rounded-md" name="exchange" value="{{$product->properties->exchange}}">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Carroceria
                                            </label>
                                            <input type="text" placeholder="Carroceria" class="w-full rounded-md" name="bodywork" value="{{$product->properties->bodywork}}">
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Condições de pagamento
                                </label>

                                <div class="flex flex-wrap gap-2 mt-6">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" class="rounded" name="in_sight" @if($product->in_sight == 1) checked @endif>
                                        <label for="">À vista</label>
                                    </div>

                                    <div class="flex items-center gap-3 ml-16">
                                        <input type="checkbox" class="rounded" name="financing" @if($product->financing == 1) checked @endif>
                                        <label for="">Financiamento</label>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" class="rounded" name="consortium" @if($product->consortium == 1) checked @endif>
                                        <label for="">Consórcio</label>
                                    </div>
                                </div>
                            </div>


                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Descrição
                                </label>

                                <textarea name="description" placeholder="Descrição"
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="" cols="30" rows="3">{{$product->description}}</textarea>
                            </div>

                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-full px-3">
                                <div class="flex space-x-4 items-center mb-2">
                                    <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold"
                                        for="grid-last-name">
                                        Imagens
                                    </label>

                                    <button class="bg-green-200 p-1 rounded-sm px-2 hover:bg-green-400 transaction duration-500" id="btn-add-images">
                                        Adicionar
                                    </button>
                                </div>

                               

                                <div class="flex w-full flex-col">

                                    <div class="py-2 flex mt-5 flex-wrap">
                                        @foreach ($product->images as $image)
                                            <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $image->name }}" class="w-32 h-32 rounded-md shadow-lg cursor-pointer mr-2 mt-2 div-image" data-imageId="{{ $image->id }}">
                                        @endforeach

                                        <div class="mt-2 flex flex-wrap" id="previewImages">
                                        </div>
                                    </div>

                                    <input
                                    class="appearance-none w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 hidden"
                                    id="grid-last-name" type="file" placeholder="Informe a cidade" name="images[]"
                                    multiple>
                                    @if ($errors->has('image'))
                                        <span class="text-red-500"><small>{{ $errors->first('image') }}</small></span>
                                    @endif
    
                                    <input type="hidden" name="imagesToDelete" id="imagesToDelete">
                                    
                                </div>

                                
                            </div>
                        </div>

                        <script>

                            

                        </script>
                    </div>


                    <div class="flex w-full md:justify-end -mt-10 mb-4">
                        <button
                            type="submit"
                            id="editProductButton"
                            class="mr-7 ml-7 w-full md:w-[25%] justify-center h-12 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-blue active:bg-green-700 transition ease-in-out duration-150">
                            {{ __('Salvar') }}
                        </button>
                    </div>

                    <script>
                       
                    </script>


                </form>

            </div>
        </div>
    </div>

    <div id="newCategoryModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full fade-in"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="bg-slate-100 rounded-md text-center text-lg font-extrabold">Nova Categoria</h3>
                    <form action="{{ route('categories.store') }}" method="POST" id="newCategoryForm"
                        class="mt-4 form-group">
                        @csrf
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
                    <button onclick="confirmNewCategoryButton()" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Salvar
                    </button>
                    <button onclick="cancelNewCategoryButton()" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>




</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>

    let images = Array.from(document.querySelectorAll('.div-image'));
    let imagesForDelete = [];

    images.forEach(image => {
        $(image).click(function (e) { 
            e.preventDefault();
            let element = e.target;
            let imageId = element.dataset.imageid;

            if (element.style.border === '3px solid red') {
                imagesForDelete = imagesForDelete.filter(id => id !== imageId);
                element.style.border = 'none';
                return;
            } else {
                imagesForDelete.push(imageId);
                element.style.border = '3px solid red';
            } 

            document.getElementById('imagesToDelete').value = imagesForDelete.join(',');

        });
    });

    $('#btn-add-images').click(function(e){
        e.preventDefault();
        $('input[type="file"]').click();
    });
    

    // Preview da imagem
    $('input[type="file"]').change(function(e) {
        const files = e.target.files;

        $('#previewImages').html('');
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $('#previewImages').append(`<img src="${e.target.result}" class="w-32 h-32 shadow-md rounded-md mr-2 mb-2" style="border: 3px solid green">`);
            }

            reader.readAsDataURL(file);
        }
    });


    // $('#editProductButton').click(function(e) {
    //     e.preventDefault();
    //     console.log('clicou');

    //     $.ajax({
    //         type: "POST",
    //         url: "products",
    //         data: "data",
    //         dataType: "dataType",
    //         success: function (response) {
                
    //         }
    //     });
    // });



    function showToast(message, type) {
        Toastify({
            text: message,
            duration: 4000, // duração em milissegundos
            gravity: 'top', // posição do toast
            stopOnFocus: true,
            position: 'right', // alinhamento horizontal do toast
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
            //backgroundColor: type === 'success' ? 'green' : 'red', // cor de fundo do toast
        }).showToast();
    }

    // Função para mostrar um toast de sucesso
    function showSuccessToast(message) {
        showToast(message, 'success');
    }

    // Função para mostrar um toast de erro
    function showErrorToast(message) {
        showToast(message, 'error');
    }


    function newCategoryModal(event) {
        event.preventDefault();
        // const userId = event.target.closest('.delete-action-container').dataset.id;
        // document.querySelector('#deleteConfirmationModalClient').dataset.userId = userId;
        document.getElementById('newCategoryModal').classList.remove('hidden');
    }


    // Função para ocultar o modal de confirmação
    function hideNewCategoryConfirmationModal() {
        document.getElementById('newCategoryModal').classList.add('hidden');
    }

    // Evento de clique no botão de confirmar exclusão
   /*  function confirmNewCategoryButton() {
        const form = document.querySelector('#newCategoryForm');
        form.submit();
    } */

    // Evento de clique no botão de cancelar exclusão
    function cancelNewCategoryButton() {
        hideNewCategoryConfirmationModal();
    }
</script>

<!-- Seu código Blade para verificar mensagens de sucesso e exibir os toasts -->
@if (session('success'))
    <script>
        showSuccessToast("{{ session('success') }}");
    </script>
@endif


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
</style>
