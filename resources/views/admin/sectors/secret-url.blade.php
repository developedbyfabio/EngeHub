<div class="space-y-6">
    <!-- Info do Setor -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 rounded-lg text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">{{ $sector->name }}</h3>
                <p class="text-indigo-200 text-sm mt-1">
                    <i class="fas fa-link mr-1"></i>
                    {{ $sector->cards->count() }} links associados
                </p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $sector->secret_url_enabled ? 'bg-green-500' : 'bg-red-500' }}">
                    <i class="fas {{ $sector->secret_url_enabled ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                    {{ $sector->secret_url_enabled ? 'URL Ativa' : 'URL Desativada' }}
                </span>
            </div>
        </div>
    </div>

    <!-- URL Secreta -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-link mr-2 text-indigo-600"></i>
            URL de Acesso do Setor
        </h4>
        
        <!-- Campo para editar a URL -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-edit mr-1"></i>
                Personalizar URL (slug):
            </label>
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm whitespace-nowrap">{{ url('/s/') }}/</span>
                <input type="text" 
                       id="sectorUrlSlug" 
                       value="{{ $sector->secret_url ?? '' }}" 
                       placeholder="Ex: administrativo, compras, rh"
                       class="flex-1 bg-white border border-gray-300 rounded-md px-4 py-2 text-sm font-mono text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button onclick="updateSectorUrl({{ $sector->id }})" 
                        id="updateUrlBtn"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-save mr-1"></i> Salvar
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Use apenas letras, números, hífens (-) e underscores (_). Ex: administrativo, setor-compras, rh_2024
            </p>
        </div>
        
        @if($sector->secret_url)
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <label class="block text-sm font-medium text-green-700 mb-2">
                    <i class="fas fa-check-circle mr-1"></i>
                    URL Completa (compartilhe com o setor):
                </label>
                <div class="flex items-center">
                    <input type="text" 
                           id="sectorSecretUrl" 
                           value="{{ $sector->full_secret_url }}" 
                           readonly 
                           class="flex-1 bg-white border border-green-300 rounded-l-md px-4 py-3 text-sm font-mono text-gray-700 focus:outline-none">
                    <button onclick="copySectorUrl()" 
                            id="copySectorUrlBtn"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-r-md transition-colors duration-200">
                        <i class="fas fa-copy mr-2"></i> Copiar
                    </button>
                </div>
                
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-white p-3 rounded border">
                        <span class="text-gray-500 block text-xs">Atualizada em:</span>
                        <span class="font-medium text-gray-900">
                            {{ $sector->secret_url_generated_at ? $sector->secret_url_generated_at->format('d/m/Y H:i') : 'N/A' }}
                        </span>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <span class="text-gray-500 block text-xs">Expira em:</span>
                        <span class="font-medium {{ $sector->secret_url_expires_at && $sector->secret_url_expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $sector->secret_url_expires_at ? $sector->secret_url_expires_at->format('d/m/Y H:i') : 'Nunca' }}
                        </span>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <span class="text-gray-500 block text-xs">Status:</span>
                        <span class="font-medium {{ $sector->isSecretUrlValid() ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ $sector->isSecretUrlValid() ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            {{ $sector->isSecretUrlValid() ? 'Válida' : 'Inválida' }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                <p class="text-yellow-700">Digite um slug acima e clique em "Salvar" para criar a URL.</p>
            </div>
        @endif
    </div>

    <!-- Ações -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-cog mr-2 text-gray-600"></i>
            Ações
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Habilitar/Desabilitar -->
            <button onclick="toggleSectorUrl({{ $sector->id }})" 
                    id="toggleUrlBtn"
                    class="flex items-center justify-center px-4 py-3 {{ $sector->secret_url_enabled ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas {{ $sector->secret_url_enabled ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                {{ $sector->secret_url_enabled ? 'Desabilitar URL' : 'Habilitar URL' }}
            </button>
            
            <!-- Definir Expiração -->
            <button onclick="toggleExpirationForm()" 
                    class="flex items-center justify-center px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-clock mr-2"></i>
                Definir Expiração
            </button>
            
            <!-- Gerar URL Aleatória -->
            <button onclick="regenerateSectorUrl({{ $sector->id }})" 
                    class="flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-random mr-2"></i>
                Gerar URL Aleatória
            </button>
        </div>
    </div>

    <!-- Formulário de Expiração -->
    <div id="expirationFormSector" class="bg-white border border-gray-200 rounded-lg p-6 hidden">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
            Definir Data de Expiração
        </h4>
        
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Data e Hora:</label>
                <input type="datetime-local" 
                       id="sectorExpirationDate" 
                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       value="{{ $sector->secret_url_expires_at ? $sector->secret_url_expires_at->format('Y-m-d\TH:i') : '' }}">
            </div>
            <button onclick="setSectorExpiration({{ $sector->id }})" 
                    class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-md transition-colors duration-200">
                <i class="fas fa-save mr-1"></i> Salvar
            </button>
            <button onclick="removeSectorExpiration({{ $sector->id }})" 
                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors duration-200">
                <i class="fas fa-times mr-1"></i> Remover
            </button>
        </div>
    </div>

    <!-- Logs de Acesso -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-history mr-2 text-blue-600"></i>
            Histórico de Acessos ({{ $recentLogs->count() }})
        </h4>
        
        @if($recentLogs->count() > 0)
            <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data/Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Navegador</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentLogs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->accessed_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                    {{ $log->ip_address ?: 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-xs" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 50) ?: 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-3xl mb-2"></i>
                <p>Nenhum acesso registrado ainda.</p>
            </div>
        @endif
    </div>
</div>

<script>
function copySectorUrl() {
    const input = document.getElementById('sectorSecretUrl');
    const btn = document.getElementById('copySectorUrlBtn');
    
    if (!input) return;
    
    navigator.clipboard.writeText(input.value).then(function() {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-2"></i> Copiado!';
        btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        btn.classList.add('bg-green-600');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-600');
            btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        }, 2000);
    }).catch(function(err) {
        input.select();
        document.execCommand('copy');
        alert('URL copiada!');
    });
}

