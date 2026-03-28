<x-app-layout>
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

<div class="flex md:justify-center pb-28 md:pb-8">
<div class="md:flex md:max-w-[1200px] flex-col w-full px-3 md:px-2">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-house text-xs"></i><span>Início</span>
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600 transition-colors">Produtos</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
        <span class="font-semibold text-gray-700 truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    {{-- Title --}}
    <div class="flex items-center gap-3 mt-1 mb-5 px-1">
        <a href="{{ route('products.index') }}"
           class="flex items-center justify-center w-9 h-9 rounded-full bg-white shadow border border-gray-200 text-gray-500 hover:text-blue-600 hover:border-blue-400 transition-all flex-shrink-0">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h2 class="font-bold text-2xl text-gray-800 truncate">{{ $product->name }}</h2>
    </div>

    {{-- ── TABS ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden">

        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button type="button" id="mainTabBtn-geral" onclick="tryGoTab('geral')"
                class="step-tab flex items-center gap-2.5 px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 border-blue-600 text-blue-700 transition -mb-px flex-shrink-0">
                <span id="stepDot-geral" class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold flex-shrink-0">1</span>
                Geral
            </button>
            <div class="flex items-center px-1 text-gray-300 flex-shrink-0"><i class="fa-solid fa-chevron-right text-[10px]"></i></div>
            <button type="button" id="mainTabBtn-atributos" onclick="tryGoTab('atributos')"
                class="step-tab flex items-center gap-2.5 px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-gray-800 transition -mb-px flex-shrink-0">
                <span id="stepDot-atributos" class="w-6 h-6 rounded-full bg-gray-300 text-gray-600 text-xs flex items-center justify-center font-bold flex-shrink-0">2</span>
                Atributos
            </button>
            <div class="flex items-center px-1 text-gray-300 flex-shrink-0"><i class="fa-solid fa-chevron-right text-[10px]"></i></div>
            <button type="button" id="mainTabBtn-estoque" onclick="tryGoTab('estoque')"
                class="step-tab flex items-center gap-2.5 px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-gray-800 transition -mb-px flex-shrink-0">
                <span id="stepDot-estoque" class="w-6 h-6 rounded-full bg-gray-300 text-gray-600 text-xs flex items-center justify-center font-bold flex-shrink-0">3</span>
                Estoque & Preços
                <span id="skuCountBadge" class="hidden bg-violet-100 text-violet-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
            </button>
        </div>

        {{-- TAB 1: GERAL --}}
        <div id="mainTab-geral" class="p-4 md:p-5">
            <div class="md:flex gap-6">
                {{-- LEFT --}}
                <div class="flex-1 flex flex-col gap-5">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-box text-xs"></i></div>
                            <p class="font-bold text-gray-800">Dados do produto</p>
                        </div>
                        <div class="grid md:grid-cols-2 gap-3 mb-3">
                            <div>
                                <x-input-label for="category_id" :value="__('Categoria *')" />
                                <select id="category_id" name="category_id" class="select-category border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full mt-1">
                                    @foreach ($categoriesByPartner as $sc)
                                        <option value="{{ $sc->category->id }}" @selected($sc->category->id == $product->category_id)>{{ $sc->category->name }}</option>
                                    @endforeach
                                </select>
                                <script>new TomSelect(".select-category",{create:false,sortField:{field:"text",direction:"asc"}});</script>
                            </div>
                            <div>
                                <x-input-label for="brand_id" :value="__('Marca *')" />
                                <select id="brand_id" name="brand_id" class="select-brands border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl shadow-xs block w-full mt-1">
                                    @foreach ($brands as $b)
                                        <option value="{{ $b->id }}" @selected($b->id == $product->brand_id)>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                <script>new TomSelect(".select-brands",{create:false,sortField:{field:"text",direction:"asc"}});</script>
                            </div>
                        </div>
                        <div class="mb-3">
                            <x-input-label for="name" :value="__('Nome *')" />
                            <x-text-input id="name" placeholder="Ex: Camisa Polo Azul" name="name" type="text" value="{{ $product->name }}" class="mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Descrição *')" />
                            <x-textarea id="description" class="mt-1" placeholder="Descreva o produto..." name="description">{{ $product->description }}</x-textarea>
                        </div>
                    </div>

                    {{-- Fotos --}}
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-camera text-xs"></i></div>
                            <p class="font-bold text-gray-800">Fotos</p>
                            <span class="text-xs text-gray-400 flex items-center gap-1 ml-1"><i class="fa-solid fa-circle-info text-blue-400 text-[10px]"></i> Aparecem no catálogo</span>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <label class="cursor-pointer">
                                <div class="OpenModalImagesProducts relative bg-blue-50 w-full aspect-square rounded-xl border-2 border-dashed border-sky-400 flex flex-col overflow-hidden hover:bg-blue-100 transition">
                                    <div class="absolute top-1.5 left-1.5 z-10">
                                        <span class="bg-amber-400 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full flex items-center gap-1"><i class="fa-solid fa-star text-[8px]"></i> principal</span>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center justify-center gap-1 pt-5">
                                        <i class="fa-solid fa-plus text-sky-400 text-xl"></i>
                                        <p class="text-[10px] font-semibold text-gray-500 text-center px-2 leading-tight">Melhor foto</p>
                                    </div>
                                    <img src="" style="display:none" class="previewsImagesInProductPage object-cover h-full w-full absolute inset-0 rounded-xl" alt="">
                                </div>
                            </label>
                            @foreach(['Qualidade','Detalhe','Nítida'] as $hint)
                            <label class="cursor-pointer">
                                <div class="OpenModalImagesProducts relative bg-blue-50 w-full aspect-square rounded-xl border-2 border-dashed border-sky-400 flex flex-col items-center justify-center gap-1 overflow-hidden hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-plus text-sky-400 text-xl"></i>
                                    <p class="text-[10px] font-semibold text-gray-500 text-center px-2 leading-tight">{{ $hint }}</p>
                                    <img src="" style="display:none" class="previewsImagesInProductPage object-cover h-full w-full absolute inset-0 rounded-xl" alt="">
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Preços --}}
                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-tag text-xs"></i></div>
                            <p class="font-bold text-gray-800">Preços</p>
                        </div>
                        <div id="divCostGreaterPrice" class="hidden mb-3">
                            <div class="bg-amber-50 border border-amber-200 px-3 py-2 rounded-xl flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm"></i>
                                <span id="msgCostGreaterPrice" class="text-amber-700 font-medium text-sm"></span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div><x-input-label for="price" :value="__('Varejo *')" /><x-text-input id="price" placeholder="0,00" name="price" type="text" value="{{ $product->price }}" class="price-mask mt-1 w-full" /></div>
                            <div><x-input-label for="price_wholesale" :value="__('Atacado')" /><x-text-input id="price_wholesale" placeholder="0,00" name="price_wholesale" type="text" value="{{ $product->price_wholesale }}" class="price-mask mt-1 w-full" /></div>
                            <div><x-input-label for="price_promotional" :value="__('Promocional')" /><x-text-input id="price_promotional" placeholder="0,00" name="price_promotional" type="text" value="{{ $product->price_promotional }}" class="price-mask mt-1 w-full" /></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><x-input-label for="cost" :value="__('Custo')" /><x-text-input id="cost" placeholder="0,00" name="cost" type="text" value="{{ $product->cost }}" class="price-mask mt-1 w-full" /></div>
                            <div><x-input-label for="profit" :value="__('Margem')" /><x-text-input disabled id="profit" placeholder="--" name="profit" type="text" value="{{ $product->profit }}" class="bg-gray-100 mt-1 w-full" /></div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT sidebar --}}
                <div class="md:w-60 lg:w-64 flex flex-col gap-4 mt-5 md:mt-0">
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                        <div class="flex items-center gap-2 mb-3"><div class="w-6 h-6 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-list-ul text-[10px]"></i></div><p class="font-bold text-gray-700 text-sm">Propriedades</p></div>
                        <div class="flex flex-col gap-3">
                            <div>
                                <x-input-label for="gender" :value="__('Gênero')" />
                                <select id="gender" name="gender" class="border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl h-9 shadow-xs w-full mt-1 text-sm">
                                    <option value="">Selecione</option>
                                    <option value="M" @selected($product->gender == 'M')>Masculino</option>
                                    <option value="F" @selected($product->gender == 'F')>Feminino</option>
                                    <option value="U" @selected($product->gender == 'U')>Unissex</option>
                                </select>
                            </div>
                            <div><x-input-label for="color" :value="__('Cor base')" /><x-text-input id="color" placeholder="Ex: Preto" name="color" type="text" value="{{ $product->color }}" class="mt-1 w-full h-9 text-sm" /></div>
                            <div><x-input-label for="stock" :value="__('Estoque')" /><x-text-input id="stock" placeholder="Ex: 10" name="stock" type="number" value="{{ $product->stock }}" class="mt-1 w-full h-9 text-sm" /></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                        <div class="flex items-center gap-2 mb-3"><div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-ruler-combined text-[10px]"></i></div><p class="font-bold text-gray-700 text-sm">Dimensões</p></div>
                        <div class="flex flex-col gap-3">
                            <div><x-input-label for="weight" :value="__('Peso (kg)')" /><x-text-input id="weight" placeholder="0.300" name="weight" type="number" step="0.001" value="{{ $product->weight }}" class="mt-1 w-full h-9 text-sm" /></div>
                            <div class="grid grid-cols-3 gap-2">
                                <div><x-input-label for="width" :value="__('Larg.')" /><x-text-input id="width" placeholder="cm" name="width" type="number" value="{{ $product->width }}" class="mt-1 w-full h-9 text-sm px-2" /></div>
                                <div><x-input-label for="height" :value="__('Alt.')" /><x-text-input id="height" placeholder="cm" name="height" type="number" value="{{ $product->height }}" class="mt-1 w-full h-9 text-sm px-2" /></div>
                                <div><x-input-label for="length" :value="__('Comp.')" /><x-text-input id="length" placeholder="cm" name="length" type="number" value="{{ $product->length }}" class="mt-1 w-full h-9 text-sm px-2" /></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                        <div class="flex items-center gap-2 mb-3"><div class="w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-credit-card text-[10px]"></i></div><p class="font-bold text-gray-700 text-sm">Pagamento</p></div>
                        <div class="flex flex-col gap-3">
                            <div>
                                <x-input-label for="installments" :value="__('Parcelamento')" />
                                <select id="installments" name="installments" class="border-gray-300 focus:border-indigo-300 focus:ring-indigo-300 rounded-xl h-9 shadow-xs w-full mt-1 text-sm">
                                    <option value="1" @selected($product->installments == 1)>1x (À vista)</option>
                                    <option value="2" @selected($product->installments == 2)>2x sem juros</option>
                                    <option value="3" @selected($product->installments == 3)>3x sem juros</option>
                                    <option value="4" @selected($product->installments == 4)>4x sem juros</option>
                                    <option value="5" @selected($product->installments == 5)>5x sem juros</option>
                                    <option value="6" @selected($product->installments == 6)>6x sem juros</option>
                                    <option value="10" @selected($product->installments == 10)>10x sem juros</option>
                                    <option value="12" @selected($product->installments == 12)>12x sem juros</option>
                                </select>
                            </div>
                            <div><x-input-label for="discount_pix" :value="__('Desconto PIX (%)')" /><x-text-input id="discount_pix" placeholder="Ex: 5" name="discount_pix" type="number" value="{{ $product->discount_pix }}" class="mt-1 w-full h-9 text-sm" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: ATRIBUTOS --}}
        <div id="mainTab-atributos" class="hidden p-4 md:p-5">
            <x-product-variants-manager
                :productId="$product->id"
                :existingVariants="$product->variants->map(fn($v) => ['id'=>$v->id,'color'=>$v->color,'size'=>$v->size,'stock'=>$v->stock,'price_override'=>$v->price_override,'sku'=>$v->sku,'color_hex'=>$v->color_hex])->toJson()" />
        </div>

        {{-- TAB 3: ESTOQUE & PREÇOS --}}
        <div id="mainTab-estoque" class="hidden">
            <div id="estoqueEmpty" class="flex flex-col items-center justify-center py-14 text-center px-6">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-3"><i class="fa-solid fa-table-list text-gray-300 text-2xl"></i></div>
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

    {{-- BOTTOM BAR --}}
    <div class="fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 px-4 py-3 md:static md:bg-transparent md:border-none md:px-0 md:py-0 md:flex md:justify-end md:mt-2 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.06)] md:shadow-none">
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('products.index') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">Cancelar</a>
            <button type="button" id="btnPrimaryAction" onclick="doSave()"
                class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm shadow-sm">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Salvar produto</span>
            </button>
        </div>
    </div>

