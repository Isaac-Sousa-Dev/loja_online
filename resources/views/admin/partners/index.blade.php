<x-app-layout>
    @section('content')
        <script>
            function partnerDrawer() {
                return {
                    open: false,
                    loading: false,
                    error: null,
                    payload: null,
                    title: 'Detalhes da loja',
                    currentPartnerId: null,
                    get suspendUrl() {
                        return this.currentPartnerId ? `{{ url('/partners') }}/${this.currentPartnerId}/suspend` : '#';
                    },
                    get reactivateUrl() {
                        return this.currentPartnerId ? `{{ url('/partners') }}/${this.currentPartnerId}/reactivate` : '#';
                    },
                    moneyBr(n) {
                        const v = Number(n) || 0;
                        return new Intl.NumberFormat('pt-BR', {
                            style: 'currency',
                            currency: 'BRL'
                        }).format(v);
                    },
                    formatAddress(a) {
                        if (!a) return '—';
                        const parts = [a.street, a.number, a.neighborhood, a.city, a.state, a.zip_code].filter(Boolean);
                        return parts.join(', ') || '—';
                    },
                    async openDrawer(partnerId) {
                        this.currentPartnerId = partnerId;
                        this.open = true;
                        this.loading = true;
                        this.error = null;
                        this.payload = null;
                        this.title = 'Detalhes da loja';
                        try {
                            const res = await fetch(`{{ url('/partners') }}/${partnerId}/drawer`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            if (!res.ok) throw new Error();
                            this.payload = await res.json();
                            this.title = this.payload.store?.store_name || 'Loja';
                        } catch (e) {
                            this.error = 'Não foi possível carregar os detalhes.';
                        } finally {
                            this.loading = false;
                        }
                    },
                    closeDrawer() {
                        this.open = false;
                    }
                };
            }
        </script>
        <div class="p-2 flex md:justify-center"
            x-data="partnerDrawer()"
            @keydown.escape.window="closeDrawer()">
            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-start mt-4">
                    <div>
                        <h2 class="font-semibold text-2xl text-gray-800">{{ __('Lojas (parceiros)') }}</h2>
                        <p class="text-sm text-gray-600 mt-1 max-w-2xl">
                            Gerencie lojas, assinaturas e suspensão manual por inadimplência. Clique em uma linha para ver
                            dados da loja, faturamento do mês e usuários vinculados.
                        </p>
                    </div>
                    <a href="{{ route('partners.create') }}"
                        class="inline-flex shrink-0 items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 shadow-md shadow-[#6A2BBA]/20">
                        {{ __('Nova loja') }}
                    </a>
                </div>

                @if (session('success'))
                    <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900"
                        role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900"
                        role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="get" action="{{ route('partners.index') }}"
                    class="mt-5 flex flex-col lg:flex-row lg:flex-wrap lg:items-end gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
                    role="search"
                    aria-label="Filtros de lojas">
                    <div class="flex-1 min-w-[200px]">
                        <label for="partner-q" class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                        <input id="partner-q" type="search" name="q" value="{{ $filterQ }}"
                            placeholder="Loja, titular ou e-mail"
                            class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2 px-3 border"
                            autocomplete="off">
                    </div>
                    <div class="w-full sm:w-auto sm:min-w-[220px]">
                        <label for="store-status" class="block text-xs font-medium text-gray-600 mb-1">Situação da
                            loja</label>
                        <select id="store-status" name="store_status"
                            class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2 px-3 border">
                            <option value="all" @selected($filterStoreStatus === 'all')>Todas</option>
                            <option value="operational" @selected($filterStoreStatus === 'operational')>Em operação (não suspensas)</option>
                            <option value="suspended_manual" @selected($filterStoreStatus === 'suspended_manual')>Suspensas manualmente</option>
                        </select>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-xl bg-[#6A2BBA] px-5 text-sm font-semibold text-white shadow-sm hover:bg-[#5a2499] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                            {{ __('Aplicar filtros') }}
                        </button>
                        @if ($filterQ !== '' || $filterStoreStatus !== 'all')
                            <a href="{{ route('partners.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2">
                                {{ __('Limpar') }}
                            </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-auto rounded-xl border border-gray-200 shadow-md mt-4">
                    <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-600" role="table"
                        aria-label="Lista de lojas parceiras">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900">Loja / titular</th>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900">Assinatura</th>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900">Situação</th>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                @php
                                    $partner = $user->partner;
                                    $subscription = $partner?->subscription;
                                    $subPlan = $subscription?->plan;
                                    $store = $partner?->store;
                                    $subStatus = $subscription?->status;
                                    $manualSuspended = $store?->suspended_at !== null;
                                @endphp
                                <tr
                                    class="hover:bg-violet-50/60 transition-colors {{ $partner ? 'cursor-pointer' : 'cursor-default' }}"
                                    @if ($partner)
                                        @click="openDrawer({{ $partner->id }})"
                                        @keydown.enter.prevent="openDrawer({{ $partner->id }})"
                                        tabindex="0"
                                        role="button"
                                        aria-label="Abrir detalhes: {{ $store?->store_name ?? $user->name }}"
                                    @endif>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-3 items-center">
                                            <div class="relative h-10 w-10 shrink-0">
                                                <img width="40" height="40"
                                                    class="h-10 w-10 rounded-full object-cover object-center border border-gray-200"
                                                    src="{{ $user->image_profile ? asset('storage/' . $user->image_profile) : asset('img/logos/logo.png') }}"
                                                    alt="" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $store?->store_name ?? '—' }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-800">{{ $subPlan?->name ?? '—' }}</div>
                                        @if ($subPlan)
                                            <div class="text-xs text-gray-500 mt-0.5">R$
                                                {{ number_format((float) $subPlan->price, 2, ',', '.') }} / mês</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="inline-flex w-fit items-center gap-1 rounded-lg px-2 py-0.5 text-xs font-semibold {{ $subStatus === 'active' ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">
                                                <span
                                                    class="h-1.5 w-1.5 rounded-full {{ $subStatus === 'active' ? 'bg-emerald-600' : 'bg-amber-600' }}"></span>
                                                Assinatura:
                                                {{ $subStatus === 'active' ? 'Ativa' : ($subStatus ? ucfirst((string) $subStatus) : '—') }}
                                            </span>
                                            @if ($manualSuspended)
                                                <span
                                                    class="inline-flex w-fit rounded-lg bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-900">Painel
                                                    suspenso (manual)</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right" @click.stop>
                                        <a href="{{ route('partners.edit', $user->id) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA]">
                                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-600">Nenhuma loja encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4" role="navigation" aria-label="Paginação">
                    {{ $users->links() }}
                </div>
            </div>

            {{-- Drawer --}}
            <div x-show="open" x-cloak class="fixed inset-0 z-50" aria-modal="true" role="dialog"
                aria-labelledby="drawer-title">
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[1px]" @click="closeDrawer()"></div>
                <div
                    class="absolute right-0 top-0 flex h-full w-full max-w-lg flex-col bg-white shadow-2xl ring-1 ring-black/5"
                    x-transition:enter="transition transform duration-200 ease-out"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition transform duration-150 ease-in"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    @click.stop>
                    <div class="flex items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
                        <div>
                            <h2 id="drawer-title" class="text-lg font-semibold text-gray-900" x-text="title">Detalhes da
                                loja</h2>
                            <p class="text-xs text-gray-500 mt-0.5" x-show="payload?.partner?.partner_link">Link:
                                <span class="font-mono text-gray-700" x-text="payload?.partner?.partner_link"></span>
                            </p>
                        </div>
                        <button type="button"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA]"
                            @click="closeDrawer()" aria-label="Fechar painel">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-6">
                        <template x-if="loading">
                            <div class="flex flex-col items-center justify-center py-16 gap-3 text-gray-500">
                                <svg class="h-8 w-8 animate-spin text-[#6A2BBA]" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-sm">Carregando…</span>
                            </div>
                        </template>

                        <template x-if="error && !loading">
                            <p class="text-sm text-red-600" x-text="error"></p>
                        </template>

                        <template x-if="payload && !loading">
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-gray-100 bg-gray-50/80 p-3">
                                        <div class="text-[11px] font-medium uppercase tracking-wide text-gray-500">Pedidos
                                            no mês</div>
                                        <div class="mt-1 text-xl font-semibold text-gray-900"
                                            x-text="payload.monthly.orders_count"></div>
                                        <div class="text-xs text-gray-500 mt-0.5" x-text="payload.monthly.period_label">
                                        </div>
                                    </div>
                                    <div class="rounded-xl border border-gray-100 bg-gray-50/80 p-3">
                                        <div class="text-[11px] font-medium uppercase tracking-wide text-gray-500">
                                            Faturamento (concluídos)</div>
                                        <div class="mt-1 text-xl font-semibold text-[#6A2BBA]"
                                            x-text="moneyBr(payload.monthly.revenue_completed_only)"></div>
                                        <div class="text-xs text-gray-500 mt-0.5">Exceto cancelados</div>
                                    </div>
                                </div>
                                <div class="rounded-xl border border-violet-100 bg-violet-50/50 p-3">
                                    <div class="text-[11px] font-medium uppercase tracking-wide text-violet-800">Volume no
                                        mês (não cancelados)</div>
                                    <div class="mt-1 text-lg font-semibold text-violet-950"
                                        x-text="moneyBr(payload.monthly.revenue_ex_cancelled)"></div>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Dados da loja</h3>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex justify-between gap-2">
                                            <dt class="text-gray-500">Nome</dt>
                                            <dd class="font-medium text-gray-900 text-right"
                                                x-text="payload.store?.store_name ?? '—'"></dd>
                                        </div>
                                        <div class="flex justify-between gap-2">
                                            <dt class="text-gray-500">E-mail</dt>
                                            <dd class="text-gray-900 text-right break-all"
                                                x-text="payload.store?.store_email ?? '—'"></dd>
                                        </div>
                                        <div class="flex justify-between gap-2">
                                            <dt class="text-gray-500">Telefone</dt>
                                            <dd class="text-gray-900 text-right"
                                                x-text="payload.store?.store_phone ?? '—'"></dd>
                                        </div>
                                        <div class="flex justify-between gap-2">
                                            <dt class="text-gray-500">CNPJ / CPF</dt>
                                            <dd class="text-gray-900 text-right"
                                                x-text="payload.store?.store_cpf_cnpj ?? '—'"></dd>
                                        </div>
                                        <div class="flex justify-between gap-2" x-show="payload.store?.address">
                                            <dt class="text-gray-500">Endereço</dt>
                                            <dd class="text-gray-900 text-right text-xs"
                                                x-text="formatAddress(payload.store?.address)"></dd>
                                        </div>
                                    </dl>
                                </div>

                                <div x-show="payload.subscription">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Assinatura</h3>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex justify-between gap-2">
                                            <dt class="text-gray-500">Plano</dt>
                                            <dd class="font-medium text-gray-900 text-right"
                                                x-text="payload.subscription?.plan?.name ?? '—'"></dd>
                                        </div>
                                        <div class="flex justify-between gap-2">
                                            <dt class="text-gray-500">Status</dt>
                                            <dd class="text-gray-900 text-right capitalize"
                                                x-text="payload.subscription?.status ?? '—'"></dd>
                                        </div>
                                    </dl>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Usuários vinculados</h3>
                                    <ul class="divide-y divide-gray-100 rounded-xl border border-gray-200">
                                        <template x-for="u in (payload.linked_users || [])" :key="u.id">
                                            <li class="px-3 py-2.5">
                                                <div class="font-medium text-gray-900 text-sm" x-text="u.name"></div>
                                                <div class="text-xs text-gray-500" x-text="u.role_label"></div>
                                                <div class="text-xs text-gray-500" x-text="u.email"></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                <div class="border-t border-gray-100 pt-4 space-y-3" x-show="currentPartnerId">
                                    <template x-if="payload.store && !payload.store.suspended_at">
                                        <form :action="suspendUrl" method="post" class="space-y-2"
                                            onsubmit="return confirm('Inativar o painel desta loja? Parceiros e consultores não poderão acessar até reativar.');">
                                            @csrf
                                            <button type="submit"
                                                class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2">
                                                Inativar loja manualmente
                                            </button>
                                            <p class="text-xs text-gray-500">Use para mensalidade em atraso ou outro motivo
                                                administrativo.</p>
                                        </form>
                                    </template>
                                    <template x-if="payload.store && payload.store.suspended_at">
                                        <form :action="reactivateUrl" method="post">
                                            @csrf
                                            <button type="submit"
                                                class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2">
                                                Reativar loja
                                            </button>
                                        </form>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endsection
</x-app-layout>
