{{-- Controles de camadas no canto superior esquerdo do mapa (.mapa-rede-forcelight deve ser position: relative). --}}
<div class="absolute top-3 left-3 z-30 pointer-events-auto">
    <button type="button" id="mapFilterToggleBtn" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white/95 shadow-lg backdrop-blur-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1" title="Filtros do mapa" aria-expanded="false" aria-controls="mapFilterPanel">
        <i class="fas fa-filter text-gray-700" aria-hidden="true"></i>
    </button>
</div>
<div id="mapFilterPanel" class="hidden absolute left-3 top-1/2 z-40 max-h-[min(28rem,calc(100%-6rem))] w-[17.5rem] max-w-[calc(100%-2rem)] -translate-y-1/2 overflow-y-auto rounded-xl border border-gray-200 bg-white/95 p-4 shadow-xl backdrop-blur-sm pointer-events-auto" role="dialog" aria-modal="true" aria-labelledby="mapFilterPanelTitle">
    <div class="relative flex items-start justify-between gap-2 mb-2">
        <h3 id="mapFilterPanelTitle" class="text-sm font-semibold text-gray-900 leading-tight">Camadas do mapa</h3>
        <button type="button" id="mapFilterPanelClose" class="-mr-1 -mt-1 shrink-0 rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500" aria-label="Fechar filtros">
            <i class="fas fa-times text-sm" aria-hidden="true"></i>
        </button>
    </div>
    <p class="text-xs text-gray-500 mb-3 leading-snug">Marque ou desmarque para mostrar ou ocultar cada tipo de elemento. A alteração é imediata.</p>
    <ul class="space-y-2.5 text-sm text-gray-800">
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="OUTLET" checked><span>Pontos (tomadas)</span></label></li>
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="SEAT" checked><span>Mesas</span></label></li>
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="PRINTER" checked><span>Impressoras</span></label></li>
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="SCAN" checked><span>Scanners</span></label></li>
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="TV" checked><span>TVs</span></label></li>
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="PHONE" checked><span>Telefones</span></label></li>
        <li><label class="flex items-center gap-2.5 cursor-pointer select-none"><input type="checkbox" class="map-filter-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" data-filter-type="AP" checked><span>Access points</span></label></li>
    </ul>
</div>
