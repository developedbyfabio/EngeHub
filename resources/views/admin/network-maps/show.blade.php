@extends('layouts.app')

@php
    $filiaisMode = $filiaisMode ?? false;
    $canEditDevicesEffective = $canEditDevices ?? true;
@endphp

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
            {{ $filiaisMode ? 'Filiais' : $network_map->name }}
        </h2>
        <div class="flex flex-wrap items-center justify-end gap-2">
            @if($filiaisMode && $svgContent)
                <button type="button" id="filiaisOpenFullscreenBtn" class="inline-flex items-center justify-center gap-2 rounded-md border-2 border-gray-800 bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                    <i class="fas fa-expand-alt"></i> Tela cheia
                </button>
            @endif
            @if($filiaisMode)
                <a href="{{ route('admin.network-maps.index') }}" class="inline-flex items-center px-4 py-2 btn-engehub-yellow border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition">
                    <i class="fas fa-cog mr-2"></i> Gerenciar Mapas de Rede
                </a>
            @else
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
            if (fromFs) url += '&fs=1';
            window.location.href = url;
        };
    </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($filiaisMode && isset($maps) && $maps->count() > 1)
                        <div class="mb-6 flex min-w-0 flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end sm:gap-4">
                            @include('filiais.partials.map-select-filter', [
                                'maps' => $maps,
                                'selectedMapId' => $network_map->id,
                                'prefix' => '',
                                'compact' => false,
                                'context' => 'main',
                            ])
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Arquivo</div>
                            <div class="font-medium text-gray-900">{{ $network_map->file_name }}</div>
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
                            @if(!$filiaisMode)
                                <form action="{{ route('admin.network-maps.resync-devices', $network_map) }}" method="POST" class="inline" onsubmit="return confirm('Varredura do SVG e sincronização de dispositivos?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-200 border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-300 transition">
                                        <i class="fas fa-sync-alt mr-1"></i> Revarrear dispositivos
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if($svgContent)
                        @if($filiaisMode)
                            <div id="filiaisMapOriginalParent">
                        @endif
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
                            <p class="text-gray-600">Arquivo SVG não encontrado em: {{ $network_map->file_path }}{{ $network_map->file_name }}</p>
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
                        <i class="fas fa-map-marked-alt mr-1.5 text-amber-600 sm:mr-2"></i> Filiais — tela cheia
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
