<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carrinho | {{ $partner->store->store_name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f8fafc; }
        .qty-btn { width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: #f1f5f9; font-size: 18px; cursor: pointer; user-select: none; transition: background .15s; }
        .qty-btn:hover { background: #e2e8f0; }
    </style>
</head>
<body>

<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-sm h-16 flex items-center px-4 md:px-8 justify-between">
    <div class="flex items-center gap-3">
        @if($logoStore)
            <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-gray-100">
                <img src="{{ $logoStore }}" class="w-full h-full object-cover" alt="Logo">
            </div>
        @endif
        <span class="font-bold text-gray-800 text-base">{{ $partner->store->store_name }}</span>
    </div>

    <a href="{{ route('catalog.index', $partner->partner_link) }}" class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Continuar comprando
    </a>
</header>

<main class="pt-24 pb-16 px-4 md:px-8 max-w-5xl mx-auto">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-8">Seu carrinho</h1>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">

        <!-- Items -->
        <div class="lg:col-span-2 space-y-4" id="cartItemsList">
            <!-- Populated by JS -->
        </div>

        <!-- Empty state -->
        <div id="cartEmptyState" class="lg:col-span-2 hidden">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 flex flex-col items-center text-center">
                <svg class="w-20 h-20 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
                <p class="text-gray-500 font-semibold text-lg">Seu carrinho está vazio</p>
                <p class="text-gray-400 text-sm mt-1 mb-6">Explore nosso catálogo e adicione produtos</p>
                <a href="{{ route('catalog.index', $partner->partner_link) }}"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-bold px-6 py-3 rounded-xl transition text-sm">
                    Ver catálogo
                </a>
            </div>
        </div>

        <!-- Summary -->
        <div id="cartSummary" class="hidden mt-6 lg:mt-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <h2 class="text-base font-bold text-gray-800 mb-5">Resumo do pedido</h2>

                <div class="space-y-3 text-sm text-gray-600 mb-5">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="summarySubtotal" class="font-semibold text-gray-800">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Itens</span>
                        <span id="summaryQty" class="font-semibold text-gray-800">0</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4 mb-5 flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total</span>
                    <span id="summaryTotal" class="text-2xl font-extrabold text-blue-700">R$ 0,00</span>
                </div>

                <!-- Contact info -->
                <div class="space-y-3 mb-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Seu nome <span class="text-red-500">*</span></label>
                        <input type="text" id="cartName" placeholder="Nome completo"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <p id="errCartName" class="hidden text-xs text-red-500 mt-1">Campo obrigatório</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Telefone <span class="text-red-500">*</span></label>
                        <input type="text" id="cartPhone" placeholder="(00) 00000-0000"
                            class="phone-mask w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <p id="errCartPhone" class="hidden text-xs text-red-500 mt-1">Campo obrigatório</p>
                    </div>
                </div>

                <button id="btnWhatsapp"
                    class="w-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Finalizar via WhatsApp
                </button>

                <button id="btnClearCart" class="w-full mt-3 text-sm text-gray-400 hover:text-red-500 transition py-2">
                    Limpar carrinho
                </button>
            </div>
        </div>

    </div>
</main>

<script>
    $(document).ready(function() {
        $('.phone-mask').mask('(00) 00000-0000');
    });

    const STORE_KEY = 'pdp_cart_{{ $partner->store->id }}';
    let cart = JSON.parse(localStorage.getItem(STORE_KEY) || '[]');

    function saveCart() { localStorage.setItem(STORE_KEY, JSON.stringify(cart)); }

    function formatPrice(val) {
        return val.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function renderCart() {
        const list = $('#cartItemsList');
        const empty = $('#cartEmptyState');
        const summary = $('#cartSummary');
        list.empty();

        if (cart.length === 0) {
            list.addClass('hidden');
            empty.removeClass('hidden');
            summary.addClass('hidden');
            return;
        }

        list.removeClass('hidden');
        empty.addClass('hidden');
        summary.removeClass('hidden');

        let total = 0;
        let totalQty = 0;

        cart.forEach((item, idx) => {
            const subtotal = item.price * item.qty;
            total += subtotal;
            totalQty += item.qty;

            list.append(`
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-4 items-center">
                    <img src="${item.image}" class="w-20 h-20 object-cover rounded-xl flex-shrink-0 bg-gray-50 border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm leading-tight">${item.name}</p>
                        <p class="text-blue-700 font-bold mt-1">R$ ${formatPrice(item.price)}</p>
                        <div class="flex items-center gap-2 mt-3">
                            <button onclick="changeQty(${idx}, -1)" class="qty-btn text-gray-600">−</button>
                            <span class="text-sm font-bold w-6 text-center">${item.qty}</span>
                            <button onclick="changeQty(${idx}, 1)" class="qty-btn text-gray-600">+</button>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-3 flex-shrink-0">
                        <p class="font-extrabold text-gray-900 text-base">R$ ${formatPrice(subtotal)}</p>
                        <button onclick="removeItem(${idx})" class="p-2 rounded-xl hover:bg-red-50 text-gray-300 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `);
        });

        $('#summarySubtotal').text('R$ ' + formatPrice(total));
        $('#summaryTotal').text('R$ ' + formatPrice(total));
        $('#summaryQty').text(totalQty + (totalQty === 1 ? ' item' : ' itens'));
    }

    function changeQty(idx, delta) {
        cart[idx].qty = Math.max(1, cart[idx].qty + delta);
        saveCart(); renderCart();
    }

    function removeItem(idx) {
        cart.splice(idx, 1);
        saveCart(); renderCart();
    }

    $('#btnClearCart').click(function() {
        cart = [];
        saveCart(); renderCart();
    });

    $('#btnWhatsapp').click(function() {
        const name = $('#cartName').val().trim();
        const phone = $('#cartPhone').val().trim();
        let valid = true;

        $('#errCartName').addClass('hidden');
        $('#errCartPhone').addClass('hidden');

        if (!name) { $('#errCartName').removeClass('hidden'); valid = false; }
        if (!phone) { $('#errCartPhone').removeClass('hidden'); valid = false; }
        if (!valid) return;

        let msg = `Olá! Sou *${name}* (${phone}) e gostaria de finalizar meu pedido:\n\n`;
        cart.forEach(i => { msg += `• ${i.name} x${i.qty} — R$ ${formatPrice(i.price * i.qty)}\n`; });
        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        msg += `\n*Total: R$ ${formatPrice(total)}*`;

        window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
    });

    renderCart();
</script>
</body>
</html>
