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
                        {{ __('Editar Cliente') }}
                    </div>

                    <button class="btn-sm">
                        <a href="{{ route('clients.index') }}"
                            class="mr-5 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </button>
                </div>

                <!-- component -->
                <form action="{{ route('clients.update', $client->id)}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="bg-white rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 px-3 mb-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Nome *
                                </label>
                                <input
                                    class="@if($errors->has('name')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-2"
                                    id="grid-first-name" type="text" placeholder="Digite o nome" name="name" @if($client->name) value="{{$client->name}}" @else value="{{old($client->name)}}" @endif>
                                    @if ($errors->has('name'))
                                        <span class="text-red-500"><small>{{ $errors->first('name') }}</small></span>
                                    @endif
                            </div>
                            <div class="md:w-1/2 px-3 mb-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    E-mail
                                </label>
                                <input
                                    class="@if($errors->has('email')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-2"
                                    id="grid-first-name" type="text" placeholder="example@mail.com" name="email" value="{{$client->email}}">
                                    @if ($errors->has('email'))
                                        <span class="text-red-500"><small>{{ $errors->first('email') }}</small></span>
                                    @endif
                            </div>
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Telefone
                                </label>
                                <input
                                    class="@if($errors->has('phone')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="grid-first-name" type="text" placeholder="(00) 00000-0000" name="phone" value="{{$client->phone}}">
                                    @if ($errors->has('phone'))
                                        <span class="text-red-500"><small>{{ $errors->first('phone') }}</small></span>
                                    @endif

                            </div>

                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    CEP
                                </label>
                                <input
                                    class="@if($errors->has('zip_code')) border-red-400 @endif appearance-none block mb-2 w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="00000-000" name="zip_code" value="{{$client->zip_code}}">
                                    <p class="text-red text-xs italic">Preenchimento automático.</p>
                                    @if ($errors->has('zip_code'))
                                        <span class="text-red-500"><small>{{ $errors->first('zip_code') }}</small></span>
                                    @endif
                            </div>

                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Cidade
                                </label>
                                <input
                                    class="@if($errors->has('city')) border-red-400 @endif appearance-none block mb-4 w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Informe a cidade" name="city" value="{{$client->city}}">
                                    @if ($errors->has('city'))
                                        <span class="text-red-500"><small>{{ $errors->first('city') }}</small></span>
                                    @endif

                            </div>

                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/3 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-first-name">
                                    Bairro *
                                </label>
                                <input
                                    class="@if($errors->has('neighborhood')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    id="grid-first-name" type="text" placeholder="Informe o bairro" name="neighborhood" value="{{$client->neighborhood}}">
                                    @if ($errors->has('neighborhood'))
                                        <span class="text-red-500"><small>{{ $errors->first('neighborhood') }}</small></span>
                                    @endif

                            </div>
                            <div class="md:w-1/3 px-3">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Endereço *
                                </label>
                                <input
                                    class="@if($errors->has('address')) border-red-400 @endif appearance-none block w-full mb-2 bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Informe o endereço" name="address" value="{{$client->address}}">
                                    @if ($errors->has('address'))
                                        <span class="text-red-500"><small>{{ $errors->first('address') }}</small></span>
                                    @endif

                            </div>


                            <div class="md:w-1/3 px-3 mt-4 md:mt-0">
                                <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"
                                    for="grid-last-name">
                                    Número *
                                </label>
                                <input
                                    class="@if($errors->has('number')) border-red-400 @endif appearance-none block w-full bg-grey-lighter text-grey-darker border border-grey-lighter rounded py-3 px-4"
                                    id="grid-last-name" type="text" placeholder="Informe o número" name="number" value="{{$client->number}}">
                                    @if ($errors->has('number'))
                                        <span class="text-red-500"><small>{{ $errors->first('number') }}</small></span>
                                    @endif
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
