<form id="editSectorForm" onsubmit="submitEditForm(event, {{ $sector->id }})">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
        <!-- Informações Básicas -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                Informações do Setor
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome do Setor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="edit_name" 
                           required
                           value="{{ $sector->name }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring-gray-500">
                </div>
                
                <div>
                    <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Observações
                    </label>
                    <input type="text" 
                           name="notes" 
                           id="edit_notes" 
                           value="{{ $sector->notes }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-gray-500 focus:ring-gray-500">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ $sector->is_active ? 'checked' : '' }}
                           class="rounded border-gray-300 text-gray-600 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Setor ativo</span>
                </label>
            </div>
        </div>
        
        <!-- Seleção de Links -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-gray-900">
                    <i class="fas fa-link mr-2 text-blue-600"></i>
                    Links que este setor pode acessar
                </h4>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="selectAllCardsEdit()" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded transition-colors">
                        <i class="fas fa-check-double mr-1"></i> Selecionar Todos
                    </button>
                    <button type="button" onclick="deselectAllCardsEdit()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded transition-colors">
                        <i class="fas fa-times mr-1"></i> Desmarcar Todos
                    </button>
                </div>
            </div>
            
            @if($tabs->count() > 0)
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                    @foreach($tabs as $tab)
                        @if($tab->cards->count() > 0)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-white px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($tab->icon)
                                            <i class="{{ $tab->icon }} mr-2" style="color: {{ $tab->color }};"></i>
                                        @else
                                            <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $tab->color }};"></div>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $tab->name }}</span>
                                        <span class="ml-2 text-xs text-gray-500">({{ $tab->cards->count() }} links)</span>
                                    </div>
                                    <button type="button" 
                                            onclick="toggleTabCardsEdit({{ $tab->id }})" 
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-2 py-1 rounded">
                                        Selecionar Aba
                                    </button>
                                </div>
                                <div class="bg-gray-50 p-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($tab->cards as $card)
                                        <label class="flex items-center p-2 bg-white rounded border border-gray-200 hover:border-gray-400 hover:bg-gray-50 cursor-pointer transition-colors edit-card-checkbox" data-tab-id="{{ $tab->id }}">
                                            <input type="checkbox" 
                                                   name="cards[]" 
                                                   value="{{ $card->id }}" 
                                                   {{ in_array($card->id, $selectedCards) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-gray-600 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                                            <div class="ml-2 flex items-center min-w-0">
                                                @if($card->custom_icon_path)
                                                    <img src="{{ $card->custom_icon_url }}" alt="" class="w-4 h-4 mr-2 object-contain flex-shrink-0">
                                                @elseif($card->icon)
                                                    <i class="{{ $card->icon }} mr-2 text-gray-400 flex-shrink-0"></i>
                                                @endif
                                                <span class="text-sm text-gray-700 truncate">{{ $card->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Contador de Selecionados -->
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-gray-800 font-medium">Links selecionados:</span>
                    <span id="editSelectedCount" class="ml-2 bg-gray-600 text-white px-3 py-1 rounded-full text-sm font-bold">{{ count($selectedCards) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-3 pt-4 border-t">
            <button type="button" 
                    onclick="closeEditModal()" 
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                Cancelar
            </button>
            <button type="submit" 
                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Salvar Alterações
            </button>
        </div>
    </div>
</form>

<script>
// Atualizar contador de selecionados
function updateEditSelectedCount() {
    const count = document.querySelectorAll('#editSectorForm input[name="cards[]"]:checked').length;
    document.getElementById('editSelectedCount').textContent = count;
}

// Adicionar listeners aos checkboxes
document.querySelectorAll('#editSectorForm input[name="cards[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateEditSelectedCount);
});

// Selecionar todos os cards
function selectAllCardsEdit() {
    document.querySelectorAll('#editSectorForm input[name="cards[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateEditSelectedCount();
}

// Desmarcar todos os cards
function deselectAllCardsEdit() {
    document.querySelectorAll('#editSectorForm input[name="cards[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateEditSelectedCount();
}

// Selecionar/desmarcar cards de uma aba
function toggleTabCardsEdit(tabId) {
    const checkboxes = document.querySelectorAll(`#editSectorForm .edit-card-checkbox[data-tab-id="${tabId}"] input[type="checkbox"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
    updateEditSelectedCount();
}

// Submit do formulário
function submitEditForm(event, sectorId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Desabilitar botão de submit
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
    
    fetch(`/admin/sectors/${sectorId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('success', data.message);
            }
            closeEditModal();
            location.reload();
        } else {
            alert('Erro: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Salvar Alterações';
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao salvar. Tente novamente.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Salvar Alterações';
    });
}
</script>




