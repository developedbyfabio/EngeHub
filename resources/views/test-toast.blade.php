@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
                    Teste do Sistema de Toast
                </h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Botão de Sucesso -->
                    <button onclick="showSuccessToast('Login realizado com sucesso!', 5000)" 
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Sucesso
                    </button>
                    
                    <!-- Botão de Erro -->
                    <button onclick="showErrorToast('Erro ao processar solicitação!', 6000)" 
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Erro
                    </button>
                    
                    <!-- Botão de Aviso -->
                    <button onclick="showWarningToast('Atenção: Verifique os dados!', 5000)" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Aviso
                    </button>
                    
                    <!-- Botão de Informação -->
                    <button onclick="showInfoToast('Informação importante disponível!', 4000)" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        <i class="fas fa-info mr-2"></i>
                        Informação
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <!-- Botão de Múltiplos Toasts -->
                    <button onclick="testMultipleToasts()" 
                            class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        <i class="fas fa-layer-group mr-2"></i>
                        Múltiplos Toasts
                    </button>
                    
                    <!-- Botão de Fechar Todos -->
                    <button onclick="toastNotification.closeAll()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                        <i class="fas fa-times-circle mr-2"></i>
                        Fechar Todos
                    </button>
                </div>

                <div class="bg-gray-100 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4">Como usar o Sistema de Toast:</h2>
                    <div class="space-y-2 text-sm">
                        <p><strong>showSuccessToast(message, duration):</strong> Exibe toast de sucesso</p>
                        <p><strong>showErrorToast(message, duration):</strong> Exibe toast de erro</p>
                        <p><strong>showWarningToast(message, duration):</strong> Exibe toast de aviso</p>
                        <p><strong>showInfoToast(message, duration):</strong> Exibe toast de informação</p>
                        <p><strong>showToast(message, type, duration):</strong> Função genérica</p>
                        <p><strong>closeToast(toastId):</strong> Fecha toast específico</p>
                        <p><strong>toastNotification.closeAll():</strong> Fecha todos os toasts</p>
                    </div>
                </div>

                <!-- Simular mensagem de login -->
                <div class="mt-8 text-center">
                    <button onclick="simulateLogin()" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Simular Login (Toast de Sucesso)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para testar múltiplos toasts
function testMultipleToasts() {
    showSuccessToast('Primeiro toast - Sucesso!', 3000);
    setTimeout(() => showInfoToast('Segundo toast - Informação!', 3000), 500);
    setTimeout(() => showWarningToast('Terceiro toast - Aviso!', 3000), 1000);
    setTimeout(() => showErrorToast('Quarto toast - Erro!', 3000), 1500);
}

// Função para simular login
function simulateLogin() {
    showSuccessToast('Logado com sucesso como Administrador!', 5000);
}

// Teste automático quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    // Aguardar um pouco para garantir que o sistema esteja carregado
    setTimeout(() => {
        if (typeof showSuccessToast === 'function') {
            showSuccessToast('Sistema de Toast carregado com sucesso!', 3000);
        }
    }, 1000);
});
</script>
@endsection
