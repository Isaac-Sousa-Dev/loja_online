<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        @php
            $categoryActionBase =
                'inline-flex shrink-0 items-center justify-center min-h-[2.75rem] min-w-[2.75rem] sm:min-h-0 sm:min-w-0 sm:h-8 sm:w-8 rounded-xl sm:rounded-lg border border-gray-200 bg-white text-gray-600 text-sm shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-1';
        @endphp

        <div class="p-2 flex justify-center pb-24 md:pb-4">
            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

                <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Início</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-semibold text-gray-700">Categorias</span>
                </nav>

                <div class="mt-1 mb-3 flex flex-col gap-3 px-1 md:flex-row md:items-center md:justify-between md:gap-4">
                    <h2 class="font-display font-semibold text-3xl text-[#33363B] leading-tight">Categorias</h2>
                    <div class="flex w-full flex-col gap-2 sm:flex-row sm:flex-wrap md:w-auto md:justify-end md:gap-2">
                        <a href="{{ route('categories.create') }}"
                            class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:h-10 md:min-w-[9.5rem] md:flex-none">
                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            <span>Nova Categoria</span>
                        </a>
                    </div>
                </div>

                <div class="mb-3 flex flex-wrap items-center gap-3 px-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-500">
                        <i class="fa-solid fa-layer-group text-xs text-blue-500" aria-hidden="true"></i>
                        {{ $categoriesByPartner->count() }} {{ $categoriesByPartner->count() === 1 ? 'categoria' : 'categorias' }}
                    </span>
                </div>

                @if ($categoriesByPartner->isEmpty())
                    <div class="rounded-2xl border border-gray-100 bg-white p-10 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                            <i class="fa-solid fa-layer-group text-xl text-blue-400" aria-hidden="true"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-700">Nenhuma categoria cadastrada</p>
                        <p class="mt-1 text-sm text-gray-400">Crie categorias para deixar a navegação do catálogo mais organizada.</p>
                        <a href="{{ route('categories.create') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-500">
                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            Adicionar categoria
                        </a>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="hidden grid-cols-12 gap-2 border-b border-gray-100 bg-gray-50 px-4 py-2.5 text-xs font-semibold uppercase tracking-wide text-gray-500 md:grid">
                            <div class="col-span-8">Categoria</div>
                            <div class="col-span-2 text-center">Visual</div>
                            <div class="col-span-2 text-right">Ações</div>
                        </div>

                        <div id="listCategoryStatic">
                            @foreach ($categoriesByPartner as $categoryByPartner)
                                @php
                                    $categoryName = $categoryByPartner->category?->name ?? 'Categoria';
                                    $hasImage = filled($categoryByPartner->image_url);
                                    $visualBadgeClass = $hasImage ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700';
                                    $visualBadgeIcon = $hasImage ? 'fa-circle-check' : 'fa-image';
                                    $visualBadgeText = $hasImage ? 'Imagem enviada' : 'Sem imagem';
                                @endphp

                                <div class="border-b border-gray-100 px-3 py-3 transition-colors last:border-b-0 hover:bg-gray-50 md:grid md:grid-cols-12 md:items-center md:gap-2 md:px-4">
                                    <div class="col-span-8 flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if ($hasImage)
                                                <img class="h-12 w-12 rounded-xl object-cover" src="{{ asset('storage/' . $categoryByPartner->image_url) }}"
                                                    alt="Imagem da categoria {{ $categoryName }}" />
                                            @else
                                                <img class="h-12 w-12 rounded-xl bg-gray-100 object-cover" src="/img/image-not-found.png"
                                                    alt="Imagem padrão para a categoria {{ $categoryName }}" />
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('categories.edit', $categoryByPartner->category->id) }}"
                                                class="line-clamp-1 text-sm font-semibold leading-snug text-gray-800 transition-colors hover:text-blue-600">
                                                {{ $categoryName }}
                                            </a>
                                            <div class="mt-0.5 flex flex-wrap items-center gap-1.5">
                                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">Categoria</span>
                                                <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $visualBadgeClass }}">
                                                    {{ $visualBadgeText }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-2 hidden justify-center md:flex">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold {{ $visualBadgeClass }}">
                                            <i class="fa-solid {{ $visualBadgeIcon }} text-[10px]" aria-hidden="true"></i>
                                            {{ $visualBadgeText }}
                                        </span>
                                    </div>

                                    <div class="col-span-2 mt-3 flex items-center justify-end gap-2 border-t border-gray-100 pt-2 md:mt-0 md:border-0 md:pt-0"
                                        role="group" aria-label="Ações da categoria">
                                        <a href="{{ route('categories.edit', $categoryByPartner->category->id) }}"
                                            title="Editar categoria"
                                            aria-label="Editar categoria {{ $categoryName }}"
                                            class="{{ $categoryActionBase }} no-underline hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 active:scale-[0.98]">
                                            <i class="fa-solid fa-pen text-sm sm:text-xs" aria-hidden="true"></i>
                                        </a>

                                        <div class="delete-action-container inline-flex" data-id="{{ $categoryByPartner->id }}" data-name="{{ $categoryName }}">
                                            <button type="button"
                                                onclick="showDeleteConfirmationCategoryModal(event)"
                                                title="Excluir categoria"
                                                aria-label="Excluir categoria {{ $categoryName }}"
                                                class="{{ $categoryActionBase }} hover:border-red-200 hover:bg-red-50 hover:text-red-600 active:scale-[0.98]">
                                                <i class="fa-solid fa-trash text-sm sm:text-xs" aria-hidden="true"></i>
                                            </button>
                                            <form action="{{ route('categories.destroy', $categoryByPartner->id) }}" class="deleteFormCategory" method="POST">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="listCategoryDinamic" style="display: none" class="bg-white"></div>
                    </div>
                @endif
            </div>
        </div>

        <x-partner.delete-confirmation-modal
            modal-id="deleteConfirmationCategoryModal"
            title="Excluir categoria?"
            name-target-id="deleteModalCategoryName"
            confirm-button-id="btnConfirmDeleteCategory"
            cancel-action="closeDeleteCategoryModal()"
            confirm-action="confirmDeleteCategory()"
            fallback-name="esta categoria"
        />
    @endsection
</x-app-layout>

<script>
    function showDeleteConfirmationCategoryModal(event) {
        const container = event.target.closest('.delete-action-container');
        const categoryId = container.dataset.id;
        const categoryName = container.dataset.name || 'esta categoria';
        const modal = document.getElementById('deleteConfirmationCategoryModal');

        modal.dataset.categoryId = categoryId;
        document.getElementById('deleteModalCategoryName').textContent = categoryName;
        document.getElementById('btnConfirmDeleteCategory').disabled = false;
        document.getElementById('btnConfirmDeleteCategory').innerHTML = '<i class="fa-solid fa-trash mr-1.5 text-xs" aria-hidden="true"></i>Excluir';
        modal.classList.remove('hidden');
    }

    function closeDeleteCategoryModal() {
        document.getElementById('deleteConfirmationCategoryModal').classList.add('hidden');
    }

    function confirmDeleteCategory() {
        const modal = document.getElementById('deleteConfirmationCategoryModal');
        const categoryId = modal.dataset.categoryId;
        const container = document.querySelector('.delete-action-container[data-id="' + categoryId + '"]');
        const form = container ? container.querySelector('.deleteFormCategory') : document.querySelector('.deleteFormCategory');
        const button = document.getElementById('btnConfirmDeleteCategory');

        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5 text-xs" aria-hidden="true"></i>Excluindo...';

        form.submit();
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteCategoryModal();
        }
    });
</script>
