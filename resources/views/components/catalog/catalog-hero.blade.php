@props(['bannerStore', 'logoStore', 'store', 'itsOpen'])

<div class="catalog-hero relative w-full overflow-hidden">
    {{-- Banner --}}
    <div class="catalog-hero__banner relative w-full h-44 md:h-64 overflow-hidden">
        @if($bannerStore)
            <img src="{{ $bannerStore }}" alt="Banner {{ $store->store_name }}"
                 class="w-full h-full object-cover object-center">
        @else
            <div class="w-full h-full catalog-hero__banner-placeholder"></div>
        @endif
        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
    </div>

    {{-- Logo + Info Card --}}
    <div class="relative z-10 -mt-16 md:-mt-20 flex flex-col items-center px-4">
        {{-- Logo --}}
        <div class="catalog-hero__logo w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-white shadow-xl bg-white">
            @if($logoStore)
                <img src="{{ $logoStore }}" alt="Logo {{ $store->store_name }}"
                     class="w-full h-full object-cover object-center">
            @else
                <img src="/img/vistuu-logo.png" alt="Logo padrão"
                     class="w-full h-full object-cover object-center">
            @endif
        </div>

        {{-- Store Info --}}
        <div class="mt-3 text-center">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">
                {{ $store->store_name }}
            </h1>

            <p class="text-sm text-gray-500 mt-0.5">{{ $store->store_email }}</p>

            @if($store->store_phone)
                <p class="text-sm text-gray-500 mask-phone">{{ $store->store_phone }}</p>
            @endif

            <div class="mt-2 flex items-center justify-center gap-1.5">
                @if($itsOpen)
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Aberto agora
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-500 text-xs font-semibold px-3 py-1 rounded-full border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                        Fechado agora
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
