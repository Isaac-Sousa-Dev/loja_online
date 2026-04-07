@if ($paginator->hasPages())
    <ul class="pagination flex justify-end gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled bg-gray-200 border rounded-md h-9 w-9 flex justify-center items-center text-xl"><span>&laquo;</span></li>
        @else
            <a class="bg-white border rounded-md h-9 w-9 flex justify-center items-center text-xl" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)

        
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled bg-white border rounded-md h-9 w-9 flex justify-center items-center text-xl"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @php
                    // Limitar a exibição a no máximo 4 links
                    $total = count($element);
                    $slice = $total > 4 ? array_slice($element, 0, 4) : $element;
                @endphp
                @foreach ($slice as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active bg-blue-700 text-white rounded-md h-9 w-9 flex justify-center items-center text-xl"><span>{{ $page }}</span></li>
                    @else
                        <a class="bg-white border rounded-md h-9 w-9 flex justify-center items-center text-xl" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="bg-white border rounded-md h-9 w-9 flex justify-center items-center text-xl" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            <li class="disabled bg-gray-200 border rounded-md h-9 w-9 flex justify-center items-center text-xl"><span>&raquo;</span></li>
        @endif
    </ul>
@endif