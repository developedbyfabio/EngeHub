@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Usuários" icon="fas fa-users">
        <x-slot name="actions">
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
                                    Permissões
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @php
                                                $isAdmin = $user->hasUserPermission(\App\Models\UserPermission::FULL_ACCESS) || 
                                                          $user->hasUserPermission(\App\Models\UserPermission::MANAGE_SYSTEM_USERS);
                                            @endphp
                                            @if($isAdmin)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Administrador
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Usuário Comum
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- Botão Gerenciar Permissões -->
                                            <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                    class="text-purple-600 hover:text-purple-900 p-1" 
                                                    title="Gerenciar permissões">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                            
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
                                            
                                            <!-- Botão Excluir -->
                                            <button data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                    class="text-red-600 hover:text-red-900 p-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Criação -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Criar Novo Usuário</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6" id="createModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando formulário...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Editar Usuário</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6" id="editModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando formulário...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Gerenciar Permissões -->
<div id="permissionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900" id="permissionsModalTitle">Gerenciar Permissões</h3>
                <button onclick="closePermissionsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="permissionsModalContent">
                <div class="p-6 text-center">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                    <p class="mt-2 text-gray-600">Carregando permissões...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de URL Secreta -->
<div id="secretUrlModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
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

@endsection

<script>
    // Aguardar o DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        // Adicionar event listeners aos botões
        setupEventListeners();
        
        // Fechar modais ao clicar fora deles
        setupModalCloseListeners();
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

        // Botões de ação na tabela - Gerenciar Permissões (escudo roxo)
        document.querySelectorAll('button[data-user-id]').forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon && icon.classList.contains('fa-shield-alt')) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    openPermissionsModal(userId, userName);
                });
            }
        });

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
            if (e.target.id === 'permissionsModal') {
                closePermissionsModal();
            }
            if (e.target.id === 'secretUrlModal') {
                closeSecretUrlModal();
            }
        });
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
        if (createForm) {
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
        if (editForm) {
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


    // Funções para o modal de permissões
    function openPermissionsModal(userId, userName) {
        document.getElementById('permissionsModalTitle').textContent = `Gerenciar Permissões - ${userName}`;
        document.getElementById('permissionsModal').classList.remove('hidden');
        loadPermissionsForm(userId);
    }

    function closePermissionsModal() {
        document.getElementById('permissionsModal').classList.add('hidden');
    }

    function loadPermissionsForm(userId) {
        fetch(`/admin/system-users/${userId}/permissions`, {
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
                document.getElementById('permissionsModalContent').innerHTML = data.html;
            } else {
                document.getElementById('permissionsModalContent').innerHTML = `
                    <div class="p-6 text-center text-red-600">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Erro ao carregar permissões</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('permissionsModalContent').innerHTML = `
                <div class="p-6 text-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erro ao carregar permissões. Tente novamente.</p>
                </div>
            `;
        });
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
