<div class="space-y-6">
    <!-- Cabeçalho com informações do card -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $card->name }}</h4>
        <p class="text-sm text-gray-600">{{ $card->description ?: 'Sem descrição' }}</p>
        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
            <span><i class="fas fa-link mr-1"></i>{{ $card->link }}</span>
            <span><i class="fas fa-folder mr-1"></i>{{ $card->tab->name }}</span>
        </div>
    </div>

    <!-- Lista de logins e senhas do sistema -->
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h5 class="text-md font-medium text-gray-900">Logins e Senhas do Sistema</h5>
            <button onclick="openCreateSystemLoginModal({{ $card->id }})" 
                    class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Login
            </button>
        </div>

        @if($systemLogins->count() > 0)
            <div class="space-y-3">
                @foreach($systemLogins as $systemLogin)
                    <div id="login-item-{{ $systemLogin->id }}" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                        <!-- Cabeçalho do Card -->
                        <div class="flex items-center justify-between mb-3">
                            <h6 class="font-semibold text-gray-900 text-lg">{{ $systemLogin->title }}</h6>
                            @if($systemLogin->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inativo
                                </span>
                            @endif
                        </div>
                        
                        <!-- Login e Senha na mesma linha -->
                        <div class="flex items-center space-x-6 login-password-row">
                            <!-- Campo Login -->
                            <div class="flex items-center space-x-2 flex-1">
                                <label class="text-sm font-medium text-gray-700 w-16">Login:</label>
                                <div class="flex-1 bg-white border border-gray-300 rounded px-4 py-2 text-sm font-mono">
                                    {{ $systemLogin->username }}
                                </div>
                                <button onclick="copyToClipboard('{{ $systemLogin->username }}', 'username', {{ $systemLogin->id }})" 
                                        id="copy-username-{{ $systemLogin->id }}"
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200 copy-button"
                                        title="Copiar login">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            
                            <!-- Campo Senha -->
                            <div class="flex items-center space-x-2 flex-1">
                                <label class="text-sm font-medium text-gray-700 w-16">Senha:</label>
                                <div class="flex-1 bg-white border border-gray-300 rounded px-4 py-2 text-sm font-mono relative">
                                    <span id="password-{{ $systemLogin->id }}" class="password-text" style="color: #6B7280;">••••••••</span>
                                    <button onclick="togglePasswordVisibility({{ $systemLogin->id }}, '{{ $systemLogin->password }}')" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors duration-200 eye-button"
                                            title="Mostrar/Ocultar senha">
                                        <i class="fas fa-eye" id="eye-icon-{{ $systemLogin->id }}" style="color: #6B7280;"></i>
                                    </button>
                                </div>
                                <button onclick="copyToClipboard('{{ $systemLogin->password }}', 'password', {{ $systemLogin->id }})" 
                                        id="copy-password-{{ $systemLogin->id }}"
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200 copy-button"
                                        title="Copiar senha">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Botões de Ação -->
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button onclick="openPermissionsModal({{ $systemLogin->id }})" 
                                        class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
                                        title="Gerenciar permissões de usuários">
                                    <i class="fas fa-users mr-1"></i>
                                    Usuários
                                </button>
                                <button onclick="openEditSystemLoginModal({{ $systemLogin->id }})" 
                                        class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
                                        title="Editar login">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </button>
                                <button onclick="deleteSystemLogin({{ $systemLogin->id }})" 
                                        class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
                                        title="Excluir login">
                                    <i class="fas fa-trash mr-1"></i>
                                    Excluir
                                </button>
                            </div>
                            
                            <!-- Status do Login -->
                            <div class="flex items-center space-x-2">
                                @if($systemLogin->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Inativo
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($systemLogin->notes)
                            <div class="mt-3 p-2 bg-gray-50 rounded text-xs text-gray-600">
                                <strong>Observações:</strong> {{ $systemLogin->notes }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-key text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum login cadastrado</h3>
                <p class="text-gray-500 mb-4">Este sistema ainda não possui logins e senhas cadastrados.</p>
                <button onclick="openCreateSystemLoginModal({{ $card->id }})" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Cadastrar Primeiro Login
                </button>
            </div>
        @endif
    </div>

    <!-- Informações adicionais -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Como funciona</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>• Aqui você cadastra os logins e senhas de acesso aos sistemas</p>
                    <p>• Exemplo: "Administrador", "Usuário Padrão", "Suporte" com suas respectivas credenciais</p>
                    <p>• Usuários logados veem apenas os logins dos sistemas que têm permissão</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para mostrar/ocultar a senha
function togglePasswordVisibility(loginId, password) {
    const passwordText = document.getElementById(`password-${loginId}`);
    const eyeIcon = document.getElementById(`eye-icon-${loginId}`);

    if (!passwordText || !eyeIcon) {
        console.error('Elementos não encontrados para login ID:', loginId);
        return;
    }

    if (passwordText.textContent === '••••••••') {
        // Mostrar senha
        passwordText.style.transition = 'all 0.3s ease';
        passwordText.textContent = password;
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
        eyeIcon.style.color = '#3B82F6'; // Azul para indicar que está visível
        passwordText.style.color = '#1F2937'; // Texto mais escuro
    } else {
        // Ocultar senha
        passwordText.style.transition = 'all 0.3s ease';
        passwordText.textContent = '••••••••';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
        eyeIcon.style.color = '#6B7280'; // Cinza para indicar que está oculto
        passwordText.style.color = '#6B7280'; // Texto cinza para asteriscos
    }
}

// Função para copiar para clipboard
function copyToClipboard(text, type, loginId) {
    // Usar fallback para navegadores mais antigos
    if (navigator.clipboard && window.isSecureContext) {
        // Método moderno
        navigator.clipboard.writeText(text).then(function() {
            showCopyFeedback(type, loginId);
        }).catch(function(err) {
            console.error('Erro ao copiar: ', err);
            fallbackCopyTextToClipboard(text, type, loginId);
        });
    } else {
        // Fallback para navegadores antigos
        fallbackCopyTextToClipboard(text, type, loginId);
    }
}

// Função fallback para copiar texto
function fallbackCopyTextToClipboard(text, type, loginId) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyFeedback(type, loginId);
        } else {
            alert('Erro ao copiar para a área de transferência');
        }
    } catch (err) {
        console.error('Erro ao copiar: ', err);
        alert('Erro ao copiar para a área de transferência');
    }
    
    document.body.removeChild(textArea);
}

