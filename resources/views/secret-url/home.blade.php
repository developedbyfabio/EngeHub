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
                                <p class="text-gray-600 mt-1 text-sm">Clique em uma mesa para ver informações. Use os controles para zoom e arrastar.</p>
                            </div>
                            <div class="mapa-rede-forcelight border-2 border-gray-300 rounded-lg overflow-hidden shadow-inner relative" style="height: 70vh; background: #ffffff !important;">
                                {{-- Controles de zoom flutuantes no canto superior direito do mapa --}}
                                <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="group" aria-label="Zoom do mapa">
                                    <button type="button" onclick="typeof mapZoomOut === 'function' && mapZoomOut()" class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-lg transition-colors" title="Diminuir zoom">−</button>
                                    <span id="zoomLevel" class="text-sm font-semibold text-gray-700 min-w-[3rem] text-center px-1">100%</span>
                                    <button type="button" onclick="typeof mapZoomIn === 'function' && mapZoomIn()" class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-lg transition-colors" title="Aumentar zoom">+</button>
                                    <button type="button" onclick="typeof mapResetZoom === 'function' && mapResetZoom()" class="px-2.5 py-1.5 text-sm font-medium rounded-md transition-colors" style="background-color: #E9B32C; color: #000;" onmouseover="this.style.backgroundColor='#d19d20'" onmouseout="this.style.backgroundColor='#E9B32C'" title="Resetar zoom">Reset</button>
                                </div>
                                <div id="mapaContainer" class="w-full h-full overflow-hidden cursor-grab" style="touch-action: none; background: #ffffff !important;">
                                    <div id="svgWrapper" class="inline-block p-4" style="transform-origin: 0 0; will-change: transform; background: #ffffff;">
                                        <div id="svgContainer" class="svg-map-theme" style="background: #ffffff !important;">
                                            {!! $mapSvgContent !!}
                                        </div>
                                    </div>
                                </div>
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

    <!-- Modal de Informações do Assento (Mapa de Rede) -->
    <div id="seatModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden" style="background: rgba(17,24,39,0.6); backdrop-filter: blur(4px);" onclick="if(event.target.id==='seatModal')closeSeatModal()">
        <div class="relative bg-white rounded-xl shadow-2xl flex flex-col w-full mx-auto my-auto" style="width: 100%; max-width: 28rem; max-height: 85vh;" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-5 border-b border-gray-200 bg-amber-50 rounded-t-xl shrink-0">
                <h3 class="text-lg font-semibold text-gray-900" id="seatModalTitle">Assento</h3>
                <button type="button" onclick="closeSeatModal()" class="p-1 text-gray-900 hover:text-gray-700 rounded">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-5 overflow-y-auto flex-1 min-h-0 text-sm" id="seatModalContent">
                <div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-2 border-amber-500 border-t-transparent mx-auto"></div><p class="mt-2 text-gray-600">Carregando...</p></div>
            </div>
        </div>
    </div>

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

    // --- Mapa de Rede: Zoom e Pan (translate + scale para arrastar em todos os 4 eixos) ---
    let mapZoomLevel = 1, mapTranslateX = 0, mapTranslateY = 0;
    let mapPanStartX, mapPanStartY, mapPanStartTranslateX, mapPanStartTranslateY, mapPanning = false;

    var mapRAF = null;
    function mapApplyTransform() {
        var w = document.getElementById('svgWrapper');
        if (!w) return;
        w.style.transform = 'translate(' + mapTranslateX + 'px, ' + mapTranslateY + 'px) scale(' + mapZoomLevel + ')';
    }
    function mapScheduleTransform() {
        if (mapRAF !== null) return;
        mapRAF = requestAnimationFrame(function() {
            mapRAF = null;
            mapApplyTransform();
        });
    }
    /** Zoom centralizado no ponto (clientX, clientY). Se não passar coordenadas, usa o centro do container. */
    function mapZoomAtPoint(clientX, clientY, zoomIn) {
        var container = document.getElementById('mapaContainer');
        var rect = container ? container.getBoundingClientRect() : null;
        var mouseX = rect ? (clientX - rect.left) : 0;
        var mouseY = rect ? (clientY - rect.top) : 0;
        var newScale = zoomIn ? Math.min(5, mapZoomLevel + 0.25) : Math.max(0.25, mapZoomLevel - 0.25);
        if (newScale === mapZoomLevel) return;
        var contentX = (mouseX - mapTranslateX) / mapZoomLevel;
        var contentY = (mouseY - mapTranslateY) / mapZoomLevel;
        mapTranslateX = mouseX - contentX * newScale;
        mapTranslateY = mouseY - contentY * newScale;
        mapZoomLevel = newScale;
        mapApplyTransform();
        var el = document.getElementById('zoomLevel');
        if (el) el.textContent = Math.round(mapZoomLevel * 100) + '%';
    }
    function mapZoomIn() {
        var container = document.getElementById('mapaContainer');
        if (container) {
            var r = container.getBoundingClientRect();
            mapZoomAtPoint(r.left + r.width / 2, r.top + r.height / 2, true);
        } else {
            mapZoomLevel = Math.min(5, mapZoomLevel + 0.25);
            mapApplyTransform();
            var el = document.getElementById('zoomLevel');
            if (el) el.textContent = Math.round(mapZoomLevel * 100) + '%';
        }
    }
    function mapZoomOut() {
        var container = document.getElementById('mapaContainer');
        if (container) {
            var r = container.getBoundingClientRect();
            mapZoomAtPoint(r.left + r.width / 2, r.top + r.height / 2, false);
        } else {
            mapZoomLevel = Math.max(0.25, mapZoomLevel - 0.25);
            mapApplyTransform();
            var el = document.getElementById('zoomLevel');
            if (el) el.textContent = Math.round(mapZoomLevel * 100) + '%';
        }
    }
    function mapResetZoom() {
        mapZoomLevel = 1;
        mapTranslateX = 0;
        mapTranslateY = 0;
        mapApplyTransform();
        var el = document.getElementById('zoomLevel');
        if (el) el.textContent = '100%';
    }
    function initMapPanZoom() {
        var container = document.getElementById('mapaContainer');
        var wrapper = document.getElementById('svgWrapper');
        if (!container || !wrapper) return;
        mapApplyTransform();
        container.style.cursor = 'grab';
        container.addEventListener('mousedown', function(e) {
            if (e.target.closest('[data-seat]') || e.target.tagName === 'text' || e.target.tagName === 'tspan') return;
            e.preventDefault();
            e.stopPropagation();
            mapPanning = true;
            mapPanStartX = e.clientX;
            mapPanStartY = e.clientY;
            mapPanStartTranslateX = mapTranslateX;
            mapPanStartTranslateY = mapTranslateY;
            container.style.cursor = 'grabbing';
            container.style.userSelect = 'none';
        }, { passive: false });
        document.addEventListener('mousemove', function(e) {
            if (!mapPanning) return;
            e.preventDefault();
            mapTranslateX = mapPanStartTranslateX + (e.clientX - mapPanStartX);
            mapTranslateY = mapPanStartTranslateY + (e.clientY - mapPanStartY);
            mapScheduleTransform();
        }, { passive: false });
        document.addEventListener('mouseup', function() {
            if (mapPanning) {
                mapPanning = false;
                container.style.cursor = 'grab';
                container.style.userSelect = '';
            }
        });
        document.addEventListener('mouseleave', function() {
            if (mapPanning) {
                mapPanning = false;
                container.style.cursor = 'grab';
                container.style.userSelect = '';
            }
        });
        container.addEventListener('wheel', function(e) {
            e.preventDefault();
            mapZoomAtPoint(e.clientX, e.clientY, e.deltaY < 0);
        }, { passive: false });
    }
    var seatLabelsSecret = @json($seatLabels ?? []);
    function applySeatLabelsSecret() {
        var container = document.getElementById('svgContainer');
        if (!container) return;
        [].forEach.call(container.querySelectorAll('[data-seat]'), function(el) {
            var code = el.getAttribute('data-seat');
            var original = el.getAttribute('data-original-text') || code;
            var text = seatLabelsSecret[code] ? seatLabelsSecret[code] : original;
            el.textContent = text;
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        initMapPanZoom();
        var svg = document.getElementById('svgContainer');
        if (svg) {
            var re = /^[A-Z]+\d{2}$/;
            function markIfLeaf(el) {
                var t = (el.textContent || '').trim();
                if (!re.test(t) || el.children.length > 0) return;
                el.setAttribute('data-seat', t);
                el.setAttribute('data-original-text', t);
                el.classList.add('seat');
                el.style.cursor = 'pointer';
            }
            [].forEach.call(svg.querySelectorAll('text'), markIfLeaf);
            [].forEach.call(svg.querySelectorAll('tspan'), markIfLeaf);
            [].forEach.call(svg.querySelectorAll('foreignObject'), function(fo) {
                [].forEach.call(fo.querySelectorAll('*'), markIfLeaf);
            });
            applySeatLabelsSecret();
            document.getElementById('mapaContainer').addEventListener('click', function(e) {
                var seatEl = e.target.closest('[data-seat]');
                if (seatEl) { e.preventDefault(); e.stopPropagation(); openSeatModal(seatEl.getAttribute('data-seat')); }
            });
        }
    });
    function openSeatModal(code) {
        var modal = document.getElementById('seatModal');
        var title = document.getElementById('seatModalTitle');
        var content = document.getElementById('seatModalContent');
        if (!modal || !content) return;
        title.textContent = 'Assento ' + code;
        content.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-2 border-amber-500 border-t-transparent mx-auto"></div><p class="mt-2 text-gray-600">Carregando...</p></div>';
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        fetch('/api/seats/' + encodeURIComponent(code), { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) { content.innerHTML = '<p class="text-red-600">Erro ao carregar.</p>'; return; }
                var d = data.data;
                title.textContent = (d.colaborador && d.colaborador.nome) ? (d.colaborador.nome + ' — ' + code) : ('Assento ' + code);
                if (d.disponivel || !d.colaborador) {
                    content.innerHTML = '<div class="text-center py-6"><i class="fas fa-chair text-4xl text-gray-400 mb-3"></i><h4 class="font-semibold text-gray-800">Assento disponível</h4><p class="text-gray-600 mt-1">O assento <strong>' + code + '</strong> está livre.</p>' + (d.setor ? '<p class="text-gray-500 mt-2 text-sm">Setor: ' + d.setor + '</p>' : '') + '</div>';
                    return;
                }
                var html = '<div class="space-y-3">';
                if (d.setor) html += '<p class="text-sm text-gray-600"><strong>Setor:</strong> ' + d.setor + '</p>';
                if (d.observacoes) html += '<p class="text-sm text-gray-600"><strong>Observações:</strong> ' + d.observacoes + '</p>';
                html += '<h4 class="font-semibold text-gray-800">Colaborador</h4><ul class="list-disc list-inside text-gray-700"><li>' + (d.colaborador.nome || '-') + '</li><li>' + (d.colaborador.email || '-') + '</li><li>Computador: ' + (d.colaborador.computador || '-') + '</li></ul>';
                if (d.pontos_rede && d.pontos_rede.length) {
                    html += '<h4 class="font-semibold text-gray-800 mt-4">Pontos de rede</h4><table class="w-full text-sm"><thead><tr class="border-b"><th class="text-left py-1">Código</th><th class="text-left py-1">IP</th></tr></thead><tbody>';
                    d.pontos_rede.forEach(function(p) { html += '<tr class="border-b"><td class="py-1">' + (p.code || '-') + '</td><td class="py-1">' + (p.ip || '-') + '</td></tr>'; });
                    html += '</tbody></table>';
                }
                if (d.historico && d.historico.length) {
                    html += '<h4 class="font-semibold text-gray-800 mt-4">Histórico</h4><div class="max-h-40 overflow-y-auto"><table class="w-full text-sm"><thead><tr class="border-b"><th class="text-left py-1">Colaborador</th><th class="text-left py-1">Período</th></tr></thead><tbody>';
                    d.historico.forEach(function(h) { html += '<tr class="border-b"><td class="py-1">' + (h.colaborador || '-') + '</td><td class="py-1">' + (h.periodo || '-') + '</td></tr>'; });
                    html += '</tbody></table></div>';
                }
                html += '</div>';
                content.innerHTML = html;
            })
            .catch(function() { content.innerHTML = '<p class="text-red-600">Erro ao carregar. Tente novamente.</p>'; });
    }
    function closeSeatModal() {
        var modal = document.getElementById('seatModal');
        if (modal) { modal.style.display = 'none'; modal.classList.add('hidden'); }
    }

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
    .mapa-rede-forcelight { color-scheme: dark !important; background: #ffffff !important; isolation: isolate; contain: layout style paint; }
    .mapa-rede-forcelight #mapaContainer, .mapa-rede-forcelight #svgContainer, .mapa-rede-forcelight #svgWrapper { background: #ffffff !important; }
    .mapa-rede-forcelight .svg-map-theme svg { background-color: #ffffff !important; }
    /* Não forçar fill/color em todo texto: preservar cores do draw.io (ex.: laranja em "RH", "PRESIDÊNCIA"). Só forçar preto nos rótulos de mesa (data-seat). */
    .mapa-rede-forcelight .svg-map-theme [data-seat].seat { fill: #000000 !important; color: #000000 !important; }
    .mapa-rede-forcelight .svg-map-theme foreignObject [data-seat].seat { color: #000000 !important; }
    /* Quando o navegador está em tema CLARO, forçar mesma renderização que no tema escuro */
    @media (prefers-color-scheme: light) {
        .mapa-rede-forcelight, .mapa-rede-forcelight * { color-scheme: dark !important; }
        .mapa-rede-forcelight .svg-map-theme svg { background: #ffffff !important; background-color: #ffffff !important; }
    }
    /* Não forçar stroke preto em path/line/polyline/rect para preservar cores do draw.io (ex.: borda laranja nas mesas) */
    .svg-map-theme [data-seat].seat { cursor: pointer !important; }
    .svg-map-theme [data-seat].seat:hover { fill: #b45309 !important; }
    .svg-map-theme foreignObject [data-seat].seat:hover { color: #b45309 !important; }
    #mapaContainer svg { max-width: none !important; height: auto !important; }
    
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
