@php
    $drawerData = $drawerData ?? [];
    $filtersState = $filtersState ?? [];
    $hasActiveFilters = (bool) ($filtersState['hasActiveDrawerFilters'] ?? false);
    $selectedPeriod = $filtersState['period'] ?? 'today';
    $selectedQuickStatus = $filtersState['selectedQuickStatus'] ?? 'all';
    $statusDotClasses = [
        \App\Enums\OrderStatus::PENDING->value => 'bg-amber-500',
        \App\Enums\OrderStatus::CONFIRMED->value => 'bg-sky-500',
        \App\Enums\OrderStatus::SEPARATING->value => 'bg-violet-500',
        \App\Enums\OrderStatus::DELIVERED->value => 'bg-indigo-500',
        \App\Enums\OrderStatus::COMPLETED->value => 'bg-emerald-500',
        \App\Enums\OrderStatus::CANCELLED->value => 'bg-red-500',
    ];
@endphp

{{-- Stats / feedback (igual Produtos: contagem + “Filtros aplicados”; atualizado no AJAX junto com a lista) --}}
<div id="orders-stats-bar"
     class="flex items-center gap-3 mb-3 flex-wrap"
     data-has-active-filters="{{ $hasActiveFilters ? '1' : '0' }}"
     role="status"
     aria-live="polite">
    <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 bg-gray-100 rounded-full px-3 py-1">
        <i class="fa-solid fa-receipt text-xs text-[#6A2BBA]" aria-hidden="true"></i>
        {{ $orders->total() }} {{ $orders->total() === 1 ? 'pedido' : 'pedidos' }}
    </span>
    @if ($hasActiveFilters)
        <span class="inline-flex items-center gap-1.5 text-md text-gray-400">
            <i class="fa-solid fa-filter text-xs text-gray-400" aria-hidden="true"></i>
            Filtros aplicados
        </span>
    @endif
</div>

<section class="mb-3 flex flex-col md:flex-row md:items-center md:justify-between rounded-2xl border border-[#33363B]/10 bg-white p-2 shadow-sm sm:mb-4 sm:p-4" aria-label="Atalhos de pedidos">
    <input type="hidden" id="orders-quick-client" value="{{ request('client') }}">
    <input type="hidden" id="orders-quick-fulfillment" value="{{ request('fulfillment_type', 'all') }}">
    <input type="hidden" id="orders-quick-status-current" value="{{ $selectedQuickStatus }}">

    <div class="flex flex-wrap gap-1.5">
        <button type="button"
            data-quick-status="all"
            class="inline-flex min-h-8 items-center rounded-full border px-2.5 py-1.5 text-[11px] font-semibold transition sm:min-h-9 sm:px-3 sm:py-2 sm:text-xs {{ $selectedQuickStatus === 'all' ? 'border-[#6A2BBA] bg-[#F8F4FE] text-[#6A2BBA]' : 'border-[#33363B]/12 bg-white text-[#33363B]/75 hover:border-[#6A2BBA]/30 hover:text-[#6A2BBA]' }}">
            Todos
        </button>
        @foreach (\App\Enums\OrderStatus::cases() as $status)
            <button type="button"
                data-quick-status="{{ $status->value }}"
                class="inline-flex min-h-8 items-center gap-1.5 rounded-full border px-2.5 py-1.5 text-[11px] font-semibold transition sm:min-h-9 sm:gap-2 sm:px-3 sm:py-2 sm:text-xs {{ $selectedQuickStatus === $status->value ? 'border-[#6A2BBA] bg-[#F8F4FE] text-[#6A2BBA]' : 'border-[#33363B]/12 bg-white text-[#33363B]/75 hover:border-[#6A2BBA]/30 hover:text-[#6A2BBA]' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $statusDotClasses[$status->value] ?? 'bg-slate-400' }} sm:h-2 sm:w-2" aria-hidden="true"></span>
                {{ $status->label() }}
            </button>
        @endforeach
    </div>

    <div class="flex justify-end">
        <div class="flex items-center gap-2 self-start lg:self-auto">
            <label for="orders-quick-period" class="text-[11px] font-semibold uppercase tracking-wide text-[#33363B]/55">Periodo</label>
            <select id="orders-quick-period"
                class="h-9 rounded-xl border border-[#33363B]/15 bg-white px-3 text-xs font-medium text-[#33363B] shadow-sm outline-none transition focus:border-[#6A2BBA] focus:ring-2 focus:ring-[#6A2BBA]/20 sm:h-10 sm:text-sm">
                <option value="today" @selected($selectedPeriod === 'today')>Hoje</option>
                <option value="7d" @selected($selectedPeriod === '7d')>Ultimos 7 dias</option>
                <option value="30d" @selected($selectedPeriod === '30d')>Ultimos 30 dias</option>
                @if ($selectedPeriod === 'custom')
                    <option value="custom" selected>Personalizado</option>
                @endif
            </select>
        </div>
    </div>
