<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">

        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                {{ __('Editar Membro') }}
            </h2>

            <div class="p-1 mt-3">

                <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded-xl shadow-md pt-3 pb-2 mb-3 flex flex-col bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Dados
                        </div>

                        <div class="flex flex-col md:flex-row md:gap-4">
                            <div class="w-full mb-3">
                                <x-input-label for="name" :value="__('Nome do membro *')" />
                                <x-text-input id="name" class="required" placeholder="Nome do membro" name="name" type="text" value="{{$member->user->name}}" />
                            </div>

                            <div class="w-full mb-3">
                                <x-input-label for="email" :value="__('E-mail *')" />
                                <x-text-input id="email" class="required" placeholder="E-mail" name="email" type="text" value="{{$member->user->email}}" />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:gap-4">
                            <div class="w-full mb-3">
                                <x-input-label for="password" :value="__('Senha *')" />
                                <x-text-input id="password" class="required" placeholder="Senha" type="password" />
                            </div>

                            <div class="w-full mb-3">
                                <x-input-label for="phone" :value="__('Telefone')" />
                                <x-text-input id="phone" class="required phone-mask" placeholder="Telefone" name="phone" type="text" value="{{$member->user->phone}}"/>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:gap-4">
                    
                            <div class="w-full">
                                <x-input-label for="role" :value="__('Setor')" />
                                <select id="role" name="role" type="text" class="border-gray-300 pt-1.5 mb-3 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                    <option value="seller">Vendas</option>
                                    {{-- <option value="seller">Markting</option> --}}
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                            <div class="w-full">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" type="text" class="border-gray-300 pt-1.5 mb-3 focus:border-indigo-300 focus:ring-indigo-300 rounded-md h-9 shadow-xs w-full">
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>
                        </div>
                    </div>

                    <div class="w-full justify-end flex mb-4 gap-2">
                        <div class="flex w-full md:w-2/6 justify-between gap-3 py-2 px-3 bg-white rounded-xl">
                            <x-secondary-button id="">
                                <a href="{{ route('members.index') }}">
                                    {{ __('Cancelar') }}
                                </a>          
                            </x-secondary-button>
                            
                            <x-primary-button id="btnUpdateDataMember">{{ __('Salvar') }}</x-primary-button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    @endsection

</x-app-layout>

<script>
    $('#btnUpdateDataMember').click(function(e) {
        e.preventDefault();

        showLoader();
        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('email', $('#email').val());
        formData.append('password', $('#password').val());
        formData.append('phone', $('#phone').val());
        formData.append('role', $('#role').val());
        formData.append('status', $('#status').val());

        $.ajax({
            url: '{{ route('members.update', $member->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.location.href = '{{ route('members.index') }}';
            },
            error: function(xhr, status, error) {

                console.log(xhr.status);
                if (xhr.status === 422) { 
                    let errors = xhr.responseJSON.errors;
                    console.log(errors);
                }

                toastr.error(xhr.responseJSON.message);               
            },
            finally: function() {
                hideLoader();
            }
        });
    })

</script>
