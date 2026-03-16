<section class="w-full bg-white p-3 rounded-xl">
    <header class="mb-3 flex justify-between items-center">
        <h2 class="text-md font-semibold text-gray-900">
            <span class="">Config.</span>
            {{ __(' Loja') }}
        </h2>

        <div class="flex gap-2 md:gap-3">
            <label for="banner-store" class="flex hover:bg-gray-100 transition ease-in-out duration-300 cursor-pointer items-center gap-1 font-semibold bg-gray-200 py-1 px-2 rounded-lg">
                <svg class="w-5 h-5 text-gray-800 dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                  </svg> 
                Banner
            </label>
            <label for="logo" class="flex hover:bg-gray-100 transition ease-in-out duration-300 cursor-pointer items-center gap-1 font-semibold bg-gray-200 py-1 px-2 rounded-lg">
                <svg class="w-5 h-5 text-gray-800 dark:text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                  </svg>                   
                Logo
            </label>
        </div>
    </header>

    @if($errors->has('logo') || $errors->has('banner'))
        <div class="bg-red-200 text-sm text-red-800 rounded p-2 space-y-1">
            @foreach ($errors->get('logo') as $message)
                <div>{{ $message }}</div>
            @endforeach
            @foreach ($errors->get('banner') as $message)
                <div>{{ $message }}</div>
            @endforeach
        </div>
    @endif

    <form method="post" action="{{ route('store.update', $store->id) }}" class="mt-2 space-y-2" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex p-1 gap-10">

            <div class="w-full relative flex justify-center items-center h-36 rounded-xl">
                {{-- LOGO STORE --}}
                <label for="logo" class="flex justify-center items-center cursor-pointer absolute">
                    <div id="div-logo" class="bg-gray-300 flex items-center justify-center border-3 border-gray-200 relative w-28 rounded-full h-28">

                        @if($logoStore != null && $logoStore != '/storage/')
                            <img id="logoStorePreview" accept="image/*" src="{{ $logoStore }}" class="rounded-full w-full h-full cursor-pointer bg-gray-300 object-cover"> 
                            <div id="div-text-edit-logo" style="display: none" class="flex items-center text-xs gap-1 font-bold justify-center absolute pointer-events-none z-10">
                                <div class="flex gap-1">
                                    Editar Logo
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4 .4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"/></svg>
                                </div>
                            </div>  
                        @else
                            <img id="logoStorePreview" accept="image/*" src="{{ $logoStore }}" class="rounded-full hidden w-full h-full cursor-pointer bg-gray-300 "> 
                            <div class="flex items-center text-xs gap-1 font-bold justify-center absolute pointer-events-none z-10">
                                Logo
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-32 252c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92H92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"/></svg> 
                            </div>
                        @endif
                    </div>
                </label>
                <input type="file" id="logo" class="hidden" name="logo" onchange="previewImageStore(event, 'logoStorePreview');">

                
                {{-- BANNER STORE --}}
                <label for="banner-store" class="w-full h-full">

                    <div id="div-banner" style="border-radius: 20px" class="flex justify-center items-center h-full bg-gray-300 border-3 border-gray-200 cursor-pointer">     
                        @if($bannerStore != null && $bannerStore != '/storage/')
                            <img id="storeBannerPreview" style="max-height: 140px; width: 100%; border-radius: 20px;" class="object-cover" accept="image/*" src="{{ $bannerStore }}">
                            <div id="div-text-edit-banner" style="display: none" class="flex items-center gap-1 font-bold justify-center absolute pointer-events-none text-black z-20">
                                <div class="flex gap-1 text-xs">
                                    Editar Banner
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4 .4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"/></svg>
                                </div>
                            </div>  
                        @else
                            <img id="storeBannerPreview" style="max-height: 140px; width: 100%; border-radius: 7px;" class="hidden object-cover object-center" accept="image/*" src="{{ $bannerStore }}">
                        @endif

                    </div>
                </label>
                <input type="file" class="hidden" id="banner-store" name="banner" onchange="previewImageStore(event, 'storeBannerPreview');">
            </div>

        </div>

        <div class="flex space-x-2">

            <div class="w-full space-y-2">
                <div class="w-full flex flex-col md:flex-row gap-1 px-1">
                    <div class="md:w-1/2">
                        <x-input-label for="store_name" :value="__('Nome da loja')" />
                        <x-text-input id="store_name" placeholder="Minha Loja" name="store_name" type="text" class="mt-1 block w-full" :value="old('store_name', $store->store_name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
                    </div>

                    <div class="md:w-1/2">
                        <x-input-label for="email" :value="__('E-mail principal')" />
                        <x-text-input id="email" placeholder="minhaloja@mail.com" name="store_email" type="email" class="mt-1 block w-full" :value="old('store_email                                                                                                                                                                                                                                                                                                                                   ', $store->store_email)" required autocomplete="store_email" />
                        <x-input-error class="mt-2" :messages="$errors->get('store_email')" />
                    </div>

                </div>
                <div class="px-1 flex flex-col md:flex-row gap-1">
                    <div class="md:w-2/5">
                        <x-input-label for="store_phone" :value="__('Telefone principal')" />
                        <x-text-input id="store_phone" name="store_phone" type="text" class="mt-1 block w-full phone-mask" :value="old('store_phone', $store->store_phone)" placeholder="(00) 00000-0000" required autocomplete="store_phone" />
                        <x-input-error class="mt-2" :messages="$errors->get('store_phone')" />
                    </div>  

                    <div class="md:w-2/5">
                        <x-input-label for="store_cpf_cnpj" :value="__('CPF/CNPJ')" />
                        <x-text-input id="store_cpf_cnpj" name="store_cpf_cnpj" type="text" class="mt-1 block w-full cpf-cnpj-mask" :value="old('store_cpf_cnpj', $store->store_cpf_cnpj)" placeholder="XX.XXX.XXX/0001-XX" required autocomplete="store_cpf_cnpj" />
                        <x-input-error class="mt-2" :messages="$errors->get('store_cpf_cnpj')" />
                    </div>

                    <div class="md:w-1/5">
                        <x-input-label for="zip_code" :value="__('CEP')" />
                        @if($store->addressStore)
                            <input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full cep-mask border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full" @if($store->addressStore) value="{{$store->addressStore->zip_code}}" @endif placeholder="00000-000" required autocomplete="zip_code" />
                        @else
                            <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full cep-mask" placeholder="00000-000" required autocomplete="zip_code" />
                        @endif  
                                
                        <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
                    </div>
                </div>

                <div class="flex flex-col md:flex-row px-1 gap-1">

                    <div class="md:w-3/5">
                        <x-input-label for="street" :value="__('Avenida/Rua')" />
                        @if($store->addressStore)
                            <input id="street" name="street" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full" @if($store->addressStore) value="{{$store->addressStore->street}}" @endif placeholder="Av. Santos Dum..." required autocomplete="street" />
                        @else
                            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" placeholder="Av. Santos Dum..." required autocomplete="street" />
                        @endif
                        <x-input-error class="mt-2" :messages="$errors->get('street')" />
                    </div>

                    <div class="flex gap-1">
                        <div class="w-3/4">
                            <x-input-label for="city" :value="__('Cidade')" />
                            @if($store->addressStore)
                                <input id="city" name="city" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full" @if($store->addressStore) value="{{$store->addressStore->city}}" @endif placeholder="Londres" required autocomplete="store_city" />
                            @else
                                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" placeholder="Londres" required autocomplete="store_city" />
                            @endif 
                            <x-input-error class="mt-2" :messages="$errors->get('city')" />
                        </div>  
    
                        <div class="w-1/4">
                            <x-input-label for="number" :value="__('Número')" />
                            @if($store->addressStore)
                                <input id="number" name="number" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full" @if($store->addressStore) value="{{$store->addressStore->number}}" @endif placeholder="1234" required autocomplete="number" />
                            @else
                                <x-text-input id="number" name="number" type="text" class="mt-1 block w-full" placeholder="1234" required autocomplete="number" />
                            @endif
                            <x-input-error class="mt-2" :messages="$errors->get('number')" />
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="flex items-center gap-4 mt-3">
            <button class="py-2 px-4 text-white font-semibold rounded-xl bg-primary" id="btnSaveInfoStore">{{ __('Salvar') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function previewImageStore(event, previewId) {

            const input = event.target;
            const file = input.files[0];
            const maxSizeMB = 2;

            if(file) {
                const fileSizeMB = file.size / (1024 * 1024);
                console.log(fileSizeMB, 'Tamanho da minha img')
                if(fileSizeMB > maxSizeMB) {
                    toastr.error(`A imagem excede o tamanho máximo permitido de ${maxSizeMB}MB`);
                    input.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);

            }
        }
    </script>
</section>

<style>

    .form-input {
    width:160px;
    padding:5px 0px;
    background:#fff;
    border: none
    }

    .form-input img {
        width:100%;
        display:none;
        margin-bottom:30px;
    }

    .form-input input {
        display:none;
    }

    .form-input label {
        display:block;
        width:100%;
        height:45px;
        margin-left: 0px;
        line-height:50px;
        text-align:center;
        background:#1172c2;
        color:#fff;
        font-size:15px;
        font-family:"Open Sans",sans-serif;
        text-transform:Uppercase;
        font-weight:600;
        border-radius:5px;
        cursor:pointer;
    }
</style>
