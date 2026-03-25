<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-medium text-gray-900">Editar Servidor</h3>
    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
        <i class="fas fa-times text-xl"></i>
    </button>
</div>

<form id="editServerForm" action="{{ route('admin.servers.update', $server) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Nome do Servidor -->
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome do Servidor *</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ $server->name }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- IP do Servidor -->
        <div>
            <label for="ip_address" class="block text-sm font-medium text-gray-700 mb-2">IP do Servidor *</label>
            <input type="text" 
                   id="ip_address" 
                   name="ip_address" 
                   value="{{ $server->ip_address }}"
                   required
                   pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Grupo -->
        <div>
            <label for="server_group_id" class="block text-sm font-medium text-gray-700 mb-2">Grupo</label>
            <select id="server_group_id" 
                    name="server_group_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Selecione um grupo</option>
                @foreach($serverGroups as $group)
                    <option value="{{ $group->id }}" {{ $server->server_group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Data Center -->
        <div>
            <label for="data_center_id" class="block text-sm font-medium text-gray-700 mb-2">Data Center</label>
            <select id="data_center_id" 
                    name="data_center_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Selecione um data center</option>
                @foreach($datacenters as $datacenter)
                    <option value="{{ $datacenter->id }}" {{ $server->data_center_id == $datacenter->id ? 'selected' : '' }}>
                        {{ $datacenter->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Logo Atual -->
        @if($server->logo_url)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Atual</label>
                <img src="{{ $server->logo_url }}" 
                     alt="{{ $server->name }}" 
                     class="w-16 h-16 rounded-md object-cover border border-gray-200">
            </div>
        @endif

        <!-- Nova Logo -->
        <div class="{{ $server->logo_url ? '' : 'md:col-span-2' }}">
            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                {{ $server->logo_url ? 'Nova Logo' : 'Logo do Servidor' }}
            </label>
            <input type="file" 
                   id="logo" 
                   name="logo" 
                   accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF (máx: 1MB)</p>
        </div>

        <!-- Sistema Operacional -->
        <div>
            <label for="operating_system" class="block text-sm font-medium text-gray-700 mb-2">Sistema Operacional</label>
            <select id="operating_system" 
                    name="operating_system" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Selecione o sistema operacional</option>
                <option value="Linux" {{ $server->operating_system == 'Linux' ? 'selected' : '' }}>Linux</option>
                <option value="Windows" {{ $server->operating_system == 'Windows' ? 'selected' : '' }}>Windows</option>
                <option value="Outros" {{ $server->operating_system == 'Outros' ? 'selected' : '' }}>Outros</option>
            </select>
        </div>

        <!-- Webmin URL -->
        <div>
            <label for="webmin_url" class="block text-sm font-medium text-gray-700 mb-2">Webmin</label>
            <input type="url" 
                   id="webmin_url" 
                   name="webmin_url" 
                   value="{{ $server->webmin_url }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ex: https://10.40.132.2:10000">
            <p class="text-xs text-gray-500 mt-1">URL do Webmin (opcional)</p>
        </div>

        <!-- Nginx URL -->
        <div>
            <label for="nginx_url" class="block text-sm font-medium text-gray-700 mb-2">Nginx</label>
            <input type="url" 
                   id="nginx_url" 
                   name="nginx_url" 
                   value="{{ $server->nginx_url }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Ex: http://10.40.132.22:81">
            <p class="text-xs text-gray-500 mt-1">URL do Nginx Proxy Manager (opcional)</p>
        </div>

        <!-- Descrição -->
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
            <textarea id="description" 
                      name="description" 
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $server->description }}</textarea>
        </div>

        <!-- Monitoramento -->
        <div class="md:col-span-2">
            <div class="flex items-center">
                <input type="checkbox" 
                       id="monitor_status" 
                       name="monitor_status" 
                       {{ $server->monitor_status ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="monitor_status" class="ml-2 block text-sm text-gray-900">
                    Ativar monitoramento de status (ping)
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-1">O sistema fará ping para verificar se o servidor está online</p>
        </div>

    </div>

    <!-- Botões -->
    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
        <button type="button" 
                onclick="closeModal()" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Cancelar
        </button>
        <button type="submit" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-save mr-2"></i>
            Atualizar Servidor
        </button>
    </div>
</form>

