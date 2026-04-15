<section class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    <header class="mb-4 flex gap-2 items-center">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-solid fa-store text-sm"></i>
        </div>
        <h2 class="font-semibold text-xl text-gray-800">Definições da Loja</h2>
    </header>

    @if($errors->has('logo') || $errors->has('banner'))
        <div class="bg-red-50 text-sm text-red-600 rounded-xl p-3 mb-4 border border-red-200 shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->get('logo') as $message)
                    <li>{{ $message }}</li>
                @endforeach
                @foreach ($errors->get('banner') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('store.update', $store->id) }}" class="mt-2 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            $acceptedPaymentMethods = (array) old('accepted_payment_methods', $store->accepted_payment_methods ?? []);
            $acceptedCardBrands = (array) old('accepted_card_brands', $store->accepted_card_brands ?? []);
            $wholesaleCountMode = old('wholesale_count_mode', $store->wholesale_count_mode?->value ?? 'cart');

            $existingWholesaleLevels = old(
                'wholesale_levels',
                $store->wholesaleLevels->map(fn ($level) => ['min_quantity' => $level->min_quantity])->values()->all()
            );
            if ($existingWholesaleLevels === []) {
                $existingWholesaleLevels = [];
            }
            $paymentMethodOptions = [
                'pix' => ['label' => 'Pix', 'hint' => 'Pagamento instantaneo'],
                'cash' => ['label' => 'Dinheiro', 'hint' => 'Pagamento no ato'],
                'credit_card' => ['label' => 'Cartao de credito', 'hint' => 'Parcelado ou a vista'],
                'debit_card' => ['label' => 'Cartao de debito', 'hint' => 'Aprovacao imediata'],
                'boleto' => ['label' => 'Boleto', 'hint' => 'Pagamento bancario'],
            ];
            $cardBrandOptions = [
                'visa' => 'Visa',
                'mastercard' => 'Mastercard',
                'elo' => 'Elo',
                'amex' => 'American Express',
                'hipercard' => 'Hipercard',
                'diners' => 'Diners Club',
            ];
        @endphp

        {{-- Banner & Logo Section --}}
        <div>
            <div class="text-sm font-semibold text-gray-700 mb-2">Imagens da Loja</div>
            <div class="flex flex-col md:flex-row gap-6">
                {{-- Banner Upload --}}
                <div class="w-full md:w-2/3">
                    <label for="banner-store" class="block w-full h-32 md:h-40 border-2 border-dashed border-sky-500 rounded-xl cursor-pointer bg-blue-50 text-gray-600 relative overflow-hidden group transition">
                        <div id="div-banner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center {{ $bannerStore && $bannerStore != '/storage/' ? 'hidden' : '' }}">
                            <i class="fa-regular fa-image text-3xl mb-2 text-sky-400 group-hover:text-blue-600 transition"></i>
                            <span class="text-sm font-semibold text-center text-sky-600 group-hover:text-blue-700 transition">Fazer upload do Banner</span>
                        </div>
                        <img id="storeBannerPreview" src="{{ $bannerStore && $bannerStore != '/storage/' ? $bannerStore : '' }}" class="w-full h-full object-cover {{ $bannerStore && $bannerStore != '/storage/' ? '' : 'hidden' }}">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="text-white font-medium text-sm flex gap-2 items-center"><i class="fa-solid fa-pen"></i> Editar Banner</span>
                        </div>
                    </label>
                    <input type="file" class="hidden" id="banner-store" name="banner" accept="image/*" onchange="previewImageStore(event, 'storeBannerPreview', 'div-banner-placeholder');">
                </div>

                {{-- Logo Upload --}}
                <div class="w-full md:w-1/3 flex justify-center">
                    <label for="logo" class="w-32 h-32 md:w-40 md:h-40 border-2 border-dashed border-sky-500 rounded-full cursor-pointer bg-blue-50 text-gray-600 relative overflow-hidden flex-shrink-0 group transition">
                        <div id="div-logo-placeholder" class="absolute inset-0 flex flex-col items-center justify-center {{ $logoStore && $logoStore != '/storage/' ? 'hidden' : '' }}">
                            <i class="fa-solid fa-camera text-2xl mb-1 text-sky-400 group-hover:text-blue-600 transition"></i>
                            <span class="text-xs font-semibold text-center text-sky-600 group-hover:text-blue-700 transition">Logo</span>
                        </div>
                        <img id="logoStorePreview" src="{{ $logoStore && $logoStore != '/storage/' ? $logoStore : '' }}" class="w-full h-full object-cover {{ $logoStore && $logoStore != '/storage/' ? '' : 'hidden' }}">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="text-white font-medium text-xs flex gap-1 items-center"><i class="fa-solid fa-pen"></i> Editar</span>
                        </div>
                    </label>
                    <input type="file" id="logo" class="hidden" name="logo" accept="image/*" onchange="previewImageStore(event, 'logoStorePreview', 'div-logo-placeholder');">
                </div>
            </div>
        </div>

        {{-- Form Fields Grid --}}
        <div>
            <div class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-100 pb-2">Informações Básicas</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="store_name" :value="__('Nome da loja *')" />
                    <x-text-input id="store_name" placeholder="Minha Loja" name="store_name" type="text" class="mt-1 block w-full required bg-gray-50 bg-opacity-50" :value="old('store_name', $store->store_name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('E-mail principal *')" />
                    <x-text-input id="email" placeholder="minhaloja@mail.com" name="store_email" type="email" class="mt-1 block w-full required bg-gray-50 bg-opacity-50" :value="old('store_email', $store->store_email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('store_email')" />
                </div>
                <div>
                    <x-input-label for="store_phone" :value="__('Telefone (WhatsApp) *')" />
                    <x-text-input id="store_phone" name="store_phone" type="text" class="mt-1 block w-full phone-mask required bg-gray-50 bg-opacity-50" :value="old('store_phone', $store->store_phone)" placeholder="(00) 00000-0000" required />
                    <x-input-error class="mt-2" :messages="$errors->get('store_phone')" />
                </div>  
                <div>
                    <x-input-label for="store_cpf_cnpj" :value="__('CPF/CNPJ *')" />
                    <x-text-input id="store_cpf_cnpj" name="store_cpf_cnpj" type="text" class="mt-1 block w-full cpf-cnpj-mask required bg-gray-50 bg-opacity-50" :value="old('store_cpf_cnpj', $store->store_cpf_cnpj)" placeholder="XX.XXX.XXX/0001-XX" required />
                    <x-input-error class="mt-2" :messages="$errors->get('store_cpf_cnpj')" />
                </div>
            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-100 pb-2">Configuração de atacado</div>

            <button type="button" id="btnAddWholesaleLevel" class="inline-flex items-center gap-2 mb-3 rounded-xl border border-blue-200 bg-white px-3 py-2 text-sm font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-50">
                <i class="fa-solid fa-plus text-xs"></i>
                Adicionar atacado
            </button>
            <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-2 md:p-5">
                {{-- <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Como o atacado será contado?</h3>
                        <p class="mt-1 max-w-2xl text-xs text-gray-500">Escolha se o desconto deve considerar a quantidade total do mesmo produto ou a soma de peças do carrinho inteiro.</p>
                    </div>
                    <div class="inline-flex rounded-2xl border border-gray-200 bg-white p-1">
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold">
                            <input type="radio" name="wholesale_count_mode" value="product" @checked($wholesaleCountMode === 'product')>
                            Por peça (produto)
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold">
                            <input type="radio" name="wholesale_count_mode" value="cart" @checked($wholesaleCountMode === 'cart')>
                            Por múltiplas peças (carrinho)
                        </label>
                    </div>
                </div> --}}

                {{-- <div class="mt-2 grid grid-cols-1 gap-2">
                    <div class="rounded-2xl border p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-700">Por peça (produto)</p>
                        <p class="mt-2 text-sm text-gray-800">Ativa o atacado quando o cliente atingir a quantidade mínima somando as variantes do mesmo produto.</p>
                        <p class="mt-2 text-xs text-gray-500">Ex.: 5 camisetas P + 5 camisetas M = 10 peças do mesmo produto.</p>
                    </div>
                    <div class="rounded-2xl border p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-700">Por múltiplas peças (carrinho)</p>
                        <p class="mt-2 text-sm text-gray-800">Ativa o atacado quando o carrinho alcançar a quantidade mínima total, independente dos produtos.</p>
                        <p class="mt-2 text-xs text-gray-500">Ex.: 5 camisetas + 5 bermudas = 10 peças no carrinho.</p>
                    </div>
                </div> --}}

               

                <div class="flex justify-between gap-3">
                    <div class="w-1/2">
                        <h3 class="text-sm font-semibold text-gray-800">Níveis de atacado</h3>
                        <p class="mt-1 text-xs text-gray-500 hidden md:block">Adicione quantos níveis quiser. Os labels são gerados automaticamente como Atacado 1, Atacado 2, Atacado 3...</p>
                    </div>
                    
                </div>

                <div id="wholesaleLevelsContainer" class="mt-4 space-y-3"></div>

                <template id="wholesaleLevelTemplate">
                    <div class="wholesale-level-card rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="wholesale-level-title text-sm font-semibold text-gray-800">Atacado 1</p>
                                <p class="mt-1 text-xs text-gray-500">Informe a quantidade mínima para liberar este nível no catálogo.</p>
                            </div>
                            <button type="button" class="btn-remove-wholesale-level rounded-xl border border-red-100 p-2 text-red-500 transition hover:bg-red-50" aria-label="Remover nível de atacado">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <x-input-label :value="__('Quantidade mínima de peças')" />
                            <input type="number" min="2" step="1" class="wholesale-min-quantity-input mt-1 block w-full rounded-md border-gray-300 bg-gray-50 bg-opacity-50 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: 10">
                        </div>
                    </div>
                </template>

                <div id="wholesaleLevelsEmptyState" class="{{ $existingWholesaleLevels !== [] ? 'hidden' : '' }} rounded-2xl border border-dashed border-gray-300 bg-white/70 px-4 py-6 text-center">
                    <p class="text-sm font-semibold text-gray-700">Nenhum atacado configurado</p>
                    <p class="mt-1 text-xs text-gray-500">Adicione o primeiro nível para liberar preços de atacado no cadastro de produtos.</p>
                </div>

                <x-input-error class="mt-3" :messages="$errors->get('wholesale_levels')" />
                <x-input-error class="mt-2" :messages="$errors->get('wholesale_levels.*.min_quantity')" />
            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-100 pb-2">Endereço Geográfico</div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-3">
                    <x-input-label for="zip_code" :value="__('CEP')" />
                    <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full cep-mask bg-gray-50 bg-opacity-50" :value="old('zip_code', optional($store->addressStore)->zip_code)" placeholder="00000-000" />
                    <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
                </div>
                <div class="md:col-span-6">
                    <x-input-label for="street" :value="__('Avenida/Rua')" />
                    <x-text-input id="street" name="street" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('street', optional($store->addressStore)->street)" placeholder="Ex: Av. Brasil" />
                    <x-input-error class="mt-2" :messages="$errors->get('street')" />
                </div>
                <div class="md:col-span-3">
                    <x-input-label for="number" :value="__('Número')" />
                    <x-text-input id="number" name="number" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('number', optional($store->addressStore)->number)" placeholder="1234" />
                    <x-input-error class="mt-2" :messages="$errors->get('number')" />
                </div>
                <div class="md:col-span-6">
                    <x-input-label for="neighborhood" :value="__('Bairro')" />
                    <x-text-input id="neighborhood" name="neighborhood" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('neighborhood', optional($store->addressStore)->neighborhood)" placeholder="Centro" />
                    <x-input-error class="mt-2" :messages="$errors->get('neighborhood')" />
                </div>
                <div class="md:col-span-4">
                    <x-input-label for="city" :value="__('Cidade')" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('city', optional($store->addressStore)->city)" placeholder="São Paulo" />
                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="state" :value="__('Estado')" />
                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full bg-gray-50 bg-opacity-50" :value="old('state', optional($store->addressStore)->state)" placeholder="SP" />
                    <x-input-error class="mt-2" :messages="$errors->get('state')" />
                </div>
            </div>
        </div>

        <div>
            <div class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-100 pb-2">Pagamentos aceitos na vitrine</div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">Formas de pagamento</h3>
                            <p class="mt-1 text-xs text-gray-500">Essas informacoes aparecerao no final do catalogo.</p>
                        </div>
                        <div class="rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                            Vitrine
                        </div>
                    </div>

                    <div class="mt-2 grid grid-cols-1 gap-2">
                        @foreach ($paymentMethodOptions as $value => $option)
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 bg-white px-2 py-1 transition hover:border-blue-300 hover:bg-blue-50/50">
                                <input type="checkbox" name="accepted_payment_methods[]" value="{{ $value }}"
                                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    @checked(in_array($value, $acceptedPaymentMethods, true))>
                                <span class="flex-1">
                                    <span class="block text-sm font-semibold text-gray-800">{{ $option['label'] }}</span>
                                    <span class="mt-0.5 block text-xs text-gray-500">{{ $option['hint'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <x-input-error class="mt-3" :messages="$errors->get('accepted_payment_methods')" />
                    <x-input-error class="mt-2" :messages="$errors->get('accepted_payment_methods.*')" />
                </div>

                <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Bandeiras de cartao</h3>
                        <p class="mt-1 text-xs text-gray-500">Selecione as bandeiras que sua loja trabalha.</p>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach ($cardBrandOptions as $value => $label)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-gray-200 bg-white px-2 py-1 transition hover:border-blue-300 hover:bg-blue-50/50">
                                <input type="checkbox" name="accepted_card_brands[]" value="{{ $value }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    @checked(in_array($value, $acceptedCardBrands, true))>
                                <span class="text-sm font-semibold text-gray-800">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <x-input-error class="mt-3" :messages="$errors->get('accepted_card_brands')" />
                    <x-input-error class="mt-2" :messages="$errors->get('accepted_card_brands.*')" />
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-end items-center gap-4 mt-2 pt-3">
            <x-primary-button id="btnSaveInfoStore" class="w-full justify-center md:w-auto px-6 py-2.5 shadow-md">
                {{ __('Salvar Modificações') }}
            </x-primary-button>
        </div>
    </form>
</section>

<script>
    const wholesaleLevelsState = @json($existingWholesaleLevels);

    function previewImageStore(event, previewId, placeholderId) {
        const input = event.target;
        const file = input.files[0];
        const maxSizeMB = 2;

        if(file) {
            const fileSizeMB = file.size / (1024 * 1024);
            if(fileSizeMB > maxSizeMB) {
                if(typeof toastr !== 'undefined') toastr.error(`A imagem excede o tamanho máximo permitido de ${maxSizeMB}MB`);
                input.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function createWholesaleLevelCard(level = {}) {
        const template = document.getElementById('wholesaleLevelTemplate');
        const container = document.getElementById('wholesaleLevelsContainer');
        if (!template || !container) {
            return null;
        }

        const card = template.content.firstElementChild.cloneNode(true);
        const input = card.querySelector('.wholesale-min-quantity-input');
        const removeButton = card.querySelector('.btn-remove-wholesale-level');

        if (input) {
            input.value = level.min_quantity ?? '';
            input.addEventListener('input', refreshWholesaleLevelOrder);
        }

        if (removeButton) {
            removeButton.addEventListener('click', function () {
                card.remove();
                refreshWholesaleLevelOrder();
            });
        }

        container.appendChild(card);

        return card;
    }

    function refreshWholesaleLevelOrder() {
        const cards = Array.from(document.querySelectorAll('.wholesale-level-card'));
        const emptyState = document.getElementById('wholesaleLevelsEmptyState');

        cards.forEach((card, index) => {
            const levelIndex = index + 1;
            const title = card.querySelector('.wholesale-level-title');
            const input = card.querySelector('.wholesale-min-quantity-input');

            if (title) {
                title.textContent = `Atacado ${levelIndex}`;
            }

            if (input) {
                input.name = `wholesale_levels[${index}][min_quantity]`;
                input.id = `wholesale_levels_${index}_min_quantity`;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', cards.length > 0);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const addButton = document.getElementById('btnAddWholesaleLevel');
        const levels = Array.isArray(wholesaleLevelsState) ? wholesaleLevelsState : [];

        levels.forEach((level) => createWholesaleLevelCard(level));
        refreshWholesaleLevelOrder();

        if (addButton) {
            addButton.addEventListener('click', function () {
                createWholesaleLevelCard();
                refreshWholesaleLevelOrder();
            });
        }
    });
</script>
