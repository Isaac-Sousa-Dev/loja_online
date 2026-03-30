@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 gap-3 bg-gradient-to-r from-[#6A2BBA] to-[#8B3DC7] py-1.5 rounded-lg border-r-4 border-[#FF914D] text-sm font-medium leading-5 text-white shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D131A3] focus-visible:ring-offset-2 focus-visible:ring-offset-[#33363B] transition duration-150 ease-in-out'
            : 'flex items-center px-3 gap-3 py-1.5 rounded-lg border-r-4 border-transparent text-sm font-medium leading-5 text-white/90 hover:text-white hover:bg-white/10 hover:border-[#6A2BBA]/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA]/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
