@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Filiais" icon="fas fa-building">
        <x-slot name="actions">
            <button onclick="openCreateModal()" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i> Nova Filial
            </button>
            <a href="{{ route('admin.forms.index') }}" class="text-gray-600 hover:text-gray-900 font-medium ml-2">
                <i class="fas fa-arrow-left mr-2"></i> Formulários
            </a>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($branches->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Links</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($branches as $branch)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $branch->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><code>{{ $branch->slug }}</code></td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $branch->form_links_count ?? 0 }} links</td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="openEditModal({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->slug) }}')" class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta filial? Os links associados também serão removidos.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-building text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma filial cadastrada</h3>
                        <p class="text-gray-500 mb-4">Cadastre as filiais para gerar links únicos por unidade.</p>
                        <button onclick="openCreateModal()" class="page-header-btn-primary">
                            <i class="fas fa-plus mr-2"></i> Cadastrar Primeira Filial
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Criar --}}
<div id="modal-create" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold text-white mb-4">Nova Filial</h3>
        <form action="{{ route('admin.branches.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nome *</label>
                    <input type="text" name="name" required class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Slug (opcional)</label>
                    <input type="text" name="slug" placeholder="ex: goiania" class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Criar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar --}}
<div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold text-white mb-4">Editar Filial</h3>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nome *</label>
                    <input type="text" name="name" id="edit-name" required class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Slug</label>
                    <input type="text" name="slug" id="edit-slug" class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modal-create').classList.remove('hidden');
    document.getElementById('modal-create').classList.add('flex');
}

function closeCreateModal() {
    document.getElementById('modal-create').classList.add('hidden');
    document.getElementById('modal-create').classList.remove('flex');
}

function openEditModal(id, name, slug) {
    document.getElementById('form-edit').action = '{{ url("admin/branches") }}/' + id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-slug').value = slug;
    document.getElementById('modal-edit').classList.remove('hidden');
    document.getElementById('modal-edit').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('modal-edit').classList.add('hidden');
    document.getElementById('modal-edit').classList.remove('flex');
}
</script>
@endsection