</div>
</div>
@endsection
</x-app-layout>

<!-- Fundo Opaco -->
<div id="backdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

<!-- Slider de Fotos -->
<div id="images-product-slider" class="fixed top-0 right-0 h-full w-full md:w-[390px] bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-50 flex flex-col">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800">Fotos do produto</h3>
        <button id="close-images-product-slider" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-500"><i class="fa-solid fa-xmark text-lg"></i></button>
    </div>
    <div class="px-4 pt-2 pb-1">
        <p class="text-xs text-gray-500 mb-1">Selecione e ordene. <span class="font-semibold text-blue-600">Máx. 8 fotos.</span></p>
        <div id="info-max-images" style="display:none" class="bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold px-3 py-2 rounded-xl mb-2"></div>
    </div>
    <div class="flex-1 overflow-y-auto px-4">
        <div id="previewImages" class="grid grid-cols-2 gap-2 mt-2 mb-4"></div>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        <label for="label-for-images-product" class="label-for-images-product-edit cursor-pointer block">
            <div class="bg-blue-50 border-2 border-dashed border-sky-400 rounded-xl h-12 flex items-center justify-center gap-2 hover:bg-blue-100 transition">
                <i class="fa-solid fa-plus text-sky-500"></i>
                <span class="font-semibold text-sky-600 text-sm">Adicionar foto</span>
            </div>
        </label>
        <input type="file" id="label-for-images-product" name="product-images[]" multiple class="hidden">
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
// ── Tabs: all unlocked in edit ──
const STEPS = ['geral','atributos','estoque'];
let currentTab = 'geral';

