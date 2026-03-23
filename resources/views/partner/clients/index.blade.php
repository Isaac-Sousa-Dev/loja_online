<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

<x-app-layout>
    <div class="flex md:justify-center">
        <div class="ml-3 mr-3 flex w-full md:justify-center">
            <div class="w-full md:min-w-[900px] max-w-[900px]">

                <div class="flex justify-between items-center mt-4">
                    <div class="text-gray-900 h-6 font-semibold text-3xl">
                        {{ __('Clientes') }}
                    </div>
                </div>


                <!-- component -->
                <div class="overflow-auto md:overflow-hidden rounded-lg border border-gray-200 shadow-md mt-8">

                    <div class="hidden md:block">
                        <div class="px-2 py-1 bg-gray-50 shadow-sm flex gap-3">
                            <div class="font-semibold w-[85%]">
                                Dados
                            </div>
                            {{-- <div class="font-semibold w-[40%]">
                                Telefone
                            </div> --}}
                            <div class="font-semibold w-[15%]">
                                Ações
                            </div>
                        </div>
                    </div>

                    <div id="listProductStatic" class="bg-white w-full">

                        @if($clients->isEmpty())
                            <div class="flex justify-center items-center h-14">
                                <div class="text-gray-400 text-lg font-semibold">
                                    Não há clientes no momento
                                </div>
                            </div>
                        @endif

                        @foreach ($clients as $client)
                        <div class="listProductStatic py-2 border-b-gray-200 border-b md:flex">

                            <div class="flex md:w-1/3 px-1 md:px-2">
                              <div class="ml-2 w-full">
                                  <div class=" font-bold text-blue-600 cursor-pointer hover:text-blue-900">
                                    <a href="">
                                        {{$client->client->name}}
                                    </a>
                                  </div>
                                  <div class="italic text-sm md:text-md font-medium">
                                    {{$client->client->phone}}
                                  </div>
                              </div>
                          
                              <div class="md:hidden flex justify-end">
                                <div class="px-2 flex gap-1">
                                    <div>
                                        {{-- <button
                                          id="dropdown-button-{{ $client->id }}"
                                          class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                          aria-expanded="false"
                                          aria-haspopup="true"
                                        >
                                        <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                                            <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
                                        </svg>
                                          
                                          
                                        </button> --}}
                                    </div>

                                    <div class="delete-action-container" data-id="{{ $client->id }}">
                                        {{-- <button
                                          class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                          onclick="showDeleteConfirmationModal(event)" x-data="{ tooltip: 'Delete' }"
                                        >
                                        <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4"/>
                                        </svg>
                                          
                                          
                                        </button>
                                        <form action="{{ route('products.destroy', $client->id) }}" class="deleteFormProduct" method="POST">
                                            @method('DELETE')
                                            @csrf
                                        </form> --}}
                                    </div>

                                </div>
                              </div>
                            </div>
                          
                            <div class=" md:w-2/3 flex mt-0">
                                <div class="flex px-3 w-full">

                                    <div class="w-full md:w-4/5 flex"></div>

                                    <div class="hidden md:block md:w-1/5">
                                        <div class="flex gap-2">
                                            {{-- <div>
                                                <button
                                                    id="dropdown-button-{{ $client->id }}"
                                                    class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                                    aria-expanded="false"
                                                    aria-haspopup="true"
                                                >
                                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path fill="currentColor" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                                                    <path fill="currentColor" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
                                                </svg>
                                                    
                                                </button>
                                            </div>
        
                                            <div class="delete-action-container" data-id="{{ $client->id }}">
                                                <button
                                                    class="bg-gray-100 border-1 border-gray-400 p-2 rounded-full flex justify-center items-center"
                                                    onclick="showDeleteConfirmationModal(event)" x-data="{ tooltip: 'Delete' }"
                                                >
                                                <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                                                </svg>

                                                    
                                                </button>
                                                <form action="{{ route('products.destroy', $client->id) }}" class="deleteFormProduct" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </div> --}}
        
                                        </div>
                                    </div>
                                
                              </div>
        
                            </div>

                        </div>                           
                          
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div id="deleteConfirmationModalClient"
        class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
        role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="cancelDeleteButton()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 animate-modalIn">
            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fa-solid fa-trash text-red-500 text-xl"></i>
                </div>
            </div>
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Excluir cliente?</h3>
                <p class="text-sm text-gray-500">Esta ação <strong>não pode ser desfeita</strong>.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="cancelDeleteButton()" type="button"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-medium text-sm hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button onclick="confirmDeleteButton()" id="confirmDeleteClientButton" type="button"
                    class="flex-1 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-colors shadow-sm">
                    <i class="fa-solid fa-trash mr-1.5 text-xs"></i> Excluir
                </button>
            </div>
        </div>
    </div>

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
        form.action = `/clients/destroy/${userId}`;
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
