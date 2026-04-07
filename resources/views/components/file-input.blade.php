@props(['disabled' => false])

<input 
    type="file"
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 h-9 rounded-md shadow-xs block w-full']) !!}
/>
