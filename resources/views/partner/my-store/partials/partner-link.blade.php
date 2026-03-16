<section>

    <form method="post" action="{{ route('password.update') }}" class="mt-2">
        @csrf
        @method('put')

        @if(session('isNewLink'))
            <div class="bg-green-500 rounded-xl p-2 mb-2 flex items-center justify-between">
                <div>
                    <div class="text-lg font-semibold text-white flex gap-1 items-center">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                        </svg>  
                        Novo link gerado
                    </div>
                    <div class="text-white text-xs">
                        Compartilhe o novo link abaixo com seus clientes
                    </div>
                </div>
                <div>
                    <svg id="closeNewLinkMessage" class="w-6 h-6 text-white hover:bg-green-600 cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>                      
                </div>
            </div>
            <script>
                let closeNewLinkMessage = $('#closeNewLinkMessage');
                closeNewLinkMessage.on('click', function() {
                    $(this).parent().parent().hide();
                });
            </script>
        @endif
        <div class="flex space-x-1 w-full">

            @if($partner->partner_link != null) 
                <div class="w-full mb-0">
                    <x-input-label for="update_password_current_password" class="font-bold" :value="__('Link da loja')" />
                    <x-text-input id="partner_link" :value="old('partner_link', request()->getSchemeAndHttpHost().'/orders/'.$user->partner->partner_link)" disabled name="current_password" type="text"
                        class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>
                <div class="flex items-center w-[12%]">
                    <button id="copyButton" type="button" class="bg-gray-800 text-white px-3 h-10 mt-3 py-2 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-copy">
                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                        </svg>
                    </button>
                </div>

            @else
                <div class="w-full mb-0">
                    <x-input-label class="font-bold" :value="__('Link da loja')" />
                    <x-text-input id="partner_link" placeholder="Preencha todas as configurações da loja para gerar o link" disabled type="text"
                        class="mt-1 block w-full" />
                </div>
            @endif
        </div>
        <div class="flex flex-col md:mb-3 py-1">
            <div class="flex items-center gap-1">
                <svg class="w-5 h-5 mt-1 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <span class="text-gray-800 text-sm font-semibold">Atenção:</span>
            </div>
            <div class="flex flex-col">
                <span class="text-gray-800 text-sm">Um novo link é gerado automaticamente após atualização do Nome da Loja.</span>
            </div>
        </div>

    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    function showToast(message, type) {
        Toastify({
            text: message,
            duration: 4000, // duração em milissegundos
            gravity: 'top', // posição do toast
            stopOnFocus: true,
            position: 'right', // alinhamento horizontal do toast
            style: {
                background: type === 'success' ? 'green' : 'red',
                height: '50px',
                color: 'white',
                display: 'flex',
                margin: '10px',
                marginTop: '63px',
                padding: '20px',
                alignItems: 'center',
                justifyContent: 'center',
                position: 'absolute',
                right: '0',
                borderRadius: '10px',
                boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)',
                animation: 'slideInRight 0.5s',
                overflow: 'hidden',
            }
            //backgroundColor: type === 'success' ? 'green' : 'red', // cor de fundo do toast
        }).showToast();
    }

    document.getElementById('copyButton').addEventListener('click', function() {
        var inputFieldValue = document.getElementById('partner_link').value;

        // Criar um elemento de texto temporário
        var tempInput = document.createElement("textarea");
        tempInput.value = inputFieldValue;
        document.body.appendChild(tempInput);

        // Selecionar o texto no elemento de texto temporário
        tempInput.select();

        // Copiar o texto selecionado para a área de transferência
        document.execCommand('copy');

        // Remover o elemento de texto temporário
        document.body.removeChild(tempInput);

        toastr.success('Link copiado com sucesso!');

        // showToast('Link copiado com sucesso!', 'success');
    });
</script>
