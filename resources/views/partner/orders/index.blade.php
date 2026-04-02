<x-app-layout>
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

@php
    $statusQuery = (array) request('status', []);
    $hasActiveFilters =
        request()->filled('date_from')
        || request()->filled('date_to')
        || request()->filled('client')
        || $statusQuery !== []
        || (request('fulfillment_type', 'all') !== 'all');
@endphp

<div class="p-2 flex md:justify-center pb-28 md:pb-8">
<div class="md:flex md:max-w-[1280px] flex-col w-full ml-2 mr-2">

    <nav class="flex items-center gap-1.5 text-sm text-[#33363B]/55 mt-4 mb-2 px-1" aria-label="breadcrumb">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-[#6A2BBA] transition-colors">
            <i class="fa-solid fa-house text-xs"></i><span>Início</span>
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-[#33363B]/35"></i>
        <span class="font-semibold text-[#33363B]">Pedidos</span>
    </nav>

    <div class="flex flex-col gap-4 mb-5 px-1 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <h1 class="font-bold text-3xl text-[#33363B] leading-tight">Pedidos</h1>
            <p class="text-sm text-[#33363B]/60 mt-1">Catálogo online — filtros, ações em massa e detalhes em tempo real.</p>
        </div>
        <div class="flex w-full flex-col gap-2 sm:flex-row sm:flex-wrap sm:justify-end sm:w-auto shrink-0">
            <button type="button"
                id="openOrderFiltersBtn"
                class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-xl border border-[#33363B]/15 bg-white px-4 py-2 text-sm font-semibold text-[#33363B] shadow-sm transition hover:border-[#6A2BBA]/35 hover:bg-[#F8F7FC] hover:text-[#6A2BBA] focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35 focus:ring-offset-2 sm:h-10 sm:min-w-[9.5rem] sm:flex-none"
                aria-expanded="false"
                aria-controls="orderFiltersPanel"
                aria-haspopup="dialog">
                <i class="fa-solid fa-sliders text-sm text-[#6A2BBA]" aria-hidden="true"></i>
                <span>Filtros</span>
                <span id="order-filters-active-indicator"
                      class="inline-flex h-2 w-2 shrink-0 rounded-full bg-[#6A2BBA] ring-2 ring-white {{ $hasActiveFilters ? '' : 'hidden' }}"
                      title="Há filtros ativos"
                      aria-hidden="true"></span>
                <span id="order-filters-active-sr" class="sr-only">{{ $hasActiveFilters ? 'Há filtros ativos' : '' }}</span>
            </button>
            <a id="orders-export-csv-link" href="{{ route('orders.export', request()->query()) }}"
               class="inline-flex h-11 flex-1 items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white border border-[#33363B]/15 text-[#33363B] hover:border-[#6A2BBA]/40 hover:text-[#6A2BBA] transition shadow-sm sm:h-10 sm:flex-none">
                <i class="fa-solid fa-file-csv text-xs"></i> Exportar CSV
            </a>
        </div>
    </div>

    <x-partner.order-filters-drawer />

    <div id="orders-table-wrapper" class="px-1">
        @include('partner.orders._table', ['orders' => $orders, 'store' => $store, 'drawerData' => $drawerData])
    </div>

</div>
</div>

{{-- Barra de ações em massa --}}
<div id="bulk-action-bar" class="hidden fixed bottom-0 inset-x-0 z-40 border-t border-[#33363B]/10 bg-white/95 backdrop-blur-md shadow-[0_-8px_30px_rgba(51,54,59,0.12)] px-4 py-3 md:py-4">
    <div class="max-w-[1280px] mx-auto flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <p class="text-sm font-semibold text-[#33363B]">
            <span id="bulk-selected-count">0</span> pedido(s) selecionado(s)
        </p>
        <div class="flex flex-wrap gap-2 justify-end">
            <button type="button" id="bulk-open-confirm" data-bulk-action="confirm"
                    class="px-4 py-2 rounded-xl text-sm font-bold bg-[#6A2BBA] text-white hover:brightness-105 transition">
                Confirmar selecionados
            </button>
            <button type="button" id="bulk-open-cancel" data-bulk-action="cancel"
                    class="px-4 py-2 rounded-xl text-sm font-bold bg-red-600 text-white hover:brightness-105 transition">
                Cancelar selecionados
            </button>
        </div>
    </div>
</div>

{{-- Modal confirmação em massa --}}
<div id="bulk-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="bulk-modal-title">
    <div class="absolute inset-0 bg-black/45" data-close-bulk-modal></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-[#33363B]/10">
        <h2 id="bulk-modal-title" class="text-lg font-bold text-[#33363B] mb-2">Confirmar ação</h2>
        <p id="bulk-modal-desc" class="text-sm text-[#33363B]/70 mb-4"></p>
        <ul id="bulk-modal-codes" class="text-xs font-mono max-h-32 overflow-y-auto mb-4 text-[#33363B]/80 space-y-1"></ul>
        <div class="flex gap-2 justify-end">
            <button type="button" class="px-4 py-2 rounded-xl text-sm font-semibold border border-[#33363B]/15" data-close-bulk-modal>Fechar</button>
            <button type="button" id="bulk-modal-execute" class="px-4 py-2 rounded-xl text-sm font-bold bg-[#33363B] text-white">Confirmar</button>
        </div>
    </div>
</div>

{{-- Drawer --}}
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 z-[48] hidden" onclick="closeOrderDrawer()"></div>
<div id="orderDrawer" class="fixed top-0 right-0 h-full w-full max-w-lg bg-white z-[49] shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col overflow-hidden border-l border-[#33363B]/10"
     role="dialog" aria-modal="true" aria-labelledby="order-drawer-title">
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#33363B]/8 flex-shrink-0">
        <div>
            <h2 id="order-drawer-title" class="font-bold text-[#33363B] text-lg">Detalhes do pedido</h2>
            <p id="drawerOrderMeta" class="text-xs text-[#33363B]/50 mt-0.5"></p>
        </div>
        <button type="button" onclick="closeOrderDrawer()" class="p-2 rounded-xl hover:bg-[#33363B]/5 text-[#33363B]/50" aria-label="Fechar">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4" id="orderDrawerBody"></div>
    <div id="drawerFooter" class="flex-shrink-0 px-6 py-4 border-t border-[#33363B]/8 flex flex-wrap gap-2"></div>
</div>

<script>
window.ordersDrawerData = @json($drawerData ?? []);

function mergeDrawerChunks() {
    document.querySelectorAll('.js-orders-drawer-chunk').forEach(function (el) {
        try {
            const chunk = JSON.parse(el.textContent || '{}');
            Object.assign(window.ordersDrawerData, chunk);
        } catch (e) {}
    });
}
mergeDrawerChunks();

function openOrderDrawer(orderId) {
    mergeDrawerChunks();
    const d = window.ordersDrawerData[orderId];
    if (!d) return;
    document.getElementById('drawerOrderMeta').textContent = (d.code.startsWith('#') ? d.code : '#' + d.code) + ' · ' + d.createdAt;
    let body = '';
    body += '<div class="flex flex-wrap gap-2 mb-2">';
    body += '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border bg-[#F8F7FC] text-[#33363B]">' + d.statusLabel + '</span>';
    body += '<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border border-[#33363B]/10 text-[#33363B]/80">' + d.fulfillmentLabel + '</span>';
    body += '</div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4 bg-[#FAFAFB]"><p class="text-[10px] font-bold text-[#33363B]/45 uppercase tracking-widest mb-2">Cliente</p>';
    body += '<p class="font-semibold text-[#33363B]">' + d.client.name + '</p>';
    body += '<p class="text-sm text-[#33363B]/65">' + d.client.phone + '</p>';
    body += '<p class="text-sm text-[#33363B]/65">' + d.client.email + '</p></div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4"><p class="text-[10px] font-bold text-[#33363B]/45 uppercase tracking-widest mb-3">Itens</p><div class="space-y-3">';
    d.lines.forEach(function (line) {
        console.log(line, 'line');
        body += '<div class="flex gap-3 items-start">';
        body += '<div class="w-14 h-14 rounded-xl overflow-hidden bg-[#33363B]/5 border border-[#33363B]/8 flex-shrink-0">';
        body += line.image ? '<img src="' + line.image.replace('public/', '') + '" class="w-full h-full object-cover" alt="">' : '<div class="w-full h-full flex items-center justify-center text-[#33363B]/25"><i class="fa-solid fa-image"></i></div>';
        body += '</div><div class="min-w-0 flex-1"><p class="font-bold text-sm text-[#33363B] leading-tight">' + line.name + '</p>';
        body += '<p class="text-xs text-[#33363B]/55">' + line.variation + ' · ' + line.qty + ' un.</p>';
        body += '<p class="text-xs font-semibold text-[#6A2BBA] mt-1">R$ ' + line.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</p></div></div>';
    });
    body += '</div></div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4 grid grid-cols-2 gap-2 text-sm">';
    body += '<div><span class="text-[10px] font-bold text-[#33363B]/45 uppercase">Subtotal</span><p class="font-bold text-[#33363B]">R$ ' + d.subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</p></div>';
    body += '<div><span class="text-[10px] font-bold text-[#33363B]/45 uppercase">Frete</span><p class="font-bold text-[#33363B]">R$ ' + d.shipping.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</p></div>';
    body += '<div><span class="text-[10px] font-bold text-[#33363B]/45 uppercase">Desconto</span><p class="font-bold text-[#33363B]">R$ ' + d.discount.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</p></div>';
    body += '<div><span class="text-[10px] font-bold text-[#33363B]/45 uppercase">Total</span><p class="font-extrabold text-[#6A2BBA]">R$ ' + d.total.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</p></div>';
    body += '</div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4 text-sm"><p class="text-[10px] font-bold text-[#33363B]/45 uppercase tracking-widest mb-2">Pagamento</p>';
    body += '<p class="text-[#33363B]">' + d.payment_method + ' · Parcelas: ' + d.payment_installments + '</p>';
    body += '<p class="text-xs text-[#33363B]/55 mt-1">Status: ' + d.payment_status + '</p></div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4 text-sm"><p class="text-[10px] font-bold text-[#33363B]/45 uppercase tracking-widest mb-2">Entrega</p>';
    body += '<p class="text-[#33363B]/75 leading-relaxed">' + d.delivery.lines + '</p></div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4"><p class="text-[10px] font-bold text-[#33363B]/45 uppercase tracking-widest mb-2">Mensagem</p>';
    body += '<p class="text-sm text-[#33363B]/70 italic">' + (d.message || '—') + '</p></div>';

    body += '<div class="rounded-2xl border border-[#33363B]/8 p-4"><p class="text-[10px] font-bold text-[#33363B]/45 uppercase tracking-widest mb-3">Histórico</p><ul class="space-y-2 text-xs text-[#33363B]/75">';
    (d.timeline || []).forEach(function (t) {
        body += '<li><span class="font-semibold text-[#33363B]">' + (t.to_label || t.to) + '</span> · ' + t.at + ' · ' + t.by + '</li>';
    });
    body += '</ul></div>';

    document.getElementById('orderDrawerBody').innerHTML = body;

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let footer = '';
    if (d.can_confirm) {
        footer += '<button type="button" class="flex-1 min-w-[120px] py-3 rounded-xl font-bold text-sm bg-[#6A2BBA] text-white" onclick="postOrderAction(\'' + '{{ url('/orders') }}' + '/' + d.id + '/confirm\', \'' + token + '\')">Confirmar</button>';
    }
    if (d.can_advance) {
        footer += '<button type="button" class="flex-1 min-w-[120px] py-3 rounded-xl font-bold text-sm bg-[#33363B] text-white" onclick="postOrderAction(\'' + '{{ url('/orders') }}' + '/' + d.id + '/advance\', \'' + token + '\')">Avançar status</button>';
    }
    if (d.can_complete_stock) {
        footer += '<button type="button" class="flex-1 min-w-[120px] py-3 rounded-xl font-bold text-sm bg-emerald-600 text-white" onclick="postOrderAction(\'' + '{{ url('/orders') }}' + '/' + d.id + '/complete\', \'' + token + '\')">Concluir venda</button>';
    }
    if (d.can_cancel) {
        footer += '<button type="button" class="flex-1 min-w-[120px] py-3 rounded-xl font-bold text-sm border-2 border-red-200 text-red-700" onclick="postOrderAction(\'' + '{{ url('/orders') }}' + '/' + d.id + '/cancel\', \'' + token + '\')">Cancelar</button>';
    }
    document.getElementById('drawerFooter').innerHTML = footer || '<p class="text-xs text-[#33363B]/45">Nenhuma ação disponível neste status.</p>';

    document.getElementById('drawerOverlay').classList.remove('hidden');
    document.getElementById('orderDrawer').classList.remove('translate-x-full');
    document.body.style.overflow = 'hidden';
}

function closeOrderDrawer() {
    document.getElementById('drawerOverlay').classList.add('hidden');
    document.getElementById('orderDrawer').classList.add('translate-x-full');
    document.body.style.overflow = '';
}

function postOrderAction(url, token) {
    const fd = new FormData();
    fd.append('_token', token);
    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
        .then(function (x) {
            if (!x.ok) { alert(x.j.message || 'Não foi possível concluir.'); return; }
            window.location.reload();
        })
        .catch(function () { alert('Erro de rede.'); });
}

(function () {
    const filterRoot = document.getElementById('orderFiltersDrawerRoot');
    const filterPanel = document.getElementById('orderFiltersPanel');
    const openFiltersBtn = document.getElementById('openOrderFiltersBtn');
    const transitionMs = 300;
    let filterCloseTimer = null;

    function openOrderFiltersDrawer() {
        if (!filterRoot || !filterPanel || !openFiltersBtn) return;
        if (filterRoot.getAttribute('aria-hidden') === 'false') return;
        if (filterCloseTimer) {
            clearTimeout(filterCloseTimer);
            filterCloseTimer = null;
        }
        filterRoot.classList.remove('pointer-events-none', 'invisible', 'opacity-0');
        filterRoot.classList.add('pointer-events-auto', 'visible', 'opacity-100');
        filterRoot.setAttribute('aria-hidden', 'false');
        openFiltersBtn.setAttribute('aria-expanded', 'true');
        requestAnimationFrame(function () {
            filterPanel.classList.remove('translate-y-full', 'md:translate-x-full');
        });
        document.body.classList.add('overflow-hidden');
    }

    window.closeOrderFiltersDrawer = function () {
        if (!filterRoot || !filterPanel || !openFiltersBtn) return;
        if (filterRoot.getAttribute('aria-hidden') === 'true') return;
        filterPanel.classList.add('translate-y-full', 'md:translate-x-full');
        filterRoot.setAttribute('aria-hidden', 'true');
        openFiltersBtn.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
        filterCloseTimer = setTimeout(function () {
            filterRoot.classList.add('pointer-events-none', 'invisible', 'opacity-0');
            filterRoot.classList.remove('pointer-events-auto', 'visible', 'opacity-100');
            filterCloseTimer = null;
        }, transitionMs);
        openFiltersBtn.focus({ preventScroll: true });
    };

    if (openFiltersBtn && filterRoot && filterPanel) {
        openFiltersBtn.addEventListener('click', function () {
            openOrderFiltersDrawer();
        });
        filterRoot.querySelectorAll('[data-order-filters-close]').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                window.closeOrderFiltersDrawer();
            });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (filterRoot.getAttribute('aria-hidden') === 'false') {
                e.preventDefault();
                window.closeOrderFiltersDrawer();
            }
        });
    }
})();

(function () {
    const $form = $('#orders-filter-form');
    const $wrap = $('#orders-table-wrapper');
    const exportBaseUrl = @json(route('orders.export'));
    let requestSeq = 0;

    function syncOrdersFilterFeedbackFromPartial() {
        const $bar = $wrap.find('#orders-stats-bar');
        const active = $bar.length && String($bar.attr('data-has-active-filters')) === '1';
        $('#order-filters-active-indicator').toggleClass('hidden', !active);
        const $sr = $('#order-filters-active-sr');
        if ($sr.length) {
            $sr.text(active ? 'Há filtros ativos' : '');
        }
        const q = window.location.search || '';
        const $export = $('#orders-export-csv-link');
        if ($export.length) {
            $export.attr('href', exportBaseUrl + q);
        }
    }

    function reloadTable(params) {
        const seq = ++requestSeq;
        const search = params || window.location.search.replace(/^\?/, '');
        $wrap.css('opacity', '0.65').attr('aria-busy', 'true');
        $.ajax({
            url: '{{ route('orders.index') }}' + (search ? '?' + search : ''),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (html) {
                if (seq !== requestSeq) return;
                $wrap.html(html);
                mergeDrawerChunks();
                bindPagination();
                bindRowChecks();
                syncOrdersFilterFeedbackFromPartial();
            },
            error: function () {
                if (seq !== requestSeq) return;
                alert('Não foi possível atualizar a lista. Tente novamente.');
            },
            complete: function () {
                if (seq !== requestSeq) return;
                $wrap.css('opacity', '1').removeAttr('aria-busy');
            }
        });
    }

    function bindPagination() {
        $wrap.off('click.ordersPag').on('click.ordersPag', '.pagination-wrap a', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (!href) return;
            const u = new URL(href, window.location.origin);
            reloadTable(u.searchParams.toString());
        });
    }

    function orderCodeFromEl(row) {
        if (!row) return null;
        const attr = row.getAttribute('data-order-code');
        if (attr) return attr.trim();
        const td = row.querySelector('td:nth-child(2)');
        return td ? td.textContent.trim() : null;
    }

    function visibleRowChecks() {
        return $('#orders-table-wrapper .order-row-check:visible');
    }

    function selectedIds() {
        return visibleRowChecks().filter(':checked').map(function () { return $(this).val(); }).get();
    }

    function updateBulkBar() {
        const n = selectedIds().length;
        $('#bulk-selected-count').text(n);
        $('#bulk-action-bar').toggleClass('hidden', n < 1);
    }

    function syncSelectAllState() {
        const $rows = visibleRowChecks();
        const total = $rows.length;
        const checked = $rows.filter(':checked').length;
        $('.order-select-all').prop('checked', total > 0 && checked === total);
    }

    function bindRowChecks() {
        $('.order-select-all').off('change').on('change', function () {
            const checked = this.checked;
            $('.order-select-all').prop('checked', checked);
            visibleRowChecks().prop('checked', checked);
            updateBulkBar();
        });
        $wrap.off('change', '.order-row-check').on('change', '.order-row-check', function () {
            syncSelectAllState();
            updateBulkBar();
        });
        syncSelectAllState();
        updateBulkBar();
    }

    $form.on('submit', function (e) {
        e.preventDefault();
        reloadTable($(this).serialize());
        const u = new URL(window.location.href);
        u.search = '?' + $(this).serialize();
        window.history.replaceState({}, '', u);
        if (typeof window.closeOrderFiltersDrawer === 'function') {
            window.closeOrderFiltersDrawer();
        }
    });

    let pendingBulkAction = null;
    const token = $('meta[name="csrf-token"]').attr('content');

    function openBulkModal(action) {
        const ids = selectedIds();
        if (!ids.length) return;
        pendingBulkAction = action;
        const labels = [];
        ids.forEach(function (id) {
            const row = document.querySelector('[data-order-id="' + id + '"]');
            const code = orderCodeFromEl(row) || '#' + id;
            labels.push(code);
        });
        $('#bulk-modal-codes').html(labels.map(function (c) { return '<li>' + c + '</li>'; }).join(''));
        $('#bulk-modal-desc').text(action === 'confirm'
            ? 'Os pedidos selecionados passarão para confirmado (quando estiverem pendentes).'
            : 'Os pedidos selecionados serão cancelados quando o status permitir.');
        $('#bulk-confirm-modal').removeClass('hidden');
    }

    $('#bulk-open-confirm').on('click', function () { openBulkModal('confirm'); });
    $('#bulk-open-cancel').on('click', function () { openBulkModal('cancel'); });

    $('[data-close-bulk-modal]').on('click', function () {
        $('#bulk-confirm-modal').addClass('hidden');
        pendingBulkAction = null;
    });

    $('#bulk-modal-execute').on('click', function () {
        if (!pendingBulkAction) return;
        $.post('{{ route('orders.bulk') }}', {
            _token: token,
            ids: selectedIds(),
            action: pendingBulkAction
        }).done(function () {
            window.location.reload();
        }).fail(function (xhr) {
            alert((xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Falha na ação em massa.');
        });
    });

    bindPagination();
    bindRowChecks();
    syncOrdersFilterFeedbackFromPartial();
})();
</script>

@endsection
</x-app-layout>
