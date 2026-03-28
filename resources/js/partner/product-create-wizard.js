import { initColorPhotosForWizard, buildColorPhotosUploadPayload } from './product-color-photos';

const STEPS = ['geral', 'atributos', 'fotos', 'estoque'];
let stepUnlocked = { geral: true, atributos: false, fotos: false, estoque: false };
let currentTab = 'geral';

function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text == null ? '' : String(text);
    return d.innerHTML;
}

/**
 * @param {{ wizardStoreUrl: string, productsIndexUrl: string }} cfg
 */
export function initProductCreateWizard(cfg) {
    const STEP_BTN = {
        geral: { label: 'Próximo: Atributos', icon: 'fa-arrow-right', action: () => goNextStep('geral') },
        atributos: { label: 'Próximo: Fotos', icon: 'fa-arrow-right', action: () => goNextStep('atributos') },
        fotos: { label: 'Próximo: Estoque', icon: 'fa-arrow-right', action: () => goNextStep('fotos') },
        estoque: { label: 'Salvar produto', icon: 'fa-floppy-disk', action: () => doSave() },
    };

    function updatePrimaryBtn() {
        const cfgBtn = STEP_BTN[currentTab];
        const btn = document.getElementById('btnPrimaryAction');
        const lbl = document.getElementById('btnPrimaryLabel');
        const ico = document.getElementById('btnPrimaryIcon');
        if (!btn || !cfgBtn) return;
        lbl.textContent = cfgBtn.label;
        if (ico) ico.className = `fa-solid ${cfgBtn.icon} text-xs`;
        btn.onclick = cfgBtn.action;

        if (currentTab === 'estoque') {
            const totalStock = (window.VM?.skuRows || []).reduce((s, r) => s + (parseInt(r.stock, 10) || 0), 0);
            btn.disabled = totalStock === 0;
            btn.classList.toggle('opacity-50', totalStock === 0);
            btn.classList.toggle('cursor-not-allowed', totalStock === 0);
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function setTabStyle(tab, active, unlocked) {
        const b = document.getElementById(`mainTabBtn-${tab}`);
        const dot = document.getElementById(`stepDot-${tab}`);
        if (!b || !dot) return;
        if (active) {
            b.className =
                'step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-blue-600 text-blue-700 transition -mb-px flex-shrink-0';
            dot.className =
                'w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] flex items-center justify-center font-bold flex-shrink-0';
            b.disabled = false;
            b.style.cursor = 'pointer';
            b.style.opacity = '1';
            b.setAttribute('aria-selected', 'true');
        } else if (unlocked) {
            b.className =
                'step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-gray-800 transition -mb-px flex-shrink-0';
            dot.className =
                'w-5 h-5 rounded-full bg-gray-300 text-gray-600 text-[10px] flex items-center justify-center font-bold flex-shrink-0';
            b.disabled = false;
            b.style.cursor = 'pointer';
            b.style.opacity = '1';
            b.setAttribute('aria-selected', 'false');
        } else {
            b.className =
                'step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-400 transition -mb-px flex-shrink-0';
            dot.className =
                'w-5 h-5 rounded-full bg-gray-200 text-gray-400 text-[10px] flex items-center justify-center font-bold flex-shrink-0';
            b.disabled = true;
            b.style.cursor = 'not-allowed';
            b.style.opacity = '0.5';
            b.setAttribute('aria-selected', 'false');
        }
    }

    function markStepDone(tab) {
        const dot = document.getElementById(`stepDot-${tab}`);
        if (dot) {
            dot.className =
                'w-5 h-5 rounded-full bg-emerald-500 text-white text-[10px] flex items-center justify-center font-bold flex-shrink-0';
            dot.innerHTML = '<i class="fa-solid fa-check" style="font-size:8px"></i>';
        }
    }

    function switchMainTab(tab) {
        STEPS.forEach((t) => {
            document.getElementById(`mainTab-${t}`)?.classList.toggle('hidden', t !== tab);
            setTabStyle(t, t === tab, stepUnlocked[t]);
        });
        currentTab = tab;
        updatePrimaryBtn();
        const activeBtn = document.getElementById(`mainTabBtn-${tab}`);
        activeBtn?.focus();
    }

    window.tryGoTab = function tryGoTab(tab) {
        if (!stepUnlocked[tab]) {
            window.toast.warning('Complete o passo anterior antes de continuar.');
            return;
        }
        switchMainTab(tab);
    };

    function validateStep1() {
        let valid = true;
        document.querySelectorAll('.step1-required').forEach((el) => {
            const empty = !(el.value && el.value.trim());
            el.classList.toggle('border-red-400', empty);
            if (empty) valid = false;
        });
        return valid;
    }

    function goNextStep(from) {
        if (from === 'geral') {
            if (!validateStep1()) {
                window.toast.error('Preencha os campos obrigatórios antes de continuar.');
                return;
            }
            markStepDone('geral');
            stepUnlocked.atributos = true;
            switchMainTab('atributos');
        } else if (from === 'atributos') {
            if (!stepUnlocked.estoque) {
                window.toast.warning('Gere as variantes antes de continuar.');
                return;
            }
            markStepDone('atributos');
            stepUnlocked.fotos = true;
            switchMainTab('fotos');
        } else if (from === 'fotos') {
            markStepDone('fotos');
            switchMainTab('estoque');
        }
    }

    window._wizardUpdateSkuRow = function (idx, field, el) {
        const val = el.value;
        if (!window.VM?.skuRows) return;
        const v = field === 'stock' ? parseInt(val, 10) || 0 : val;
        window.VM.skuRows[idx][field] = v;
        if (field === 'stock') {
            const stock = parseInt(val, 10) || 0;
            const badge = document.getElementById(`skuBadge_${idx}`);
            if (badge) {
                badge.className = `${
                    stock > 0 ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-600 border border-red-200'
                } text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap`;
                badge.textContent = stock > 0 ? `${stock} un.` : 'Esgotado';
            }
        }
        updateSkuFooter();
        if (currentTab === 'estoque') updatePrimaryBtn();
    };

    window._wizardRemoveSkuRow = function (idx) {
        if (!window.VM?.skuRows) return;
        window.VM.skuRows.splice(idx, 1);
        renderSkuTable(window.VM.skuRows);
    };

    function renderSkuTable(rows) {
        document.getElementById('skuTableSection')?.classList.remove('hidden');
        document.getElementById('estoqueEmpty')?.classList.add('hidden');
        const tbody = document.getElementById('skuTableBody');
        if (!tbody) return;
        tbody.innerHTML = rows
            .map((row, idx) => {
                const hex = (window.VM?.getHex || (() => '#94a3b8'))(row.color);
                const light = ['#f8fafc', '#f5f0e8', '#d4b896'].includes(hex);
                const stock = parseInt(row.stock, 10) || 0;
                const bc =
                    stock > 0
                        ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                        : 'bg-red-100 text-red-600 border border-red-200';
                const colorLabel = escapeHtml(row.color);
                const sizeLabel = escapeHtml(row.size);
                const skuVal = escapeHtml(row.sku || '');
                const priceVal = row.price != null && row.price !== '' ? escapeHtml(String(row.price)) : '';
                return `<tr class="hover:bg-violet-50/20 transition group">
            <td class="px-4 py-3"><div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full flex-shrink-0 ${
                light ? 'border border-gray-300' : ''
            }" style="background:${hex}"></span><span class="font-semibold text-gray-800 text-sm">${colorLabel}</span></div></td>
            <td class="px-4 py-3"><span class="bg-gray-100 text-gray-700 font-bold text-xs px-2.5 py-1 rounded-lg">${sizeLabel}</span></td>
            <td class="px-4 py-3"><input type="text" value="${skuVal}" onchange="window._wizardUpdateSkuRow(${idx},'sku',this)" class="font-mono text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 w-32 focus:outline-none focus:ring-2 focus:ring-violet-400 bg-gray-50 uppercase tracking-wide"></td>
            <td class="px-4 py-3"><div class="flex items-center border border-gray-200 rounded-lg overflow-hidden w-28 focus-within:ring-2 focus-within:ring-violet-400"><span class="bg-gray-50 px-2 py-1.5 text-xs text-gray-400 font-semibold border-r border-gray-200 select-none">R$</span><input type="number" value="${priceVal}" placeholder="0,00" step="0.01" min="0" onchange="window._wizardUpdateSkuRow(${idx},'price',this)" class="flex-1 px-2 py-1.5 text-sm focus:outline-none bg-white w-0 min-w-0"></div></td>
            <td class="px-4 py-3"><input type="number" value="${stock}" min="0" oninput="window._wizardUpdateSkuRow(${idx},'stock',this)" class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-violet-400 w-16"></td>
            <td class="px-4 py-3"><span id="skuBadge_${idx}" class="${bc} text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap">${
                    stock > 0 ? `${stock} un.` : 'Esgotado'
                }</span></td>
            <td class="px-4 py-3"><button type="button" onclick="window._wizardRemoveSkuRow(${idx})" class="opacity-0 group-hover:opacity-100 p-1.5 rounded-lg hover:bg-red-50 text-gray-300 hover:text-red-500 transition"><i class="fa-solid fa-trash-can text-xs"></i></button></td>
        </tr>`;
            })
            .join('');
        updateSkuFooter();
        if (currentTab === 'estoque') updatePrimaryBtn();
    }

    function updateSkuFooter() {
        if (!window.VM?.skuRows) return;
        const total = window.VM.skuRows.reduce((s, r) => s + (parseInt(r.stock, 10) || 0), 0);
        const esgotado = window.VM.skuRows.filter((r) => (parseInt(r.stock, 10) || 0) === 0).length;
        const el = document.getElementById('skuFooterSummary');
        if (el) {
            el.innerHTML = `<span class="font-bold text-gray-700">${total}</span> unidades em estoque &nbsp;·&nbsp; <span class="font-bold ${
                esgotado > 0 ? 'text-red-500' : 'text-gray-700'
            }">${esgotado}</span> variante${esgotado !== 1 ? 's' : ''} esgotada${esgotado !== 1 ? 's' : ''}`;
        }
    }

    document.addEventListener('variantsGenerated', function (e) {
        const rows = e.detail?.rows || [];
        if (!rows.length) return;
        stepUnlocked.estoque = true;
        setTabStyle('estoque', false, true);
        const skuBadge = document.getElementById('skuCountBadge');
        if (skuBadge) {
            skuBadge.textContent = rows.length;
            skuBadge.classList.remove('hidden');
        }
        renderSkuTable(rows);

        const seen = new Set();
        const colors = [];
        rows.forEach((r) => {
            if (r.color && !seen.has(r.color)) {
                seen.add(r.color);
                colors.push({
                    id: r.color,
                    nome: r.color,
                    hex: (window.VM?.getHex || (() => '#94a3b8'))(r.color),
                });
            }
        });
        const wrapper = document.getElementById('colorPhotosWrapper');
        if (wrapper) {
            stepUnlocked.fotos = true;
            setTabStyle('fotos', false, true);
            const fb = document.getElementById('fotosCountBadge');
            if (fb) {
                fb.textContent = colors.length;
                fb.classList.remove('hidden');
            }
            initColorPhotosForWizard(wrapper, colors);
        }

        if (currentTab === 'atributos') updatePrimaryBtn();
    });

    function doSave() {
        const totalStock = (window.VM?.skuRows || []).reduce((s, r) => s + (parseInt(r.stock, 10) || 0), 0);
        if (totalStock === 0) {
            window.toast.warning('Adicione estoque em pelo menos uma variante antes de salvar.');
            return;
        }
        const form = document.getElementById('productForm');
        if (!form) return;

        if (window.VM?.skuRows) {
            const vp = document.getElementById('variantsPayload');
            if (vp) vp.value = JSON.stringify(window.VM.skuRows);
        }

        const formData = new FormData(form);
        const vp = document.getElementById('variantsPayload');
        if (vp) {
            formData.set('variants_payload', vp.value);
        }

        const { flat, files } = buildColorPhotosUploadPayload();
        if (flat.length) {
            formData.append('color_photos_flat', JSON.stringify(flat));
            files.forEach((file) => formData.append('color_photo_files[]', file));
        }

        window.showLoader?.();
        // eslint-disable-next-line no-undef
        $.ajax({
            url: cfg.wizardStoreUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            success(data) {
                window.hideLoader?.();
                const msg =
                    data && typeof data.message === 'string' && data.message.trim() !== ''
                        ? data.message
                        : 'Produto cadastrado com sucesso!';
                if (typeof window.toast?.success === 'function') {
                    // Título omitido para feedback mais limpo; duração um pouco maior que o redirect
                    window.toast.success(msg, false, 4500);
                }
                const redirectMs = 2000;
                window.setTimeout(() => {
                    window.location.href = cfg.productsIndexUrl;
                }, redirectMs);
            },
            error(xhr) {
                window.hideLoader?.();
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors || {};
                    Object.keys(errors).forEach((field) => {
                        const el = document.querySelector(`[name="${field}"]`);
                        if (el) el.classList.add('border-red-400');
                    });
                    switchMainTab('geral');
                    window.toast.error('Corrija os erros no formulário.');
                } else {
                    window.toast.error(xhr.responseJSON?.message || 'Erro ao salvar produto.');
                }
            },
        });
    }

    // Profit calc (jQuery)
    // eslint-disable-next-line no-undef
    $('#price, #cost').on('blur', function () {
        // eslint-disable-next-line no-undef
        const price = parseFloat($('#price').val().replace(/\./g, '').replace(',', '.')) || 0;
        // eslint-disable-next-line no-undef
        const cost = parseFloat($('#cost').val().replace(/\./g, '').replace(',', '.')) || 0;
        if (cost > price && cost > 0) {
            // eslint-disable-next-line no-undef
            $('#divCostGreaterPrice').removeClass('hidden');
            // eslint-disable-next-line no-undef
            $('#msgCostGreaterPrice').text('Atenção: custo maior que o preço de venda!');
        } else {
            // eslint-disable-next-line no-undef
            $('#divCostGreaterPrice').addClass('hidden');
        }
        if (cost > 0 && price > 0) {
            // eslint-disable-next-line no-undef
            $('#profit').val(`${Math.floor(((price - cost) / cost) * 100)}%`);
        }
    });

    // eslint-disable-next-line no-undef
    $(document).ready(function () {
        // eslint-disable-next-line no-undef
        $('.price-mask').mask('000.000.000.000.000,00', { reverse: true });
        updatePrimaryBtn();
        document.querySelectorAll('.step1-required').forEach((el) => {
            el.addEventListener('input', () => el.classList.toggle('border-red-400', !el.value.trim()));
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.productCreateWizardConfig) {
        initProductCreateWizard(window.productCreateWizardConfig);
    }
});
