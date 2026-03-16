@props(['active'])

@php
    $classes = ($active ?? false)
            ? 'bg-blue-400 ml-1 w-1 h-8'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<div class="flex items-center">
    <div {{ $attributes->merge(['class' => $classes]) }}></div>
    <a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out']) }}>
        {{ $slot }}
    </a>
</div>
