<x-app-layout>
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

<div class="p-2 flex md:justify-center pb-24 md:pb-0">
<div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-house text-xs"></i><span>Início</span>
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
        <span class="font-semibold text-gray-700">Pedidos</span>
    </nav>

    <div class="mb-5 px-1">
        <h2 class="font-bold text-3xl text-gray-800 leading-tight">Pedidos</h2>
        <p class="text-sm text-gray-500 mt-1">Gerencie os pedidos recebidos pelo seu catálogo online.</p>
    </div>

    @php
        $total       = $groupedOrders->count();
        $emAberto    = $groupedOrders->where('status', 'in_open')->count();
        $emAndamento = $groupedOrders->where('status', 'in_progress')->count();
        $vendidos    = $groupedOrders->where('status', 'sold')->count();
        $cancelados  = $groupedOrders->where('status', 'canceled')->count();
    @endphp

    {{-- Filter Tabs --}}
    <div class="flex gap-2 flex-wrap mb-4 px-1">
        <button data-filter="all" class="filter-tab active-tab flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition border border-transparent bg-gray-800 text-white">
            Todos <span class="bg-white/20 text-white text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $total }}</span>
        </button>
        <button data-filter="in_open" class="filter-tab flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition border border-gray-200 bg-white text-gray-600 hover:border-amber-300 hover:text-amber-700">
            Em aberto @if($emAberto > 0)<span class="bg-amber-100 text-amber-700 text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $emAberto }}</span>@endif
        </button>
        <button data-filter="in_progress" class="filter-tab flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition border border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:text-blue-700">
            Em andamento @if($emAndamento > 0)<span class="bg-blue-100 text-blue-700 text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $emAndamento }}</span>@endif
        </button>
        <button data-filter="sold" class="filter-tab flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition border border-gray-200 bg-white text-gray-600 hover:border-emerald-300 hover:text-emerald-700">
            Vendidos @if($vendidos > 0)<span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $vendidos }}</span>@endif
        </button>
        <button data-filter="canceled" class="filter-tab flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition border border-gray-200 bg-white text-gray-600 hover:border-red-300 hover:text-red-600">
            Cancelados @if($cancelados > 0)<span class="bg-red-100 text-red-600 text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $cancelados }}</span>@endif
        </button>
    </div>

    @if($groupedOrders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 flex flex-col items-center text-center">
            <i class="fa-solid fa-inbox text-5xl text-gray-200 mb-4"></i>
            <p class="font-semibold text-gray-500 text-lg">Nenhum pedido recebido ainda</p>
            <p class="text-sm text-gray-400 mt-1 max-w-sm">Compartilhe o link do seu catálogo para que seus clientes possam fazer pedidos.</p>
        </div>
    @else

    <div class="flex flex-col gap-3" id="requestsList">
        @foreach($groupedOrders as $order)
        @php
            $sc = [
                'in_open'     => ['label' => 'Em aberto',    'dot' => 'bg-amber-400',   'badge' => 'bg-amber-100 text-amber-700 border-amber-200'],
                'in_progress' => ['label' => 'Em andamento', 'dot' => 'bg-blue-500',    'badge' => 'bg-blue-100 text-blue-700 border-blue-200'],
                'sold'        => ['label' => 'Vendido',       'dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                'canceled'    => ['label' => 'Cancelado',     'dot' => 'bg-red-400',     'badge' => 'bg-red-100 text-red-600 border-red-200'],
            ][$order['status']] ?? ['label' => '—', 'dot' => 'bg-gray-300', 'badge' => 'bg-gray-100 text-gray-500 border-gray-200'];
            $firstRequest = $order['items']->first();
            $isMultiItem  = $order['items']->count() > 1;
        @endphp

        <div class="request-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden"
             data-status="{{ $order['status'] }}">

            <div class="flex gap-3 p-4">

                {{-- Thumbnails --}}
                <div class="flex-shrink-0">
                    @if($isMultiItem)
                        {{-- Grid de até 4 thumbs para pedidos com múltiplos itens --}}
                        <div class="grid grid-cols-2 gap-1 w-16">
                            @foreach($order['items']->take(4) as $item)
                                <div class="w-7 h-7 rounded-lg overflow-hidden bg-gray-100 border border-gray-100">
                                    @if($item->product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->url) }}" class="w-full h-full object-cover object-center">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-[8px]"><i class="fa-solid fa-image"></i></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 border border-gray-100 cursor-pointer open-drawer" data-id="{{ $firstRequest->id }}">
                            @if($firstRequest->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $firstRequest->product->images->first()->url) }}" class="w-full h-full object-cover object-center">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-solid fa-image text-xl"></i></div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0 flex flex-col gap-2">
                    {{-- Status + data --}}
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $sc['badge'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>{{ $sc['label'] }}
                        </span>
                        @if($isMultiItem)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                {{ $order['items']->count() }} produtos
                            </span>
                        @endif
                        @if($order['order_ref'])
                            <span class="text-[10px] text-gray-400 font-mono">{{ $order['order_ref'] }}</span>
                        @endif
                        <span class="text-[11px] text-gray-400 ml-auto flex items-center gap-1 whitespace-nowrap">
                            <i class="fa-regular fa-clock text-[10px]"></i>
                            {{ $order['created_at']->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    {{-- Produtos --}}
                    @if($isMultiItem)
                        <div class="space-y-0.5">
                            @foreach($order['items'] as $item)
                                <p class="text-xs text-gray-700 truncate">
                                    <span class="font-semibold">{{ $item->quantity }}x</span> {{ $item->product->name }}
                                    <span class="text-gray-400">— R$ {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}</span>
                                </p>
                            @endforeach
                        </div>
                    @else
                        <p class="font-bold text-gray-800 text-sm leading-tight">{{ $firstRequest->product->name }}</p>
                    @endif

                    {{-- Cliente + total --}}
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex flex-col gap-0.5 min-w-0">
                            <span class="text-xs text-gray-600 flex items-center gap-1 truncate">
                                <i class="fa-regular fa-user text-gray-400 text-[10px] flex-shrink-0"></i>
                                <span class="font-semibold truncate">{{ $order['client']->name ?? '—' }}</span>
                            </span>
                            @if($order['client']->phone ?? false)
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <i class="fa-solid fa-phone text-[10px] flex-shrink-0"></i>
                                    {{ $order['client']->phone }}
                                </span>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-base font-extrabold text-blue-700">R$ {{ number_format($order['total'], 2, ',', '.') }}</p>
                            <p class="text-[11px] text-gray-400">{{ $order['qty_items'] }} {{ $order['qty_items'] == 1 ? 'item' : 'itens' }}</p>
                        </div>
                    </div>

                    {{-- Ações --}}
                    <div class="flex gap-2 flex-wrap mt-0.5">
                        {{-- Detalhes abre drawer do primeiro request --}}
                        <button class="open-drawer flex items-center gap-1 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition"
                                data-id="{{ $firstRequest->id }}">
                            <i class="fa-solid fa-eye text-[10px]"></i> Detalhes
                        </button>

                        @php $clientPhone = preg_replace('/\D/', '', $order['client']->phone ?? ''); @endphp
                        @php $waMsg = 'Olá ' . ($order['client']->name ?? '') . ', sou da loja ' . $order['store']->store_name . ' e estou entrando em contato sobre seu pedido.'; @endphp

                        @if($order['status'] == 'in_open')
                            <a href="https://api.whatsapp.com/send/?phone=55{{ $clientPhone }}&text={{ urlencode($waMsg) }}&app_absent=0"
                               target="_blank" class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-2.5 py-1.5 rounded-lg transition">
                                <i class="fa-brands fa-whatsapp"></i> WhatsApp
                            </a>
                            {{-- Iniciar todos os itens do pedido --}}
                            <button
                                data-orderitems="{{ $order['items']->pluck('id')->join(',') }}"
                                data-clientname="{{ $order['client']->name ?? '' }}"
                                data-clientphone="{{ $clientPhone }}"
                                data-storename="{{ $order['store']->store_name }}"
                                class="btnInitOrder flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-2.5 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-play text-[10px]"></i> Iniciar
                            </button>
                        @endif

                        @if($order['status'] == 'in_progress')
                            <a href="https://api.whatsapp.com/send/?phone=55{{ $clientPhone }}&text={{ urlencode($waMsg) }}&app_absent=0"
                               target="_blank" class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-2.5 py-1.5 rounded-lg transition">
                                <i class="fa-brands fa-whatsapp"></i> WhatsApp
                            </a>
                            @foreach($order['items'] as $item)
                                @if($item->status == 'in_progress')
                                <button
                                    data-requestidsold="{{ $item->id }}"
                                    data-productidsold="{{ $item->product->id }}"
                                    data-productname="{{ $item->product->name }}"
                                    data-clientname="{{ $order['client']->name ?? '' }}"
                                    onclick="showSoldConfirmationModal(event)"
                                    class="flex items-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-2.5 py-1.5 rounded-lg transition">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                    {{ $isMultiItem ? 'Vender: ' . Str::limit($item->product->name, 15) : 'Finalizar venda' }}
                                </button>
                                @endif
                            @endforeach
                        @endif

                        @if($order['status'] == 'sold')
                            <span class="flex items-center gap-1 text-emerald-600 text-xs font-bold px-2.5 py-1.5 bg-emerald-50 rounded-lg border border-emerald-200">
                                <i class="fa-solid fa-circle-check"></i> Venda concluída
                            </span>
                        @endif
                        @if($order['status'] == 'canceled')
                            <span class="flex items-center gap-1 text-red-500 text-xs font-bold px-2.5 py-1.5 bg-red-50 rounded-lg border border-red-200">
                                <i class="fa-solid fa-ban"></i> Cancelado
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if($order['status'] == 'in_open')
            <div class="bg-amber-50 border-t border-amber-100 px-4 py-2 flex items-center gap-2">
                <i class="fa-solid fa-robot text-amber-500 text-xs"></i>
                <span class="text-xs text-amber-700 font-medium">Agente IA disponível em breve — este pedido poderá ser tratado automaticamente via WhatsApp.</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

</div>
</div>

{{-- Drawer de detalhes (mantido para pedidos individuais) --}}
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 z-40 hidden" onclick="closeDrawer()"></div>
<div id="orderDrawer" class="fixed top-0 right-0 h-full w-full max-w-lg bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
        <div>
            <h2 class="font-bold text-gray-900 text-lg">Detalhes do Pedido</h2>
            <p id="drawerOrderId" class="text-xs text-gray-400 mt-0.5"></p>
        </div>
        <button onclick="closeDrawer()" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5">
        <div id="drawerBadges" class="flex flex-wrap gap-2"></div>
        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Produto</p>
            <div class="flex gap-4 items-start">
                <div id="drawerProductImg" class="w-20 h-20 rounded-xl overflow-hidden bg-gray-200 flex-shrink-0 border border-gray-100"></div>
                <div class="flex-1 min-w-0">
                    <p id="drawerProductName" class="font-bold text-gray-800 text-base"></p>
                    <p id="drawerProductBrand" class="text-xs text-gray-500 mt-0.5"></p>
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="bg-white rounded-xl p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Preço unitário</p>
                            <p id="drawerProductPrice" class="font-extrabold text-blue-700 text-base mt-0.5"></p>
                        </div>
                        <div class="bg-white rounded-xl p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Quantidade</p>
                            <p id="drawerProductQty" class="font-extrabold text-gray-800 text-base mt-0.5"></p>
                        </div>
                        <div class="bg-white rounded-xl p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Subtotal</p>
                            <p id="drawerSubtotal" class="font-extrabold text-gray-800 text-base mt-0.5"></p>
                        </div>
                        <div class="bg-white rounded-xl p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Estoque atual</p>
                            <p id="drawerProductStock" class="font-bold text-gray-700 text-sm mt-0.5"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Cliente</p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2"><i class="fa-regular fa-user text-gray-400 w-4 text-center"></i><span id="drawerClientName" class="font-semibold text-gray-800"></span></div>
                <div class="flex items-center gap-2"><i class="fa-solid fa-phone text-gray-400 w-4 text-center text-xs"></i><span id="drawerClientPhone" class="text-gray-600"></span></div>
                <div class="flex items-center gap-2"><i class="fa-solid fa-envelope text-gray-400 w-4 text-center text-xs"></i><span id="drawerClientEmail" class="text-gray-600"></span></div>
            </div>
        </div>
        <div class="bg-gray-50 rounded-2xl p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Mensagem do cliente</p>
            <p id="drawerMessage" class="text-sm text-gray-600 italic leading-relaxed"></p>
        </div>
        <div class="bg-gray-50 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Entrega</p>
                <span class="text-[10px] bg-orange-100 text-orange-600 font-bold px-2 py-0.5 rounded-full border border-orange-200">Em breve</span>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed">As informações de entrega serão coletadas no catálogo em breve. Confirme com o cliente via WhatsApp.</p>
        </div>
    </div>
    <div id="drawerFooter" class="flex-shrink-0 px-6 py-4 border-t border-gray-100 flex gap-3"></div>
</div>

{{-- Dados JSON para o drawer --}}
<script>
const ordersData = {
    @foreach($allRequests as $request)
    "{{ $request->id }}": {
        id: {{ $request->id }},
        status: "{{ $request->status }}",
        statusLabel: "{{ $request->statusLabel() }}",
        createdAt: "{{ $request->created_at->format('d/m/Y \à\s H:i') }}",
        orderRef: @json($request->order_ref),
        quantity: {{ $request->quantity ?? 1 }},
        shift: {{ $request->shift }},
        finance: {{ $request->finance }},
        message: @json($request->message ?? ''),
        product: {
            id: {{ $request->product->id }},
            name: @json($request->product->name),
            price: {{ $request->product->price }},
            stock: {{ $request->product->stock ?? 0 }},
            brand: @json($request->product->brand->name ?? null),
            color: @json($request->product->color ?? null),
            image: "{{ $request->product->images->isNotEmpty() ? asset('storage/' . $request->product->images->first()->url) : '' }}",
        },
        client: {
            name: @json($request->client->name ?? '—'),
            phone: @json($request->client->phone ?? '—'),
            email: @json($request->client->email ?? null),
        },
        store: { name: @json($request->store->store_name) }
    },
    @endforeach
};
</script>

{{-- Modal confirmação de venda --}}
<div id="soldConfirmationModalProduct" class="hidden fixed z-50 inset-0 flex items-center justify-center px-4" aria-modal="true">
    <div class="fixed inset-0 bg-black/40" onclick="document.getElementById('soldConfirmationModalProduct').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
        <button onclick="document.getElementById('soldConfirmationModalProduct').classList.add('hidden')" class="absolute top-4 right-4 p-1.5 rounded-lg hover:bg-gray-100 transition text-gray-400"><i class="fa-solid fa-xmark"></i></button>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="fa-solid fa-bag-shopping"></i></div>
            <div><h3 class="font-bold text-gray-800 text-base div-product-name"></h3><p class="text-xs text-gray-500 div-client-name"></p></div>
        </div>
        <p class="text-sm text-gray-600 mb-5">Confirme o resultado desta venda. O estoque será atualizado automaticamente.</p>
        <div class="flex gap-3">
            <button onclick="cancelSale()" class="flex-1 flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-xl transition text-sm border border-red-200"><i class="fa-solid fa-ban text-xs"></i> Cancelar</button>
            <button onclick="confirmSale()" class="flex-1 flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-xl transition text-sm"><i class="fa-solid fa-check text-xs"></i> Confirmar</button>
        </div>
    </div>
</div>

@endsection
</x-app-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
// Filter tabs
document.querySelectorAll('.filter-tab').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-tab').forEach(b => {
            b.classList.remove('bg-gray-800','text-white','active-tab','border-transparent');
            b.classList.add('bg-white','text-gray-600','border-gray-200');
        });
        this.classList.add('bg-gray-800','text-white','active-tab','border-transparent');
        this.classList.remove('bg-white','text-gray-600','border-gray-200');
        const filter = this.dataset.filter;
        document.querySelectorAll('.request-card').forEach(card => {
            card.style.display = (filter === 'all' || card.dataset.status === filter) ? '' : 'none';
        });
    });
});

