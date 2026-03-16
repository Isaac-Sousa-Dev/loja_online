@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white shadow-sm rounded-xl p-6">
            <h2 class="text-lg font-bold mb-4">Exemplo de formulário</h2>
            <form>
                <label class="block mb-2 text-sm font-medium text-gray-700">Selecione</label>
                <select class="select2 block w-full border-gray-300 rounded-xl" name="option">
                    <option value="">Selecione</option>
                    <option value="1">Opção 1</option>
                    <option value="2">Opção 2</option>
                </select>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                dropdownParent: $('main')
            });
        });
    </script>
@endsection
