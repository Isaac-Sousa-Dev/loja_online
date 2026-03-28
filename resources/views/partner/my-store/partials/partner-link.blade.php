<section class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-link text-sm"></i>
        </div>
        <h2 class="font-semibold text-lg text-gray-800">Link da Loja</h2>
    </div>

    @if(session('isNewLink'))
        <div class="bg-green-50 rounded-xl border border-green-200 p-3 mb-4 flex items-start justify-between">
            <div class="flex gap-2">
                <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                <div>
                    <div class="text-sm font-semibold text-green-800">Novo link gerado</div>
                    <div class="text-xs text-green-600 mt-1">Compartilhe o novo link abaixo com seus clientes!</div>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold text-gray-700">Link Público</label>
        
        @if($partner && $partner->partner_link != null)
            <div class="flex flex-col gap-2">
                <input id="partner_link" type="text" readonly
                    value="{{ request()->getSchemeAndHttpHost().'/orders/'.$user->partner->partner_link }}"
                    class="bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                
                <button id="copyButton" type="button" class="w-full px-4 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-700 transition flex items-center justify-center gap-2 font-semibold">
                    <i class="fa-regular fa-copy"></i>
                    <span>Copiar Link</span>
                </button>
            </div>
        @else
            <input id="partner_link" type="text" readonly
                placeholder="Preencha as configurações da loja para gerar o link"
                class="bg-gray-50 border border-gray-200 text-gray-400 text-sm rounded-xl block w-full p-2.5 italic" />
        @endif
    </div>

    <div class="mt-4 bg-yellow-50 rounded-xl p-3 flex gap-3 items-start border border-yellow-100">
        <i class="fa-solid fa-circle-info text-yellow-600 mt-0.5"></i>
        <div class="text-xs text-yellow-800 leading-relaxed">
            <span class="font-semibold block mb-0.5">Atenção</span> 
            Um novo link é gerado automaticamente após a atualização do Nome da Loja.
        </div>
    </div>
</section>

<script>
    if(document.getElementById('copyButton')) {
        document.getElementById('copyButton').addEventListener('click', function() {
            var inputFieldValue = document.getElementById('partner_link').value;
            var tempInput = document.createElement("textarea");
            tempInput.value = inputFieldValue;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            if(typeof toastr !== 'undefined') {
                toastr.success('Link copiado com sucesso!');
            }
        });
    }
</script>
