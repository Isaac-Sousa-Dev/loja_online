<section class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    <header class="mb-4 flex gap-2 items-center">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-lock text-sm"></i>
        </div>
        <h2 class="font-semibold text-xl text-gray-800">Alterar Senha</h2>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <x-input-label for="update_password_current_password" :value="__('Senha Atual')" class="font-semibold text-gray-700" />
                <x-text-input id="update_password_current_password" placeholder="***************" name="current_password" type="password" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl shadow-none focus:ring-blue-500 focus:border-blue-500" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
    
            <div class="hidden md:block"></div> {{-- Spacer if we want current password alone on top. Wait, let's keep it tight --}}

            <div> 
                <x-input-label for="update_password_password" :value="__('Nova Senha')" class="font-semibold text-gray-700" />
                <x-text-input id="update_password_password" placeholder="***************" name="password" type="password" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl shadow-none focus:ring-blue-500 focus:border-blue-500" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Nova Senha')" class="font-semibold text-gray-700" />
                <x-text-input id="update_password_password_confirmation" placeholder="***************" name="password_confirmation" type="password" class="mt-1 block w-full bg-gray-50 border-gray-200 rounded-xl shadow-none focus:ring-blue-500 focus:border-blue-500" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex flex-col-reverse md:flex-row items-center gap-4 mt-6 border-t border-gray-100 pt-5 justify-end">
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium flex items-center justify-center gap-1 w-full md:w-auto"
                ><i class="fa-solid fa-circle-check"></i> {{ __('Senha salva com sucesso!') }}</p>
            @endif

            <x-primary-button class="w-full md:w-auto px-6 py-2.5 shadow-md justify-center">
                {{ __('Atualizar Senha') }}
            </x-primary-button>
        </div>

    </form>
</section>