function updateSectorUrl(sectorId) {
    const slugInput = document.getElementById('sectorUrlSlug');
    const slug = slugInput.value.trim();
    
    if (!slug) {
        alert('Digite uma URL válida');
        slugInput.focus();
        return;
    }
    
    // Validar formato
    if (!/^[a-zA-Z0-9_-]+$/.test(slug)) {
        alert('A URL deve conter apenas letras, números, hífens e underscores.');
        slugInput.focus();
        return;
    }
    
    if (slug.length < 3) {
        alert('A URL deve ter no mínimo 3 caracteres.');
        slugInput.focus();
        return;
    }
    
    const btn = document.getElementById('updateUrlBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Salvando...';
    btn.disabled = true;
    
    fetch(`/admin/sectors/${sectorId}/secret-url/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ secret_url: slug })
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('success', data.message);
            } else {
                alert(data.message);
            }
            // Recarregar para mostrar a nova URL
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.error('Erro:', error);
        alert('Erro ao salvar URL');
    });
}

function regenerateSectorUrl(sectorId) {
    if (!confirm('Tem certeza? Isso irá substituir a URL atual por uma aleatória.')) {
        return;
    }
    
    fetch(`/admin/sectors/${sectorId}/secret-url/regenerate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('success', data.message);
            }
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao regenerar URL');
    });
}

function toggleSectorUrl(sectorId) {
    fetch(`/admin/sectors/${sectorId}/secret-url/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('success', data.message);
            }
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao alterar status');
    });
}

function toggleExpirationForm() {
    const form = document.getElementById('expirationFormSector');
    form.classList.toggle('hidden');
}

function setSectorExpiration(sectorId) {
    const expirationDate = document.getElementById('sectorExpirationDate').value;
    
    if (!expirationDate) {
        alert('Selecione uma data de expiração');
        return;
    }
    
    fetch(`/admin/sectors/${sectorId}/secret-url/expiration`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ expires_at: expirationDate })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('success', data.message);
            }
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao definir expiração');
    });
}

function removeSectorExpiration(sectorId) {
    fetch(`/admin/sectors/${sectorId}/secret-url/expiration`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ expires_at: null })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') {
                showToast('success', data.message);
            }
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao remover expiração');
    });
}
</script>

