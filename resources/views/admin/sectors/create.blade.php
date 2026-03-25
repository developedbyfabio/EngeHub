<form id="createSectorForm" action="{{ route('admin.sectors.store') }}" method="POST">
    @csrf
    <input type="hidden" name="_ajax" value="1">
    
    <div class="space-y-6">
        <!-- Informações Básicas -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                Informações do Setor
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome do Setor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ex: Administrativo, Contabilidade, RH...">
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                        Observações
                    </label>
                    <input type="text" 
                           name="notes" 
                           id="notes" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Descrição opcional...">
                </div>
            </div>
            
            <div class="mt-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" 
                           name="generate_url" 
                           value="1" 
                           checked
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Gerar URL secreta automaticamente</span>
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
                    <button type="button" onclick="selectAllCards()" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded transition-colors">
                        <i class="fas fa-check-double mr-1"></i> Selecionar Todos
                    </button>
                    <button type="button" onclick="deselectAllCards()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded transition-colors">
                        <i class="fas fa-times mr-1"></i> Desmarcar Todos
                    </button>
                </div>
            </div>
            
            <div class="text-sm text-gray-500 mb-4">
                <i class="fas fa-info-circle mr-1"></i>
                Selecione os links/sistemas que este setor terá acesso via URL secreta.
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
                                            onclick="toggleTabCards({{ $tab->id }})" 
                                            class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-2 py-1 rounded">
                                        Selecionar Aba
                                    </button>
                                </div>
                                <div class="bg-gray-50 p-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($tab->cards as $card)
                                        <label class="flex items-center p-2 bg-white rounded border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition-colors card-checkbox" data-tab-id="{{ $tab->id }}">
                                            <input type="checkbox" 
                                                   name="cards[]" 
                                                   value="{{ $card->id }}" 
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-folder-open text-3xl mb-2"></i>
                    <p>Nenhum link cadastrado no sistema.</p>
                </div>
            @endif
        </div>
        
        <!-- Contador de Selecionados -->
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-indigo-800 font-medium">Links selecionados:</span>
                    <span id="selectedCount" class="ml-2 bg-indigo-600 text-white px-3 py-1 rounded-full text-sm font-bold">0</span>
                </div>
                <div class="text-sm text-indigo-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    O setor só terá acesso aos links selecionados
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-3 pt-4 border-t">
            <button type="button" 
                    onclick="closeCreateModal()" 
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                Cancelar
            </button>
            <button type="button" 
                    id="btnCreateSector"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Criar Setor
            </button>
        </div>
    </div>
</form>

<script>
// Scripts executados imediatamente após inserção no DOM
(function initCreateSectorForm() {
    console.log('Inicializando formulário de criação de setor...');
    
    const form = document.getElementById('createSectorForm');
    const btnCreate = document.getElementById('btnCreateSector');
    const storeUrl = form ? form.getAttribute('action') : '/admin/sectors';
    
    if (!form || !btnCreate) {
        console.error('Elementos do formulário não encontrados');
        return;
    }
    
    // Atualizar contador de selecionados
    function updateCount() {
        const count = form.querySelectorAll('input[name="cards[]"]:checked').length;
        const counter = document.getElementById('selectedCount');
        if (counter) counter.textContent = count;
    }
    
    // Listeners para checkboxes
    form.querySelectorAll('input[name="cards[]"]').forEach(function(cb) {
        cb.addEventListener('change', updateCount);
    });
    
    // Funções globais para os botões
    window.selectAllCards = function() {
        form.querySelectorAll('input[name="cards[]"]').forEach(function(cb) { cb.checked = true; });
        updateCount();
    };
    
    window.deselectAllCards = function() {
        form.querySelectorAll('input[name="cards[]"]').forEach(function(cb) { cb.checked = false; });
        updateCount();
    };
    
    window.toggleTabCards = function(tabId) {
        const cbs = form.querySelectorAll('.card-checkbox[data-tab-id="' + tabId + '"] input[type="checkbox"]');
        const allChecked = Array.from(cbs).every(function(c) { return c.checked; });
        cbs.forEach(function(c) { c.checked = !allChecked; });
        updateCount();
    };
    
    // Click no botão Criar Setor
    btnCreate.addEventListener('click', function() {
        console.log('Botão Criar Setor clicado');
        
        const nameInput = form.querySelector('input[name="name"]');
        if (!nameInput || !nameInput.value.trim()) {
            alert('Por favor, informe o nome do setor');
            if (nameInput) nameInput.focus();
            return;
        }
        
        const formData = new FormData(form);
        
        // Debug
        console.log('URL:', storeUrl);
        console.log('Dados:');
        for (let p of formData.entries()) {
            console.log('  ' + p[0] + ':', p[1]);
        }
        
        // Desabilitar botão
        btnCreate.disabled = true;
        btnCreate.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
        
        fetch(storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            console.log('Status:', response.status);
            return response.json().catch(function() {
                return response.text().then(function(text) {
                    console.error('Resposta não é JSON:', text);
                    throw new Error('Resposta inválida do servidor');
                });
            });
        })
        .then(function(data) {
            console.log('Resposta:', data);
            if (data.success) {
                alert('Setor criado com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + (data.message || 'Erro desconhecido'));
                btnCreate.disabled = false;
                btnCreate.innerHTML = '<i class="fas fa-save mr-2"></i>Criar Setor';
            }
        })
        .catch(function(error) {
            console.error('Erro:', error);
            alert('Erro ao criar setor: ' + error.message);
            btnCreate.disabled = false;
            btnCreate.innerHTML = '<i class="fas fa-save mr-2"></i>Criar Setor';
        });
    });
    
    // Inicializar contador
    updateCount();
    
    console.log('Formulário inicializado com sucesso');
})();
</script>

