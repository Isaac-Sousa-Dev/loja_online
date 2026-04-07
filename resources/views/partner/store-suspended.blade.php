<x-app-layout>
    @section('content')
        <div class="min-h-[60vh] flex items-center justify-center p-6">
            <div class="max-w-lg w-full rounded-2xl border border-amber-200 bg-amber-50/90 p-8 shadow-sm text-center"
                role="alert"
                aria-live="polite">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-800 mb-4"
                    aria-hidden="true">
                    <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-gray-900">Loja temporariamente inativa</h1>
                <p class="mt-3 text-sm text-gray-700 leading-relaxed">
                    O acesso ao painel foi suspenso manualmente pela administração da plataforma, em geral por pendência
                    de mensalidade ou acordo comercial. Entre em contato com o suporte ou regularize o pagamento para
                    reativar o serviço.
                </p>
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-900 focus-visible:ring-offset-2">
                        Sair da conta
                    </button>
                </form>
            </div>
        </div>
    @endsection
</x-app-layout>
