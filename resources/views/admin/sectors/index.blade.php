@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Setores" icon="fas fa-building">
        <x-slot name="actions">
            <button onclick="openCreateModal()" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Setor
            </button>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Informativo -->
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-indigo-600 text-xl mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-indigo-800">Como funciona?</h3>
                    <p class="text-indigo-700 text-sm mt-1">
                        Cada setor possui uma <strong>URL secreta única</strong> que permite acesso direto aos links permitidos, sem necessidade de login.
                        Basta criar um setor, selecionar os links que ele pode acessar e compartilhar a URL.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if($sectors->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Setor
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Links
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        URL Secreta
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Último Acesso
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sectors as $sector)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-building text-indigo-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $sector->name }}</div>
                                                    @if($sector->notes)
                                                        <div class="text-xs text-gray-500">{{ Str::limit($sector->notes, 30) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sector->cards->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                                <i class="fas fa-link mr-1"></i>
                                                {{ $sector->cards->count() }} {{ $sector->cards->count() === 1 ? 'link' : 'links' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($sector->secret_url)
                                                <div class="flex items-center space-x-2">
                                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono truncate max-w-[200px]" title="{{ $sector->full_secret_url }}">
                                                        {{ Str::limit($sector->full_secret_url, 35) }}
                                                    </code>
                                                    <button onclick="copyToClipboard('{{ $sector->full_secret_url }}')" 
                                                            class="text-indigo-600 hover:text-indigo-800 p-1" 
                                                            title="Copiar URL">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">Não gerada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($sector->secret_url_enabled && $sector->isSecretUrlValid())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Ativa
                                                </span>
                                            @elseif(!$sector->secret_url_enabled)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Desativada
                                                </span>
                                            @elseif($sector->secret_url_expires_at && $sector->secret_url_expires_at->isPast())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> Expirada
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    <i class="fas fa-minus-circle mr-1"></i> Pendente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($sector->secretUrlAccessLogs->first())
                                                {{ $sector->secretUrlAccessLogs->first()->accessed_at->diffForHumans() }}
                                            @else
                                                <span class="text-gray-400">Nunca</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-1">
                                                <!-- Gerenciar Links -->
                                                <button onclick="openCardsModal({{ $sector->id }}, '{{ $sector->name }}')" 
                                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors duration-200" 
                                                        title="Gerenciar Links">
                                                    <i class="fas fa-link"></i>
                                                </button>
                                                
                                                <!-- URL Secreta -->
                                                <button onclick="openSecretUrlModal({{ $sector->id }}, '{{ $sector->name }}')" 
                                                        class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 p-2 rounded-lg transition-colors duration-200" 
                                                        title="URL Secreta">
                                                    <i class="fas fa-key"></i>
                                                </button>
                                                
                                                <!-- Editar -->
                                                <button onclick="openEditModal({{ $sector->id }})" 
                                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-colors duration-200" 
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <!-- Excluir -->
                                                <button onclick="deleteSector({{ $sector->id }}, '{{ $sector->name }}')" 
                                                        class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition-colors duration-200" 
                                                        title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-building text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Nenhum setor cadastrado</h3>
                        <p class="text-gray-500 mb-4">Crie seu primeiro setor para começar a gerenciar os acessos por URL secreta.</p>
                        <button onclick="openCreateModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Primeiro Setor
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Criar Setor -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="if(event.target.id === 'createModal') closeCreateModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-indigo-600 rounded-t-lg">
                <h3 class="text-lg font-medium text-white">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Criar Novo Setor
                </h3>
                <button onclick="closeCreateModal()" class="text-white hover:text-indigo-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto" id="createModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Setor -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="if(event.target.id === 'editModal') closeEditModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-600 rounded-t-lg">
                <h3 class="text-lg font-medium text-white">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Setor
                </h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto" id="editModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gerenciar Links -->
<div id="cardsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="if(event.target.id === 'cardsModal') closeCardsModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-5xl w-full mx-4" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-blue-600 rounded-t-lg">
                <h3 class="text-lg font-medium text-white" id="cardsModalTitle">
                    <i class="fas fa-link mr-2"></i>
                    Gerenciar Links do Setor
                </h3>
                <button onclick="closeCardsModal()" class="text-white hover:text-blue-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto" id="cardsModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal URL Secreta -->
<div id="secretUrlModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="if(event.target.id === 'secretUrlModal') closeSecretUrlModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-indigo-600 rounded-t-lg">
                <h3 class="text-lg font-medium text-white" id="secretUrlModalTitle">
                    <i class="fas fa-key mr-2"></i>
                    URL Secreta do Setor
                </h3>
                <button onclick="closeSecretUrlModal()" class="text-white hover:text-indigo-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto" id="secretUrlModalContent">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Carregando...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de setores carregada');
});

// === FUNÇÃO HELPER PARA EXECUTAR SCRIPTS ===
function executeScripts(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(oldScript => {
        const newScript = document.createElement('script');
        if (oldScript.src) {
            newScript.src = oldScript.src;
        } else {
            newScript.textContent = oldScript.textContent;
        }
        oldScript.parentNode.removeChild(oldScript);
        document.body.appendChild(newScript);
    });
}

// === FUNÇÕES DE COPIAR ===
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('success', 'URL copiada para a área de transferência!');
    }).catch(function(err) {
        // Fallback
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.opacity = "0";
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('success', 'URL copiada!');
    });
}