function setTabStyle(tab, active) {
    const btn = document.getElementById(`mainTabBtn-${tab}`);
    const dot = document.getElementById(`stepDot-${tab}`);
    if (!btn || !dot) return;
    if (active) {
        btn.className = 'step-tab flex items-center gap-2.5 px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 border-blue-600 text-blue-700 transition -mb-px flex-shrink-0';
        dot.className = 'w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold flex-shrink-0';
    } else {
        btn.className = 'step-tab flex items-center gap-2.5 px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 border-transparent text-gray-600 hover:text-gray-800 transition -mb-px flex-shrink-0';
        dot.className = 'w-6 h-6 rounded-full bg-gray-300 text-gray-600 text-xs flex items-center justify-center font-bold flex-shrink-0';
    }
}

function switchMainTab(tab) {
    STEPS.forEach(t => {
        document.getElementById(`mainTab-${t}`)?.classList.toggle('hidden', t !== tab);
        setTabStyle(t, t === tab);
    });
    currentTab = tab;
}

function tryGoTab(tab) { switchMainTab(tab); }

// ── Variants event ──
document.addEventListener('variantsGenerated', function(e) {
    const rows = e.detail?.rows || [];
    if (!rows.length) return;
    const badge = document.getElementById('skuCountBadge');
    badge.textContent = rows.length;
    badge.classList.remove('hidden');
    renderSkuTable(rows);
});

