{{-- Modais de consulta (visualização) — mesma estrutura do admin, sem CRUD --}}
<div id="cameraViewerModal" class="fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 10100;">
    <div class="relative bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
            <div>
                <h3 class="text-sm font-medium text-gray-500">DVR</h3>
                <p id="cameraViewerDvrName" class="text-lg font-semibold text-gray-900"></p>
                <h3 class="text-sm font-medium text-gray-500 mt-1">Câmera</h3>
                <p id="cameraViewerCameraName" class="text-base font-medium text-gray-800"></p>
            </div>
            <button type="button" onclick="closeCameraViewerConsulta()" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 flex items-center justify-center p-6 bg-gray-100 min-h-[300px]">
            <div class="relative">
                <img id="cameraViewerImage" src="" alt="" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg" style="display: none;">
                <div id="cameraViewerPlaceholder" class="hidden flex items-center justify-center w-64 h-48 bg-gray-200 rounded-lg text-gray-500">
                    <span><i class="fas fa-image mr-2"></i>Sem imagem</span>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <button type="button" onclick="cameraViewerPrevConsulta()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                <i class="fas fa-chevron-left mr-2"></i>Anterior
            </button>
            <span id="cameraViewerCounter" class="text-sm text-gray-600"></span>
            <button type="button" onclick="cameraViewerNextConsulta()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                Próxima<i class="fas fa-chevron-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

<div id="dvrFotoViewerModalConsulta" class="fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 10110;">
    <div class="relative bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
            <div>
                <p id="dvrFotoViewerContextHintConsulta" class="text-xs font-medium text-indigo-600 mb-1 hidden">Histórico deste DVR</p>
                <h3 class="text-sm font-medium text-gray-500">DVR</h3>
                <p id="dvrFotoViewerDvrNameConsulta" class="text-lg font-semibold text-gray-900"></p>
                <h3 class="text-sm font-medium text-gray-500 mt-2">Data da foto</h3>
                <p id="dvrFotoViewerDataLabelConsulta" class="text-base font-medium text-gray-800"></p>
            </div>
            <button type="button" onclick="closeDvrFotoViewerConsulta()" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 flex items-center justify-center p-6 bg-gray-100 min-h-[300px]">
            <img id="dvrFotoViewerImageConsulta" src="" alt="" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg">
        </div>
        <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <button type="button" id="dvrFotoViewerBtnPrevConsulta" onclick="dvrFotoViewerPrevConsulta()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                <i class="fas fa-chevron-left mr-2"></i>Anterior
            </button>
            <span id="dvrFotoViewerCounterConsulta" class="text-sm text-gray-600"></span>
            <button type="button" id="dvrFotoViewerBtnNextConsulta" onclick="dvrFotoViewerNextConsulta()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                Próxima<i class="fas fa-chevron-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

<div id="historicoDvrModalConsulta" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 10105;">
    <div class="w-full max-w-2xl shadow-lg rounded-md bg-white my-4 max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-images text-indigo-600 mr-2"></i><span id="historicoDvrModalTituloConsulta">Fotos do DVR</span></h3>
            <button type="button" onclick="closeHistoricoDvrModalConsulta()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <ul id="historicoDvrModalListaConsulta" class="space-y-4"></ul>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            <button type="button" onclick="closeHistoricoDvrModalConsulta()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Fechar</button>
        </div>
    </div>
</div>

<div id="historicoCameraModalConsulta" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 10105;">
    <div class="w-full max-w-2xl shadow-lg rounded-md bg-white my-4 max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-history text-indigo-600 mr-2"></i><span id="historicoCameraModalTituloConsulta">Histórico da Câmera</span></h3>
            <button type="button" onclick="closeHistoricoCameraModalConsulta()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <ul id="historicoCameraModalListaConsulta" class="space-y-3"></ul>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            <button type="button" onclick="closeHistoricoCameraModalConsulta()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Fechar</button>
        </div>
    </div>
</div>
