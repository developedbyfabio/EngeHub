<!-- Modal de Permissões de Login -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="permissionsModal">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Header do Modal -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="permissionsModalTitle">
                Gerenciar Permissões - {{ $systemLogin->title }}
            </h3>
            <button onclick="closePermissionsModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Informações do Login -->
        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-key text-blue-500 mr-2"></i>
                <span class="font-medium text-blue-900">{{ $systemLogin->title }}</span>
            </div>
            <div class="text-sm text-blue-700">
                <strong>Username:</strong> {{ $systemLogin->username }}
            </div>
            <div class="text-sm text-blue-700">
                <strong>Sistema:</strong> {{ $systemLogin->card->name }}
            </div>
        </div>

        <!-- Lista de Usuários -->
        <div class="mb-6">
            <h4 class="text-md font-medium text-gray-900 mb-3">
                <i class="fas fa-users mr-2"></i>
                Usuários do Sistema
            </h4>
            
            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                @if($systemUsers->count() > 0)
                    @foreach($systemUsers as $user)
                        <div class="flex items-center justify-between p-3 border-b border-gray-100 hover:bg-gray-50">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="user_{{ $user->id }}" 
                                       name="user_ids[]" 
                                       value="{{ $user->id }}"
                                       {{ in_array($user->id, $userIdsWithPermission) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="user_{{ $user->id }}" class="ml-3 text-sm font-medium text-gray-900">
                                    {{ $user->name }}
                                </label>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $user->username ?: 'Sem username' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-4 text-center text-gray-500">
                        <i class="fas fa-user-slash text-2xl mb-2"></i>
                        <p>Nenhum usuário do sistema encontrado.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="bg-yellow-50 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-500 mr-2 mt-1"></i>
                <div class="text-sm text-yellow-700">
                    <p class="font-medium mb-1">Como funciona:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Marque os usuários que podem visualizar este login</li>
                        <li>Usuários não marcados não verão este login</li>
                        <li>Administradores sempre veem todos os logins</li>
                        <li>As alterações são aplicadas imediatamente</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-3">
            <button onclick="closePermissionsModal()" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </button>
            <button onclick="savePermissions({{ $systemLogin->id }})" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>
                Salvar Permissões
            </button>
        </div>
    </div>
</div>

<!-- Script removido - funções estão no contexto global -->
