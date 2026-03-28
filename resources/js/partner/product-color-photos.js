/**
 * Fotos por cor — usado no wizard de cadastro de produto.
 */

let cpDragSrcIdx = null;
let cpActiveColorId = null;

export function getColorPhotosState() {
    return window._cpState || {};
}

export function getColorPhotosColors() {
    return window._cpColors || [];
}

export function buildColorPhotosUploadPayload() {
    const flat = [];
    const files = [];
    const state = window._cpState || {};
    const colors = window._cpColors || [];
    for (const c of colors) {
        const photos = state[c.id] || [];
        for (const p of photos) {
            if (p.file) {
                flat.push({ color: c.id, is_cover: !!p.isCapa });
                files.push(p.file);
            }
        }
    }
    return { flat, files };
}

function getHexCP(hex) {
    return hex || '#94a3b8';
}

function isLightCP(hex) {
    return ['#f8fafc', '#f5f0e8', '#d4b896', '#ffffff', '#fff'].includes((hex || '').toLowerCase());
}

function updateCPMetrics() {
    const state = window._cpState || {};
    const colors = window._cpColors || [];
    const total = Object.values(state).reduce((s, a) => s + a.length, 0);
    const withP = Object.values(state).filter((a) => a.length > 0).length;
    const withoutP = colors.filter((c) => !state[c.id]?.length).length;
    const t = document.getElementById('cpMetricTotal');
    const w = document.getElementById('cpMetricWith');
    const wo = document.getElementById('cpMetricWithout');
    if (t) t.textContent = total;
    if (w) w.textContent = withP;
    if (wo) {
        wo.textContent = withoutP;
        wo.className = `text-xl font-extrabold ${withoutP > 0 ? 'text-amber-500' : 'text-gray-800'}`;
    }
}

function renderCPTabs() {
    const list = document.getElementById('cpColorTabsList');
    if (!list) return;
    const state = window._cpState || {};
    const colors = window._cpColors || [];
    list.innerHTML = colors
        .map((c) => {
            const count = state[c.id]?.length || 0;
            const noPhoto = count === 0;
            const active = c.id === cpActiveColorId;
            const hex = getHexCP(c.hex);
            const light = isLightCP(hex);
            return `<button type="button" data-cp-tab="${escapeAttr(c.id)}"
            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left transition ${active ? 'bg-gray-100 border border-gray-200' : 'hover:bg-gray-50 border border-transparent'}">
            <span class="w-4 h-4 rounded-full flex-shrink-0 ${light ? 'border border-gray-300' : ''}" style="background:${hex}"></span>
            <span class="flex-1 text-sm font-medium truncate ${noPhoto ? 'text-amber-600' : 'text-gray-700'}">${escapeHtml(c.nome)}</span>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full ${noPhoto ? 'bg-amber-100 text-amber-600' : 'bg-gray-200 text-gray-600'}">${count}</span>
        </button>`;
        })
        .join('');

    list.querySelectorAll('[data-cp-tab]').forEach((btn) => {
        btn.addEventListener('click', () => cpSetActive(btn.getAttribute('data-cp-tab')));
    });
}

