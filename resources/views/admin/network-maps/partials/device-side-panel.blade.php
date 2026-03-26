{{-- Painel lateral de detalhes do dispositivo (substitui modais de visualização). Dentro de .mapa-rede-forcelight. --}}
<div id="deviceSidePanel" class="map-device-panel bg-white flex flex-col overflow-hidden" role="complementary" aria-label="Detalhes do dispositivo" aria-hidden="true">
    <div class="flex shrink-0 items-start justify-between gap-2 border-b border-gray-200 bg-gray-50/90 px-3 py-2.5 rounded-t-xl">
        <h3 id="deviceSidePanelTitle" class="text-sm font-semibold text-gray-900 leading-snug pr-2 min-w-0 break-words"></h3>
        <button type="button" id="deviceSidePanelClose" class="shrink-0 rounded-md p-1.5 text-gray-500 hover:bg-gray-200 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400" title="Fechar painel" aria-label="Fechar painel">
            <i class="fas fa-times text-lg" aria-hidden="true"></i>
        </button>
    </div>
    <div id="deviceSidePanelBody" class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-3 sm:p-4 text-sm text-gray-800"></div>
    <div id="deviceSidePanelFooter" class="hidden shrink-0 border-t border-gray-200 bg-white px-3 py-2.5 flex justify-end rounded-b-xl">
        <button type="button" id="deviceSidePanelEditBtn" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg btn-engehub-yellow">
            <i class="fas fa-edit mr-2"></i>Editar
        </button>
    </div>
</div>
