<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1e3a5f">
    <title>{{ $store->store_name }} — Catálogo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-blue: #1e3a5f;
            --brand-blue-light: #2563eb;
            --brand-accent: #f59e0b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            font-family: 'Montserrat', sans-serif;
        }

        /* ── Hero ────────────────────────────── */
        .catalog-hero__banner-placeholder {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #0ea5e9 100%);
        }

        /* ── Category Pills ──────────────────── */
        .div-categoria {
            transition: all 0.2s ease;
        }

        .div-categoria.active-category {
            background-color: #1e3a5f !important;
            color: white !important;
            border-color: #1e3a5f !important;
            box-shadow: 0 4px 14px rgba(30, 58, 95, 0.35);
        }

        /* ── Scrollbar hidden ────────────────── */
        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ── Product Card ────────────────────── */
        .catalog-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            display: block;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .catalog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(30, 58, 95, 0.15);
        }

        .catalog-card__image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            object-position: center;
            display: block;
            background: #e2e8f0;
        }

        @media (min-width: 768px) {
            .catalog-card__image {
                height: 190px;
            }
        }

        .catalog-card__body {
            padding: 10px 12px 14px;
        }

        .catalog-card__price-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 1px;
        }

        .catalog-card__price {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1e3a5f;
            line-height: 1.1;
        }

        .catalog-card__name {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 6px;
            line-height: 1.35;

            /* clamp to 2 lines */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .catalog-card__km {
            font-size: 0.72rem;
            color: #94a3b8;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .catalog-card__badges {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 8px;
        }

        .catalog-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .catalog-badge--avista {
            background: #e0f2fe;
            color: #0284c7;
        }

        .catalog-badge--financing {
            background: #eff6ff;
            color: #2563eb;
        }

        .catalog-badge--consortium {
            background: #1e3a5f;
            color: white;
        }

        /* ── Skeleton ────────────────────────── */
        .skeleton {
            border-radius: 16px;
            overflow: hidden;
            background: white;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .skeleton-pulse {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.4s infinite;
        }

        @keyframes skeleton-shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* ── Empty state ─────────────────────── */
        .catalog-empty {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: #94a3b8;
            text-align: center;
        }

        .catalog-empty svg {
            width: 56px;
            height: 56px;
            margin-bottom: 12px;
            opacity: 0.4;
        }

        /* ── Search bar ──────────────────────── */
        #catalog-search-input:focus {
            outline: none;
        }

        /* ── Sticky filter bar ───────────────── */
        .catalog-filters {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(241, 245, 249, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* ── Desktop sidebar ─────────────────── */
        @media (min-width: 1024px) {
            .catalog-layout {
                display: grid;
                grid-template-columns: 280px 1fr;
                gap: 24px;
                align-items: start;
            }

            .catalog-sidebar {
                position: sticky;
                top: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
        }

        .catalog-sidebar-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .catalog-sidebar-card h2 {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        /* ── Product count chip ──────────────── */
        .catalog-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
        }

        .catalog-count strong {
            font-weight: 800;
            color: #1e3a5f;
        }
    </style>
</head>

<body>

    {{-- ════════════════════════════════════════════
     HERO DA LOJA
═══════════════════════════════════════════════ --}}
    <x-catalog.catalog-hero :bannerStore="$bannerStore" :logoStore="$logoStore" :store="$store" :itsOpen="$itsOpen" />

    {{-- ════════════════════════════════════════════
     FILTROS FIXOS (busca + categorias)
═══════════════════════════════════════════════ --}}
    <div class="catalog-filters px-4 py-3 mt-4 md:px-8">
        <x-catalog.catalog-search placeholder="Busque por marca, modelo ou nome..." />

        @if (count($categories) > 0)
            <div class="mt-3">
                <x-catalog.catalog-category-pills :categories="$categories" />
            </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════
     CORPO PRINCIPAL (sidebar desktop + grid)
═══════════════════════════════════════════════ --}}
    <div class="px-4 md:px-8 py-4 pb-16 max-w-screen-2xl mx-auto">
        <div class="catalog-layout">

            {{-- ── Sidebar (desktop somente) ──────── --}}
            <aside class="catalog-sidebar hidden lg:flex">
                {{-- Contato --}}
                <div class="catalog-sidebar-card">
                    <h2>Contato</h2>
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $store->store_email }}
                        </div>
                        @if ($store->store_phone)
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="mask-phone">{{ $store->store_phone }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Horários --}}
                <div class="catalog-sidebar-card">
                    <h2>Horários</h2>
                    <div class="flex flex-col gap-1 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Seg – Sex</span>
                            <span class="font-semibold text-gray-800">08:00 – 18:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sábado</span>
                            <span class="font-semibold text-gray-800">08:00 – 13:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Domingo</span>
                            <span class="font-semibold text-red-400">Fechado</span>
                        </div>
                    </div>
                </div>

                {{-- Mapa --}}
                <div class="catalog-sidebar-card">
                    <h2>Localização</h2>
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($store->store_address ?? 'Fortaleza, CE') }}&output=embed"
                        width="100%" height="180" class="rounded-xl" style="border:0" loading="lazy"
                        allowfullscreen="">
                    </iframe>
                </div>
            </aside>

            {{-- ── Main content ─────────────────── --}}
            <main>
                {{-- Contagem de produtos --}}
                <div class="flex items-center justify-between mb-4">
                    <span class="catalog-count" id="catalog-count-label">
                        <strong id="catalog-count-number">{{ $qtdProducts }}</strong>
                        produtos encontrados
                    </span>
                </div>

                {{-- Grid de produtos --}}
                <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3">

                    {{-- Skeleton loader ──────────────── --}}
                    @for ($i = 0; $i < 8; $i++)
                        <div class="skeleton" id="skeleton-{{ $i }}">
                            <div class="skeleton-pulse w-full h-40 md:h-48"></div>
                            <div class="p-3 flex flex-col gap-2">
                                <div class="skeleton-pulse h-3 w-1/3 rounded-full"></div>
                                <div class="skeleton-pulse h-5 w-2/3 rounded-full"></div>
                                <div class="skeleton-pulse h-3 w-full rounded-full"></div>
                                <div class="skeleton-pulse h-3 w-4/5 rounded-full"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </main>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════════════ --}}
    <script>
        const partnerLink = window.location.href.split('/')[4];

        function formatPrice(price) {
            return price.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                })
                .replace('R$', '').trim();
        }

        function buildProductCard(product) {
            console.log(product, 'teste')
            const imgSrc = (product.image_main) ?
                `/storage/${product.image_main.replace('public/', '')}` :
                '/img/image-not-found.png';

            const price = parseFloat(product.price);
            const oldPrice = parseFloat(product.old_price);
            const hasDiscount = oldPrice && price < oldPrice;

            const badgeAvista = product.in_sight == 1 ? `<span class="catalog-badge catalog-badge--avista">À vista</span>` :
                '';
            const badgeFinancing = product.financing == 1 ?
                `<span class="catalog-badge catalog-badge--financing">Financiamento</span>` : '';
            const badgeConsortium = product.consortium == 1 ?
                `<span class="catalog-badge catalog-badge--consortium">Consórcio</span>` : '';
            const hasBadges = badgeAvista || badgeFinancing || badgeConsortium;

            const kmBlock = product.stock != null ?
                `<div class="catalog-card__km">
                ${product.stock.toLocaleString('pt-BR')} em estoque
               </div>` :
                '';

            const oldPriceBlock = hasDiscount ?
                `<div class="text-xs text-gray-400 line-through leading-none">R$ ${formatPrice(oldPrice)}</div>` :
                '';

            return `
            <a href="/orders/${partnerLink}/product/${product.id}" class="catalog-card">
                <img class="catalog-card__image" src="${imgSrc}" alt="${product.name}"
                     onerror="this.src='/img/image-not-found.png'">
                <div class="catalog-card__body">
                    <div class="catalog-card__price-label">Preço</div>
                    <div class="catalog-card__price">R$ ${formatPrice(price)}</div>
                    ${oldPriceBlock}
                    <p class="catalog-card__name">${product.name}</p>
                    ${kmBlock}
                    ${hasBadges ? `<div class="catalog-card__badges">${badgeAvista}${badgeFinancing}${badgeConsortium}</div>` : ''}
                </div>
            </a>
        `;
        }

        function renderProducts(data) {
            const grid = document.getElementById('product-grid');

            if (!data || data.length === 0) {
                grid.innerHTML = `
                <div class="catalog-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01
                                 M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold text-lg">Nenhum produto encontrado</p>
                    <p class="text-sm mt-1">Tente outra categoria ou termo de busca.</p>
                </div>
            `;
                updateCount(0);
                return;
            }

            grid.innerHTML = data.map(p => buildProductCard(p)).join('');
            updateCount(data.length);
        }

        function updateCount(n) {
            const num = document.getElementById('catalog-count-number');
            if (num) num.textContent = n;
        }

        function getAllProducts() {
            $.ajax({
                type: 'GET',
                url: '/get-products-by-partner',
                success: data => {
                    data.forEach(p => {
                        p.price = parseFloat(p.price);
                        p.old_price = parseFloat(p.old_price);
                    });
                    renderProducts(data);
                }
            });
        }

        function getProductsByCategory(categoryId) {
            $.ajax({
                type: 'GET',
                url: '/orders/get-products-by-category/' + categoryId,
                success: data => {
                    data.forEach(p => {
                        p.price = parseFloat(p.price);
                        p.old_price = parseFloat(p.old_price);
                    });
                    renderProducts(data);
                }
            });
        }

        // ── Category pill click ───────────────────
        $(document).on('click', '.div-categoria', function() {
            $('.div-categoria').removeClass('active-category')
                .addClass('text-gray-600 bg-white border-gray-200')
                .removeClass('text-white bg-blue-600 border-blue-600');

            $(this).addClass('active-category')
                .removeClass('text-gray-600 bg-white border-gray-200');

            const catId = $(this).data('categoryid');
            if (catId === 'todos') {
                getAllProducts();
            } else {
                getProductsByCategory(catId);
            }
        });

        // ── Search ────────────────────────────────
        let searchTimeout;
        $('#catalog-search-input').on('input', function() {
            clearTimeout(searchTimeout);
            const val = $(this).val().toLowerCase().trim();

            searchTimeout = setTimeout(() => {
                if (val.length === 0) {
                    getAllProducts();
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: '/catalog/search?search=' + encodeURIComponent(val),
                    success: data => {
                        const list = data.data || data;
                        list.forEach(p => {
                            p.price = parseFloat(p.price);
                            p.old_price = parseFloat(p.old_price);
                        });
                        renderProducts(list);
                    }
                });
            }, 320);
        });

        // ── Initial load ──────────────────────────
        $(document).ready(function() {
            $('[data-mask="phone"]').mask('(00) 00000-0000');
            getAllProducts();
        });
    </script>

</body>

</html>
