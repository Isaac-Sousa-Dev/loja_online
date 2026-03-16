@props([
    'columns' => [],    // ['Nome' => 'name', 'E-mail' => 'email', ...]
    'data' => [],       // coleção ou array de dados
    'empty_message' => 'Nenhum registro encontrado.',
    'actions' => false  // callback ou slot para botões de ação
])

<div class="overflow-auto rounded-lg border border-gray-200 shadow-md mt-4">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-100 border-b border-gray-200">
            <tr>
                @foreach ($columns as $label => $field)
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">{{ $label }}</th>
                @endforeach

                @if ($actions)
                    <th class="px-4 py-2 text-right font-semibold text-gray-700">Ações</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    @foreach ($columns as $label => $field)
                        <td class="px-4 py-2 text-gray-700">
                            {{ data_get($item, $field) }}
                        </td>
                    @endforeach

                    @if ($actions)
                        <td class="px-4 py-2 text-right">
                            {{ $actions($item) }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="px-4 py-6 text-center text-gray-400 font-semibold">
                        {{ $empty_message }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
