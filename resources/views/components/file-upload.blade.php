@props([
    'label' => '',
    'name' => '',
    'id' => null,
    'placeholder' => 'Selecione o arquivo',
    'file_url' => null // URL do arquivo existente (se houver)
])

@php
    $inputId = $id ?? $name;
@endphp

<div class="w-full" 

     x-data="{ 
        fileName: @js($file_url ? basename($file_url) : ''), 
        filePreview: @js($file_url) 
     }">

    @if ($label)
        <x-input-label :for="$inputId" :value="__($label)" />
    @endif

    <div class="flex items-center gap-2 w-full">
        <!-- Botão de upload -->
        <label for="{{ $inputId }}"
            class="h-9 flex-1 min-w-0 rounded-lg cursor-pointer border-2 border-dashed border-gray-300 flex items-center justify-between px-2 hover:border-indigo-400 transition">
            <span class="text-gray-800 font-semibold text-sm truncate flex-1" x-text="fileName || '{{ $placeholder }}'"></span>
            <svg class="w-5 h-5 text-gray-800 flex-shrink-0 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                <path d="M31.7 239l136-136c9.4-9.4 
                24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 
                9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 
                9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 
                0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"/>
            </svg>
        </label>

        <!-- Botão de visualizar -->
        <template x-if="filePreview">
            <a href="/storage/{{$file_url}}" target="_blank"
            class="bg-blue-500 text-white px-3 py-1 h-[34px] flex items-center rounded hover:bg-blue-600 transition text-sm whitespace-nowrap flex-shrink-0">
                Visualizar
            </a>
        </template>
    </div>

    <!-- Input escondido -->
    <x-text-input 
        class="hidden" 
        id="{{ $inputId }}" 
        name="{{ $name }}" 
        type="file"
        x-on:change="
            if ($event.target.files.length > 0) {
                fileName = $event.target.files[0].name;
                filePreview = URL.createObjectURL($event.target.files[0]);
            }
        " />

    <x-input-error class="mt-2" :messages="$errors->get($name)" />
</div>
