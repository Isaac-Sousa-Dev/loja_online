<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">

            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                {{ __('Meu perfil') }}
            </h2>

            <div class="flex flex-col md:flex-row md:justify-between md:space-x-10">
                
                <div class="w-full flex flex-col space-y-1">
                    <div class="">
                        <div class="">
                            @include('partner.my-store.partials.partner-link')
                        </div>
                    </div>
                    <div class="sm:rounded-lg">
                        <div class="">
                            @include('profile.partials.my-profile-form')
                        </div>
                    </div>
                </div>

                <div class="w-full space-y-4 mt-3">
                    <div class=" sm:rounded-lg">
                        <div class="">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

            </div>
        

        </div>
    </div>
    @endsection
</x-app-layout>
