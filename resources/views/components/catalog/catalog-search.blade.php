@props(['placeholder' => 'Busque por marca, modelo ou nome...'])

<div class="relative w-full">
    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
        </svg>
    </div>
    <input
        type="text"
        id="catalog-search-input"
        class="inputSearchCatalog w-full h-12 pl-12 pr-5 rounded-2xl border border-gray-200 bg-white
               text-sm text-gray-700 placeholder-gray-400 shadow-sm outline-none
               focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all duration-200"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
    >
</div>