function renderSkuTable(rows) {
    document.getElementById('skuTableSection').classList.remove('hidden');
    document.getElementById('estoqueEmpty').classList.add('hidden');
    const tbody = document.getElementById('skuTableBody');
    tbody.innerHTML = rows.map((row, idx) => {
        const hex   = (window.VM?.getHex || (()=>'#94a3b8'))(row.color);
        const light = ['#f8fafc','#f5f0e8','#d4b896'].includes(hex);
        const stock = parseInt(row.stock) || 0;
        const bc    = stock > 0 ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-600 border border-red-200';
        return `<tr class="hover:bg-violet-50/20 transition group">
            <td class="px-4 py-3"><div class="flex items-center gap-2"><span class="w-5 h-5 rounded-full flex-shrink-0 ${light?'border border-gray-300':''}" style="background:${hex}"></span><span class="font-semibold text-gray-800 text-sm">${row.color}</span></div></td>
            <td class="px-4 py-3"><span class="bg-gray-100 text-gray-700 font-bold text-xs px-2.5 py-1 rounded-lg">${row.size}</span></td>
            <td class="px-4 py-3"><input type="text" value="${row.sku||''}" onchange="updateSkuRow(${idx},'sku',this.value)" class="font-mono text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 w-32 focus:outline-none focus:ring-2 focus:ring-violet-400 bg-gray-50 uppercase tracking-wide"></td>
            <td class="px-4 py-3"><div class="flex items-center border border-gray-200 rounded-lg overflow-hidden w-28 focus-within:ring-2 focus-within:ring-violet-400"><span class="bg-gray-50 px-2 py-1.5 text-xs text-gray-400 font-semibold border-r border-gray-200 select-none">R$</span><input type="number" value="${row.price_override||row.price||''}" placeholder="0,00" step="0.01" min="0" onchange="updateSkuRow(${idx},'price_override',this.value)" class="flex-1 px-2 py-1.5 text-sm focus:outline-none bg-white w-0 min-w-0"></div></td>
            <td class="px-4 py-3"><input type="number" value="${stock}" min="0" oninput="updateSkuRow(${idx},'stock',parseInt(this.value)||0)" class="border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-violet-400 w-16"></td>
            <td class="px-4 py-3"><span id="skuBadge_${idx}" class="${bc} text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap">${stock>0?stock+' un.':'Esgotado'}</span></td>
            <td class="px-4 py-3"><button type="button" onclick="removeSkuRow(${idx})" class="opacity-0 group-hover:opacity-100 p-1.5 rounded-lg hover:bg-red-50 text-gray-300 hover:text-red-500 transition"><i class="fa-solid fa-trash-can text-xs"></i></button></td>
        </tr>`;
    }).join('');
    updateSkuFooter();
}

