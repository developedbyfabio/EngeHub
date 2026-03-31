@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Grupos e Usuários" icon="fas fa-users">
        <x-slot name="actions">
            <button type="button" id="btnOpenGroupsModal" class="page-header-btn-secondary mr-2">
                <i class="fas fa-layer-group mr-2"></i>
                Grupos
            </button>
            <button data-action="create-user" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Criar Usuário
            </button>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if($users->isNotEmpty())
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label for="system-users-search" class="block text-xs font-medium text-gray-500 mb-1">Buscar por nome ou usuário</label>
                        <input type="search" id="system-users-search" autocomplete="off" placeholder="Ex.: Fabio ou fabio.lemes" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div class="w-full sm:w-auto sm:min-w-[12rem]">
                        <label for="system-users-filter-group" class="block text-xs font-medium text-gray-500 mb-1">Grupo</label>
                        <select id="system-users-filter-group" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Todos os grupos</option>
                            @foreach($userGroups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="system-users-filter-reset" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-undo-alt mr-1.5 text-gray-400"></i> Limpar filtros
                    </button>
                </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Username
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grupo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="system-user-row hover:bg-gray-50" data-search="{{ Str::lower($user->name.' '.$user->username) }}" data-group-id="{{ $user->user_group_id ?? '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 flex flex-wrap items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === 1)
                                            <span class="text-xs font-normal text-gray-500 border border-gray-200 rounded px-1.5 py-0.5" title="Usuário raiz; não pode ser excluído pelo painel.">Conta principal</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ $user->userGroup->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- Botão URL Secreta -->
                                            <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                    class="text-indigo-600 hover:text-indigo-900 p-1" 
                                                    title="Gerenciar URL Secreta"
                                                    data-action="secret-url">
                                                <i class="fas fa-link"></i>
                                            </button>
                                            
                                            <!-- Botão Editar -->
                                            <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                    class="text-blue-600 hover:text-blue-900 p-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <!-- Botão Excluir (conta id 1 protegida) -->
                                            @if($user->id !== 1)
                                            <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                    class="text-red-600 hover:text-red-900 p-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @else
                                            <span class="inline-block p-1 text-gray-300 cursor-not-allowed" title="Esta conta principal não pode ser excluída pelo painel.">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Nenhum usuário encontrado.
                                    </td>
                                </tr>
                            @endforelse
                            @if($users->isNotEmpty())
                                <tr id="system-users-filter-empty" class="hidden">
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                        Nenhum usuário corresponde aos filtros selecionados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Criação (altura limitada + rolagem só no corpo) -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110]">
    <div class="flex min-h-full items-center justify-center p-4 py-8">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-900">Criar Novo Usuário</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 min-h-0" id="createModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando formulário...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição (altura limitada + rolagem só no corpo) -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110]">
    <div class="flex min-h-full items-center justify-center p-4 py-8">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-900">Editar Usuário</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 min-h-0" id="editModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando formulário...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de URL Secreta -->
<div id="secretUrlModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="secretUrlModalTitle">
                    <i class="fas fa-link mr-2 text-indigo-600"></i>
                    Gerenciar URL Secreta
                </h3>
                <button onclick="closeSecretUrlModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 max-h-[80vh] overflow-y-auto" id="secretUrlModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Grupos (lista principal) -->
<div id="groupsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-layer-group mr-2 text-amber-600"></i>Grupos de Usuários</h3>
                <button type="button" onclick="closeGroupsModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto flex-1" id="groupsModalBody">
                <p class="text-sm text-gray-600 mb-4">Cada grupo define quais abas do menu (Início, Servidores, Câmeras, Mapas de Rede e itens em <strong>Gerenciar</strong>) os usuários podem ver. O grupo <strong>Administradores</strong> não pode ser editado nem excluído.</p>
                <div class="flex justify-end mb-4">
                    <button type="button" id="btnOpenCreateGroupModal" class="inline-flex items-center px-4 py-2 bg-amber-500 text-black text-sm font-semibold rounded-md hover:bg-amber-600">
                        <i class="fas fa-plus mr-2"></i>Novo grupo
                    </button>
                </div>
                <div id="groupsListContainer" class="space-y-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Sub-modal: criar grupo -->
