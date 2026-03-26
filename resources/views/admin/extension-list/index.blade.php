@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Lista de Ramais" icon="fas fa-phone-alt">
        <x-slot name="actions">
            <a href="{{ route('home') }}" class="page-header-btn-secondary">
                <i class="fas fa-home mr-2"></i>
                Início
            </a>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 space-y-6">
                @if(session('success'))
                    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="rounded-lg border border-amber-100 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    <p class="font-medium"><i class="fas fa-info-circle mr-1"></i> Como usar</p>
                    <p class="mt-1 text-amber-800/90">
                        Converta o PDF oficial da lista para <strong>SVG</strong> (Illustrator, Inkscape ou ferramentas online) e envie aqui.
                        No <strong>Início</strong>, usuários autenticados verão o botão <strong>Lista de Ramais</strong> com visualização em tela cheia e busca por nome no desenho.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Arquivo atual</h3>
                    @if($document)
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-vector-square text-amber-600 mr-1"></i>
                            {{ $document->original_filename ?: 'lista-ramais.svg' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Atualizado em {{ $document->updated_at->format('d/m/Y H:i') }}</p>
                    @else
                        <p class="text-sm text-gray-500">Nenhum SVG cadastrado. O botão no Início não será exibido.</p>
                    @endif
                </div>

                <form action="{{ route('admin.extension-list.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 border-t border-gray-100 pt-6">
                    @csrf
                    <div>
                        <label for="extension_list_svg" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $document ? 'Substituir pelo novo SVG' : 'Enviar SVG' }}
                        </label>
                        <input type="file" name="svg" id="extension_list_svg" accept=".svg,image/svg+xml" required
                               class="block w-full text-sm text-gray-800 file:mr-4 file:rounded-md file:border-0 file:bg-amber-500 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-black hover:file:bg-amber-600">
                        @error('svg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Apenas SVG, até 50&nbsp;MB. Scripts e iframes são removidos na importação.</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold text-black btn-engehub-yellow border border-transparent shadow-sm">
                            <i class="fas fa-upload mr-2"></i>
                            {{ $document ? 'Atualizar lista' : 'Cadastrar lista' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>.btn-engehub-yellow{background-color:#E9B32C!important;color:#000!important}.btn-engehub-yellow:hover{background-color:#d19d20!important}</style>
@endsection