function updateSkuRow(idx, field, val) {
    if (!window.VM?.skuRows) return;
    VM.skuRows[idx][field] = val;
    if (field === 'stock') {
        const stock = parseInt(val)||0;
        const badge = document.getElementById(`skuBadge_${idx}`);
        if (badge) {
            badge.className = `${stock>0?'bg-emerald-100 text-emerald-700 border border-emerald-200':'bg-red-100 text-red-600 border border-red-200'} text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap`;
            badge.textContent = stock>0?stock+' un.':'Esgotado';
        }
    }
    updateSkuFooter();
}

function removeSkuRow(idx) {
    if (!window.VM?.skuRows) return;
    VM.skuRows.splice(idx,1);
    renderSkuTable(VM.skuRows);
}

function updateSkuFooter() {
    if (!window.VM?.skuRows) return;
    const total    = VM.skuRows.reduce((s,r)=>s+(parseInt(r.stock)||0),0);
    const esgotado = VM.skuRows.filter(r=>(parseInt(r.stock)||0)===0).length;
    document.getElementById('skuFooterSummary').innerHTML =
        `<span class="font-bold text-gray-700">${total}</span> unidades em estoque &nbsp;·&nbsp; <span class="font-bold ${esgotado>0?'text-red-500':'text-gray-700'}">${esgotado}</span> variante${esgotado!==1?'s':''} esgotada${esgotado!==1?'s':''}`;
}

