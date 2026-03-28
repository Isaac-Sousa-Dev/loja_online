{{-- Componente: Fotos por Cor --}}
{{-- Props: colors (JSON string de [{id, nome, hex}]), initialImages (JSON opcional) --}}
@props(['colors' => '[]', 'initialImages' => '{}'])

<div id="colorPhotosRoot">

    {{-- Métricas --}}
    <div class="grid grid-cols-3 gap-3 mb-4">
        <div class="bg-gray-50 rounded-xl p-3 text-center">
            <p class="text-xl font-extrabold text-gray-800" id="metricTotal">0</p>
            <p class="text-xs text-gray-500 mt-0.5">Total de fotos</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3 text-center">
            <p class="text-xl font-extrabold text-emerald-600" id="metricWithPhotos">0</p>
            <p class="text-xs text-gray-500 mt-0.5">Cores com fotos</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3 text-center">
            <p class="text-xl font-extrabold text-amber-500" id="metricWithoutPhotos">0</p>
            <p class="text-xs text-gray-500 mt-0.5">Cores sem fotos</p>
        </div>
    </div>

    {{-- Layout duas colunas --}}
    <div class="flex flex-col md:flex-row gap-4 min-h-[420px]">

        {{-- Coluna esquerda: navegação --}}
        <div class="md:w-[220px] flex-shrink-0 flex flex-col gap-2">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-1">Cores do produto</p>
            <div id="colorTabsList" class="grid grid-cols-2 md:grid-cols-12 gap-1"></div>

            {{-- Dica --}}
            <div class="mt-3 bg-blue-50 border border-blue-100 rounded-xl p-3">
                <p class="text-[11px] text-blue-700 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    A <strong>primeira foto</strong> de cada cor é usada como miniatura no seletor de cores. Arraste para reordenar.
                </p>
            </div>
        </div>

        {{-- Coluna direita: gerenciador --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col">

            {{-- Header da cor ativa --}}
            <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100">
                <span id="activeColorDot" class="w-5 h-5 rounded-full flex-shrink-0 border border-gray-200"></span>
                <span id="activeColorName" class="text-sm font-semibold text-gray-800"></span>
                <span id="activeColorCount" class="text-xs text-gray-400 ml-1"></span>
            </div>

            {{-- Conteúdo --}}
            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">

                {{-- Zona de upload --}}
                <div id="uploadZone"
                    class="border-2 border-dashed border-gray-200 rounded-xl p-7 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50 transition"
                    onclick="document.getElementById('colorPhotoInput').click()">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-plus text-gray-400 text-lg"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-600 text-center">Clique para fazer upload ou arraste as imagens aqui</p>
                    <p class="text-xs text-gray-400 text-center">JPG, PNG ou WEBP · máx. 5 MB por arquivo · múltiplos arquivos permitidos</p>
                </div>
                <input type="file" id="colorPhotoInput" accept="image/*" multiple class="hidden">

                {{-- Grid de fotos --}}
                <div id="photosSection" class="hidden">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Fotos cadastradas</p>
                    <div id="photosGrid" class="grid gap-2.5" style="grid-template-columns: repeat(auto-fill, minmax(110px, 1fr))"></div>
                    <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                        A foto <strong>CAPA</strong> aparece primeiro na galeria e como miniatura no seletor de cores. Arraste para reordenar.
                    </p>
                </div>

                {{-- Estado vazio --}}
                <div id="emptyState" class="flex-1 flex flex-col items-center justify-center py-8 text-center hidden">
                    <i class="fa-solid fa-image text-gray-200 text-4xl mb-3"></i>
                    <p class="text-sm font-semibold text-gray-400" id="emptyStateText">Nenhuma foto para esta cor</p>
                    <p class="text-xs text-gray-300 mt-1">Clique na área acima para adicionar fotos</p>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Hidden input para o payload --}}
<input type="hidden" id="colorPhotosPayload" name="color_photos_payload" value="{}">