function escapeHtml(s) {
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function escapeAttr(s) {
    return String(s).replace(/"/g, '&quot;');
}

function renderCPPanel() {
    if (!cpActiveColorId) {
        const colors = window._cpColors || [];
        if (colors.length) {
            cpActiveColorId = colors[0].id;
        } else return;
    }
    const color = (window._cpColors || []).find((c) => c.id === cpActiveColorId);
    const photos = (window._cpState || {})[cpActiveColorId] || [];
    const hex = getHexCP(color?.hex);
    const light = isLightCP(hex);

    const dot = document.getElementById('cpActiveColorDot');
    if (dot) {
        dot.style.background = hex;
        dot.className = `w-5 h-5 rounded-full flex-shrink-0 ${light ? 'border border-gray-300' : 'border border-transparent'}`;
    }
    const nameEl = document.getElementById('cpActiveColorName');
    if (nameEl) nameEl.textContent = color?.nome || '';
    const countEl = document.getElementById('cpActiveColorCount');
    if (countEl) countEl.textContent = `${photos.length} foto${photos.length !== 1 ? 's' : ''}`;
    const emptyText = document.getElementById('cpEmptyStateText');
    if (emptyText) emptyText.textContent = `Nenhuma foto para "${color?.nome}"`;

    const grid = document.getElementById('cpPhotosSection');
    const empty = document.getElementById('cpEmptyState');
    if (photos.length > 0) {
        grid?.classList.remove('hidden');
        empty?.classList.add('hidden');
        renderCPGrid(photos);
    } else {
        grid?.classList.add('hidden');
        empty?.classList.remove('hidden');
    }
}

function renderCPGrid(photos) {
    const grid = document.getElementById('cpPhotosGrid');
    if (!grid) return;
    grid.innerHTML = photos
        .map(
            (img, idx) => `
        <div class="relative rounded-xl overflow-hidden border-2 ${img.isCapa ? 'border-gray-800' : 'border-gray-100'} bg-gray-50 cursor-grab"
             draggable="true"
             data-cp-drag="${idx}">
            <div class="aspect-square"><img src="${img.previewUrl}" class="w-full h-full object-cover" alt="${escapeAttr(img.label || 'Foto do produto')}"></div>
            ${img.isCapa ? `<span class="absolute top-1 left-1 bg-gray-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">CAPA</span>` : ''}
            <span class="absolute top-1 right-1 bg-black/50 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">${idx + 1}</span>
            <div class="absolute bottom-0 left-0 right-0 bg-white/90 px-1.5 py-1 flex items-center justify-between gap-1">
                <span class="text-[10px] text-gray-500 truncate flex-1">${escapeHtml(img.label || 'foto')}</span>
                <div class="flex gap-1 flex-shrink-0">
                    ${!img.isCapa ? `<button type="button" data-cp-capa="${idx}" class="text-amber-500 hover:text-amber-600 transition" title="Definir como capa"><i class="fa-solid fa-star text-[10px]"></i></button>` : ''}
                    <button type="button" data-cp-remove="${idx}" class="text-gray-400 hover:text-red-500 transition" title="Remover"><i class="fa-solid fa-xmark text-[10px]"></i></button>
                </div>
            </div>
        </div>`
        )
        .join('');

    grid.querySelectorAll('[data-cp-drag]').forEach((el) => {
        const idx = parseInt(el.getAttribute('data-cp-drag'), 10);
        el.addEventListener('dragstart', (e) => cpDragStart(e, idx));
        el.addEventListener('dragover', cpDragOver);
        el.addEventListener('drop', (e) => cpDrop(e, idx));
        el.addEventListener('dragend', cpDragEnd);
    });
    grid.querySelectorAll('[data-cp-capa]').forEach((btn) => {
        btn.addEventListener('click', () => cpSetCapa(parseInt(btn.getAttribute('data-cp-capa'), 10)));
    });
    grid.querySelectorAll('[data-cp-remove]').forEach((btn) => {
        btn.addEventListener('click', () => cpRemove(parseInt(btn.getAttribute('data-cp-remove'), 10)));
    });
}

function cpSetActive(colorId) {
    cpActiveColorId = colorId;
    renderCPTabs();
    renderCPPanel();
}

function cpSetCapa(idx) {
    const photos = window._cpState[cpActiveColorId];
    photos.forEach((p, i) => (p.isCapa = i === idx));
    renderCPPanel();
    renderCPTabs();
    updateCPMetrics();
}

function cpRemove(idx) {
    const photos = window._cpState[cpActiveColorId];
    const wasCapa = photos[idx].isCapa;
    photos.splice(idx, 1);
    photos.forEach((p, i) => (p.posicao = i + 1));
    if (wasCapa && photos.length > 0) photos[0].isCapa = true;
    renderCPPanel();
    renderCPTabs();
    updateCPMetrics();
}

function cpDragStart(e, idx) {
    cpDragSrcIdx = idx;
    e.dataTransfer.effectAllowed = 'move';
    e.currentTarget.style.opacity = '0.4';
}

function cpDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
}

function cpDrop(e, targetIdx) {
    e.preventDefault();
    if (cpDragSrcIdx === null || cpDragSrcIdx === targetIdx) return;
    const photos = window._cpState[cpActiveColorId];
    const [moved] = photos.splice(cpDragSrcIdx, 1);
    photos.splice(targetIdx, 0, moved);
    photos.forEach((p, i) => (p.posicao = i + 1));
    cpDragSrcIdx = null;
    renderCPPanel();
}

function cpDragEnd(e) {
    e.currentTarget.style.opacity = '1';
    cpDragSrcIdx = null;
}

/**
 * @param {{ id: string, nome: string, hex?: string }[]} colors
 */
export function initColorPhotosForWizard(wrapper, colors) {
    if (!wrapper) return;
    window._cpColors = colors;
    window._cpState = {};
    colors.forEach((c) => {
        window._cpState[c.id] = [];
    });
    cpActiveColorId = colors.length ? colors[0].id : null;

    wrapper.innerHTML = `
        <div>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xl font-extrabold text-gray-800" id="cpMetricTotal">0</p><p class="text-xs text-gray-500 mt-0.5">Total de fotos</p></div>
                <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xl font-extrabold text-emerald-600" id="cpMetricWith">0</p><p class="text-xs text-gray-500 mt-0.5">Cores com fotos</p></div>
                <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xl font-extrabold text-amber-500" id="cpMetricWithout">0</p><p class="text-xs text-gray-500 mt-0.5">Cores sem fotos</p></div>
            </div>
            <div class="flex gap-4 min-h-[420px]">
                <div class="w-[220px] flex-shrink-0 flex flex-col gap-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-1">Cores do produto</p>
                    <div id="cpColorTabsList" class="flex flex-col gap-1"></div>
                    <div class="mt-3 bg-blue-50 border border-blue-100 rounded-xl p-3">
                        <p class="text-[11px] text-blue-700 leading-relaxed"><i class="fa-solid fa-circle-info mr-1"></i>A <strong>primeira foto</strong> de cada cor é usada como miniatura no seletor de cores. Arraste para reordenar.</p>
                    </div>
                </div>
                <div class="flex-1 bg-white rounded-xl border border-gray-100 overflow-hidden flex flex-col">
                    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-100">
                        <span id="cpActiveColorDot" class="w-5 h-5 rounded-full flex-shrink-0 border border-gray-200"></span>
                        <span id="cpActiveColorName" class="text-sm font-semibold text-gray-800"></span>
                        <span id="cpActiveColorCount" class="text-xs text-gray-400 ml-1"></span>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-4">
                        <div id="cpUploadZone" class="border-2 border-dashed border-gray-200 rounded-xl p-7 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center"><i class="fa-solid fa-plus text-gray-400 text-lg"></i></div>
                            <p class="text-sm font-semibold text-gray-600 text-center">Clique para fazer upload ou arraste as imagens aqui</p>
                            <p class="text-xs text-gray-400 text-center">JPG, PNG ou WEBP · máx. 5 MB por arquivo · múltiplos arquivos permitidos</p>
                        </div>
                        <input type="file" id="cpFileInput" accept="image/*" multiple class="hidden">
                        <div id="cpPhotosSection" class="hidden">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Fotos cadastradas</p>
                            <div id="cpPhotosGrid" class="grid gap-2.5" style="grid-template-columns:repeat(auto-fill,minmax(110px,1fr))"></div>
                            <p class="text-xs text-gray-400 mt-3 leading-relaxed">A foto <strong>CAPA</strong> aparece primeiro na galeria e como miniatura no seletor de cores. Arraste para reordenar.</p>
                        </div>
                        <div id="cpEmptyState" class="flex-1 flex flex-col items-center justify-center py-8 text-center hidden">
                            <i class="fa-solid fa-image text-gray-200 text-4xl mb-3"></i>
                            <p class="text-sm font-semibold text-gray-400" id="cpEmptyStateText">Nenhuma foto para esta cor</p>
                            <p class="text-xs text-gray-300 mt-1">Clique na área acima para adicionar fotos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    const zone = document.getElementById('cpUploadZone');
    const input = document.getElementById('cpFileInput');
    if (zone && input) {
        zone.addEventListener('click', () => input.click());
        input.addEventListener('change', function (e) {
            const fileList = Array.from(e.target.files || []);
            const photos = window._cpState[cpActiveColorId];
            fileList.forEach((file) => {
                if (file.size > 5 * 1024 * 1024) {
                    window.toast?.warning(`"${file.name}" excede 5 MB.`);
                    return;
                }
                const reader = new FileReader();
                reader.onload = (ev) => {
                    photos.push({
                        id: `new_${Date.now()}_${Math.random().toString(36).slice(2)}`,
                        colorId: cpActiveColorId,
                        file,
                        previewUrl: ev.target.result,
                        posicao: photos.length + 1,
                        isCapa: photos.length === 0,
                        label: file.name,
                    });
                    renderCPPanel();
                    renderCPTabs();
                    updateCPMetrics();
                };
                reader.readAsDataURL(file);
            });
            this.value = '';
        });
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('bg-blue-50');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('bg-blue-50'));
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('bg-blue-50');
            const dt = new DataTransfer();
            Array.from(e.dataTransfer.files).forEach((f) => dt.items.add(f));
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        });
    }

    renderCPTabs();
    renderCPPanel();
    updateCPMetrics();
}
