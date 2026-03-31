@extends('layouts.app')

@php
    $filiaisMode = $filiaisMode ?? false;
    $canEditDevicesEffective = $canEditDevices ?? true;
    $mapActiveFloor = $mapActiveFloor ?? 1;
    $deviceCountsByType = $deviceCountsByType ?? [];
@endphp

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
            {{ $filiaisMode ? 'Mapas de Rede' : $network_map->name }}
        </h2>
        <div class="flex flex-wrap items-center justify-end gap-2">
            @if($filiaisMode && $svgContent)
                <button type="button" id="filiaisOpenFullscreenBtn" class="inline-flex items-center justify-center gap-2 rounded-md border-2 border-gray-800 bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                    <i class="fas fa-expand-alt"></i> Tela cheia
                </button>
            @endif
            @if($filiaisMode && auth()->guard('web')->check() && auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_NETWORK_MAPS))
                <a href="{{ route('admin.network-maps.index') }}" class="inline-flex items-center px-4 py-2 btn-engehub-yellow border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition">
                    <i class="fas fa-cog mr-2"></i> Gerenciar Mapas de Rede
                </a>
            @elseif(!$filiaisMode)
                <a href="{{ route('admin.network-maps.edit', $network_map) }}" class="inline-flex items-center px-4 py-2 btn-engehub-yellow border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <a href="{{ route('admin.network-maps.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    @if($filiaisMode)
    <script>
        window.filiaisNavigateMap = function (selectEl) {
            if (!selectEl || selectEl.value === '') return;
            var fromFs = selectEl.getAttribute('data-filiais-context') === 'fullscreen';
            var url = @json(route('filiais.index')) + '?map=' + encodeURIComponent(selectEl.value);
            var cur = new URLSearchParams(window.location.search);
            var fl = cur.get('floor');
            if (fl === '1' || fl === '2') url += '&floor=' + encodeURIComponent(fl);
            if (fromFs) url += '&fs=1';
            window.location.href = url;
        };
    </script>
    @endif

    <div class="{{ $filiaisMode ? 'py-6 sm:py-8' : 'py-12' }}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="{{ $filiaisMode ? 'p-4 sm:p-5' : 'p-6' }}">
                    @if($filiaisMode && isset($maps) && $maps->count() > 1)
                        <div class="mb-3 flex min-w-0 flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-end sm:gap-3">
                            @include('filiais.partials.map-select-filter', [
                                'maps' => $maps,
                                'selectedMapId' => $network_map->id,
                                'prefix' => '',
                                'compact' => false,
                                'context' => 'main',
                            ])
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @if($filiaisMode)
                            @php
                                $hasAnyTypeCount = false;
                                foreach (\App\Models\Device::MAP_LAYER_TYPE_ORDER as $_t) {
                                    if (($deviceCountsByType[$_t] ?? 0) > 0) {
                                        $hasAnyTypeCount = true;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="bg-gray-50 rounded-lg border border-gray-100 px-3 py-2 flex min-h-0 items-center md:min-h-[2.75rem]">
                                <div class="text-sm font-semibold text-gray-900 leading-snug">Mapa de Rede Filial: {{ $network_map->name }}</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg border border-gray-100 px-3 py-2 flex min-h-0 flex-wrap items-center justify-between gap-2 md:min-h-[2.75rem]">
                                <span class="text-sm font-medium text-gray-800">Itens no Mapa</span>
                                <button type="button" id="filiaisOpenItemsMapModalBtn" class="inline-flex shrink-0 items-center gap-1.5 rounded-md border border-gray-300 bg-white px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-gray-800 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1">
                                    <i class="fas fa-expand text-[11px]" aria-hidden="true"></i>
                                    Expandir
                                </button>
                            </div>
                            <div class="bg-gray-50 rounded-lg border border-gray-100 px-3 py-2 flex min-h-0 flex-wrap items-center justify-between gap-2 md:min-h-[2.75rem]">
                                <span class="text-xs font-medium text-gray-500">Pontos clicáveis no mapa</span>
                                <span class="text-xl font-bold tabular-nums text-gray-900 md:text-2xl" id="deviceCount">{{ $network_map->devices->count() }}</span>
                            </div>

                            <div id="filiaisItemsMapModal" class="fixed inset-0 z-[190] hidden items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true" aria-labelledby="filiaisItemsMapModalTitle">
                                <div class="max-h-[min(32rem,85vh)] w-full max-w-md overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xl">
                                    <div class="flex items-start justify-between gap-3 border-b border-gray-200 bg-gray-50 px-4 py-3">
                                        <h3 id="filiaisItemsMapModalTitle" class="text-sm font-bold text-gray-900 leading-tight sm:text-base">
                                            <i class="fas fa-layer-group mr-2 text-amber-600" aria-hidden="true"></i>
                                            Itens no mapa — {{ $network_map->name }}
                                        </h3>
                                        <button type="button" id="filiaisCloseItemsMapModalBtn" class="rounded-md p-1.5 text-gray-500 hover:bg-gray-200 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500" aria-label="Fechar">
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="max-h-[min(24rem,70vh)] overflow-y-auto px-4 py-3">
                                        @if($hasAnyTypeCount)
                                            <p class="mb-3 text-xs text-gray-500">Quantidade de itens clicáveis por tipo neste mapa.</p>
                                            <ul class="divide-y divide-gray-100 text-sm text-gray-800">
                                                @foreach(\App\Models\Device::MAP_LAYER_TYPE_ORDER as $type)
                                                    @php $cnt = (int) ($deviceCountsByType[$type] ?? 0); @endphp
                                                    @if($cnt > 0)
                                                        <li class="flex justify-between gap-4 py-2.5">
                                                            <span>{{ \App\Models\Device::mapLayerTypeLabel($type) }}</span>
                                                            <span class="shrink-0 font-semibold tabular-nums text-gray-900">{{ $cnt }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            <div class="mt-4 flex justify-between border-t border-gray-100 pt-3 text-sm font-semibold text-gray-900">
                                                <span>Total</span>
                                                <span class="tabular-nums">{{ $network_map->devices->count() }}</span>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-600">Nenhum ponto sincronizado neste mapa. Um administrador pode revarrer os SVGs em <strong>Gerenciar Mapas de Rede</strong>.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <script>
                            (function() {
                                var modal = document.getElementById('filiaisItemsMapModal');
                                var openBtn = document.getElementById('filiaisOpenItemsMapModalBtn');
                                var closeBtn = document.getElementById('filiaisCloseItemsMapModalBtn');
                                if (!modal || !openBtn || !closeBtn) return;
                                function openModal() {
                                    modal.classList.remove('hidden');
                                    modal.classList.add('flex');
                                    document.body.classList.add('overflow-hidden');
                                    closeBtn.focus();
                                }
                                function closeModal() {
                                    modal.classList.add('hidden');
                                    modal.classList.remove('flex');
                                    document.body.classList.remove('overflow-hidden');
                                    openBtn.focus();
                                }
                                openBtn.addEventListener('click', openModal);
                                closeBtn.addEventListener('click', closeModal);
                                modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
                                document.addEventListener('keydown', function(e) {
                                    if (e.key === 'Escape' && modal.classList.contains('flex')) closeModal();
                                });
                            })();
                            </script>
                        @else
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-500">{{ $network_map->has_two_floors ? 'Arquivos (andares)' : 'Arquivo' }}</div>
                                <div class="font-medium text-gray-900 break-all">{{ $network_map->file_name }}</div>
                                @if($network_map->has_two_floors)
                                    <div class="mt-2 text-xs text-gray-500">1º andar · SVG acima</div>
                                    <div class="font-medium text-gray-900 break-all mt-1">{{ $network_map->file_name_floor2 ?: '—' }}</div>
                                    @if($network_map->file_name_floor2)
                                        <div class="text-xs text-gray-500 mt-0.5">2º andar</div>
                                    @endif
                                @endif
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-500">Status</div>
                                @if($network_map->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inativo</span>
                                @endif
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between gap-2">
                                <div>
                                    <div class="text-sm text-gray-500">Dispositivos no mapa</div>
                                    <div class="font-medium text-gray-900" id="deviceCount">{{ $network_map->devices->count() }}</div>
                                </div>
                                <form action="{{ route('admin.network-maps.resync-devices', $network_map) }}" method="POST" class="inline" onsubmit="return confirm('Varredura do SVG e sincronização de dispositivos?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-200 border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-300 transition">
                                        <i class="fas fa-sync-alt mr-1"></i> Revarrear dispositivos
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    @if($svgContent)
                        @if($filiaisMode)
                            <div id="filiaisMapOriginalParent">
                        @endif
                        <div class="mapa-rede-forcelight border-2 border-gray-300 rounded-lg overflow-hidden shadow-inner relative" style="height: 70vh; background: #ffffff !important;">
                            @include('admin.network-maps.partials.map-layer-filters')
                            <div class="absolute top-3 right-3 z-40 flex flex-wrap items-center justify-end gap-2 max-w-[calc(100%-1rem)] pointer-events-auto" role="toolbar" aria-label="Controles do mapa">
                                <div id="mapTutorialSearchWrap" class="flex flex-wrap items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="search" aria-label="Buscar colaborador no mapa">
                                    <input type="search" id="mapCollaboratorSearch" name="map_collaborator_search" placeholder="Buscar colaborador…" autocomplete="off" class="text-sm rounded-md border border-gray-300 px-2 py-1.5 w-36 sm:w-44 min-w-0 bg-white text-gray-900 shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                    <div id="mapSearchNav" class="hidden flex items-center gap-0.5 shrink-0">
                                        <button type="button" id="mapSearchPrev" class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-800 text-lg font-semibold leading-none" title="Resultado anterior">&lsaquo;</button>
                                        <span id="mapSearchStatus" class="text-xs text-gray-800 font-semibold px-1 min-w-[5.5rem] text-center tabular-nums"></span>
                                        <button type="button" id="mapSearchNext" class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-800 text-lg font-semibold leading-none" title="Próximo resultado">&rsaquo;</button>
                                    </div>
                                    <span id="mapSearchFeedback" class="hidden text-xs text-amber-800 max-w-[12rem] leading-tight"></span>
                                </div>
                                @if($network_map->has_two_floors)
                                    <div id="mapFloorToolbar" class="flex shrink-0 flex-wrap items-center gap-1 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="group" aria-label="Andar do mapa">
                                        <button type="button" data-map-floor="1" class="map-floor-btn whitespace-nowrap rounded-md border px-2.5 py-1.5 text-xs sm:text-sm font-semibold transition-colors border-gray-200 bg-gray-50 text-gray-800 hover:bg-gray-100">
                                            1º Andar
                                        </button>
                                        <button type="button" data-map-floor="2" class="map-floor-btn whitespace-nowrap rounded-md border px-2.5 py-1.5 text-xs sm:text-sm font-semibold transition-colors border-gray-200 bg-gray-50 text-gray-800 hover:bg-gray-100 {{ ! $network_map->fileExistsFloor2() ? 'opacity-75' : '' }}">
                                            2º Andar
                                        </button>
                                    </div>
                                @endif
                                <div id="mapTutorialLabelToggles" class="flex items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="group" aria-label="Rótulos no mapa">
                                    <button type="button" id="mapShowCodes" class="px-2.5 py-1.5 rounded-md text-sm font-medium border border-gray-400 btn-engehub-yellow transition-colors">Códigos</button>
                                    <button type="button" id="mapShowNames" class="px-2.5 py-1.5 rounded-md text-sm font-medium border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">Nomes</button>
                                </div>
                                <div id="mapTutorialZoomControls" class="flex items-center gap-1.5 rounded-lg bg-white/95 shadow-lg border border-gray-200 p-1.5 backdrop-blur-sm" role="group" aria-label="Zoom do mapa">
                                    <button type="button" id="mapZoomOut" class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-lg transition-colors" title="Diminuir zoom">−</button>
                                    <span id="zoomLevel" class="text-sm font-semibold text-gray-700 min-w-[3rem] text-center px-1">100%</span>
                                    <button type="button" id="mapZoomIn" class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-lg transition-colors" title="Aumentar zoom">+</button>
                                    <button type="button" id="mapZoomReset" class="px-2.5 py-1.5 text-sm font-medium rounded-md btn-engehub-yellow transition-colors" title="Resetar zoom">Reset</button>
                                </div>
                            </div>
                            <div id="mapaContainer" class="relative z-0 w-full h-full overflow-hidden cursor-grab" style="touch-action: none; background: #ffffff !important;">
                                <div id="svgWrapper" class="mapa-rede-svg-wrapper inline-block p-4">
                                    <div id="svgContainer" class="svg-map-theme" style="background: #ffffff !important;">
                                        {!! $svgContent !!}
                                    </div>
                                </div>
                            </div>
                            @include('admin.network-maps.partials.device-side-panel')
                        </div>
                        @if($filiaisMode)
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">
                                @if($mapActiveFloor === 2 && $network_map->has_two_floors)
                                    SVG do 2º andar não encontrado{{ $network_map->file_name_floor2 ? ' em: '.$network_map->file_path.$network_map->file_name_floor2 : '' }}.
                                @else
                                    Arquivo SVG não encontrado em: {{ $network_map->file_path }}{{ $network_map->file_name }}
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 mt-2">Faça upload novamente na edição do mapa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($filiaisMode && $svgContent)
        <div id="filiaisFullscreenModal" class="filiais-fs-modal fixed inset-0 z-[200] items-center justify-center bg-black/55 p-3 sm:p-5 md:p-6" role="dialog" aria-modal="true" aria-labelledby="filiaisFullscreenTitle">
            <div class="flex max-h-[calc(100dvh-1.5rem)] w-full max-w-[calc(100vw-1.5rem)] flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
                <div class="flex w-full flex-wrap items-center gap-x-2 gap-y-2 border-b border-gray-200 bg-gray-50 px-3 py-2 sm:px-4">
                    <h2 id="filiaisFullscreenTitle" class="flex flex-shrink-0 items-center text-sm font-bold text-gray-900 sm:text-base">
                        <i class="fas fa-map-marked-alt mr-1.5 text-amber-600 sm:mr-2"></i> Mapas de Rede — tela cheia
                    </h2>
                    @if(isset($maps) && $maps->count() > 1)
                        <div class="flex min-w-0 flex-1 flex-wrap items-center justify-center px-1">
                            @include('filiais.partials.map-select-filter', ['maps' => $maps, 'selectedMapId' => $network_map->id, 'prefix' => 'fs_', 'compact' => true, 'context' => 'fullscreen'])
                        </div>
                    @else
                        <div class="min-w-0 flex-1"></div>
                    @endif
                    <div class="flex flex-shrink-0 items-center sm:ml-auto">
                        <button type="button" id="filiaisCloseFullscreenBtn" class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-gray-800 sm:px-4">
                            <i class="fas fa-times"></i> Fechar
                        </button>
                    </div>
                </div>
                <div id="filiaisFullscreenMapHost" class="filiais-fs-map-host min-h-0 flex-1 overflow-hidden bg-white p-2 sm:p-3"></div>
            </div>
        </div>
    @endif

    @include('admin.network-maps.partials.device-modals', ['canEditDevicesEffective' => $canEditDevicesEffective])

    <style>
        .btn-engehub-yellow { background-color: #E9B32C !important; color: #000 !important; }
        .btn-engehub-yellow:hover { background-color: #d19d20 !important; }
        .device-modal { align-items: center; justify-content: center; }
        #deviceSeatEditModal .relative.bg-white { max-height: 85vh; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
        .mapa-rede-forcelight { color-scheme: dark !important; background: #ffffff !important; isolation: isolate; contain: layout style; }
        .mapa-rede-forcelight #mapaContainer, .mapa-rede-forcelight #svgContainer, .mapa-rede-forcelight #svgWrapper { background: #ffffff !important; }
        .mapa-rede-forcelight .svg-map-theme svg { background-color: #ffffff !important; }
        .mapa-rede-forcelight .svg-map-theme [data-code].device { fill: #000000 !important; color: #000000 !important; }
        .mapa-rede-forcelight .svg-map-theme foreignObject [data-code].device { color: #000000 !important; }
        @media (prefers-color-scheme: light) {
            .mapa-rede-forcelight, .mapa-rede-forcelight * { color-scheme: dark !important; }
            .mapa-rede-forcelight .svg-map-theme svg { background: #ffffff !important; background-color: #ffffff !important; }
        }
        .svg-map-theme [data-code].device { cursor: pointer !important; }
        .svg-map-theme [data-code].device:hover { fill: #b45309 !important; }
        .svg-map-theme foreignObject [data-code].device:hover { color: #b45309 !important; }
        .svg-map-theme .device.device-search-highlight {
            filter: drop-shadow(0 0 1px #E9B32C) drop-shadow(0 0 3px rgba(233, 179, 44, 0.65));
            transition: filter 0.25s ease;
        }
        .svg-map-theme foreignObject .device.device-search-highlight {
            box-shadow: 0 0 0 2px #E9B32C, 0 0 12px rgba(233, 179, 44, 0.5);
            transition: box-shadow 0.25s ease;
        }
        .svg-map-theme .device.map-layer-filter-hidden {
            visibility: hidden !important;
            pointer-events: none !important;
        }
        .svg-map-theme foreignObject .device.map-layer-filter-hidden {
            visibility: hidden !important;
            pointer-events: none !important;
        }
        #mapaContainer svg {
            max-width: none !important;
            height: auto !important;
            text-rendering: geometricPrecision;
            shape-rendering: geometricPrecision;
        }
        #mapaContainer svg text,
        #mapaContainer svg tspan { text-rendering: geometricPrecision; }
        #mapaContainer svg image {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        #svgWrapper.mapa-rede-svg-wrapper { transform-origin: 0 0; }
        .filiais-fs-map-host { display: flex; flex-direction: column; }
        .mapa-rede-forcelight.filiais-map-fullscreen-panel { flex: 1; min-height: 0; height: 100% !important; }
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
    </style>

    @if($svgContent)
    <script>
    @include('admin.network-maps.partials.network-map-devices-script', [
        'network_map' => $network_map,
        'filiaisMode' => $filiaisMode ?? false,
        'canEditDevicesEffective' => $canEditDevicesEffective,
        'deviceLabels' => $deviceLabels ?? [],
    ])
    </script>
    @if($network_map->has_two_floors)
    <script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            var floorBar = document.getElementById('mapFloorToolbar');
            var svgHost = document.getElementById('svgContainer');
            if (!floorBar || !svgHost) return;
            var svgJsonUrl = @json($filiaisMode ? route('filiais.network-maps.svg-floor', $network_map) : route('admin.network-maps.svg-floor', $network_map));
            var activeFloor = {{ (int) $mapActiveFloor }};
            var busy = false;
            function applyFloorButtonStyles(flActive) {
                [].forEach.call(floorBar.querySelectorAll('.map-floor-btn[data-map-floor]'), function(btn) {
                    var fl = parseInt(btn.getAttribute('data-map-floor'), 10);
                    var on = fl === flActive;
                    btn.setAttribute('aria-pressed', on ? 'true' : 'false');
                    if (on) {
                        btn.classList.add('btn-engehub-yellow', 'shadow-sm', 'border-amber-400/50');
                        btn.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-800', 'hover:bg-gray-100');
                    } else {
                        btn.classList.remove('btn-engehub-yellow', 'shadow-sm', 'border-amber-400/50');
                        btn.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-800', 'hover:bg-gray-100');
                    }
                });
            }
            function syncUrlFloor(f) {
                try {
                    var u = new URL(window.location.href);
                    u.searchParams.set('floor', String(f));
                    window.history.replaceState({}, '', u.pathname + u.search + u.hash);
                } catch (e) { /* ignore */ }
            }
            function notifyErr(msg) {
                var m = (msg && String(msg).trim()) ? String(msg).trim() : 'Não foi possível carregar o andar.';
                if (typeof window.showToast === 'function') {
                    window.showToast(m, 'error', 6000);
                } else {
                    alert(m);
                }
            }
            function setBusy(v) {
                busy = v;
                [].forEach.call(floorBar.querySelectorAll('.map-floor-btn'), function(b) { b.disabled = !!v; });
            }
            function loadFloor(f) {
                if (busy || (f !== 1 && f !== 2) || f === activeFloor) return;
                setBusy(true);
                var reqUrl = new URL(svgJsonUrl);
                reqUrl.searchParams.set('floor', String(f));
                fetch(reqUrl.toString(), {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).then(function(r) {
                    return r.json().then(function(data) { return { ok: r.ok, data: data }; });
                }).then(function(res) {
                    setBusy(false);
                    if (!res.ok || !res.data.success) {
                        notifyErr(res.data && res.data.message);
                        return;
                    }
                    svgHost.innerHTML = res.data.svg;
                    activeFloor = parseInt(res.data.floor, 10) || f;
                    applyFloorButtonStyles(activeFloor);
                    syncUrlFloor(activeFloor);
                    if (typeof window.__enghubMapRebindAfterSvgSwap === 'function') {
                        window.__enghubMapRebindAfterSvgSwap();
                    }
                }).catch(function() {
                    setBusy(false);
                    notifyErr('Erro de rede ao carregar o mapa.');
                });
            }
            floorBar.addEventListener('click', function(e) {
                var btn = e.target.closest('.map-floor-btn[data-map-floor]');
                if (!btn || btn.disabled) return;
                e.preventDefault();
                loadFloor(parseInt(btn.getAttribute('data-map-floor'), 10));
            });
            applyFloorButtonStyles(activeFloor);
        });
    })();
    </script>
    @endif
    @include('admin.network-maps.partials.network-map-tutorial')
    @endif

    @if($filiaisMode && $svgContent)
    <script>
    (function() {
        var modal = document.getElementById('filiaisFullscreenModal');
        var host = document.getElementById('filiaisFullscreenMapHost');
        var parent = document.getElementById('filiaisMapOriginalParent');
        var openBtn = document.getElementById('filiaisOpenFullscreenBtn');
        var closeBtn = document.getElementById('filiaisCloseFullscreenBtn');
        if (!modal || !host || !parent || !openBtn || !closeBtn) return;

        function getMapPanel() {
            return parent.querySelector('.mapa-rede-forcelight') || host.querySelector('.mapa-rede-forcelight');
        }
        function openFiliaisMapFullscreen() {
            var mapEl = getMapPanel();
            if (!mapEl) return;
            host.appendChild(mapEl);
            mapEl.classList.add('filiais-map-fullscreen-panel');
            modal.classList.add('filiais-fs-open');
            document.body.classList.add('overflow-hidden');
        }
        function closeFiliaisMapFullscreen() {
            var mapEl = getMapPanel();
            if (!mapEl) return;
            parent.appendChild(mapEl);
            mapEl.classList.remove('filiais-map-fullscreen-panel');
            modal.classList.remove('filiais-fs-open');
            document.body.classList.remove('overflow-hidden');
        }
        openBtn.addEventListener('click', openFiliaisMapFullscreen);
        closeBtn.addEventListener('click', closeFiliaisMapFullscreen);
        modal.addEventListener('click', function(e) { if (e.target === modal) closeFiliaisMapFullscreen(); });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('filiais-fs-open')) closeFiliaisMapFullscreen();
        });
        document.addEventListener('DOMContentLoaded', function() {
            var params = new URLSearchParams(window.location.search);
            if (params.get('fs') === '1') {
                requestAnimationFrame(function() {
                    openFiliaisMapFullscreen();
                    params.delete('fs');
                    var qs = params.toString();
                    window.history.replaceState({}, '', window.location.pathname + (qs ? '?' + qs : '') + window.location.hash);
                });
            }
        });
        window.openFiliaisMapFullscreen = openFiliaisMapFullscreen;
        window.closeFiliaisMapFullscreen = closeFiliaisMapFullscreen;
    })();
    </script>
    @endif
@endsection