</section>

{{-- Mobile: cards --}}
<div class="space-y-2.5 md:hidden" role="list" aria-label="Lista de pedidos">
    <div class="flex items-center justify-between gap-2 rounded-xl border border-[#33363B]/10 bg-[#F8F7FC] px-3 py-2">
        <label class="inline-flex cursor-pointer items-center gap-2 text-[11px] font-semibold text-[#33363B]/70">
            <input type="checkbox" class="order-select-all rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                   aria-label="Selecionar todos desta página">
            <span>Selecionar página</span>
        </label>
    </div>
    @forelse($orders as $order)
        @php
            $codeDisplay = str_starts_with((string) $order->code, '#') ? $order->code : '#'.$order->code;
        @endphp
        <article class="rounded-[20px] border border-[#33363B]/10 bg-white p-3 shadow-sm"
                 role="listitem"
                 data-order-id="{{ $order->id }}"
                 data-order-code="{{ $codeDisplay }}">
            <div class="flex items-start gap-2.5">
                <input type="checkbox" class="order-row-check mt-0.5 rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA] shrink-0"
                       value="{{ $order->id }}" aria-label="Selecionar pedido {{ $order->code }}">
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <span class="block truncate font-mono text-[13px] font-bold tracking-tight text-[#33363B]">{{ $codeDisplay }}</span>
                            <p class="mt-0.5 truncate text-[15px] font-semibold leading-5 text-[#33363B]">{{ $order->client?->name ?? '—' }}</p>
                            @if($order->client?->phone)
                                <p class="truncate text-[11px] text-[#33363B]/50">{{ $order->client->phone }}</p>
                            @endif
                        </div>
                        <time class="shrink-0 text-[11px] text-[#33363B]/55 whitespace-nowrap" datetime="{{ $order->created_at->toIso8601String() }}">{{ $order->created_at->format('d/m H:i') }}</time>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-1.5">
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2 py-0.5 text-[11px] font-semibold {{ $order->status->badgeClasses() }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $order->status->dotClass() }}" aria-hidden="true"></span>
                            {{ $order->status->label() }}
                        </span>

                        @if($order->fulfillment_type === \App\Enums\FulfillmentType::DELIVERY)
                            <span class="inline-flex items-center gap-1 rounded-full border border-[#33363B]/10 bg-white px-2 py-0.5 text-[11px] font-medium text-[#33363B]/75" title="Entrega">
                                <i class="fa-solid fa-truck text-[10px] text-[#6A2BBA]" aria-hidden="true"></i>
                                Entrega
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-[#33363B]/10 bg-white px-2 py-0.5 text-[11px] font-medium text-[#33363B]/75" title="Retirada">
                                <i class="fa-solid fa-store text-[10px] text-[#6A2BBA]" aria-hidden="true"></i>
                                Retirada
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-3 grid grid-cols-[minmax(0,1fr)_auto] items-end gap-3 border-t border-[#33363B]/8 pt-2.5">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#33363B]/42">Itens</p>
                    <p class="mt-1 truncate text-[12px] leading-5 text-[#33363B]/78" title="{{ $order->itemsVariationSummary() }}">
                        {{ $order->itemsVariationSummary() }}
                    </p>
                    <p class="mt-2 text-[10px] font-bold uppercase tracking-[0.22em] text-[#33363B]/42">Valor</p>
                    <p class="mt-0.5 text-lg font-extrabold tracking-tight text-[#6A2BBA]">
                        R$ {{ number_format((float) $order->total, 2, ',', '.') }}
                    </p>
                </div>

                <button type="button"
                        class="inline-flex min-h-10 items-center justify-center rounded-xl bg-[#F8F4FE] px-3 py-2 text-[11px] font-bold text-[#6A2BBA] transition hover:bg-[#F1EAFE] hover:text-[#D131A3]"
                        onclick="openOrderDrawer({{ $order->id }})">
                    Ver detalhes
                </button>
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-[#33363B]/10 bg-white p-10 text-center text-[#33363B]/50">
            <i class="fa-solid fa-inbox text-3xl mb-2 opacity-30 block" aria-hidden="true"></i>
            Nenhum pedido encontrado com estes filtros.
        </div>
    @endforelse
</div>

{{-- Desktop: tabela --}}
<div class="hidden md:block overflow-x-auto rounded-2xl border border-[#33363B]/10 bg-white shadow-sm">
    <table class="min-w-full text-sm text-left" role="table" aria-label="Lista de pedidos">
        <thead class="bg-[#F8F7FC] text-[11px] font-bold uppercase tracking-wider text-[#33363B]/60 border-b border-[#33363B]/8">
            <tr>
                <th scope="col" class="p-3 w-10">
                    <input type="checkbox" class="order-select-all rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                           aria-label="Selecionar todos desta página">
                </th>
                <th scope="col" class="p-3">Código</th>
                <th scope="col" class="p-3">Data</th>
                <th scope="col" class="p-3">Cliente</th>
                <th scope="col" class="p-3 min-w-[12rem]">Itens</th>
                <th scope="col" class="p-3 text-right">Valor</th>
                <th scope="col" class="p-3">Status</th>
                <th scope="col" class="p-3">Tipo</th>
                <th scope="col" class="p-3 text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#33363B]/6">
            @forelse($orders as $order)
                @php
                    $codeDisplay = str_starts_with((string) $order->code, '#') ? $order->code : '#'.$order->code;
                @endphp
                <tr class="hover:bg-[#6A2BBA]/[0.03] transition" data-order-id="{{ $order->id }}" data-order-code="{{ $codeDisplay }}">
                    <td class="p-3 align-top">
                        <input type="checkbox" class="order-row-check rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                               value="{{ $order->id }}" aria-label="Selecionar pedido {{ $order->code }}">
                    </td>
                    <td class="p-3 align-top font-mono font-semibold text-[#33363B]">{{ $codeDisplay }}</td>
                    <td class="p-3 align-top text-[#33363B]/75 whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="p-3 align-top">
                        <div class="font-semibold text-[#33363B]">{{ $order->client?->name ?? '—' }}</div>
                        @if($order->client?->phone)
                            <div class="text-xs text-[#33363B]/50">{{ $order->client->phone }}</div>
                        @endif
                    </td>
                    <td class="p-3 align-top text-[#33363B]/80 text-xs leading-relaxed max-w-md">
                        {{ $order->itemsVariationSummary() }}
                    </td>
                    <td class="p-3 align-top text-right font-bold text-[#6A2BBA] whitespace-nowrap">
                        R$ {{ number_format((float) $order->total, 2, ',', '.') }}
                    </td>
                    <td class="p-3 align-top">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $order->status->badgeClasses() }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $order->status->dotClass() }}" aria-hidden="true"></span>
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td class="p-3 align-top">
                        @if($order->fulfillment_type === \App\Enums\FulfillmentType::DELIVERY)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-[#33363B]/80" title="Entrega">
                                <i class="fa-solid fa-truck text-[#6A2BBA]" aria-hidden="true"></i> Entrega
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-[#33363B]/80" title="Retirada">
                                <i class="fa-solid fa-store text-[#6A2BBA]" aria-hidden="true"></i> Retirada
                            </span>
                        @endif
                    </td>
                    <td class="p-3 align-top text-right whitespace-nowrap">
                        <button type="button"
                                class="text-xs font-bold text-[#6A2BBA] hover:text-[#D131A3] transition"
                                onclick="openOrderDrawer({{ $order->id }})">
                            Ver detalhes
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="p-12 text-center text-[#33363B]/50">
                        <i class="fa-solid fa-inbox text-3xl mb-2 opacity-30 block" aria-hidden="true"></i>
                        Nenhum pedido encontrado com estes filtros.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
    <div class="mt-4 px-1 pagination-wrap" role="navigation" aria-label="Paginação">
        {{ $orders->links() }}
    </div>
@endif

@if(!empty($drawerData))
<script type="application/json" class="js-orders-drawer-chunk" data-drawer-chunk="1">{!! json_encode($drawerData, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
