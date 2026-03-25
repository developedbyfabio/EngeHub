<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-lg text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">URL Secreta</h3>
                <p class="text-purple-200 text-sm mt-1">Gerencie o acesso por URL secreta para: <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $systemUser->secret_url_enabled ? 'bg-green-500' : 'bg-red-500' }}">
                    <i class="fas {{ $systemUser->secret_url_enabled ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                    {{ $systemUser->secret_url_enabled ? 'Ativa' : 'Desativada' }}
                </span>
            </div>
        </div>
    </div>

    <!-- URL Secreta -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-link mr-2 text-purple-600"></i>
            URL de Acesso
        </h4>
        
        @if($systemUser->secret_url)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 mr-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL Completa:</label>
                        <div class="flex items-center">
                            <input type="text" 
                                   id="secretUrlInput" 
                                   value="{{ $systemUser->full_secret_url }}" 
                                   readonly 
                                   class="flex-1 bg-white border border-gray-300 rounded-l-md px-4 py-2 text-sm font-mono text-gray-700 focus:outline-none">
                            <button onclick="copySecretUrl()" 
                                    id="copyUrlBtn"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-r-md transition-colors duration-200"
                                    title="Copiar URL">
                                <i class="fas fa-copy mr-1"></i> Copiar
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Gerada em:</span>
                        <span class="font-medium text-gray-900 ml-1">
                            {{ $systemUser->secret_url_generated_at ? $systemUser->secret_url_generated_at->format('d/m/Y H:i') : 'N/A' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Expira em:</span>
                        <span class="font-medium {{ $systemUser->secret_url_expires_at && $systemUser->secret_url_expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }} ml-1">
                            {{ $systemUser->secret_url_expires_at ? $systemUser->secret_url_expires_at->format('d/m/Y H:i') : 'Nunca' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="font-medium ml-1 {{ $systemUser->isSecretUrlValid() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $systemUser->isSecretUrlValid() ? 'Válida' : 'Inválida' }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
                <p class="text-yellow-700">Nenhuma URL secreta gerada para este usuário.</p>
                <p class="text-yellow-600 text-sm mt-1">Clique em "Gerar URL" para criar uma nova.</p>
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
            <!-- Regenerar URL -->
            <button onclick="regenerateSecretUrl({{ $user->id }})" 
                    class="flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-sync-alt mr-2"></i>
                {{ $systemUser->secret_url ? 'Regenerar URL' : 'Gerar URL' }}
            </button>
            
            <!-- Habilitar/Desabilitar -->
            <button onclick="toggleSecretUrl({{ $user->id }})" 
                    class="flex items-center justify-center px-4 py-3 {{ $systemUser->secret_url_enabled ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas {{ $systemUser->secret_url_enabled ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                {{ $systemUser->secret_url_enabled ? 'Desabilitar' : 'Habilitar' }}
            </button>
            
            <!-- Definir Expiração -->
            <button onclick="showExpirationModal()" 
                    class="flex items-center justify-center px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-clock mr-2"></i>
                Definir Expiração
            </button>
        </div>
    </div>

    <!-- Definir Expiração Form (inicialmente oculto) -->
    <div id="expirationForm" class="bg-white border border-gray-200 rounded-lg p-6 hidden">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
            Definir Data de Expiração
        </h4>
        
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Data e Hora de Expiração:</label>
                <input type="datetime-local" 
                       id="expirationDate" 
                       class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                       value="{{ $systemUser->secret_url_expires_at ? $systemUser->secret_url_expires_at->format('Y-m-d\TH:i') : '' }}">
            </div>
            <button onclick="setExpiration({{ $user->id }})" 
                    class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-md transition-colors duration-200">
                Salvar
            </button>
            <button onclick="removeExpiration({{ $user->id }})" 
                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors duration-200">
                Remover Expiração
            </button>
            <button onclick="hideExpirationModal()" 
                    class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors duration-200">
                Cancelar
            </button>
        </div>
    </div>

    <!-- Logs de Acesso -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-history mr-2 text-blue-600"></i>
            Últimos Acessos ({{ $recentLogs->count() }})
        </h4>
        
        @if($recentLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Navegador</th>
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
function copySecretUrl() {
    const input = document.getElementById('secretUrlInput');
    const btn = document.getElementById('copyUrlBtn');
    
    navigator.clipboard.writeText(input.value).then(function() {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-1"></i> Copiado!';
        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        btn.classList.add('bg-green-600');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-600');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }).catch(function(err) {
        input.select();
        document.execCommand('copy');
        alert('URL copiada!');
    });
}

function regenerateSecretUrl(userId) {
    if (!confirm('Tem certeza que deseja regenerar a URL secreta? A URL anterior será invalidada.')) {
        return;
    }
    
    fetch(`/admin/system-users/${userId}/secret-url/regenerate`, {
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
            } else {
                alert(data.message);
            }
            // Recarregar modal
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao regenerar URL secreta');
    });
}

function toggleSecretUrl(userId) {
    fetch(`/admin/system-users/${userId}/secret-url/toggle`, {
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
            } else {
                alert(data.message);
            }
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao alterar status da URL secreta');
    });
}

function showExpirationModal() {
    document.getElementById('expirationForm').classList.remove('hidden');
}

function hideExpirationModal() {
    document.getElementById('expirationForm').classList.add('hidden');
}

function setExpiration(userId) {
    const expirationDate = document.getElementById('expirationDate').value;
    
    fetch(`/admin/system-users/${userId}/secret-url/expiration`, {
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
            } else {
                alert(data.message);
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

function removeExpiration(userId) {
    fetch(`/admin/system-users/${userId}/secret-url/expiration`, {
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
            } else {
                alert(data.message);
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




