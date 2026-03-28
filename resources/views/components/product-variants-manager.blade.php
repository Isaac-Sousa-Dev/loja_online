@props(['productId' => null, 'existingVariants' => '[]'])

{{-- Seção de Atributos (cores + tamanhos) --}}
<div id="variantAttrsSection">
    <div class="grid md:grid-cols-2 gap-4">

        {{-- Cores --}}
        <div class="border border-gray-100 rounded-xl p-2 bg-gray-50/50">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-6 h-6 rounded-lg bg-violet-100 flex items-center justify-center">
                    <i class="fa-solid fa-circle-half-stroke text-violet-600 text-[10px]"></i>
                </div>
                <p class="text-sm font-bold text-gray-700">Cores disponíveis</p>
            </div>
            <div id="colorTags" class="flex flex-wrap gap-2 min-h-[40px] mb-3"></div>
            <div class="flex gap-2">
                <input type="text" id="colorInput" placeholder="Ex: Azul Marinho"
                    class="flex-1 border border-gray-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 transition placeholder-gray-400">
                <button type="button" onclick="addColor()"
                    class="flex items-center gap-1.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold px-3 py-2 rounded-xl transition">
                    <i class="fa-solid fa-plus text-[10px]"></i> Adicionar
                </button>
            </div>
        </div>

        {{-- Tamanhos --}}
        <div class="border border-gray-100 rounded-xl p-2 bg-gray-50/50">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-ruler text-blue-600 text-[10px]"></i>
                </div>
                <p class="text-sm font-bold text-gray-700">Tamanhos disponíveis</p>
            </div>
            <div id="sizeTags" class="flex flex-wrap gap-2 min-h-[40px] mb-3"></div>
            <div class="flex gap-2">
                <input type="text" id="sizeInput" placeholder="Ex: P, M, G, GG, 42"
                    class="flex-1 border border-gray-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition placeholder-gray-400">
                <button type="button" onclick="addSize()"
                    class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-2 rounded-xl transition">
                    <i class="fa-solid fa-plus text-[10px]"></i> Adicionar
                </button>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-sm text-gray-500">
            <span class="font-bold text-gray-800" id="colorCount">0</span> cores ×
            <span class="font-bold text-gray-800" id="sizeCount">0</span> tamanhos =
            <span class="font-extrabold text-violet-700 text-base" id="totalVariants">0</span> variantes
        </p>
        <button type="button" id="btnGenerateVariants"
            class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
            <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
            Gerar variantes
        </button>
    </div>
</div>

<input type="hidden" id="variantProductId" value="{{ $productId }}">
<input type="hidden" id="variantsPayload" name="variants_payload" value="">

