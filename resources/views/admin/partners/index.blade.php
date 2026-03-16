<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div class="">

                <div class="flex justify-between items-center mt-4">
                    <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                        {{ __('Motivados') }}
                    </h2>

                    <div class="flex gap-2 items-center">
                        <div>
                            <input type="text" class="rounded-xl h-9 border-gray-300" placeholder="Pesquisar...">
                        </div>
                        <button class="btn-sm" href="javascript:void(0)">
                            <a href="{{ route('partners.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                {{ __('Novo Motivado') }}
                            </a>
                        </button>
                    </div>

                </div>


                <!-- component -->
                <div class="overflow-auto md:overflow-hidden rounded-xl border border-gray-200 shadow-md mt-3">
                    <table class="table-responsive w-full border-collapse bg-white text-left text-sm text-gray-500">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-2 font-medium text-gray-900">Nome</th>
                                <th scope="col" class="px-6 py-2 font-medium text-gray-900">Status</th>
                                <th scope="col" class="px-6 py-2 font-medium text-gray-900">Assinatura</th>
                                <th scope="col" class="px-6 py-2 font-medium text-gray-900">Telefone</th>
                                <th scope="col" class="px-6 py-2 font-medium text-gray-900">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 border-t border-gray-100">

                            @if ($users->isEmpty())
                                <tr>
                                    <td class="px-6 py-4 text-gray-900" colspan="5">Nenhum motivado encontrado.</td>
                                </tr>
                            @endif
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <th class="flex gap-10 px-6 py-4 font-normal text-gray-900">
                                        <div class="relative h-10 w-10">
                                            <img width="50"
                                                class="h-full w-12 rounded-full object-cover object-center"
                                                src="{{ $user->image_profile ? asset('storage/' . $user->image_profile) : asset('img/logos/logo.png') }}"
                                                alt="" />
                                            {{-- <span
                                                class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span> --}}
                                        </div>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-700">{{ $user->name }}</div>
                                            <div class="text-gray-400 font-medium text-xs">{{ $user->email }}</div>
                                            
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-lg px-2 text-xs font-semibold {{ $user->partner->subscription->status == 'active' ? 'bg-green-300 text-green-800' : 'bg-red-300 text-red-800' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $user->partner->subscription->status == 'active' ? 'bg-green-800' : 'bg-red-800' }}"></span>
                                            {{ $user->partner->subscription->status == 'active' ? 'Ativo' : 'Inativa' }}
                                        </span>
                                        <div class="text-xs mt-1 font-medium">
                                            Inicio: {{$user->partner->store->created_at->format('d/m/Y')}}
                                        </div>
                                    </td>
                                    <td class="px-0 py-4">
                            
                                        <div class="text-md space-y-0.5 border-gray-400 border rounded-xl px-2 py-1 flex gap-1 items-center justify-between">
                                            <div class="bg-gray-500 rounded-md text-white text-center px-2 font-medium w-2/3">
                                                {{$user->partner->subscription->plan->name}}
                                            </div>
                                            <div class="bg-blue-700 rounded-md text-white text-center px-2 font-medium w-1/3">
                                                <span class="mr-1">R$</span><span class="price-mask">{{$user->partner->subscription->plan->price}}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <span class="phone-mask">{{ $user->phone }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-4 items-center">

                                            {{-- <div class="delete-action-container" data-id="{{ $user->id }}">
                                                <button onclick="showDeleteConfirmationModal(event)" x-data="{ tooltip: 'Delete' }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-6 w-6" x-tooltip="tooltip">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('partners.destroy', $user->id) }}" id="deleteForm" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </div> --}}

                                            <a x-data="{ tooltip: 'Edite' }" href="{{ route('partners.edit', $user->id) }}">
                                                <svg class="w-7 h-7 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                                    <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>                                                  
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal de confirmação -->
    <div id="deleteConfirmationModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
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
                                Excluir sócio
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tem certeza de que deseja excluir este sócio? Esta ação não pode ser desfeita.
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
        document.querySelector('#deleteConfirmationModal').dataset.userId = userId;
        document.getElementById('deleteConfirmationModal').classList.remove('hidden');
    }

    // Função para ocultar o modal de confirmação
    function hideDeleteConfirmationModal() {
        document.getElementById('deleteConfirmationModal').classList.add('hidden');
    }

    // Evento de clique no botão de confirmar exclusão
    function confirmDeleteButton() {
        const userId = document.querySelector('#deleteConfirmationModal').dataset.userId;
        const form = document.querySelector('#deleteForm');
        form.action = `partners/destroy/${userId}`;
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
