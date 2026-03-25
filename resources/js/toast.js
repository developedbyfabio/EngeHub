// Toast Notification System
class ToastNotification {
    constructor() {
        this.container = document.getElementById('toast-container');
        this.toasts = new Map();
        
        // Verificar se o container existe
        if (!this.container) {
            console.error('Toast container not found! Make sure toast-notification.blade.php is included.');
            return;
        }
    }

    show(message, type = 'success', duration = 4000) {
        // Verificar se o container existe
        if (!this.container) {
            console.error('Toast container not available');
            return null;
        }

        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        
        // Definir cores baseadas no tipo
        const typeConfig = {
            success: {
                bgColor: 'bg-green-500',
                textColor: 'text-white',
                icon: 'fas fa-check-circle',
                borderColor: 'border-green-600',
                progressColor: 'bg-green-300'
            },
            error: {
                bgColor: 'bg-red-500',
                textColor: 'text-white',
                icon: 'fas fa-exclamation-circle',
                borderColor: 'border-red-600',
                progressColor: 'bg-red-300'
            },
            warning: {
                bgColor: 'bg-yellow-500',
                textColor: 'text-white',
                icon: 'fas fa-exclamation-triangle',
                borderColor: 'border-yellow-600',
                progressColor: 'bg-yellow-300'
            },
            info: {
                bgColor: 'bg-blue-500',
                textColor: 'text-white',
                icon: 'fas fa-info-circle',
                borderColor: 'border-blue-600',
                progressColor: 'bg-blue-300'
            }
        };

        const config = typeConfig[type] || typeConfig.success;

        // Criar elemento do toast
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `
            ${config.bgColor} ${config.textColor} ${config.borderColor}
            border-l-4 px-6 py-4 shadow-lg rounded-lg
            transform transition-all duration-300 ease-in-out
            translate-x-full opacity-0
            max-w-sm w-full
            flex items-center space-x-3
            cursor-pointer
            relative overflow-hidden
            toast-hover
        `;
        
        toast.innerHTML = `
            <div class="flex-shrink-0">
                <i class="${config.icon} text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="flex-shrink-0">
                <button onclick="window.toastNotification.close('${toastId}')" 
                        class="ml-4 text-white hover:text-gray-200 transition-colors duration-200 focus:outline-none">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <!-- Barra de progresso -->
            <div class="absolute bottom-0 left-0 h-1 ${config.progressColor} rounded-b-lg toast-progress" 
                 style="animation-duration: ${duration}ms;"></div>
        `;

        // Adicionar ao container
        this.container.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 10);

        // Armazenar referência
        this.toasts.set(toastId, toast);

        // Auto-remover após duração especificada
        setTimeout(() => {
            this.close(toastId);
        }, duration);

        // Adicionar evento de clique para fechar
        toast.addEventListener('click', (e) => {
            // Não fechar se clicar no botão de fechar (já tem seu próprio handler)
            if (!e.target.closest('button')) {
                this.close(toastId);
            }
        });

        // Adicionar evento de hover para pausar o progresso
        toast.addEventListener('mouseenter', () => {
            const progressBar = toast.querySelector('.toast-progress');
            if (progressBar) {
                progressBar.style.animationPlayState = 'paused';
            }
        });

        toast.addEventListener('mouseleave', () => {
            const progressBar = toast.querySelector('.toast-progress');
            if (progressBar) {
                progressBar.style.animationPlayState = 'running';
            }
        });

        return toastId;
    }

    close(toastId) {
        const toast = this.toasts.get(toastId);
        if (!toast) return;

        // Animar saída
        toast.classList.add('translate-x-full', 'opacity-0');
        toast.classList.remove('translate-x-0', 'opacity-100');

        // Remover do DOM após animação
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            this.toasts.delete(toastId);
        }, 300);
    }

    closeAll() {
        this.toasts.forEach((toast, toastId) => {
            this.close(toastId);
        });
    }
}

// Função para inicializar o sistema de toast
function initializeToastSystem() {
    // Verificar se já foi inicializado
    if (window.toastNotification) {
        return;
    }

    // Inicializar sistema de toast
    window.toastNotification = new ToastNotification();

    // Função global para facilitar uso
    window.showToast = function(message, type = 'success', duration = 4000) {
        if (window.toastNotification) {
            return window.toastNotification.show(message, type, duration);
        }
        console.error('Toast system not initialized');
        return null;
    };

    // Função para fechar toast específico
    window.closeToast = function(toastId) {
        if (window.toastNotification) {
            window.toastNotification.close(toastId);
        }
    };

    // Função para mostrar diferentes tipos de toast (conveniência)
    window.showSuccessToast = function(message, duration = 4000) {
        return window.showToast(message, 'success', duration);
    };

    window.showErrorToast = function(message, duration = 6000) {
        return window.showToast(message, 'error', duration);
    };

    window.showWarningToast = function(message, duration = 5000) {
        return window.showToast(message, 'warning', duration);
    };

    window.showInfoToast = function(message, duration = 4000) {
        return window.showToast(message, 'info', duration);
    };

    /**
     * Grava um toast para ser exibido após location.reload() (mesmo padrão de duração do flash pós-login: 5000ms).
     */
    window.queueToastAfterReload = function(message, type = 'success', duration = 5000) {
        if (!message) {
            return;
        }
        try {
            sessionStorage.setItem('engehub_toast_after_reload', JSON.stringify({
                message: String(message),
                type: type || 'success',
                duration: typeof duration === 'number' ? duration : 5000,
            }));
        } catch (e) {
            /* storage indisponível */
        }
    };

    consumeToastQueuedForReload();

    console.log('Toast system initialized successfully');
}

/** Exibe toast gravado antes de um reload (ex.: criar/editar servidor, verificar status). */
function consumeToastQueuedForReload() {
    try {
        const raw = sessionStorage.getItem('engehub_toast_after_reload');
        if (!raw) {
            return;
        }
        sessionStorage.removeItem('engehub_toast_after_reload');
        const payload = JSON.parse(raw);
        const msg = payload && payload.message;
        if (!msg || typeof window.showToast !== 'function') {
            return;
        }
        const type = payload.type || 'success';
        const duration = typeof payload.duration === 'number' ? payload.duration : 5000;
        setTimeout(function() {
            window.showToast(msg, type, duration);
        }, 200);
    } catch (e) {
        try {
            sessionStorage.removeItem('engehub_toast_after_reload');
        } catch (err) {
            /* ignore */
        }
    }
}

// Inicializar sistema de toast quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeToastSystem);
} else {
    // DOM já está pronto
    initializeToastSystem();
}
