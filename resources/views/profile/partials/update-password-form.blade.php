<section class="bg-white p-3 rounded-xl">
    <header>
        <h2 class="text-md font-semibold text-gray-900">
            {{ __('Atualizar Senha') }}
        </h2>

        {{-- <p class="mt-1 text-sm text-gray-600">
            {{ __('Abaixo você pode atualizar sua senha.') }}
        </p> --}}
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-2 space-y-2">
        @csrf
        @method('put')

        <div class="flex space-x-1">
            <div class="w-1/2">
                <x-input-label for="update_password_current_password" :value="__('Senha Atual')" />
                <x-text-input id="update_password_current_password" placeholder="***************" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
    
            <div class="w-1/2"> 
                <x-input-label for="update_password_password" :value="__('Nova Senha')" />
                <x-text-input id="update_password_password" placeholder="***************" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
        </div>

        <div class="flex space-x-1 justify-between">
            <div class="w-1/2">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Senha')" />
                <x-text-input id="update_password_password_confirmation" placeholder="***************" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
    
            <div class="flex items-center mt-4">
                <button class="py-2 px-4 text-white font-semibold rounded-xl bg-primary">{{ __('Salvar') }}</button>
    
                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </div>

    </form>
</section>
