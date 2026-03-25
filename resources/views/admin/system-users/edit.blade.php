<form action="{{ route('admin.system-users.update', $user->id) }}" method="POST" id="editForm">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
        <!-- Nome -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nome completo do usuário">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username', str_replace('@engepecas.com', '', $user->email)) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ex: fabio.lemes">
            <p class="mt-1 text-sm text-gray-500">Este será o login para acessar o sistema (será convertido para email automaticamente)</p>
            @error('username')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Senha -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha (deixe em branco para não alterar)</label>
            <input type="password" name="password" id="password"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Deixe em branco para manter a senha atual">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Instruções de Login -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Instruções de Login</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>O usuário pode fazer login no sistema usando:</p>
                        <ul class="list-disc list-inside mt-1">
                            <li><strong>Email:</strong> {{ $user->email }}</li>
                            <li><strong>Senha:</strong> A senha atual ou a nova senha definida acima</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões -->
    <div class="mt-8 flex justify-end space-x-3">
        <button type="button" onclick="closeEditModal()" 
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
            Cancelar
        </button>
        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Atualizar Usuário
        </button>
    </div>
</form>