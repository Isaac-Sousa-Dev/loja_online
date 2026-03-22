<section class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    <header class="mb-4 flex gap-2 items-center">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-store text-sm"></i>
        </div>
        <h2 class="font-semibold text-xl text-gray-800">Definições da Loja</h2>
    </header>

    @if($errors->has('logo') || $errors->has('banner'))
        <div class="bg-red-50 text-sm text-red-600 rounded-xl p-3 mb-4 border border-red-200 shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->get('logo') as $message)
                    <li>{{ $message }}</li>
                @endforeach
                @foreach ($errors->get('banner') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('store.update', $store->id) }}" class="mt-2 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Banner & Logo Section --}}
        <div>
            <div class="text-sm font-semibold text-gray-700 mb-2">Imagens da Loja</div>
            <div class="flex flex-col md:flex-row gap-6">
                {{-- Banner Upload --}}
                <div class="w-full md:w-2/3">
                    <label for="banner-store" class="block w-full h-32 md:h-40 border-2 border-dashed border-sky-500 rounded-xl cursor-pointer bg-blue-50 text-gray-600 relative overflow-hidden group transition">
                        <div id="div-banner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center {{ $bannerStore && $bannerStore != '/storage/' ? 'hidden' : '' }}">
                            <i class="fa-regular fa-image text-3xl mb-2 text-sky-400 group-hover:text-blue-600 transition"></i>
                            <span class="text-sm font-semibold text-center text-sky-600 group-hover:text-blue-700 transition">Fazer upload do Banner</span>
                        </div>
                        <img id="storeBannerPreview" src="{{ $bannerStore && $bannerStore != '/storage/' ? $bannerStore : '' }}" class="w-full h-full object-cover {{ $bannerStore && $bannerStore != '/storage/' ? '' : 'hidden' }}">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="text-white font-medium text-sm flex gap-2 items-center"><i class="fa-solid fa-pen"></i> Editar Banner</span>
                        </div>
                    </label>
                    <input type="file" class="hidden" id="banner-store" name="banner" accept="image/*" onchange="previewImageStore(event, 'storeBannerPreview', 'div-banner-placeholder');">
                </div>

                {{-- Logo Upload --}}
                <div class="w-full md:w-1/3 flex justify-center">
                    <label for="logo" class="w-32 h-32 md:w-40 md:h-40 border-2 border-dashed border-sky-500 rounded-full cursor-pointer bg-blue-50 text-gray-600 relative overflow-hidden flex-shrink-0 group transition">
                        <div id="div-logo-placeholder" class="absolute inset-0 flex flex-col items-center justify-center {{ $logoStore && $logoStore != '/storage/' ? 'hidden' : '' }}">
                            <i class="fa-solid fa-camera text-2xl mb-1 text-sky-400 group-hover:text-blue-600 transition"></i>
                            <span class="text-xs font-semibold text-center text-sky-600 group-hover:text-blue-700 transition">Logo</span>
                        </div>
                        <img id="logoStorePreview" src="{{ $logoStore && $logoStore != '/storage/' ? $logoStore : '' }}" class="w-full h-full object-cover {{ $logoStore && $logoStore != '/storage/' ? '' : 'hidden' }}">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="text-white font-medium text-xs flex gap-1 items-center"><i class="fa-solid fa-pen"></i> Editar</span>
                        </div>
                    </label>
                    <input type="file" id="logo" class="hidden" name="logo" accept="image/*" onchange="previewImageStore(event, 'logoStorePreview', 'div-logo-placeholder');">
                </div>
            </div>
        </div>

        {{-- Form Fields Grid --}}
        <div>
            <div class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-100 pb-2">Informações Básicas</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="store_name" :value="__('Nome da loja *')" />
                    <x-text-input id="store_name" placeholder="Minha Loja" name="store_name" type="text" class="mt-1 block w-full required bg-gray-50 bg-opacity-50" :value="old('store_name', $store->store_name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('E-mail principal *')" />
                    <x-text-input id="email" placeholder="minhaloja@mail.com" name="store_email" type="email" class="mt-1 block w-full required bg-gray-50 bg-opacity-50" :value="old('store_email', $store->store_email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('store_email')" />
                </div>
                <div>
                    <x-input-label for="store_phone" :value="__('Telefone (WhatsApp) *')" />
                    <x-text-input id="store_phone" name="store_phone" type="text" class="mt-1 block w-full phone-mask required bg-gray-50 bg-opacity-50" :value="old('store_phone', $store->store_phone)" placeholder="(00) 00000-0000" required />
                    <x-input-error class="mt-2" :messages="$errors->get('store_phone')" />
                </div>  
                <div>
                    <x-input-label for="store_cpf_cnpj" :value="__('CPF/CNPJ *')" />
                    <x-text-input id="store_cpf_cnpj" name="store_cpf_cnpj" type="text" class="mt-1 block w-full cpf-cnpj-mask required bg-gray-50 bg-opacity-50" :value="old('store_cpf_cnpj', $store->store_cpf_cnpj)" placeholder="XX.XXX.XXX/0001-XX" required />
                    <x-input-error class="mt-2" :messages="$errors->get('store_cpf_cnpj')" />
                </div>
            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-100 pb-2">Endereço Geográfico</div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-3">
                    <x-input-label for="zip_code" :value="__('CEP')" />
                    <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full cep-mask bg-gray-50 bg-opacity-50" :value="old('zip_code', optional($store->addressStore)->zip_code)" placeholder="00000-000" />
                    <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
                </div>
                <div class="md:col-span-6">
                    <x-input-label for="street" :value="__('Avenida/Rua')" />
                    <x-text-input id="street" name="street" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('street', optional($store->addressStore)->street)" placeholder="Ex: Av. Brasil" />
                    <x-input-error class="mt-2" :messages="$errors->get('street')" />
                </div>
                <div class="md:col-span-3">
                    <x-input-label for="number" :value="__('Número')" />
                    <x-text-input id="number" name="number" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('number', optional($store->addressStore)->number)" placeholder="1234" />
                    <x-input-error class="mt-2" :messages="$errors->get('number')" />
                </div>
                <div class="md:col-span-6">
                    <x-input-label for="neighborhood" :value="__('Bairro')" />
                    <x-text-input id="neighborhood" name="neighborhood" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('neighborhood', optional($store->addressStore)->neighborhood)" placeholder="Centro" />
                    <x-input-error class="mt-2" :messages="$errors->get('neighborhood')" />
                </div>
                <div class="md:col-span-4">
                    <x-input-label for="city" :value="__('Cidade')" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('city', optional($store->addressStore)->city)" placeholder="São Paulo" />
                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="state" :value="__('Estado')" />
                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('state', optional($store->addressStore)->state)" placeholder="SP" />
                    <x-input-error class="mt-2" :messages="$errors->get('state')" />
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-end items-center gap-4 mt-6 border-t border-gray-100 pt-5">
            <x-primary-button id="btnSaveInfoStore" class="w-full justify-center md:w-auto px-6 py-2.5 shadow-md">
                {{ __('Salvar Modificações') }}
            </x-primary-button>
        </div>
    </form>
</section>

<script>
    function previewImageStore(event, previewId, placeholderId) {
        const input = event.target;
        const file = input.files[0];
        const maxSizeMB = 2;

        if(file) {
            const fileSizeMB = file.size / (1024 * 1024);
            if(fileSizeMB > maxSizeMB) {
                if(typeof toastr !== 'undefined') toastr.error(`A imagem excede o tamanho máximo permitido de ${maxSizeMB}MB`);
                input.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