<div id="createGroupModal" class="fixed inset-0 bg-gray-900 bg-opacity-40 overflow-y-auto h-full w-full hidden z-[120]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] flex flex-col border-2 border-amber-200">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h4 class="font-semibold text-gray-900"><i class="fas fa-plus-circle mr-2 text-amber-600"></i>Novo grupo</h4>
                <button type="button" onclick="closeCreateGroupModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form id="formCreateGroup" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" id="createGroupName" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex.: Administrativo">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="createGroupFullAccess" class="rounded border-gray-300">
                        <label for="createGroupFullAccess" class="text-sm text-gray-700">Acesso total ao menu (equivalente a Administradores)</label>
                    </div>
                    <div id="createGroupCheckboxes" class="grid grid-cols-1 sm:grid-cols-2 gap-2 border border-gray-200 rounded-md p-3 bg-gray-50 max-h-52 overflow-y-auto"></div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="closeCreateGroupModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 text-black font-semibold text-sm rounded-md hover:bg-amber-600">Criar grupo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sub-modal: editar grupo -->
<div id="editGroupModal" class="fixed inset-0 bg-gray-900 bg-opacity-40 overflow-y-auto h-full w-full hidden z-[120]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 max-h-[90vh] flex flex-col border-2 border-blue-200">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h4 class="font-semibold text-gray-900"><i class="fas fa-edit mr-2 text-blue-600"></i>Editar grupo</h4>
                <button type="button" onclick="closeEditGroupModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-4 overflow-y-auto">
                <form id="formEditGroup" class="space-y-4">
                    <input type="hidden" id="editGroupId">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" id="editGroupName" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="editGroupFullAccess" class="rounded border-gray-300">
                        <label for="editGroupFullAccess" class="text-sm text-gray-700">Acesso total ao menu</label>
                    </div>
                    <div id="editGroupCheckboxes" class="grid grid-cols-1 sm:grid-cols-2 gap-2 border border-gray-200 rounded-md p-3 bg-gray-50 max-h-52 overflow-y-auto"></div>
                    <p id="editGroupSlugHint" class="text-xs text-gray-500"></p>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="closeEditGroupModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-md hover:bg-blue-700">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sub-modal: excluir grupo (senha) -->
