@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                    
                    @if($tabs->count() > 0)
                        <!-- Sistema de Abas com Filtros -->
                        <div class="mb-8" x-data="{ 
                            activeTab: '{{ $favoritesTab ? 'favorites' : ($tabs->first() ? $tabs->first()->id : '') }}',
                            filters: {
                                @if($favoritesTab)
                                'favorites': {
                                    category: 'all',
                                    datacenter: 'all',
                                    sort: 'name'
                                },
                                @endif
                                @foreach($tabs as $tab)
                                '{{ $tab->id }}': {
                                    category: 'all',
                                    datacenter: 'all',
                                    sort: 'name'
                                }{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            },
                            
                            // Função para verificar se o card deve ser mostrado
                            shouldShowCard(cardId, categoryId, datacenterId, cardName, tabId) {
                                const filter = this.filters[tabId];
                                if (!filter) return true;
                                
                                // Filtro por categoria
                                if (filter.category !== 'all' && categoryId !== filter.category) {
                                    return false;
                                }
                                
                                // Filtro por data center
                                if (filter.datacenter !== 'all') {
                                    if (filter.datacenter === 'none' && datacenterId !== '') {
                                        return false;
                                    } else if (filter.datacenter !== 'none' && datacenterId !== filter.datacenter) {
                                        return false;
                                    }
                                }
                                
                                return true;
                            },
                            
                            // Função para ordenar cards
                            sortCards() {
                                const activeTabId = this.activeTab;
                                const filter = this.filters[activeTabId];
                                if (!filter) return;
                                
                                const cardsGrid = document.querySelector(`[x-show*='activeTab === \\\'${activeTabId}\\\''] .cards-grid`);
                                if (!cardsGrid) return;
                                
                                const cards = Array.from(cardsGrid.children);
                                
                                cards.sort((a, b) => {
                                    const aName = a.dataset.cardName;
                                    const bName = b.dataset.cardName;
                                    
                                    switch (filter.sort) {
                                        case 'name_desc':
                                            return bName.localeCompare(aName, 'pt-BR');
                                        case 'name':
                                        default:
                                            return aName.localeCompare(bName, 'pt-BR');
                                    }
                                });
                                
                                // Reordenar os elementos no DOM
                                cards.forEach(card => {
                                    cardsGrid.appendChild(card);
                                });
                                
                                // Reajustar alturas após reordenação
                                this.$nextTick(() => {
                                    adjustCardHeights();
                                });
                            }
                        }"
                        x-init="
                            $watch('filters', () => {
                                $nextTick(() => {
                                    sortCards();
                                    adjustCardHeights();
                                });
                            }, { deep: true });
                            
                            $watch('activeTab', () => {
                                $nextTick(() => {
                                    sortCards();
                                    adjustCardHeights();
                                });
                            });
                        ">
                            <!-- Container das Abas -->
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                                    @if($favoritesTab)
                                        <button
                                            @click="activeTab = 'favorites'"
                                            :class="activeTab === 'favorites' ? 'tab-active' : 'tab-inactive'"
                                            class="tab-button whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm transition-all duration-200 flex items-center"
                                            :style="activeTab === 'favorites' ? 
                                                'border-color: {{ $favoritesTab->color }}; color: {{ $favoritesTab->color }}; background-color: {{ $favoritesTab->color }}15;' : 
                                                'border-color: transparent; color: {{ $favoritesTab->color }};'"
                                        >
                                            <i class="fas fa-star mr-2"></i>
                                            {{ $favoritesTab->name }}
                                        </button>
                                    @endif
                                    @foreach($tabs as $tab)
                                        <button
                                            @click="activeTab = '{{ $tab->id }}'"
                                            :class="activeTab === '{{ $tab->id }}' ? 'tab-active' : 'tab-inactive'"
                                            class="tab-button whitespace-nowrap py-3 px-4 border-b-2 font-medium text-sm transition-all duration-200 flex items-center"
                                            :style="activeTab === '{{ $tab->id }}' ? 
                                                'border-color: {{ $tab->color }}; color: {{ $tab->color }}; background-color: {{ $tab->color }}15;' : 
                                                'border-color: transparent; color: {{ $tab->color }};'"
                                        >
                                            <i class="fas fa-folder mr-2"></i>
                                            {{ $tab->name }}
                                        </button>
                                    @endforeach
                                </nav>
                            </div>


                            <!-- Tab Content -->
                            @if($favoritesTab)
                                <!-- Aba de Favoritos -->
                                <div x-show="activeTab === 'favorites'" class="mt-8" x-data="tabContent">
                                    <!-- Filtros da Aba de Favoritos -->
                                    <div class="mb-6 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                                        <div class="flex flex-wrap items-center justify-center gap-4">
                                            @php
                                                $favoriteCategories = $favoritesTab->cards->pluck('category')->filter()->unique('id')->sortBy('name');
                                                $favoriteDatacenters = $favoritesTab->cards->pluck('dataCenter')->filter()->unique('id')->sortBy('name');
                                            @endphp
                                            
                                            @if($favoriteCategories->count() > 0)
                                                <!-- Filtro por Categoria -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                                        <i class="fas fa-filter mr-1"></i>
                                                        Categoria:
                                                    </label>
                                                    <select x-model="filters['favorites'].category" 
                                                            class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[120px]">
                                                        <option value="all">Todas</option>
                                                        @foreach($favoriteCategories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            
                                            @if($favoriteDatacenters->count() > 0)
                                                <!-- Filtro por Data Center -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                                        <i class="fas fa-server mr-1"></i>
                                                        Data Center:
                                                    </label>
                                                    <select x-model="filters['favorites'].datacenter" 
                                                            class="text-sm border-gray-300 rounded-md focus:border-purple-500 focus:ring-purple-500 min-w-[120px]">
                                                        <option value="all">Todos</option>
                                                        <option value="none">Sem Data Center</option>
                                                        @foreach($favoriteDatacenters as $datacenter)
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
                                                <select x-model="filters['favorites'].sort" 
                                                        class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[120px]">
                                                    <option value="name">Alfabética A-Z</option>
                                                    <option value="name_desc">Alfabética Z-A</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Botão Limpar Filtros -->
                                            <button @click="filters['favorites'].category = 'all'; filters['favorites'].datacenter = 'all'; filters['favorites'].sort = 'name';"
                                                    class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                                <i class="fas fa-eraser"></i>
                                                Limpar Filtros
                                            </button>
                                            
                                            <!-- Contador de Sistemas Favoritos -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-star mr-1"></i>
                                                {{ $favoritesTab->cards->count() }} {{ $favoritesTab->cards->count() === 1 ? 'favorito' : 'favoritos' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($favoritesTab->cards->count() > 0)
                                        <div class="cards-grid">
                                            @foreach($favoritesTab->cards as $card)
                                                <div x-show="shouldShowCard('{{ $card->id }}', '{{ $card->category_id }}', '{{ $card->data_center_id ?? '' }}', '{{ $card->name }}', 'favorites')"
                                                     x-transition:enter="transition ease-out duration-300"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200"
                                                     data-card-id="{{ $card->id }}"
                                                     data-card-name="{{ $card->name }}"
                                                     data-card-category="{{ $card->category_id }}"
                                                     data-tab-id="favorites">
                                                    <div class="p-6">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <div class="flex items-center">
                                                                @if($card->custom_icon_path)
                                                                    <img src="{{ $card->custom_icon_url }}" alt="{{ $card->name }}" class="w-8 h-8 object-contain">
                                                                @elseif($card->icon)
                                                                    <i class="{{ $card->icon }} text-2xl" style="color: {{ $favoritesTab->color }};"></i>
                                                                @else
                                                                    <div class="w-8 h-8 rounded-full" style="background-color: {{ $favoritesTab->color }};"></div>
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="flex items-center space-x-2">
                                                                <!-- Estrela de Favorito (sempre preenchida na aba favoritos) -->
                                                                <button onclick="toggleFavorite({{ $card->id }}, this)" 
                                                                        class="favorite-star text-yellow-500 hover:text-yellow-600 transition-colors duration-200"
                                                                        title="Remover dos favoritos"
                                                                        data-card-id="{{ $card->id }}"
                                                                        data-is-favorite="true">
                                                                    <i class="fas fa-star text-lg"></i>
                                                                </button>
                                                                
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
                                                                            <div class="text-xs text-gray-300">Verificado: {{ $card->last_status_check ? $card->last_status_check->format('d/m/Y H:i:s') : 'Nunca' }}</div>
                                                                            <div class="tooltip-arrow"></div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($card->description)
                                                                    <div class="relative tooltip-container">
                                                                        <i class="fas fa-info-circle text-gray-400 cursor-help" style="color: {{ $favoritesTab->color }};"></i>
                                                                        <div class="tooltip-description">
                                                                            <div class="font-medium mb-1">Descrição</div>
                                                                            <div class="text-xs text-gray-300">{{ $card->description }}</div>
                                                                            <div class="tooltip-arrow"></div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($card->file_path)
                                                                    <a href="{{ Storage::url($card->file_path) }}" target="_blank" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                                        <i class="fas fa-paperclip"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                                            {{ $card->name }}
                                                        </h3>
                                                        
                                                        <div class="flex space-x-2">
                                                            @if($card->monitoring_type === 'ping')
                                                                <button 
                                                                    onclick="copyServerIP('{{ $card->link }}', '{{ $card->name }}')"
                                                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                                    style="background-color: {{ $favoritesTab->color }};"
                                                                    title="Copiar IP do servidor"
                                                                >
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            @else
                                                                <a 
                                                                    href="{{ $card->link }}" 
                                                                    target="_blank" 
                                                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                                    style="background-color: {{ $favoritesTab->color }};"
                                                                >
                                                                    <i class="fas fa-external-link-alt mr-2"></i>
                                                                    Acessar
                                                                </a>
                                                            @endif
                                                            @php
                                                                $hasPermission = false;
                                                                try {
                                                                    if (auth()->check() && auth()->user() && auth()->user()->canViewPasswords()) {
                                                                        $hasPermission = true;
                                                                    } elseif (auth()->guard('system')->check() && auth()->guard('system')->user() && auth()->guard('system')->user()->canViewSystem($card->id)) {
                                                                        $hasPermission = true;
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    $hasPermission = false;
                                                                }
                                                            @endphp
                                                            <button 
                                                                onclick="{{ $hasPermission ? 'openLoginsModal(' . $card->id . ', \'' . addslashes($card->name) . '\')' : 'openAccessDeniedModal()' }}" 
                                                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                            >
                                                                <i class="fas fa-key mr-2"></i>
                                                                Logins
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-12">
                                            <i class="fas fa-star text-4xl text-gray-400 mb-4"></i>
                                            <p class="text-gray-500">Você ainda não tem nenhum sistema favorito.</p>
                                            <p class="text-gray-400 text-sm mt-2">Clique na estrela dos cards para adicioná-los aos favoritos.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @foreach($tabs as $tab)
                                <div x-show="activeTab === '{{ $tab->id }}'" class="mt-8" x-data="tabContent">
                                    <!-- Filtros da Aba -->
                                    <div class="mb-6 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                                        <div class="flex flex-wrap items-center justify-center gap-4">
                                            @php
                                                $categories = $tab->cards->pluck('category')->filter()->unique('id')->sortBy('name');
                                                $datacenters = $tab->cards->pluck('dataCenter')->filter()->unique('id')->sortBy('name');
                                            @endphp
                                            
                                            @if($categories->count() > 0)
                                                <!-- Filtro por Categoria -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                                        <i class="fas fa-filter mr-1"></i>
                                                        Categoria:
                                                    </label>
                                                    <select x-model="filters['{{ $tab->id }}'].category" 
                                                            class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[120px]">
                                                        <option value="all">Todas</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            
                                            @if($datacenters->count() > 0)
                                                <!-- Filtro por Data Center -->
                                                <div class="flex items-center gap-2">
                                                    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                                        <i class="fas fa-server mr-1"></i>
                                                        Data Center:
                                                    </label>
                                                    <select x-model="filters['{{ $tab->id }}'].datacenter" 
                                                            class="text-sm border-gray-300 rounded-md focus:border-purple-500 focus:ring-purple-500 min-w-[120px]">
                                                        <option value="all">Todos</option>
                                                        <option value="none">Sem Data Center</option>
                                                        @foreach($datacenters as $datacenter)
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
                                                <select x-model="filters['{{ $tab->id }}'].sort" 
                                                        class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 min-w-[120px]">
                                                    <option value="name">Alfabética A-Z</option>
                                                    <option value="name_desc">Alfabética Z-A</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Botão Limpar Filtros -->
                                            <button @click="filters['{{ $tab->id }}'].category = 'all'; filters['{{ $tab->id }}'].datacenter = 'all'; filters['{{ $tab->id }}'].sort = 'name';"
                                                    class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                                <i class="fas fa-eraser"></i>
                                                Limpar Filtros
                                            </button>
                                            
                                            <!-- Contador de Sistemas -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                                {{ $tab->cards->count() }} {{ $tab->cards->count() === 1 ? 'sistema' : 'sistemas' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    
                                    @if($tab->cards->count() > 0)
                                        <div class="cards-grid">
                                            @foreach($tab->cards as $card)
                                                <div x-show="shouldShowCard('{{ $card->id }}', '{{ $card->category_id }}', '{{ $card->data_center_id ?? '' }}', '{{ $card->name }}', '{{ $tab->id }}')"
                                                     x-transition:enter="transition ease-out duration-300"
                                                     x-transition:enter-start="opacity-0 transform scale-95"
                                                     x-transition:enter-end="opacity-100 transform scale-100"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 transform scale-100"
                                                     x-transition:leave-end="opacity-0 transform scale-95"
                                                     class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200"
                                                     data-card-id="{{ $card->id }}"
                                                     data-card-name="{{ $card->name }}"
                                                     data-card-category="{{ $card->category_id }}"
                                                     data-tab-id="{{ $tab->id }}">
                                                    <div class="p-6">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <div class="flex items-center">
                                                                @if($card->custom_icon_path)
                                                                    <img src="{{ $card->custom_icon_url }}" alt="{{ $card->name }}" class="w-8 h-8 object-contain">
                                                                @elseif($card->icon)
                                                                    <i class="{{ $card->icon }} text-2xl" style="color: {{ $tab->color }};"></i>
                                                                @else
                                                                    <div class="w-8 h-8 rounded-full" style="background-color: {{ $tab->color }};"></div>
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="flex items-center space-x-2">
                                                                <!-- Estrela de Favorito -->
                                                                @if(auth('web')->check() || auth('system')->check())
                                                                    <button onclick="toggleFavorite({{ $card->id }}, this)" 
                                                                            class="favorite-star {{ in_array($card->id, $favoriteCardIds) ? 'text-yellow-500 hover:text-yellow-600' : 'text-gray-300 hover:text-yellow-400' }} transition-colors duration-200"
                                                                            title="{{ in_array($card->id, $favoriteCardIds) ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                                                                            data-card-id="{{ $card->id }}"
                                                                            data-is-favorite="{{ in_array($card->id, $favoriteCardIds) ? 'true' : 'false' }}">
                                                                        <i class="{{ in_array($card->id, $favoriteCardIds) ? 'fas' : 'far' }} fa-star text-lg"></i>
                                                                    </button>
                                                                @endif
                                                                
                                                                @if($card->monitor_status)
                                                                    <div class="relative tooltip-container">
                                                                        <div class="flex items-center space-x-1 cursor-help">
                                                                            <!-- DEBUG: Status: {{ $card->status }}, Class: {{ $card->status_class }} -->
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
                                                                            <div class="text-xs text-gray-300">Verificado: {{ $card->last_status_check ? $card->last_status_check->format('d/m/Y H:i:s') : 'Nunca' }}</div>
                                                                            <!-- Seta do tooltip -->
                                                                            <div class="tooltip-arrow"></div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($card->description)
                                                                    <div class="relative tooltip-container">
                                                                        <i class="fas fa-info-circle text-gray-400 cursor-help" style="color: {{ $tab->color }};"></i>
                                                                        
                                                                        <!-- Description Tooltip -->
                                                                        <div class="tooltip-description">
                                                                            <div class="font-medium mb-1">Descrição</div>
                                                                            <div class="text-xs text-gray-300">{{ $card->description }}</div>
                                                                            <!-- Seta do tooltip -->
                                                                            <div class="tooltip-arrow"></div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($card->file_path)
                                                                    <a href="{{ Storage::url($card->file_path) }}" target="_blank" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                                        <i class="fas fa-paperclip"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                                            {{ $card->name }}
                                                        </h3>
                                                        
                                                        <div class="flex space-x-2">
                                                            @if($card->monitoring_type === 'ping')
                                                                <!-- Botão Copiar IP para servidores -->
                                                                <button 
                                                                    onclick="copyServerIP('{{ $card->link }}', '{{ $card->name }}')"
                                                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                                    style="background-color: {{ $tab->color }};"
                                                                    title="Copiar IP do servidor"
                                                                >
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            @else
                                                                <!-- Botão Acessar para sites web -->
                                                                <a 
                                                                    href="{{ $card->link }}" 
                                                                    target="_blank" 
                                                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                                    style="background-color: {{ $tab->color }};"
                                                                >
                                                                    <i class="fas fa-external-link-alt mr-2"></i>
                                                                    Acessar
                                                                </a>
                                                            @endif
                                                            @php
                                                                $hasPermission = false;
                                                                try {
                                                                    if (auth()->check() && auth()->user() && auth()->user()->canViewPasswords()) {
                                                                        $hasPermission = true;
                                                                    } elseif (auth()->guard('system')->check() && auth()->guard('system')->user() && auth()->guard('system')->user()->canViewSystem($card->id)) {
                                                                        $hasPermission = true;
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    $hasPermission = false;
                                                                }
                                                            @endphp
                                                            <button 
                                                                onclick="{{ $hasPermission ? 'openLoginsModal(' . $card->id . ', \'' . addslashes($card->name) . '\')' : 'openAccessDeniedModal()' }}" 
                                                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                            >
                                                                <i class="fas fa-key mr-2"></i>
                                                                Logins
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-12">
                                            <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                                            <p class="text-gray-500">Nenhum sistema cadastrado nesta categoria.</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-home text-4xl text-gray-400 mb-4"></i>
                            <h2 class="text-xl font-semibold text-gray-600 mb-2">Nenhuma categoria cadastrada</h2>
                            <p class="text-gray-500">Entre em contato com o administrador para configurar as categorias e sistemas.</p>
                        </div>
                    @endif
                </div>
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

    <!-- Modal de Acesso Negado -->
    <div id="accessDeniedModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="handleAccessDeniedModalClick(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Acesso Negado</h3>
                    <button onclick="closeAccessDeniedModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6 text-center">
                    <i class="fas fa-lock text-4xl text-red-500 mb-4"></i>
                    <p class="text-gray-700 mb-4" id="accessDeniedMessage">
                        {{ auth()->check() ? 'Você não tem permissão para visualizar os logins dos sistemas.' : 'Faça login para ter acesso aos logins dos sistemas.' }}
                    </p>
                    <div class="flex space-x-3 justify-center">
                        <button onclick="closeAccessDeniedModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                            Fechar
                        </button>
                        @if(!auth()->check() && !auth()->guard('system')->check())
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                                Fazer Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($extensionListSvgUrl))
        @include('partials.extension-list-home', ['extensionListSvgUrl' => $extensionListSvgUrl])
    @endif

    <style>
        /* Grid personalizado para cards com altura uniforme */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            align-items: stretch;
        }

        @media (min-width: 768px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .cards-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1280px) {
            .cards-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Forçar altura uniforme dos cards */
        .cards-grid > * {
            height: 100%;
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }

        /* Garantir que o conteúdo interno use toda a altura */
        .cards-grid > * > div {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Título flexível */
        .cards-grid h3 {
            flex-grow: 1;
        }

        /* Botão sempre na base */
        .cards-grid a[href] {
            margin-top: auto;
        }

        /* Estilos para o modal de logins */
        .password-text {
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            user-select: none; /* Previne seleção de texto */
        }

        /* Botão de olho responsivo */
        .eye-button {
            transition: all 0.2s ease-in-out;
            z-index: 10;
        }

        .eye-button:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            padding: 2px;
        }

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
            .login-card {
                padding: 1rem;
            }

            .login-card h5 {
                font-size: 1rem;
            }

            /* Espaçamento reduzido no mobile */
            .login-password-row {
                gap: 0.75rem;
            }
        }

        /* Melhorias no modal */
        .modal-content {
            max-height: 80vh;
            overflow-y: auto;
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
        
        /* Estilos simples para tooltips */
        .group:hover .group-hover\:opacity-100 {
            opacity: 1 !important;
        }
        
        .group:hover .group-hover\:visible {
            visibility: visible !important;
        }
        
        /* Garantir que tooltips apareçam acima de outros elementos */
        .z-50 {
            z-index: 50;
        }
        
        /* Melhorar a aparência dos tooltips */
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Tooltip Styles */
        .tooltip-container {
            position: relative;
        }

        .tooltip-status, .tooltip-description {
            position: absolute;
            background-color: #1f2937;
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            line-height: 1.25rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-in-out, visibility 0.2s ease-in-out;
            z-index: 1000;
            pointer-events: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid #374151;
            bottom: calc(100% + 8px);
            right: 0;
            min-width: 200px;
        }

        .tooltip-description {
            white-space: normal;
            max-width: 300px;
        }

        .tooltip-container:hover .tooltip-status,
        .tooltip-container:hover .tooltip-description {
            opacity: 1;
            visibility: visible;
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
    </style>

    <script>
        // Função para ajustar altura dos cards baseada no conteúdo
        function adjustCardHeights() {
            const cardsGrid = document.querySelector('.cards-grid');
            if (!cardsGrid) return;
            
            const cards = cardsGrid.querySelectorAll('div[class*="bg-white"]');
            if (cards.length === 0) return;
            
            // Resetar alturas para calcular naturalmente
            cards.forEach(card => {
                card.style.height = 'auto';
            });
            
            // Encontrar a altura máxima natural
            let maxHeight = 0;
            cards.forEach(card => {
                const cardHeight = card.offsetHeight;
                if (cardHeight > maxHeight) {
                    maxHeight = cardHeight;
                }
            });
            
            // Aplicar altura uniforme baseada no conteúdo mais alto
            if (maxHeight > 0) {
                cards.forEach(card => {
                    card.style.height = maxHeight + 'px';
                });
            }
        }
        
        // Executar quando a página carregar
        document.addEventListener('DOMContentLoaded', adjustCardHeights);
        
        // Executar quando mudar de aba (para Alpine.js)
        document.addEventListener('alpine:init', () => {
            Alpine.data('tabContent', () => ({
                init() {
                    this.$nextTick(() => {
                        adjustCardHeights();
                    });
                }
            }));
        });
        
        // Executar quando a janela for redimensionada
        window.addEventListener('resize', adjustCardHeights);

        // Funções para o modal de logins
        function openLoginsModal(cardId, cardName) {
            document.getElementById('loginsModalTitle').textContent = `Logins - ${cardName}`;
            document.getElementById('loginsModal').classList.remove('hidden');
            loadSystemUsers(cardId);
        }

        function closeLoginsModal() {
            document.getElementById('loginsModal').classList.add('hidden');
        }

        function handleLoginsModalClick(event) {
            console.log('=== DEBUG: handleLoginsModalClick chamado ===');
            console.log('Event target ID:', event.target.id);
            console.log('Event target class:', event.target.className);
            console.log('Event currentTarget ID:', event.currentTarget.id);
            
            // Fecha o modal se clicar exatamente no backdrop ou no div interno
            if (event.target.id === 'loginsModal' || event.target.classList.contains('flex')) {
                console.log('Fechando modal de logins...');
                closeLoginsModal();
            } else {
                console.log('Clique não foi no backdrop, modal permanece aberto');
            }
        }

        function openAccessDeniedModal() {
            if (!{{ (auth()->check() || auth()->guard('system')->check()) ? 'true' : 'false' }}) {
                document.getElementById('accessDeniedMessage').textContent = 'Faça login para ter acesso aos logins dos sistemas.';
            } else {
                document.getElementById('accessDeniedMessage').textContent = 'Seu usuário não tem permissão para acessar os logins dos sistemas.';
            }
            document.getElementById('accessDeniedModal').classList.remove('hidden');
        }

        function closeAccessDeniedModal() {
            document.getElementById('accessDeniedModal').classList.add('hidden');
        }

        function handleAccessDeniedModalClick(event) {
            console.log('=== DEBUG: handleAccessDeniedModalClick chamado ===');
            console.log('Event target ID:', event.target.id);
            console.log('Event target class:', event.target.className);
            console.log('Event currentTarget ID:', event.currentTarget.id);
            
            // Fecha o modal se clicar exatamente no backdrop ou no div interno
            if (event.target.id === 'accessDeniedModal' || event.target.classList.contains('flex')) {
                console.log('Fechando modal de acesso negado...');
                closeAccessDeniedModal();
            } else {
                console.log('Clique não foi no backdrop, modal permanece aberto');
            }
        }

        function loadSystemUsers(cardId) {
            fetch(`/cards/${cardId}/logins`, {
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
                    if (response.status === 401) {
                        throw new Error('Você precisa estar logado para acessar os logins dos sistemas. Faça login na área administrativa.');
                    }
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.html) {
                        // Resposta da rota web - contém HTML renderizado
                        document.getElementById('loginsModalContent').innerHTML = data.html;
                    } else if (data.success) {
                        // Fallback para resposta da API (se ainda existir)
                        displaySystemLogins(data.systemLogins, data.cardName);
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

        function displaySystemLogins(systemLogins, cardName) {
            if (systemLogins.length === 0) {
                document.getElementById('loginsModalContent').innerHTML = `
                    <div class="text-center text-gray-600">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>Nenhum login cadastrado para o sistema <strong>${cardName}</strong>.</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="mb-4">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Logins do Sistema: ${cardName}</h4>
                </div>
                <div class="space-y-3">
            `;

            systemLogins.forEach((login, index) => {
                html += `
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200 login-card">
                        <!-- Cabeçalho do Card -->
                        <div class="flex items-center justify-between mb-3">
                            <h5 class="font-semibold text-gray-900 text-lg">${login.title}</h5>
                        </div>
                        
                        <!-- Login e Senha na mesma linha -->
                        <div class="flex items-center space-x-6 login-password-row">
                            <!-- Campo Login -->
                            <div class="flex items-center space-x-2 flex-1">
                                <label class="text-sm font-medium text-gray-700 w-16">Login:</label>
                                <div class="flex-1 bg-white border border-gray-300 rounded px-4 py-2 text-sm font-mono">
                                    ${login.username}
                                </div>
                                <button onclick="copyToClipboard('${login.username}', 'username', ${index})" 
                                        id="copy-username-${index}"
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200 copy-button"
                                        title="Copiar login">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            
                            <!-- Campo Senha -->
                            <div class="flex items-center space-x-2 flex-1">
                                <label class="text-sm font-medium text-gray-700 w-16">Senha:</label>
                                <div class="flex-1 bg-white border border-gray-300 rounded px-4 py-2 text-sm font-mono relative">
                                    <span id="password-${index}" class="password-text" style="color: #6B7280;">••••••••</span>
                                    <button onclick="togglePasswordVisibility(${index}, '${login.password}')" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors duration-200 eye-button"
                                            title="Mostrar/Ocultar senha">
                                        <i class="fas fa-eye" id="eye-icon-${index}" style="color: #6B7280;"></i>
                                    </button>
                                </div>
                                <button onclick="copyToClipboard('${login.password}', 'password', ${index})" 
                                        id="copy-password-${index}"
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200 copy-button"
                                        title="Copiar senha">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            document.getElementById('loginsModalContent').innerHTML = html;
        }

        // Função para mostrar/ocultar a senha
        function togglePasswordVisibility(index, password) {
            const passwordText = document.getElementById(`password-${index}`);
            const eyeIcon = document.getElementById(`eye-icon-${index}`);

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

        function copyToClipboard(text, type, index) {
            // Usar fallback para navegadores mais antigos
            if (navigator.clipboard && window.isSecureContext) {
                // Método moderno
                navigator.clipboard.writeText(text).then(function() {
                    showCopyFeedback(type, index);
                }).catch(function(err) {
                    console.error('Erro ao copiar: ', err);
                    fallbackCopyTextToClipboard(text, type, index);
                });
            } else {
                // Fallback para navegadores antigos
                fallbackCopyTextToClipboard(text, type, index);
            }
        }

        // Função fallback para copiar texto
        function fallbackCopyTextToClipboard(text, type, index) {
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
                    showCopyFeedback(type, index);
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
        function showCopyFeedback(type, index) {
            const button = document.getElementById(`copy-${type}-${index}`);
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


        // Função para copiar IP do servidor
        function copyServerIP(ip, serverName) {
            console.log('=== INÍCIO DA FUNÇÃO copyServerIP ===');
            console.log('IP recebido:', ip);
            console.log('Nome do servidor:', serverName);
            
            // Limpar o IP removendo protocolos se existirem
            let cleanIP = ip.replace(/^https?:\/\//, '').replace(/\/.*$/, '');
            console.log('IP limpo:', cleanIP);
            
            // Tentar usar a API moderna primeiro
            if (navigator.clipboard && window.isSecureContext) {
                console.log('Usando Clipboard API moderna');
                navigator.clipboard.writeText(cleanIP).then(function() {
                    console.log('✅ IP copiado com sucesso via clipboard API');
                    showCopySuccess(cleanIP, serverName);
                }).catch(function(err) {
                    console.error('❌ Erro na clipboard API:', err);
                    fallbackCopy(cleanIP, serverName);
                });
            } else {
                console.log('Usando fallback para cópia');
                fallbackCopy(cleanIP, serverName);
            }
            console.log('=== FIM DA FUNÇÃO copyServerIP ===');
        }

        // Função fallback para navegadores mais antigos
        function fallbackCopy(text, serverName) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    console.log('IP copiado com sucesso via fallback');
                    showCopySuccess(text, serverName);
                } else {
                    console.error('Falha ao copiar via fallback');
                    alert('Erro ao copiar IP. Tente selecionar e copiar manualmente: ' + text);
                }
            } catch (err) {
                console.error('Erro no fallback:', err);
                alert('Erro ao copiar IP. Tente selecionar e copiar manualmente: ' + text);
            }
            
            document.body.removeChild(textArea);
        }

        // Função para mostrar feedback de cópia bem-sucedida
        function showCopySuccess(ip, serverName) {
            // Criar toast de sucesso
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2';
            toast.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>IP <strong>${ip}</strong> copiado!</span>
            `;
            
            document.body.appendChild(toast);
            
            // Remover após 3 segundos
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Código antigo removido - agora usando onclick nos elementos

        // ===============================
        // SISTEMA DE FAVORITOS
        // ===============================

        /**
         * Alternar favorito (adicionar/remover)
         */
        function toggleFavorite(cardId, buttonElement) {
            const isCurrentlyFavorite = buttonElement.dataset.isFavorite === 'true';
            const icon = buttonElement.querySelector('i');
            
            // Feedback visual imediato
            buttonElement.disabled = true;
            buttonElement.style.opacity = '0.6';
            
            // Fazer requisição AJAX
            fetch(`/favorites/${cardId}/toggle`, {
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
                    // Atualizar estado do botão
                    const newIsFavorite = data.is_favorite;
                    buttonElement.dataset.isFavorite = newIsFavorite ? 'true' : 'false';
                    
                    // Atualizar aparência
                    if (newIsFavorite) {
                        // Adicionar aos favoritos
                        icon.className = 'fas fa-star text-lg';
                        buttonElement.className = 'favorite-star text-yellow-500 hover:text-yellow-600 transition-colors duration-200';
                        buttonElement.title = 'Remover dos favoritos';
                        
                        // Animação de sucesso
                        buttonElement.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            buttonElement.style.transform = 'scale(1)';
                        }, 200);
                        
                    } else {
                        // Remover dos favoritos
                        icon.className = 'far fa-star text-lg';
                        buttonElement.className = 'favorite-star text-gray-300 hover:text-yellow-400 transition-colors duration-200';
                        buttonElement.title = 'Adicionar aos favoritos';
                    }
                    
                    // Mostrar notificação toast
                    if (typeof showSuccessToast === 'function') {
                        showSuccessToast(data.message, 3000);
                    }
                    
                    // Se estivermos na aba de favoritos e removemos um favorito, recarregar a página
                    if (!newIsFavorite && window.location.pathname === '/' && getActiveTab() === 'favorites') {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                    
                    // Se adicionamos um favorito e não há aba de favoritos, recarregar para criar a aba
                    if (newIsFavorite && !document.querySelector('[data-tab="favorites"]')) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                    
                } else {
                    console.error('Erro ao alterar favorito:', data.message);
                    
                    // Mostrar erro
                    if (typeof showErrorToast === 'function') {
                        showErrorToast(data.message || 'Erro ao alterar favorito', 4000);
                    } else {
                        alert(data.message || 'Erro ao alterar favorito');
                    }
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                
                // Mostrar erro
                if (typeof showErrorToast === 'function') {
                    showErrorToast('Erro de conexão. Tente novamente.', 4000);
                } else {
                    alert('Erro de conexão. Tente novamente.');
                }
            })
            .finally(() => {
                // Restaurar estado do botão
                buttonElement.disabled = false;
                buttonElement.style.opacity = '1';
            });
        }

        /**
         * Obter aba ativa atual
         */
        function getActiveTab() {
            const activeButton = document.querySelector('.tab-button.tab-active');
            if (activeButton) {
                return activeButton.getAttribute('data-tab') || 'unknown';
            }
            return 'unknown';
        }

        /**
         * Inicializar estrelas de favoritos ao carregar a página
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar estado inicial dos favoritos
            const favoriteButtons = document.querySelectorAll('.favorite-star');
            
            favoriteButtons.forEach(button => {
                const cardId = button.dataset.cardId;
                const isFavorite = button.dataset.isFavorite === 'true';
                
                // Log para debug
                console.log(`Card ${cardId}: ${isFavorite ? 'É favorito' : 'Não é favorito'}`);
            });
            
            console.log('Sistema de favoritos inicializado com sucesso!');
        });

        // Código antigo removido - agora usando onclick nos elementos
    </script>
    
    <style>
        /* SISTEMA DE ABAS REFEITO - CSS OTIMIZADO */
        
        /* Prevenir APENAS scroll horizontal */
        body, html {
            overflow-x: hidden !important;
            overflow-y: auto !important;
        }
        
        
        /* Botões das abas */
        .tab-button {
            position: relative;
            border-radius: 8px 8px 0 0;
            transition: all 0.2s ease-in-out;
        }
        
        .tab-button:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }
        
        .tab-active {
            border-bottom: 3px solid;
            font-weight: 600;
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