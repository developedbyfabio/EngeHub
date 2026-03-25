<div class="bg-white p-4 rounded-lg">
    <form id="createSystemLoginForm" class="space-y-3">
        @csrf
        <input type="hidden" name="card_id" value="{{ $cardId }}">
        
        <!-- Título -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Título <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ex: Administrador, Usuário Padrão"
                   required>
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                Username/Login <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="username" 
                   name="username" 
                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ex: admin@portal.com"
                   required>
        </div>

        <!-- Senha -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Senha <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                       placeholder="Digite a senha"
                       required>
                <button type="button" 
                        onclick="togglePasswordField('password')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye text-sm" id="password-eye-icon"></i>
                </button>
            </div>
        </div>

        <!-- Observações -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                Observações
            </label>
            <textarea id="notes" 
                      name="notes" 
                      rows="2"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                      placeholder="Informações adicionais sobre este login"></textarea>
        </div>

        <!-- Status -->
        <div class="flex items-center justify-between pt-2">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="is_active" 
                       value="1" 
                       checked
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-700">Login ativo</span>
            </label>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200">
            <button type="button" 
                    onclick="closeCreateSystemLoginModal()"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Cancelar
            </button>
            <button type="button" 
                    onclick="submitCreateLoginForm()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-plus mr-1"></i>
                Criar Login
            </button>
        </div>
    </form>
</div>