<script>
(function() {
    const COLORS = @json(json_decode($colors, true) ?? []);
    // state: { [colorId]: ImageItem[] }
    let state = @json(json_decode($initialImages, true) ?? []);
    if (!state || Array.isArray(state)) state = {};

    // Ensure all colors have an array
    COLORS.forEach(c => { if (!state[c.id]) state[c.id] = []; });

    let activeColorId = COLORS.length ? COLORS[0].id : null;
    let dragSrcIdx = null;

    // ── Helpers ──
    function getHex(hex) { return hex || '#94a3b8'; }
    function isLight(hex) { return ['#f8fafc','#f5f0e8','#d4b896','#ffffff','#fff'].includes((hex||'').toLowerCase()); }
    function totalPhotos() { return Object.values(state).reduce((s, arr) => s + arr.length, 0); }
    function colorsWithPhotos() { return Object.values(state).filter(arr => arr.length > 0).length; }
    function colorsWithoutPhotos() { return COLORS.filter(c => !state[c.id]?.length).length; }

    function savePayload() {
        const el = document.getElementById('colorPhotosPayload');
        if (el) el.value = JSON.stringify(state);
    }

    // ── Metrics ──
    function updateMetrics() {
        document.getElementById('metricTotal').textContent         = totalPhotos();
        document.getElementById('metricWithPhotos').textContent    = colorsWithPhotos();
        document.getElementById('metricWithoutPhotos').textContent = colorsWithoutPhotos();
        const warnEl = document.getElementById('metricWithoutPhotos');
        warnEl.className = `text-xl font-extrabold ${colorsWithoutPhotos() > 0 ? 'text-amber-500' : 'text-gray-800'}`;
    }

    // ── Left tabs ──
    function renderColorTabs() {
        const list = document.getElementById('colorTabsList');
        if (!list) return;
        list.innerHTML = COLORS.map(c => {
            const count   = state[c.id]?.length || 0;
            const noPhoto = count === 0;
            const active  = c.id === activeColorId;
            const hex     = getHex(c.hex);
            const light   = isLight(hex);
            return `<button type="button" onclick="setActiveColor('${c.id}')"
                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left transition ${active ? 'bg-gray-100 border border-gray-200' : 'hover:bg-gray-50 border border-transparent'}">
                <span class="w-4 h-4 rounded-full flex-shrink-0 ${light ? 'border border-gray-300' : ''}" style="background:${hex}"></span>
                <span class="flex-1 text-sm font-medium truncate ${noPhoto ? 'text-amber-600' : 'text-gray-700'}">${c.nome}</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full ${noPhoto ? 'bg-amber-100 text-amber-600' : 'bg-gray-200 text-gray-600'}">${count}</span>
            </button>`;
        }).join('');
    }

    // ── Right panel ──
    function renderActivePanel() {
        if (!activeColorId) return;
        const color  = COLORS.find(c => c.id === activeColorId);
        const photos = state[activeColorId] || [];
        const hex    = getHex(color?.hex);
        const light  = isLight(hex);

        // Header
        const dot = document.getElementById('activeColorDot');
        dot.style.background = hex;
        dot.className = `w-5 h-5 rounded-full flex-shrink-0 ${light ? 'border border-gray-300' : 'border border-transparent'}`;
        document.getElementById('activeColorName').textContent  = color?.nome || '';
        document.getElementById('activeColorCount').textContent = `${photos.length} foto${photos.length !== 1 ? 's' : ''}`;

        // Empty state text
        document.getElementById('emptyStateText').textContent = `Nenhuma foto para "${color?.nome}"`;

        // Toggle sections
        const grid    = document.getElementById('photosSection');
        const empty   = document.getElementById('emptyState');
        if (photos.length > 0) {
            grid.classList.remove('hidden');
            empty.classList.add('hidden');
            renderPhotosGrid(photos);
        } else {
            grid.classList.add('hidden');
            empty.classList.remove('hidden');
        }
    }

    function renderPhotosGrid(photos) {
        const grid = document.getElementById('photosGrid');
        grid.innerHTML = photos.map((img, idx) => `
            <div class="photo-card relative rounded-xl overflow-hidden border-2 ${img.isCapa ? 'border-gray-800' : 'border-gray-100'} bg-gray-50 cursor-grab"
                 draggable="true"
                 data-idx="${idx}"
                 ondragstart="photoDragStart(event,${idx})"
                 ondragover="photoDragOver(event)"
                 ondrop="photoDrop(event,${idx})"
                 ondragend="photoDragEnd(event)">
                <div class="aspect-square">
                    <img src="${img.previewUrl}" class="w-full h-full object-cover" alt="${img.label || ''}">
                </div>
                ${img.isCapa ? `<span class="absolute top-1 left-1 bg-gray-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">CAPA</span>` : ''}
                <span class="absolute top-1 right-1 bg-black/50 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">${idx + 1}</span>
                <div class="absolute bottom-0 left-0 right-0 bg-white/90 px-1.5 py-1 flex items-center justify-between gap-1">
                    <span class="text-[10px] text-gray-500 truncate flex-1">${img.label || 'foto'}</span>
                    <div class="flex gap-1 flex-shrink-0">
                        ${!img.isCapa ? `<button type="button" onclick="setCapaPhoto(${idx})" class="text-amber-500 hover:text-amber-600 transition" title="Definir como capa"><i class="fa-solid fa-star text-[10px]"></i></button>` : ''}
                        <button type="button" onclick="removePhoto(${idx})" class="text-gray-400 hover:text-red-500 transition" title="Remover"><i class="fa-solid fa-xmark text-[10px]"></i></button>
                    </div>
                </div>
            </div>`).join('');
    }

    // ── Actions ──
    window.setActiveColor = function(colorId) {
        activeColorId = colorId;
        renderColorTabs();
        renderActivePanel();
    };

    window.setCapaPhoto = function(idx) {
        const photos = state[activeColorId];
        photos.forEach((p, i) => p.isCapa = i === idx);
        renderActivePanel();
        renderColorTabs();
        updateMetrics();
        savePayload();
    };

    window.removePhoto = function(idx) {
        const photos = state[activeColorId];
        const wasCapa = photos[idx].isCapa;
        photos.splice(idx, 1);
        // Recalc positions
        photos.forEach((p, i) => p.posicao = i + 1);
        // Promote first to capa if removed was capa
        if (wasCapa && photos.length > 0) photos[0].isCapa = true;
        renderActivePanel();
        renderColorTabs();
        updateMetrics();
        savePayload();
    };

    // ── Drag & Drop ──
    window.photoDragStart = function(e, idx) {
        dragSrcIdx = idx;
        e.dataTransfer.effectAllowed = 'move';
        e.currentTarget.style.opacity = '0.4';
    };
    window.photoDragOver = function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    };
    window.photoDrop = function(e, targetIdx) {
        e.preventDefault();
        if (dragSrcIdx === null || dragSrcIdx === targetIdx) return;
        const photos = state[activeColorId];
        const [moved] = photos.splice(dragSrcIdx, 1);
        photos.splice(targetIdx, 0, moved);
        photos.forEach((p, i) => p.posicao = i + 1);
        dragSrcIdx = null;
        renderActivePanel();
        savePayload();
    };
    window.photoDragEnd = function(e) {
        e.currentTarget.style.opacity = '1';
        dragSrcIdx = null;
    };

    // ── Upload ──
    document.getElementById('colorPhotoInput').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const photos = state[activeColorId];
        files.forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                window.toast?.warning(`"${file.name}" excede 5 MB e foi ignorado.`);
                return;
            }
            const reader = new FileReader();
            reader.onload = ev => {
                const isFirst = photos.length === 0;
                photos.push({
                    id:         `new_${Date.now()}_${Math.random().toString(36).slice(2)}`,
                    colorId:    activeColorId,
                    file:       file,
                    previewUrl: ev.target.result,
                    posicao:    photos.length + 1,
                    isCapa:     isFirst,
                    label:      file.name,
                });
                renderActivePanel();
                renderColorTabs();
                updateMetrics();
                savePayload();
            };
            reader.readAsDataURL(file);
        });
        this.value = '';
    });

    // Drag files onto upload zone
    const zone = document.getElementById('uploadZone');
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('bg-blue-50'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('bg-blue-50'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('bg-blue-50');
        const input = document.getElementById('colorPhotoInput');
        // Simulate file selection via DataTransfer
        const dt = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    });

    // ── Init ──
    if (!COLORS.length) {
        document.getElementById('colorPhotosRoot').innerHTML =
            `<div class="flex flex-col items-center justify-center py-12 text-center">
                <i class="fa-solid fa-palette text-gray-200 text-4xl mb-3"></i>
                <p class="font-semibold text-gray-400 text-sm">Nenhuma cor cadastrada</p>
                <p class="text-xs text-gray-300 mt-1">Volte para a aba Atributos e adicione cores primeiro.</p>
            </div>`;
        return;
    }

    renderColorTabs();
    renderActivePanel();
    updateMetrics();
})();
</script>
