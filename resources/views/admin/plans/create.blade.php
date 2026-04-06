<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">

        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                {{ __('Novo Plano') }}
            </h2>

            <div class="p-1 mt-3">

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded-xl shadow-md pt-3 pb-2 mb-3 flex flex-col bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Dados do plano
                        </div>
                        <div>
                            <div class="w-full mb-3">
                                <x-input-label for="name" :value="__('Nome do plano *')" />
                                <x-text-input id="name" class="required" placeholder="Nome do Plano" name="name" type="text" autofocus autocomplete="name" />
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-full mb-3">
                                <x-input-label for="qtd_products" :value="__('Quantidade de produtos *')" />
                                <x-text-input id="qtd_products" class="required" placeholder="10" name="qtd_products" type="text" />
                            </div>

                            <div class="w-full mb-3">
                                <x-input-label for="description" :value="__('Descrição')" />
                                <x-text-input id="description" class="required" placeholder="R$ 100,00 /mês" name="description" type="text" autofocus autocomplete="description" />
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-full mb-3">
                                <x-input-label for="price" :value="__('Preço *')" />
                                <x-text-input id="price" class="required price-mask" placeholder="R$ 100,00" name="price" type="text" autofocus autocomplete="price" />
                            </div>

                            <div class="w-full mb-3">
                                <x-input-label for="status" :value="__('Status')" />
                                <select name="status" class="w-full border-gray-300 rounded-md h-9 py-1 px-3" id="status">
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl shadow-md pt-3 mb-4 flex flex-col pb-3 bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Módulos disponíveis
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-1">

                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="dashboard" checked name="dashboard" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="dashboard" class="font-medium text-gray-700">Dashboard</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="analytics" name="analytics" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="analytics" class="font-medium text-gray-700">Analytics</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="motivados" name="motivados" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="motivados" class="font-medium text-gray-700">Motivados</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="plans" name="plans" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="plans" class="font-medium text-gray-700">Planos</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="requests" checked name="requests" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="requests" class="font-medium text-gray-700">Solicitações</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="agenteia" name="agenteia" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="agenteia" class="font-medium text-gray-700">Agente IA</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="sales" name="sales" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="sales" class="font-medium text-gray-700">Vendas</label>
                            </div>
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="team" name="team" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="team" class="font-medium text-gray-700">Equipe</label>
                            </div>
                            {{-- <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="categories" checked name="categories" type="checkbox" class="rounded-sm border-gray-300">
                                <label for="categories" class="font-medium text-gray-700">Categorias</label>
                            </div> --}}
                            {{-- <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="subcategories" checked name="subcategories" type="checkbox" class="rounded-sm border-gray-300">
                                <label for="subcategories" class="font-medium text-gray-700">Marcas</label>
                            </div> --}}
                            <div class="mt-2 flex py-1 px-2 bg-[#EDE9FE]/60 rounded-lg border border-[#6A2BBA]/10 items-center gap-1">
                                <input id="products" checked name="products" type="checkbox" class="module rounded-sm border-gray-300">
                                <label for="products" class="font-medium text-gray-700">Produtos</label>
                            </div>
                        </div>
                    </div>

                    <div class="w-full justify-end flex mb-4 gap-2">
                        <div class="flex w-full md:w-2/6 justify-between gap-3 py-2 px-3 bg-white rounded-xl">
                            <x-secondary-button id="">
                                <a href="{{ route('plans.index') }}">
                                    {{ __('Cancelar') }}
                                </a>          
                            </x-secondary-button>
                            
                            <x-primary-button id="btnSaveDataPlan">{{ __('Salvar') }}</x-primary-button>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
    @endsection

</x-app-layout>

<script>

    $('#btnSaveDataPlan').click(function(e) {
        e.preventDefault();    

        let modules = [];
        let modulesAvailable = $('.module').toArray();
        console.log(modulesAvailable);
        modulesAvailable.forEach(moduleAvailable => {
            if(moduleAvailable.checked) {
                modules.push(moduleAvailable.name);
            }
        });

        // console.log(modules);

        showLoader();
        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('qtd_products', $('#qtd_products').val());
        formData.append('description', $('#description').val());
        formData.append('price', $('#price').val());
        formData.append('status', $('#status').val());
        formData.append('modules', modules);

        // formData.append('dashboard', $('#dashboard').is(':checked') ? 1 : 0);
        // formData.append('analytics', $('#analytics').is(':checked') ? 1 : 0);
        // formData.append('motivados', $('#motivados').is(':checked') ? 1 : 0);
        // formData.append('plans', $('#plans').is(':checked') ? 1 : 0);
        // formData.append('requests', $('#requests').is(':checked') ? 1 : 0);
        // formData.append('agenteia', $('#agenteia').is(':checked') ? 1 : 0);
        // formData.append('sales', $('#sales').is(':checked') ? 1 : 0);
        // formData.append('team', $('#team').is(':checked') ? 1 : 0);
        // formData.append('categories', $('#categories').is(':checked') ? 1 : 0);
        // formData.append('subcategories', $('#subcategories').is(':checked') ? 1 : 0);
        // formData.append('products', $('#products').is(':checked') ? 1 : 0);

        $.ajax({
            url: '{{ route('plans.store') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.location.href = '{{ route('plans.index') }}';
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
