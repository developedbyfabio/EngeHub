<form method="POST" action="{{ route('admin.cards.update', $card) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <x-input-label for="name" :value="__('Nome do Sistema')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $card->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Descrição (opcional)')" />
        <textarea id="description" name="description" rows="3" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $card->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="link" :value="__('Link/IP do Sistema')" />
        <x-text-input id="link" class="block mt-1 w-full" type="text" name="link" :value="old('link', $card->link)" required placeholder="https://exemplo.com ou 192.168.1.100" />
        <p class="text-sm text-gray-500 mt-1" id="link-help">Para sites web, use URL completa (https://exemplo.com). Para servidores, use apenas o IP (192.168.1.100)</p>
        <x-input-error :messages="$errors->get('link')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="monitoring_type" :value="__('Tipo de Monitoramento')" />
        <select id="monitoring_type" name="monitoring_type" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full" required>
            <option value="http" {{ old('monitoring_type', $card->monitoring_type ?? 'http') == 'http' ? 'selected' : '' }}>Site Web (HTTP/HTTPS)</option>
            <option value="ping" {{ old('monitoring_type', $card->monitoring_type ?? 'http') == 'ping' ? 'selected' : '' }}>Servidor (Ping IP)</option>
        </select>
        <p class="text-sm text-gray-500 mt-1">Escolha o tipo de monitoramento: HTTP para sites web ou Ping para servidores</p>
        <x-input-error :messages="$errors->get('monitoring_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tab_id" :value="__('Aba/Categoria')" />
        <select id="tab_id" name="tab_id" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm block mt-1 w-full" required>
            <option value="">Selecione uma aba</option>
            @foreach($tabs as $tab)
                <option value="{{ $tab->id }}" {{ old('tab_id', $card->tab_id) == $tab->id ? 'selected' : '' }}>
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
                <option value="{{ $category->id }}" {{ old('category_id', $card->category_id) == $category->id ? 'selected' : '' }}>
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
                <option value="{{ $datacenter->id }}" {{ old('data_center_id', $card->data_center_id) == $datacenter->id ? 'selected' : '' }}>
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
        <input id="icon" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm" type="text" name="icon" value="{{ old('icon', $card->icon) }}" placeholder="fas fa-cogs" />
        <p class="text-sm text-gray-500 mt-1">Use classes do Font Awesome (ex: fas fa-cogs, fas fa-users, etc.)</p>
        @error('icon')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="custom_icon" class="block font-medium text-sm text-gray-700">
            Ícone Personalizado (opcional)
        </label>
        @if($card->custom_icon_path)
            <div class="mb-2">
                <p class="text-sm text-gray-600">Ícone atual:</p>
                <img src="{{ $card->custom_icon_url }}" alt="Ícone atual" class="w-8 h-8 inline-block mr-2 border border-gray-300 rounded">
                <a href="{{ $card->custom_icon_url }}" target="_blank" class="text-blue-600 hover:text-blue-900 text-sm">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Ver ícone atual
                </a>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remove_custom_icon" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ml-2 text-sm text-red-600">Remover ícone personalizado</span>
                    </label>
                </div>
            </div>
        @endif
        <input type="file" id="custom_icon" name="custom_icon" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".jpg,.jpeg,.png,.gif" />
        <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF (máx. 1MB). A imagem será redimensionada para 32x32 pixels. Deixe em branco para manter o ícone atual.</p>
        @error('custom_icon')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <input id="monitor_status" type="checkbox" name="monitor_status" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('monitor_status', $card->monitor_status) ? 'checked' : '' }}>
        <label for="monitor_status" class="ml-2 block text-sm text-gray-900">
            <span class="font-medium">Monitorar Status Online/Offline</span>
            <p class="text-gray-500 mt-1">Ativa o monitoramento de status para este card. O sistema fará ping/verificação periódica para determinar se o serviço está online.</p>
        </label>
    </div>

    @if($card->monitor_status)
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Status Atual</h4>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full {{ $card->status_class }} mr-2"></div>
                    <span class="text-sm text-gray-700">{{ $card->status_text }}</span>
                </div>
                @if($card->response_time)
                    <span class="text-sm text-gray-500">Tempo de resposta: {{ $card->response_time }}ms</span>
                @endif
                @if($card->last_status_check)
                    <span class="text-sm text-gray-500">Última verificação: {{ $card->last_status_check->format('d/m/Y H:i:s') }}</span>
                @endif
            </div>
        </div>
    @endif

    <div>
        <label for="file" class="block font-medium text-sm text-gray-700">
            Arquivo (opcional)
        </label>
        @if($card->file_path)
            <div class="mb-2">
                <p class="text-sm text-gray-600">Arquivo atual:</p>
                <a href="{{ Storage::url($card->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900 text-sm">
                    <i class="fas fa-paperclip mr-1"></i>
                    Ver arquivo atual
                </a>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remove_file" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ml-2 text-sm text-red-600">Remover arquivo</span>
                    </label>
                </div>
            </div>
        @endif
        <input type="file" id="file" name="file" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept=".jpg,.jpeg,.png,.gif,.pdf" />
        <p class="text-sm text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF, PDF (máx. 2MB). Deixe em branco para manter o arquivo atual.</p>
        @error('file')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
        <button type="button" onclick="closeEditModal()" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
            Cancelar
        </button>
        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-save mr-2"></i>
            Atualizar Card
        </button>
    </div>
</form>

<script>
    // Desabilitar campos de upload quando checkbox de remoção estiver marcado
    document.addEventListener('DOMContentLoaded', function() {
        const removeFileCheckbox = document.querySelector('input[name="remove_file"]');
        const fileInput = document.getElementById('file');
        
        if (removeFileCheckbox && fileInput) {
            removeFileCheckbox.addEventListener('change', function() {
                fileInput.disabled = this.checked;
                if (this.checked) {
                    fileInput.style.opacity = '0.5';
                } else {
                    fileInput.style.opacity = '1';
                }
            });
        }
        
        const removeIconCheckbox = document.querySelector('input[name="remove_custom_icon"]');
        const iconInput = document.getElementById('custom_icon');
        
        if (removeIconCheckbox && iconInput) {
            removeIconCheckbox.addEventListener('change', function() {
                iconInput.disabled = this.checked;
                if (this.checked) {
                    iconInput.style.opacity = '0.5';
                } else {
                    iconInput.style.opacity = '1';
                }
            });
        }

        // Funcionalidade para tipo de monitoramento
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