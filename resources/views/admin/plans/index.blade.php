<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div class="">

                <div class="flex items-center md:justify-between mt-4">

                    <div class="flex items-center justify-between">
                        <h2 class="font-display font-semibold text-2xl mb-3 mt-3 text-[#33363B]">
                            {{ __('Planos') }}
                        </h2>
                    </div>


                    <div class="flex flex-wrap md:items-center gap-2">
                        <div class="w-full flex justify-between items-center">
                            <button class="flex" href="javascript:void(0)">
                                <a href="{{ route('plans.create') }}"
                                    class="inline-flex md:items-center gap-1 md:gap-2 px-4 py-[11px] md:px-2 md:py-[10px] border border-transparent text-sm leading-5 font-semibold rounded-xl text-white bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-[#6A2BBA]/25">
                                    <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                      </svg>                                      
                                    {{ __('Novo Plano') }}
                                </a>
                            </button>
                        </div>
                    </div>

                </div>


                <!-- component -->
                <div class="overflow-auto rounded-lg border border-[#33363B]/10 shadow-md mt-8 ring-1 ring-[#33363B]/5">
                    <div class="hidden md:block">
                        <div class="px-4 py-2 bg-[#F8F9FC] border-b border-[#6A2BBA]/10 shadow-sm grid grid-cols-4 gap-4">
                            <div class="font-semibold text-[#33363B]">Dados</div>
                            <div class="font-semibold text-[#33363B]">Preço</div>
                            <div class="font-semibold text-[#33363B]">Combo</div>
                            <div class="font-semibold text-right text-[#33363B]">Ações</div>
                        </div>
                    </div>
                
                    <div id="listProductStatic" class="bg-white w-full">
                        @if($allPlans->isEmpty())
                            <div class="flex justify-center items-center h-14">
                                <div class="text-gray-400 text-lg font-semibold">
                                    Não há planos cadastrados
                                </div>
                            </div>
                        @else
                            @foreach ($allPlans as $plan)
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-4 py-2 border-b border-[#33363B]/8 hover:bg-[#F8F9FC]/80">
                                <div class="flex items-center">
                                    <div>
                                        <div class="font-bold text-[#6A2BBA] hover:text-[#D131A3]">
                                            <a href="#">{{ $plan->name }}</a>
                                        </div>
                                        <div class="text-xs @if($plan->status == 'active') text-green-600 @else text-red-500 @endif font-semibold"> 
                                            {{$plan->status == 'active' ? 'Ativo' : 'Inativo'}}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center italic text-sm md:text-md font-semibold">
                                    R$ <span class="price-mask ml-1">{{ $plan->price }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 items-center italic text-sm md:text-md font-medium">
                                    @if($plan->modules->isEmpty())
                                        <span class="px-1 font-semibold rounded-md text-xs">Nenhum módulo encontrado</span>
                                    @else
                                        @foreach($plan->modules as $module)
                                            <span class="bg-[#6A2BBA] text-white px-1.5 py-0.5 font-semibold rounded-md text-xs">{{ $module->module }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="flex items-center justify-end">
                                    <div class="delete-action-container" data-id="{{ $plan->id }}">
                                        <button
                                            class="bg-gray-100 border border-gray-400 p-2 rounded-full flex justify-center items-center hover:bg-gray-200"
                                            onclick="showDeleteConfirmationModal(event)"
                                            x-data="{ tooltip: 'Delete' }"
                                        >
                                            <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('plans.destroy', $plan->id) }}" class="deleteFormProduct" method="POST">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de confirmação -->
    <div id="deleteConfirmationModalClient" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Heroicon name: exclamation -->
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 3h13.856c1.54 0 2.288-.738 2.94-1.386.653-.648 1.386-1.397 1.386-2.937v-6.354c0-1.54-.738-2.288-1.386-2.94-.648-.653-1.397-1.386-2.937-1.386H8.062c-1.54 0-2.288.738-2.94 1.386-.653.652-1.386 1.4-1.386 2.94v6.353c0 1.54.738 2.288 1.386 2.94.652.648 1.4 1.386 2.94 1.386z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Excluir cliente
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tem certeza de que deseja excluir este cliente? Esta ação não pode ser desfeita.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="confirmDeleteButton()" id="confirmDeleteButton" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Excluir
                    </button>
                    <button onclick="cancelDeleteButton()"  type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endsection

</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
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
                marginTop: '63px',
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

    // Função para mostrar o modal de confirmação
    function showDeleteConfirmationModal(event) {
        const userId = event.target.closest('.delete-action-container').dataset.id;
        document.querySelector('#deleteConfirmationModalClient').dataset.userId = userId;
        document.getElementById('deleteConfirmationModalClient').classList.remove('hidden');
    }

    // Função para ocultar o modal de confirmação
    function hideDeleteConfirmationModal() {
        document.getElementById('deleteConfirmationModalClient').classList.add('hidden');
    }

    // Evento de clique no botão de confirmar exclusão
    function confirmDeleteButton() {
        const userId = document.querySelector('#deleteConfirmationModalClient').dataset.userId;
        const form = document.querySelector('#deleteFormClient');
        form.action = `/clients/destroy/${userId}`
        form.submit();
    }

    // Evento de clique no botão de cancelar exclusão
    function cancelDeleteButton() {
        hideDeleteConfirmationModal();
    }
</script>

<!-- Seu código Blade para verificar mensagens de sucesso e exibir os toasts -->
@if(session('success'))
    <script>
        showSuccessToast("{{ session('success') }}");
    </script>
@endif

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
