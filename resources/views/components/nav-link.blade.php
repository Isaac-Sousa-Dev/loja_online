@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 gap-3 bg-slate-500 py-1.5 rounded-lg border-r-4 border-white text-sm font-medium leading-5 text-white focus:outline-none focus:border-white transition duration-150 ease-in-out'
            : 'flex items-center px-3 gap-3 py-1.5 rounded-sm border-r-4 border-transparent text-sm font-medium leading-5 text-white hover:text-gray-100 hover:border-white focus:outline-none focus:text-white focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
