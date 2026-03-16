<x-app-layout>


    @section('content')
    <div class="p-2 flex md:justify-center">

        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                {{ __('Novo Motivado') }}
            </h2>

            <div class="p-1 mt-3">

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="rounded-xl shadow-md pt-3 pb-2 mb-3 flex flex-col bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Dados do usuário
                        </div>
                        <div>
                            <div class="w-full mb-3">
                                <x-input-label for="name" :value="__('Nome *')" />
                                <x-text-input id="name" class="required" placeholder="Nome do Motivado" name="name" type="text" autofocus autocomplete="name" />
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-full mb-3">
                                <x-input-label for="email" :value="__('Email *')" />
                                <x-text-input id="email" class="required" placeholder="silva@example.com" name="email" type="text" autofocus autocomplete="email" />
                            </div>

                            <div class="w-full mb-3">
                                <x-input-label for="phone" :value="__('WhatsApp *')" />
                                <x-text-input id="phone" class="required phone-mask" placeholder="(00) 00000-0000" name="phone" type="text" autofocus autocomplete="phone" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl shadow-md pt-3 mb-4 flex flex-col pb-3 bg-white px-3">
                        <div class="text-lg font-semibold text-gray-800 mb-3">
                            Dados da loja e assinatura
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="w-full flex gap-2">
                                <div class="w-1/2">
                                    <x-input-label for="store_name" :value="__('Nome da Loja *')" />
                                    <x-text-input id="store_name" placeholder="Nome da Loja" name="store_name" type="text" class="required" />
                                </div>

                                <div class="w-1/2">
                                    <x-input-label for="qtd_vehicles_in_stock" :value="__('Quantidade de veículos em estoque')" />
                                    <select id="qtd_vehicles_in_stock" class="w-full rounded-xl py-2 px-3 border-gray-300" name="" id="">
                                        <option value="10-vehicles">Até 10 veículos</option>
                                        <option value="25-vehicles">Até 25 veículos</option>
                                        <option value="60-vehicles">Até 60 veículos</option>
                                        <option value="plus-vehicles">Mais de 60 veículos</option>
                                    </select> 
                                </div>

                            </div>

                            <div class="w-full flex gap-2">
                                <div class="w-1/2">
                                    <x-input-label for="plan_id" :value="__('Assinatura/Plano')" />
                                    <select id="plan_id" class="w-full rounded-xl py-2 px-3 border-gray-300">
                                        <option value="1">Plano Test - 1x R$ 49,99</option>
                                        <option value="2">Start Plus - R$ 89,99/mês</option>
                                        <option value="3">Advanced - R$ 119,99/mês</option>
                                    </select>
                                </div>

                                <div class="w-1/2">
                                    <x-input-label for="payment_method" :value="__('Forma de pagamento')" />
                                    <select id="payment_method" class="w-full rounded-xl py-2 px-3 border-gray-300">
                                        <option selected value="pix">Pix</option>
                                        <option value="credit">Crédito</option>
                                    </select> 
                                </div>

                            </div>

                            <div class="mt-2">
                                <input id="is_testing" name="is_testing" type="checkbox" class="rounded-sm border-gray-300">
                                <label for="is_testing">Está testando</label>
                            </div>
                        </div>
                    </div>

                    <div class="w-full justify-end flex mb-4 gap-2">
                        <div class="flex w-full md:w-2/6 justify-between gap-3 py-2 px-3 bg-white rounded-xl">
                            <x-secondary-button id="">
                                <a href="{{ route('partners.index') }}">
                                    {{ __('Cancelar') }}
                                </a>          
                            </x-secondary-button>
                            
                            <x-primary-button id="btnSaveDataPartner">{{ __('Salvar') }}</x-primary-button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    @endsection

</x-app-layout>

<script>

    $('#btnSaveDataPartner').click(function(e) {
        e.preventDefault();

        showLoader();
        // Crie um objeto FormData
        const formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('email', $('#email').val());
        formData.append('phone', $('#phone').val());
        formData.append('store_name', $('#store_name').val());
        formData.append('plan_id', $('#plan_id').val());
        formData.append('payment_method', $('#payment_method').val());
        formData.append('is_testing', $('#is_testing').val());

        $.ajax({
            url: '{{ route('partners.store') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.location.href = '{{ route('partners.index') }}';
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
