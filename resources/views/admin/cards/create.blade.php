<form method="POST" action="{{ route('admin.cards.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div>
        <x-input-label for="name" :value="__('Nome do Sistema')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Descrição (opcional)')" />
        <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="link" :value="__('Link/IP do Sistema')" />
        <x-text-input id="link" class="block mt-1 w-full" type="text" name="link" :value="old('link')" required placeholder="https://exemplo.com ou 192.168.1.100" />
        <p class="text-sm text-gray-500 mt-1" id="link-help">Para sites web, use URL completa (https://exemplo.com). Para servidores, use apenas o IP (192.168.1.100)</p>
        <x-input-error :messages="$errors->get('link')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="monitoring_type" :value="__('Tipo de Monitoramento')" />
        <select id="monitoring_type" name="monitoring_type" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full" required>
            <option value="http" {{ old('monitoring_type', 'http') == 'http' ? 'selected' : '' }}>Site Web (HTTP/HTTPS)</option>
            <option value="ping" {{ old('monitoring_type') == 'ping' ? 'selected' : '' }}>Servidor (Ping IP)</option>
        </select>
        <p class="text-sm text-gray-500 mt-1">Escolha o tipo de monitoramento: HTTP para sites web ou Ping para servidores</p>
        <x-input-error :messages="$errors->get('monitoring_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tab_id" :value="__('Aba/Categoria')" />
        <select id="tab_id" name="tab_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full" required>
            <option value="">Selecione uma aba</option>
            @foreach($tabs as $tab)
                <option value="{{ $tab->id }}" {{ old('tab_id') == $tab->id ? 'selected' : '' }}>
                    {{ $tab->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tab_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category_id" :value="__('Categoria (opcional)')" />
        <select id="category_id" name="category_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">
            <option value="">Nenhuma categoria selecionada</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <p class="text-sm text-gray-500 mt-1">Deixe em branco se não quiser categorizar este card</p>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="data_center_id" :value="__('Data Center')" />
        <select id="data_center_id" name="data_center_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">
            <option value="">Nenhum data center selecionado</option>
            @foreach($datacenters as $datacenter)
                <option value="{{ $datacenter->id }}" {{ old('data_center_id') == $datacenter->id ? 'selected' : '' }}>
                    {{ $datacenter->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('data_center_id')" class="mt-2" />
    </div>

    <div>
        <label for="icon" class="block font-medium text-sm text-gray-700">
            Ícone Font Awesome (opcional)
        </label>
        <input id="icon" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" type="text" name="icon" value="{{ old('icon') }}" placeholder="fas fa-cogs" />
        <p class="text-sm text-gray-500 mt-1">Use classes do Font Awesome (ex: fas fa-cogs, fas fa-users, etc.)</p>
        @error('icon')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="custom_icon" class="block font-medium text-sm text-gray-700">
            Ícone Personalizado (opcional)
        </label>
        <input type="file" id="custom_icon" name="custom_icon" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".jpg,.jpeg,.png,.gif" />
        <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF (máx. 1MB). A imagem será redimensionada para 32x32 pixels.</p>
        @error('custom_icon')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <input id="monitor_status" type="checkbox" name="monitor_status" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('monitor_status') ? 'checked' : '' }}>
        <label for="monitor_status" class="ml-2 block text-sm text-gray-900">
            <span class="font-medium">Monitorar Status Online/Offline</span>
            <p class="text-gray-500 mt-1">Ativa o monitoramento de status para este card. O sistema fará ping/verificação periódica para determinar se o serviço está online.</p>
        </label>
    </div>

    <div>
        <x-input-label for="file" :value="__('Arquivo (opcional)')" />
        <input type="file" id="file" name="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".jpg,.jpeg,.png,.gif,.pdf" />
        <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF, PDF (máx. 2MB)</p>
        <x-input-error :messages="$errors->get('file')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeCreateModal()" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
            Cancelar
        </button>
        <x-primary-button class="ml-4">
            <i class="fas fa-save mr-2"></i>
            {{ __('Criar Card') }}
        </x-primary-button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monitoringTypeSelect = document.getElementById('monitoring_type');
    const linkInput = document.getElementById('link');
    const linkHelp = document.getElementById('link-help');
    
    function updateLinkField() {
        if (monitoringTypeSelect.value === 'ping') {
            linkInput.placeholder = '192.168.1.100';
            linkHelp.textContent = 'Digite apenas o endereço IP do servidor (ex: 192.168.1.100)';
        } else {
            linkInput.placeholder = 'https://exemplo.com';
            linkHelp.textContent = 'Digite a URL completa do site (ex: https://exemplo.com)';
        }
    }
    
    monitoringTypeSelect.addEventListener('change', updateLinkField);
    updateLinkField(); // Executar na carga inicial
});
</script> 