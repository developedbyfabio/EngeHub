@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Cards" icon="fas fa-th-large">
        <x-slot name="actions">
            <button type="button" onclick="openCardGroupPermissionsModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                <i class="fas fa-user-shield mr-2"></i>
                Gerenciar Permissões
            </button>
            <button type="button" onclick="openTabsManagerModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                <i class="fas fa-folder-open mr-2"></i>
                Gerenciar Abas
            </button>
            <button onclick="openCategoriesModal()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition ease-in-out duration-150">
                <i class="fas fa-tags mr-2"></i>
                Gerenciar Categorias
            </button>
            <button onclick="openCreateModal()" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Card
            </button>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($tabs->count() > 0)
                        <!-- Tabs Navigation -->
                        <div class="mb-8" x-data="{ activeTab: '{{ $tabs->first()->id }}' }">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8 overflow-x-auto tabs-nav" aria-label="Tabs">
                                    @foreach($tabs as $tab)
                                        <button
                                            @click="activeTab = '{{ $tab->id }}'"
                                            :class="activeTab === '{{ $tab->id }}' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                            class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm transition-all duration-200 rounded-t-lg"
                                            :style="activeTab === '{{ $tab->id }}' ? 'border-color: {{ $tab->color }}; color: {{ $tab->color }}; background-color: {{ $tab->color }}20;' : 'border-color: {{ $tab->color }}; color: {{ $tab->color }};'"
                                        >
                                            <i class="fas fa-folder mr-2"></i>
                                            {{ $tab->name }}
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $tab->cards->count() }}
                                            </span>
                                        </button>
                                    @endforeach
                                </nav>
                            </div>

                            <!-- Tab Content -->
                            @foreach($tabs as $tab)
                                <div x-show="activeTab === '{{ $tab->id }}'" class="mt-6">
                                    @if($tab->cards->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Nome
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Descrição
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Link
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Status
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Arquivo
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Ações
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($tab->cards as $card)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    @if($card->custom_icon_path)
                                                                        <img src="{{ $card->custom_icon_url }}" alt="{{ $card->name }}" class="w-6 h-6 object-contain mr-3">
                                                                    @elseif($card->icon)
                                                                        <i class="{{ $card->icon }} text-lg mr-3" style="color: {{ $tab->color }};"></i>
                                                                    @else
                                                                        <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $tab->color }};"></div>
                                                                    @endif
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        {{ $card->name }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <div class="text-sm text-gray-900">
                                                                    {{ Str::limit($card->description, 50) ?: 'Sem descrição' }}
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <div class="text-sm text-gray-900">
                                                                    <a href="{{ $card->link }}" target="_blank" class="text-blue-600 hover:text-blue-900 truncate block max-w-xs" title="{{ $card->link }}">
                                                                        {{ Str::limit($card->link, 20) }}
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                @if($card->monitor_status)
                                                                    <div class="flex items-center">
                                                                        <div class="w-3 h-3 rounded-full {{ $card->status_class }} mr-2"></div>
                                                                        <span class="text-sm">{{ $card->status_text }}</span>
                                                                        @if($card->response_time)
                                                                            <span class="text-xs text-gray-500 ml-1">({{ $card->response_time }}ms)</span>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="text-gray-400">Não monitorado</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                @if($card->file_path)
                                                                    <a href="{{ Storage::url($card->file_path) }}" target="_blank" class="text-green-600 hover:text-green-900">
                                                                        <i class="fas fa-paperclip"></i> Ver arquivo
                                                                    </a>
                                                                @else
                                                                    <span class="text-gray-400">Sem arquivo</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                <div class="flex space-x-2">
                                                                    <button onclick="openEditModal({{ $card->id }})" class="text-blue-600 hover:text-blue-900">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button onclick="openCardLoginsModal({{ $card->id }}, '{{ addslashes($card->name) }}')" class="text-green-600 hover:text-green-900">
                                                                        <i class="fas fa-key"></i>
                                                                    </button>
                                                                    <button onclick="openDeleteConfirmModal({{ $card->id }})" class="text-red-600 hover:text-red-900">
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
                                            <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum card nesta categoria</h3>
                                            <p class="text-gray-500 mb-4">Esta aba ainda não possui cards cadastrados.</p>
                                            <button onclick="openCreateModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                <i class="fas fa-plus mr-2"></i>
                                                Criar Primeiro Card
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma aba cadastrada</h3>
                            <p class="text-gray-500 mb-4">Primeiro você precisa criar uma aba para poder adicionar cards.</p>
                            <button type="button" onclick="openTabsManagerModal(); setTimeout(function() { openTabCreateModal(); }, 200);" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Primeira Aba
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal">
        <div class="w-96 shadow-lg rounded-md bg-white delete-confirm-content">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full delete-confirm-icon">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-2 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Exclusão</h3>
                    <p class="text-sm text-gray-500 mb-6">Tem certeza que deseja excluir este card?</p>
                </div>
                <div class="flex justify-center space-x-3 px-6 pb-6">
                    <button onclick="closeDeleteConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Container de Notificações Toast -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Modal de Criação -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal p-4">
        <div class="w-full max-w-2xl max-h-[85vh] shadow-lg rounded-md bg-white modal-content delete-confirm-content flex flex-col">
            <div class="flex-shrink-0">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">Novo Card</h3>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body px-6 pb-6 overflow-y-auto flex-1 min-h-0" id="createModalContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal p-4">
        <div class="w-full max-w-2xl max-h-[85vh] shadow-lg rounded-md bg-white modal-content delete-confirm-content flex flex-col">
            <div class="flex-shrink-0">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">Editar Card</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body px-6 pb-6 overflow-y-auto flex-1 min-h-0" id="editModalContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>

    <!-- Modal de Logins do Card -->
    <div id="cardLoginsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal">
        <div class="w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white modal-content delete-confirm-content">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900" id="cardLoginsModalTitle">Gerenciar Logins</h3>
                    <button onclick="closeCardLoginsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="modal-body px-6" id="cardLoginsModalContent">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Carregando logins...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Criação de Login -->
    <div id="createSystemLoginModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[120] flex items-center justify-center delete-confirm-modal">
        <div class="w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/3 xl:w-1/4 shadow-lg rounded-md bg-white modal-content delete-confirm-content">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">Criar Novo Login</h3>
                    <button onclick="closeCreateSystemLoginModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="modal-body px-6" id="createSystemLoginModalContent">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Login -->
    <div id="editSystemLoginModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[120] flex items-center justify-center delete-confirm-modal">
        <div class="w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/3 xl:w-1/4 shadow-lg rounded-md bg-white modal-content delete-confirm-content">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">Editar Login</h3>
                    <button onclick="closeEditSystemLoginModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="modal-body px-6" id="editSystemLoginModalContent">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Categorias -->
    <div id="categoriesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal">
        <div class="w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white modal-content delete-confirm-content">
            <!-- Cabeçalho -->
            <div class="flex justify-between items-center mb-4 px-6 pt-4">
                <h3 class="text-lg font-medium text-gray-900">Gerenciar Categorias</h3>
                <button onclick="closeCategoriesModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Área de criação -->
            <div class="px-6">
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <input type="text" id="newCategoryName" placeholder="Nome da nova categoria" 
                               class="flex-1 border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                        <button onclick="createCategoryInline()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lista de categorias com scroll (máximo 5 visíveis) -->
            <div class="px-6 pb-6 overflow-y-auto" style="max-height: 300px;">
                <div id="categoriesList" class="space-y-4">
                    <!-- Categorias serão carregadas via AJAX -->
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="text-gray-500 mt-2">Carregando categorias...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal matriz cards × grupos (visibilidade no Início) -->
    <div id="cardGroupPermissionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal p-4">
        <div class="w-full max-w-[min(96vw,1400px)] max-h-[92vh] shadow-lg rounded-md bg-white modal-content delete-confirm-content flex flex-col">
            <div class="flex-shrink-0 flex justify-between items-center gap-3 px-6 pt-4 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                    <i class="fas fa-user-shield text-indigo-600"></i>
                    Gerenciar Permissões
                </h3>
                <button type="button" onclick="closeCardGroupPermissionsModal()" class="text-gray-400 hover:text-gray-600 p-1 shrink-0" aria-label="Fechar">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="px-6 py-3 flex flex-wrap items-center gap-3 border-b border-gray-100 bg-gray-50">
                <button type="button" id="cardGroupPermissionsSaveBtn" onclick="saveCardGroupPermissionsMatrix()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i>
                    Salvar alterações
                </button>
                <div class="flex items-center gap-2">
                    <label for="cardGroupPermissionsTabFilter" class="text-xs font-medium text-gray-600 whitespace-nowrap">Filtrar por aba</label>
                    <select id="cardGroupPermissionsTabFilter" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 min-w-[180px]">
                        <option value="">Todas as abas</option>
                    </select>
                </div>
                <span id="cardGroupPermissionsStatus" class="text-sm text-gray-600 ml-auto"></span>
            </div>
            <div class="px-6 py-4 overflow-auto flex-1 min-h-0" id="cardGroupPermissionsScroll">
                <div id="cardGroupPermissionsLoading" class="text-center py-12 hidden">
                    <i class="fas fa-spinner fa-spin text-3xl text-indigo-500"></i>
                    <p class="text-gray-500 mt-2">Carregando cards e grupos…</p>
                </div>
                <div id="cardGroupPermissionsError" class="hidden text-center py-8 text-red-600 text-sm"></div>
                <div id="cardGroupPermissionsTableWrap" class="hidden overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm" id="cardGroupPermissionsTable"></table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Gerenciar Abas -->
    <div id="tabsManagerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[110] flex items-center justify-center delete-confirm-modal p-4">
        <div class="w-full max-w-4xl max-h-[90vh] shadow-lg rounded-md bg-white modal-content delete-confirm-content flex flex-col">
            <div class="flex-shrink-0 flex justify-between items-center gap-3 px-6 pt-4 pb-2 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                    <i class="fas fa-folder-open text-blue-600"></i>
                    Gerenciar Abas
                </h3>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="openTabCreateModal()" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Nova Aba
                    </button>
                    <button type="button" onclick="closeTabsManagerModal()" class="text-gray-400 hover:text-gray-600 p-1" aria-label="Fechar">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4 overflow-y-auto flex-1 min-h-0">
                @if($tabs->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordem</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cards</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tabsManagerTableBody" class="bg-white divide-y divide-gray-200">
                                @foreach($tabs as $tab)
                                    <tr data-tab-row-id="{{ $tab->id }}">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 rounded-full mr-3 flex-shrink-0" style="background-color: {{ $tab->color }};"></div>
                                                <span class="text-sm font-medium text-gray-900">{{ $tab->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm text-gray-900">{{ Str::limit($tab->description, 50) ?: 'Sem descrição' }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded border border-gray-300 mr-2" style="background-color: {{ $tab->color }};"></div>
                                                <span class="text-sm text-gray-900">{{ $tab->color }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $tab->order }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $tab->cards_count }} {{ $tab->cards_count === 1 ? 'card' : 'cards' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button type="button" onclick="openTabEditModal({{ $tab->id }})" class="text-blue-600 hover:text-blue-900" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" onclick="openTabDeleteConfirmModal({{ $tab->id }})" class="text-red-600 hover:text-red-900" title="Excluir">
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
                    <div class="text-center py-10 px-4">
                        <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Nenhuma aba cadastrada</h4>
                        <p class="text-gray-500 mb-6">Crie uma aba para organizar os cards na página inicial.</p>
                        <button type="button" onclick="openTabCreateModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Criar Primeira Aba
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Nova Aba (formulário) -->
    <div id="tabCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[120] flex items-center justify-center delete-confirm-modal p-4">
        <div class="w-full max-w-lg max-h-[90vh] shadow-lg rounded-md bg-white modal-content delete-confirm-content flex flex-col">
            <div class="flex-shrink-0 flex justify-between items-center px-6 pt-4 pb-2">
                <h3 class="text-lg font-medium text-gray-900">Nova Aba</h3>
                <button type="button" onclick="closeTabCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="modal-body px-6 pb-6 overflow-y-auto flex-1 min-h-0" id="tabCreateModalContent"></div>
        </div>
    </div>

    <!-- Modal Editar Aba -->
    <div id="tabEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[120] flex items-center justify-center delete-confirm-modal p-4">
        <div class="w-full max-w-lg max-h-[90vh] shadow-lg rounded-md bg-white modal-content delete-confirm-content flex flex-col">
            <div class="flex-shrink-0 flex justify-between items-center px-6 pt-4 pb-2">
                <h3 class="text-lg font-medium text-gray-900">Editar Aba</h3>
                <button type="button" onclick="closeTabEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="modal-body px-6 pb-6 overflow-y-auto flex-1 min-h-0" id="tabEditModalContent"></div>
        </div>
    </div>

    <!-- Modal confirmar exclusão de aba -->
    <div id="tabDeleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[120] flex items-center justify-center delete-confirm-modal">
        <div class="w-96 shadow-lg rounded-md bg-white delete-confirm-content">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full delete-confirm-icon">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-2 text-center px-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Exclusão</h3>
                    <p class="text-sm text-gray-500 mb-6">Tem certeza que deseja excluir esta aba? Todos os cards desta aba serão excluídos permanentemente.</p>
                </div>
                <div class="flex justify-center space-x-3 px-6 pb-6">
                    <button type="button" onclick="closeTabDeleteConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="button" id="confirmTabDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão de Categoria -->
    <div id="deleteCategoryConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[120] flex items-center justify-center delete-confirm-modal">
        <div class="w-96 shadow-lg rounded-md bg-white delete-confirm-content">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full delete-confirm-icon">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-2 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmar Exclusão</h3>
                    <p class="text-sm text-gray-500 mb-6">Tem certeza que deseja excluir esta categoria?</p>
                </div>
                <div class="flex justify-center space-x-3 px-6 pb-6">
                    <button onclick="closeDeleteCategoryConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button id="confirmDeleteCategoryBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Efeito hover para abas inativas */
        .tabs-nav button:hover:not([style*="background-color"]) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        /* Transição suave para mudança de aba */
        .tabs-nav button {
            position: relative;
            overflow: hidden;
        }
        
        .tabs-nav button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: currentColor;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .tabs-nav button:hover::before {
            opacity: 0.1;
        }

        /* Estilos para modais */
        .modal-open {
            overflow: hidden;
        }

        /* Matriz Gerenciar Permissões (cards × grupos) */
        #cardGroupPermissionsTable tbody tr.cg-matrix-row {
            transition: background-color 0.12s ease;
        }
        #cardGroupPermissionsTable tbody tr.cg-matrix-row:hover td {
            background-color: rgb(238 242 255);
        }
        #cardGroupPermissionsTable tbody tr.cg-matrix-row:hover td.cg-matrix-sticky-cell {
            background-color: rgb(224 231 255);
        }
        
        .modal-content {
            max-height: 85vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin: 0;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 0 1.5rem 1.5rem 1.5rem;
            max-height: calc(85vh - 100px); /* Altura máxima menos header e padding */
            min-height: 0; /* Permite que o flex funcione corretamente */
        }
        
        /* Estilos específicos para os modais de criação e edição de cards */
        #createModal .modal-body,
        #editModal .modal-body {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        
        #createModal .modal-body::-webkit-scrollbar,
        #editModal .modal-body::-webkit-scrollbar {
            width: 6px;
        }
        
        #createModal .modal-body::-webkit-scrollbar-track,
        #editModal .modal-body::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 3px;
        }
        
        #createModal .modal-body::-webkit-scrollbar-thumb,
        #editModal .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }
        
        #createModal .modal-body::-webkit-scrollbar-thumb:hover,
        #editModal .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        .modal-footer {
            flex-shrink: 0;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background-color: #f9fafb;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
        
        /* Scrollbar personalizada para o modal */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        
        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Sistema de Notificações Toast */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 99999;
            pointer-events: none;
            max-height: calc(100vh - 2rem);
            overflow: hidden;
        }
        
        .toast {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 1rem 1.5rem;
            margin-bottom: 0.75rem;
            min-width: 300px;
            max-width: 400px;
            border-left: 4px solid;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 99999;
        }
        
        .toast.show {
            transform: translateX(0) !important;
            opacity: 1 !important;
        }
        
        .toast.hide {
            transform: translateX(100%) !important;
            opacity: 0 !important;
        }
        
        .toast.success {
            border-left-color: #10b981;
            color: #065f46;
            background-color: #f0fdf4;
        }
        
        .toast.success .toast-icon {
            color: #10b981;
        }
        
        .toast.error {
            border-left-color: #ef4444;
            color: #991b1b;
            background-color: #fef2f2;
        }
        
        .toast.error .toast-icon {
            color: #ef4444;
        }
        
        .toast.info {
            border-left-color: #3b82f6;
            color: #1e40af;
            background-color: #eff6ff;
        }
        
        .toast.info .toast-icon {
            color: #3b82f6;
        }
        
        .toast-icon {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            font-size: 1.25rem;
        }
        
        .toast-content {
            flex: 1;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            font-weight: bold;
        }
        
        .toast-close:hover {
            color: #374151;
            background-color: #f3f4f6;
        }
        
        /* Responsividade para telas pequenas */
        @media (max-width: 640px) {
            .toast-container {
                left: 0.5rem;
                right: 0.5rem;
            }
            
            .toast {
                min-width: auto;
                width: 100%;
            }
        }
        
        /* Modal de Confirmação de Exclusão */
        .delete-confirm-modal {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        .delete-confirm-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes modalSlideIn {
            from {
                transform: scale(0.9) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }
        
        .delete-confirm-icon {
            animation: iconBounce 0.6s ease-out;
        }
        
        @keyframes iconBounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        /* Animação para remoção de linha da tabela */
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(-100%);
            }
        }
    </style>

    <script>
        // Funções para gerenciar modais
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            loadCreateForm();
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function openEditModal(cardId) {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            loadEditForm(cardId);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function openCardLoginsModal(cardId, cardName) {
            document.getElementById('cardLoginsModalTitle').textContent = `Gerenciar Logins - ${cardName}`;
            document.getElementById('cardLoginsModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            loadCardLogins(cardId);
        }

        function closeCardLoginsModal() {
            document.getElementById('cardLoginsModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        // Variável global para controlar o estado do modal
        let categoriesModalOpen = false;

        function openCategoriesModal() {
            document.getElementById('categoriesModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            categoriesModalOpen = true;
            loadCategoriesList();
        }

        function closeCategoriesModal() {
            document.getElementById('categoriesModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
            categoriesModalOpen = false;
        }

        // Funções para gerenciar modal de confirmação de exclusão
        function openDeleteConfirmModal(cardId) {
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('confirmDeleteBtn').setAttribute('onclick', `confirmDeleteCard(${cardId})`);
        }

        function closeDeleteConfirmModal() {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }

        function confirmDeleteCard(cardId) {
            // Fechar modal de confirmação
            closeDeleteConfirmModal();
            
            // Mostrar indicador de carregamento
            const deleteBtn = document.querySelector(`button[onclick="openDeleteConfirmModal(${cardId})"]`);
            const originalContent = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteBtn.disabled = true;
            
            // Fazer requisição AJAX para excluir o card
            fetch(`/admin/cards/${cardId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    // Remover a linha da tabela sem recarregar a página
                    const row = deleteBtn.closest('tr');
                    if (row) {
                        row.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            row.remove();
                            // Atualizar contadores das abas
                            updateTabCounters();
                        }, 300);
                    }
                } else {
                    showErrorMessage('Erro ao excluir card');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showErrorMessage('Erro ao excluir card');
            })
            .finally(() => {
                // Restaurar botão
                deleteBtn.innerHTML = originalContent;
                deleteBtn.disabled = false;
            });
        }
        
        // Atualizar contadores das abas
        function updateTabCounters() {
            const tabs = document.querySelectorAll('.tabs-nav button');
            tabs.forEach(tab => {
                const tabId = tab.getAttribute('@click').match(/'([^']+)'/)[1];
                const cardCount = document.querySelectorAll(`[x-show="activeTab === '${tabId}'"] tr`).length - 1; // -1 para o cabeçalho
                const countSpan = tab.querySelector('span');
                if (countSpan) {
                    countSpan.textContent = Math.max(0, cardCount);
                }
            });
        }

        // Carregar formulário de criação
        function loadCreateForm() {
            fetch('{{ route("admin.cards.create") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('createModalContent').innerHTML = data.html;
                setupCreateForm();
                setupFileRemovalHandlers();
            })
            .catch(error => {
                console.error('Erro ao carregar formulário:', error);
            });
        }

        // Carregar formulário de edição
        function loadEditForm(cardId) {
            fetch(`/admin/cards/${cardId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('editModalContent').innerHTML = data.html;
                setupEditForm();
                setupFileRemovalHandlers();
            })
            .catch(error => {
                console.error('Erro ao carregar formulário:', error);
            });
        }

        // Carregar logins do card
        function loadCardLogins(cardId) {
            console.log('=== DEBUG: Carregando logins para card ===');
            console.log('Card ID:', cardId);
            
            fetch(`/admin/cards/${cardId}/logins`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Logins carregados:', data);
                document.getElementById('cardLoginsModalContent').innerHTML = data.html;
                // Armazenar o cardId no modal para uso posterior
                document.getElementById('cardLoginsModal').dataset.cardId = cardId;
                
                // Garantir que o JavaScript seja executado após o carregamento
                setTimeout(() => {
                    console.log('=== DEBUG: Executando scripts após carregamento ===');
                    // Re-executar scripts se necessário
                    const scripts = document.getElementById('cardLoginsModalContent').querySelectorAll('script');
                    console.log('Scripts encontrados:', scripts.length);
                    scripts.forEach((script, index) => {
                        console.log(`Executando script ${index}:`, script.textContent);
                        if (script.textContent) {
                            try {
                                eval(script.textContent);
                            } catch (error) {
                                console.error(`Erro ao executar script ${index}:`, error);
                            }
                        }
                    });
                    
                    // Verificar se a função togglePassword está disponível
                    console.log('togglePassword disponível:', typeof window.togglePassword);
                    
                    // Adicionar event listeners diretamente aos botões
                    const eyeButtons = document.querySelectorAll('button[onclick*="togglePassword"]');
                    console.log('Botões de olho encontrados:', eyeButtons.length);
                    eyeButtons.forEach((button, index) => {
                        console.log(`Botão ${index}:`, button.outerHTML);
                        // Remover onclick e adicionar event listener
                        const loginId = button.getAttribute('onclick').match(/togglePassword\((\d+)\)/)[1];
                        button.removeAttribute('onclick');
                        button.addEventListener('click', function() {
                            console.log('Clique no botão detectado para login ID:', loginId);
                            if (typeof window.togglePassword === 'function') {
                                window.togglePassword(loginId);
                            } else {
                                console.error('Função togglePassword não está disponível');
                                alert('Erro: Função não disponível');
                            }
                        });
                    });
                }, 100);
            })
            .catch(error => {
                console.error('Erro ao carregar logins:', error);
                document.getElementById('cardLoginsModalContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                        <p class="text-red-500 mt-2">Erro ao carregar os logins. Tente Novamente</p>
                    </div>
                `;
            });
        }

        // Funções para gerenciar logins dos sistemas
        function openCreateSystemLoginModal(cardId) {
            console.log('=== DEBUG: Abrindo modal de criação ===');
            console.log('Card ID recebido:', cardId);
            console.log('Tipo do Card ID:', typeof cardId);
            
            // Verificar se o cardId é válido
            if (!cardId || cardId === 'undefined' || cardId === 'null') {
                console.error('Card ID inválido:', cardId);
                alert('Erro: ID do card inválido');
                return;
            }
            
            // Carregar modal de criação
            const url = `/admin/system-logins/create?card_id=${cardId}`;
            console.log('URL da requisição:', url);
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.text();
            })
            .then(html => {
                console.log('HTML recebido (primeiros 200 chars):', html.substring(0, 200));
                document.getElementById('createSystemLoginModalContent').innerHTML = html;
                document.getElementById('createSystemLoginModal').classList.remove('hidden');
                document.body.classList.add('modal-open');
                console.log('Modal aberto com sucesso');
            })
            .catch(error => {
                console.error('Erro ao carregar modal:', error);
                alert('Erro ao carregar modal de criação: ' + error.message);
            });
        }

        function closeCreateSystemLoginModal() {
            document.getElementById('createSystemLoginModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function openEditSystemLoginModal(systemLoginId) {
            console.log('Abrindo modal de edição para login:', systemLoginId);
            // Carregar modal de edição
            fetch(`/admin/system-logins/${systemLoginId}/edit`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const login = data.data;
                    
                    // Preencher formulário
                    document.getElementById('edit_title').value = login.title;
                    document.getElementById('edit_username').value = login.username;
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_notes').value = login.notes || '';
                    document.getElementById('edit_is_active').checked = login.is_active;
                    
                    // Definir ID do login no formulário
                    document.getElementById('editSystemLoginForm').dataset.loginId = login.id;
                    
                    // Abrir modal
                    document.getElementById('editSystemLoginModal').classList.remove('hidden');
                    document.body.classList.add('modal-open');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar dados:', error);
                alert('Erro ao carregar dados do login');
            });
        }

        function closeEditSystemLoginModal() {
            document.getElementById('editSystemLoginModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function openDeleteSystemLoginConfirmModal(systemLoginId, loginTitle, cardId) {
            if (confirm(`Tem certeza que deseja excluir o login "${loginTitle}"?`)) {
                fetch(`/admin/system-logins/${systemLoginId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Recarregar modal
                        loadCardLogins(cardId);
                        showSuccessMessage(data.message);
                    } else {
                        showErrorMessage('Erro ao excluir login');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showErrorMessage('Erro ao excluir login');
                });
            }
        }

        function togglePassword(loginId) {
            console.log("=== DEBUG: Toggle password chamado ===");
            console.log("Login ID:", loginId);
            
            // Verificar se o CSRF token está disponível
            const csrfToken = document.querySelector("meta[name=\"csrf-token\"]");
            if (!csrfToken) {
                console.error("CSRF token não encontrado");
                alert("Erro: CSRF token não encontrado");
                return;
            }
            
            fetch(`/admin/system-logins/${loginId}/toggle-password`, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken.getAttribute("content"),
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
            .then(response => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Response data:", data);
                if (data.success) {
                    const passwordElement = document.getElementById(`password-${loginId}`);
                    if (passwordElement) {
                        passwordElement.textContent = data.password;
                        
                        // Adicionar classe para indicar que está visível
                        passwordElement.classList.add("text-red-600", "font-mono");
                        
                        // Reverter após 3 segundos
                        setTimeout(() => {
                            passwordElement.textContent = "••••••••";
                            passwordElement.classList.remove("text-red-600", "font-mono");
                        }, 3000);
                    } else {
                        console.error("Elemento password não encontrado:", `password-${loginId}`);
                        alert("Erro: Elemento de senha não encontrado");
                    }
                } else {
                    console.error("Erro na resposta:", data);
                    alert(data.message || "Erro ao mostrar senha");
                }
            })
            .catch(error => {
                console.error("Erro ao mostrar senha:", error);
                alert("Erro ao mostrar senha. Tente novamente.");
            });
        }
        function togglePasswordField(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-eye-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Função para submeter o formulário de criação via AJAX
        function submitCreateLoginForm() {
            console.log('=== SUBMIT CREATE LOGIN ===');
            
            const form = document.getElementById('createSystemLoginForm');
            const formData = new FormData(form);
            
            // Log dos dados
            console.log('Dados do formulário:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            // Verificar CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('Erro: CSRF token não encontrado');
                return;
            }
            
            // Fazer a requisição AJAX
            fetch('/admin/system-logins', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                console.log('Status da resposta:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Dados da resposta:', data);
                
                if (data.success) {
                    // Sucesso - fechar modal e recarregar
                    alert('Login criado com sucesso!');
                    closeCreateSystemLoginModal();
                    
                    // Recarregar a lista de logins
                    const cardId = formData.get('card_id');
                    if (cardId && typeof loadCardLogins === 'function') {
                        setTimeout(() => {
                            loadCardLogins(cardId);
                        }, 500);
                    }
                } else {
                    // Erro
                    alert('Erro: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Erro ao criar login: ' + error.message);
            });
        }

        // Função para submeter o formulário de edição via AJAX
        function submitEditLoginForm() {
            console.log('=== SUBMIT EDIT LOGIN ===');
            
            const form = document.getElementById('editSystemLoginForm');
            const formData = new FormData(form);
            const loginId = formData.get('login_id');
            
            // Log dos dados
            console.log('Dados do formulário:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            // Verificar CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('Erro: CSRF token não encontrado');
                return;
            }
            
            // Fazer a requisição AJAX
            fetch(`/admin/system-logins/${loginId}`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                console.log('Status da resposta:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Dados da resposta:', data);
                
                if (data.success) {
                    // Sucesso - fechar modal e recarregar
                    alert('Login atualizado com sucesso!');
                    closeEditSystemLoginModal();
                    
                    // Recarregar a lista de logins
                    const cardLoginsModal = document.querySelector('#cardLoginsModal');
                    if (cardLoginsModal && cardLoginsModal.dataset.cardId) {
                        const cardId = cardLoginsModal.dataset.cardId;
                        if (typeof loadCardLogins === 'function') {
                            setTimeout(() => {
                                loadCardLogins(cardId);
                            }, 500);
                        }
                    }
                } else {
                    // Erro
                    alert('Erro: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Erro ao atualizar login: ' + error.message);
            });
        }

        // Carregar lista de categorias
        function loadCategoriesList() {
            fetch('{{ route("admin.categories.index") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                renderCategoriesList(data.categories);
            })
            .catch(error => {
                console.error('Erro ao carregar lista de categorias:', error);
                document.getElementById('categoriesList').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                        <p class="text-red-500 mt-2">Erro ao carregar categorias</p>
                    </div>
                `;
            });
        }

        // Renderizar lista de categorias
        function renderCategoriesList(categories) {
            const container = document.getElementById('categoriesList');
            
            if (categories.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-tags text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma categoria cadastrada</h3>
                        <p class="text-gray-500 mb-4">Comece criando sua primeira categoria para organizar os cards.</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="space-y-3">
            `;

            categories.forEach(category => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <div class="category-name-container" data-category-id="${category.id}">
                                <span class="text-sm font-medium text-gray-900 category-name-display">${category.name}</span>
                                <input type="text" class="hidden text-sm font-medium text-gray-900 bg-transparent border-b border-blue-500 focus:outline-none focus:border-blue-700 category-name-input" value="${category.name}">
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="editCategoryInline(${category.id})" class="text-blue-600 hover:text-blue-900 p-1 edit-category-btn" data-category-id="${category.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="saveCategoryInline(${category.id})" class="hidden text-green-600 hover:text-green-900 p-1 save-category-btn" data-category-id="${category.id}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="cancelCategoryEdit(${category.id})" class="hidden text-gray-600 hover:text-gray-900 p-1 cancel-category-btn" data-category-id="${category.id}">
                                <i class="fas fa-times"></i>
                            </button>
                            <button onclick="openDeleteCategoryConfirmModal(${category.id})" class="text-red-600 hover:text-red-900 p-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
            container.innerHTML = html;
        }

        // Funções para gerenciar modal de confirmação de exclusão de categoria
        function openDeleteCategoryConfirmModal(categoryId) {
            document.getElementById('deleteCategoryConfirmModal').classList.remove('hidden');
            document.getElementById('confirmDeleteCategoryBtn').setAttribute('onclick', `confirmDeleteCategory(${categoryId})`);
        }

        function closeDeleteCategoryConfirmModal() {
            document.getElementById('deleteCategoryConfirmModal').classList.add('hidden');
        }

        function confirmDeleteCategory(categoryId) {
            console.log('=== DEBUG: confirmDeleteCategory chamado ===');
            console.log('Category ID:', categoryId);
            
            // Fechar modal de confirmação
            closeDeleteCategoryConfirmModal();
            
            // Mostrar indicador de carregamento
            const deleteBtn = document.querySelector(`button[onclick="openDeleteCategoryConfirmModal(${categoryId})"]`);
            console.log('Delete button encontrado:', deleteBtn);
            
            if (!deleteBtn) {
                console.error('Botão de delete não encontrado');
                return;
            }
            
            const originalContent = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteBtn.disabled = true;
            
            // Fazer requisição AJAX para excluir a categoria
            fetch(`/admin/categories/${categoryId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
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
                    showSuccessMessage(data.message);
                    
                    // Garantir que o modal permaneça aberto
                    if (categoriesModalOpen) {
                        console.log('Modal está aberto, removendo categoria da lista');
                        // Remover apenas o elemento da categoria excluída
                        removeCategoryFromList(categoryId);
                    } else {
                        console.log('Modal não está aberto');
                    }
                } else {
                    console.error('Erro na resposta:', data);
                    showErrorMessage(data.message || 'Erro ao excluir categoria');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                showErrorMessage('Erro ao excluir categoria');
            })
            .finally(() => {
                // Restaurar botão
                if (deleteBtn) {
                    deleteBtn.innerHTML = originalContent;
                    deleteBtn.disabled = false;
                }
            });
        }

        // Função para remover categoria da lista sem recarregar
        function removeCategoryFromList(categoryId) {
            console.log('=== DEBUG: removeCategoryFromList chamado ===');
            console.log('Category ID:', categoryId);
            
            // Buscar o elemento da categoria de forma mais específica
            const categoryContainer = document.querySelector(`.category-name-container[data-category-id="${categoryId}"]`);
            console.log('Category container encontrado:', categoryContainer);
            
            if (categoryContainer) {
                // Buscar o elemento pai completo (div com classe flex)
                const categoryElement = categoryContainer.closest('.flex.items-center.justify-between');
                console.log('Category element encontrado:', categoryElement);
                
                if (categoryElement) {
                    // Animação de fade out
                    categoryElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                    categoryElement.style.opacity = '0';
                    categoryElement.style.transform = 'translateX(-10px)';
                    
                    setTimeout(() => {
                        categoryElement.remove();
                        console.log('Categoria removida do DOM');
                        
                        // Verificar se não há mais categorias
                        const container = document.getElementById('categoriesList');
                        const remainingCategories = container.querySelectorAll('.category-name-container');
                        console.log('Categorias restantes:', remainingCategories.length);
                        
                        if (remainingCategories.length === 0) {
                            container.innerHTML = `
                                <div class="text-center py-8">
                                    <i class="fas fa-tags text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma categoria cadastrada</h3>
                                    <p class="text-gray-500 mb-4">Comece criando sua primeira categoria para organizar os cards.</p>
                                </div>
                            `;
                        }
                    }, 300);
                } else {
                    console.error('Elemento pai da categoria não encontrado');
                }
            } else {
                console.error('Container da categoria não encontrado para ID:', categoryId);
                // Fallback: recarregar a lista completa
                loadCategories();
            }
        }

        // Função para criar categoria inline
        function createCategoryInline() {
            const categoryName = document.getElementById('newCategoryName').value.trim();
            if (!categoryName) {
                showErrorMessage('O nome da categoria não pode ser vazio.');
                return;
            }

            const formData = new FormData();
            formData.append('name', categoryName);
            formData.append('description', ''); // Descrição padrão
            formData.append('color', '#4f46e5'); // Cor padrão
            formData.append('order', 0); // Ordem padrão

            fetch('{{ route("admin.categories.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    document.getElementById('newCategoryName').value = ''; // Limpar campo
                    
                    // Garantir que o modal permaneça aberto
                    if (categoriesModalOpen) {
                        // Adicionar nova categoria à lista sem recarregar tudo
                        addCategoryToList(data.category || { id: Date.now(), name: categoryName, cards_count: 0 });
                    }
                } else {
                    showErrorMessage('Erro ao criar categoria');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showErrorMessage('Erro ao criar categoria');
            });
        }

        // Função para adicionar categoria à lista sem recarregar
        function addCategoryToList(category) {
            const container = document.getElementById('categoriesList');
            
            // Se não há categorias, limpar mensagem de "nenhuma categoria"
            if (container.querySelector('.text-center')) {
                container.innerHTML = '';
            }
            
            const newCategoryHtml = `
                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <div class="category-name-container" data-category-id="${category.id}">
                            <span class="text-sm font-medium text-gray-900 category-name-display">${category.name}</span>
                            <input type="text" class="hidden text-sm font-medium text-gray-900 bg-transparent border-b border-blue-500 focus:outline-none focus:border-blue-700 category-name-input" value="${category.name}">
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="editCategoryInline(${category.id})" class="text-blue-600 hover:text-blue-900 p-1 edit-category-btn" data-category-id="${category.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="saveCategoryInline(${category.id})" class="hidden text-green-600 hover:text-green-900 p-1 save-category-btn" data-category-id="${category.id}">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="cancelCategoryEdit(${category.id})" class="hidden text-gray-600 hover:text-gray-900 p-1 cancel-category-btn" data-category-id="${category.id}">
                            <i class="fas fa-times"></i>
                        </button>
                        <button onclick="openDeleteCategoryConfirmModal(${category.id})" class="text-red-600 hover:text-red-900 p-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', newCategoryHtml);
        }

        // Função para editar categoria inline
        function editCategoryInline(categoryId) {
            const categoryNameContainer = document.querySelector(`.category-name-container[data-category-id="${categoryId}"]`);
            if (!categoryNameContainer) return;

            const displaySpan = categoryNameContainer.querySelector('.category-name-display');
            const inputField = categoryNameContainer.querySelector('.category-name-input');

            if (displaySpan && inputField) {
                displaySpan.classList.add('hidden');
                inputField.classList.remove('hidden');
                inputField.focus();
                inputField.addEventListener('blur', function() {
                    const newName = this.value.trim();
                    if (newName && newName !== displaySpan.textContent) {
                        saveCategoryInline(categoryId);
                    } else {
                        cancelCategoryEdit(categoryId);
                    }
                });
            }
        }

        // Função para salvar categoria inline
        function saveCategoryInline(categoryId) {
            const categoryNameContainer = document.querySelector(`.category-name-container[data-category-id="${categoryId}"]`);
            if (!categoryNameContainer) return;

            const displaySpan = categoryNameContainer.querySelector('.category-name-display');
            const inputField = categoryNameContainer.querySelector('.category-name-input');

            if (displaySpan && inputField) {
                const newName = inputField.value.trim();
                if (newName && newName !== displaySpan.textContent) {
                    const formData = new FormData();
                    formData.append('name', newName);
                    formData.append('description', ''); // Descrição padrão
                    formData.append('color', '#4f46e5'); // Cor padrão
                    formData.append('order', 0); // Ordem padrão
                    formData.append('_method', 'PUT');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    fetch(`/admin/categories/${categoryId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessMessage(data.message);
                            
                            // Garantir que o modal permaneça aberto
                            if (categoriesModalOpen) {
                                // Atualizar apenas o nome exibido
                                displaySpan.textContent = newName;
                                inputField.classList.add('hidden');
                                displaySpan.classList.remove('hidden');
                            }
                        } else {
                            showErrorMessage('Erro ao salvar categoria');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        showErrorMessage('Erro ao salvar categoria');
                    });
                } else {
                    cancelCategoryEdit(categoryId);
                }
            }
        }

        // Função para cancelar edição de categoria inline
        function cancelCategoryEdit(categoryId) {
            const categoryNameContainer = document.querySelector(`.category-name-container[data-category-id="${categoryId}"]`);
            if (!categoryNameContainer) return;

            const displaySpan = categoryNameContainer.querySelector('.category-name-display');
            const inputField = categoryNameContainer.querySelector('.category-name-input');

            if (displaySpan && inputField) {
                inputField.classList.add('hidden');
                displaySpan.classList.remove('hidden');
            }
        }

        // Configurar formulário de criação
        function setupCreateForm() {
            const form = document.querySelector('#createModalContent form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitCreateForm(this);
                });
            }
        }

        // Configurar formulário de edição
        function setupEditForm() {
            const form = document.querySelector('#editModalContent form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitEditForm(this);
                });

                // Configurar JavaScript para remoção de arquivos
                setupFileRemovalHandlers();
            }
        }

        // Submeter formulário de criação
        function submitCreateForm(form) {
            const formData = new FormData(form);
            
            fetch('{{ route("admin.cards.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeCreateModal();
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showErrorMessage('Erro ao criar card');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showErrorMessage('Erro ao criar card');
            });
        }

        // Submeter formulário de edição
        function submitEditForm(form) {
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            
            const cardId = form.action.split('/').pop();
            
            fetch(`/admin/cards/${cardId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeEditModal();
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showErrorMessage('Erro ao atualizar card');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showErrorMessage('Erro ao atualizar card');
            });
        }

        // Configurar handlers de remoção de arquivos
        function setupFileRemovalHandlers() {
            // Handlers para remoção de arquivos já estão no template edit.blade.php
            // Esta função é chamada após carregar os formulários para garantir que funcionem
        }

        // Mostrar mensagem de sucesso
        function showSuccessMessage(message) {
            const toastContainer = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            const toastId = 'toast-' + Date.now();
            
            toast.id = toastId;
            toast.className = 'toast show success';
            toast.innerHTML = `
                <i class="fas fa-check-circle toast-icon"></i>
                <div class="toast-content">${message}</div>
                <button class="toast-close" onclick="removeToast('${toastId}')">&times;</button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto-hide após 4 segundos
            setTimeout(() => {
                removeToast(toastId);
            }, 4000);
            
            // Modal permanece aberto - sem recarregar a página
        }

        // Mostrar mensagem de erro
        function showErrorMessage(message) {
            const toastContainer = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            const toastId = 'toast-' + Date.now();
            
            toast.id = toastId;
            toast.className = 'toast show error';
            toast.innerHTML = `
                <i class="fas fa-times-circle toast-icon"></i>
                <div class="toast-content">${message}</div>
                <button class="toast-close" onclick="removeToast('${toastId}')">&times;</button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto-hide após 6 segundos (erros ficam mais tempo)
            setTimeout(() => {
                removeToast(toastId);
            }, 6000);
        }

        // Remover notificação toast
        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('show');
                toast.classList.add('hide');
                
                // Remover do DOM após a animação
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }

        // Limpar todas as notificações
        function clearAllToasts() {
            const toastContainer = document.getElementById('toastContainer');
            const toasts = toastContainer.querySelectorAll('.toast');
            
            toasts.forEach(toast => {
                toast.classList.remove('show');
                toast.classList.add('hide');
            });
            
            setTimeout(() => {
                toastContainer.innerHTML = '';
            }, 300);
        }

        // Event listener para prevenir fechamento acidental do modal
        document.addEventListener('DOMContentLoaded', function() {
            const categoriesModal = document.getElementById('categoriesModal');
            if (categoriesModal) {
                // Prevenir fechamento ao clicar fora do modal
                categoriesModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        // Não fechar ao clicar fora - modal deve permanecer aberto
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
                
                // Prevenir fechamento com ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && categoriesModalOpen) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            }
        });

        /* ——— Matriz cards × grupos (visibilidade Início) ——— */
        const cardGroupPermissionsDataUrl = @json(route('admin.cards.group-permissions-matrix.data'));
        const cardGroupPermissionsSaveUrl = @json(route('admin.cards.group-permissions-matrix.sync'));
        let __cgMatrixCardIds = [];

        function openCardGroupPermissionsModal() {
            document.getElementById('cardGroupPermissionsModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            loadCardGroupPermissionsMatrix();
        }

        function closeCardGroupPermissionsModal() {
            document.getElementById('cardGroupPermissionsModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function escapeHtmlMatrix(s) {
            if (s === null || s === undefined) return '';
            const d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }

        function escapeAttrMatrix(s) {
            if (s === null || s === undefined) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;');
        }

        function safeTabColorForMatrix(hex) {
            const s = String(hex == null ? '' : hex).trim();
            return /^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(s) ? s : '#6366f1';
        }

        function buildCardIconHtmlForMatrix(c) {
            const color = safeTabColorForMatrix(c.tab_color);
            if (c.custom_icon_url) {
                const u = escapeHtmlMatrix(c.custom_icon_url);
                return `<img src="${u}" alt="" class="w-8 h-8 object-contain rounded">`;
            }
            if (c.icon) {
                return `<i class="${escapeHtmlMatrix(c.icon)} text-xl leading-none" style="color: ${color};"></i>`;
            }
            return `<div class="w-8 h-8 rounded-full flex-shrink-0 border border-gray-100" style="background-color: ${color};"></div>`;
        }

        function populateCardGroupPermissionsTabFilter(cards) {
            const sel = document.getElementById('cardGroupPermissionsTabFilter');
            if (!sel) return;
            const tabs = [...new Set(cards.map(c => c.tab_name).filter(t => t && t !== '—'))]
                .sort((a, b) => a.localeCompare(b, 'pt-BR'));
            sel.innerHTML = '';
            const optAll = document.createElement('option');
            optAll.value = '';
            optAll.textContent = 'Todas as abas';
            sel.appendChild(optAll);
            tabs.forEach(t => {
                const o = document.createElement('option');
                o.value = t;
                o.textContent = t;
                sel.appendChild(o);
            });
            sel.value = '';
        }

        function applyCardGroupPermissionsTabFilter(tabName) {
            document.querySelectorAll('#cardGroupPermissionsTable tbody tr.cg-matrix-row').forEach(tr => {
                const t = tr.getAttribute('data-tab-name') || '';
                tr.style.display = (!tabName || t === tabName) ? '' : 'none';
            });
        }

        function syncColumnHeaderState(table, groupId) {
            if (!table || groupId === null || groupId === undefined) return;
            const gid = String(groupId);
            const header = table.querySelector('.cg-col-select-all[data-group-id="' + gid + '"]');
            const cells = table.querySelectorAll('.cg-matrix-cb[data-group-id="' + gid + '"]');
            if (!header || !cells.length) return;
            let checked = 0;
            cells.forEach(cb => { if (cb.checked) checked++; });
            header.checked = checked === cells.length && cells.length > 0;
            header.indeterminate = checked > 0 && checked < cells.length;
        }

        function syncAllColumnHeaderStates(table) {
            if (!table) return;
            table.querySelectorAll('.cg-col-select-all').forEach(h => {
                syncColumnHeaderState(table, h.getAttribute('data-group-id'));
            });
        }

        if (!window.__cgTabFilterBound) {
            window.__cgTabFilterBound = true;
            document.getElementById('cardGroupPermissionsTabFilter')?.addEventListener('change', function() {
                applyCardGroupPermissionsTabFilter(this.value);
            });
        }

        if (!window.__cgPermissionsMatrixChangeBound) {
            window.__cgPermissionsMatrixChangeBound = true;
            document.body.addEventListener('change', function(e) {
                const t = e.target;
                const table = document.getElementById('cardGroupPermissionsTable');
                if (!table || !table.contains(t)) return;
                if (t.classList.contains('cg-col-select-all')) {
                    const gid = t.getAttribute('data-group-id');
                    const on = t.checked;
                    table.querySelectorAll('.cg-matrix-cb[data-group-id="' + gid + '"]').forEach(cb => { cb.checked = on; });
                    t.indeterminate = false;
                    syncColumnHeaderState(table, gid);
                    return;
                }
                if (t.classList.contains('cg-matrix-cb')) {
                    syncColumnHeaderState(table, t.getAttribute('data-group-id'));
                }
            });
        }

        async function loadCardGroupPermissionsMatrix() {
            const loading = document.getElementById('cardGroupPermissionsLoading');
            const errEl = document.getElementById('cardGroupPermissionsError');
            const wrap = document.getElementById('cardGroupPermissionsTableWrap');
            const status = document.getElementById('cardGroupPermissionsStatus');
            errEl.classList.add('hidden');
            errEl.textContent = '';
            status.textContent = '';
            loading.classList.remove('hidden');
            wrap.classList.add('hidden');
            try {
                const r = await fetch(cardGroupPermissionsDataUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await r.json();
                if (!r.ok) {
                    throw new Error(data.message || 'Erro ao carregar dados.');
                }
                renderCardGroupPermissionsTable(data);
                loading.classList.add('hidden');
                wrap.classList.remove('hidden');
            } catch (e) {
                loading.classList.add('hidden');
                errEl.textContent = e.message || 'Falha ao carregar.';
                errEl.classList.remove('hidden');
            }
        }

        function renderCardGroupPermissionsTable(data) {
            const table = document.getElementById('cardGroupPermissionsTable');
            const groups = data.groups || [];
            const cards = data.cards || [];
            __cgMatrixCardIds = cards.map(c => c.id);

            if (cards.length === 0) {
                table.innerHTML = '<tbody><tr><td class="px-4 py-8 text-center text-gray-500">Nenhum card cadastrado.</td></tr></tbody>';
                populateCardGroupPermissionsTabFilter([]);
                return;
            }

            if (groups.length === 0) {
                table.innerHTML = '<tbody><tr><td class="px-4 py-8 text-center text-gray-500">Nenhum grupo de usuários cadastrado. Crie grupos em Gerenciar Grupos e Usuários.</td></tr></tbody>';
                populateCardGroupPermissionsTabFilter(cards);
                return;
            }

            let html = '<thead class="bg-gray-50"><tr>';
            html += '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap sticky left-0 bg-gray-50 z-20 min-w-[260px] border-r border-gray-200 shadow-[2px_0_4px_-2px_rgba(0,0,0,0.06)] align-bottom">Card / Aba</th>';
            groups.forEach(g => {
                html += `<th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap min-w-[5.5rem] align-bottom">
                    <div class="flex flex-col items-center gap-1.5">
                        <input type="checkbox" class="cg-col-select-all rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" data-group-id="${g.id}" title="Marcar ou desmarcar todos os cards para ${escapeAttrMatrix(g.name)}">
                        <span class="inline-block max-w-[120px] truncate leading-tight" title="${escapeHtmlMatrix(g.name)}">${escapeHtmlMatrix(g.name)}</span>
                    </div>
                </th>`;
            });
            html += '</tr></thead><tbody class="bg-white divide-y divide-gray-100">';

            cards.forEach(c => {
                const tabName = c.tab_name || '';
                html += `<tr class="cg-matrix-row" data-tab-name="${escapeAttrMatrix(tabName)}">`;
                html += `<td class="px-3 py-2 sticky left-0 z-10 border-r border-gray-100 shadow-[2px_0_4px_-2px_rgba(0,0,0,0.06)] cg-matrix-sticky-cell bg-white">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 bg-gray-50/80">
                            ${buildCardIconHtmlForMatrix(c)}
                        </div>
                        <div class="min-w-0">
                            <div class="font-medium text-gray-900 truncate">${escapeHtmlMatrix(c.name)}</div>
                            <div class="text-xs text-gray-500 truncate">${escapeHtmlMatrix(tabName)}</div>
                        </div>
                    </div></td>`;
                const set = new Set((c.group_ids || []).map(Number));
                groups.forEach(g => {
                    const checked = set.has(g.id) ? ' checked' : '';
                    html += `<td class="px-2 py-2 text-center align-middle bg-white">
                        <input type="checkbox" class="cg-matrix-cb rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" data-card-id="${c.id}" data-group-id="${g.id}"${checked}>
                    </td>`;
                });
                html += '</tr>';
            });
            html += '</tbody>';
            table.innerHTML = html;

            populateCardGroupPermissionsTabFilter(cards);
            applyCardGroupPermissionsTabFilter('');
            syncAllColumnHeaderStates(table);
        }

        async function saveCardGroupPermissionsMatrix() {
            const btn = document.getElementById('cardGroupPermissionsSaveBtn');
            const status = document.getElementById('cardGroupPermissionsStatus');
            const matrix = {};
            __cgMatrixCardIds.forEach(id => {
                matrix[String(id)] = [];
            });
            document.querySelectorAll('#cardGroupPermissionsTable .cg-matrix-cb:checked').forEach(cb => {
                const cid = String(cb.getAttribute('data-card-id'));
                const gid = parseInt(cb.getAttribute('data-group-id'), 10);
                if (!matrix[cid]) matrix[cid] = [];
                matrix[cid].push(gid);
            });

            btn.disabled = true;
            status.textContent = 'Salvando…';
            try {
                const r = await fetch(cardGroupPermissionsSaveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ matrix }),
                });
                const data = await r.json();
                if (!r.ok) {
                    throw new Error(data.message || (data.errors ? JSON.stringify(data.errors) : 'Erro ao salvar.'));
                }
                status.textContent = data.message || 'Salvo.';
                if (typeof window.showToast === 'function') {
                    window.showToast(data.message || 'Visibilidade atualizada.', 'success', 4500);
                }
            } catch (e) {
                status.textContent = '';
                if (typeof window.showToast === 'function') {
                    window.showToast(e.message || 'Erro ao salvar.', 'error', 5500);
                } else {
                    alert(e.message || 'Erro ao salvar.');
                }
            } finally {
                btn.disabled = false;
            }
        }

        /* ——— Gerenciar Abas (modal na página de cards) ——— */
        function openTabsManagerModal() {
            document.getElementById('tabsManagerModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeTabsManagerModal() {
            document.getElementById('tabsManagerModal').classList.add('hidden');
            closeTabCreateModal(false);
            closeTabEditModal(false);
            closeTabDeleteConfirmModal(false);
            document.body.classList.remove('modal-open');
        }

        function openTabCreateModal() {
            document.getElementById('tabCreateModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            loadTabCreateForm();
        }

        function closeTabCreateModal(syncBody) {
            document.getElementById('tabCreateModal').classList.add('hidden');
            const content = document.getElementById('tabCreateModalContent');
            if (content) content.innerHTML = '';
            if (syncBody !== false && document.getElementById('tabsManagerModal').classList.contains('hidden')) {
                document.body.classList.remove('modal-open');
            }
        }

        function openTabEditModal(tabId) {
            document.getElementById('tabEditModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            loadTabEditForm(tabId);
        }

        function closeTabEditModal(syncBody) {
            document.getElementById('tabEditModal').classList.add('hidden');
            const content = document.getElementById('tabEditModalContent');
            if (content) content.innerHTML = '';
            if (syncBody !== false && document.getElementById('tabsManagerModal').classList.contains('hidden')) {
                document.body.classList.remove('modal-open');
            }
        }

        function openTabDeleteConfirmModal(tabId) {
            document.getElementById('tabDeleteConfirmModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            const btn = document.getElementById('confirmTabDeleteBtn');
            btn.onclick = function () { confirmDeleteTab(tabId); };
        }

        function closeTabDeleteConfirmModal(syncBody) {
            document.getElementById('tabDeleteConfirmModal').classList.add('hidden');
            if (syncBody !== false && document.getElementById('tabsManagerModal').classList.contains('hidden')) {
                document.body.classList.remove('modal-open');
            }
        }

        function loadTabCreateForm() {
            fetch("{{ route('admin.tabs.create') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('tabCreateModalContent').innerHTML = data.html;
                setupTabCreateForm();
            })
            .catch(err => console.error('Erro ao carregar formulário de aba:', err));
        }

        function loadTabEditForm(tabId) {
            fetch('{{ url('/admin/tabs') }}/' + tabId + '/edit', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('tabEditModalContent').innerHTML = data.html;
                setupTabEditForm();
            })
            .catch(err => console.error('Erro ao carregar edição de aba:', err));
        }

        function setupTabCreateForm() {
            const form = document.querySelector('#tabCreateModalContent form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitTabCreateForm(this);
                });
            }
        }

        function setupTabEditForm() {
            const form = document.querySelector('#tabEditModalContent form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitTabEditForm(this);
                });
            }
        }

        function submitTabCreateForm(form) {
            const formData = new FormData(form);
            fetch("{{ route('admin.tabs.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeTabCreateModal(false);
                    closeTabsManagerModal();
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    showErrorMessage('Erro ao criar aba');
                }
            })
            .catch(() => showErrorMessage('Erro ao criar aba'));
        }

        function submitTabEditForm(form) {
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            const m = form.getAttribute('action').match(/\/tabs\/(\d+)/);
            const tabId = m ? m[1] : null;
            if (!tabId) {
                showErrorMessage('Não foi possível identificar a aba');
                return;
            }
            fetch('{{ url('/admin/tabs') }}/' + tabId, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeTabEditModal(false);
                    closeTabsManagerModal();
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    showErrorMessage('Erro ao atualizar aba');
                }
            })
            .catch(() => showErrorMessage('Erro ao atualizar aba'));
        }

        function confirmDeleteTab(tabId) {
            closeTabDeleteConfirmModal(false);
            const row = document.querySelector('#tabsManagerTableBody tr[data-tab-row-id="' + tabId + '"]');
            const deleteBtn = row ? row.querySelector('button[onclick*="openTabDeleteConfirmModal(' + tabId + ')"]') : null;
            const originalContent = deleteBtn ? deleteBtn.innerHTML : '';
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                deleteBtn.disabled = true;
            }
            fetch('{{ url('/admin/tabs') }}/' + tabId, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeTabsManagerModal();
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    showErrorMessage('Erro ao excluir aba');
                }
            })
            .catch(() => showErrorMessage('Erro ao excluir aba'))
            .finally(() => {
                if (deleteBtn) {
                    deleteBtn.innerHTML = originalContent;
                    deleteBtn.disabled = false;
                }
            });
        }

    </script>
@endsection 