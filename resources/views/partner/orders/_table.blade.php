@php
    $drawerData = $drawerData ?? [];
    $statusQueryPartial = (array) request('status', []);
    $hasActiveFilters =
        request()->filled('date_from')
        || request()->filled('date_to')
        || request()->filled('client')
        || $statusQueryPartial !== []
        || (request('fulfillment_type', 'all') !== 'all');
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

{{-- Mobile: cards --}}
<div class="md:hidden space-y-3" role="list" aria-label="Lista de pedidos">
    <div class="flex items-center justify-between gap-2 rounded-xl border border-[#33363B]/10 bg-[#F8F7FC] px-3 py-2">
        <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-[#33363B]/70">
            <input type="checkbox" class="order-select-all rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                   aria-label="Selecionar todos desta página">
            <span>Selecionar página</span>
        </label>
    </div>
    @forelse($orders as $order)
        @php
            $codeDisplay = str_starts_with((string) $order->code, '#') ? $order->code : '#'.$order->code;
        @endphp
        <article class="rounded-2xl border border-[#33363B]/10 bg-white p-4 shadow-sm"
                 role="listitem"
                 data-order-id="{{ $order->id }}"
                 data-order-code="{{ $codeDisplay }}">
            <div class="flex gap-3 border-b border-[#33363B]/8 pb-3 mb-3">
                <input type="checkbox" class="order-row-check mt-1 rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA] shrink-0"
                       value="{{ $order->id }}" aria-label="Selecionar pedido {{ $order->code }}">
                <div class="min-w-0 flex-1 space-y-1">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <span class="font-mono text-sm font-bold text-[#33363B]">{{ $codeDisplay }}</span>
                        <time class="text-xs text-[#33363B]/55 whitespace-nowrap" datetime="{{ $order->created_at->toIso8601String() }}">{{ $order->created_at->format('d/m/Y H:i') }}</time>
                    </div>
                    <div>
                        <p class="font-semibold text-[#33363B] leading-snug">{{ $order->client?->name ?? '—' }}</p>
                        @if($order->client?->phone)
                            <p class="text-xs text-[#33363B]/50">{{ $order->client->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <dl class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-[#33363B]/45">Itens</dt>
                    <dd class="text-xs text-[#33363B]/80 leading-relaxed mt-0.5">{{ $order->itemsVariationSummary() }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-[#33363B]/45">Valor</dt>
                    <dd class="font-bold text-[#6A2BBA] mt-0.5">R$ {{ number_format((float) $order->total, 2, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-[#33363B]/45">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $order->status->badgeClasses() }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $order->status->dotClass() }}" aria-hidden="true"></span>
                            {{ $order->status->label() }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-2 flex flex-wrap items-center justify-between gap-2 pt-1">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#33363B]/45 block mb-1">Tipo</span>
                        @if($order->fulfillment_type === \App\Enums\FulfillmentType::DELIVERY)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-[#33363B]/80" title="Entrega">
                                <i class="fa-solid fa-truck text-[#6A2BBA]" aria-hidden="true"></i> Entrega
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-[#33363B]/80" title="Retirada">
                                <i class="fa-solid fa-store text-[#6A2BBA]" aria-hidden="true"></i> Retirada
                            </span>
                        @endif
                    </div>
                    <button type="button"
                            class="text-xs font-bold text-[#6A2BBA] hover:text-[#D131A3] transition py-2 px-1 min-h-[44px] min-w-[44px] inline-flex items-center justify-end"
                            onclick="openOrderDrawer({{ $order->id }})">
                        Ver detalhes
                    </button>
                </div>
            </dl>
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
