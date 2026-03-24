@props(['categories'])

<div id="div-categorias" class="flex gap-2 overflow-x-auto pb-1 scrollbar-hidden">
    @if(count($categories) > 1)
        <button
            data-categoryId="todos"
            class="div-categoria flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold
                   bg-blue-600 text-white border border-blue-600 cursor-pointer
                   transition-all duration-200 whitespace-nowrap active-category">
            Todos
        </button>
    @endif

    @foreach ($categories as $storeCategory)
        <button
            data-categoryId="{{ $storeCategory->category->id }}"
            class="div-categoria flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold
                   bg-white text-gray-600 border border-gray-200 cursor-pointer
                   hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700
                   transition-all duration-200 whitespace-nowrap">
            {{ $storeCategory->category->name }}
        </button>
    @endforeach
</div>
