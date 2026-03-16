@props([
    'id_modal' => 'confirmationModal',
    'title' => 'Confirmar Ação',
    'message' => 'tem certeza que deseja prosseguir com esta ação?',
    'confirm_text' => 'Confirmar',
    'cancel_text' => 'Cancelar',
    'delete_url' => null,
    'item_id' => null
])

<div id="{{ $id_modal }}" class="fixed z-50 hidden inset-0 overflow-y-auto" aria-labelledby="modal-title"
     role="dialog" aria-modal="true"
     data-item-id="{{ $item_id }}"
     data-delete-url="{{ $delete_url }}">
     
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pb-4 p-6 sm:pb-4">
                <div class="flex items-center gap-3">
                    
                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                            {{ $title }}
                        </h3>
                    </div>
                    
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex gap-4">
                <button type="button"
                        onclick="hideModal('{{ $id_modal }}')"
                        class="w-1/2 justify-center h-10 rounded-md border border-transparent shadow-sm px-2 bg-gray-600 font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    {{ $cancel_text }}
                </button>
                 <button type="button"
                        onclick="confirmDelete('{{ $id_modal }}')"
                        class="w-1/2 justify-center h-10 rounded-md border border-transparent shadow-sm px-2 bg-red-600 font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    {{ $confirm_text }}
                </button>
            </div>
        </div>
    </div>
</div>