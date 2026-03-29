@php
    $start = $paginator->firstItem();
    $end = $paginator->lastItem();
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
@endphp

<nav class="mx-auto mt-2 max-w-3xl px-1" aria-label="Paginação de produtos">
    <div class="flex flex-col gap-4 rounded-2xl border border-gray-100 bg-white px-3 py-2 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:gap-6 sm:px-5">
        <p class="text-center text-sm leading-snug text-gray-600 sm:text-left">
            @if ($start !== null && $end !== null)
                <span class="text-gray-500">Mostrando</span>
                <span class="font-semibold text-gray-900">{{ $start }}</span>
                <span class="text-gray-400">–</span>
                <span class="font-semibold text-gray-900">{{ $end }}</span>
                <span class="text-gray-500"> de </span>
                <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
            @else
                <span class="text-gray-500">Nenhum registro nesta página</span>
            @endif
        </p>

        @if ($paginator->hasPages())
            <div class="flex flex-wrap items-center justify-center gap-1.5 sm:justify-end" role="group" aria-label="Navegação entre páginas">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex h-10 min-w-[2.5rem] cursor-not-allowed items-center justify-center rounded-xl border border-gray-100 bg-gray-50 text-sm font-medium text-gray-300"
                        aria-disabled="true" aria-label="Página anterior indisponível">
                        <span aria-hidden="true">&lsaquo;</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        aria-label="Ir para a página anterior">
                        <span aria-hidden="true">&lsaquo;</span>
                    </a>
                @endif

                @php
                    $showPages = [];
                    if ($last <= 7) {
                        for ($i = 1; $i <= $last; $i++) {
                            $showPages[] = $i;
                        }
                    } else {
                        $showPages[] = 1;
                        $window = 1;
                        $from = max(2, $current - $window);
                        $to = min($last - 1, $current + $window);
                        if ($from > 2) {
                            $showPages[] = '…';
                        }
                        for ($i = $from; $i <= $to; $i++) {
                            $showPages[] = $i;
                        }
                        if ($to < $last - 1) {
                            $showPages[] = '…';
                        }
                        $showPages[] = $last;
                    }
                @endphp

                @foreach ($showPages as $p)
                    @if ($p === '…')
                        <span class="inline-flex h-10 min-w-[2.25rem] items-center justify-center text-sm font-medium text-gray-400" aria-hidden="true">…</span>
                    @elseif ((int) $p === $current)
                        <span class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white shadow-sm"
                            aria-current="page" aria-label="Página {{ $p }}, atual">{{ $p }}</span>
                    @else
                        <a href="{{ $paginator->url((int) $p) }}"
                            class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                            aria-label="Ir para a página {{ $p }}">{{ $p }}</a>
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="inline-flex h-10 min-w-[2.5rem] items-center justify-center rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        aria-label="Ir para a próxima página">
                        <span aria-hidden="true">&rsaquo;</span>
                    </a>
                @else
                    <span class="inline-flex h-10 min-w-[2.5rem] cursor-not-allowed items-center justify-center rounded-xl border border-gray-100 bg-gray-50 text-sm font-medium text-gray-300"
                        aria-disabled="true" aria-label="Próxima página indisponível">
                        <span aria-hidden="true">&rsaquo;</span>
                    </span>
                @endif
            </div>
        @endif
    </div>
</nav>
