<x-app-layout>

    @section('content')
    <div class="p-2 flex md:justify-center">
        <div class="md:flex md:max-w-[1200px] flex-col w-full ml-2 mr-2">
            <h2 class="font-semibold text-2xl mb-3 mt-3 text-gray-800">
                {{ __('Minha loja') }}
            </h2>

            <div class="flex flex-col md:flex-row md:justify-between md:space-x-10">
                
                <div class="w-full flex space-y-3 gap-4">
                    <div class="w-2/3"> 
                        <div>
                            <div>
                                @include('partner.my-store.partials.partner-link')
                            </div>
                        </div>
                        @if($user->role == 'partner')
                            <div>
                                <div>
                                    @include('partner.my-store.partials.my-store-form')
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col w-1/3">
                        @if($user->role == 'partner')  
                            <div class="md:w-full flex flex-col space-y-2 md:mt-0 mt-2">
                                <div>
                                    @include('partner.my-store.partials.my-plan')
                                </div>
                                <div>
                                    @include('partner.my-store.partials.store-hours-form')
                                </div>
                                {{-- <div>
                                    @include('partner.my-store.partials.payment-data')
                                </div> --}}
                            </div>
                        @endif
                    </div>

                </div>

            </div>        

        </div>
    </div>
    @endsection
</x-app-layout>
