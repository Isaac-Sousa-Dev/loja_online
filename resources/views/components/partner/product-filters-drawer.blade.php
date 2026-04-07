@props([
    'categoriesByPartner',
    'brandsByPartner',
    'filters' => [],
])

@php
    $f = $filters;
@endphp

{{-- Drawer: mobile = bottom sheet, md+ = painel à direita --}}
<div id="productFiltersDrawerRoot"
    class="fixed inset-0 z-40 flex items-end justify-center md:items-stretch md:justify-end pointer-events-none invisible opacity-0 transition-[opacity,visibility] duration-200"
    aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/45 backdrop-blur-[2px]"
        data-product-filters-close
        aria-hidden="true"></div>

    <aside id="productFiltersPanel"
        class="relative z-10 flex w-full max-h-[min(90dvh,720px)] md:max-h-none md:h-full md:max-w-[420px] flex-col bg-white shadow-[0_-8px_40px_rgba(15,23,42,0.12)] md:shadow-2xl rounded-t-2xl md:rounded-none md:rounded-l-2xl border border-gray-100 md:border-l md:border-t-0 md:border-b-0 md:border-r-0 translate-y-full md:translate-y-0 md:translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] pointer-events-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="productFiltersDrawerTitle">

        <div class="mx-auto mt-2 h-1 w-10 shrink-0 rounded-full bg-gray-200 md:hidden" aria-hidden="true"></div>

        <header class="flex items-center justify-between gap-3 border-b border-gray-100 px-4 py-3 shrink-0">
            <div class="min-w-0">
                <h2 id="productFiltersDrawerTitle" class="text-base font-bold text-gray-900 truncate">Filtrar produtos</h2>
                <p class="text-xs text-gray-500 mt-0.5">Refine por nome, categoria, marca, gênero e status.</p>
            </div>
            <button type="button"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                data-product-filters-close
                aria-label="Fechar painel de filtros">
                <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
            </button>
        </header>

        <form method="get" action="{{ route('products.index') }}" id="productFiltersForm" class="flex min-h-0 flex-1 flex-col">
            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain px-4 py-4">
                <div>
                    <label for="filter_name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Nome</label>
                    <input type="search" name="name" id="filter_name" value="{{ $f['name'] ?? '' }}"
                        placeholder="Buscar por nome..."
                        class="h-10 w-full rounded-xl border border-gray-200 px-3 text-sm shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        autocomplete="off" />
                </div>
                <div>
                    <label for="filter_category" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Categoria</label>
                    <select name="category_id" id="filter_category"
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">Todas</option>
                        @foreach ($categoriesByPartner as $sc)
                            @if ($sc->category)
                                <option value="{{ $sc->category->id }}" @selected(isset($f['category_id']) && (int) $f['category_id'] === (int) $sc->category->id)>{{ $sc->category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filter_brand" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Marca</label>
                    <select name="brand_id" id="filter_brand"
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">Todas</option>
                        @foreach ($brandsByPartner as $b)
                            <option value="{{ $b->id }}" @selected(isset($f['brand_id']) && (int) $f['brand_id'] === (int) $b->id)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filter_gender" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Gênero</label>
                    <select name="gender" id="filter_gender"
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">Todos</option>
                        <option value="masculine" @selected(($f['gender'] ?? '') === 'masculine')>Masculino</option>
                        <option value="feminine" @selected(($f['gender'] ?? '') === 'feminine')>Feminino</option>
                    </select>
                </div>
                <div>
                    <label for="filter_status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-500">Status</label>
                    <select name="status" id="filter_status"
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="all" @selected(($f['status'] ?? 'all') === 'all')>Todos</option>
                        <option value="active" @selected(($f['status'] ?? '') === 'active')>Ativo</option>
                        <option value="inactive" @selected(($f['status'] ?? '') === 'inactive')>Inativo</option>
                    </select>
                </div>
            </div>

            <footer class="shrink-0 border-t border-gray-100 bg-gray-50/80 px-4 py-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] md:pb-3">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="submit"
                        class="inline-flex h-11 flex-1 items-center justify-center gap-2 py-2 rounded-xl bg-gray-900 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                        Aplicar filtros
                    </button>
                    <a href="{{ route('products.index') }}"
                        class="inline-flex h-11 flex-1 items-center justify-center rounded-xl py-2 border border-gray-200 bg-white text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:flex-none sm:min-w-[7rem]">
                        Limpar
                    </a>
                </div>
            </footer>
        </form>
    </aside>
</div>
