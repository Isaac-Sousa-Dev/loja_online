<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>
  
    <div class="py-2">

        <div class="mx-10">
            <div>

                <div class="flex justify-between items-center mt-2">
                    <div class="ml-1 text-gray-900 rounded-full h-6 font-semibold">
                        {{ __('Adicionar Produto') }}
                    </div>

                    <button>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </button>
                </div>

                <!-- component -->
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded pt-6 pb-8 mb-4 flex flex-col">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-[50%] w-full px-3 mb-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Nome do produto *
                                </label>
                                <input
                                    class="@if ($errors->has('name')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3"
                                    id="grid-first-name" type="text" placeholder="Digite o nome" name="name"
                                    @if (old('name')) value="{{ old('name') }}" @endif>
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
                                    class="@if ($errors->has('price')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3 price-mask"
                                    id="input-price" type="text" placeholder="R$ 00,00" name="price"
                                    @if (old('price')) value="{{ old('price') }}" @endif>
                                    @if ($errors->has('price'))
                                        <span class="text-red-500"><small>{{ $errors->first('price') }}</small></span>
                                    @endif
                            </div>
                            <div class="md:w-[25%] px-3 mb-3">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Quantidade
                                </label>
                                <input
                                    type="number"
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3 mb-2"
                                    id="grid-first-name" type="text" placeholder="Quantidade" name="stock"
                                    @if (old('email')) value="{{ old('email') }}" @endif>
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-0">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Modelo *
                                </label>
                                <div class="flex items-center space-x-3 md:space-x-2">
                                    <select name="modelo_id" id="categoriesSelect"
                                        class="appearance-none block w-[85%] bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3 mb-3">
                                        @if ($modelos->isEmpty())
                                            <option value="">Não há modelos</option>
                                        @else
                                            <option value="">Selecione um modelo</option>
                                            @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->id }}">{{ $modelo->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>

                            <div class="md:w-1/3 px-3 mb-5">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Tags
                                </label>

                                <input
                                    class="appearance-none block mb-2 w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3"
                                    id="grid-last-name" type="text" placeholder="Ex: tag1, tag2, tag3 ..."
                                    name="tags"
                                    @if (old('zip_code')) value="{{ old('zip_code') }}" @endif>
                                <p class="text-red text-xs italic">Separe as tags por vírgula.</p>
                            </div>


                            <div class="md:w-1/3 px-3 mb-5">
                                <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Cor
                                </label>
                                <input
                                    class="appearance-none block mb-2 w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3"
                                    id="grid-last-name" type="text" placeholder="Cor do produto"
                                    name="color"
                                >
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
                                            <input type="text" placeholder="Ano" class="w-full rounded-md border border-gray-300" name="year">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Combustível
                                            </label>
                                            <input type="text" placeholder="Combustível" class="w-full rounded-md border border-gray-300" name="fuel">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Placa final
                                            </label>
                                            <input type="text" placeholder="Placa final" class="w-full rounded-md border border-gray-300" name="end_plate">
                                        </div>
                                    </div>

                                    <div class="flex gap-1 w-full">

                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                KM
                                            </label>
                                            <input type="text" placeholder="KM" class="w-full rounded-md border border-gray-300" name="km">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Câmbio
                                            </label>
                                            <input type="text" placeholder="Câmbio" class="w-full rounded-md border border-gray-300" name="exchange">
                                        </div>
                                        <div>
                                            <label class="block uppercase tracking-wide text-grey-darker text-[9px] font-bold mb-1"
                                                for="grid-first-name">
                                                Variação/Detalhes
                                            </label>
                                            <input type="text" placeholder="Variação/Detalhes" class="w-full rounded-md border border-gray-300" name="bodywork">
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
                                        <input type="checkbox" class="rounded" name="in_sight">
                                        <label for="">À vista</label>
                                    </div>

                                    <div class="flex items-center gap-3 ml-16">
                                        <input type="checkbox" class="rounded" name="financing">
                                        <label for="">Financiamento</label>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" class="rounded" name="consortium">
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
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 rounded px-3 mb-3"
                                    id="" cols="30" rows="4"></textarea>
                            </div>

                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            


                            <div class="md:w-full px-3">

                                <div class="flex space-x-4 items-center mb-2">
                                    <label class="block uppercase tracking-wide text-blue-700 text-xs font-bold"
                                        for="grid-last-name">
                                        Imagens
                                    </label>

                                    <button class="bg-green-200 p-1 rounded-md px-2" id="btn-add-images">
                                        Adicionar
                                    </button>
                                </div>

                                <input
                                    class="appearance-none w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4 hidden"
                                    id="grid-last-name" type="file" placeholder="Informe a cidade" name="images[]"
                                    @if (old('city')) value="{{ old('city') }}" @endif multiple>

                                    @if ($errors->has('image'))
                                        <span class="text-red-500"><small>{{ $errors->first('image') }}</small></span>
                                    @endif

                                    <div class="mt-6 flex flex-wrap w-full" id="previewImages">

                                    </div>

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


</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>

    $(document).ready(function() {
        $('.price-mask').mask('000.000.000.000.000,00', {reverse: true});
    });


    $('#btn-add-images').click(function(e){
        e.preventDefault();
        $('input[type="file"]').click();
    });


    // Preview da imagem
    $('input[type="file"]').change(function(e) {
        const files = e.target.files;
        $('#previewImages').html(''); // Limpa a pré-visualização anterior
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $('#previewImages').append(`<img src="${e.target.result}" class="w-32 h-32 shadow-md rounded-md mr-2 mb-2">`);
            }

            reader.readAsDataURL(file);
        }
    });


    
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


    function showSuccessToast(message) {
        showToast(message, 'success');
    }


    function showErrorToast(message) {
        showToast(message, 'error');
    }

</script>

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
