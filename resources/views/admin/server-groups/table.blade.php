<!-- Cabeçalho com botões -->
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-medium text-gray-900">Grupos de Servidores</h3>
    <div class="flex space-x-3">
        <button onclick="openCreateGroupModal()" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Novo Grupo
        </button>
    </div>
</div>

<!-- Tabela de grupos -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servidores</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordem</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($serverGroups as $group)
                <tr>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $group->color }}"></div>
                            <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded border" style="background-color: {{ $group->color }}"></div>
                            <span class="ml-2 text-sm text-gray-600">{{ $group->color }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">{{ $group->servers_count }} servidor(es)</span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $group->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $group->sort_order }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="openEditGroupModal({{ $group->id }})" 
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteGroup({{ $group->id }})" 
                                    class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                        Nenhum grupo de servidores encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal de Criação -->
<div id="createGroupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-60">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Novo Grupo</h3>
                <button onclick="closeCreateGroupModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="createGroupForm" method="POST" action="{{ route('admin.server-groups.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome do Grupo *</label>
                        <input type="text" id="name" name="name" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Cor *</label>
                        <input type="color" id="color" name="color" value="#3B82F6" required
                               class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">Ordem</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" value="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Grupo ativo
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateGroupModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Criar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div id="editGroupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-60">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Grupo</h3>
                <button onclick="closeEditGroupModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editGroupForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Nome do Grupo *</label>
                        <input type="text" id="edit_name" name="name" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea id="edit_description" name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label for="edit_color" class="block text-sm font-medium text-gray-700">Cor *</label>
                        <input type="color" id="edit_color" name="color" required
                               class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_sort_order" class="block text-sm font-medium text-gray-700">Ordem</label>
                        <input type="number" id="edit_sort_order" name="sort_order" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="edit_is_active" name="is_active"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="edit_is_active" class="ml-2 block text-sm text-gray-900">
                            Grupo ativo
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditGroupModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Atualizar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Dados dos grupos para edição
const groupsData = @json($serverGroups->keyBy('id'));

function openCreateGroupModal() {
    document.getElementById('createGroupModal').classList.remove('hidden');
}

function closeCreateGroupModal() {
    document.getElementById('createGroupModal').classList.add('hidden');
}

function openEditGroupModal(groupId) {
    const group = groupsData[groupId];
    if (!group) return;

    // Preencher formulário de edição
    document.getElementById('edit_name').value = group.name;
    document.getElementById('edit_description').value = group.description || '';
    document.getElementById('edit_color').value = group.color;
    document.getElementById('edit_sort_order').value = group.sort_order;
    document.getElementById('edit_is_active').checked = group.is_active;
    
    // Definir action do formulário
    document.getElementById('editGroupForm').action = `/admin/server-groups/${groupId}`;
    
    document.getElementById('editGroupModal').classList.remove('hidden');
}

function closeEditGroupModal() {
    document.getElementById('editGroupModal').classList.add('hidden');
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

// Event delegation para formulários
document.addEventListener('submit', function(e) {
    if (e.target.id === 'createGroupForm') {
        e.preventDefault();
        handleCreateGroupForm(e.target);
    } else if (e.target.id === 'editGroupForm') {
        e.preventDefault();
        handleEditGroupForm(e.target);
    }
});

function handleCreateGroupForm(form) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Corrigir checkbox is_active
    const isActiveCheckbox = form.querySelector('#is_active');
    if (isActiveCheckbox) {
        if (!isActiveCheckbox.checked) {
            formData.set('is_active', '0');
        } else {
            formData.set('is_active', '1');
        }
    }
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Criando...';
    submitButton.disabled = true;
    
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
            closeCreateGroupModal();
            // Recarregar o modal de grupos
            openManageGroupsModal();
        } else {
            if (data.errors) {
                let errorMessage = 'Erros de validação:\n';
                for (const field in data.errors) {
                    errorMessage += `- ${data.errors[field][0]}\n`;
                }
                alert(errorMessage);
            } else {
                alert(data.message || 'Erro ao criar grupo');
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao criar grupo: ' + error.message);
    })
    .finally(() => {
        submitButton.innerHTML = originalContent;
        submitButton.disabled = false;
    });
}

function handleEditGroupForm(form) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Corrigir checkbox is_active
    const isActiveCheckbox = form.querySelector('#edit_is_active');
    if (isActiveCheckbox) {
        if (!isActiveCheckbox.checked) {
            formData.set('is_active', '0');
        } else {
            formData.set('is_active', '1');
        }
    }
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Atualizando...';
    submitButton.disabled = true;
    
    const formAction = form.action;
    const groupId = formAction.split('/').pop();
    
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
            // Recarregar o modal de grupos
            openManageGroupsModal();
        } else {
            if (data.errors) {
                let errorMessage = 'Erros de validação:\n';
                for (const field in data.errors) {
                    errorMessage += `- ${data.errors[field][0]}\n`;
                }
                alert(errorMessage);
            } else {
                alert(data.message || 'Erro ao atualizar grupo');
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar grupo: ' + error.message);
    })
    .finally(() => {
        submitButton.innerHTML = originalContent;
        submitButton.disabled = false;
    });
}
</script>
