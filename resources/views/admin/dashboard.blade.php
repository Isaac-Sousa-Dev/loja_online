<x-app-layout>
    @section('content')
        <div class="p-2 flex md:justify-center">
            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
                <h2 class="font-display font-semibold text-2xl mb-4 mt-2 text-[#33363B]">
                    {{ __('Dashboard') }}
                </h2>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
                    <article class="flex flex-col items-center justify-between rounded-xl border border-[#33363B]/8 bg-white px-3 py-4 text-center shadow-sm min-h-[9.5rem]" aria-labelledby="card-lojas-title">
                        <div id="card-lojas-title" class="text-xs font-bold uppercase tracking-wide text-[#33363B]/55">Lojas</div>
                        <div class="flex justify-center py-1" aria-hidden="true">
                            <svg class="h-10 w-10 text-[#6A2BBA]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-semibold tabular-nums text-[#33363B]" aria-label="Total de lojas cadastradas">{{ $storesCount }}</p>
                    </article>

                    <article class="flex flex-col items-center justify-between rounded-xl border border-[#33363B]/8 bg-white px-3 py-4 text-center shadow-sm min-h-[9.5rem]" aria-labelledby="card-storage-title">
                        <div id="card-storage-title" class="text-xs font-bold uppercase tracking-wide text-[#33363B]/55">Armazenamento</div>
                        <div class="flex justify-center py-1" aria-hidden="true">
                            <svg class="h-10 w-10 text-[#D131A3]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 7.205c4.418 0 8-1.165 8-2.602C20 3.165 16.418 2 12 2S4 3.165 4 4.603c0 1.437 3.582 2.602 8 2.602ZM12 22c4.963 0 8-1.686 8-2.603v-4.404c-.052.032-.112.06-.165.09a7.75 7.75 0 0 1-.745.387c-.193.088-.394.173-.6.253-.063.024-.124.05-.189.073a18.934 18.934 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.073a10.143 10.143 0 0 1-.852-.373 7.75 7.75 0 0 1-.493-.267c-.053-.03-.113-.058-.165-.09v4.404C4 20.315 7.037 22 12 22Zm7.09-13.928a9.91 9.91 0 0 1-.6.253c-.063.025-.124.05-.189.074a18.935 18.935 0 0 1-6.3.998c-2.135.027-4.26-.31-6.3-.998-.065-.024-.126-.05-.189-.074a10.163 10.163 0 0 1-.852-.372 7.816 7.816 0 0 1-.493-.268c-.055-.03-.115-.058-.167-.09V12c0 .917 3.037 2.603 8 2.603s8-1.686 8-2.603V7.596c-.052.031-.112.059-.165.09a7.816 7.816 0 0 1-.745.386Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-semibold tabular-nums text-[#33363B]" aria-label="Percentual em relação à cota de referência">{{ $storagePercent }}%</p>
                            <p class="mt-0.5 text-[10px] font-medium text-[#33363B]/50">{{ $storageUsedHuman }} em uso (público)</p>
                        </div>
                    </article>

                    <article class="flex flex-col items-center justify-between rounded-xl border border-[#33363B]/8 bg-white px-3 py-4 text-center shadow-sm min-h-[9.5rem]" aria-labelledby="card-solicitacoes-title">
                        <div id="card-solicitacoes-title" class="text-xs font-bold uppercase tracking-wide text-[#33363B]/55">Solicitações</div>
                        <div class="flex justify-center py-1 text-[#FF914D]" aria-hidden="true">
                            <svg class="h-9 w-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor"><path d="M336 0H48C21.5 0 0 21.5 0 48v464l192-112 192 112V48c0-26.5-21.5-48-48-48zm0 428.4l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.3 0 6 2.7 6 6V428.4z"/></svg>
                        </div>
                        <p class="text-2xl font-semibold tabular-nums text-[#33363B]" aria-label="Total de solicitações de plano">{{ $solicitationsCount }}</p>
                    </article>

                    <article class="flex flex-col items-center justify-between rounded-xl border border-[#33363B]/8 bg-white px-3 py-4 text-center shadow-sm min-h-[9.5rem]" aria-labelledby="card-renda-title">
                        <div id="card-renda-title" class="text-xs font-bold uppercase tracking-wide text-[#33363B]/55">Renda mensal</div>
                        <div class="flex justify-center py-1 text-[#33363B]" aria-hidden="true">
                            <svg class="h-9 w-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M461.2 128H80c-8.8 0-16-7.2-16-16s7.2-16 16-16h384c8.8 0 16-7.2 16-16 0-26.5-21.5-48-48-48H64C28.7 32 0 60.7 0 96v320c0 35.4 28.7 64 64 64h397.2c28 0 50.8-21.5 50.8-48V176c0-26.5-22.8-48-50.8-48zM416 336c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
                        </div>
                        <p class="text-lg font-semibold tabular-nums leading-tight text-[#33363B] sm:text-xl" aria-label="Soma mensal estimada pelos planos das lojas ativas">
                            R$ {{ number_format((float) $monthlyRevenue, 2, ',', '.') }}
                        </p>
                        <p class="mt-0.5 text-[10px] font-medium text-[#33363B]/45">Planos das lojas com assinatura ativa</p>
                    </article>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 md:gap-6">
                    <section  on class="flex flex-col" aria-labelledby="ultimas-solicitacoes-heading">
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2 px-1">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-[#6A2BBA]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1 .9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9 .7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"/></svg>
                                <h3 id="ultimas-solicitacoes-heading" class="font-semibold text-[#33363B]">Últimas solicitações</h3>
                            </div>
                            <a href="{{ route('list.request.plans') }}" class="text-md font-semibold text-[#6A2BBA] hover:text-[#D131A3] hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 rounded">
                                Ver todas
                            </a>
                        </div>
                        <p class="mb-3 px-1 text-xs text-[#33363B]/60">Pedidos de acesso e plano recebidos pelo formulário público.</p>

                        <div class="flex flex-col gap-2">
                            @forelse ($latestRequestPlans as $requestPlan)
                                <article class="rounded-lg border border-[#33363B]/10 bg-gradient-to-br from-gray-50 to-white p-3 shadow-sm">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span class="rounded-md bg-emerald-500 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Em aberto</span>
                                            <span class="rounded-md bg-[#33363B] px-2 py-0.5 text-[10px] font-semibold text-white">{{ $requestPlan->plan_name }}</span>
                                            @php
                                                $pm = $requestPlan->payment_method;
                                                $pmLabel = match ($pm) {
                                                    'pix' => 'Pix',
                                                    'credit' => 'Crédito',
                                                    'pendente' => 'A combinar',
                                                    default => $pm ? ucfirst((string) $pm) : '—',
                                                };
                                            @endphp
                                            <span class="rounded-md border border-[#33363B]/15 bg-white px-2 py-0.5 text-[10px] font-semibold text-[#33363B]/80">{{ $pmLabel }}</span>
                                        </div>
                                        <time class="whitespace-nowrap text-[10px] font-semibold text-[#33363B]/55" datetime="{{ $requestPlan->created_at?->toIso8601String() }}">
                                            {{ $requestPlan->created_at?->format('d/m/Y H:i') }}
                                        </time>
                                    </div>
                                    <h4 class="mt-2 text-sm font-bold text-[#6A2BBA]">
                                        {{ $requestPlan->store_name ?: $requestPlan->name ?: 'Solicitação sem nome' }}
                                    </h4>
                                    <p class="mt-1 line-clamp-2 text-xs italic text-[#33363B]/70">
                                        @if ($requestPlan->notes)
                                            {{ $requestPlan->notes }}
                                        @else
                                            {{ $requestPlan->email }}{{ $requestPlan->phone ? ' · '.$requestPlan->phone : '' }}
                                        @endif
                                    </p>
                                </article>
                            @empty
                                <p class="py-10 text-center text-sm font-medium text-[#33363B]/45" role="status">Nenhuma solicitação no momento.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="flex flex-col" aria-labelledby="ultimas-lojas-heading">
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2 px-1">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-[#6A2BBA]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/>
                                </svg>
                                <h3 id="ultimas-lojas-heading" class="font-semibold text-[#33363B]">Últimas lojas cadastradas</h3>
                            </div>
                            <a href="{{ route('partners.index') }}" class="text-md font-semibold text-[#6A2BBA] hover:text-[#D131A3] hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 rounded">
                                Ver todas
                            </a>
                        </div>
                        <p class="mb-3 px-1 text-xs text-[#33363B]/60">Parceiros criados manualmente ou pelo fluxo de cadastro.</p>

                        <div class="overflow-hidden rounded-xl border border-[#33363B]/8 bg-white shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-[#33363B]/10 text-left text-sm" role="table" aria-label="Últimas lojas cadastradas">
                                    <thead class="bg-gray-50/90">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-[#33363B]/60">Loja</th>
                                            <th scope="col" class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-[#33363B]/60">Responsável</th>
                                            <th scope="col" class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-[#33363B]/60">Telefone</th>
                                            <th scope="col" class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-[#33363B]/60">Plano</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#33363B]/8">
                                        @forelse ($latestPartnerUsers as $user)
                                            @php
                                                $store = $user->partner?->store;
                                                $planName = $user->partner?->subscription?->plan?->name;
                                            @endphp
                                            <tr class="hover:bg-gray-50/80">
                                                <td class="px-4 py-3 font-medium text-[#33363B]">{{ $store?->store_name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-[#33363B]/85">{{ $user->name }}</td>
                                                <td class="px-4 py-3 text-[#33363B]/75 phone-mask">{{ $user->phone ?? '—' }}</td>
                                                <td class="px-4 py-3 text-[#33363B]/75">{{ $planName ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-4 py-10 text-center text-sm font-medium text-[#33363B]/45" role="status">Nenhuma loja cadastrada ainda.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
