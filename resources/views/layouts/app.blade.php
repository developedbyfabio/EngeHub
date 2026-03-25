<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">

    <title>{{ config('app.name', 'EngeHub - Intranet') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('media/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased custom-wallpaper-bg">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Toast Notification System -->
    @include('components.toast-notification')
    
    <!-- Modal de Confirmação de Logout -->
    <div id="logoutConfirmModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-[9999] hidden" style="display: none;">
        <div class="modal-dark rounded-2xl p-6 max-w-sm w-full mx-4 text-center" style="border-radius: 24px !important; overflow: hidden;">
            <!-- Ícone de Aviso -->
            <div class="mb-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-500/20">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                </div>
            </div>
            
            <!-- Mensagem -->
            <h3 class="modal-dark-title text-lg font-semibold mb-2">Confirmar Logout</h3>
            <p class="modal-dark-text text-sm mb-6">Tem certeza que deseja sair do sistema?</p>
            
            <!-- Botões -->
            <div class="flex space-x-3">
                <button type="button" onclick="hideLogoutConfirmModal()" 
                        class="flex-1 px-4 py-2 border border-gray-600 rounded-xl text-sm font-medium text-gray-300 bg-gray-700/50 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#1f1f1f] focus:ring-blue-500 transition-colors duration-150"
                        style="border-radius: 12px !important;">
                    Cancelar
                </button>
                <button type="button" onclick="confirmLogout()" 
                        class="flex-1 px-4 py-2 bg-red-600 border border-transparent rounded-xl text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#1f1f1f] focus:ring-red-500 transition-colors duration-150"
                        style="border-radius: 12px !important;">
                    Sim, Sair
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Login -->
    @php
        $isAuthenticated = auth()->check() || auth()->guard('system')->check();
        $requireLogin = request()->routeIs('home') && !$isAuthenticated;
    @endphp
    <div id="loginModal" 
         class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-[9999] {{ $requireLogin ? 'show' : 'hidden' }}" 
         style="{{ $requireLogin ? 'display: flex;' : 'display: none;' }}" 
         data-required="{{ $requireLogin ? 'true' : 'false' }}"
         onclick="handleLoginModalClick(event)">
        <div id="loginModalContent" class="modal-dark rounded-2xl p-6 max-w-md w-full mx-4 text-center" style="border-radius: 24px !important; overflow: hidden;" onclick="event.stopPropagation()">
            <!-- Título -->
            <h3 class="modal-dark-title text-xl font-bold mb-2">EngeHub</h3>
            @if($requireLogin)
                <p class="modal-dark-text text-sm mb-4">Faça login para acessar o EngeHub</p>
            @else
                <div class="mb-4"></div>
            @endif
            
            <!-- Mensagem de erro ao tentar fechar sem autenticar -->
            <div id="loginModalRequiredError" class="hidden mb-4 p-3 rounded-lg bg-red-500/20 border border-red-500/30">
                <p class="text-sm text-red-400 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Você precisa fazer login para utilizar o sistema.
                </p>
            </div>
            
            <!-- Formulário de Login -->
            <form id="modalLoginForm" method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Campo Usuário -->
                <div class="mb-4 text-left">
                    <label for="modal_username" class="modal-dark-label block text-sm font-medium mb-2">
                        <i class="fas fa-user mr-1"></i>
                        Usuário
                    </label>
                    <input id="modal_username" 
                           type="text" 
                           name="username" 
                           required 
                           autofocus
                           class="modal-dark-input w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-150"
                           style="border-radius: 12px !important;"
                           placeholder="Digite seu usuário">
                    <div id="username_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>
                
                <!-- Campo Senha -->
                <div class="mb-4 text-left">
                    <label for="modal_password" class="modal-dark-label block text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-1"></i>
                        Senha
                    </label>
                    <input id="modal_password" 
                           type="password" 
                           name="password" 
                           required
                           class="modal-dark-input w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-150"
                           style="border-radius: 12px !important;"
                           placeholder="Digite sua senha">
                    <div id="password_error" class="text-red-400 text-xs mt-1 hidden"></div>
                </div>
                
                <!-- Lembrar de mim -->
                <div class="mb-6 text-left">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-500 text-blue-600 shadow-sm focus:ring-blue-500 bg-gray-600">
                        <span class="modal-dark-text ml-2 text-sm">Lembrar de mim</span>
                    </label>
                </div>
                
                <!-- Botões -->
                <div class="flex {{ $requireLogin ? 'justify-center' : 'space-x-3' }}">
                    @if(!$requireLogin)
                    <button type="button" onclick="hideLoginModal()" 
                            id="loginCancelBtn"
                            class="flex-1 px-4 py-2 border border-gray-600 rounded-xl text-sm font-medium text-gray-300 bg-gray-700/50 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#1f1f1f] focus:ring-blue-500 transition-colors duration-150"
                            style="border-radius: 12px !important;">
                        Cancelar
                    </button>
                    @endif
                    <button type="submit" 
                            class="{{ $requireLogin ? 'w-full' : 'flex-1' }} px-4 py-2 bg-blue-600 border border-transparent rounded-xl text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#1f1f1f] focus:ring-blue-500 transition-colors duration-150"
                            style="border-radius: 12px !important;">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Estilo para modal de logout com efeito sutil -->
    <style>
        /* Forçar tema claro independente do tema do navegador */
        html { color-scheme: light; }
        
        /* Modal de logout com transição suave */
        #logoutConfirmModal {
            opacity: 0;
            transition: opacity 0.2s ease-out;
            z-index: 9999 !important;
        }
        
        #logoutConfirmModal.show {
            opacity: 1 !important;
            backdrop-filter: blur(4px);
        }
        
        /* Efeito sutil de aparição do modal */
        #logoutConfirmModal > div {
            transform: scale(0.95);
            transition: transform 0.2s ease-out;
            border-radius: 24px !important;
            overflow: hidden;
        }
        
        #logoutConfirmModal.show > div {
            transform: scale(1);
        }
        
        /* Bordas arredondadas forçadas para botões */
        #logoutConfirmModal button {
            border-radius: 12px !important;
        }
        
        /* Garantir que o ícone também tenha bordas arredondadas */
        #logoutConfirmModal .rounded-full {
            border-radius: 50% !important;
        }
        
        /* Modal de login com transição suave */
        #loginModal {
            opacity: 0;
            transition: opacity 0.2s ease-out;
            z-index: 9999 !important;
        }
        
        #loginModal.show {
            opacity: 1 !important;
            backdrop-filter: blur(4px);
        }
        
        /* Efeito sutil de aparição do modal de login */
        #loginModal > div {
            transform: scale(0.95);
            transition: transform 0.2s ease-out;
            border-radius: 24px !important;
            overflow: hidden;
        }
        
        #loginModal.show > div {
            transform: scale(1);
        }
        
        /* Bordas arredondadas forçadas para campos e botões do login */
        #loginModal input, #loginModal button {
            border-radius: 12px !important;
        }
        
        /* Garantir que o ícone do login também tenha bordas arredondadas */
        #loginModal .rounded-full {
            border-radius: 50% !important;
        }
        
        /* Background wallpaper personalizado */
        .custom-wallpaper-bg {
            background-image: url('/media/Wallpaper 1920x1080.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        /* Overlay para melhorar legibilidade do conteúdo */
        .content-overlay {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(1px);
        }
        
        /* Remover overlay geral - deixar wallpaper visível */
        
        /* Aplicar fundo semi-transparente apenas em containers de conteúdo */
        .bg-white {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(1px);
        }
        
        /* Garantir texto escuro no conteúdo (não herdar do tema do navegador) */
        main .text-gray-900 { color: #111827 !important; }
        main .text-gray-800 { color: #1f2937 !important; }
        main .text-gray-700 { color: #374151 !important; }
        main .text-gray-600 { color: #4b5563 !important; }
        main .text-gray-500 { color: #6b7280 !important; }
        
        /* Cabeçalho fixo — z-index abaixo dos modais (Tailwind z-50 = 50) para overlays cobrirem o header */
        nav {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 40;
            background-color: #000000 !important;
            border-bottom: 1px solid #333333;
        }
        
        /* Ajustar cores apenas dos elementos de navegação no header (exceto botões amarelos) */
        nav .max-w-7xl > div > a, 
        nav .max-w-7xl > div > button:not(.bg-yellow-500) {
            color: white !important;
        }
        
        /* Forçar cor branca para abas dos sistemas quando não selecionadas */
        .tab-inactive,
        .tab-inactive *,
        .tab-inactive i {
            color: white !important;
        }
        
        /* Forçar cor preta em todos os botões amarelos */
        nav .bg-yellow-500,
        nav .bg-yellow-500 *,
        nav .bg-yellow-500 i,
        nav .bg-yellow-500 span,
        nav .bg-yellow-500 svg {
            color: black !important;
            fill: black !important;
        }
        
        /* Forçar cor preta especificamente no dropdown do usuário */
        nav button.bg-yellow-500,
        nav button.bg-yellow-500 *,
        nav button.bg-yellow-500 i,
        nav button.bg-yellow-500 span,
        nav button.bg-yellow-500 svg,
        nav button.bg-yellow-500 div {
            color: black !important;
            fill: black !important;
        }
        
        /* Garantir que elementos dentro do dropdown também sejam pretos */
        .bg-yellow-500 .fas,
        .bg-yellow-500 .fa-user {
            color: black !important;
        }
        
        /* Estilo para abas ativas/selecionadas - forçar amarelo */
        .tab-active,
        .tab-active *,
        .tab-active i {
            color: #eab308 !important; /* yellow-500 */
        }
        
        /* Forçar borda amarela na aba ativa */
        .tab-active {
            border-bottom-color: #eab308 !important; /* yellow-500 */
        }
        
        /* Garantir que ícones das abas ativas sejam amarelos */
        .tab-active .fas,
        .tab-active .fa-folder {
            color: #eab308 !important; /* yellow-500 */
        }
        
        /* Estilo para abas de navegação ativas no cabeçalho */
        nav .max-w-7xl a.border-primary-400 {
            color: #eab308 !important; /* yellow-500 */
            border-bottom-color: #eab308 !important; /* yellow-500 */
        }
        
        /* Hover para links de navegação */
        nav .max-w-7xl a.border-transparent:hover {
            color: #eab308 !important; /* yellow-500 */
            border-bottom-color: #eab308 !important; /* yellow-500 */
        }
        
        /* Forçar amarelo em todos os links de navegação quando ativos */
        nav .max-w-7xl a[class*="border-primary-400"] {
            color: #eab308 !important; /* yellow-500 */
            border-bottom-color: #eab308 !important; /* yellow-500 */
        }

        /* Estilo do dropdown Gerenciar na navbar */
        nav .max-w-7xl .gerenciar-dropdown-trigger {
            color: #9ca3af !important;
        }
        nav .max-w-7xl .gerenciar-dropdown-trigger:hover,
        nav .max-w-7xl .gerenciar-dropdown-trigger:focus {
            color: #eab308 !important;
            border-bottom-color: #eab308 !important;
        }
        nav .max-w-7xl .gerenciar-dropdown-trigger.border-primary-400,
        nav .max-w-7xl .gerenciar-dropdown-trigger.gerenciar-dropdown-open {
            color: #eab308 !important;
            border-bottom-color: #eab308 !important;
        }
        
        nav .max-w-7xl .text-gray-400 {
            color: #d1d5db !important;
        }
        
        nav .max-w-7xl .text-gray-500 {
            color: #9ca3af !important;
        }
        
        nav .max-w-7xl .hover\:text-gray-500:hover {
            color: #9ca3af !important;
        }
        
        nav .max-w-7xl .hover\:text-gray-600:hover {
            color: #6b7280 !important;
        }
        
        /* Ajustar layout para cabeçalho fixo */
        .min-h-screen {
            padding-top: 64px; /* Altura do nav fixo */
        }
        
        main {
            background: transparent;
            min-height: calc(100vh - 64px);
        }
    </style>
    
    <!-- Script para garantir que o toast funcione -->
    <script>
        // Verificar se há mensagem de sessão para exibir como toast
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast('{{ addslashes(session('success')) }}', 'success', 5000);
                    } else {
                        createFallbackToast('{{ addslashes(session('success')) }}');
                    }
                }, 200);
            });
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast('{{ addslashes(session('error')) }}', 'error', 5000);
                    }
                }, 200);
            });
        @endif
        
        // Função fallback para criar toast manualmente
        function createFallbackToast(message) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'bg-green-500 text-white border-green-600 border-l-4 px-6 py-4 shadow-lg rounded-lg transform transition-all duration-300 ease-in-out translate-x-0 opacity-100 max-w-sm w-full flex items-center space-x-3 cursor-pointer relative overflow-hidden';
            
            toast.innerHTML = `
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="flex-shrink-0">
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="ml-4 text-white hover:text-gray-200 transition-colors duration-200 focus:outline-none">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.transform = 'translateX(100%)';
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }
            }, 5000);
        }
    </script>
    
    <!-- Script para Modal de Logout -->
    <script>
        // Variáveis globais para armazenar dados do logout
        let currentLogoutForm = null;
        let currentLogoutLink = null;
        
        // Função para mostrar modal de confirmação
        function showLogoutConfirmModal(form = null, link = null) {
            console.log('=== DEBUG: showLogoutConfirmModal chamado ===');
            
            currentLogoutForm = form;
            currentLogoutLink = link;
            
            const modal = document.getElementById('logoutConfirmModal');
            
            if (modal) {
                // Aparição com efeito sutil
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                
                // Pequeno delay para permitir a transição
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
                
                console.log('Modal exibido com efeito sutil');
            } else {
                console.error('ERRO: Modal não encontrado!');
            }
        }
        
        // Função para esconder modal de confirmação
        function hideLogoutConfirmModal() {
            console.log('=== DEBUG: hideLogoutConfirmModal chamado ===');
            const modal = document.getElementById('logoutConfirmModal');
            if (modal) {
                // Ocultação com efeito sutil
                modal.classList.remove('show');
                
                // Aguardar a transição terminar antes de esconder
                setTimeout(() => {
                modal.classList.add('hidden');
                    modal.style.display = 'none';
                }, 200);
                
                console.log('Modal escondido com efeito sutil');
            }
            currentLogoutForm = null;
            currentLogoutLink = null;
        }
        
        // Função para confirmar logout
        function confirmLogout() {
            console.log('=== DEBUG: confirmLogout iniciado ===');
            
            hideLogoutConfirmModal();
            
            // Logout direto sem modal de loading
            if (currentLogoutForm) {
                console.log('Fazendo logout via formulário - SUBMIT DIRETO');
                currentLogoutForm.submit();
            } else if (currentLogoutLink) {
                console.log('Fazendo logout via link - NAVEGAÇÃO DIRETA');
                
                // Criar formulário temporário para POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                // Adicionar CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);
                
                // Adicionar ao DOM e submeter
                document.body.appendChild(form);
                form.submit();
            } else {
                console.error('Nenhum formulário ou link encontrado');
                
                // Fallback - criar formulário do zero
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Função para mostrar modal de login
        function showLoginModal() {
            console.log('=== DEBUG: showLoginModal chamado ===');
            
            const modal = document.getElementById('loginModal');
            
            if (modal) {
                // Limpar campos, erros e mensagem de login obrigatório
                document.getElementById('modal_username').value = '';
                document.getElementById('modal_password').value = '';
                document.getElementById('username_error').classList.add('hidden');
                document.getElementById('password_error').classList.add('hidden');
                const requiredError = document.getElementById('loginModalRequiredError');
                if (requiredError) requiredError.classList.add('hidden');
                
                // Aparição com efeito sutil
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                
                // Pequeno delay para permitir a transição
                setTimeout(() => {
                    modal.classList.add('show');
                    // Focar no campo usuário
                    document.getElementById('modal_username').focus();
                }, 10);
                
                console.log('Modal de login exibido com efeito sutil');
            } else {
                console.error('ERRO: Modal de login não encontrado!');
            }
        }
        
        // Função para esconder modal de login
        function hideLoginModal() {
            console.log('=== DEBUG: hideLoginModal chamado ===');
            const modal = document.getElementById('loginModal');
            if (modal) {
                // Ocultação com efeito sutil
                modal.classList.remove('show');
                
                // Aguardar a transição terminar antes de esconder
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                }, 200);
                
                console.log('Modal de login escondido com efeito sutil');
            }
        }
        
        // Função para lidar com cliques no modal de login
        function handleLoginModalClick(event) {
            const modal = document.getElementById('loginModal');
            // Clicou no backdrop (fora do modal)
            if (event.target.id === 'loginModal') {
                // Verifica se o login é obrigatório
                if (modal && modal.dataset.required === 'true') {
                    // Não fecha - executa shake e mostra mensagem
                    const content = document.getElementById('loginModalContent');
                    const errorMsg = document.getElementById('loginModalRequiredError');
                    if (content) {
                        content.classList.remove('modal-shake');
                        void content.offsetWidth; // Force reflow para reiniciar animação
                        content.classList.add('modal-shake');
                        setTimeout(() => content.classList.remove('modal-shake'), 400);
                    }
                    if (errorMsg) {
                        errorMsg.classList.remove('hidden');
                        setTimeout(() => errorMsg.classList.add('hidden'), 4000);
                    }
                    return;
                }
                // Login opcional - permite fechar
                hideLoginModal();
            }
        }
        
        // Interceptar cliques em links/formulários de logout
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DEBUG: Inicializando interceptação de logout ===');
            
            // Se o modal de login foi carregado como obrigatório, focar no campo de usuário
            const loginModal = document.getElementById('loginModal');
            if (loginModal && loginModal.dataset.required === 'true') {
                setTimeout(() => {
                    const usernameField = document.getElementById('modal_username');
                    if (usernameField) {
                        usernameField.focus();
                    }
                }, 100);
            }
            
            // Interceptar formulários de logout
            const logoutForms = document.querySelectorAll('form[action*="logout"]');
            logoutForms.forEach((form) => {
                form.addEventListener('submit', handleFormSubmit);
            });
            
            // Interceptar links de logout
            const logoutLinks = document.querySelectorAll('a[href*="logout"]');
            logoutLinks.forEach((link) => {
                link.addEventListener('click', handleLinkClick);
            });
            
            // Interceptar links de login
            const loginLinks = document.querySelectorAll('a[href*="login"]');
            console.log('Links de login encontrados:', loginLinks.length);
            loginLinks.forEach((link) => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showLoginModal();
                    return false;
                });
            });
            
            // Interceptar submit do formulário de login modal
            const modalLoginForm = document.getElementById('modalLoginForm');
            if (modalLoginForm) {
                modalLoginForm.addEventListener('submit', handleLoginSubmit);
            }
        });
        
        // Função para lidar com submit de formulário
        function handleFormSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            showLogoutConfirmModal(e.target, null);
            return false;
        }
        
        // Função para lidar com clique em link
        function handleLinkClick(e) {
            e.preventDefault();
            e.stopPropagation();
            showLogoutConfirmModal(null, e.target);
            return false;
        }
        
        // Função para lidar com submit do formulário de login
        function handleLoginSubmit(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('=== DEBUG: Login submit interceptado ===');
            
            const form = e.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Limpar erros anteriores
            document.getElementById('username_error').classList.add('hidden');
            document.getElementById('password_error').classList.add('hidden');
            
            // Desabilitar botão de submit
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Entrando...';
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Login bem-sucedido - processar resposta JSON
                    return response.json();
                } else if (response.status === 422) {
                    // Erros de validação
                    return response.json();
                } else {
                    throw new Error('Erro no servidor');
                }
            })
            .then(data => {
                if (data && data.success) {
                    // Login bem-sucedido - recarregar imediatamente
                    console.log('Login bem-sucedido:', data.message);
                    
                    // Recarregar página imediatamente (o toast aparecerá via sessão Laravel)
                    window.location.reload();
                } else if (data && data.errors) {
                    // Mostrar erros de validação
                    if (data.errors.username) {
                        const usernameError = document.getElementById('username_error');
                        usernameError.textContent = data.errors.username[0];
                        usernameError.classList.remove('hidden');
                    }
                    if (data.errors.password) {
                        const passwordError = document.getElementById('password_error');
                        passwordError.textContent = data.errors.password[0];
                        passwordError.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Erro no login:', error);
                // Mostrar erro genérico
                const usernameError = document.getElementById('username_error');
                usernameError.textContent = 'Usuário ou senha incorretos.';
                usernameError.classList.remove('hidden');
            })
            .finally(() => {
                // Reabilitar botão
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
            
            return false;
        }
        
        // Tornar funções globalmente disponíveis
        window.showLogoutConfirmModal = showLogoutConfirmModal;
        window.hideLogoutConfirmModal = hideLogoutConfirmModal;
        window.confirmLogout = confirmLogout;
        window.showLoginModal = showLoginModal;
        window.hideLoginModal = hideLoginModal;
    </script>
</body>
</html> 