@extends('layouts.app')

@section('header')
<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
        Novo Mapa de Rede
    </h2>
    <a href="{{ route('admin.network-maps.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
        <i class="fas fa-arrow-left mr-2"></i>Voltar
    </a>
</div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('admin.network-maps.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome do mapa *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Ex: Matriz Enge">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">Arquivo SVG *</label>
                            <input type="file" name="file" id="file" accept=".svg" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                            <p class="mt-1 text-xs text-gray-500">Apenas .svg. Max 10 MB.</p>
                            @error('file')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Mapa ativo</label>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('admin.network-maps.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</a>
                        <button type="submit" class="px-4 py-2 text-black font-semibold rounded-md" style="background-color: #E9B32C;" onmouseover="this.style.backgroundColor='#d19d20'" onmouseout="this.style.backgroundColor='#E9B32C'">
                            <i class="fas fa-save mr-2"></i>Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
