<x-app-layout>
    @section('content')

    <!-- Modal de confirmação -->
    <x-confirmation-modal
        id_modal="deleteMemberModal"
        title="Excluir Membro..."
        message="Tem certeza de que deseja excluir este membro? Esta ação não pode ser desfeita."
        confirm_text="Excluir"
        cancel_text="Cancelar"
        delete_url="{{ route('members.destroy', ':id') }}"
    />

    <div class="p-2 flex justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <div class="">

                <div class="flex items-center justify-between mt-4">

                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                            {{ __('Equipe') }}
                        </h2>
                    </div>


                    <div class="flex flex-wrap md:items-center gap-2">
                        <div class="w-full flex justify-between items-center">
                            <button class="flex" href="javascript:void(0)">
                                <a href="{{ route('members.create') }}"
                                    class="inline-flex md:items-center gap-1 md:gap-2 px-4 py-[11px] md:px-2 md:py-[10px] border border-transparent text-sm leading-5 font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                      </svg>                                      
                                    {{ __('Novo Membro') }}
                                </a>
                            </button>
                        </div>
                    </div>

                </div>

                <x-responsive-table 
                    :columns="[
                        'Nome' => 'user.name',
                        'E-mail' => 'user.email',
                        'Telefone' => 'user.phone'
                    ]"
                    :data="$members"
                    empty-message="Não há membros cadastrados."
                    :actions="fn($member) => view('partials.member-actions', compact('member'))"
                />

            </div>
        </div>
    </div>
    @endsection
</x-app-layout>
