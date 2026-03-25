<div class="bg-white p-6 rounded-lg">
    <form id="editSystemLoginForm" class="space-y-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="login_id" value="{{ $systemLogin->id }}">
        
        <!-- Título -->
        <div>
            <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">
                Título <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="edit_title" 
                   name="title" 
                   value="{{ $systemLogin->title }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ex: Administrador, Usuário Padrão, Suporte"
                   required>
        </div>

        <!-- Username -->
        <div>
            <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-1">
                Username/Login <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="edit_username" 
                   name="username" 
                   value="{{ $systemLogin->username }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ex: admin@portal.com"
                   required>
        </div>

        <!-- Senha -->
        <div>
            <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">
                Nova Senha <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="password" 
                       id="edit_password" 
                       name="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                       placeholder="Digite a nova senha"
                       required>
                <button type="button" 
                        onclick="togglePasswordField('edit_password')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye" id="edit_password-eye-icon"></i>
                </button>
            </div>
        </div>

        <!-- Observações -->
        <div>
            <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-1">
                Observações
            </label>
            <textarea id="edit_notes" 
                      name="notes" 
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Informações adicionais sobre este login">{{ $systemLogin->notes }}</textarea>
        </div>

        <!-- Status -->
        <div>
            <label class="flex items-center">
                <input type="checkbox" 
                       id="edit_is_active"
                       name="is_active" 
                       value="1" 
                       {{ $systemLogin->is_active ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">Login ativo</span>
            </label>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3 pt-4">
            <button type="button" 
                    onclick="closeEditSystemLoginModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Cancelar
            </button>
            <button type="button" 
                    onclick="submitEditLoginForm()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Atualizar Login
            </button>
        </div>
    </form>
</div>
