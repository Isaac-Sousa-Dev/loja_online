@props(['disabled' => false])

<textarea rows="5" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-md shadow-xs mt-1 block w-full']) !!}>
{{ $slot }}
</textarea>
