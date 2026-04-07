<x-app-layout>
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

@php
    $f = $filters ?? [];
    $formatBrl = static fn (float $v): string => 'R$ '.number_format($v, 2, ',', '.');
@endphp

<div class="min-h-screen bg-slate-100 pb-10">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-4 pt-4">

        <nav class="flex items-center gap-1.5 text-sm text-slate-500 mb-3" aria-label="Navegação">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                <span>Voltar</span>
            </a>
            <span class="text-slate-300" aria-hidden="true">/</span>
            <span class="font-semibold text-slate-700">Vendas</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-4 lg:gap-5 items-start">

            {{-- Área principal: filtros + tabela --}}
            <div class="flex-1 w-full min-w-0 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Vendas</h1>
                        <p class="text-sm text-slate-500 mt-0.5">Pedidos confirmados e finalizados registrados automaticamente.</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 border border-blue-200 rounded-xl px-3 py-2 bg-blue-50/50 transition">
                        <i class="fa-solid fa-bag-shopping text-xs" aria-hidden="true"></i>
                        Ir para pedidos
                    </a>
                </div>

                <form method="get" action="{{ route('index.sales') }}" class="px-4 sm:px-5 py-4 bg-slate-50/80 border-b border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
                        <div class="sm:col-span-2">
                            <label for="q" class="block text-xs font-semibold text-slate-600 mb-1">Buscar</label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                                <input type="search" name="q" id="q" value="{{ $f['q'] ?? '' }}"
                                    placeholder="Código do pedido, cliente, telefone, itens…"
                                    class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 outline-none transition"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div>
                            <label for="date_from" class="block text-xs font-semibold text-slate-600 mb-1">De</label>
                            <input type="date" name="date_from" id="date_from" value="{{ $f['date_from'] ?? '' }}"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 outline-none">
                        </div>
                        <div>
                            <label for="date_to" class="block text-xs font-semibold text-slate-600 mb-1">Até</label>
                            <input type="date" name="date_to" id="date_to" value="{{ $f['date_to'] ?? '' }}"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 outline-none">
                        </div>
                        <div>
                            <label for="sale_status" class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                            <select name="sale_status" id="sale_status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 outline-none">
                                <option value="all" @selected(($f['sale_status'] ?? 'all') === 'all')>Todos</option>
                                <option value="confirmed" @selected(($f['sale_status'] ?? '') === 'confirmed')>Confirmada</option>
                                <option value="completed" @selected(($f['sale_status'] ?? '') === 'completed')>Finalizada</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment_method" class="block text-xs font-semibold text-slate-600 mb-1">Pagamento</label>
                            <select name="payment_method" id="payment_method" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 outline-none">
                                <option value="">Todos</option>
                                <option value="pix" @selected(($f['payment_method'] ?? '') === 'pix')>PIX</option>
                                <option value="credit_card" @selected(($f['payment_method'] ?? '') === 'credit_card')>Cartão</option>
                                <option value="cash" @selected(($f['payment_method'] ?? '') === 'cash')>Dinheiro</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button type="submit" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                            <i class="fa-solid fa-filter text-xs" aria-hidden="true"></i>
                            Aplicar filtros
                        </button>
                        <a href="{{ route('index.sales') }}" class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-xl hover:bg-white transition">
                            Limpar
                        </a>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide font-semibold border-b border-slate-100">
                            <tr>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Pedido</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Data</th>
                                <th scope="col" class="px-4 py-3 min-w-[140px]">Cliente</th>
                                <th scope="col" class="px-4 py-3 hidden lg:table-cell">Operador</th>
                                <th scope="col" class="px-4 py-3 hidden md:table-cell min-w-[200px]">Itens</th>
                                <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">Subtotal</th>
                                <th scope="col" class="px-4 py-3 text-right whitespace-nowrap hidden sm:table-cell">Desc.</th>
                                <th scope="col" class="px-4 py-3 text-right whitespace-nowrap font-semibold">Total</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sales as $sale)
                                @php
                                    $effectiveStatus = $sale->sale_status ?? $sale->status;
                                    $statusUi = match ($effectiveStatus) {
                                        'confirmed' => ['label' => 'Confirmada', 'class' => 'bg-sky-100 text-sky-800 border-sky-200'],
                                        'completed' => ['label' => 'Finalizada', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                                        default => ['label' => ucfirst((string) $effectiveStatus), 'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
                                    };
                                    $code = $sale->order_ref
                                        ? (str_starts_with((string) $sale->order_ref, 'single_')
                                            ? '#'.str_pad(substr((string) $sale->order_ref, 7), 6, '0', STR_PAD_LEFT)
                                            : $sale->order_ref)
                                        : ('#'.$sale->id);
                                    $rowSub = $sale->subtotal !== null ? (float) $sale->subtotal : (float) $sale->total_amount;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-700 whitespace-nowrap">{{ $code }}</td>
                                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-800 truncate max-w-[200px]">{{ $sale->client?->name ?? '—' }}</div>
                                        @if($sale->client?->phone)
                                            <div class="text-xs text-slate-500">{{ $sale->client->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 hidden lg:table-cell">
                                        {{ $sale->seller?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 text-xs hidden md:table-cell max-w-xs">
                                        <span class="line-clamp-2" title="{{ $sale->items_summary }}">{{ $sale->items_summary ?? '—' }}</span>
                                        @if($sale->items_count)
                                            <span class="block text-[11px] text-slate-400 mt-0.5">{{ $sale->items_count }} linha(s)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-700 whitespace-nowrap">{{ $formatBrl($rowSub) }}</td>
                                    <td class="px-4 py-3 text-right text-slate-600 whitespace-nowrap hidden sm:table-cell">{{ $formatBrl((float) $sale->discount) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-900 whitespace-nowrap">{{ $formatBrl((float) $sale->total_amount) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold border {{ $statusUi['class'] }}">
                                            {{ $statusUi['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-16 text-center">
                                        <div class="flex flex-col items-center gap-2 text-slate-500">
                                            <i class="fa-solid fa-receipt text-4xl text-slate-200" aria-hidden="true"></i>
                                            <p class="font-semibold text-slate-600">Nenhuma venda neste filtro</p>
                                            <p class="text-sm max-w-md">Confirme pedidos em <a href="{{ route('orders.index') }}" class="text-blue-600 font-medium hover:underline">Pedidos</a> para que apareçam aqui.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sales->hasPages())
                    <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>

            {{-- Painel resumo (estilo POS) --}}
            <aside class="w-full lg:w-80 flex-shrink-0 rounded-2xl bg-gradient-to-b from-slate-900 to-slate-800 text-white shadow-lg border border-slate-700/50 overflow-hidden" aria-label="Resumo das vendas filtradas">
                <div class="px-5 py-4 border-b border-white/10">
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-cyan-300/90">Resumo</p>
                    <p class="text-sm text-slate-300 mt-1">Totais conforme filtros ativos</p>
                </div>
                <div class="px-5 py-4 space-y-4 text-sm">
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-400">Vendas</span>
                        <span class="font-bold text-white tabular-nums">{{ $dashboard['sale_count'] }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-400">Unidades (linhas)</span>
                        <span class="font-semibold text-slate-100 tabular-nums">{{ $dashboard['items_sum'] }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-400">Subtotal bruto</span>
                        <span class="font-semibold text-slate-100 tabular-nums">{{ $formatBrl($dashboard['subtotal_sum']) }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-slate-400">Descontos</span>
                        <span class="font-semibold text-amber-200/90 tabular-nums">{{ $formatBrl($dashboard['discount_sum']) }}</span>
                    </div>
                    <div class="pt-3 mt-1 border-t border-white/10">
                        <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold mb-1">Saldo filtrado</p>
                        <p class="text-2xl font-extrabold text-cyan-300 tabular-nums leading-tight">{{ $formatBrl($dashboard['total_sum']) }}</p>
                        <p class="text-[11px] text-slate-500 mt-2">Soma dos totais das vendas listadas (após filtros).</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
</x-app-layout>
