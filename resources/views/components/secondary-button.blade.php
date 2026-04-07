<button {{ $attributes->merge(['type' => 'button', 'class' => 'w-full justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-gray-100 text-base font-medium text-black hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-100']) }}>
    {{ $slot }}
</button>