// ── Profit calc ──
$('#price, #cost').on('blur', function() {
    const price = parseFloat($('#price').val().replace(/\./g,'').replace(',','.')) || 0;
    const cost  = parseFloat($('#cost').val().replace(/\./g,'').replace(',','.'))  || 0;
    if (cost > price && cost > 0) {
        $('#divCostGreaterPrice').removeClass('hidden');
        $('#msgCostGreaterPrice').text('Atenção: custo maior que o preço de venda!');
    } else { $('#divCostGreaterPrice').addClass('hidden'); }
    if (cost > 0 && price > 0) $('#profit').val(Math.floor((price-cost)/cost*100)+'%');
});

// ── doSave ──
function doSave() {
    const btn = document.getElementById('btnPrimaryAction');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Salvando...</span>';

    const formData = new FormData();
    ['brand_id','category_id','gender','name','description','price','price_wholesale',
     'price_promotional','cost','profit','color','installments','discount_pix',
     'weight','width','height','length','stock'].forEach(f => {
        const el = document.getElementById(f);
        if (el) formData.append(f, (el.value||'').replace('%',''));
    });
    currentFiles.forEach(id => formData.append('current-images[]', id));
    newsFiles.forEach(file => formData.append('news-images[]', file));
    deleteFiles.forEach(id => formData.append('delete-images[]', id));

    $.ajax({
        url: '{{ route('products.update', $product->id) }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function() {
            const variantRows = window.VM?.skuRows;
            const afterSave = () => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> <span>Salvar produto</span>';
                window.toast.success('Produto salvo com sucesso!');
            };
            if (variantRows && variantRows.length > 0) {
                fetch(`/products/{{ $product->id }}/variants/sync`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify({ variants: variantRows })
                }).finally(afterSave);
            } else {
                afterSave();
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> <span>Salvar produto</span>';
            if (xhr.status === 422) {
                const errors = xhr.responseJSON?.errors || {};
                Object.entries(errors).forEach(([field]) => {
                    const el = document.querySelector(`[name="${field}"]`);
                    if (el) el.classList.add('border-red-400');
                });
                switchMainTab('geral');
                window.toast.error('Corrija os erros no formulário.');
            } else {
                window.toast.error(xhr.responseJSON?.message || 'Erro ao salvar produto.');
            }
        }
    });
}

// ── Photo slider ──
const notificationSlider = document.getElementById("images-product-slider");
const closeButton        = document.getElementById("close-images-product-slider");
const backdrop           = document.getElementById("backdrop");
const inputImagesProduct = document.getElementById("label-for-images-product");
const labelForImagesProductEdit = $('.label-for-images-product-edit');

let imagesArray  = {!! json_encode($images) !!};
let newsFiles    = [];
let deleteFiles  = [];
let currentFiles = imagesArray.filter(i=>i.id).map(i=>i.id);

$(document).ready(function() {
    $('.price-mask').mask('000.000.000.000.000,00', { reverse: true });
    // Show existing images in preview slots
    const previews = document.querySelectorAll('.previewsImagesInProductPage');
    imagesArray.slice(0,4).forEach((img,i) => {
        if (previews[i] && img.url) {
            previews[i].src = '/storage/' + img.url.replace('public/','');
            previews[i].style.display = 'block';
        }
    });
    // Init profit
    const p = document.getElementById('profit');
    if (p && p.value && !p.value.includes('%')) p.value = p.value + '%';
});

$('.OpenModalImagesProducts').click(function(e) {
    e.preventDefault();
    $('#previewImages').empty();
    notificationSlider.classList.remove("translate-x-full");
    backdrop.classList.remove("hidden");
    if (imagesArray.length < 8) inputImagesProduct.click();
    if (imagesArray.length >= 8) labelForImagesProductEdit[0].style.display = 'none';

    imagesArray.forEach(image => {
        if (image.id) {
            $('#previewImages').append(`
                <div class="rounded-xl relative h-32 mb-2 overflow-hidden group" data-divimageid="${image.id}">
                    <button type="button" data-imageid="${image.id}" class="btn-remove-image-product absolute top-1.5 right-1.5 z-10 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs"><i class="fa-solid fa-xmark"></i></button>
                    <img src="/storage/${image.url.replace('public/','')}" class="w-full h-32 object-cover rounded-xl">
                </div>`);
        } else {
            $('#previewImages').append(`
                <div class="rounded-xl relative h-32 mb-2 overflow-hidden group" data-indexnewfile="${image.indexNewFile}" data-divimageid="${image.index}">
                    <button type="button" class="btn-remove-image-product absolute top-1.5 right-1.5 z-10 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs"><i class="fa-solid fa-xmark"></i></button>
                    <img src="${image.url}" class="w-full h-32 object-cover rounded-xl">
                </div>`);
        }
    });
});

