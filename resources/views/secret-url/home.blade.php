@extends('layouts.secret-url-app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                    
                @if(($cards && $cards->count() > 0) || ($activeNetworkMap ?? null))
                    <!-- Sistema de Abas: Área do Colaborador + Mapa de Rede -->
                    <div class="mb-8" x-data="{ 
                        activeTab: 'colaborador',
                        filters: {
                            category: 'all',
                            datacenter: 'all',
                            sort: 'name'
                        },
                        // Quantidade de colunas na grade (carrega do localStorage ou usa padrão: 4)
                        gridColumns: localStorage.getItem('secretUrlGridColumns') || '4',
                        
                        // Função para verificar se o card deve ser mostrado
                        shouldShowCard(cardId, categoryId, datacenterId) {
                            // Filtro por categoria
                            if (this.filters.category !== 'all' && categoryId !== this.filters.category) {
                                return false;
                            }
                            
                            // Filtro por data center
                            if (this.filters.datacenter !== 'all') {
                                if (this.filters.datacenter === 'none' && datacenterId !== '') {
                                    return false;
                                } else if (this.filters.datacenter !== 'none' && datacenterId !== this.filters.datacenter) {
                                    return false;
                                }
                            }
                            
                            return true;
                        },
                        
                        // Função para ordenar os cards
                        sortCards() {
                            const container = document.querySelector('.cards-grid');
                            if (!container) return;
                            
                            const cards = Array.from(container.children);
                            
                            cards.sort((a, b) => {
                                const nameA = a.dataset.cardName || '';
                                const nameB = b.dataset.cardName || '';
                                
                                if (this.filters.sort === 'name_desc') {
                                    return nameB.localeCompare(nameA, 'pt-BR');
                                }
                                return nameA.localeCompare(nameB, 'pt-BR');
                            });
                            
                            cards.forEach(card => container.appendChild(card));
                        }
                    }" x-init="
                        // Ordenar cards alfabeticamente ao carregar a página
                        $nextTick(() => {
                            sortCards();
                        });
                        
                        // Salvar seleção de grade no localStorage quando mudar
                        $watch('gridColumns', (value) => {
                            localStorage.setItem('secretUrlGridColumns', value);
                        });
                        
                        $watch('filters', () => {
                            $nextTick(() => {
                                sortCards();
                            });
                        }, { deep: true });
                    ">
                        
                        <!-- Botões das Abas -->
                        <div class="bg-black rounded-t-lg">
                            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                                <button
                                    @click="activeTab = 'colaborador'"
                                    :class="activeTab === 'colaborador' ? 'tab-button tab-active' : 'tab-button tab-inactive'"
                                    class="whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm transition-all duration-200 flex items-center"
                                    :style="activeTab === 'colaborador' ? 'border-color: #E9B32C !important; color: #E9B32C !important; background-color: transparent;' : 'border-color: transparent; color: #9ca3af;'"
                                >
                                    <i class="fas fa-link mr-2" :style="activeTab === 'colaborador' ? 'color: #E9B32C !important;' : ''"></i>
                                    Área do Colaborador
                                </button>
                                @if($activeNetworkMap && $mapSvgContent)
                                <button
                                    @click="activeTab = 'mapa'"
                                    :class="activeTab === 'mapa' ? 'tab-button tab-active' : 'tab-button tab-inactive'"
                                    class="whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm transition-all duration-200 flex items-center"
                                    :style="activeTab === 'mapa' ? 'border-color: #E9B32C !important; color: #E9B32C !important; background-color: transparent;' : 'border-color: transparent; color: #9ca3af;'"
                                >
                                    <i class="fas fa-map-marked-alt mr-2" :style="activeTab === 'mapa' ? 'color: #E9B32C !important;' : ''"></i>
                                    Mapa de Rede
                                </button>
                                @endif
                            </nav>
                        </div>
                        
                        <!-- Conteúdo da Aba Área do Colaborador -->
                        <div class="mt-8" x-show="activeTab === 'colaborador'" x-transition>
                        <div>
                            @if($cards && $cards->count() > 0)
                            <!-- Filtros -->
                            <div class="mb-6 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                                <div class="flex flex-wrap items-center justify-center gap-4">
                                    @if($allCategories && $allCategories->count() > 0)
                                        <!-- Filtro por Categoria -->
                                        <div class="flex items-center gap-2">
                                            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                                <i class="fas fa-filter mr-1"></i>
                                                Categoria:
                                            </label>
                                            <select x-model="filters.category" 
                                                    class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[120px]">
                                                <option value="all">Todas</option>
                                                @foreach($allCategories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    
                                    @if($allDataCenters && $allDataCenters->count() > 0)
                                        <!-- Filtro por Data Center -->
                                        <div class="flex items-center gap-2">
                                            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                                <i class="fas fa-server mr-1"></i>
                                                Data Center:
                                            </label>
                                            <select x-model="filters.datacenter" 
                                                    class="text-sm border-gray-300 rounded-md focus:border-purple-500 focus:ring-purple-500 min-w-[120px]">
                                                <option value="all">Todos</option>
                                                <option value="none">Sem Data Center</option>
                                                @foreach($allDataCenters as $datacenter)
                                                    <option value="{{ $datacenter->id }}">{{ $datacenter->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    
                                    <!-- Filtro de Ordenação -->
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <i class="fas fa-sort mr-1"></i>
                                            Ordenar:
                                        </label>
                                        <select x-model="filters.sort" 
                                                @change="sortCards()"
                                                class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[120px]">
                                            <option value="name">Alfabética A-Z</option>
                                            <option value="name_desc">Alfabética Z-A</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Filtro de Grade -->
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <i class="fas fa-th-large mr-1"></i>
                                            Grade:
                                        </label>
                                        <select x-model="gridColumns" 
                                                class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[110px]">
                                            <option value="4">4 colunas</option>
                                            <option value="6">6 colunas</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Botão Limpar Filtros -->
                                    <button @click="filters.category = 'all'; filters.datacenter = 'all'; filters.sort = 'name'; sortCards();"
                                            class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                        <i class="fas fa-eraser"></i>
                                        Limpar Filtros
                                    </button>
                                    
                                    <!-- Contador de Sistemas -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                        {{ $cards->count() }} {{ $cards->count() === 1 ? 'sistema' : 'sistemas' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Grid de Cards -->
                            <div class="cards-grid grid gap-4 grid-cols-1 sm:grid-cols-2"
                                 :class="'grid-cols-' + gridColumns"
                                 :style="'grid-template-columns: repeat(' + gridColumns + ', minmax(0, 1fr));'"
                                 x-bind:style="'grid-template-columns: repeat(' + gridColumns + ', minmax(0, 1fr));'">
                                @foreach($cards as $card)
                                    <div x-show="shouldShowCard('{{ $card->id }}', '{{ $card->category_id }}', '{{ $card->data_center_id ?? '' }}')"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200"
                                         data-card-id="{{ $card->id }}"
                                         data-card-name="{{ $card->name }}"
                                         data-card-category="{{ $card->category_id }}">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center">
                                                    @if($card->custom_icon_path)
                                                        <img src="{{ $card->custom_icon_url }}" alt="{{ $card->name }}" class="w-8 h-8 object-contain">
                                                    @elseif($card->icon)
                                                        <i class="{{ $card->icon }} text-2xl" style="color: {{ $card->tab->color ?? '#3B82F6' }};"></i>
                                                    @else
                                                        <div class="w-8 h-8 rounded-full" style="background-color: {{ $card->tab->color ?? '#3B82F6' }};"></div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex items-center space-x-2">
                                                    @if($card->monitor_status)
                                                        <div class="relative tooltip-container">
                                                            <div class="flex items-center space-x-1 cursor-help">
                                                                <div class="w-3 h-3 rounded-full {{ $card->status_class }}" style="background-color: {{ $card->status === 'online' ? '#10b981' : ($card->status === 'offline' ? '#ef4444' : '#6b7280') }};"></div>
                                                                @php
                                                                    $statusColor = 'text-gray-500';
                                                                    if ($card->status === 'online') {
                                                                        $statusColor = 'text-green-600';
                                                                    } elseif ($card->status === 'offline') {
                                                                        $statusColor = 'text-red-600';
                                                                    }
                                                                @endphp
                                                                <span class="text-xs font-medium {{ $statusColor }}">
                                                                    {{ $card->status_text }}
                                                                </span>
                                                            </div>
                                                            
                                                            <!-- Status Tooltip -->
                                                            <div class="tooltip-status">
                                                                <div class="flex items-center mb-1">
                                                                    <div class="w-2 h-2 rounded-full {{ $card->status_class }} mr-2" style="background-color: {{ $card->status === 'online' ? '#10b981' : ($card->status === 'offline' ? '#ef4444' : '#6b7280') }};"></div>
                                                                    <span class="font-medium">{{ $card->status_text }}</span>
                                                                </div>
                                                                @if($card->response_time)
                                                                    <div class="text-xs text-gray-300">Tempo: {{ $card->response_time }}ms</div>
                                                                @endif
                                                                <div class="text-xs text-gray-300">Verificado: {{ $card->last_status_check ? $card->last_status_check->format('H:i:s') : 'Nunca' }}</div>
                                                                <div class="tooltip-arrow"></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($card->description)
                                                        <div class="relative tooltip-container">
                                                            <div class="cursor-help rounded-full w-4 h-4 flex items-center justify-center" style="background-color: #f49e0b;">
                                                                <i class="fas fa-info" style="color: #ffffff; font-size: 0.5rem;"></i>
                                                            </div>
                                                            
                                                            <!-- Description Tooltip -->
                                                            <div class="tooltip-description">
                                                                <div class="font-medium mb-1">Descrição</div>
                                                                <div class="text-xs text-gray-300">{{ $card->description }}</div>
                                                                <div class="tooltip-arrow"></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($card->file_path)
                                                        <a href="{{ $card->file_url }}" target="_blank" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                            <i class="fas fa-paperclip"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                                {{ $card->name }}
                                            </h3>
                                            
                                            <div class="w-full">
                                                @if($card->monitoring_type === 'ping')
                                                    <!-- Botão Copiar IP para servidores -->
                                                    <button 
                                                        onclick="copyServerIP('{{ $card->link }}', '{{ $card->name }}')"
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide transition ease-in-out duration-150"
                                                        style="background-color: #f49e0b;"
                                                        onmouseover="this.style.backgroundColor='#d97706'"
                                                        onmouseout="this.style.backgroundColor='#f49e0b'"
                                                        title="Copiar IP do servidor"
                                                    >
                                                        <i class="fas fa-copy mr-2"></i>
                                                        Copiar IP
                                                    </button>
                                                @else
                                                    <!-- Botão Acessar para sites web -->
                                                    <a 
                                                        href="{{ $card->link }}" 
                                                        target="_blank" 
                                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide transition ease-in-out duration-150"
                                                        style="background-color: #f49e0b;"
                                                        onmouseover="this.style.backgroundColor='#d97706'"
                                                        onmouseout="this.style.backgroundColor='#f49e0b'"
                                                    >
                                                        <i class="fas fa-external-link-alt mr-2"></i>
                                                        Acessar
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                            @else
                            <div class="text-center py-12">
                                <i class="fas fa-link text-4xl text-gray-400 mb-4"></i>
                                <h2 class="text-xl font-semibold text-gray-600 mb-2">Nenhum sistema neste setor</h2>
                                <p class="text-gray-500">A Área do Colaborador não possui sistemas cadastrados.</p>
                            </div>
                            @endif
                        </div>
                        @if($activeNetworkMap && $mapSvgContent)
                        <!-- Conteúdo da Aba Mapa de Rede -->
                        <div class="mt-8" x-show="activeTab === 'mapa'" x-transition style="display: none;">
                            <div class="mb-4">
                                <h2 class="text-xl font-bold text-gray-800">
                                    <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
                                    Mapa de Rede — {{ $activeNetworkMap->name }}
                                </h2>
                                <p class="text-gray-600 mt-1 text-sm">Clique em um dispositivo do mapa (mesa, impressora, TV, etc.) para ver detalhes. Use os controles para zoom e arrastar.</p>
                            </div>
                            <div class="mapa-rede-forcelight border-2 border-gray-300 rounded-lg overflow-hidden shadow-inner relative" style="height: 70vh; background: #ffffff !important;">
                                @include('admin.network-maps.partials.map-layer-filters')
                                <div class="absolute top-3 right-3 z-30 flex flex-wrap items-center justify-end gap-2 max-w-[calc(100%-1rem)] pointer-events-auto" role="toolbar" aria-label="Controles do mapa">
                                    <div class="flex flex-wrap items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="search" aria-label="Buscar colaborador no mapa">
                                        <input type="search" id="mapCollaboratorSearch" name="map_collaborator_search" placeholder="Buscar colaborador…" autocomplete="off" class="text-sm rounded-md border border-gray-300 px-2 py-1.5 w-36 sm:w-44 min-w-0 bg-white text-gray-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                        <div id="mapSearchNav" class="hidden flex items-center gap-0.5 shrink-0">
                                            <button type="button" id="mapSearchPrev" class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-800 text-lg font-semibold leading-none" title="Resultado anterior">&lsaquo;</button>
                                            <span id="mapSearchStatus" class="text-xs text-gray-800 font-semibold px-1 min-w-[5.5rem] text-center tabular-nums"></span>
                                            <button type="button" id="mapSearchNext" class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-800 text-lg font-semibold leading-none" title="Próximo resultado">&rsaquo;</button>
                                        </div>
                                        <span id="mapSearchFeedback" class="hidden text-xs text-amber-800 max-w-[12rem] leading-tight"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="group" aria-label="Rótulos no mapa">
                                        <button type="button" id="mapShowCodes" class="px-2.5 py-1.5 rounded-md text-sm font-medium border border-gray-400 btn-engehub-yellow transition-colors">Códigos</button>
                                        <button type="button" id="mapShowNames" class="px-2.5 py-1.5 rounded-md text-sm font-medium border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">Nomes</button>
                                    </div>
                                    <div class="flex items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="group" aria-label="Zoom do mapa">
                                        <button type="button" id="mapZoomOut" class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-lg transition-colors" title="Diminuir zoom">−</button>
                                        <span id="zoomLevel" class="text-sm font-semibold text-gray-700 min-w-[3rem] text-center px-1">100%</span>
                                        <button type="button" id="mapZoomIn" class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-lg transition-colors" title="Aumentar zoom">+</button>
                                        <button type="button" id="mapZoomReset" class="px-2.5 py-1.5 text-sm font-medium rounded-md btn-engehub-yellow transition-colors" title="Resetar zoom">Reset</button>
                                    </div>
                                </div>
                                <div id="mapaContainer" class="relative z-0 w-full h-full overflow-hidden cursor-grab" style="touch-action: none; background: #ffffff !important;">
                                    <div id="svgWrapper" class="inline-block p-4" style="transform-origin: 0 0; will-change: transform; background: #ffffff;">
                                        <div id="svgContainer" class="svg-map-theme" style="background: #ffffff !important;">
                                            {!! $mapSvgContent !!}
                                        </div>
                                    </div>
                                </div>
                                @include('admin.network-maps.partials.device-side-panel')
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                        <h2 class="text-xl font-semibold text-gray-600 mb-2">Nenhum sistema disponível</h2>
                        <p class="text-gray-500">Seu setor ainda não possui sistemas cadastrados.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.network-maps.partials.device-modals', ['canEditDevicesEffective' => false])

    <!-- Modal de Logins -->
    <div id="loginsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="handleLoginsModalClick(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900" id="loginsModalTitle">Logins do Sistema</h3>
                    <button onclick="closeLoginsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6" id="loginsModalContent">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Carregando logins...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // URL secreta atual para requisições
    const secretUrl = '{{ $systemUser->secret_url }}';
    
    // Funções para o modal de logins
    function openLoginsModal(cardId, cardName) {
        document.getElementById('loginsModalTitle').textContent = `Logins - ${cardName}`;
        document.getElementById('loginsModal').classList.remove('hidden');
        loadSystemLogins(cardId);
    }

    function closeLoginsModal() {
        document.getElementById('loginsModal').classList.add('hidden');
    }

    function handleLoginsModalClick(event) {
        if (event.target.id === 'loginsModal' || event.target.classList.contains('flex')) {
            closeLoginsModal();
        }
    }

    function loadSystemLogins(cardId) {
        fetch(`/s/${secretUrl}/cards/${cardId}/logins`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.html) {
                    document.getElementById('loginsModalContent').innerHTML = data.html;
                } else {
                    document.getElementById('loginsModalContent').innerHTML = `
                        <div class="text-center text-red-600">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Erro ao carregar os logins: ${data.message || 'Erro desconhecido'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('loginsModalContent').innerHTML = `
                    <div class="text-center text-red-600">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>Erro ao carregar os logins. Tente novamente.</p>
                    </div>
                `;
            });
    }
</script>
@if($activeNetworkMap && $mapSvgContent)
<script>
@include('admin.network-maps.partials.network-map-devices-script', [
    'network_map' => $activeNetworkMap,
    'filiaisMode' => false,
    'canEditDevicesEffective' => false,
    'deviceLabels' => $deviceLabels ?? [],
    'deviceApiBase' => url('/api/map-devices'),
])
</script>
@endif
<script>
    // Função para copiar IP do servidor
    function copyServerIP(ip, serverName) {
        navigator.clipboard.writeText(ip).then(function() {
            showToast('success', `IP de ${serverName} copiado!`);
        }).catch(function(err) {
            // Fallback
            const textArea = document.createElement("textarea");
            textArea.value = ip;
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                showToast('success', `IP de ${serverName} copiado!`);
            } catch (err) {
                showToast('error', 'Erro ao copiar IP');
            }
            
            document.body.removeChild(textArea);
        });
    }

    // Função para mostrar toast (simplificada)
    function showToast(type, message) {
        // Se existir o sistema de toast global, usar ele
        if (window.Toast && typeof window.Toast[type] === 'function') {
            window.Toast[type](message);
        } else {
            alert(message);
        }
    }
</script>

<style>
    /* Grid de cards - responsivo */
    .cards-grid {
        display: grid;
        gap: 1rem;
    }
    
    /* Mobile: 1 coluna */
    @media (max-width: 639px) {
        .cards-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        }
    }
    
    /* Tablet pequeno: 2 colunas */
    @media (min-width: 640px) and (max-width: 767px) {
        .cards-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    
    /* Tablet e Desktop: número de colunas baseado na seleção (4, 6 ou 8) */
    @media (min-width: 768px) {
        .cards-grid {
            /* O número de colunas será controlado via inline style do Alpine.js */
        }
    }
    
    /* Cards menores */
    .cards-grid > div {
        min-width: 0;
    }
    
    .cards-grid .p-6 {
        padding: 1rem;
    }
    
    .cards-grid h3 {
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }
    
    /* Tooltips */
    .tooltip-container {
        position: relative;
        z-index: 1;
    }
    
    .tooltip-container:hover {
        z-index: 999999;
    }
    
    .tooltip-status,
    .tooltip-description {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        z-index: 999999;
        background-color: #1f2937;
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.875rem;
        line-height: 1.25rem;
        white-space: nowrap;
        transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
        pointer-events: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border: 1px solid #374151;
        bottom: calc(100% + 8px);
        right: 0;
        min-width: 150px;
        max-width: 250px;
    }
    
    .tooltip-status {
        min-width: 120px;
        max-width: 180px;
    }
    
    .tooltip-description {
        white-space: normal;
        max-width: 250px;
    }
    
    .tooltip-container:hover .tooltip-status,
    .tooltip-container:hover .tooltip-description {
        visibility: visible;
        opacity: 1;
    }
    
    .tooltip-arrow {
        content: '';
        position: absolute;
        top: 100%;
        right: 8px;
        border-width: 6px;
        border-style: solid;
        border-color: #1f2937 transparent transparent transparent;
    }
    
    /* Mapa de Rede: forçar visual correto (fundo branco, elementos escuros) em QUALQUER tema do navegador.
       Com tema CLARO o navegador pinta o SVG preto; com tema ESCURO fica certo. Tratamos o bloco como
       color-scheme: dark para o navegador aplicar a mesma renderização que no tema escuro, e forçamos
       fundo branco e texto preto por CSS. */
    .mapa-rede-forcelight { color-scheme: dark !important; background: #ffffff !important; isolation: isolate; contain: layout style; }
    .mapa-rede-forcelight #mapaContainer, .mapa-rede-forcelight #svgContainer, .mapa-rede-forcelight #svgWrapper { background: #ffffff !important; }
    .mapa-rede-forcelight .svg-map-theme svg { background-color: #ffffff !important; }
    .btn-engehub-yellow { background-color: #E9B32C !important; color: #000 !important; }
    .btn-engehub-yellow:hover { background-color: #d19d20 !important; }
    /* Rótulos de dispositivo: preto no mapa forçado */
    .mapa-rede-forcelight .svg-map-theme [data-code].device { fill: #000000 !important; color: #000000 !important; }
    .mapa-rede-forcelight .svg-map-theme foreignObject [data-code].device { color: #000000 !important; }
    /* Quando o navegador está em tema CLARO, forçar mesma renderização que no tema escuro */
    @media (prefers-color-scheme: light) {
        .mapa-rede-forcelight, .mapa-rede-forcelight * { color-scheme: dark !important; }
        .mapa-rede-forcelight .svg-map-theme svg { background: #ffffff !important; background-color: #ffffff !important; }
    }
    /* Não forçar stroke preto em path/line/polyline/rect para preservar cores do draw.io (ex.: borda laranja nas mesas) */
    .svg-map-theme [data-code].device { cursor: pointer !important; }
    .svg-map-theme [data-code].device:hover { fill: #b45309 !important; }
    .svg-map-theme foreignObject [data-code].device:hover { color: #b45309 !important; }
    .svg-map-theme .device.device-search-highlight {
        filter: drop-shadow(0 0 3px #E9B32C) drop-shadow(0 0 6px rgba(233, 179, 44, 0.85));
        transition: filter 0.35s ease;
    }
    .svg-map-theme foreignObject .device.device-search-highlight {
        box-shadow: 0 0 0 2px #E9B32C, 0 0 12px rgba(233, 179, 44, 0.6);
        transition: box-shadow 0.35s ease;
    }
    .svg-map-theme .device.map-layer-filter-hidden {
        visibility: hidden !important;
        pointer-events: none !important;
    }
    .svg-map-theme foreignObject .device.map-layer-filter-hidden {
        visibility: hidden !important;
        pointer-events: none !important;
    }
    #mapaContainer svg { max-width: none !important; height: auto !important; }
    #deviceSidePanel.map-device-panel {
        position: absolute;
        right: 0.75rem;
        top: 4.25rem;
        bottom: 0.75rem;
        width: min(22rem, 36vw);
        min-width: 280px;
        max-width: 400px;
        z-index: 25;
        border-radius: 0.75rem;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.14);
        border: 1px solid #e5e7eb;
        opacity: 0;
        visibility: hidden;
        transform: translateX(10px);
        transition: opacity 0.28s ease, transform 0.28s ease, visibility 0.28s;
        pointer-events: none;
    }
    #deviceSidePanel.map-device-panel.map-device-panel--open {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
        pointer-events: auto;
    }
    @media (max-width: 640px) {
        #deviceSidePanel.map-device-panel {
            width: min(20rem, calc(100vw - 4rem));
            min-width: 0;
            right: 0.5rem;
        }
    }
    
    /* Garantir que os cards não bloqueiem tooltips */
    .cards-grid > div {
        position: relative;
        z-index: 1;
    }
    
    .cards-grid > div:hover {
        z-index: 10;
    }
    
    /* Estilos das Abas - Mesmo estilo da página Início */
    .tab-button {
        position: relative;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s ease-in-out;
    }
    
    .tab-button:hover {
        background-color: rgba(255, 255, 255, 0.05);
        transform: translateY(-1px);
    }
    
    .tab-active {
        border-bottom: 3px solid;
        font-weight: 600;
    }
    
    .tab-active,
    .tab-active *,
    .tab-active i {
        color: #E9B32C !important;
    }
    
    .tab-active {
        border-bottom-color: #E9B32C !important;
    }
    
    .tab-inactive {
        border-bottom: 2px solid transparent;
        opacity: 0.8;
    }
    
    .tab-inactive:hover {
        opacity: 1;
    }
</style>
@endsection