// Drawer
function openDrawer(id) {
    const o = ordersData[id]; if (!o) return;
    const statusColors = { in_open:'bg-amber-100 text-amber-700 border-amber-200', in_progress:'bg-blue-100 text-blue-700 border-blue-200', sold:'bg-emerald-100 text-emerald-700 border-emerald-200', canceled:'bg-red-100 text-red-600 border-red-200' };
    const dotColors    = { in_open:'bg-amber-400', in_progress:'bg-blue-500', sold:'bg-emerald-500', canceled:'bg-red-400' };
    document.getElementById('drawerOrderId').textContent = `Pedido #${o.id}${o.orderRef ? ' · ' + o.orderRef : ''} · ${o.createdAt}`;
    const bc = statusColors[o.status] || 'bg-gray-100 text-gray-500 border-gray-200';
    const dc = dotColors[o.status] || 'bg-gray-300';
    let badges = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border ${bc}"><span class="w-1.5 h-1.5 rounded-full ${dc}"></span>${o.statusLabel}</span>`;
    if (o.shift)   badges += `<span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700 border border-sky-200">Negociação</span>`;
    if (o.finance) badges += `<span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">Financiamento</span>`;
    document.getElementById('drawerBadges').innerHTML = badges;
    document.getElementById('drawerProductImg').innerHTML = o.product.image ? `<img src="${o.product.image}" class="w-full h-full object-cover">` : `<div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-solid fa-image text-2xl"></i></div>`;
    document.getElementById('drawerProductName').textContent = o.product.name;
    document.getElementById('drawerProductBrand').textContent = [o.product.brand, o.product.color].filter(Boolean).join(' · ') || '—';
    document.getElementById('drawerProductPrice').textContent = 'R$ ' + o.product.price.toLocaleString('pt-BR', {minimumFractionDigits:2});
    document.getElementById('drawerProductQty').textContent = o.quantity + ' un.';
    document.getElementById('drawerSubtotal').textContent = 'R$ ' + (o.product.price * o.quantity).toLocaleString('pt-BR', {minimumFractionDigits:2});
    document.getElementById('drawerProductStock').textContent = o.product.stock > 0 ? `${o.product.stock} un.` : 'Sem estoque';
    document.getElementById('drawerClientName').textContent  = o.client.name;
    document.getElementById('drawerClientPhone').textContent = o.client.phone;
    document.getElementById('drawerClientEmail').textContent = o.client.email || 'Não informado';
    document.getElementById('drawerMessage').textContent = o.message || 'Nenhuma mensagem.';
    const waUrl = `https://api.whatsapp.com/send/?phone=55${o.client.phone.replace(/\D/g,'')}&text=${encodeURIComponent(`Olá ${o.client.name}, sou da loja ${o.store.name} e estou entrando em contato sobre seu pedido do produto ${o.product.name}.`)}&app_absent=0`;
    let footer = `<a href="${waUrl}" target="_blank" class="flex-1 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition text-sm"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>`;
    if (o.status === 'in_open')     footer += `<button onclick="initFromDrawer(${o.id})" class="flex-1 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm"><i class="fa-solid fa-play text-xs"></i> Iniciar</button>`;
    if (o.status === 'in_progress') footer += `<button onclick="openSoldFromDrawer(${o.id})" class="flex-1 flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl transition text-sm"><i class="fa-solid fa-check text-xs"></i> Finalizar</button>`;
    document.getElementById('drawerFooter').innerHTML = footer;
    document.getElementById('drawerOverlay').classList.remove('hidden');
    document.getElementById('orderDrawer').classList.remove('translate-x-full');
    document.body.style.overflow = 'hidden';
}
function closeDrawer() { document.getElementById('drawerOverlay').classList.add('hidden'); document.getElementById('orderDrawer').classList.add('translate-x-full'); document.body.style.overflow = ''; }
document.querySelectorAll('.open-drawer').forEach(el => el.addEventListener('click', function(e) { e.stopPropagation(); openDrawer(this.dataset.id); }));