const closeSlider = () => {
    const previews = document.querySelectorAll('.previewsImagesInProductPage');
    imagesArray.slice(0,4).forEach((img,i) => {
        if (previews[i]) {
            previews[i].src = img.id ? '/storage/'+img.url.replace('public/','') : img.url;
            previews[i].style.display = 'block';
        }
    });
    for (let i = imagesArray.length; i < 4; i++) { if (previews[i]) previews[i].style.display = 'none'; }
    notificationSlider.classList.add("translate-x-full");
    backdrop.classList.add("hidden");
};
$(closeButton).click(e => { e.preventDefault(); closeSlider(); });
$(backdrop).click(e => { e.preventDefault(); closeSlider(); });

$(inputImagesProduct).change(function(e) {
    const files = e.target.files;
    const remaining = 8 - imagesArray.length;
    const toProcess = Math.min(files.length, remaining);
    for (let i = 0; i < toProcess; i++) {
        const file = files[i];
        const reader = new FileReader();
        const fileFormatted = { id: null, url: null, index: imagesArray.length, indexNewFile: newsFiles.length };
        reader.onload = ev => {
            fileFormatted.url = ev.target.result;
            $('#previewImages').append(`
                <div class="rounded-xl relative h-32 mb-2 overflow-hidden group" data-indexnewfile="${fileFormatted.indexNewFile}" data-divimageid="${fileFormatted.index}">
                    <button type="button" class="btn-remove-image-product absolute top-1.5 right-1.5 z-10 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-xs"><i class="fa-solid fa-xmark"></i></button>
                    <img src="${ev.target.result}" class="w-full h-32 object-cover rounded-xl">
                </div>`);
        };
        imagesArray.push(fileFormatted);
        newsFiles.push(file);
        if (imagesArray.length >= 8) labelForImagesProductEdit[0].style.display = 'none';
        reader.readAsDataURL(file);
    }
    if (files.length > remaining) {
        const info = document.getElementById('info-max-images');
        info.textContent = `Apenas ${remaining} foto(s) adicionada(s). Limite: 8.`;
        info.style.display = 'block';
    }
});

$('#previewImages').on('click', '.btn-remove-image-product', function(e) {
    e.preventDefault();
    const imageId = $(this).data('imageid');
    if (imageId !== undefined) {
        imagesArray = imagesArray.filter(img => img.id !== imageId);
        deleteFiles.push(imageId);
        currentFiles = currentFiles.filter(id => id !== imageId);
    } else {
        const divId = $(this).closest('[data-divimageid]').data('divimageid');
        const nfIdx = $(this).closest('[data-indexnewfile]').data('indexnewfile');
        imagesArray = imagesArray.filter(img => img.index !== divId);
        newsFiles.splice(nfIdx, 1);
    }
    if (imagesArray.length < 8) {
        document.getElementById('info-max-images').style.display = 'none';
        labelForImagesProductEdit[0].style.display = 'block';
    }
    $(this).closest('[data-divimageid]').remove();
});

new Sortable(document.getElementById('previewImages'), {
    animation: 150, ghostClass: 'opacity-50',
    onSort() {
        const newOrder = [];
        $('#previewImages > div').each(function() {
            const divId = $(this).data('divimageid');
            const img = imagesArray.find(i => (i.id ?? i.index) === divId);
            if (img) newOrder.push(img);
        });
        imagesArray = newOrder;
    }
});
</script>
