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
        </div>

        @if($systemLogins->count() > 0)
            <div class="space-y-3">
                @foreach($systemLogins as $systemLogin)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
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
            </div>
        @endif
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









