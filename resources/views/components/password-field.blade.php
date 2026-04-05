@props([
    'label',
    'id',
    'name',
    'value' => '',
    'placeholder' => null,
    'autocomplete' => 'current-password',
    'required' => false,
    'autofocus' => false,
    'errorName' => null,
])

@php
    $errorKey = $errorName ?? $name;
@endphp

<div {{ $attributes }}>
    <label for="{{ $id }}" class="mb-1 block text-sm font-semibold text-[#33363B]">
        {{ $label }}
    </label>
    <div class="relative">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="password"
            value="{{ $value }}"
            @if ($placeholder !== null && $placeholder !== '') placeholder="{{ $placeholder }}" @endif
            autocomplete="{{ $autocomplete }}"
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            class="w-full rounded-2xl border border-[#33363B]/12 bg-white py-3 pl-4 pr-12 text-[#33363B] shadow-sm transition focus:border-[#6A2BBA]/40 focus:outline-none focus:ring-2 focus:ring-[#6A2BBA]/35"
        />
        <button
            type="button"
            class="absolute inset-y-0 right-0 z-[1] flex items-center justify-center rounded-r-2xl px-3 text-[#33363B]/50 transition hover:text-[#6A2BBA] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#6A2BBA]/40"
            aria-label="Mostrar senha"
            aria-pressed="false"
            aria-controls="{{ $id }}"
            data-password-toggle
        >
            <span class="pw-eye-open" aria-hidden="true">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </span>
            <span class="pw-eye-off hidden" aria-hidden="true">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </span>
        </button>
    </div>
    <x-input-error :messages="$errors->get($errorKey)" class="mt-1" />
</div>

@once
    @push('scripts')
        <script>
            (function() {
                document.addEventListener('click', function(e) {
                    var btn = e.target.closest('[data-password-toggle]');
                    if (!btn) return;
                    var id = btn.getAttribute('aria-controls');
                    var input = document.getElementById(id);
                    if (!input) return;
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                    btn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
                    var open = btn.querySelector('.pw-eye-open');
                    var off = btn.querySelector('.pw-eye-off');
                    if (open && off) {
                        open.classList.toggle('hidden', show);
                        off.classList.toggle('hidden', !show);
                    }
                });
            })();
        </script>
    @endpush
@endonce
