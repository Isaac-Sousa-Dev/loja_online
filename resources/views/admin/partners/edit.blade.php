@php
    $partner = $user->partner;
    $store = $partner->store;
    $subscription = $partner->subscription;
    $address = $store->addressStore;
    $subStart = $subscription?->start_date !== null
        ? \Illuminate\Support\Carbon::parse((string) $subscription->start_date)->format('Y-m-d')
        : now()->format('Y-m-d');
    $subEnd = $subscription?->end_date !== null
        ? \Illuminate\Support\Carbon::parse((string) $subscription->end_date)->format('Y-m-d')
        : '';
@endphp

<x-app-layout>
    @section('content')
        <div class="p-2 flex md:justify-center">
            <div class="md:flex md:max-w-[960px] flex-col w-full ml-2 mr-2 pb-16">
                {{-- Cabeçalho --}}
                <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="space-y-1">
                        <nav class="text-xs font-medium text-gray-500" aria-label="Navegação secundária">
                            <a href="{{ route('partners.index') }}"
                                class="text-[#6A2BBA] hover:underline">Lojas (parceiros)</a>
                            <span class="mx-1 text-gray-300" aria-hidden="true">/</span>
                            <span class="text-gray-600">Editar</span>
                        </nav>
                        <h1 class="font-semibold text-2xl text-gray-900 tracking-tight">Editar loja</h1>
                        <p class="text-sm text-gray-600 max-w-xl leading-relaxed">
                            Atualize o titular, dados da vitrine, assinatura e endereço. A senha só é alterada se você
                            preencher os dois campos. O link público define o endereço do catálogo.
                        </p>
                    </div>
                    <a href="{{ route('partners.index') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                        Voltar à lista
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900"
                        role="alert">
                        <p class="font-semibold">Corrija os campos abaixo e tente novamente.</p>
                        <ul class="mt-2 list-disc space-y-0.5 pl-5">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('partners.update', $user->id) }}" method="POST" class="mt-6 space-y-5"
                    novalidate>
                    @method('PUT')
                    @csrf

                    {{-- Titular --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="edit-section-owner">
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#6A2BBA] to-[#D131A3] text-white shadow-md shadow-[#6A2BBA]/25"
                                aria-hidden="true">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </span>
                            <div>
                                <h2 id="edit-section-owner" class="text-base font-semibold text-gray-900">Titular da
                                    conta</h2>
                                <p class="text-xs text-gray-500">Usado para login no painel da loja.</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-input-label for="edit-name" :value="__('Nome completo')" />
                                <x-text-input id="edit-name" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="name" type="text" :value="old('name', $user->name)" required autocomplete="name" />
                            </div>
                            <div>
                                <x-input-label for="edit-email" :value="__('E-mail')" />
                                <x-text-input id="edit-email" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="email" type="email" :value="old('email', $user->email)" required
                                    autocomplete="email" />
                            </div>
                            <div>
                                <x-input-label for="edit-phone" :value="__('Telefone / WhatsApp')" />
                                <x-text-input id="edit-phone" class="mt-1 block w-full phone-mask rounded-xl border-gray-300"
                                    name="phone" type="text" :value="old('phone', $user->phone)" autocomplete="tel" />
                                <p class="mt-1 text-xs text-gray-500">Opcional. Apenas números também são aceitos.</p>
                            </div>
                            <div>
                                <x-input-label for="edit-password" :value="__('Nova senha (opcional)')" />
                                <x-text-input id="edit-password" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="password" type="password" autocomplete="new-password" />
                            </div>
                            <div>
                                <x-input-label for="edit-password_confirmation" :value="__('Confirmar nova senha')" />
                                <x-text-input id="edit-password_confirmation"
                                    class="mt-1 block w-full rounded-xl border-gray-300" name="password_confirmation"
                                    type="password" autocomplete="new-password" />
                            </div>
                        </div>
                    </section>

                    {{-- Identidade da loja --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="edit-section-identity">
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-700"
                                aria-hidden="true">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H20m-9.5 0H9.5" />
                                </svg>
                            </span>
                            <div>
                                <h2 id="edit-section-identity" class="text-base font-semibold text-gray-900">Identidade
                                    e catálogo</h2>
                                <p class="text-xs text-gray-500">Link usado na URL pública da vitrine.</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-input-label for="edit-store_name" :value="__('Nome da loja')" />
                                <x-text-input id="edit-store_name" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="store_name" type="text" :value="old('store_name', $store->store_name)" required />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="edit-partner_link" :value="__('Link público (slug)')" />
                                <x-text-input id="edit-partner_link" class="mt-1 block w-full rounded-xl border-gray-300 font-mono text-sm"
                                    name="partner_link" type="text" :value="old('partner_link', $partner->partner_link)"
                                    placeholder="ex.: studio-vitrine" autocomplete="off" />
                                <p class="mt-1 text-xs text-gray-500">
                                    @if ($partner->partner_link)
                                        Catálogo atual:
                                        <a href="{{ route('catalog.index', ['partner_link' => $partner->partner_link]) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="font-medium text-[#6A2BBA] hover:underline">abrir vitrine</a>
                                    @else
                                        Sem link definido — o catálogo pode ficar indisponível até configurar um slug.
                                    @endif
                                </p>
                            </div>
                            <div class="md:col-span-2 flex items-start gap-3 rounded-xl bg-gray-50/80 px-4 py-3 border border-gray-100">
                                <input id="edit-is_testing" name="is_testing" type="checkbox" value="1"
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                                    @checked(old('is_testing', $partner->is_testing)) />
                                <div>
                                    <label for="edit-is_testing" class="text-sm font-medium text-gray-900">Conta em modo
                                        teste</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Marque se a loja for apenas para
                                        demonstração ou homologação.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Loja / contato --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="edit-section-store">
                        <h2 id="edit-section-store" class="text-base font-semibold text-gray-900 mb-4">Contato da loja
                        </h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <x-input-label for="edit-store_email" :value="__('E-mail da loja')" />
                                <x-text-input id="edit-store_email" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="store_email" type="email" :value="old('store_email', $store->store_email)"
                                    autocomplete="off" />
                            </div>
                            <div>
                                <x-input-label for="edit-store_phone" :value="__('Telefone da loja')" />
                                <x-text-input id="edit-store_phone"
                                    class="mt-1 block w-full phone-mask rounded-xl border-gray-300" name="store_phone"
                                    type="text" :value="old('store_phone', $store->store_phone)" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="edit-store_cpf_cnpj" :value="__('CPF / CNPJ')" />
                                <x-text-input id="edit-store_cpf_cnpj" class="mt-1 block w-full cpf-cnpj-mask rounded-xl border-gray-300"
                                    name="store_cpf_cnpj" type="text" :value="old('store_cpf_cnpj', $store->store_cpf_cnpj)" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="edit-qtd" :value="__('Porte do catálogo')" />
                                <select id="edit-qtd" name="qtd_products_in_stock"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2.5 px-3 border text-sm">
                                    <option value="">{{ __('—') }}</option>
                                    <option value="10-products" @selected(old('qtd_products_in_stock', $store->qtd_products_in_stock) === '10-products')>Até 10 produtos</option>
                                    <option value="25-products" @selected(old('qtd_products_in_stock', $store->qtd_products_in_stock) === '25-products')>Até 25 produtos</option>
                                    <option value="60-products" @selected(old('qtd_products_in_stock', $store->qtd_products_in_stock) === '60-products')>Até 60 produtos</option>
                                    <option value="plus-products" @selected(old('qtd_products_in_stock', $store->qtd_products_in_stock) === 'plus-products')>Mais de 60 produtos</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- Assinatura --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="edit-section-sub">
                        <h2 id="edit-section-sub" class="text-base font-semibold text-gray-900 mb-4">Plano e assinatura
                        </h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-input-label for="edit-plan_id" :value="__('Plano')" />
                                <select id="edit-plan_id" name="plan_id" required
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2.5 px-3 border text-sm">
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" @selected((string) old('plan_id', $store->plan_id) === (string) $plan->id)>
                                            {{ $plan->name }} — R$ {{ number_format((float) $plan->price, 2, ',', '.') }}
                                            @if ($plan->status !== 'active')
                                                ({{ $plan->status }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="edit-subscription_status" :value="__('Status da assinatura')" />
                                <select id="edit-subscription_status" name="subscription_status" required
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2.5 px-3 border text-sm">
                                    <option value="active" @selected(old('subscription_status', $subscription?->status) === 'active')>Ativa</option>
                                    <option value="pending" @selected(old('subscription_status', $subscription?->status) === 'pending')>Pendente</option>
                                    <option value="cancelled" @selected(old('subscription_status', $subscription?->status) === 'cancelled')>Cancelada</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="edit-payment_method" :value="__('Forma de pagamento')" />
                                <select id="edit-payment_method" name="payment_method"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm py-2.5 px-3 border text-sm">
                                    <option value="">{{ __('—') }}</option>
                                    <option value="pix" @selected(old('payment_method', $subscription?->payment_method) === 'pix')>Pix</option>
                                    <option value="credit" @selected(old('payment_method', $subscription?->payment_method) === 'credit')>Cartão</option>
                                    <option value="manual" @selected(old('payment_method', $subscription?->payment_method) === 'manual')>Manual / outros</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="edit-start_date" :value="__('Início')" />
                                <x-text-input id="edit-start_date" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="start_date" type="date"
                                    :value="old('start_date', $subStart)"
                                    required />
                            </div>
                            <div>
                                <x-input-label for="edit-end_date" :value="__('Término (opcional)')" />
                                <x-text-input id="edit-end_date" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="end_date" type="date"
                                    :value="old('end_date', $subEnd)" />
                            </div>
                        </div>
                    </section>

                    {{-- Endereço --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="edit-section-address">
                        <h2 id="edit-section-address" class="text-base font-semibold text-gray-900 mb-4">Endereço da loja
                        </h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <x-input-label for="edit-zip" :value="__('CEP')" />
                                <x-text-input id="edit-zip" class="mt-1 block w-full cep-mask rounded-xl border-gray-300"
                                    name="zip_code" type="text" :value="old('zip_code', $address?->zip_code)" />
                            </div>
                            <div>
                                <x-input-label for="edit-state" :value="__('UF')" />
                                <x-text-input id="edit-state" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="state" type="text" :value="old('state', $address?->state)" maxlength="2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="edit-street" :value="__('Logradouro')" />
                                <x-text-input id="edit-street" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="street" type="text" :value="old('street', $address?->street)" />
                            </div>
                            <div>
                                <x-input-label for="edit-number" :value="__('Número')" />
                                <x-text-input id="edit-number" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="number" type="text" :value="old('number', $address?->number)" />
                            </div>
                            <div>
                                <x-input-label for="edit-neighborhood" :value="__('Bairro')" />
                                <x-text-input id="edit-neighborhood" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="neighborhood" type="text" :value="old('neighborhood', $address?->neighborhood)" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="edit-city" :value="__('Cidade')" />
                                <x-text-input id="edit-city" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="city" type="text" :value="old('city', $address?->city)" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="edit-country" :value="__('País')" />
                                <x-text-input id="edit-country" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="country" type="text" :value="old('country', $address?->country ?? 'Brasil')" />
                            </div>
                        </div>
                    </section>

                    {{-- Acesso ao painel --}}
                    <section
                        class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50/90 to-white p-3 shadow-sm ring-1 ring-amber-100/80"
                        aria-labelledby="edit-section-access">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 id="edit-section-access" class="text-base font-semibold text-gray-900">Acesso ao painel
                                </h2>
                                <p class="text-xs text-gray-600 mt-1 max-w-md leading-relaxed">
                                    Quando suspenso, titular e equipe de vendas não conseguem usar o painel (ex. inadimplência).
                                    O mesmo controle está disponível na lista de lojas, no painel lateral.
                                </p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0 rounded-xl bg-white/80 px-3 py-2 border border-amber-100">
                                <input id="edit-panel_suspended" name="panel_suspended" type="checkbox" value="1"
                                    class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500"
                                    @checked(old('panel_suspended', $store->suspended_at !== null)) />
                                <label for="edit-panel_suspended" class="text-sm font-semibold text-gray-900">Suspender
                                    painel manualmente</label>
                            </div>
                        </div>
                    </section>

                    {{-- Ações --}}
                    <div
                        class="sticky bottom-0 z-10 -mx-2 flex flex-col gap-3 border-t border-gray-200/80 bg-[#F8F9FC]/95 px-2 py-4 backdrop-blur-sm sm:flex-row sm:justify-end md:static md:border-0 md:bg-transparent md:p-0 md:backdrop-blur-none">
                        <a href="{{ route('partners.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 bg-white px-5 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] px-6 text-sm font-semibold text-white shadow-lg shadow-[#6A2BBA]/25 hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
</x-app-layout>
