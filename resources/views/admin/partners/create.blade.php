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
                            <span class="text-gray-600">Nova loja</span>
                        </nav>
                        <h1 class="font-semibold text-2xl text-gray-900 tracking-tight">{{ __('Nova loja (parceiro)') }}</h1>
                        <p class="text-sm text-gray-600 max-w-xl leading-relaxed">
                            Cadastre manualmente o titular, a loja e o plano. O parceiro recebe o código de verificação por
                            e-mail para o primeiro acesso. O link público do catálogo é gerado automaticamente a partir do
                            nome da loja (você pode ajustá-lo depois em <strong>Editar loja</strong>).
                        </p>
                    </div>
                    <a href="{{ route('partners.index') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                        Voltar à lista
                    </a>
                </div>

                @if (session('error'))
                    <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900"
                        role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($plans->isEmpty())
                    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950"
                        role="status">
                        Não há planos com status <strong>ativo</strong>.
                        <a href="{{ route('plans.index') }}"
                            class="font-semibold text-[#6A2BBA] hover:underline">Abrir cadastro de planos</a>
                        antes de criar uma loja.
                    </div>
                @endif

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

                <form action="{{ route('partners.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-6 space-y-5" novalidate>
                    @csrf

                    {{-- Titular --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="create-section-owner">
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
                                <h2 id="create-section-owner" class="text-base font-semibold text-gray-900">Titular da
                                    conta</h2>
                                <p class="text-xs text-gray-500">Credenciais de acesso ao painel da loja.</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-input-label for="create-name" :value="__('Nome completo')" />
                                <x-text-input id="create-name" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="name" type="text" :value="old('name')" required autocomplete="name" />
                                <x-input-error class="mt-1" :messages="$errors->get('name')" />
                            </div>
                            <div>
                                <x-input-label for="create-email" :value="__('E-mail')" />
                                <x-text-input id="create-email" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="email" type="email" :value="old('email')" required autocomplete="email" />
                                <x-input-error class="mt-1" :messages="$errors->get('email')" />
                            </div>
                            <div>
                                <x-input-label for="create-phone" :value="__('Telefone / WhatsApp')" />
                                <x-text-input id="create-phone"
                                    class="mt-1 block w-full phone-mask rounded-xl border-gray-300" name="phone"
                                    type="text" :value="old('phone')" required autocomplete="tel" />
                                <x-input-error class="mt-1" :messages="$errors->get('phone')" />
                            </div>
                            <div>
                                <x-input-label for="create-password" :value="__('Senha')" />
                                <x-text-input id="create-password" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="password" type="password" required autocomplete="new-password" />
                                <x-input-error class="mt-1" :messages="$errors->get('password')" />
                            </div>
                            <div>
                                <x-input-label for="create-password_confirmation" :value="__('Confirmar senha')" />
                                <x-text-input id="create-password_confirmation"
                                    class="mt-1 block w-full rounded-xl border-gray-300" name="password_confirmation"
                                    type="password" required autocomplete="new-password" />
                                <x-input-error class="mt-1" :messages="$errors->get('password_confirmation')" />
                            </div>
                        </div>
                    </section>

                    {{-- Identidade da loja --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="create-section-identity">
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
                                <h2 id="create-section-identity" class="text-base font-semibold text-gray-900">Identidade
                                    da loja</h2>
                                <p class="text-xs text-gray-500">Nome exibido na plataforma e base do link do catálogo.</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-input-label for="create-store_name" :value="__('Nome da loja')" />
                                <x-text-input id="create-store_name" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="store_name" type="text" :value="old('store_name')" required
                                    autocomplete="organization" />
                                <p class="mt-1 text-xs text-gray-500">
                                    O sistema cria um slug a partir deste nome (letras minúsculas e hífens). Se já existir
                                    outro igual, um sufixo numérico é adicionado automaticamente.
                                </p>
                                <x-input-error class="mt-1" :messages="$errors->get('store_name')" />
                            </div>
                        </div>
                    </section>

                    {{-- Plano, documentos e opções --}}
                    <section
                        class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm shadow-gray-900/[0.03] ring-1 ring-black/[0.02]"
                        aria-labelledby="create-section-plan">
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800"
                                aria-hidden="true">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </span>
                            <div>
                                <h2 id="create-section-plan" class="text-base font-semibold text-gray-900">Plano,
                                    assinatura e documentos</h2>
                                <p class="text-xs text-gray-500">Define cobrança, início da assinatura e comprovante
                                    opcional.</p>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-input-label for="create-plan_id" :value="__('Plano')" />
                                <select id="create-plan_id" name="plan_id" required @disabled($plans->isEmpty())
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2.5 px-3 border text-sm disabled:cursor-not-allowed disabled:bg-gray-100">
                                    <option value="">{{ __('Selecione…') }}</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" @selected((string) old('plan_id') === (string) $plan->id)>
                                            {{ $plan->name }} — R$ {{ number_format((float) $plan->price, 2, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('plan_id')" />
                            </div>
                            <div>
                                <x-input-label for="create-start_date" :value="__('Data de início')" />
                                <x-text-input id="create-start_date" class="mt-1 block w-full rounded-xl border-gray-300"
                                    name="start_date" type="date" :value="old('start_date', now()->format('Y-m-d'))"
                                    required />
                                <x-input-error class="mt-1" :messages="$errors->get('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="create-payment_method" :value="__('Forma de pagamento (opcional)')" />
                                <select id="create-payment_method" name="payment_method"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2.5 px-3 border text-sm">
                                    <option value="">{{ __('—') }}</option>
                                    <option value="pix" @selected(old('payment_method') === 'pix')>Pix</option>
                                    <option value="credit" @selected(old('payment_method') === 'credit')>Cartão</option>
                                    <option value="manual" @selected(old('payment_method') === 'manual')>Manual / outros</option>
                                </select>
                                <x-input-error class="mt-1" :messages="$errors->get('payment_method')" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="create-qtd" :value="__('Porte do catálogo (opcional)')" />
                                <select id="create-qtd" name="qtd_vehicles_in_stock"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2.5 px-3 border text-sm">
                                    <option value="">{{ __('—') }}</option>
                                    <option value="10-products" @selected(old('qtd_vehicles_in_stock') === '10-products')>Até 10 produtos</option>
                                    <option value="25-products" @selected(old('qtd_vehicles_in_stock') === '25-products')>Até 25 produtos</option>
                                    <option value="60-products" @selected(old('qtd_vehicles_in_stock') === '60-products')>Até 60 produtos</option>
                                    <option value="plus-products" @selected(old('qtd_vehicles_in_stock') === 'plus-products')>Mais de 60 produtos</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="create-store_cpf_cnpj" :value="__('CPF / CNPJ (opcional)')" />
                                <x-text-input id="create-store_cpf_cnpj"
                                    class="mt-1 block w-full cpf-cnpj-mask rounded-xl border-gray-300" name="store_cpf_cnpj"
                                    type="text" :value="old('store_cpf_cnpj')" />
                                <x-input-error class="mt-1" :messages="$errors->get('store_cpf_cnpj')" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="create-manual_receipt" :value="__('Comprovante de pagamento (opcional)')" />
                                <input id="create-manual_receipt" name="manual_receipt" type="file"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-xl file:border-0 file:bg-[#6A2BBA]/10 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-[#6A2BBA] hover:file:bg-[#6A2BBA]/15" />
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG ou PDF, até 5 MB.</p>
                                <x-input-error class="mt-1" :messages="$errors->get('manual_receipt')" />
                            </div>
                        </div>

                        <div
                            class="mt-5 flex flex-col gap-3 rounded-xl bg-gray-50/80 px-4 py-3 border border-gray-100 md:col-span-2">
                            <input type="hidden" name="grant_access" value="0" />
                            <div class="flex items-start gap-3">
                                <input id="create-grant_access" type="checkbox" name="grant_access" value="1"
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                                    @checked(old('grant_access', true)) />
                                <div>
                                    <label for="create-grant_access" class="text-sm font-medium text-gray-900">Liberar
                                        acesso ao sistema</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Assinatura fica <strong>ativa</strong> e o
                                        e-mail é tratado como verificado; o parceiro entra direto após o cadastro.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 border-t border-gray-200/80 pt-3">
                                <input id="create-is_testing" name="is_testing" type="checkbox" value="1"
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-[#6A2BBA] focus:ring-[#6A2BBA]"
                                    @checked(old('is_testing')) />
                                <div>
                                    <label for="create-is_testing" class="text-sm font-medium text-gray-900">Conta em
                                        modo teste</label>
                                    <p class="text-xs text-gray-500 mt-0.5">Indica loja de demonstração ou homologação.</p>
                                </div>
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
                        <button type="submit" @disabled($plans->isEmpty())
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] px-6 text-sm font-semibold text-white shadow-lg shadow-[#6A2BBA]/25 hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50">
                            Salvar cadastro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
</x-app-layout>
