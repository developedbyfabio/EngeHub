@extends('layouts.app')

@section('header')
    <x-page-header title="Editar Formulário" icon="fas fa-clipboard-list">
        <x-slot name="actions">
            <a href="{{ route('admin.forms.show', $form) }}" class="text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('admin.forms.update', $form) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $form->title) }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('description', $form->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $form->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Formulário ativo</span>
                        </label>
                    </div>
                </div>
                <div class="mt-6 flex justify-between">
                    <div class="flex gap-3">
                        <button type="submit" class="page-header-btn-primary">
                            <i class="fas fa-save mr-2"></i> Salvar
                        </button>
                        <a href="{{ route('admin.forms.show', $form) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancelar</a>
                    </div>
                    <form action="{{ route('admin.forms.destroy', $form) }}" method="POST" onsubmit="return confirm('Excluir este formulário e todos os dados associados?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-red-600 border border-red-300 rounded-md hover:bg-red-50">Excluir</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
