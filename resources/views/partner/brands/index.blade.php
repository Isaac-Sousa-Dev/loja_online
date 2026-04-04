<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        @php
            $brandActionBase =
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
                    <span class="font-semibold text-gray-700">Marcas</span>
                </nav>

                <div class="mt-1 mb-3 flex flex-col gap-3 px-1 md:flex-row md:items-center md:justify-between md:gap-4">
                    <h2 class="font-display font-semibold text-3xl text-[#33363B] leading-tight">Marcas</h2>
                    <div class="flex w-full flex-col gap-2 sm:flex-row sm:flex-wrap md:w-auto md:justify-end md:gap-2">
                        <a href="{{ route('brands.create') }}"
                            class="inline-flex h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:h-10 md:min-w-[9.5rem] md:flex-none">
                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            <span>Nova Marca</span>
                        </a>
                    </div>
                </div>

                <div class="mb-3 flex flex-wrap items-center gap-3 px-1">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-500">
                        <i class="fa-solid fa-tags text-xs text-blue-500" aria-hidden="true"></i>
                        {{ $brands->count() }} {{ $brands->count() === 1 ? 'marca' : 'marcas' }}
                    </span>
                </div>

                @if ($brands->isEmpty())
                    <div class="rounded-2xl border border-gray-100 bg-white p-10 text-center shadow-sm">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                            <i class="fa-solid fa-tags text-xl text-blue-400" aria-hidden="true"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-700">Nenhuma marca cadastrada</p>
                        <p class="mt-1 text-sm text-gray-400">Cadastre sua primeira marca para organizar melhor seu catálogo.</p>
                        <a href="{{ route('brands.create') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-500">
                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            Adicionar marca
                        </a>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="hidden grid-cols-12 gap-2 border-b border-gray-100 bg-gray-50 px-4 py-2.5 text-xs font-semibold uppercase tracking-wide text-gray-500 md:grid">
                            <div class="col-span-8">Marca</div>
                            <div class="col-span-2 text-center">Visual</div>
                            <div class="col-span-2 text-right">Ações</div>
                        </div>

                        <div id="listBrandStatic">
                            @foreach ($brands as $brand)
                                @php
                                    $hasLogo = filled($brand->logo_brand);
                                    $visualBadgeClass = $hasLogo ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700';
                                    $visualBadgeIcon = $hasLogo ? 'fa-circle-check' : 'fa-image';
                                    $visualBadgeText = $hasLogo ? 'Logo enviada' : 'Sem logo';
                                @endphp

                                <div class="border-b border-gray-100 px-3 py-3 transition-colors last:border-b-0 hover:bg-gray-50 md:grid md:grid-cols-12 md:items-center md:gap-2 md:px-4">
                                    <div class="col-span-8 flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if ($hasLogo)
                                                <img class="h-12 w-12 rounded-xl object-cover" src="{{ asset('storage/' . $brand->logo_brand) }}"
                                                    alt="Logo da marca {{ $brand->name }}" />
                                            @else
                                                <img class="h-12 w-12 rounded-xl bg-gray-100 object-cover" src="/img/image-not-found.png"
                                                    alt="Imagem padrão para a marca {{ $brand->name }}" />
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('brands.edit', $brand->id) }}"
                                                class="line-clamp-1 text-sm font-semibold leading-snug text-gray-800 transition-colors hover:text-blue-600">
                                                {{ $brand->name }}
                                            </a>
                                            <div class="mt-0.5 flex flex-wrap items-center gap-1.5">
                                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">Marca</span>
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
                                        role="group" aria-label="Ações da marca">
                                        <a href="{{ route('brands.edit', $brand->id) }}"
                                            title="Editar marca"
                                            aria-label="Editar marca {{ $brand->name }}"
                                            class="{{ $brandActionBase }} no-underline hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 active:scale-[0.98]">
                                            <i class="fa-solid fa-pen text-sm sm:text-xs" aria-hidden="true"></i>
                                        </a>

                                        <div class="delete-action-container inline-flex" data-id="{{ $brand->id }}" data-name="{{ $brand->name }}">
                                            <button type="button"
                                                onclick="showDeleteConfirmationBrandModal(event)"
                                                title="Excluir marca"
                                                aria-label="Excluir marca {{ $brand->name }}"
                                                class="{{ $brandActionBase }} hover:border-red-200 hover:bg-red-50 hover:text-red-600 active:scale-[0.98]">
                                                <i class="fa-solid fa-trash text-sm sm:text-xs" aria-hidden="true"></i>
                                            </button>
                                            <form action="{{ route('brands.destroy', $brand->id) }}" class="deleteFormBrand" method="POST">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="listBrandDinamic" style="display: none" class="bg-white"></div>
                    </div>
                @endif
            </div>
        </div>

        <x-partner.delete-confirmation-modal
            modal-id="deleteConfirmationBrandModal"
            title="Excluir marca?"
            name-target-id="deleteModalBrandName"
            confirm-button-id="btnConfirmDeleteBrand"
            cancel-action="closeDeleteBrandModal()"
            confirm-action="confirmDeleteBrand()"
            fallback-name="esta marca"
        />
    @endsection
</x-app-layout>

<script>
    function showDeleteConfirmationBrandModal(event) {
        const container = event.target.closest('.delete-action-container');
        const brandId = container.dataset.id;
        const brandName = container.dataset.name || 'esta marca';
        const modal = document.getElementById('deleteConfirmationBrandModal');

        modal.dataset.brandId = brandId;
        document.getElementById('deleteModalBrandName').textContent = brandName;
        document.getElementById('btnConfirmDeleteBrand').disabled = false;
        document.getElementById('btnConfirmDeleteBrand').innerHTML = '<i class="fa-solid fa-trash mr-1.5 text-xs" aria-hidden="true"></i>Excluir';
        modal.classList.remove('hidden');
    }

    function closeDeleteBrandModal() {
        document.getElementById('deleteConfirmationBrandModal').classList.add('hidden');
    }

    function confirmDeleteBrand() {
        const modal = document.getElementById('deleteConfirmationBrandModal');
        const brandId = modal.dataset.brandId;
        const container = document.querySelector('.delete-action-container[data-id="' + brandId + '"]');
        const form = container ? container.querySelector('.deleteFormBrand') : document.querySelector('.deleteFormBrand');
        const button = document.getElementById('btnConfirmDeleteBrand');

        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5 text-xs" aria-hidden="true"></i>Excluindo...';

        form.submit();
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteBrandModal();
        }
    });
</script>