// Init order (múltiplos requests)
$(document).on('click', '.btnInitOrder', function(e) {
    e.preventDefault();
    const ids = $(this).data('orderitems').toString().split(',');
    const clientName  = $(this).data('clientname');
    const clientPhone = $(this).data('clientphone');
    const storeName   = $(this).data('storename');
    showLoader();
    const calls = ids.map(id => $.ajax({ url: '/requests/init', type: 'POST', data: { requestId: id } }));
    $.when(...calls).always(function() {
        hideLoader();
        const url = `https://api.whatsapp.com/send/?phone=55${clientPhone.replace(/\D/g,'')}&text=${encodeURIComponent(`Olá ${clientName}, sou da loja ${storeName} e vi que você fez um pedido. Gostaria de mais informações?`)}&app_absent=0`;
        window.open(url, '_blank');
        setTimeout(() => window.location.reload(), 2000);
    });
});

function initFromDrawer(id) {
    const o = ordersData[id]; closeDrawer(); showLoader();
    $.ajax({ url: '/requests/init', type: 'POST', data: { requestId: id }, complete: function() {
        hideLoader();
        window.open(`https://api.whatsapp.com/send/?phone=55${o.client.phone.replace(/\D/g,'')}&text=${encodeURIComponent(`Olá ${o.client.name}, sou da loja ${o.store.name} e estou entrando em contato sobre seu pedido.`)}&app_absent=0`, '_blank');
        setTimeout(() => window.location.reload(), 2000);
    }});
}
function openSoldFromDrawer(id) {
    const o = ordersData[id]; closeDrawer();
    const modal = document.getElementById('soldConfirmationModalProduct');
    modal.dataset.productId = o.product.id; modal.dataset.requestId = o.id;
    document.querySelector('.div-product-name').innerText = o.product.name;
    document.querySelector('.div-client-name').innerText  = 'Cliente: ' + o.client.name;
    modal.classList.remove('hidden');
}
function showSoldConfirmationModal(event) {
    const btn = event.currentTarget, modal = document.getElementById('soldConfirmationModalProduct');
    modal.dataset.productId = btn.dataset.productidsold; modal.dataset.requestId = btn.dataset.requestidsold;
    document.querySelector('.div-product-name').innerText = btn.dataset.productname;
    document.querySelector('.div-client-name').innerText  = 'Cliente: ' + btn.dataset.clientname;
    modal.classList.remove('hidden');
}
function confirmSale() {
    const modal = document.getElementById('soldConfirmationModalProduct'); showLoader();
    $.ajax({ url: '/requests/sold', type: 'POST', data: { productId: modal.dataset.productId, requestId: modal.dataset.requestId },
        success: function() { hideLoader(); modal.classList.add('hidden'); window.location.reload(); },
        error: function() { hideLoader(); }
    });
}
function cancelSale() {
    const modal = document.getElementById('soldConfirmationModalProduct'); showLoader();
    $.ajax({ url: '/requests/unsold', type: 'POST', data: { requestId: modal.dataset.requestId },
        success: function() { hideLoader(); modal.classList.add('hidden'); window.location.reload(); },
        error: function() { hideLoader(); }
    });
}
</script>
