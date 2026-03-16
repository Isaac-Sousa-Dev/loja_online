<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informações de Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Abaixo você pode atualizar suas informações de perfil.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-2 space-y-2" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex space-x-2">
            <div class="flex">

                <div class="items-center">
                    @if ($user->image_profile != '')
                        <div class="bg-slate-200 p-2 rounded-md h-40 w-40 flex justify-center items-center">
                            <img id="profileImagePreview" src="{{ $imageProfile }}" class="rounded-full h-32 w-32">
                        </div>
                    @else
                        <div class="bg-slate-200 p-2 rounded-md h-40 w-40 flex justify-center items-center">
                            <img id="profileImagePreview" src="/img/logo-teste.webp" class="rounded-full h-32 w-32">
                        </div>
                    @endif
                    <div class="form-input">
                        <label for="file-ip-1">Upload Logo</label>
                        <input type="file" id="file-ip-1" name="image-profile" accept="image/*" onchange="previewImage(event, 'profileImagePreview');">
                    </div>

                </div>

            </div>

            <div class="w-full space-y-4">
                <div class="w-full">
                    <x-input-label for="name" :value="__('Nome')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('E-mail')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="whatsapp" :value="__('WhatsApp')" />
                    <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" :value="old('phone', $partner->phone)" placeholder="(00) 00000-0000" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                </div>

            </div>
        </div>
        <div class="mt-5">
            <x-input-label for="description" :value="__('Mensagem Inicial')" />
            <textarea name="description" placeholder="Olá gostaria de comprar..."
                class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-gray-300 md:mt-1 rounded md:py-3 md:px-4 mb-3"
                id="description" cols="30" rows="3">{{ old('initial_message', $partner->initial_message) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>







        <div class="flex items-center gap-4">
            <x-primary-button id="btnSaveInfoUser">{{ __('Salvar') }}</x-primary-button>

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
        function previewImage(event, previewId) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById(previewId);
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
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