// Função para mostrar feedback visual de cópia
function showCopyFeedback(type, loginId) {
    const button = document.getElementById(`copy-${type}-${loginId}`);
    if (!button) return;
    
    const originalHTML = button.innerHTML;
    const originalClasses = button.className;
    
    // Mudar para estado "Copiado!"
    button.innerHTML = '<i class="fas fa-check mr-1"></i>Copiado!';
    button.className = 'px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-md transition-all duration-300 copy-button copy-feedback';
    
    // Restaurar após 2 segundos
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.className = originalClasses;
    }, 2000);
}

// Garantir que as funções estejam disponíveis globalmente
window.togglePasswordVisibility = togglePasswordVisibility;
window.copyToClipboard = copyToClipboard;
window.showCopyFeedback = showCopyFeedback;
window.fallbackCopyTextToClipboard = fallbackCopyTextToClipboard;
</script>

<style>
/* Layout responsivo para login e senha */
@media (max-width: 768px) {
    .login-password-row {
        flex-direction: column;
        space-y: 3;
    }
    
    .login-password-row > div {
        flex: none;
        width: 100%;
    }

    /* Ajustes para mobile */
    .login-password-row {
        gap: 0.75rem;
    }
}

/* Estilos para botões de copiar */
.copy-button {
    transition: all 0.3s ease;
}

/* Animação suave para feedback de cópia */
.copy-feedback {
    animation: copySuccess 0.3s ease-in-out;
}

@keyframes copySuccess {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Melhorias nos campos de input */
.login-password-row input,
.login-password-row .bg-white {
    min-width: 200px; /* Largura mínima para os campos */
}

/* Ajuste para o botão de olho */
.eye-button {
    padding: 4px;
    border-radius: 4px;
}

.eye-button:hover {
    background-color: rgba(59, 130, 246, 0.1);
}

/* Estilos para senha */
.password-text {
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
    user-select: none; /* Previne seleção de texto */
}
</style>

<script>

// Função para abrir o modal de permissões
function openPermissionsModal(systemLoginId) {
    console.log('=== DEBUG: openPermissionsModal chamado ===');
    console.log('SystemLogin ID:', systemLoginId);
    
    // Mostrar loading
    const modal = document.getElementById('permissionsModal');
    if (!modal) {
        console.error('Modal de permissões não encontrado');
        showErrorToast('Erro: Modal de permissões não encontrado', 5000);
        return;
    }
    
    // Mostrar modal com loading
    modal.innerHTML = `
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando permissões...</p>
                </div>
            </div>
        </div>
    `;
    modal.classList.remove('hidden');
    
    // Carregar dados do modal
    fetch(`/admin/system-logins/${systemLoginId}/permissions`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Substituir o conteúdo do modal
            modal.innerHTML = data.html;
        } else {
            showErrorToast('Erro ao carregar permissões', 5000);
            closePermissionsModal();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showErrorToast('Erro ao carregar permissões', 5000);
        closePermissionsModal();
    });
}

