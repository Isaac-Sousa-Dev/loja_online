<x-app-layout>

    @section('content')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
        <div class="py-2 px-2 flex justify-center pb-24 md:pb-0">

            <div class="flex flex-col w-full max-w-[1200px]">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 text-sm text-gray-500 mt-4 mb-2 px-1" aria-label="breadcrumb">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Dashboard</span>
                    </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-semibold text-gray-700">Meu Perfil</span>
                </nav>

                {{-- Title --}}
                <div class="flex items-center gap-2 mt-2 mb-4 px-1">
                    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                        {{ __('Meu Perfil') }}
                    </h2>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    
                    {{-- Left Column (Main Form & Password) --}}
                    <div class="w-full md:w-2/3 flex flex-col space-y-6">
                        <div>
                            @include('profile.partials.my-profile-form')
                        </div>
                        <div>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- Right Column (Sidebar) --}}
                    <div class="w-full md:w-1/3 flex flex-col space-y-6">
                        @if ($user->role == 'partner')
                            <div>
                                @include('partner.my-store.partials.partner-link')
                            </div>
                        @endif
                    </div>

                </div>
            
            </div>
        </div>
    @endsection
</x-app-layout>