<script>
(function () {
    const COLOR_MAP = {
        'preto':'#1a1a1a','negro':'#1a1a1a','branco':'#f8fafc','white':'#f8fafc',
        'azul':'#2563eb','blue':'#2563eb','vermelho':'#dc2626','red':'#dc2626',
        'verde':'#16a34a','green':'#16a34a','amarelo':'#eab308','yellow':'#eab308',
        'rosa':'#ec4899','pink':'#ec4899','cinza':'#6b7280','gray':'#6b7280',
        'laranja':'#f97316','orange':'#f97316','roxo':'#7c3aed','purple':'#7c3aed',
        'marrom':'#92400e','brown':'#92400e','bege':'#d4b896','nude':'#d4b896',
        'vinho':'#7f1d1d','bordo':'#7f1d1d','navy':'#1e3a5f','azul marinho':'#1e3a5f',
        'turquesa':'#0891b2','ciano':'#06b6d4','dourado':'#d97706','gold':'#d97706',
        'prata':'#9ca3af','silver':'#9ca3af','off white':'#f5f0e8','caramelo':'#c2853a',
    };

    window.VM = window.VM || {};
    VM.getHex = n => COLOR_MAP[(n||'').toLowerCase().trim()] || '#94a3b8';

    function slugify(s) {
        return (s||'').normalize('NFD').replace(/[\u0300-\u036f]/g,'')
            .toUpperCase().replace(/\s+/g,'-').replace(/[^A-Z0-9-]/g,'').slice(0,6);
    }

    VM.generateSku = (color, size) => {
        const nm = document.getElementById('name')?.value || 'PRD';
        return `${slugify(nm).slice(0,3)}-${slugify(color)}-${slugify(size)}`;
    };

    let colors = ['Preto','Branco','Azul','Vermelho'];
    let sizes  = ['P','M','G','GG'];
    VM.skuRows = [];

    const existing = @json(json_decode($existingVariants, true) ?? []);
    if (existing.length) {
        colors = [...new Set(existing.filter(v=>v.color).map(v=>v.color))];
        sizes  = [...new Set(existing.filter(v=>v.size).map(v=>v.size))];
        VM.skuRows = existing.map(v => ({
            color: v.color||'', size: v.size||'',
            sku:   v.sku || VM.generateSku(v.color||'', v.size||''),
            price: v.price_override ?? '',
            stock: v.stock ?? 0,
            id:    v.id || null,
        }));
    }

    function renderTags() {
        const ct = document.getElementById('colorTags');
        const st = document.getElementById('sizeTags');
        ct.innerHTML = colors.map((c,i) => {
            const hex = VM.getHex(c);
            const light = ['#f8fafc','#f5f0e8','#d4b896'].includes(hex);
            return `<span class="inline-flex items-center gap-1.5 bg-white border border-gray-200 rounded-full pl-2 pr-1 py-1 text-xs font-semibold text-gray-700 shadow-sm">
                <span class="w-3.5 h-3.5 rounded-full flex-shrink-0 ${light?'border border-gray-300':''}" style="background:${hex}"></span>
                ${c}
                <button type="button" onclick="removeColor(${i})" class="w-4 h-4 rounded-full bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-400 flex items-center justify-center transition text-[10px] ml-0.5">×</button>
            </span>`;
        }).join('');
        st.innerHTML = sizes.map((s,i) =>
            `<span class="inline-flex items-center gap-1.5 bg-white border border-gray-200 rounded-full pl-3 pr-1 py-1 text-xs font-semibold text-gray-700 shadow-sm">
                ${s}
                <button type="button" onclick="removeSize(${i})" class="w-4 h-4 rounded-full bg-gray-100 hover:bg-red-100 hover:text-red-500 text-gray-400 flex items-center justify-center transition text-[10px] ml-0.5">×</button>
            </span>`
        ).join('');
        document.getElementById('colorCount').textContent    = colors.length;
        document.getElementById('sizeCount').textContent     = sizes.length;
        document.getElementById('totalVariants').textContent = colors.length * sizes.length;
    }

    window.addColor = () => {
        const inp = document.getElementById('colorInput');
        const val = inp.value.trim();
        if (!val) return;
        const norm = val.charAt(0).toUpperCase() + val.slice(1);
        if (!colors.includes(norm)) { colors.push(norm); renderTags(); }
        inp.value = ''; inp.focus();
    };
    window.removeColor = i => { colors.splice(i,1); renderTags(); };

    window.addSize = () => {
        const inp = document.getElementById('sizeInput');
        const val = inp.value.trim().toUpperCase();
        if (!val) return;
        if (!sizes.includes(val)) { sizes.push(val); renderTags(); }
        inp.value = ''; inp.focus();
    };
    window.removeSize = i => { sizes.splice(i,1); renderTags(); };

    document.getElementById('colorInput').addEventListener('keydown', e => { if(e.key==='Enter'){e.preventDefault();addColor();} });
    document.getElementById('sizeInput').addEventListener('keydown',  e => { if(e.key==='Enter'){e.preventDefault();addSize();}  });

    document.getElementById('btnGenerateVariants').addEventListener('click', () => {
        if (!colors.length || !sizes.length) {
            alert('Adicione pelo menos uma cor e um tamanho.');
            return;
        }
        const newRows = [];
        colors.forEach(color => sizes.forEach(size => {
            const ex = VM.skuRows.find(r => r.color===color && r.size===size);
            newRows.push({ color, size,
                sku:   ex?.sku   || VM.generateSku(color, size),
                price: ex?.price ?? '',
                stock: ex?.stock ?? 0,
                id:    ex?.id    || null,
            });
        }));
        VM.skuRows = newRows;
        document.dispatchEvent(new CustomEvent('variantsGenerated', { detail: { rows: newRows } }));
    });

    window.saveAllVariants = () => {
        const productId = document.getElementById('variantProductId').value;
        const btn = document.getElementById('btnSaveVariants');
        if (!productId) {
            document.getElementById('variantsPayload').value = JSON.stringify(VM.skuRows);
            window.showVMToast('Variantes prontas!', 'success');
            return;
        }
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Salvando...';
        fetch(`/products/${productId}/variants/sync`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content||'' },
            body: JSON.stringify({ variants: VM.skuRows })
        }).then(r=>r.json()).then(()=>{
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Salvar variantes';
            window.showVMToast('Variantes salvas!', 'success');
        }).catch(()=>{
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> Salvar variantes';
            window.showVMToast('Erro ao salvar.', 'error');
        });
    };

    window.showVMToast = (msg, type) => {
        const t = document.createElement('div');
        t.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl z-[9999] flex items-center gap-2';
        t.innerHTML = `<i class="fa-solid ${type==='success'?'fa-circle-check text-emerald-400':'fa-circle-xmark text-red-400'}"></i> ${msg}`;
        document.body.appendChild(t);
        setTimeout(()=>t.remove(), 3000);
    };

    renderTags();
    if (VM.skuRows.length) {
        document.dispatchEvent(new CustomEvent('variantsGenerated', { detail: { rows: VM.skuRows } }));
    }
})();
</script>
