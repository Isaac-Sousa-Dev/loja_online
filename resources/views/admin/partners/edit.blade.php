<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clientes') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="flex justify-between items-center mt-4">
                    <div class="px-4 text-gray-900 bg-slate-100 ml-5 rounded-full h-6 font-semibold">
                        {{ __('Editar Sócio') }}
                    </div>

                    <button class="btn-sm">
                        <a href="{{ route('partners.index') }}"
                            class="mr-5 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-semibold rounded-md text-white bg-gradient-to-r from-[#6A2BBA] to-[#D131A3] hover:brightness-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#6A2BBA] focus-visible:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </button>
                </div>

                <!-- component -->
                <form action="{{ route('partners.update', $user->id)}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="bg-white rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Nome *
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-2"
                                    id="grid-first-name" type="text" placeholder="Digite o nome" name="name" value="{{$user->name}}">
                            </div>
                            <div class="md:w-1/2 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    E-mail
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="example@mail.com" name="email" value="{{$user->email}}">
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Senha *
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="grid-first-name" type="password" placeholder="*************" name="password">

                            </div>
                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Papel
                                </label>
                                <input
                                    class="appearance-none block mb-4 w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Selecione o papel" name="role" value="{{$user->role}}">
                            </div>

                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Telefone
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="(00) 00000-0000" name="phone" value="{{$user->partner->phone}}">
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Status
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="grid-first-name" type="text" placeholder="Status" name="status" value="{{$user->partner->status}}">

                            </div>
                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    CEP
                                </label>
                                <input
                                    class="appearance-none block w-full mb-2 bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="00000-000" name="zip_code" value="{{$user->partner->zip_code}}">
                                    <p class="text-red text-xs italic">Preenchimento automático.</p>
                            </div>

                            <div class="md:w-1/3 px-3 mt-4 md:mt-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Cidade
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Londres" name="city" value="{{$user->partner->city}}">
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Bairro
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="grid-first-name" type="text" placeholder="Informe o bairro" name="neighborhood" value="{{$user->partner->neighborhood}}">

                            </div>
                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Endereço
                                </label>
                                <input
                                    class="appearance-none block w-full mb-5 bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Informe o endereço" name="address" value="{{$user->partner->address}}">
                            </div>

                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Número
                                </label>
                                <input
                                    class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Informe o número" name="number" value="{{$user->partner->number}}">
                            </div>
                        </div>

                    </div>


                    <div class="flex w-full md:justify-end -mt-14 mb-4">
                        <button
                            class="mr-7 ml-7 w-full md:w-[25%] justify-center h-12 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-blue active:bg-green-700 transition ease-in-out duration-150">
                            {{ __('Salvar') }}
                        </button>
                    </div>


                </form>

            </div>
        </div>
    </div>
</x-app-layout>
