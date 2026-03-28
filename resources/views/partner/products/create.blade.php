<x-app-layout>
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="flex md:justify-center pb-28 md:pb-8">
<div class="md:flex md:max-w-[1200px] flex-col w-full px-3 md:px-2">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-house text-xs" aria-hidden="true"></i><span>Início</span>
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-gray-400" aria-hidden="true"></i>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition-colors">Produtos</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-gray-400" aria-hidden="true"></i>
        <span class="font-semibold text-gray-700">Novo Produto</span>
    </nav>

    {{-- Title --}}
    <div class="flex items-center gap-3 mt-1 mb-5 px-1">
        <a href="{{ route('products.index') }}"
           class="flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-400 transition-all flex-shrink-0"
           aria-label="Voltar para lista de produtos">
            <i class="fa-solid fa-arrow-left text-sm" aria-hidden="true"></i>
        </a>
        <h2 class="font-bold text-2xl text-gray-800">Novo Produto</h2>
    </div>

    {{-- TABS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">

        <div class="flex border-b border-gray-100 overflow-x-auto overflow-y-hidden scrollbar-none" role="tablist" aria-label="Etapas do cadastro" style="-webkit-overflow-scrolling:touch; scrollbar-width:none; -ms-overflow-style:none;">
            <button type="button" role="tab" id="mainTabBtn-geral" aria-controls="mainTab-geral" aria-selected="true"
                onclick="tryGoTab('geral')"
                class="step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-blue-600 text-blue-700 transition -mb-px flex-shrink-0">
                <span id="stepDot-geral" class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] flex items-center justify-center font-bold flex-shrink-0">1</span>
                Geral
            </button>
            <div class="flex items-center px-1 text-gray-300 flex-shrink-0" aria-hidden="true"><i class="fa-solid fa-chevron-right text-[10px]"></i></div>
            <button type="button" role="tab" id="mainTabBtn-atributos" aria-controls="mainTab-atributos" aria-selected="false" onclick="tryGoTab('atributos')" disabled
                class="step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-400 transition -mb-px flex-shrink-0 opacity-50 cursor-not-allowed">
                <span id="stepDot-atributos" class="w-5 h-5 rounded-full bg-gray-200 text-gray-500 text-[10px] flex items-center justify-center font-bold flex-shrink-0">2</span>
                Atributos
            </button>
            <div class="flex items-center px-1 text-gray-300 flex-shrink-0" aria-hidden="true"><i class="fa-solid fa-chevron-right text-[10px]"></i></div>
            <button type="button" role="tab" id="mainTabBtn-fotos" aria-controls="mainTab-fotos" aria-selected="false" onclick="tryGoTab('fotos')" disabled
                class="step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-400 transition -mb-px flex-shrink-0 opacity-50 cursor-not-allowed">
                <span id="stepDot-fotos" class="w-5 h-5 rounded-full bg-gray-200 text-gray-500 text-[10px] flex items-center justify-center font-bold flex-shrink-0">3</span>
                Fotos
                <span id="fotosCountBadge" class="hidden bg-purple-100 text-purple-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
            </button>
            <div class="flex items-center px-1 text-gray-300 flex-shrink-0" aria-hidden="true"><i class="fa-solid fa-chevron-right text-[10px]"></i></div>
            <button type="button" role="tab" id="mainTabBtn-estoque" aria-controls="mainTab-estoque" aria-selected="false" onclick="tryGoTab('estoque')" disabled
                class="step-tab flex items-center gap-2 px-3 py-3.5 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-400 transition -mb-px flex-shrink-0 opacity-50 cursor-not-allowed">
                <span id="stepDot-estoque" class="w-5 h-5 rounded-full bg-gray-200 text-gray-500 text-[10px] flex items-center justify-center font-bold flex-shrink-0">4</span>
                Estoque
                <span id="skuCountBadge" class="hidden bg-violet-100 text-violet-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
            </button>
        </div>

        {{-- TAB 1: GERAL --}}
        <div id="mainTab-geral" role="tabpanel" aria-labelledby="mainTabBtn-geral" class="p-3 md:p-5">
            <form id="productForm" action="{{ route('products.wizard.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="md:flex gap-6">
                    {{-- LEFT --}}
                    <div class="flex-1 flex flex-col gap-5">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-box text-xs" aria-hidden="true"></i></div>
                                <p class="font-bold text-gray-800">Dados do produto</p>
                            </div>
                            <div class="grid md:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <x-input-label for="category_id" :value="__('Categoria *')" />
                                    <select id="category_id" name="category_id" class="select-category border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full mt-1">
                                        @foreach ($categoriesByPartner as $sc)
                                            <option value="{{ $sc->category->id }}">{{ $sc->category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="brand_id" :value="__('Marca *')" />
                                    <select id="brand_id" name="brand_id" class="select-brands border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full mt-1">
                                        @foreach ($brandsByPartner as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <x-input-label for="name" :value="__('Nome *')" />
                                <x-text-input id="name" placeholder="Ex: Camisa Polo Azul" name="name" type="text" class="mt-1 w-full step1-required" />
                            </div>
                            <div>
                                <x-input-label for="description" :value="__('Descrição *')" />
                                <x-textarea id="description" class="step1-required mt-1" placeholder="Descreva o produto..." name="description"></x-textarea>
                            </div>
                        </div>

                        {{-- Preços --}}
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-tag text-xs" aria-hidden="true"></i></div>
                                <p class="font-bold text-gray-800">Preços</p>
                            </div>
                            <div id="divCostGreaterPrice" class="hidden mb-3">
                                <div class="bg-amber-50 border border-amber-200 px-3 py-2 rounded-xl flex items-center gap-2">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm" aria-hidden="true"></i>
                                    <span id="msgCostGreaterPrice" class="text-amber-700 font-medium text-sm"></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <div><x-input-label for="price" :value="__('Varejo *')" /><x-text-input id="price" placeholder="0,00" name="price" type="text" class="price-mask step1-required mt-1 w-full" /></div>
                                <div><x-input-label for="price_wholesale" :value="__('Atacado')" /><x-text-input id="price_wholesale" placeholder="0,00" name="price_wholesale" type="text" class="price-mask mt-1 w-full" /></div>
                                <div><x-input-label for="price_promotional" :value="__('Promocional')" /><x-text-input id="price_promotional" placeholder="0,00" name="price_promotional" type="text" class="price-mask mt-1 w-full" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><x-input-label for="cost" :value="__('Custo')" /><x-text-input id="cost" placeholder="0,00" name="cost" type="text" class="price-mask mt-1 w-full" /></div>
                                <div><x-input-label for="profit" :value="__('Margem')" /><x-text-input disabled id="profit" placeholder="--" name="profit" type="text" class="bg-gray-100 mt-1 w-full" /></div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT sidebar --}}
                    <div class="md:w-60 lg:w-64 flex flex-col gap-4 mt-5 md:mt-0">
                        <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                            <div class="flex items-center gap-2 mb-3"><div class="w-6 h-6 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-list-ul text-[10px]" aria-hidden="true"></i></div><p class="font-bold text-gray-700 text-sm">Propriedades</p></div>
                            <div>
                                <x-input-label for="gender" :value="__('Gênero')" />
                                <select id="gender" name="gender" class="border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl h-9 shadow-xs w-full mt-1 text-sm">
                                    <option value="">Selecione</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminino</option>
                                    <option value="U">Unissex</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                            <div class="flex items-center gap-2 mb-3"><div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-ruler-combined text-[10px]" aria-hidden="true"></i></div><p class="font-bold text-gray-700 text-sm">Dimensões</p></div>
                            <div class="flex flex-col gap-3">
                                <div><x-input-label for="weight" :value="__('Peso (kg)')" /><x-text-input id="weight" placeholder="0.300" name="weight" type="number" step="0.001" class="mt-1 w-full h-9 text-sm" /></div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div><x-input-label for="width" :value="__('Larg.')" /><x-text-input id="width" placeholder="cm" name="width" type="number" class="mt-1 w-full h-9 text-sm px-2" /></div>
                                    <div><x-input-label for="height" :value="__('Alt.')" /><x-text-input id="height" placeholder="cm" name="height" type="number" class="mt-1 w-full h-9 text-sm px-2" /></div>
                                    <div><x-input-label for="length" :value="__('Comp.')" /><x-text-input id="length" placeholder="cm" name="length" type="number" class="mt-1 w-full h-9 text-sm px-2" /></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                            <div class="flex items-center gap-2 mb-3"><div class="w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-credit-card text-[10px]" aria-hidden="true"></i></div><p class="font-bold text-gray-700 text-sm">Pagamento</p></div>
                            <div class="flex flex-col gap-3">
                                <div>
                                    <x-input-label for="installments" :value="__('Parcelamento')" />
                                    <select id="installments" name="installments" class="border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl h-9 shadow-xs w-full mt-1 text-sm">
                                        <option value="1">1x (À vista)</option>
                                        <option value="2">2x sem juros</option>
                                        <option value="3">3x sem juros</option>
                                        <option value="4">4x sem juros</option>
                                        <option value="5">5x sem juros</option>
                                        <option value="6">6x sem juros</option>
                                        <option value="10">10x sem juros</option>
                                        <option value="12">12x sem juros</option>
                                    </select>
                                </div>
                                <div><x-input-label for="discount_pix" :value="__('Desconto PIX (%)')" /><x-text-input id="discount_pix" placeholder="Ex: 5" name="discount_pix" type="number" class="mt-1 w-full h-9 text-sm" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- TAB 2: ATRIBUTOS --}}
        <div id="mainTab-atributos" role="tabpanel" aria-labelledby="mainTabBtn-atributos" class="hidden p-2 md:p-5">
            <x-product-variants-manager :productId="null" existingVariants="[]" />
        </div>

        {{-- TAB 3: FOTOS POR COR --}}
        <div id="mainTab-fotos" role="tabpanel" aria-labelledby="mainTabBtn-fotos" class="hidden p-3 md:p-5">
            <div id="colorPhotosWrapper">
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <i class="fa-solid fa-palette text-gray-200 text-4xl mb-3" aria-hidden="true"></i>
                    <p class="font-semibold text-gray-400 text-sm">Nenhuma cor cadastrada ainda</p>
                    <p class="text-xs text-gray-300 mt-1">Volte para Atributos e gere as variantes primeiro.</p>
                </div>
            </div>
        </div>

        {{-- TAB 4: ESTOQUE --}}
        <div id="mainTab-estoque" role="tabpanel" aria-labelledby="mainTabBtn-estoque" class="hidden">
            <div id="estoqueEmpty" class="flex flex-col items-center justify-center py-14 text-center px-6">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-3"><i class="fa-solid fa-table-list text-gray-300 text-2xl" aria-hidden="true"></i></div>
                <p class="font-semibold text-gray-500 text-sm">Nenhuma variante gerada</p>
                <p class="text-xs text-gray-400 mt-1 mb-4">Vá para Atributos e clique em "Gerar variantes".</p>
                <button type="button" onclick="tryGoTab('atributos')" class="text-xs font-bold text-violet-600 border border-violet-200 hover:border-violet-400 px-4 py-2 rounded-xl transition">Ir para Atributos →</button>
            </div>
            <div id="skuTableSection" class="hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Cor</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Tamanho</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Código SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Preço</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide w-20">Estoque</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 w-8"></th>
                        </tr></thead>
                        <tbody id="skuTableBody" class="divide-y divide-gray-50"></tbody>
                    </table>
                </div>
                <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3 bg-gray-50/50">
                    <p id="skuFooterSummary" class="text-xs text-gray-500 font-medium"></p>
                </div>
            </div>
        </div>

    </div>

    {{-- BOTTOM BAR — botão contextual por step --}}
    <div class="fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 px-4 py-3 md:static md:bg-transparent md:border-none md:px-0 md:py-0 md:flex md:justify-end md:mt-2 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.06)] md:shadow-none">
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('products.index') }}"
               class="flex-1 md:flex-none flex items-center justify-center gap-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                Cancelar
            </a>
            <button type="button" id="btnPrimaryAction"
                class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm shadow-sm">
                <i class="fa-solid fa-arrow-right text-xs" id="btnPrimaryIcon" aria-hidden="true"></i>
                <span id="btnPrimaryLabel">Próximo: Atributos</span>
            </button>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
window.productCreateWizardConfig = {
    wizardStoreUrl: @json(route('products.wizard.store')),
    productsIndexUrl: @json(route('products.index')),
};
</script>
@vite(['resources/js/partner/product-create-wizard.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof TomSelect !== 'undefined') {
        document.querySelectorAll('.select-category').forEach(function (el) {
            new TomSelect(el, { create: false, sortField: { field: 'text', direction: 'asc' } });
        });
        document.querySelectorAll('.select-brands').forEach(function (el) {
            new TomSelect(el, { create: false, sortField: { field: 'text', direction: 'asc' } });
        });
    }
});
</script>
@endpush
</x-app-layout>