// === MODAL CRIAR ===
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    loadCreateForm();
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function loadCreateForm() {
    fetch('{{ route("admin.sectors.create") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('createModalContent');
        container.innerHTML = data.html;
        executeScripts(container);
    })
    .catch(error => {
        console.error('Erro ao carregar formulário:', error);
        document.getElementById('createModalContent').innerHTML = `
            <div class="text-center text-red-600">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Erro ao carregar formulário</p>
            </div>
        `;
    });
}

// === MODAL EDITAR ===
function openEditModal(sectorId) {
    document.getElementById('editModal').classList.remove('hidden');
    loadEditForm(sectorId);
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function loadEditForm(sectorId) {
    fetch(`/admin/sectors/${sectorId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('editModalContent');
        container.innerHTML = data.html;
        executeScripts(container);
    })
    .catch(error => {
        console.error('Erro ao carregar formulário:', error);
        document.getElementById('editModalContent').innerHTML = `
            <div class="text-center text-red-600">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Erro ao carregar formulário</p>
            </div>
        `;
    });
}

// === MODAL GERENCIAR LINKS ===
function openCardsModal(sectorId, sectorName) {
    document.getElementById('cardsModalTitle').innerHTML = `<i class="fas fa-link mr-2"></i>Gerenciar Links - ${sectorName}`;
    document.getElementById('cardsModal').classList.remove('hidden');
    loadCardsForm(sectorId);
}

function closeCardsModal() {
    document.getElementById('cardsModal').classList.add('hidden');
}

function loadCardsForm(sectorId) {
    fetch(`/admin/sectors/${sectorId}/cards`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('cardsModalContent');
        container.innerHTML = data.html;
        executeScripts(container);
    })
    .catch(error => {
        console.error('Erro ao carregar links:', error);
        document.getElementById('cardsModalContent').innerHTML = `
            <div class="text-center text-red-600">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Erro ao carregar links</p>
            </div>
        `;
    });
}

// === MODAL URL SECRETA ===
function openSecretUrlModal(sectorId, sectorName) {
    document.getElementById('secretUrlModalTitle').innerHTML = `<i class="fas fa-key mr-2"></i>URL Secreta - ${sectorName}`;
    document.getElementById('secretUrlModal').classList.remove('hidden');
    loadSecretUrlForm(sectorId);
}

function closeSecretUrlModal() {
    document.getElementById('secretUrlModal').classList.add('hidden');
}

function loadSecretUrlForm(sectorId) {
    fetch(`/admin/sectors/${sectorId}/secret-url`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('secretUrlModalContent');
        container.innerHTML = data.html;
        executeScripts(container);
    })
    .catch(error => {
        console.error('Erro ao carregar URL secreta:', error);
        document.getElementById('secretUrlModalContent').innerHTML = `
            <div class="text-center text-red-600">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Erro ao carregar dados</p>
            </div>
        `;
    });
}

// === EXCLUIR SETOR ===
function deleteSector(sectorId, sectorName) {
    if (!confirm(`Tem certeza que deseja excluir o setor "${sectorName}"?\n\nEsta ação não pode ser desfeita.`)) {
        return;
    }
    
    fetch(`/admin/sectors/${sectorId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            location.reload();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Erro ao excluir setor');
    });
}

// === TOAST ===
function showToast(type, message) {
    if (window.Toast && typeof window.Toast[type] === 'function') {
        window.Toast[type](message);
    } else {
        alert(message);
    }
}
</script>

