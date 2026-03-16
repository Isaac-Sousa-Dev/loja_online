<div class="container bg-white p-3 rounded-xl">
    <h2 class="font-semibold">Horários de Funcionamento</h2>

    <form class="mt-2" action="{{ route('store_hours.update', $store->id) }}" method="POST">
        @csrf

        <div class="flex items-center border-b-[1px] border-gray-300 py-2">
            <div class="w-1/2">
                <label for="">Seg a Sex</label>
            </div>
            <div class="flex gap-2">
                <x-text-input type="time" />
                <x-text-input type="time" />
            </div>
        </div>

        <div class="flex items-center border-b-[1px] border-gray-300 py-2">
            <div class="w-1/2">
                <label for="">Sábado</label>
            </div>
            <div class="flex gap-2">
                <x-text-input type="time" />
                <x-text-input type="time" />
            </div>
        </div>

        <div class="flex items-center border-b-[1px] border-gray-300 py-2">
            <div class="w-1/2">
                <label for="">Domingo</label>
            </div>
            <div class="flex gap-2">
                <x-text-input type="time" />
                <x-text-input type="time" />
            </div>
        </div>

        {{-- @foreach(range(0, 6) as $key => $day)

            <div class="flex rounded-lg mb-2">
                <div class="border-b w-3/5">
                    <button data-key="{{$key}}" onclick="toggleAccordion({{$key}})" class="w-full flex justify-between items-center py-2 px-1 text-slate-800 btn-toggle-store-hour">
                    <label for="hours[{{ $day }}][open_time]" class="form-label">
                        {{ ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'][$day] }}
                    </label>    
        
                    <div class="flex items-center gap-2">
                        <span id="icon-{{$key}}" class="text-slate-800 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M11.78 9.78a.75.75 0 0 1-1.06 0L8 7.06 5.28 9.78a.75.75 0 0 1-1.06-1.06l3.25-3.25a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                    </button>
                    <div id="content-{{$key}}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                    <div class="pb-1 text-sm text-slate-500">
                        <div class="bg-slate-50 flex flex-col md:flex-row items-center gap-2 p-2 rounded-lg">
                            <div class="items-center md:w-1/2 gap-2">
                                <label class="text-xs" for="">Horário Inicial</label>
                                <input type="time" class="rounded-lg w-full" placeholder="Horário inicial" name="hours[{{ $day }}][open_time]" 
                                    value="{{ optional($storeHours->firstWhere('day_of_week', $day))->open_time }}">
                            </div>
        
                            <div class="items-center md:w-1/2 gap-2">
                                <label class="text-xs" for="">Horário Final</label>
                                <input type="time" class="rounded-lg w-full" placeholder="Horário final" name="hours[{{ $day }}][close_time]"
                                    value="{{ optional($storeHours->firstWhere('day_of_week', $day))->close_time }}">
                            </div>
        
                        </div>
                    </div>
                    </div>
                </div>
        
                <div class=" text-center w-2/5 justify-center">
                    <div class="text-xs">Atendimento</div>

                    <div class="inline-flex items-center gap-2">
                        <label for="switch-component-on" class="text-red-600 text-sm cursor-pointer">Não</label>

                        <label class="relative inline-flex cursor-pointer items-center">
                            <input id="switch-2" name="hours[{{ $day }}][is_open]" @checked($store->storeHours[$day]['is_open'] != 0) type="checkbox" class="peer sr-only" />
                            <label for="switch-2" class="hidden"></label>
                            <div class="peer h-4 w-11 rounded-full border bg-slate-200 after:absolute after:-top-1 after:left-0 after:h-6 after:w-6 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-green-300 peer-checked:after:translate-x-full peer-focus:ring-green-300"></div>
                        </label>
                        <label for="switch-component-on" class="text-green-600 text-sm cursor-pointer">Sim</label>
                    </div>
                </div>
            </div>
        @endforeach --}}

        {{-- <script>

            $('.btn-toggle-store-hour').click(function(event) {
                event.preventDefault();
                const index = $(this).data('key');
                this.toggleAccordion(index);
            });

            function toggleAccordion(index) {
                const content = document.getElementById(`content-${index}`);
                const icon = document.getElementById(`icon-${index}`);
            
                // SVG for Down icon
                const downSVG = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
                `;
            
                // SVG for Up icon
                const upSVG = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M11.78 9.78a.75.75 0 0 1-1.06 0L8 7.06 5.28 9.78a.75.75 0 0 1-1.06-1.06l3.25-3.25a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                </svg>
                `;
            
                // Toggle the content's max-height for smooth opening and closing
                if (content.style.maxHeight && content.style.maxHeight !== '0px') {
                    content.style.maxHeight = '0';
                    icon.innerHTML = upSVG;
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.innerHTML = downSVG;
                }
            }
        </script> --}}

        {{-- <div class="flex items-center gap-4 mt-3">
            <button class="py-2 px-4 text-white font-semibold rounded-xl bg-primary" id="btnSaveStoreHour">{{ __('Salvar') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div> --}}

    </form>
</div>
