@php
$startIndex = ($paginator->currentPage() - 1) * $paginator->perPage() + 1;
$endIndex = $startIndex + $paginator->count() - 1;
@endphp

<small class="text-center">Exibindo do {{ $startIndex }} ao {{ $endIndex }} de {{ $paginator->total() }} registros</small>

@if ($paginator->hasPages())
    <nav class="pagination flex space-x-5 mt-2 justify-center" role="navigation" aria-label="Pagination">
        {{-- Botão "Anterior" --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-previous bg-gray-200 px-2 rounded-full" aria-disabled="true" aria-label="@lang('pagination.previous')" disabled>&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-previous bg-gray-200 px-2 rounded-full" aria-label="@lang('pagination.previous')">&lsaquo;</a>
        @endif

        {{-- Links das páginas --}}
        <ul class="pagination-list flex space-x-4">


            {{-- Link da primeira página --}}
            @if ($paginator->currentPage() > 1)
                <li><a href="{{ $paginator->url($paginator->currentPage() - 1) }}" class="pagination-link text-xs" aria-label="@lang('pagination.goto_page', ['page' => $paginator->currentPage() - 1])">{{ $paginator->currentPage() - 1 }}</a></li>
            @endif

            {{-- Link da página atual --}}
            <li><span class="pagination-link bg-gray-500 text-white px-3 py-2 text-xs rounded-full" aria-label="@lang('pagination.goto_page', ['page' => $paginator->currentPage()])">{{ $paginator->currentPage() }}</span></li>

            {{-- Link da próxima página --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" class="pagination-link text-xs" aria-label="@lang('pagination.goto_page', ['page' => $paginator->currentPage() + 1])">{{ $paginator->currentPage() + 1 }}</a></li>
            @endif
        </ul>

        {{-- Botão "Próximo" --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-next bg-gray-200 px-2 rounded-full" aria-label="@lang('pagination.next')"> &rsaquo;</a>
        @else
            <span class="pagination-next bg-gray-200 px-2 rounded-full" aria-disabled="true" aria-label="@lang('pagination.next')" disabled> &rsaquo;</span>
        @endif
    </nav>
@endif
