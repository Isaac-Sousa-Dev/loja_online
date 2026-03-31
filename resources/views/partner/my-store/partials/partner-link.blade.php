<section class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-[#33363B]/8">
    <div class="flex items-center gap-2 mb-4">
        <div class="w-8 h-8 rounded-full bg-[#EDE9FE] flex items-center justify-center text-[#6A2BBA]">
            <i class="fa-solid fa-link text-sm"></i>
        </div>
        <h2 class="font-semibold text-lg text-[#33363B]">Link da Loja</h2>
    </div>

    @if(session('isNewLink'))
        <div class="bg-[#EDE9FE]/60 rounded-xl border border-[#6A2BBA]/20 p-3 mb-4 flex items-start justify-between">
            <div class="flex gap-2">
                <i class="fa-solid fa-circle-check text-[#6A2BBA] mt-0.5"></i>
                <div>
                    <div class="text-sm font-semibold text-[#33363B]">Novo link gerado</div>
                    <div class="text-xs text-[#33363B]/70 mt-1">Compartilhe o novo link abaixo com seus clientes!</div>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-[#6A2BBA] hover:text-[#D131A3] transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold text-[#33363B]">Link Público</label>
        
        @if($partner && $partner->partner_link != null)
            <div class="flex flex-col gap-2">
                <input id="partner_link" type="text" readonly
                    value="{{ request()->getSchemeAndHttpHost().'/catalog/'.$user->partner->partner_link }}"
                    class="bg-[#F8F9FC] border border-[#33363B]/10 text-[#33363B]/80 text-sm rounded-xl focus:ring-2 focus:ring-[#6A2BBA]/40 focus:border-[#6A2BBA] block w-full p-2.5" />
                
                <button id="copyButton" type="button" class="w-full px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] text-white hover:brightness-105 transition flex items-center justify-center gap-2 font-semibold shadow-md shadow-[#6A2BBA]/25">
                    <i class="fa-regular fa-copy"></i>
                    <span>Copiar Link</span>
                </button>
            </div>
        @else
            <input id="partner_link" type="text" readonly
                placeholder="Preencha as configurações da loja para gerar o link"
                class="bg-[#F8F9FC] border border-[#33363B]/10 text-[#33363B]/45 text-sm rounded-xl block w-full p-2.5 italic" />
        @endif
    </div>

    <div class="mt-4 bg-[#FFF8E7] rounded-xl p-3 flex gap-3 items-start border border-[#FF914D]/25">
        <i class="fa-solid fa-circle-info text-[#FF914D] mt-0.5"></i>
        <div class="text-xs text-[#33363B]/85 leading-relaxed">
            <span class="font-semibold block mb-0.5 text-[#33363B]">Atenção</span> 
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
