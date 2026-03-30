<form action="{{ route('admin.system-users.store') }}" method="POST" id="createForm">
    @csrf
    
    <div class="space-y-6">
        <!-- Nome -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Nome completo do usuário">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Ex: fabio.lemes">
            <p class="mt-1 text-sm text-gray-500">Este será o login para acessar o sistema (será convertido para email automaticamente)</p>
            @error('username')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Senha -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
            <input type="password" name="password" id="password" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Senha para fazer login no sistema">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Grupo de navegação -->
        <div id="wrap_user_group_id">
            <label for="user_group_id" class="block text-sm font-medium text-gray-700">Grupo de acesso *</label>
            <select name="user_group_id" id="user_group_id" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach($groups as $g)
                    @if($g->slug !== \App\Models\UserGroup::SLUG_ADMINISTRADORES)
                        <option value="{{ $g->id }}" @selected($g->slug === \App\Models\UserGroup::SLUG_USUARIOS)>{{ $g->name }}</option>
                    @endif
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Define quais abas e áreas do menu o usuário enxerga. O grupo <strong>Administradores</strong> só é aplicado ao marcar “Usuário Administrador” abaixo.</p>
            @error('user_group_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Serviços operacionais (além do menu) -->
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
            <label class="block text-sm font-medium text-gray-700 mb-2">Serviços que o usuário pode executar</label>
            <p class="text-xs text-gray-500 mb-3">Recursos como checklists na aba Câmeras. <strong>Usuário administrador</strong> (marcado abaixo) ignora esta lista e tem acesso a todos os serviços.</p>
            <div class="space-y-2">
                @foreach(\App\Support\UserService::labels() as $serviceKey => $serviceLabel)
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" name="enabled_services[]" value="{{ $serviceKey }}"
                               class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               @checked(in_array($serviceKey, old('enabled_services', []), true))>
                        <span class="text-sm text-gray-800">{{ $serviceLabel }}</span>
                    </label>
                @endforeach
            </div>
            @error('enabled_services')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('enabled_services.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tipo de Usuário -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Usuário</label>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <div class="flex items-center h-5">
                        <input id="is_admin" name="is_admin" type="checkbox" value="1"
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_admin" class="font-medium text-gray-700 cursor-pointer">
                            Usuário Administrador (acesso total + permissões de edição no painel)
                        </label>
                        <p class="text-gray-500">Marque para conceder permissões administrativas completas (senhas, logins, usuários) e vincular ao grupo <strong>Administradores</strong> automaticamente.</p>
                    </div>
                </div>
            </div>
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
                        <p>Após criar o usuário, ele poderá fazer login no sistema usando:</p>
                        <ul class="list-disc list-inside mt-1">
                            <li><strong>Email:</strong> username@engepecas.com</li>
                            <li><strong>Senha:</strong> A senha definida acima</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões -->
    <div class="mt-8 flex justify-end space-x-3">
        <button type="button" onclick="closeCreateModal()" 
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
            Cancelar
        </button>
        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Criar Usuário
        </button>
    </div>
</form>
