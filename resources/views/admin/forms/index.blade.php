@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Formulários e Checklists" icon="fas fa-clipboard-list">
        <x-slot name="actions">
            <button type="button" onclick="openFiliaisModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-building mr-2"></i> Filiais
            </button>
            <a href="{{ route('admin.forms.create') }}" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i> Novo Formulário
            </a>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">{{ session('error') }}</div>
        @endif

        @if($forms->count() > 0)
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($forms as $form)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:border-gray-300 transition">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $form->title }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $form->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            @if($form->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($form->description, 100) }}</p>
                            @endif
                            <div class="flex flex-wrap gap-2 text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-question-circle mr-1"></i> {{ $form->questions_count }} perguntas</span>
                                <span><i class="fas fa-link mr-1"></i> {{ $form->links_count }} links</span>
                                <span><i class="fas fa-reply mr-1"></i> {{ $form->responses_count }} respostas</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.forms.show', $form) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-xs font-medium rounded hover:bg-primary-700">
                                    <i class="fas fa-cog mr-1"></i> Gerenciar
                                </a>
                                <a href="{{ route('admin.forms.stats', $form) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700">
                                    <i class="fas fa-chart-bar mr-1"></i> Estatísticas
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum formulário cadastrado</h3>
                <p class="text-gray-500 mb-4">Comece criando seu primeiro formulário (ex: CRARP - riscos psicossociais).</p>
                <a href="{{ route('admin.forms.create') }}" class="page-header-btn-primary inline-flex">
                    <i class="fas fa-plus mr-2"></i> Criar Primeiro Formulário
                </a>
                <p class="text-sm text-gray-500 mt-4">Não esqueça de cadastrar as <button type="button" onclick="openFiliaisModal()" class="text-primary-600 hover:underline">Filiais</button> para gerar links.</p>
            </div>
        @endif
    </div>
</div>

{{-- Modal Filiais --}}
<div id="modal-filiais" class="fixed inset-0 bg-black bg-opacity-50 z-[10050] hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-2xl w-full max-h-[75vh] overflow-y-auto" @click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Gerenciar Filiais</h3>
            <div class="flex gap-2">
                <button type="button" onclick="openFiliaisCreateModal()" class="page-header-btn-primary text-xs py-1.5">
                    <i class="fas fa-plus mr-1"></i> Nova Filial
                </button>
                <button type="button" onclick="closeFiliaisModal()" class="px-3 py-1.5 text-gray-400 hover:text-white rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        @if($branches->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-600 text-sm">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Nome</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Slug</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Links</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-400 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-600">
                        @foreach($branches as $branch)
                            <tr>
                                <td class="px-3 py-2 text-gray-200">{{ $branch->name }}</td>
                                <td class="px-3 py-2 text-gray-400"><code class="text-xs">{{ $branch->slug }}</code></td>
                                <td class="px-3 py-2 text-gray-400">{{ $branch->form_links_count ?? 0 }} links</td>
                                <td class="px-3 py-2 text-right">
                                    <button type="button" onclick="openFiliaisEditModal({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->slug) }}')" class="text-blue-400 hover:text-blue-300 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta filial? Os links associados também serão removidos.')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="_redirect" value="{{ url()->current() }}">
                                        <button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-building text-3xl mb-3 block"></i>
                <p class="mb-4">Nenhuma filial cadastrada.</p>
                <button type="button" onclick="openFiliaisCreateModal()" class="page-header-btn-primary text-xs">
                    <i class="fas fa-plus mr-1"></i> Cadastrar Primeira Filial
                </button>
            </div>
        @endif
    </div>
</div>

{{-- Modal Criar Filial (dentro do modal Filiais) --}}
<div id="modal-filiais-create" class="fixed inset-0 bg-black bg-opacity-60 z-[10100] hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-md w-full" @click.stop>
        <h3 class="text-lg font-semibold text-white mb-4">Nova Filial</h3>
        <form action="{{ route('admin.branches.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_redirect" value="{{ url()->current() }}">
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
                <button type="button" onclick="closeFiliaisCreateModal()" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Criar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Filial --}}
<div id="modal-filiais-edit" class="fixed inset-0 bg-black bg-opacity-60 z-[10100] hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-md w-full" @click.stop>
        <h3 class="text-lg font-semibold text-white mb-4">Editar Filial</h3>
        <form id="form-filiais-edit" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="_redirect" value="{{ url()->current() }}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nome *</label>
                    <input type="text" name="name" id="edit-filiais-name" required class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Slug</label>
                    <input type="text" name="slug" id="edit-filiais-slug" class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2">
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" onclick="closeFiliaisEditModal()" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openFiliaisModal() {
    document.getElementById('modal-filiais').classList.remove('hidden');
    document.getElementById('modal-filiais').classList.add('flex');
}

function closeFiliaisModal() {
    document.getElementById('modal-filiais').classList.add('hidden');
    document.getElementById('modal-filiais').classList.remove('flex');
}

function openFiliaisCreateModal() {
    document.getElementById('modal-filiais-create').classList.remove('hidden');
    document.getElementById('modal-filiais-create').classList.add('flex');
}

function closeFiliaisCreateModal() {
    document.getElementById('modal-filiais-create').classList.add('hidden');
    document.getElementById('modal-filiais-create').classList.remove('flex');
}

function openFiliaisEditModal(id, name, slug) {
    document.getElementById('form-filiais-edit').action = '{{ url("admin/branches") }}/' + id;
    document.getElementById('edit-filiais-name').value = name;
    document.getElementById('edit-filiais-slug').value = slug;
    document.getElementById('modal-filiais-edit').classList.remove('hidden');
    document.getElementById('modal-filiais-edit').classList.add('flex');
}

function closeFiliaisEditModal() {
    document.getElementById('modal-filiais-edit').classList.add('hidden');
    document.getElementById('modal-filiais-edit').classList.remove('flex');
}

// Fechar modal ao clicar no backdrop
document.getElementById('modal-filiais')?.addEventListener('click', function(e) {
    if (e.target === this) closeFiliaisModal();
});
document.getElementById('modal-filiais-create')?.addEventListener('click', function(e) {
    if (e.target === this) closeFiliaisCreateModal();
});
document.getElementById('modal-filiais-edit')?.addEventListener('click', function(e) {
    if (e.target === this) closeFiliaisEditModal();
});
</script>
@endsection
