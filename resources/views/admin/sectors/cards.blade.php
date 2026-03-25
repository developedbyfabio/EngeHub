<form id="cardsForm" onsubmit="submitCardsForm(event, {{ $sector->id }})">
    @csrf
    
    <div class="space-y-6">
        <!-- Info do Setor -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-blue-800">{{ $sector->name }}</h4>
                    <p class="text-blue-600 text-sm">Selecione os links que este setor terá acesso</p>
                </div>
            </div>
        </div>
        
        <!-- Ações Rápidas -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Apenas os links selecionados serão visíveis via URL secreta
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="selectAllCardsForm()" class="text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-check-double mr-1"></i> Selecionar Todos
                </button>
                <button type="button" onclick="deselectAllCardsForm()" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-1"></i> Desmarcar Todos
                </button>
            </div>
        </div>
        
        <!-- Lista de Links por Aba -->
        @if($tabs->count() > 0)
            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                @foreach($tabs as $tab)
                    @if($tab->cards->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-white px-4 py-3 border-b border-gray-200 flex items-center justify-between sticky top-0 z-10">
                                <div class="flex items-center">
                                    @if($tab->icon)
                                        <i class="{{ $tab->icon }} mr-2 text-lg" style="color: {{ $tab->color }};"></i>
                                    @else
                                        <div class="w-5 h-5 rounded-full mr-2" style="background-color: {{ $tab->color }};"></div>
                                    @endif
                                    <span class="font-semibold text-gray-900">{{ $tab->name }}</span>
                                    <span class="ml-2 text-sm text-gray-500">({{ $tab->cards->count() }} links)</span>
                                </div>
                                <button type="button" 
                                        onclick="toggleTabCardsForm({{ $tab->id }})" 
                                        class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1 rounded-lg transition-colors">
                                    <i class="fas fa-check-square mr-1"></i> Alternar Aba
                                </button>
                            </div>
                            <div class="bg-gray-50 p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($tab->cards as $card)
                                    <label class="flex items-center p-3 bg-white rounded-lg border-2 {{ in_array($card->id, $selectedCards) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} hover:border-blue-300 cursor-pointer transition-all cards-form-checkbox" data-tab-id="{{ $tab->id }}">
                                        <input type="checkbox" 
                                               name="cards[]" 
                                               value="{{ $card->id }}" 
                                               {{ in_array($card->id, $selectedCards) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-5 h-5">
                                        <div class="ml-3 flex items-center min-w-0 flex-1">
                                            @if($card->custom_icon_path)
                                                <img src="{{ $card->custom_icon_url }}" alt="" class="w-6 h-6 mr-3 object-contain flex-shrink-0">
                                            @elseif($card->icon)
                                                <i class="{{ $card->icon }} mr-3 text-lg text-gray-400 flex-shrink-0"></i>
                                            @else
                                                <div class="w-6 h-6 rounded mr-3 flex-shrink-0" style="background-color: {{ $tab->color }};"></div>
                                            @endif
                                            <div class="min-w-0">
                                                <span class="text-sm font-medium text-gray-900 truncate block">{{ $card->name }}</span>
                                                @if($card->description)
                                                    <span class="text-xs text-gray-500 truncate block">{{ Str::limit($card->description, 40) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-folder-open text-3xl mb-2"></i>
                <p>Nenhum link cadastrado no sistema.</p>
            </div>
        @endif
        
        <!-- Contador de Selecionados -->
        <div class="bg-blue-100 border border-blue-300 rounded-lg p-4 sticky bottom-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-blue-600 text-xl mr-3"></i>
                    <span class="text-blue-800 font-medium">Links selecionados:</span>
                    <span id="cardsFormCount" class="ml-2 bg-blue-600 text-white px-4 py-1 rounded-full text-lg font-bold">{{ count($selectedCards) }}</span>
                </div>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Links
                </button>
            </div>
        </div>
    </div>
</form>

<script>
// Atualizar contador e estilo dos cards
function updateCardsFormCount() {
    const count = document.querySelectorAll('#cardsForm input[name="cards[]"]:checked').length;
    document.getElementById('cardsFormCount').textContent = count;
    
    // Atualizar estilo visual dos cards
    document.querySelectorAll('#cardsForm .cards-form-checkbox').forEach(label => {
        const checkbox = label.querySelector('input[type="checkbox"]');
        if (checkbox.checked) {
            label.classList.remove('border-gray-200');
            label.classList.add('border-blue-500', 'bg-blue-50');
        } else {
            label.classList.remove('border-blue-500', 'bg-blue-50');
            label.classList.add('border-gray-200');
        }
    });
}

// Adicionar listeners aos checkboxes
document.querySelectorAll('#cardsForm input[name="cards[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateCardsFormCount);
});

// Selecionar todos os cards
function selectAllCardsForm() {
    document.querySelectorAll('#cardsForm input[name="cards[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateCardsFormCount();
}

// Desmarcar todos os cards
function deselectAllCardsForm() {
    document.querySelectorAll('#cardsForm input[name="cards[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateCardsFormCount();
}

// Selecionar/desmarcar cards de uma aba
function toggleTabCardsForm(tabId) {
    const checkboxes = document.querySelectorAll(`#cardsForm .cards-form-checkbox[data-tab-id="${tabId}"] input[type="checkbox"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
    updateCardsFormCount();
}

// Submit do formulário
function submitCardsForm(event, sectorId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Desabilitar botão de submit
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
    
    fetch(`/admin/sectors/${sectorId}/cards`, {
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
            closeCardsModal();
            location.reload();
        } else {
            alert('Erro: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Salvar Links';
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao salvar. Tente novamente.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Salvar Links';
    });
}

// Inicializar
updateCardsFormCount();
</script>




