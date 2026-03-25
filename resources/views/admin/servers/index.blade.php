@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Servidores" icon="fas fa-server">
        <x-slot name="actions">
            <button onclick="openManageGroupsModal()" class="page-header-btn-secondary">
                <i class="fas fa-layer-group mr-2"></i>
                Gerenciar Grupos
            </button>
            <button onclick="openDataCentersModal()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition ease-in-out duration-150">
                <i class="fas fa-server mr-2"></i>
                Gerenciar Data Centers
            </button>
            <button onclick="openCreateModal()" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Servidor
            </button>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if($servers->count() > 0)
                    <!-- Tabela de Servidores (larguras fixas para evitar rolagem horizontal) -->
                    <div class="overflow-hidden">
                        <table class="w-full table-fixed divide-y divide-gray-200">
                            <colgroup>
                                <col style="width: 17%">
                                <col style="width: 14%">
                                <col style="width: 15%">
                                <col style="width: 11%">
                                <col style="width: 11%">
                                <col style="width: 12%">
                                <col style="width: 10%">
                            </colgroup>
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Servidor
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        IP / Grupo
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Data Center
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sistema
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Monitoramento
                                    </th>
                                    <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($servers as $server)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 align-top overflow-hidden">
                                            <div class="flex items-start gap-2 min-w-0">
                                                @if($server->logo_url)
                                                    <img src="{{ $server->logo_url }}" 
                                                         alt="{{ $server->name }}" 
                                                         class="w-8 h-8 rounded-md object-cover flex-shrink-0 mt-0.5">
                                                @else
                                                    <div class="w-8 h-8 bg-gray-100 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <i class="fas fa-server text-gray-500 text-sm"></i>
                                                    </div>
                                                @endif
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-medium text-gray-900 truncate" title="{{ $server->name }}">{{ $server->name }}</div>
                                                    @if($server->description)
                                                        <div class="text-xs text-gray-500 line-clamp-2 break-words mt-0.5" title="{{ $server->description }}">{{ $server->description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 align-top overflow-hidden">
                                            <div class="text-sm text-gray-900 truncate font-mono" title="{{ $server->ip_address }}">{{ $server->ip_address }}</div>
                                            @if($server->serverGroup)
                                                <div class="flex items-center text-xs text-gray-500 mt-1 min-w-0">
                                                    <div class="w-2.5 h-2.5 rounded-full mr-1.5 flex-shrink-0" style="background-color: {{ $server->serverGroup->color }}"></div>
                                                    <span class="truncate">{{ $server->serverGroup->name }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 align-top text-sm text-gray-900 overflow-hidden">
                                            <span class="line-clamp-2 break-words">{{ $server->dataCenter ? $server->dataCenter->name : '-' }}</span>
                                        </td>
                                        <td class="px-3 py-4 align-top text-sm text-gray-900 overflow-hidden">
                                            @if($server->operating_system)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $server->operating_system == 'Linux' ? 'bg-green-100 text-green-800' : 
                                                       ($server->operating_system == 'Windows' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    @if($server->operating_system == 'Linux')
                                                        <i class="fab fa-linux mr-0.5 text-[10px]"></i>
                                                    @elseif($server->operating_system == 'Windows')
                                                        <i class="fab fa-windows mr-0.5 text-[10px]"></i>
                                                    @else
                                                        <i class="fas fa-desktop mr-0.5 text-[10px]"></i>
                                                    @endif
                                                    {{ $server->operating_system }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 align-top overflow-hidden">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $server->status_class }} text-white">
                                                {{ $server->status_text }}
                                            </span>
                                            @if($server->response_time)
                                                <div class="text-xs text-gray-500 mt-1">{{ $server->response_time }}ms</div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 align-top overflow-hidden">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $server->monitor_status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $server->monitor_status ? 'Ativo' : 'Inativo' }}
                                            </span>
                                            @if($server->last_status_check)
                                                <div class="text-xs text-gray-500 mt-1 whitespace-nowrap">{{ $server->last_status_check->format('d/m H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-right text-sm font-medium align-top whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-2">
                                                @if($server->monitor_status)
                                                    <button onclick="checkServerStatus({{ $server->id }})" 
                                                            class="text-blue-600 hover:text-blue-900" 
                                                            title="Verificar Status">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                @endif
                                                <button onclick="editServer({{ $server->id }})" 
                                                        class="text-indigo-600 hover:text-indigo-900" 
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteServer({{ $server->id }}, '{{ $server->name }}')" 
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Estado Vazio -->
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-server text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum servidor cadastrado</h3>
                        <p class="text-gray-500 mb-4">Comece criando seu primeiro servidor.</p>
                        <button onclick="openCreateModal()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Primeiro Servidor
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Modal (z-index acima do nav fixo z-40; altura limitada à viewport com rolagem interna) -->
<div id="serverModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-600 bg-opacity-50 p-4 hidden">
    <div class="relative flex min-h-0 w-full max-w-2xl max-h-[min(90vh,calc(100dvh-2rem))] flex-col rounded-md border border-gray-200 bg-white shadow-lg">
        <div id="modalContent" class="min-h-0 flex-1 overflow-y-auto px-5 py-5">
            <!-- Conteúdo será carregado aqui -->
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    fetch('{{ route("admin.servers.create") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalContent').innerHTML = data.html;
        document.getElementById('serverModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao carregar formulário');
    });
}

function editServer(serverId) {
    fetch(`/admin/servers/${serverId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalContent').innerHTML = data.html;
        document.getElementById('serverModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao carregar formulário de edição');
    });
}

function closeModal() {
    document.getElementById('serverModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function deleteServer(serverId, serverName) {
    if (confirm(`Tem certeza que deseja excluir o servidor "${serverName}"?`)) {
        fetch(`/admin/servers/${serverId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof window.queueToastAfterReload === 'function') {
                    window.queueToastAfterReload(data.message || 'Servidor excluído com sucesso!', 'success', 5000);
                }
                location.reload();
            } else {
                alert(data.message || 'Erro ao excluir servidor');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir servidor');
        });
    }
}

function checkServerStatus(serverId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    
    // Mostrar loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch(`/admin/servers/${serverId}/check-status`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof window.queueToastAfterReload === 'function') {
                window.queueToastAfterReload(data.message || 'Status verificado com sucesso!', 'success', 5000);
            }
            location.reload();
        } else {
            alert('Erro ao verificar status do servidor');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao verificar status do servidor');
    })
    .finally(() => {
        // Restaurar botão
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

// Fechar modal clicando no backdrop
document.getElementById('serverModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Delegar eventos para formulários carregados dinamicamente
document.addEventListener('submit', function(e) {
    if (e.target.id === 'createServerForm') {
        e.preventDefault();
        handleCreateServerForm(e.target);
    } else if (e.target.id === 'editServerForm') {
        e.preventDefault();
        handleEditServerForm(e.target);
    }
});

function handleCreateServerForm(form) {
    console.log('Formulário de criação enviado');
    
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Corrigir checkbox monitor_status - se não estiver marcado, adicionar como false
    const monitorCheckbox = form.querySelector('#monitor_status');
    if (monitorCheckbox) {
        if (!monitorCheckbox.checked) {
            formData.set('monitor_status', '0');
        } else {
            formData.set('monitor_status', '1');
        }
    }
    
    // Debug: mostrar dados do formulário
    console.log('Dados do formulário:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Mostrar loading
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Criando...';
    submitButton.disabled = true;
    
    // Verificar se o token CSRF está disponível
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('Token CSRF não encontrado!');
        return;
    }
    
    console.log('CSRF Token:', csrfToken.getAttribute('content'));
    
    fetch('{{ route("admin.servers.store") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            closeModal();
            if (typeof window.queueToastAfterReload === 'function') {
                window.queueToastAfterReload(data.message || 'Servidor criado com sucesso!', 'success', 5000);
            }
            location.reload();
        } else {
            // Mostrar erros de validação
            if (data.errors) {
                let errorMessage = 'Erros de validação:\n';
                for (const field in data.errors) {
                    errorMessage += `- ${data.errors[field][0]}\n`;
                }
                alert(errorMessage);
            } else {
                alert(data.message || 'Erro ao criar servidor');
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar servidor: ' + error.message);
    })
    .finally(() => {
        // Restaurar botão
        submitButton.innerHTML = originalContent;
        submitButton.disabled = false;
    });
}

function handleEditServerForm(form) {
    console.log('Formulário de edição enviado');
    
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Corrigir checkbox monitor_status - se não estiver marcado, adicionar como false
    const monitorCheckbox = form.querySelector('#monitor_status');
    if (monitorCheckbox) {
        if (!monitorCheckbox.checked) {
            formData.set('monitor_status', '0');
        } else {
            formData.set('monitor_status', '1');
        }
    }
    
    // Debug: mostrar dados do formulário
    console.log('Dados do formulário:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Mostrar loading
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Atualizando...';
    submitButton.disabled = true;
    
    // Verificar se o token CSRF está disponível
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('Token CSRF não encontrado!');
        return;
    }
    
    console.log('CSRF Token:', csrfToken.getAttribute('content'));
    
    // Extrair ID do servidor da URL do formulário
    const formAction = form.action;
    const serverId = formAction.split('/').pop();
    
    // Adicionar _method=PUT para Laravel reconhecer como atualização
    formData.append('_method', 'PUT');
    
    fetch(`/admin/servers/${serverId}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            closeModal();
            if (typeof window.queueToastAfterReload === 'function') {
                window.queueToastAfterReload(data.message || 'Servidor atualizado com sucesso!', 'success', 5000);
            }
            location.reload();
        } else {
            // Mostrar erros de validação
            if (data.errors) {
                let errorMessage = 'Erros de validação:\n';
                for (const field in data.errors) {
                    errorMessage += `- ${data.errors[field][0]}\n`;
                }
                alert(errorMessage);
            } else {
                alert(data.message || 'Erro ao atualizar servidor');
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar servidor: ' + error.message);
    })
    .finally(() => {
        // Restaurar botão
        submitButton.innerHTML = originalContent;
        submitButton.disabled = false;
    });
}

    // Variável global para controlar o estado do modal
    let groupsModalOpen = false;

    // Função para abrir modal de gerenciar grupos
    function openManageGroupsModal() {
        document.getElementById('manageGroupsModal').classList.remove('hidden');
        document.body.classList.add('modal-open');
        groupsModalOpen = true;
        loadGroupsList();
    }

    // Função para fechar modal de gerenciar grupos
    function closeManageGroupsModal() {
        document.getElementById('manageGroupsModal').classList.add('hidden');
        document.body.classList.remove('modal-open');
        groupsModalOpen = false;
    }

    // Carregar lista de grupos
    function loadGroupsList() {
        fetch('{{ route("admin.server-groups.index") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            renderGroupsList(data.serverGroups);
        })
        .catch(error => {
            console.error('Erro ao carregar lista de grupos:', error);
            document.getElementById('groupsList').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                    <p class="text-red-500 mt-2">Erro ao carregar grupos</p>
                </div>
            `;
        });
    }

    // Renderizar lista de grupos
    function renderGroupsList(groups) {
        const container = document.getElementById('groupsList');
        
        if (groups.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-layer-group text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum grupo cadastrado</h3>
                    <p class="text-gray-500 mb-4">Comece criando seu primeiro grupo para organizar os servidores.</p>
                </div>
            `;
            return;
        }

        let html = `
            <div class="space-y-3">
        `;

        groups.forEach(group => {
            html += `
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 rounded-full" style="background-color: ${group.color}"></div>
                        <div>
                            <h4 class="font-medium text-gray-900">${group.name}</h4>
                            <p class="text-sm text-gray-500">${group.servers_count} servidor(es)</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${group.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${group.is_active ? 'Ativo' : 'Inativo'}
                        </span>
                        <button onclick="openEditGroupModal(${group.id})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteGroup(${group.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        container.innerHTML = html;
    }

// Função para editar grupo inline
function openEditGroupModal(groupId) {
    // Buscar dados do grupo via AJAX
    fetch(`/admin/server-groups/${groupId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.group) {
            const group = data.group;
            
            // Criar modal de edição dinâmico
            const editModal = document.createElement('div');
            editModal.id = 'editGroupModal';
            editModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center';
            editModal.innerHTML = `
                <div class="w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4 px-6 pt-4">
                        <h3 class="text-lg font-medium text-gray-900">Editar Grupo</h3>
                        <button onclick="closeEditGroupModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="px-6 pb-6">
                        <form id="editGroupForm">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                <input type="text" id="edit_name" name="name" value="${group.name}" 
                                       class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                                <textarea id="edit_description" name="description" rows="3" 
                                          class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">${group.description || ''}</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
                                <input type="color" id="edit_color" name="color" value="${group.color}" 
                                       class="w-20 h-10 border-gray-300 rounded-md shadow-sm">
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                                <input type="number" id="edit_sort_order" name="sort_order" value="${group.sort_order}" 
                                       class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeEditGroupModal()" 
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            document.body.appendChild(editModal);
            
            // Adicionar event listener para o formulário
            document.getElementById('editGroupForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateGroup(groupId);
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao carregar dados do grupo');
    });
}

// Função para fechar modal de edição
function closeEditGroupModal() {
    const modal = document.getElementById('editGroupModal');
    if (modal) {
        modal.remove();
    }
}

// Função para atualizar grupo
function updateGroup(groupId) {
    const form = document.getElementById('editGroupForm');
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    
    fetch(`/admin/server-groups/${groupId}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditGroupModal();
            loadGroupsList(); // Recarregar lista
            
            if (typeof showToast === 'function') {
                showToast(data.message, 'success');
            }
        } else {
            alert(data.message || 'Erro ao atualizar grupo');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar grupo');
    });
}

function deleteGroup(groupId) {
    if (confirm('Tem certeza que deseja excluir este grupo?')) {
        fetch(`/admin/server-groups/${groupId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recarregar o modal de grupos
                openManageGroupsModal();
            } else {
                alert(data.message || 'Erro ao excluir grupo');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir grupo');
        });
    }
}

// Função para criar grupo inline
function createGroupInline() {
    const name = document.getElementById('newGroupName').value.trim();
    const color = document.getElementById('newGroupColor').value;
    
    if (!name) {
        alert('Por favor, digite o nome do grupo');
        return;
    }
    
    const formData = new FormData();
    formData.append('name', name);
    formData.append('color', color);
    formData.append('is_active', '1');
    
    fetch('{{ route("admin.server-groups.store") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpar campos
            document.getElementById('newGroupName').value = '';
            document.getElementById('newGroupColor').value = '#3B82F6';
            
            // Recarregar lista
            loadGroupsList();
            
            if (typeof showToast === 'function') {
                showToast(data.message, 'success');
            }
        } else {
            alert(data.message || 'Erro ao criar grupo');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar grupo');
    });
}

// ——— Data Centers (CRUD inline, mesmo padrão da antiga tela Gerenciar Cards) ———
function dcNotifySuccess(message) {
    if (typeof showToast === 'function') {
        showToast(message, 'success');
    } else {
        alert(message);
    }
}
function dcNotifyError(message) {
    if (typeof showToast === 'function') {
        showToast(message, 'error');
    } else {
        alert(message);
    }
}

let dataCentersModalOpen = false;

function openDataCentersModal() {
    document.getElementById('dataCentersModal').classList.remove('hidden');
    document.body.classList.add('modal-open');
    dataCentersModalOpen = true;
    loadDataCenters();
}

function closeDataCentersModal() {
    document.getElementById('dataCentersModal').classList.add('hidden');
    document.body.classList.remove('modal-open');
    dataCentersModalOpen = false;
}

function loadDataCenters() {
    fetch('{{ url('/admin/datacenters') }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderDataCenters(data.datacenters);
        } else {
            dcNotifyError('Erro ao carregar data centers');
        }
    })
    .catch(() => dcNotifyError('Erro ao carregar data centers'));
}

function renderDataCenters(dataCenters) {
    const container = document.getElementById('dataCentersList');
    if (dataCenters.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-server text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum data center cadastrado</h3>
                <p class="text-gray-500 mb-4">Comece criando seu primeiro data center para associar aos servidores e aos cards do portal.</p>
            </div>
        `;
        return;
    }
    let html = '';
    dataCenters.forEach(dataCenter => {
        html += `
            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                    <div class="datacenter-name-container" data-datacenter-id="${dataCenter.id}">
                        <span class="text-sm font-medium text-gray-900 datacenter-name-display">${dataCenter.name}</span>
                        <input type="text" class="hidden text-sm font-medium text-gray-900 bg-transparent border-b border-purple-500 focus:outline-none focus:border-purple-700 datacenter-name-input" value="${dataCenter.name}">
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="editDataCenterInline(${dataCenter.id})" class="text-blue-600 hover:text-blue-900 p-1 edit-datacenter-btn" data-datacenter-id="${dataCenter.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" onclick="saveDataCenterInline(${dataCenter.id})" class="hidden text-green-600 hover:text-green-900 p-1 save-datacenter-btn" data-datacenter-id="${dataCenter.id}">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" onclick="cancelDataCenterEdit(${dataCenter.id})" class="hidden text-gray-600 hover:text-gray-900 p-1 cancel-datacenter-btn" data-datacenter-id="${dataCenter.id}">
                        <i class="fas fa-times"></i>
                    </button>
                    <button type="button" onclick="openDeleteDataCenterConfirmModal(${dataCenter.id})" class="text-red-600 hover:text-red-900 p-1">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function openDeleteDataCenterConfirmModal(dataCenterId) {
    document.getElementById('deleteDataCenterConfirmModal').classList.remove('hidden');
    document.getElementById('confirmDeleteDataCenterBtn').setAttribute('onclick', `confirmDeleteDataCenter(${dataCenterId})`);
}

function closeDeleteDataCenterConfirmModal() {
    document.getElementById('deleteDataCenterConfirmModal').classList.add('hidden');
}

function confirmDeleteDataCenter(dataCenterId) {
    closeDeleteDataCenterConfirmModal();
    const deleteBtn = document.querySelector(`button[onclick="openDeleteDataCenterConfirmModal(${dataCenterId})"]`);
    if (!deleteBtn) return;
    const originalContent = deleteBtn.innerHTML;
    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    deleteBtn.disabled = true;
    fetch(`{{ url('/admin/datacenters') }}/${dataCenterId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            dcNotifySuccess(data.message);
            if (dataCentersModalOpen) {
                removeDataCenterFromList(dataCenterId);
            }
        } else {
            dcNotifyError(data.message || 'Erro ao excluir data center');
        }
    })
    .catch(() => dcNotifyError('Erro ao excluir data center'))
    .finally(() => {
        deleteBtn.innerHTML = originalContent;
        deleteBtn.disabled = false;
    });
}

function removeDataCenterFromList(dataCenterId) {
    const dataCenterContainer = document.querySelector(`.datacenter-name-container[data-datacenter-id="${dataCenterId}"]`);
    if (dataCenterContainer) {
        const dataCenterElement = dataCenterContainer.closest('.flex.items-center.justify-between');
        if (dataCenterElement) {
            dataCenterElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            dataCenterElement.style.opacity = '0';
            dataCenterElement.style.transform = 'translateX(-10px)';
            setTimeout(() => {
                dataCenterElement.remove();
                const container = document.getElementById('dataCentersList');
                if (!container.querySelectorAll('.datacenter-name-container').length) {
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-server text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum data center cadastrado</h3>
                            <p class="text-gray-500 mb-4">Comece criando seu primeiro data center para associar aos servidores e aos cards do portal.</p>
                        </div>
                    `;
                }
            }, 300);
            return;
        }
    }
    loadDataCenters();
}

