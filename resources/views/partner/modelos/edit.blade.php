<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clientes') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="flex justify-between items-center mt-4">
                    <div class="px-4 text-gray-900 bg-slate-100 ml-5 rounded-full h-6 font-semibold">
                        {{ __('Editar Modelo') }}
                    </div>

                    <button class="btn-sm">
                        <a href="{{ route('modelos.index') }}"
                            class="mr-5 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </button>
                </div>

                <!-- component -->
                <form action="{{ route('modelos.update', $modelo->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 px-3 mb-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Nome *
                                </label>
                                <input
                                    class="@if($errors->has('name')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4"
                                    id="grid-first-name" type="text" placeholder="Digite o nome" name="name" value="{{ $modelo->name }}" @if (old('name')) value="{{ old('name') }}" @endif
                                >
                                @if ($errors->has('name'))
                                    <span class="text-red-500"><small>{{ $errors->first('name') }}</small></span>
                                @endif
                            </div>
                            <div class="md:w-1/2 px-3 mb-3">
                                
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Subcategoria *
                                </label>
                                <div class="flex items-center space-x-3 md:space-x-2">
                                    <select name="subcategory_id" id="subcategoriesSelect"
                                        class="appearance-none block w-[85%] bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3">
                                        @if ($subcategories->isEmpty())
                                            <option value="">Não há subcategorias</option>
                                        @else
                                            <option value="">Selecione uma categoria</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}" {{ $subcategory->id == $modelo->subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
    
                        
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            

                            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Descrição
                                </label>
                                <textarea
                                    rows="4"
                                    class="@if($errors->has('description')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-first-name" type="text" placeholder="Digite a descrição" name="description">@if (old('description')) {{ old('description') }} @endif {{ $modelo->description }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-red-500"><small>{{ $errors->first('description') }}</small></span>
                                    @endif
                            </div>

                        </div>

                    </div>


                    <div class="flex w-full md:justify-end -mt-10 mb-4">
                        <button
                            class="mr-7 ml-7 w-full md:w-[25%] justify-center h-12 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-blue active:bg-green-700 transition ease-in-out duration-150">
                            {{ __('Salvar') }}
                        </button>
                    </div>


                </form>

            </div>
        </div>
    </div>


</x-app-layout>

<script>

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
