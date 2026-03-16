<x-app-layout>
    <div class="flex md:justify-center">
        <div class="ml-3 mr-3 flex w-full md:w-[900px]">
            <div class="w-full md:min-w-[900px] max-w-[900px]">

                <div class="flex flex-col md:justify-between mt-4">

                    <div class="flex items-center justify-between">
                        <div class="text-gray-900 font-semibold text-3xl md:text-3xl ml-1">
                            {{ __('Marcas') }}
                        </div>

                        {{-- <a href="{{route('subcategories.index')}}" class="flex gap-1 items-center">
                            <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                            </svg>                               
                            Voltar                             
                        </a> --}}
                    </div>


                    <div class="flex flex-wrap md:items-center mt-3 gap-2">
                        <div class="w-full flex justify-between items-center mt-2">

                            <div></div>

                            <button class="flex" href="javascript:void(0)">
                                <a href="{{ route('subcategories.create') }}"
                                    class="inline-flex md:items-center gap-1 md:gap-2 px-4 py-[11px] md:px-2 md:py-[10px] border border-transparent text-sm leading-5 font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                      </svg>                                      
                                    {{ __('Nova Marca') }}
                                </a>
                            </button>
                        </div>

                    </div>

                </div>


                <!-- component -->
                @if($subcategoriesByPartner->isEmpty())
                    <div class="flex justify-center items-center h-12 mt-4">
                        <div class="text-gray-500 text-lg font-medium">
                            Nenhuma marca encontrada
                        </div>
                    </div>
                @else
                    <div class="overflow-auto md:overflow-hidden rounded-lg border border-gray-200 shadow-sm mt-3">

                        <div id="listCategoryStatic" class="bg-white">

                            

                            @foreach ($subcategoriesByPartner as $subcategoryByPartner)
                            <div class="py-2 border-b-gray-200 flex justify-between border-b md:flex">

                                <div class="flex md:w-[55%] md:px-2 px-3">
                                <div class="md:ml-2">
                                    <div class="w-full md:w-full font-medium text-blue-600 cursor-pointer hover:text-blue-900">
                                        <a href="{{ route('subcategories.edit', $subcategoryByPartner->subcategory->id) }}">
                                            {{$subcategoryByPartner->subcategory->name}}
                                        </a>
                                    </div>
                                </div>                       
                                </div>
                            
                                <div class="px-1 md:px-6 md:block">
                                    <div class="px-2 flex gap-2">
                                        <div>
                                            {{-- <button
                                            id="dropdown-button-{{ $brand["id"] }}"
                                            class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                            aria-expanded="false"
                                            aria-haspopup="true"
                                            >
                                            <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M7.926 10.898 15 7.727m-7.074 5.39L15 16.29M8 12a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm12 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0-11a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                                            </svg>
                                            
                                            </button> --}}
                                        </div>

                                        <div class="delete-action-container" data-id="{{ $subcategoryByPartner->subcategory->id }}">
                                            <button
                                            class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                            onclick="showDeleteConfirmationSubcategoryModal(event)" x-data="{ tooltip: 'Delete' }"
                                            >
                                            <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                            </svg>
                                            
                                            </button>
                                            <form action="{{ route('subcategories.destroy', $subcategoryByPartner->subcategory->id) }}" class="deleteFormSubcategory" method="POST">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </div>

                                    </div>
                                </div>

                            </div>    
                                        
                            @endforeach
                        </div>

                        {{-- <div id="listCategoryDinamic" style="display: none" class="bg-white"></div> --}}
                        
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Modal de confirmação -->
    <div id="deleteConfirmationModalSubcategory" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center mt-72 justify-center md:mt-0 px-4">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom w-full md:w-1/4 bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-2 pb-2">
                    <div class="mt-1">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <!-- Heroicon name: exclamation -->
                            <svg class="w-6 h-6 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                              </svg>
                              
                        </div>
                        <div class="mt-2 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Excluir marca
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tem certeza de que deseja excluir esta marca? Esta ação não pode ser desfeita.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex gap-2 justify-between">      
                    <div>
                        <button onclick="cancelDeleteSubcategoryButton()"  type="button"
                            class="w-full justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                    </div>
                    <div class="">
                        <button onclick="confirmDeleteSubcategoryButton()" id="confirmDeleteSubcategoryButton" type="button"
                            class="w-full justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>

    // Função para mostrar o modal de confirmação
    function showDeleteConfirmationSubcategoryModal(event) {
        const subcategoryId = event.target.closest('.delete-action-container').dataset.id;
        document.querySelector('#deleteConfirmationModalSubcategory').dataset.subcategoryId = subcategoryId;
        document.getElementById('deleteConfirmationModalSubcategory').classList.remove('hidden');
    }

    // Função para ocultar o modal de confirmação
    function hideDeleteConfirmationModal() {
        document.getElementById('deleteConfirmationModalSubcategory').classList.add('hidden');
    }

    // Evento de clique no botão de confirmar exclusão
    function confirmDeleteSubcategoryButton() {
        const subcategoryId = document.querySelector('#deleteConfirmationModalSubcategory').dataset.subcategoryId;
        const form = document.querySelector('.deleteFormSubcategory');
        form.action = `/subcategories/destroy/${subcategoryId}`
        form.submit();
    }

    // Evento de clique no botão de cancelar exclusão
    function cancelDeleteSubcategoryButton() {
        hideDeleteConfirmationModal();
    }
</script>

<style>
    /* Animação de entrada */
    @keyframes slideInRight {
        from {
            transform: translateX(1%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