function createDataCenterInline() {
    const dataCenterName = document.getElementById('newDataCenterName').value.trim();
    if (!dataCenterName) {
        dcNotifyError('Por favor, digite um nome para o data center');
        return;
    }
    const button = document.querySelector('#dataCentersModal button[onclick="createDataCenterInline()"]');
    if (!button) return;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Criando...';
    button.disabled = true;
    fetch('{{ url('/admin/datacenters') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name: dataCenterName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            dcNotifySuccess(data.message);
            document.getElementById('newDataCenterName').value = '';
            addDataCenterToList(data.datacenter);
        } else if (data.errors) {
            const firstError = Object.values(data.errors)[0];
            dcNotifyError(Array.isArray(firstError) ? firstError[0] : firstError);
        } else {
            dcNotifyError(data.message || 'Erro ao criar data center');
        }
    })
    .catch(() => dcNotifyError('Erro ao criar data center'))
    .finally(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

function addDataCenterToList(dataCenter) {
    const container = document.getElementById('dataCentersList');
    const emptyMessage = container.querySelector('.text-center.py-8');
    if (emptyMessage) {
        container.innerHTML = '';
    }
    const newDataCenterHtml = `
        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                <div class="datacenter-name-container" data-datacenter-id="${dataCenter.id}">
                    <span class="text-sm font-medium text-gray-900 datacenter-name-display">${dataCenter.name}</span>
                    <input type="text" class="hidden text-sm font-medium text-gray-900 bg-transparent border-b border-purple-500 focus:outline-none focus:border-purple-700 datacenter-name-input" value="${dataCenter.name}">
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="editDataCenterInline(${dataCenter.id})" class="text-blue-600 hover:text-blue-900 p-1 edit-datacenter-btn" data-datacenter-id="${dataCenter.id}">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" onclick="saveDataCenterInline(${dataCenter.id})" class="hidden text-green-600 hover:text-green-900 p-1 save-datacenter-btn" data-datacenter-id="${dataCenter.id}">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" onclick="cancelDataCenterEdit(${dataCenter.id})" class="hidden text-gray-600 hover:text-gray-900 p-1 cancel-datacenter-btn" data-datacenter-id="${dataCenter.id}">
                    <i class="fas fa-times"></i>
                </button>
                <button type="button" onclick="openDeleteDataCenterConfirmModal(${dataCenter.id})" class="text-red-600 hover:text-red-900 p-1">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newDataCenterHtml);
}

function editDataCenterInline(dataCenterId) {
    const dataCenterNameContainer = document.querySelector(`.datacenter-name-container[data-datacenter-id="${dataCenterId}"]`);
    if (!dataCenterNameContainer) return;
    const displaySpan = dataCenterNameContainer.querySelector('.datacenter-name-display');
    const inputField = dataCenterNameContainer.querySelector('.datacenter-name-input');
    displaySpan.classList.add('hidden');
    inputField.classList.remove('hidden');
    inputField.focus();
    const editBtn = document.querySelector(`.edit-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    const saveBtn = document.querySelector(`.save-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    const cancelBtn = document.querySelector(`.cancel-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    if (editBtn && saveBtn && cancelBtn) {
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    }
}

function saveDataCenterInline(dataCenterId) {
    const dataCenterNameContainer = document.querySelector(`.datacenter-name-container[data-datacenter-id="${dataCenterId}"]`);
    if (!dataCenterNameContainer) return;
    const displaySpan = dataCenterNameContainer.querySelector('.datacenter-name-display');
    const inputField = dataCenterNameContainer.querySelector('.datacenter-name-input');
    const newName = inputField.value.trim();
    if (!newName) {
        dcNotifyError('Nome do data center não pode estar vazio');
        return;
    }
    const saveBtn = document.querySelector(`.save-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    const originalContent = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    saveBtn.disabled = true;
    fetch(`{{ url('/admin/datacenters') }}/${dataCenterId}`, {
        method: 'PUT',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name: newName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            dcNotifySuccess(data.message);
            displaySpan.textContent = newName;
            displaySpan.classList.remove('hidden');
            inputField.classList.add('hidden');
            const editBtn = document.querySelector(`.edit-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
            const cancelBtn = document.querySelector(`.cancel-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
            if (editBtn && cancelBtn) {
                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');
            }
        } else if (data.errors) {
            const firstError = Object.values(data.errors)[0];
            dcNotifyError(Array.isArray(firstError) ? firstError[0] : firstError);
        } else {
            dcNotifyError(data.message || 'Erro ao atualizar data center');
        }
    })
    .catch(() => dcNotifyError('Erro ao atualizar data center'))
    .finally(() => {
        saveBtn.innerHTML = originalContent;
        saveBtn.disabled = false;
    });
}

function cancelDataCenterEdit(dataCenterId) {
    const dataCenterNameContainer = document.querySelector(`.datacenter-name-container[data-datacenter-id="${dataCenterId}"]`);
    if (!dataCenterNameContainer) return;
    const displaySpan = dataCenterNameContainer.querySelector('.datacenter-name-display');
    const inputField = dataCenterNameContainer.querySelector('.datacenter-name-input');
    inputField.value = displaySpan.textContent;
    displaySpan.classList.remove('hidden');
    inputField.classList.add('hidden');
    const editBtn = document.querySelector(`.edit-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    const saveBtn = document.querySelector(`.save-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    const cancelBtn = document.querySelector(`.cancel-datacenter-btn[data-datacenter-id="${dataCenterId}"]`);
    if (editBtn && saveBtn && cancelBtn) {
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    }
}
</script>

<!-- Modal de Grupos de Servidores -->
<div id="manageGroupsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center delete-confirm-modal">
    <div class="w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white modal-content delete-confirm-content">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-4 px-6 pt-4">
            <h3 class="text-lg font-medium text-gray-900">Gerenciar Grupos de Servidores</h3>
            <button onclick="closeManageGroupsModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Área de criação -->
        <div class="px-6">
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <input type="text" id="newGroupName" placeholder="Nome do novo grupo" 
                           class="flex-1 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                    <input type="color" id="newGroupColor" value="#3B82F6" 
                           class="w-12 h-10 border-gray-300 rounded-md shadow-sm">
                    <button onclick="createGroupInline()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de grupos com scroll (máximo 5 visíveis) -->
        <div class="px-6 pb-6 overflow-y-auto" style="max-height: 300px;">
            <div id="groupsList">
                <!-- Grupos serão carregados via AJAX -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="text-gray-500 mt-2">Carregando grupos...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Data Centers -->
<div id="dataCentersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center delete-confirm-modal">
    <div class="w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white modal-content delete-confirm-content">
        <div class="flex justify-between items-center mb-4 px-6 pt-4">
            <h3 class="text-lg font-medium text-gray-900">Gerenciar Data Centers</h3>
            <button type="button" onclick="closeDataCentersModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="px-6">
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <input type="text" id="newDataCenterName" placeholder="Nome do novo data center"
                           class="flex-1 border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                    <button type="button" onclick="createDataCenterInline()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar
                    </button>
                </div>
            </div>
        </div>
        <div class="px-6 pb-6 overflow-y-auto" style="max-height: 300px;">
            <div id="dataCentersList" class="space-y-4">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="text-gray-500 mt-2">Carregando data centers...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteDataCenterConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center delete-confirm-modal">
    <div class="w-96 shadow-lg rounded-md bg-white delete-confirm-content">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full delete-confirm-icon">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div class="mt-2 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Exclusão</h3>
                <p class="text-sm text-gray-500 mb-6">Tem certeza que deseja excluir este data center?</p>
            </div>
            <div class="flex justify-center space-x-3 px-6 pb-6">
                <button type="button" onclick="closeDeleteDataCenterConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                    Cancelar
                </button>
                <button type="button" id="confirmDeleteDataCenterBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
