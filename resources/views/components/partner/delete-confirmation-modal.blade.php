@props([
    'modalId',
    'title',
    'nameTargetId',
    'confirmButtonId',
    'cancelAction',
    'confirmAction',
    'fallbackName' => 'este item',
    'confirmLabel' => 'Excluir',
])

<div id="{{ $modalId }}"
    class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
    aria-labelledby="{{ $modalId }}-title"
    role="dialog"
    aria-modal="true">

    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="{{ $cancelAction }}"></div>

    <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl animate-modalIn">
        <div class="mb-4 flex justify-center">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <i class="fa-solid fa-trash text-xl text-red-500" aria-hidden="true"></i>
            </div>
        </div>

        <div class="mb-6 text-center">
            <h3 id="{{ $modalId }}-title" class="mb-1 text-lg font-bold text-gray-900">{{ $title }}</h3>
            <p class="text-sm text-gray-500">
                Você está prestes a excluir
                <strong id="{{ $nameTargetId }}" class="text-gray-700">{{ $fallbackName }}</strong>.
                Esta ação <strong>não pode ser desfeita</strong>.
            </p>
        </div>

        <div class="flex gap-3">
            <button type="button"
                onclick="{{ $cancelAction }}"
                class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
                Cancelar
            </button>
            <button type="button"
                id="{{ $confirmButtonId }}"
                onclick="{{ $confirmAction }}"
                class="flex-1 rounded-xl bg-red-600 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-red-700">
                <i class="fa-solid fa-trash mr-1.5 text-xs" aria-hidden="true"></i>{{ $confirmLabel }}
            </button>
        </div>
    </div>
</div>

@once
    <style>
        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.92) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-modalIn {
            animation: modalIn 0.25s cubic-bezier(0.34, 1.26, 0.64, 1) both;
        }
    </style>
@endonce