<div id="deleteGroupModal" class="fixed inset-0 bg-gray-900 bg-opacity-40 overflow-y-auto h-full w-full hidden z-[120]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 border-2 border-red-200">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h4 class="font-semibold text-gray-900"><i class="fas fa-trash-alt mr-2 text-red-600"></i>Excluir grupo</h4>
                <button type="button" onclick="closeDeleteGroupModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-4 space-y-4">
                <p class="text-sm text-gray-700">Confirme a exclusão do grupo <strong id="deleteGroupLabel"></strong>. Esta ação não pode ser desfeita.</p>
                <input type="hidden" id="deleteGroupId">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Senha de exclusão</label>
                    <input type="password" id="deleteGroupPassword" autocomplete="off" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Digite a senha">
                    <p id="deleteGroupPasswordErr" class="mt-1 text-sm text-red-600 hidden"></p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeDeleteGroupModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="button" id="btnConfirmDeleteGroup" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700">Excluir definitivamente</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    window.NAV_LABELS = @json(\App\Support\NavPermission::labels());
    window.NAV_KEYS = @json(\App\Support\NavPermission::allKeys());
    window.ADMIN_GROUP_ID = @json($userGroups->firstWhere('slug', \App\Models\UserGroup::SLUG_ADMINISTRADORES)?->id);

    function showGroupToast(message, type) {
        const t = type || 'success';
        if (typeof window.showToast === 'function') {
            window.showToast(message, t, t === 'error' ? 5000 : 4500);
        } else {
            console.warn(message);
        }
    }

    function applySystemUserFilters() {
        const q = (document.getElementById('system-users-search')?.value || '').trim().toLowerCase();
        const gid = document.getElementById('system-users-filter-group')?.value || '';
        const rows = document.querySelectorAll('tr.system-user-row');
        let visible = 0;
        rows.forEach(function(tr) {
            let ok = true;
            if (q) {
                const hay = (tr.getAttribute('data-search') || '').toLowerCase();
                if (hay.indexOf(q) === -1) ok = false;
            }
            if (gid && String(tr.getAttribute('data-group-id') || '') !== gid) ok = false;
            if (ok) visible++;
            tr.classList.toggle('hidden', !ok);
        });
        const er = document.getElementById('system-users-filter-empty');
        if (er) er.classList.toggle('hidden', visible !== 0);
    }

    function setupSystemUserListFilters() {
        const s = document.getElementById('system-users-search');
        const g = document.getElementById('system-users-filter-group');
        const r = document.getElementById('system-users-filter-reset');
        if (s) s.addEventListener('input', applySystemUserFilters);
        if (g) g.addEventListener('change', applySystemUserFilters);
        if (r) {
            r.addEventListener('click', function() {
                if (s) s.value = '';
                if (g) g.value = '';
                applySystemUserFilters();
            });
        }
    }

    // Aguardar o DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        setupEventListeners();
        setupModalCloseListeners();
        setupGroupsModalUi();
        setupSystemUserListFilters();
    });

    function setupEventListeners() {
        // Botão de criar usuário
        const createBtn = document.querySelector('button[data-action="create-user"]');
        if (createBtn) {
            createBtn.removeAttribute('data-action');
            createBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openCreateModal();
            });
        }

        // Botões de ação na tabela - URL Secreta (link índigo)
        document.querySelectorAll('button[data-action="secret-url"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                openSecretUrlModal(userId, userName);
            });
        });


        // Botões de ação na tabela - Editar (ícone de edição)
        document.querySelectorAll('button[data-user-id]').forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon && icon.classList.contains('fa-edit')) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const userId = this.getAttribute('data-user-id');
                    openEditModal(userId);
                });
            }
        });

        // Botões de ação na tabela - Excluir (ícone de lixeira)
        document.querySelectorAll('button[data-user-id]').forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon && icon.classList.contains('fa-trash')) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    deleteUser(userId, userName);
                });
            }
        });
    }

    function setupModalCloseListeners() {
        // Fechar modais ao clicar fora deles
        document.addEventListener('click', function(e) {
            if (e.target.id === 'createModal') {
                closeCreateModal();
            }
            if (e.target.id === 'editModal') {
                closeEditModal();
            }
            if (e.target.id === 'secretUrlModal') {
                closeSecretUrlModal();
            }
            if (e.target.id === 'groupsModal') {
                closeGroupsModal();
            }
            if (e.target.id === 'createGroupModal') {
                closeCreateGroupModal();
            }
            if (e.target.id === 'editGroupModal') {
                closeEditGroupModal();
            }
            if (e.target.id === 'deleteGroupModal') {
                closeDeleteGroupModal();
            }
        });
    }

    window._groupsCache = [];

    function setupGroupsModalUi() {
        const btn = document.getElementById('btnOpenGroupsModal');
        if (btn) btn.addEventListener('click', () => openGroupsModal());
        const btnNew = document.getElementById('btnOpenCreateGroupModal');
        if (btnNew) btnNew.addEventListener('click', () => openCreateGroupModal());

        const cfa = document.getElementById('createGroupFullAccess');
        if (cfa) {
            cfa.addEventListener('change', () => syncFullAccessUi('create'));
        }
        const efa = document.getElementById('editGroupFullAccess');
        if (efa) {
            efa.addEventListener('change', () => syncFullAccessUi('edit'));
        }

        const formCreate = document.getElementById('formCreateGroup');
        if (formCreate && !formCreate.dataset.bound) {
            formCreate.dataset.bound = '1';
            formCreate.addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData();
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                fd.append('name', document.getElementById('createGroupName').value);
                const full = document.getElementById('createGroupFullAccess').checked;
                fd.append('full_access', full ? '1' : '0');
                if (!full) {
                    (window.NAV_KEYS || []).forEach(k => {
                        const el = document.querySelector(`#createGroupCheckboxes input[data-nav-key="${k}"]`);
                        if (el && el.checked) fd.append(`nav_permissions[${k}]`, '1');
                    });
                }
                fetch('{{ route("admin.user-groups.store") }}', {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        closeCreateGroupModal();
                        loadGroupsList();
                        showGroupToast(data.message || 'Grupo criado.', 'success');
                    } else {
                        showGroupToast(data.message || (data.errors ? JSON.stringify(data.errors) : 'Erro ao criar grupo.'), 'error');
                    }
                })
                .catch(() => showGroupToast('Erro ao criar grupo.', 'error'));
            });
        }

        const formEdit = document.getElementById('formEditGroup');
        if (formEdit && !formEdit.dataset.bound) {
            formEdit.dataset.bound = '1';
            formEdit.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('editGroupId').value;
                const fd = new FormData();
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                fd.append('_method', 'PUT');
                fd.append('name', document.getElementById('editGroupName').value);
                const full = document.getElementById('editGroupFullAccess').checked;
                fd.append('full_access', full ? '1' : '0');
                if (!full) {
                    (window.NAV_KEYS || []).forEach(k => {
                        const el = document.querySelector(`#editGroupCheckboxes input[data-nav-key="${k}"]`);
                        if (el && el.checked) fd.append(`nav_permissions[${k}]`, '1');
                    });
                }
                fetch(`/admin/user-groups/${id}`, {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        closeEditGroupModal();
                        loadGroupsList();
                        showGroupToast(data.message || 'Grupo atualizado.', 'success');
                    } else {
                        showGroupToast(data.message || 'Erro ao atualizar.', 'error');
                    }
                })
                .catch(() => showGroupToast('Erro ao atualizar grupo.', 'error'));
            });
        }

        const btnDel = document.getElementById('btnConfirmDeleteGroup');
        if (btnDel && !btnDel.dataset.bound) {
            btnDel.dataset.bound = '1';
            btnDel.addEventListener('click', submitDeleteGroup);
        }
    }

    function buildGroupCheckboxes(containerId, permMap, allCheckedDefault) {
        const container = document.getElementById(containerId);
        if (!container || !window.NAV_LABELS) return;
        container.innerHTML = '';
        Object.entries(window.NAV_LABELS).forEach(([key, label]) => {
            const id = containerId + '_' + key;
            const checked = allCheckedDefault ? true : !!((permMap && permMap[key]) === true);
            const wrap = document.createElement('label');
            wrap.className = 'flex items-center gap-2 text-sm text-gray-700';
            wrap.innerHTML = `<input type="checkbox" data-nav-key="${key}" id="${id}" class="rounded border-gray-300 nav-key-cb" ${checked ? 'checked' : ''}> <span>${label}</span>`;
            container.appendChild(wrap);
        });
    }

    function syncFullAccessUi(mode) {
        const fullEl = document.getElementById(mode === 'create' ? 'createGroupFullAccess' : 'editGroupFullAccess');
        const box = document.getElementById(mode === 'create' ? 'createGroupCheckboxes' : 'editGroupCheckboxes');
        if (!fullEl || !box) return;
        const on = fullEl.checked;
        box.classList.toggle('opacity-50', on);
        box.classList.toggle('pointer-events-none', on);
        box.querySelectorAll('input[type="checkbox"]').forEach(cb => { cb.disabled = on; });
    }

    function openGroupsModal() {
        document.getElementById('groupsModal').classList.remove('hidden');
        loadGroupsList();
    }

    function closeGroupsModal() {
        document.getElementById('groupsModal').classList.add('hidden');
        closeCreateGroupModal();
        closeEditGroupModal();
        closeDeleteGroupModal();
    }

    function openCreateGroupModal() {
        document.getElementById('createGroupName').value = '';
        document.getElementById('createGroupFullAccess').checked = false;
        buildGroupCheckboxes('createGroupCheckboxes', null, true);
        syncFullAccessUi('create');
        document.getElementById('createGroupModal').classList.remove('hidden');
    }

    function closeCreateGroupModal() {
        const m = document.getElementById('createGroupModal');
        if (m) m.classList.add('hidden');
    }

    function openEditGroupModal(g) {
        if (!g || g.slug === 'administradores') return;
        document.getElementById('editGroupId').value = g.id;
        document.getElementById('editGroupName').value = g.name;
        document.getElementById('editGroupFullAccess').checked = !!g.full_access;
        document.getElementById('editGroupSlugHint').textContent = 'Identificador interno: ' + (g.slug || '');
        const perms = g.nav_permissions || {};
        buildGroupCheckboxes('editGroupCheckboxes', perms, false);
        if (g.full_access) {
            document.querySelectorAll('#editGroupCheckboxes input.nav-key-cb').forEach(cb => { cb.checked = true; });
        }
        syncFullAccessUi('edit');
        document.getElementById('editGroupModal').classList.remove('hidden');
    }

    function closeEditGroupModal() {
        const m = document.getElementById('editGroupModal');
        if (m) m.classList.add('hidden');
    }

    function openDeleteGroupModal(g) {
        if (!g || g.slug === 'administradores') return;
        document.getElementById('deleteGroupId').value = g.id;
        document.getElementById('deleteGroupLabel').textContent = g.name;
        document.getElementById('deleteGroupPassword').value = '';
        document.getElementById('deleteGroupPasswordErr').classList.add('hidden');
        document.getElementById('deleteGroupModal').classList.remove('hidden');
    }

    function closeDeleteGroupModal() {
        const m = document.getElementById('deleteGroupModal');
        if (m) m.classList.add('hidden');
    }

    function submitDeleteGroup() {
        const id = document.getElementById('deleteGroupId').value;
        const pwd = document.getElementById('deleteGroupPassword').value;
        const errEl = document.getElementById('deleteGroupPasswordErr');
        errEl.classList.add('hidden');
        if (!pwd) {
            errEl.textContent = 'Informe a senha.';
            errEl.classList.remove('hidden');
            return;
        }
        const fd = new FormData();
        fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        fd.append('_method', 'DELETE');
        fd.append('delete_password', pwd);
        fetch(`/admin/user-groups/${id}`, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (data.success) {
                closeDeleteGroupModal();
                loadGroupsList();
                showGroupToast(data.message || 'Grupo removido.', 'success');
            } else {
                errEl.textContent = data.message || 'Não foi possível excluir.';
                errEl.classList.remove('hidden');
            }
        })
        .catch(() => {
            errEl.textContent = 'Erro na requisição.';
            errEl.classList.remove('hidden');
        });
    }

    function loadGroupsList() {
        const c = document.getElementById('groupsListContainer');
        if (!c) return;
        c.innerHTML = '<p class="text-gray-500 text-sm">Carregando…</p>';
        fetch('{{ route("admin.user-groups.index") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            const list = Array.isArray(data.groups) ? data.groups : (Array.isArray(data) ? data : []);
            window._groupsCache = list;
            if (!list.length) {
                c.innerHTML = '<p class="text-gray-500">Nenhum grupo.</p>';
                return;
            }
            c.innerHTML = '';
            list.forEach(g => {
                const row = document.createElement('div');
                row.className = 'flex flex-wrap items-center justify-between gap-2 p-3 border border-gray-200 rounded-lg bg-white';
                const left = document.createElement('div');
                left.className = 'min-w-0 flex-1';
                left.innerHTML = `<strong class="text-gray-900">${escapeHtml(g.name)}</strong>` +
                    (g.full_access ? ' <span class="text-xs text-purple-600">(acesso total)</span>' : '') +
                    (g.is_system ? ' <span class="text-xs text-gray-500">sistema</span>' : '');
                const right = document.createElement('div');
                right.className = 'flex flex-wrap items-center gap-2';

                const slugAdm = 'administradores';
                if (g.slug !== slugAdm) {
                    const btnEdit = document.createElement('button');
                    btnEdit.type = 'button';
                    btnEdit.className = 'text-sm px-2 py-1 text-blue-700 border border-blue-200 rounded hover:bg-blue-50';
                    btnEdit.textContent = 'Editar';
                    btnEdit.onclick = () => openEditGroupModal(g);
                    right.appendChild(btnEdit);
                    const btnDel = document.createElement('button');
                    btnDel.type = 'button';
                    btnDel.className = 'text-sm px-2 py-1 text-red-700 border border-red-200 rounded hover:bg-red-50';
                    btnDel.textContent = 'Excluir';
                    btnDel.onclick = () => openDeleteGroupModal(g);
                    right.appendChild(btnDel);
                }
                row.appendChild(left);
                row.appendChild(right);
                c.appendChild(row);
            });
        })
        .catch(() => { c.innerHTML = '<p class="text-red-600 text-sm">Erro ao carregar grupos.</p>'; });
    }

    function escapeHtml(s) {
        if (!s) return '';
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // Funções para o modal de criação
    function openCreateModal() {
        const modal = document.getElementById('createModal');
        if (modal) {
            modal.classList.remove('hidden');
            loadCreateForm();
        }
    }

    function closeCreateModal() {
        const modal = document.getElementById('createModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Funções para o modal de edição
    function openEditModal(userId) {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.classList.remove('hidden');
            loadEditForm(userId);
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }


    // Função para excluir usuário
    function deleteUser(userId, userName) {
        if (confirm(`Tem certeza que deseja excluir o usuário "${userName}"?`)) {
            fetch(`/admin/system-users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erro ao excluir usuário: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erro ao excluir usuário. Tente novamente.');
            });
        }
    }

    // Carregar formulário de criação
    function loadCreateForm() {
        fetch('{{ route("admin.system-users.create") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.text())
            .then(html => {
                const content = document.getElementById('createModalContent');
                if (content) {
                    content.innerHTML = html;
                    setupCreateForm();
                }
            })
            .catch(error => {
                const content = document.getElementById('createModalContent');
                if (content) {
                    content.innerHTML = `
                        <div class="text-center text-red-600">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Erro ao carregar o formulário. Tente novamente.</p>
                        </div>
                    `;
                }
            });
    }

    function setupCreateForm() {
        const createForm = document.getElementById('createForm');
        const admId = window.ADMIN_GROUP_ID;
        const sel = document.getElementById('user_group_id');
        const wrap = document.getElementById('wrap_user_group_id');
        const cb = document.getElementById('is_admin');
        if (cb && sel && admId) {
            function syncAdminGroup() {
                if (cb.checked) {
                    sel.removeAttribute('required');
                    sel.value = String(admId);
                    if (wrap) wrap.classList.add('opacity-50', 'pointer-events-none');
                } else {
                    sel.setAttribute('required', 'required');
                    if (wrap) wrap.classList.remove('opacity-50', 'pointer-events-none');
                }
            }
            cb.addEventListener('change', syncAdminGroup);
            syncAdminGroup();
        }
        if (createForm && !createForm.dataset.submitBound) {
            createForm.dataset.submitBound = '1';
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeCreateModal();
                        window.location.reload();
                    } else {
                        alert('Erro ao criar usuário: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao criar usuário. Tente novamente.');
                });
            });
        }
    }

    // Carregar formulário de edição
    function loadEditForm(userId) {
        fetch(`/admin/system-users/${userId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.text())
            .then(html => {
                const content = document.getElementById('editModalContent');
                if (content) {
                    content.innerHTML = html;
                    setupEditForm();
                }
            })
            .catch(error => {
                const content = document.getElementById('editModalContent');
                if (content) {
                    content.innerHTML = `
                        <div class="text-center text-red-600">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Erro ao carregar o formulário. Tente novamente.</p>
                        </div>
                    `;
                }
            });
    }

    function setupEditForm() {
        const editForm = document.getElementById('editForm');
        if (editForm && !editForm.dataset.submitBound) {
            editForm.dataset.submitBound = '1';
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'PUT',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeEditModal();
                        window.location.reload();
                    } else {
                        alert('Erro ao atualizar usuário: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao atualizar usuário. Tente novamente.');
                });
            });
        }
    }


    // Funções para o modal de URL Secreta
    function openSecretUrlModal(userId, userName) {
        document.getElementById('secretUrlModalTitle').innerHTML = `<i class="fas fa-link mr-2 text-indigo-600"></i>URL Secreta - ${userName}`;
        document.getElementById('secretUrlModal').classList.remove('hidden');
        loadSecretUrlForm(userId);
    }

    function closeSecretUrlModal() {
        document.getElementById('secretUrlModal').classList.add('hidden');
    }

    function loadSecretUrlForm(userId) {
        fetch(`/admin/system-users/${userId}/secret-url`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                document.getElementById('secretUrlModalContent').innerHTML = data.html;
            } else {
                document.getElementById('secretUrlModalContent').innerHTML = `
                    <div class="text-center text-red-600">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Erro ao carregar dados da URL secreta</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            document.getElementById('secretUrlModalContent').innerHTML = `
                <div class="text-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erro ao carregar dados. Tente novamente.</p>
                </div>
            `;
        });
    }
</script>
