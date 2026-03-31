@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
            Editar Mapa — {{ $network_map->name }}
        </h2>
        <a href="{{ route('admin.network-maps.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar
        </a>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.network-maps.update', $network_map) }}" method="POST" enctype="multipart/form-data" id="networkMapEditForm">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome do mapa *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $network_map->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex items-start gap-3 rounded-lg border border-gray-200 bg-amber-50/40 p-4">
                                <input type="hidden" name="has_two_floors" value="0">
                                <input type="checkbox" name="has_two_floors" id="has_two_floors" value="1" {{ old('has_two_floors', $network_map->has_two_floors) ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                <div>
                                    <label for="has_two_floors" class="block text-sm font-medium text-gray-800">Mapa com dois andares</label>
                                    <p class="text-xs text-gray-600 mt-0.5">Com dois andares, faça upload do SVG de cada piso abaixo (ou substitua só um deles).</p>
                                </div>
                            </div>
                            <div id="wrap-single-svg-replace">
                                <label for="file" class="block text-sm font-medium text-gray-700">Substituir SVG (opcional)</label>
                                <input type="file" name="file" id="file" accept=".svg,.image/svg+xml" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                <p class="mt-1 text-xs text-gray-500">Atual: {{ $network_map->file_name }}</p>
                                @error('file')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div id="wrap-dual-svg-replace" class="hidden space-y-4">
                                <div>
                                    <label for="file_floor1_edit" class="block text-sm font-medium text-gray-700">Substituir SVG — 1º andar (opcional)</label>
                                    <input type="file" name="file" id="file_floor1_edit" accept=".svg,.image/svg+xml" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                    <p class="mt-1 text-xs text-gray-500">Atual: {{ $network_map->file_name }}</p>
                                </div>
                                <div>
                                    <label for="file_floor2" class="block text-sm font-medium text-gray-700">
                                        Substituir SVG — 2º andar
                                        @if(! $network_map->file_name_floor2)
                                            <span class="text-red-600">*</span>
                                        @else
                                            <span class="text-gray-500 font-normal">(opcional)</span>
                                        @endif
                                    </label>
                                    <input type="file" name="file_floor2" id="file_floor2" accept=".svg,.image/svg+xml" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                    <p class="mt-1 text-xs text-gray-500">Atual: {{ $network_map->file_name_floor2 ?: 'nenhum — envie um arquivo para ativar o segundo andar.' }}</p>
                                    @error('file_floor2')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $network_map->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Mapa ativo</label>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('admin.network-maps.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</a>
                            <button type="submit" class="px-4 py-2 text-black font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: #E9B32C;" onmouseover="this.style.backgroundColor='#d19d20'" onmouseout="this.style.backgroundColor='#E9B32C'">
                                <i class="fas fa-save mr-2"></i>Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    (function() {
        var form = document.getElementById('networkMapEditForm');
        var cb = document.getElementById('has_two_floors');
        var wSingle = document.getElementById('wrap-single-svg-replace');
        var wDual = document.getElementById('wrap-dual-svg-replace');
        var inpSingle = document.getElementById('file');
        var inp1 = document.getElementById('file_floor1_edit');
        var inp2 = document.getElementById('file_floor2');
        var hasExistingFloor2 = {{ $network_map->file_name_floor2 ? 'true' : 'false' }};
        if (!form || !cb || !wSingle || !wDual || !inpSingle || !inp1 || !inp2) return;
        function sync() {
            var two = cb.checked;
            wSingle.classList.toggle('hidden', two);
            wDual.classList.toggle('hidden', !two);
            inpSingle.disabled = two;
            inp1.disabled = !two;
            inp2.disabled = !two;
            if (two) {
                inpSingle.value = '';
                inp2.required = !hasExistingFloor2;
            } else {
                inp1.value = '';
                inp2.value = '';
                inp2.required = false;
            }
        }
        cb.addEventListener('change', sync);
        form.addEventListener('submit', sync);
        sync();
    })();
    </script>
@endsection
