<div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Gerenciar Permissões</h2>
        <p class="text-gray-600 mt-1">Usuário: <strong>{{ $user->name }}</strong> ({{ $user->username }})</p>
    </div>

    <form id="permissionsForm" action="{{ route('admin.system-users.update-permissions', $user) }}" method="POST">
        @csrf
        <div class="space-y-4">
            @foreach($permissions as $key => $permission)
                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center h-5">
                        <input 
                            id="permission_{{ $key }}" 
                            name="permissions[]" 
                            type="radio" 
                            value="{{ $key }}"
                            {{ $permission['active'] ? 'checked' : '' }}
                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                        >
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="permission_{{ $key }}" class="font-medium text-gray-700 cursor-pointer">
                            {{ $permission['label'] }}
                        </label>
                        <p class="text-gray-500">{{ $permission['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button 
                type="button" 
                onclick="closePermissionsModal()" 
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Cancelar
            </button>
            <button 
                type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Salvar Permissões
            </button>
        </div>
    </form>
</div>

<script>
function closePermissionsModal() {
    document.getElementById('permissionsModal').classList.add('hidden');
}

document.getElementById('permissionsForm').addEventListener('submit', function(e) {
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
            alert(data.message);
            closePermissionsModal();
            location.reload(); // Recarregar para atualizar a tabela
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        alert('Erro ao atualizar permissões: ' + error.message);
    });
});
</script>