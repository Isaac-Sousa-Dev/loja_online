@php
    $selectedStatuses = collect((array) request('status', []))->filter()->all();
@endphp

<div id="orderFiltersDrawerRoot"
    class="fixed inset-0 z-[45] flex items-end justify-center md:items-stretch md:justify-end pointer-events-none invisible opacity-0 transition-[opacity,visibility] duration-200"
    aria-hidden="true">
    <div class="absolute inset-0 bg-[#33363B]/45 backdrop-blur-[2px]"
        data-order-filters-close
        aria-hidden="true"></div>

    <aside id="orderFiltersPanel"
        class="relative z-10 flex w-full max-h-[min(90dvh,720px)] md:max-h-none md:h-full md:max-w-[420px] flex-col bg-white shadow-[0_-8px_40px_rgba(51,54,59,0.15)] md:shadow-2xl rounded-t-2xl md:rounded-none md:rounded-l-2xl border border-[#33363B]/10 md:border-l md:border-t-0 md:border-b-0 md:border-r-0 translate-y-full md:translate-y-0 md:translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] pointer-events-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="orderFiltersDrawerTitle">

        <div class="mx-auto mt-2 h-1 w-10 shrink-0 rounded-full bg-[#33363B]/15 md:hidden" aria-hidden="true"></div>

        <header class="flex items-center justify-between gap-3 border-b border-[#33363B]/10 px-4 py-3 shrink-0">
            <div class="min-w-0">
                <h2 id="orderFiltersDrawerTitle" class="text-base font-bold text-[#33363B] truncate">Filtrar pedidos</h2>
                <p class="text-xs text-[#33363B]/55 mt-0.5">Datas, cliente, status e tipo de entrega.</p>
            </div>
            <button type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[#33363B]/15 text-[#33363B]/70 hover:bg-[#F8F7FC] transition-colors focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/40 focus:ring-offset-2"
                data-order-filters-close
                aria-label="Fechar painel de filtros">
                <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
            </button>
        </header>

        <form method="get" action="{{ route('orders.index') }}" id="orders-filter-form" class="flex min-h-0 flex-1 flex-col">
            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain px-4 py-4">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <label for="date_from" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#33363B]/55">De</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="h-10 w-full rounded-xl border border-[#33363B]/15 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/20">
                    </div>
                    <div class="sm:col-span-1">
                        <label for="date_to" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#33363B]/55">Até</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="h-10 w-full rounded-xl border border-[#33363B]/15 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/20">
                    </div>
                </div>

                <div>
                    <label for="client" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#33363B]/55">Cliente</label>
                    <input type="search" name="client" id="client" value="{{ request('client') }}" placeholder="Nome ou telefone"
                        class="h-10 w-full rounded-xl border border-[#33363B]/15 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/20"
                        autocomplete="off">
                </div>

                <fieldset class="rounded-xl border border-[#33363B]/10 p-3">
                    <legend class="px-1 text-xs font-semibold uppercase tracking-wide text-[#33363B]/55">Status</legend>
                    <p class="text-[11px] text-[#33363B]/45 mb-3">Selecione um ou mais. Nenhum = todos.</p>
                    <div class="space-y-2">
                        @foreach (\App\Enums\OrderStatus::cases() as $st)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-transparent px-2 py-2 hover:bg-[#F8F7FC] focus-within:ring-2 focus-within:ring-[#6A2BBA]/30">
                                <input type="checkbox" name="status[]" value="{{ $st->value }}"
                                    id="filter_status_{{ $st->value }}"
                                    @checked(in_array($st->value, $selectedStatuses, true))
                                    class="h-4 w-4 rounded border-[#33363B]/25 text-[#6A2BBA] focus:ring-[#6A2BBA]">
                                <span class="text-sm font-medium text-[#33363B]">{{ $st->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div>
                    <label for="fulfillment_type" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#33363B]/55">Tipo</label>
                    <select name="fulfillment_type" id="fulfillment_type"
                        class="h-10 w-full rounded-xl border border-[#33363B]/15 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/20">
                        <option value="all" @selected(request('fulfillment_type', 'all') === 'all')>Todos</option>
                        <option value="delivery" @selected(request('fulfillment_type') === 'delivery')>Entrega</option>
                        <option value="pickup" @selected(request('fulfillment_type') === 'pickup')>Retirada</option>
                    </select>
                </div>
            </div>

            <footer class="shrink-0 border-t border-[#33363B]/10 bg-[#FAFAFB] px-4 py-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] md:pb-3">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-[#33363B] text-sm font-semibold text-white shadow-sm transition hover:bg-[#2a2e32] focus:outline-none focus:ring-2 focus:ring-[#6A2BBA] focus:ring-offset-2">
                        <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                        Aplicar filtros
                    </button>
                    <a href="{{ route('orders.index') }}"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-xl border border-[#33363B]/15 bg-white text-sm font-semibold text-[#33363B] shadow-sm transition hover:bg-[#F8F7FC] focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/30 focus:ring-offset-2 sm:flex-none sm:min-w-[7rem] text-center">
                        Limpar
                    </a>
                </div>
            </footer>
        </form>
    </aside>
</div>
