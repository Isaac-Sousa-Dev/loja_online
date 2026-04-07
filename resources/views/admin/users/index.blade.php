<x-app-layout>
    @section('content')
        <div class="p-2 flex md:justify-center">
            <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mt-4">
                    <div>
                        <h1 class="font-semibold text-2xl text-gray-800">{{ __('Usuários da equipe') }}</h1>
                        <p class="text-sm text-gray-600 mt-1">
                            Contas com perfil SysAdmin — administradores da plataforma. Lojas e consultores ficam em
                            <a href="{{ route('partners.index') }}" class="font-medium text-[#6A2BBA] hover:underline">Lojas (parceiros)</a>.
                        </p>
                    </div>
                    {{-- <a href="{{ route('partners.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 shadow-md shadow-[#6A2BBA]/20">
                        {{ __('Nova loja (parceiro)') }}
                    </a> --}}
                </div>

                <form method="get" action="{{ route('admin.users.index') }}"
                    class="mt-5 flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
                    role="search"
                    aria-label="Filtrar administradores">
                    <div class="flex-1 min-w-[200px]">
                        <label for="admin-search" class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                        <input id="admin-search" type="search" name="q" value="{{ $filterQ }}"
                            placeholder="Nome ou e-mail"
                            class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-[#6A2BBA] focus:ring-[#6A2BBA] py-2 px-3 border"
                            autocomplete="off">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-xl bg-[#6A2BBA] px-5 text-sm font-semibold text-white shadow-sm hover:bg-[#5a2499] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2">
                            {{ __('Filtrar') }}
                        </button>
                        @if ($filterQ !== '')
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2">
                                {{ __('Limpar') }}
                            </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-auto rounded-xl border border-gray-200 shadow-md mt-4">
                    <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-600" role="table"
                        aria-label="Lista de administradores SysAdmin">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900">Administrador</th>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900">Papel</th>
                                <th scope="col" class="px-4 py-3 font-medium text-gray-900">Cadastro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        @if ($user->phone)
                                            <div class="text-xs text-gray-500 mt-0.5 phone-mask">{{ $user->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex rounded-lg bg-violet-100 px-2 py-0.5 text-xs font-semibold text-violet-900">SysAdmin</span>
                                        @if ($user->email_verified_at)
                                            <div class="text-xs text-green-700 mt-1">E-mail verificado</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                        {{ $user->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-600">Nenhum administrador encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4" role="navigation" aria-label="Paginação">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
