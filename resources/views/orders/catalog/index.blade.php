@php
    $addressStore = $store->addressStore;
    $addressLine = collect([
        $addressStore?->street,
        $addressStore?->number,
        $addressStore?->neighborhood,
    ])->filter()->implode(', ');
    $cityLine = collect([
        $addressStore?->city,
        $addressStore?->state,
    ])->filter()->implode(' - ');
    $zipCode = $addressStore?->zip_code;
    $fullAddress = collect([$addressLine, $cityLine, $zipCode])->filter()->implode(' | ');
    $mapQuery = $fullAddress !== '' ? $fullAddress : ($store->store_name ?: 'Brasil');

    $dayLabels = [
        1 => 'Segunda',
        2 => 'Terca',
        3 => 'Quarta',
        4 => 'Quinta',
        5 => 'Sexta',
        6 => 'Sabado',
        0 => 'Domingo',
    ];
    $dayOrder = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 0 => 7];
    $storeHours = collect($store->storeHours ?? [])
        ->sortBy(fn ($hour) => $dayOrder[$hour->day_of_week] ?? 99)
        ->values();

    $paymentMethodLabels = [
        'pix' => 'Pix',
        'cash' => 'Dinheiro',
        'credit_card' => 'Cartao de credito',
        'debit_card' => 'Cartao de debito',
        'boleto' => 'Boleto',
    ];
    $cardBrandLabels = [
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'elo' => 'Elo',
        'amex' => 'American Express',
        'hipercard' => 'Hipercard',
        'diners' => 'Diners Club',
    ];
    $acceptedPaymentMethods = collect($store->accepted_payment_methods ?? [])
        ->filter(fn ($method) => isset($paymentMethodLabels[$method]))
        ->values();
    $acceptedCardBrands = collect($store->accepted_card_brands ?? [])
        ->filter(fn ($brand) => isset($cardBrandLabels[$brand]))
        ->values();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1e3a5f">
    <title>{{ $store->store_name }} — Catálogo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-blue: #1e3a5f;
            --brand-blue-light: #2563eb;
            --brand-blue-dark: #10243a;
            --brand-blue-soft: rgba(37, 99, 235, 0.12);
            --brand-accent: #f59e0b;
            --surface: #f8fafc;
            --surface-strong: #eef4fb;
            --border-soft: rgba(148, 163, 184, 0.2);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #f1f5f9 100%);
            color: #0f172a;
            font-family: 'Montserrat', sans-serif;
        }

        .catalog-hero__banner-placeholder {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 60%, #0ea5e9 100%);
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .catalog-filters {
            position: sticky;
            top: 0;
            z-index: 30;
            padding-top: 14px;
            /* background: linear-gradient(180deg, rgba(248, 250, 252, 0.95) 0%, rgba(248, 250, 252, 0.82) 100%); */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .catalog-filter-shell {
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
        }

        .div-categoria {
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease, color 0.18s ease;
        }

        .div-categoria:hover {
            transform: translateY(-1px);
        }

        .div-categoria.active-category {
            background-color: #1e3a5f !important;
            color: #fff !important;
            border-color: #1e3a5f !important;
            box-shadow: 0 10px 20px rgba(30, 58, 95, 0.18);
        }

        @keyframes catalog-fade-up {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes skeleton-shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .catalog-reveal {
            animation: catalog-fade-up 0.45s ease both;
        }

        .catalog-layout {
            display: block;
        }

        @media (min-width: 1024px) {
            .catalog-layout {
                display: grid;
                grid-template-columns: 290px minmax(0, 1fr);
                gap: 24px;
                align-items: start;
            }

            .catalog-sidebar {
                position: sticky;
                top: 120px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
        }

        .catalog-sidebar-card,
        /* .catalog-panel {
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
        } */

        .catalog-sidebar-card {
            padding: 18px;
        }

        .catalog-sidebar-card h2,
        .catalog-panel__eyebrow {
            margin: 0 0 12px;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .catalog-stat-card {
            padding: 18px 20px;
            background: linear-gradient(135deg, rgba(30, 58, 95, 0.98), rgba(37, 99, 235, 0.96));
            color: #fff;
        }

        .catalog-stat-card strong {
            display: block;
            font-size: 2rem;
            line-height: 1;
            margin-top: 8px;
        }

        .catalog-highlights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .catalog-highlight {
            min-height: 100%;
            padding: 16px;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.75);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.92));
        }

        .catalog-highlight__icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: #1d4ed8;
            background: rgba(37, 99, 235, 0.12);
        }

        .catalog-highlight__title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .catalog-highlight__text {
            margin-top: 6px;
            font-size: 0.8rem;
            line-height: 1.5;
            color: #64748b;
        }

        .catalog-summary {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            margin-bottom: 18px;
        }

        .catalog-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #475569;
        }

        .catalog-count strong {
            font-weight: 800;
            color: #0f172a;
        }

        .catalog-active-filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            font-size: 0.76rem;
            font-weight: 700;
            color: #475569;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        @media (min-width: 768px) {
            .catalog-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .catalog-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .catalog-card {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            overflow: hidden;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.95);
            text-decoration: none;
            color: inherit;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }

        .catalog-card:hover {
            transform: translateY(-4px);
            border-color: rgba(59, 130, 246, 0.25);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
        }

        .catalog-card__media {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #f8fafc, #e2e8f0);
        }

        .catalog-card__image {
            display: block;
            width: 100%;
            height: 190px;
            object-fit: cover;
            object-position: center;
            transition: transform 0.35s ease;
        }

        .catalog-card:hover .catalog-card__image {
            transform: scale(1.03);
        }

        .catalog-card__gradient {
            position: absolute;
            inset: auto 0 0 0;
            height: 42%;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0), rgba(15, 23, 42, 0.35));
            pointer-events: none;
        }

        .catalog-card__sale-badge,
        .catalog-card__stock-badge {
            position: absolute;
            top: 12px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.18);
        }

        .catalog-card__sale-badge {
            left: 12px;
            color: #fff;
            background: linear-gradient(135deg, #111827, #f97316);
        }

        .catalog-card__stock-badge {
            right: 12px;
            color: #0f172a;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .catalog-card__body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 10px 12px 14px;
        }

        .catalog-card__brand {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .catalog-card__name {
            margin-top: 8px;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.35;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 1.35em;
        }

        .catalog-card__pricing {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 10px;
            margin-top: 12px;
        }

        .catalog-card__price-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .catalog-card__price {
            font-size: 1.42rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--brand-blue-dark);
        }

        .catalog-card__old-price {
            margin-top: 4px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .catalog-card__stock {
            margin-top: 12px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #64748b;
        }

        .catalog-card__badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 12px;
        }

        .catalog-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .catalog-badge--avista {
            color: #0f766e;
            background: #ccfbf1;
        }

        .catalog-badge--financing {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .catalog-badge--consortium {
            color: #fff;
            background: var(--brand-blue);
        }

        .catalog-card__cta {
            margin-top: auto;
            padding-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--brand-blue);
        }

        .catalog-card__cta span:last-child {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.22);
        }

        .skeleton {
            overflow: hidden;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
        }

        .skeleton-pulse {
            background: linear-gradient(90deg, #e2e8f0 25%, #f8fafc 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.4s infinite;
        }

        .catalog-empty {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 70px 24px;
            border-radius: 24px;
            border: 1px dashed rgba(148, 163, 184, 0.35);
            background: rgba(255, 255, 255, 0.86);
            color: #64748b;
            text-align: center;
        }

        .catalog-empty svg {
            width: 60px;
            height: 60px;
            margin-bottom: 14px;
            opacity: 0.45;
        }

        .catalog-pagination {
            margin-top: 22px;
        }

        .catalog-pagination__shell {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 16px;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        }

        .catalog-pagination__meta {
            font-size: 0.86rem;
            font-weight: 600;
            color: #64748b;
            text-align: center;
        }

        .catalog-pagination__meta strong {
            color: #0f172a;
        }

        .catalog-pagination__items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }

        .catalog-pagination__btn {
            min-width: 42px;
            height: 42px;
            padding: 0 14px;
            border: 1px solid rgba(203, 213, 225, 0.95);
            border-radius: 14px;
            background: #fff;
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
            transition: all 0.18s ease;
            cursor: pointer;
        }

        .catalog-pagination__btn:hover:not([disabled]) {
            border-color: rgba(59, 130, 246, 0.35);
            color: #1d4ed8;
            background: #eff6ff;
        }

        .catalog-pagination__btn[disabled] {
            cursor: not-allowed;
            opacity: 0.45;
        }

        .catalog-pagination__btn.is-active {
            border-color: transparent;
            color: #fff;
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            box-shadow: 0 12px 20px rgba(37, 99, 235, 0.2);
        }

        .catalog-store-extra {
            margin-top: 34px;
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 16px;
        }

        @media (min-width: 768px) {
            .catalog-store-extra {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .catalog-store-card {
            position: relative;
            overflow: hidden;
            padding: 24px;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
        }

        .catalog-store-card::after {
            content: '';
            position: absolute;
            inset: auto -40px -70px auto;
            width: 150px;
            height: 150px;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            pointer-events: none;
        }

        .catalog-store-card h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
        }

        .catalog-store-card p {
            margin: 10px 0 0;
            line-height: 1.65;
            font-size: 0.9rem;
            color: #64748b;
        }

        .catalog-chip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
        }

        .catalog-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(191, 219, 254, 0.95);
            background: rgba(239, 246, 255, 0.9);
            font-size: 0.78rem;
            font-weight: 800;
            color: #1d4ed8;
            letter-spacing: 0.02em;
        }

        .catalog-chip--card {
            color: #0f172a;
            background: #fff;
            border-color: rgba(203, 213, 225, 0.95);
        }

        .catalog-hours-list {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .catalog-hours-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            font-size: 0.86rem;
            color: #475569;
        }

        .catalog-hours-item strong {
            font-weight: 700;
            color: #0f172a;
        }

        .catalog-iframe {
            margin-top: 16px;
            border: 0;
            width: 100%;
            height: 170px;
            border-radius: 18px;
        }

        .catalog-footer {
            margin-top: 42px;
            border-top: 1px solid rgba(226, 232, 240, 0.95);
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.97), rgba(15, 23, 42, 1));
            color: rgba(255, 255, 255, 0.78);
        }

        .catalog-footer__inner {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 28px 0;
        }

        @media (min-width: 768px) {
            .catalog-footer__inner {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .catalog-footer__brand {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .catalog-footer__text {
            max-width: 640px;
            font-size: 0.86rem;
            line-height: 1.65;
        }

        @media (max-width: 1023px) {
            .catalog-highlights {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <x-catalog.catalog-hero :bannerStore="$bannerStore" :logoStore="$logoStore" :store="$store" :itsOpen="$itsOpen" />

    <div class="catalog-filters px-2 md:px-8">
        <div class="mx-auto max-w-screen-2xl">
            <div class="catalog-filter-shell rounded-[28px] px-3 py-4 md:px-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <x-catalog.catalog-search placeholder="Busque por produto, descricao ou marca..." />
                    </div>

                    <div class="hidden items-center gap-3 lg:flex">
                        <div class="rounded-full bg-slate-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-slate-500">
                            {{ count($categories) }} categorias
                        </div>
                        <div class="rounded-full bg-blue-50 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-blue-700">
                            vitrine online
                        </div>
                    </div>
                </div>

                @if (count($categories) > 0)
                    <div class="mt-4">
                        <x-catalog.catalog-category-pills :categories="$categories" />
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl px-4 py-5 pb-16 md:px-8">
        <div class="catalog-layout">
            <aside class="catalog-sidebar hidden lg:flex">
                <div class="catalog-sidebar-card catalog-stat-card catalog-reveal">
                    <span class="catalog-panel__eyebrow !mb-0 !text-blue-100/80">Selecao da loja</span>
                    <strong>{{ $qtdProducts }}</strong>
                    <p class="mt-3 text-sm leading-6 text-white/78">
                        Produtos ativos para o cliente explorar com busca, categorias e navegacao fluida.
                    </p>
                </div>

                <div class="catalog-sidebar-card catalog-reveal" style="animation-delay: 0.05s;">
                    <h2>Contato</h2>
                    <div class="flex flex-col gap-3 text-sm text-slate-600">
                        @if ($store->store_email)
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 rounded-full bg-blue-50 p-2 text-blue-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span>{{ $store->store_email }}</span>
                            </div>
                        @endif

                        @if ($store->store_phone)
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 rounded-full bg-blue-50 p-2 text-blue-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <span>{{ $store->store_phone }}</span>
                            </div>
                        @endif

                        @if ($fullAddress !== '')
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 rounded-full bg-blue-50 p-2 text-blue-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span>{{ $fullAddress }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="catalog-sidebar-card catalog-reveal" style="animation-delay: 0.1s;">
                    <h2>Horarios</h2>
                    <div class="catalog-hours-list">
                        @forelse ($storeHours as $hour)
                            <div class="catalog-hours-item">
                                <span>{{ $dayLabels[$hour->day_of_week] ?? 'Dia' }}</span>
                                <strong>
                                    {{ $hour->is_open ? substr((string) $hour->open_time, 0, 5).' - '.substr((string) $hour->close_time, 0, 5) : 'Fechado' }}
                                </strong>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">Horarios serao informados em breve.</div>
                        @endforelse
                    </div>
                </div>
            </aside>

            <main>
                {{-- <section class="catalog-highlights mb-5">
                    <article class="catalog-highlight catalog-reveal">
                        <div class="catalog-highlight__icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-3.314 0-6 2.239-6 5 0 1.46.75 2.773 1.947 3.686L7 20l3.093-1.547c.602.124 1.243.187 1.907.187 3.314 0 6-2.239 6-5s-2.686-5-6-5z" />
                            </svg>
                        </div>
                        <div class="catalog-highlight__title">Busca simples e rapida</div>
                        <p class="catalog-highlight__text">
                            Encontre produtos por nome, descricao ou marca sem perder o contexto da vitrine.
                        </p>
                    </article>

                    <article class="catalog-highlight catalog-reveal" style="animation-delay: 0.05s;">
                        <div class="catalog-highlight__icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h.01M11 15h2m-8 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="catalog-highlight__title">Pagamento com mais clareza</div>
                        <p class="catalog-highlight__text">
                            A loja pode destacar os meios de pagamento e bandeiras aceitas logo na vitrine.
                        </p>
                    </article>

                    <article class="catalog-highlight catalog-reveal" style="animation-delay: 0.1s;">
                        <div class="catalog-highlight__icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="catalog-highlight__title">Mais confianca para comprar</div>
                        <p class="catalog-highlight__text">
                            Informacoes da loja, endereco e horarios ficam visiveis para orientar o cliente do inicio ao fim.
                        </p>
                    </article>
                </section> --}}

                <section class="catalog-panel" style="animation-delay: 0.08s;">
                    <div class="catalog-summary">
                        <div class="catalog-count" id="catalog-count-label">
                            <strong id="catalog-count-number">{{ $qtdProducts }}</strong>
                            produtos encontrados
                        </div>

                        <div class="catalog-active-filter">
                            <span>Filtro ativo</span>
                            <strong id="catalog-active-filter-label">Todos os produtos</strong>
                        </div>
                    </div>

                    <div id="product-grid" class="catalog-grid">
                        @for ($i = 0; $i < 8; $i++)
                            <div class="skeleton">
                                <div class="skeleton-pulse h-[190px] w-full"></div>
                                <div class="space-y-3 p-4">
                                    <div class="skeleton-pulse h-3 w-1/3 rounded-full"></div>
                                    <div class="skeleton-pulse h-5 w-4/5 rounded-full"></div>
                                    <div class="skeleton-pulse h-4 w-2/3 rounded-full"></div>
                                    <div class="skeleton-pulse h-9 w-full rounded-2xl"></div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div id="catalog-pagination" class="catalog-pagination"></div>
                </section>

                <section class="catalog-store-extra">
                    @if ($fullAddress !== '')
                        <article class="catalog-store-card catalog-reveal">
                            <span class="catalog-panel__eyebrow">Endereco da loja</span>
                            <h3>{{ $store->store_name }}</h3>
                            <p>{{ $fullAddress }}</p>

                            <div class="catalog-hours-list">
                                @forelse ($storeHours->take(3) as $hour)
                                    <div class="catalog-hours-item">
                                        <span>{{ $dayLabels[$hour->day_of_week] ?? 'Dia' }}</span>
                                        <strong>
                                            {{ $hour->is_open ? substr((string) $hour->open_time, 0, 5).' - '.substr((string) $hour->close_time, 0, 5) : 'Fechado' }}
                                        </strong>
                                    </div>
                                @empty
                                    <div class="text-sm text-slate-500">Horarios serao adicionados em breve.</div>
                                @endforelse
                            </div>

                            <iframe class="catalog-iframe"
                                src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed"
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                title="Mapa da loja {{ $store->store_name }}"></iframe>
                        </article>
                    @endif

                    <article class="catalog-store-card catalog-reveal" style="animation-delay: 0.05s;">
                        <span class="catalog-panel__eyebrow">Pagamentos aceitos</span>
                        <h3>Escolha a melhor forma de pagar</h3>
                        <p>
                            {{ $acceptedPaymentMethods->isNotEmpty() ? 'A loja informa abaixo os meios de pagamento disponiveis para facilitar a decisao do cliente.' : 'Os meios de pagamento ainda nao foram configurados pela loja.' }}
                        </p>

                        <div class="catalog-chip-list">
                            @forelse ($acceptedPaymentMethods as $method)
                                <span class="catalog-chip">{{ $paymentMethodLabels[$method] }}</span>
                            @empty
                                <span class="catalog-chip">Consulte a loja</span>
                            @endforelse
                        </div>

                        <div class="catalog-chip-list">
                            @foreach ($acceptedCardBrands as $brand)
                                <span class="catalog-chip catalog-chip--card">{{ $cardBrandLabels[$brand] }}</span>
                            @endforeach
                        </div>
                    </article>
                </section>
            </main>
        </div>
    </div>

    <footer class="catalog-footer">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
            <div class="catalog-footer__inner">
                <div>
                    <div class="catalog-footer__brand">Vistoo</div>
                    <div class="mt-2 text-sm text-white/60">Vitrine digital para lojas modernas.</div>
                </div>

                <div class="catalog-footer__text">
                    O sistema Vistoo ajuda lojas a apresentar produtos, organizar catalogos e simplificar o contato com os clientes em uma experiencia visual mais clara, confiavel e intuitiva.
                </div>
            </div>
        </div>
    </footer>

    <script>
        const partnerLink = @json($partner->partner_link);
        const initialProductsCount = {{ $qtdProducts }};
        const catalogState = {
            page: 1,
            categoryId: 'todos',
            search: '',
        };
        let searchTimeout = null;
        let activeCatalogRequest = null;

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function formatPrice(price) {
            const numericPrice = Number(price || 0);

            return numericPrice.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).replace('R$', '').trim();
        }

        function buildSkeletonCard() {
            return `
                <div class="skeleton">
                    <div class="skeleton-pulse h-[190px] w-full"></div>
                    <div class="space-y-3 p-4">
                        <div class="skeleton-pulse h-3 w-1/3 rounded-full"></div>
                        <div class="skeleton-pulse h-5 w-4/5 rounded-full"></div>
                        <div class="skeleton-pulse h-4 w-2/3 rounded-full"></div>
                        <div class="skeleton-pulse h-9 w-full rounded-2xl"></div>
                    </div>
                </div>
            `;
        }

        function showSkeletons(quantity = 8) {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = Array.from({
                length: quantity
            }, buildSkeletonCard).join('');
        }

        function buildProductCard(product, index) {
            const imagePath = product.image_main ?
                `/storage/${String(product.image_main).replace('public/', '')}` :
                '/img/image-not-found.png';
            const price = Number(product.price || 0);
            const oldPrice = Number(product.old_price || 0);
            const hasDiscount = oldPrice > 0 && price > 0 && price < oldPrice;
            const discountPercentage = hasDiscount ? Math.round(((oldPrice - price) / oldPrice) * 100) : 0;
            const brandName = product.brand && product.brand.name ? product.brand.name : 'Colecao';
            const stock = Number(product.stock || 0);

            const paymentBadges = [
                product.in_sight == 1 ? '<span class="catalog-badge catalog-badge--avista">A vista</span>' : '',
                product.financing == 1 ? '<span class="catalog-badge catalog-badge--financing">Financiamento</span>' : '',
                product.consortium == 1 ? '<span class="catalog-badge catalog-badge--consortium">Consorcio</span>' : '',
            ].filter(Boolean).join('');

            return `
                <a href="/catalog/${encodeURIComponent(partnerLink)}/product/${product.id}" class="catalog-card catalog-reveal" style="animation-delay: ${Math.min(index * 0.04, 0.28)}s">
                    <div class="catalog-card__media">
                        ${hasDiscount ? `<span class="catalog-card__sale-badge">${discountPercentage}% OFF</span>` : ''}
                        <span class="catalog-card__stock-badge">${stock > 0 ? `${stock} un.` : 'Disponivel'}</span>
                        <img class="catalog-card__image" src="${imagePath}" alt="${escapeHtml(product.name)}" onerror="this.src='/img/image-not-found.png'">
                        <div class="catalog-card__gradient"></div>
                    </div>

                    <div class="catalog-card__body">
                        <span class="catalog-card__brand">${escapeHtml(brandName)}</span>
                        <div class="catalog-card__name">${escapeHtml(product.name)}</div>

                        <div class="catalog-card__pricing">
                            <div>
                                <span class="catalog-card__price-label">Preco</span>
                                <div class="catalog-card__price">R$ ${formatPrice(price)}</div>
                                ${hasDiscount ? `<div class="catalog-card__old-price">R$ ${formatPrice(oldPrice)}</div>` : ''}
                            </div>
                        </div>

                        <div class="catalog-card__stock">${stock > 0 ? `${stock.toLocaleString('pt-BR')} item(ns) em estoque` : 'Consulte disponibilidade'}</div>

                        ${paymentBadges ? `<div class="catalog-card__badges">${paymentBadges}</div>` : ''}

                        <div class="catalog-card__cta">
                            <span>Ver detalhes</span>
                            <span aria-hidden="true">+</span>
                        </div>
                    </div>
                </a>
            `;
        }

        function renderEmptyState() {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = `
                <div class="catalog-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-lg font-semibold text-slate-700">Nenhum produto encontrado</p>
                    <p class="mt-1 text-sm">Tente ajustar sua busca ou trocar a categoria selecionada.</p>
                </div>
            `;
        }

        function renderProducts(products) {
            const grid = document.getElementById('product-grid');

            if (!Array.isArray(products) || products.length === 0) {
                renderEmptyState();
                return;
            }

            grid.innerHTML = products.map((product, index) => buildProductCard(product, index)).join('');
        }

        function updateCount(total) {
            const countNumber = document.getElementById('catalog-count-number');
            if (countNumber) {
                countNumber.textContent = total;
            }
        }

        function updateFilterLabel() {
            const label = document.getElementById('catalog-active-filter-label');
            if (!label) {
                return;
            }

            const activeCategoryText = $('.div-categoria.active-category').text().trim();
            if (catalogState.search) {
                label.textContent = `Busca: ${catalogState.search}`;
                return;
            }

            if (catalogState.categoryId !== 'todos' && activeCategoryText) {
                label.textContent = activeCategoryText;
                return;
            }

            label.textContent = 'Todos os produtos';
        }

        function buildPaginationPages(currentPage, lastPage) {
            if (lastPage <= 7) {
                return Array.from({
                    length: lastPage
                }, (_, index) => index + 1);
            }

            const pages = [1];
            const from = Math.max(2, currentPage - 1);
            const to = Math.min(lastPage - 1, currentPage + 1);

            if (from > 2) {
                pages.push('...');
            }

            for (let page = from; page <= to; page += 1) {
                pages.push(page);
            }

            if (to < lastPage - 1) {
                pages.push('...');
            }

            pages.push(lastPage);

            return pages;
        }

        function renderPagination(meta) {
            const container = document.getElementById('catalog-pagination');

            if (!meta || meta.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            const pages = buildPaginationPages(meta.current_page, meta.last_page);

            container.innerHTML = `
                <div class="catalog-pagination__shell catalog-reveal">
                    <div class="catalog-pagination__meta">
                        Mostrando <strong>${meta.from ?? 0}</strong> a <strong>${meta.to ?? 0}</strong> de <strong>${meta.total ?? 0}</strong> produtos
                    </div>
                    <div class="catalog-pagination__items">
                        <button type="button" class="catalog-pagination__btn" data-page="${meta.current_page - 1}" ${meta.current_page <= 1 ? 'disabled' : ''}>
                            &lsaquo;
                        </button>
                        ${pages.map((page) => {
                            if (page === '...') {
                                return '<button type="button" class="catalog-pagination__btn" disabled>...</button>';
                            }

                            return `
                                <button type="button"
                                    class="catalog-pagination__btn ${page === meta.current_page ? 'is-active' : ''}"
                                    data-page="${page}">
                                    ${page}
                                </button>
                            `;
                        }).join('')}
                        <button type="button" class="catalog-pagination__btn" data-page="${meta.current_page + 1}" ${meta.current_page >= meta.last_page ? 'disabled' : ''}>
                            &rsaquo;
                        </button>
                    </div>
                </div>
            `;
        }

        function scrollProductsBelowFilters() {
            const panel = document.querySelector('.catalog-panel');
            const filters = document.querySelector('.catalog-filters');

            if (!panel) {
                return;
            }

            const filtersHeight = filters ? filters.offsetHeight : 0;
            const targetTop = panel.getBoundingClientRect().top + window.scrollY - filtersHeight - 12;

            window.scrollTo({
                top: Math.max(targetTop, 0),
                behavior: 'smooth'
            });
        }

        function buildCatalogUrl(page = 1) {
            const params = new URLSearchParams();
            params.set('partner_link', partnerLink);
            params.set('page', String(page));

            if (catalogState.search) {
                params.set('search', catalogState.search);
            }

            if (catalogState.categoryId && catalogState.categoryId !== 'todos') {
                params.set('category_id', catalogState.categoryId);
            }

            return `/catalog/products-by-partner?${params.toString()}`;
        }

        function fetchProducts(page = 1, options = {}) {
            const {
                shouldScroll = false
            } = options;

            catalogState.page = page;
            updateFilterLabel();
            showSkeletons();

            if (activeCatalogRequest) {
                activeCatalogRequest.abort();
            }

            activeCatalogRequest = $.ajax({
                type: 'GET',
                url: buildCatalogUrl(page),
                success: (response) => {
                    const products = Array.isArray(response.data) ? response.data : [];
                    renderProducts(products);
                    renderPagination(response);
                    updateCount(Number(response.total || 0));

                    if (shouldScroll) {
                        scrollProductsBelowFilters();
                    }
                },
                error: (xhr) => {
                    if (xhr.statusText === 'abort') {
                        return;
                    }

                    renderEmptyState();
                    renderPagination(null);
                    updateCount(0);
                },
                complete: () => {
                    activeCatalogRequest = null;
                }
            });
        }

        $(document).on('click', '.div-categoria', function() {
            $('.div-categoria').removeClass('active-category')
                .addClass('text-gray-600 bg-white border-gray-200')
                .removeClass('text-white bg-blue-600 border-blue-600');

            $(this).addClass('active-category')
                .removeClass('text-gray-600 bg-white border-gray-200');

            catalogState.categoryId = String($(this).data('categoryid'));
            catalogState.page = 1;
            updateFilterLabel();
            fetchProducts(1, {
                shouldScroll: true
            });
        });

        $(document).on('input', '#catalog-search-input', function() {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                catalogState.search = String($(this).val() || '').trim();
                catalogState.page = 1;
                updateFilterLabel();
                fetchProducts(1);
            }, 320);
        });

        $(document).on('click', '.catalog-pagination__btn[data-page]', function() {
            if (this.disabled) {
                return;
            }

            const targetPage = Number($(this).data('page'));
            if (!Number.isFinite(targetPage) || targetPage < 1 || targetPage === catalogState.page) {
                return;
            }

            fetchProducts(targetPage, {
                shouldScroll: true
            });
        });

        $(document).ready(function() {
            updateCount(initialProductsCount);
            updateFilterLabel();
            fetchProducts(1);
        });
    </script>
</body>

</html>
