<section class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
    <header class="mb-4 flex gap-2 items-center">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
            <i class="fa-regular fa-clock text-sm"></i>
        </div>
        <h2 class="font-semibold text-xl text-gray-800">Horários de Funcionamento</h2>
    </header>

    <form class="mt-2 flex flex-col gap-3" action="{{ route('store_hours.update', $store->id) }}" method="POST">
        @csrf

        {{-- Seg a Sex --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between p-2 px-4 rounded-xl bg-gray-50 border border-gray-100 gap-3 md:gap-0 hover:border-gray-200 transition">
            <div class="font-medium text-gray-700 w-full md:w-1/3 flex items-center gap-2">
                <i class="fa-regular fa-calendar-days text-gray-400"></i>
                Seg a Sex
            </div>
            <div class="flex items-center gap-3 w-full md:w-2/3 md:justify-end">
                <div class="flex flex-col w-full md:w-32">
                    <span class="text-[10px] uppercase text-gray-500 font-bold mb-1 ml-1">Abertura</span>
                    <x-text-input type="time" name="weekday_open" class="text-sm rounded-xl border-gray-300 text-gray-600" />
                </div>
                <span class="text-gray-300 mt-4">-</span>
                <div class="flex flex-col w-full md:w-32">
                    <span class="text-[10px] uppercase text-gray-500 font-bold mb-1 ml-1">Fechamento</span>
                    <x-text-input type="time" name="weekday_close" class="text-sm rounded-xl border-gray-300 text-gray-600" />
                </div>
            </div>
        </div>

        {{-- Sábado --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between p-2 px-4 rounded-xl bg-gray-50 border border-gray-100 gap-3 md:gap-0 hover:border-gray-200 transition">
            <div class="font-medium text-gray-700 w-full md:w-1/3 flex items-center gap-2">
                <i class="fa-regular fa-calendar-check text-gray-400"></i>
                Sábado
            </div>
            <div class="flex items-center gap-3 w-full md:w-2/3 md:justify-end">
                <div class="flex flex-col w-full md:w-32">
                    <span class="text-[10px] uppercase text-gray-500 font-bold mb-1 ml-1">Abertura</span>
                    <x-text-input type="time" name="saturday_open" class="text-sm rounded-xl border-gray-300 text-gray-600" />
                </div>
                <span class="text-gray-300 mt-4">-</span>
                <div class="flex flex-col w-full md:w-32">
                    <span class="text-[10px] uppercase text-gray-500 font-bold mb-1 ml-1">Fechamento</span>
                    <x-text-input type="time" name="saturday_close" class="text-sm rounded-xl border-gray-300 text-gray-600" />
                </div>
            </div>
        </div>

        {{-- Domingo --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between p-2 px-4 rounded-xl bg-gray-50 border border-gray-100 gap-3 md:gap-0 hover:border-gray-200 transition">
            <div class="font-medium text-gray-700 w-full md:w-1/3 flex items-center gap-2">
                <i class="fa-regular fa-calendar-xmark text-gray-400"></i>
                Domingo
            </div>
            <div class="flex items-center gap-3 w-full md:w-2/3 md:justify-end">
                <div class="flex flex-col w-full md:w-32">
                    <span class="text-[10px] uppercase text-gray-500 font-bold mb-1 ml-1">Abertura</span>
                    <x-text-input type="time" name="sunday_open" class="text-sm rounded-xl border-gray-300 text-gray-600" />
                </div>
                <span class="text-gray-300 mt-4">-</span>
                <div class="flex flex-col w-full md:w-32">
                    <span class="text-[10px] uppercase text-gray-500 font-bold mb-1 ml-1">Fechamento</span>
                    <x-text-input type="time" name="sunday_close" class="text-sm rounded-xl border-gray-300 text-gray-600" />
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-1 pt-3 border-t border-gray-100">
            <x-primary-button id="btnSaveStoreHour" class="w-full justify-center md:w-auto px-6 py-2.5 shadow-md">
                {{ __('Atualizar Horários') }}
            </x-primary-button>
        </div>

    </form>
</section>