// Função para fechar o modal de permissões
function closePermissionsModal() {
    const modal = document.getElementById('permissionsModal');
    if (modal) {
        modal.classList.add('hidden');
        // Limpar o conteúdo do modal para evitar problemas
        modal.innerHTML = '';
    }
}

// Função para salvar as permissões
function savePermissions(systemLoginId) {
    console.log('=== DEBUG: savePermissions chamado ===');
    console.log('SystemLogin ID:', systemLoginId);
    
    const checkboxes = document.querySelectorAll('input[name="user_ids[]"]:checked');
    const userIds = Array.from(checkboxes).map(cb => cb.value);
    
    console.log('Usuários selecionados:', userIds);
    
    // Mostrar loading
    const saveButton = event.target;
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
    saveButton.disabled = true;
    
    fetch(`/admin/system-logins/${systemLoginId}/permissions`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            user_ids: userIds
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            if (typeof showSuccessToast === 'function') {
                showSuccessToast(data.message, 3000);
            } else {
                alert('Sucesso: ' + data.message);
            }
            // Fechar modal e recarregar a página para atualizar as permissões
            closePermissionsModal();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            if (typeof showErrorToast === 'function') {
                showErrorToast(data.message || 'Erro ao salvar permissões', 5000);
            } else {
                alert('Erro: ' + (data.message || 'Erro ao salvar permissões'));
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        if (typeof showErrorToast === 'function') {
            showErrorToast('Erro ao salvar permissões: ' + error.message, 5000);
        } else {
            alert('Erro ao salvar permissões: ' + error.message);
        }
    })
    .finally(() => {
        // Restaurar botão
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

// Função para abrir modal de edição de login
function openEditSystemLoginModal(systemLoginId) {
    console.log('=== DEBUG: openEditSystemLoginModal chamado ===');
    console.log('SystemLogin ID:', systemLoginId);
    
    // Buscar dados do login
    fetch(`/admin/system-logins/${systemLoginId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const login = data.data;
            
            // Preencher o formulário de edição
            document.getElementById('editSystemLoginId').value = login.id;
            document.getElementById('editTitle').value = login.title;
            document.getElementById('editUsername').value = login.username;
            document.getElementById('editPassword').value = login.password;
            document.getElementById('editNotes').value = login.notes || '';
            document.getElementById('editIsActive').checked = login.is_active;
            
            // Mostrar o modal
            document.getElementById('editSystemLoginModal').classList.remove('hidden');
        } else {
            alert('Erro ao carregar dados do login: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao carregar dados do login: ' + error.message);
    });
}

// Função para fechar modal de edição
function closeEditSystemLoginModal() {
    document.getElementById('editSystemLoginModal').classList.add('hidden');
}

// Função para alternar visibilidade da senha
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Função para salvar edição de login
function saveEditSystemLogin() {
    const form = document.getElementById('editSystemLoginForm');
    const formData = new FormData(form);
    const systemLoginId = document.getElementById('editSystemLoginId').value;
    
    // Converter FormData para objeto
    const data = {};
    formData.forEach((value, key) => {
        if (key === 'is_active') {
            data[key] = document.getElementById('editIsActive').checked;
        } else {
            data[key] = value;
        }
    });
    
    console.log('=== DEBUG: saveEditSystemLogin ===');
    console.log('Data:', data);
    
    // Mostrar loading
    const saveButton = document.querySelector('#editSystemLoginModal button[type="submit"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
    saveButton.disabled = true;
    
    fetch(`/admin/system-logins/${systemLoginId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (typeof showSuccessToast === 'function') {
                showSuccessToast(data.message || 'Login atualizado com sucesso!', 3000);
            } else {
                alert('Login atualizado com sucesso!');
            }
            closeEditSystemLoginModal();
            // Recarregar a página para mostrar as mudanças
            window.location.reload();
        } else {
            if (typeof showErrorToast === 'function') {
                showErrorToast(data.message || 'Erro ao atualizar login', 5000);
            } else {
                alert('Erro: ' + (data.message || 'Erro ao atualizar login'));
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        if (typeof showErrorToast === 'function') {
            showErrorToast('Erro ao atualizar login: ' + error.message, 5000);
        } else {
            alert('Erro ao atualizar login: ' + error.message);
        }
    })
    .finally(() => {
        // Restaurar botão
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

// Função para excluir login
function deleteSystemLogin(systemLoginId) {
    console.log('=== DEBUG: deleteSystemLogin chamado ===');
    console.log('SystemLogin ID:', systemLoginId);
    
    // Confirmar exclusão
    if (!confirm('Tem certeza que deseja excluir este login? Esta ação não pode ser desfeita.')) {
        return;
    }
    
    // Mostrar loading
    const deleteButton = event.target;
    const originalText = deleteButton.innerHTML;
    deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Excluindo...';
    deleteButton.disabled = true;
    
    fetch(`/admin/system-logins/${systemLoginId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            if (typeof showSuccessToast === 'function') {
                showSuccessToast(data.message || 'Login excluído com sucesso!', 3000);
            } else {
                alert('Login excluído com sucesso!');
            }
            
            // Remover o elemento da lista dinamicamente
            const loginItem = document.getElementById(`login-item-${systemLoginId}`);
            if (loginItem) {
                // Adicionar animação de fade out
                loginItem.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                loginItem.style.opacity = '0';
                loginItem.style.transform = 'translateX(-100%)';
                
                // Remover o elemento após a animação
                setTimeout(() => {
                    loginItem.remove();
                    
                    // Verificar se não há mais logins e mostrar mensagem
                    const remainingLogins = document.querySelectorAll('[id^="login-item-"]');
                    if (remainingLogins.length === 0) {
                        const container = document.querySelector('.space-y-3');
                        if (container) {
                            container.innerHTML = '<div class="text-center py-8 text-gray-500"><i class="fas fa-info-circle text-2xl mb-2"></i><p>Nenhum login cadastrado para este sistema.</p></div>';
                        }
                    }
                }, 300);
            }
        } else {
            if (typeof showErrorToast === 'function') {
                showErrorToast(data.message || 'Erro ao excluir login', 5000);
            } else {
                alert('Erro: ' + (data.message || 'Erro ao excluir login'));
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        if (typeof showErrorToast === 'function') {
            showErrorToast('Erro ao excluir login: ' + error.message, 5000);
        } else {
            alert('Erro ao excluir login: ' + error.message);
        }
    })
    .finally(() => {
        // Restaurar botão
        deleteButton.innerHTML = originalText;
        deleteButton.disabled = false;
    });
}

// Garantir que as funções estejam disponíveis globalmente
window.openPermissionsModal = openPermissionsModal;
window.closePermissionsModal = closePermissionsModal;
window.savePermissions = savePermissions;
window.openEditSystemLoginModal = openEditSystemLoginModal;
window.closeEditSystemLoginModal = closeEditSystemLoginModal;
window.saveEditSystemLogin = saveEditSystemLogin;
window.deleteSystemLogin = deleteSystemLogin;
window.togglePasswordVisibility = togglePasswordVisibility;

console.log('=== DEBUG: Script de togglePassword carregado ===');
console.log('Função togglePassword disponível:', typeof window.togglePassword);
console.log('Função openPermissionsModal disponível:', typeof window.openPermissionsModal);
console.log('Função savePermissions disponível:', typeof window.savePermissions);
</script>

<!-- Modal de Permissões (será carregado dinamicamente) -->
<div id="permissionsModal" class="hidden"></div>

<!-- Modal de Edição de Login -->
<div id="editSystemLoginModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Editar Login</h3>
                <button onclick="closeEditSystemLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6">
                <form id="editSystemLoginForm" onsubmit="event.preventDefault(); saveEditSystemLogin();">
                    <input type="hidden" id="editSystemLoginId" name="id">
                    
                    <!-- Título -->
                    <div class="mb-4">
                        <label for="editTitle" class="block text-sm font-medium text-gray-700 mb-2">
                            Título *
                        </label>
                        <input type="text" 
                               id="editTitle" 
                               name="title" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Username -->
                    <div class="mb-4">
                        <label for="editUsername" class="block text-sm font-medium text-gray-700 mb-2">
                            Username *
                        </label>
                        <input type="text" 
                               id="editUsername" 
                               name="username" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Senha -->
                    <div class="mb-4">
                        <label for="editPassword" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha *
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   id="editPassword" 
                                   name="password" 
                                   required
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('editPassword')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Notas -->
                    <div class="mb-4">
                        <label for="editNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas
                        </label>
                        <textarea id="editNotes" 
                                  name="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <!-- Status Ativo -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   id="editIsActive" 
                                   name="is_active" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Login ativo</span>
                        </label>
                    </div>
                    
                    <!-- Botões -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeEditSystemLoginModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
