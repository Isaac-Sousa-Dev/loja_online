<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) }}>
    {{ $slot }}
</button>
