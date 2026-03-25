@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-map-marked-alt mr-2" style="color: #E9B32C;"></i>
            {{ $network_map->name }}
        </h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.network-maps.edit', $network_map) }}" class="inline-flex items-center px-4 py-2 btn-engehub-yellow border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('admin.network-maps.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
                                <div class="text-sm text-gray-500">Mesas cadastradas</div>
                                <div class="font-medium text-gray-900" id="seatCount">{{ $network_map->seats->count() }}</div>
                            </div>
                            <form action="{{ route('admin.network-maps.resync-seats', $network_map) }}" method="POST" class="inline" onsubmit="return confirm('Revarredurar o SVG e sincronizar as mesas?');">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-200 border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-300 transition">
                                    <i class="fas fa-sync-alt mr-1"></i> Revarrear mesas
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($svgContent)
                        <div class="mapa-rede-forcelight border-2 border-gray-300 rounded-lg overflow-hidden shadow-inner relative" style="height: 70vh; background: #ffffff !important;">
                            {{-- Controles flutuantes: rótulos no mapa + zoom (canto superior direito) --}}
                            <div class="absolute top-3 right-3 z-10 flex flex-wrap items-center gap-2">
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
                            <div id="mapaContainer" class="w-full h-full overflow-hidden cursor-grab" style="touch-action: none; background: #ffffff !important;">
                                <div id="svgWrapper" class="inline-block p-4" style="transform-origin: 0 0; will-change: transform; background: #ffffff;">
                                    <div id="svgContainer" class="svg-map-theme" style="background: #ffffff !important;">
                                        {!! $svgContent !!}
                                    </div>
                                </div>
                            </div>
                        </div>
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

    {{-- Modal Visualizar Mesa (só leitura + botão Editar) --}}
    <div id="seatViewModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" aria-modal="true" style="display: none;">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeSeatViewModal()" aria-hidden="true"></div>
        <div class="relative bg-white rounded-xl shadow-2xl flex flex-col max-h-[85vh] w-full mx-auto my-auto" style="width: 100%; max-width: 28rem;" onclick="event.stopPropagation()">
            <div class="p-5 border-b border-gray-200 shrink-0 flex justify-between items-center rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900">Mesa — <span id="seatViewCode"></span></h3>
                <button type="button" onclick="closeSeatViewModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-5 overflow-y-auto flex-1 min-h-0 text-sm" id="seatViewContent">
                <div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-2 border-amber-500 border-t-transparent mx-auto"></div><p class="mt-2 text-gray-600">Carregando...</p></div>
            </div>
            <div class="p-5 border-t border-gray-200 flex justify-end gap-2 shrink-0 rounded-b-xl">
                <button type="button" onclick="closeSeatViewModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300">Fechar</button>
                <button type="button" id="seatViewEditBtn" class="px-4 py-2 btn-engehub-yellow rounded-lg font-medium"><i class="fas fa-edit mr-2"></i>Editar</button>
            </div>
        </div>
    </div>

    {{-- Modal Editar Mesa (centralizado na tela) --}}
    <div id="seatEditModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" aria-modal="true" style="display: none;">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeSeatEditModal()" aria-hidden="true"></div>
        <div class="relative bg-white rounded-xl shadow-2xl flex flex-col w-full mx-auto my-auto" style="z-index: 1; max-width: 42rem; max-height: 85vh; min-height: 0;" onclick="event.stopPropagation()">
                <div class="p-6 border-b border-gray-200 shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900">Editar mesa — <span id="seatEditCode"></span></h3>
                </div>
                <div class="overflow-y-auto flex-1 min-h-0">
                <form id="seatEditForm" class="p-6 space-y-4">
                    <input type="hidden" name="code" id="seatEditCodeInput">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                        <input type="text" id="seatEditCodeReadonly" readonly class="w-full rounded border-gray-300 bg-gray-100 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Setor</label>
                        <input type="text" name="setor" id="seatEditSetor" class="w-full rounded border-gray-300" maxlength="100" placeholder="Ex: TI">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                        <textarea name="observacoes" id="seatEditObservacoes" class="w-full rounded border-gray-300" rows="2" maxlength="500" placeholder="Opcional"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Colaborador (nome exibido no mapa)</label>
                        <input type="text" name="collaborator_name" id="seatEditCollaboratorName" class="w-full rounded border-gray-300" maxlength="255" placeholder="Ex: Fabio Henrique (digite o nome; no mapa o código será substituído por este nome)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome do computador</label>
                        <input type="text" name="computer_name" id="seatEditComputerName" class="w-full rounded border-gray-300" maxlength="100" placeholder="Opcional">
                    </div>
                    <div class="border-t pt-4 mt-4">
                        <h4 class="font-medium text-gray-800 mb-2">Ponto de rede 1</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-0.5">Código</label>
                                <input type="text" name="point_1_code" id="seatEditPoint1Code" class="w-full rounded border-gray-300 text-sm" maxlength="20">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-0.5">IP</label>
                                <input type="text" name="point_1_ip" id="seatEditPoint1Ip" class="w-full rounded border-gray-300 text-sm" maxlength="45">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-0.5">MAC</label>
                                <input type="text" name="point_1_mac" id="seatEditPoint1Mac" class="w-full rounded border-gray-300 text-sm" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">Ponto de rede 2</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs text-gray-600 mb-0.5">Código</label>
                                <input type="text" name="point_2_code" id="seatEditPoint2Code" class="w-full rounded border-gray-300 text-sm" maxlength="20">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-0.5">IP</label>
                                <input type="text" name="point_2_ip" id="seatEditPoint2Ip" class="w-full rounded border-gray-300 text-sm" maxlength="45">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-0.5">MAC</label>
                                <input type="text" name="point_2_mac" id="seatEditPoint2Mac" class="w-full rounded border-gray-300 text-sm" maxlength="50">
                            </div>
                        </div>
                    </div>
                </form>
                </div>
                <div class="p-6 border-t border-gray-200 flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeSeatEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded font-medium hover:bg-gray-300">Cancelar</button>
                    <button type="submit" form="seatEditForm" class="px-4 py-2 btn-engehub-yellow rounded font-medium">Atualizar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Botão amarelo padrão (mesma cor do header do usuário #E9B32C) */
        .btn-engehub-yellow { background-color: #E9B32C !important; color: #000 !important; }
        .btn-engehub-yellow:hover { background-color: #d19d20 !important; }
        #seatEditModal { align-items: center; justify-content: center; }
        #seatEditModal .relative.bg-white { max-height: 85vh; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
        #seatEditModal .relative.bg-white > div.overflow-y-auto { -webkit-overflow-scrolling: touch; }
        /* Forçar visual correto em qualquer tema: tratar mapa como color-scheme dark (igual tema escuro) + fundo branco */
        .mapa-rede-forcelight { color-scheme: dark !important; background: #ffffff !important; isolation: isolate; contain: layout style paint; }
        .mapa-rede-forcelight #mapaContainer, .mapa-rede-forcelight #svgContainer, .mapa-rede-forcelight #svgWrapper { background: #ffffff !important; }
        .mapa-rede-forcelight .svg-map-theme svg { background-color: #ffffff !important; }
        /* Não forçar fill/color em todo texto: preservar cores do draw.io (ex.: laranja em "RH", "PRESIDÊNCIA"). Só forçar preto nos rótulos de mesa (data-seat). */
        .mapa-rede-forcelight .svg-map-theme [data-seat].seat { fill: #000000 !important; color: #000000 !important; }
        .mapa-rede-forcelight .svg-map-theme foreignObject [data-seat].seat { color: #000000 !important; }
        @media (prefers-color-scheme: light) {
            .mapa-rede-forcelight, .mapa-rede-forcelight * { color-scheme: dark !important; }
            .mapa-rede-forcelight .svg-map-theme svg { background: #ffffff !important; background-color: #ffffff !important; }
        }
        /* Não forçar stroke preto em path/line/polyline/rect para preservar cores do draw.io (ex.: borda laranja nas mesas) */
        .svg-map-theme [data-seat].seat { cursor: pointer !important; }
        .svg-map-theme [data-seat].seat:hover { fill: #b45309 !important; }
        .svg-map-theme foreignObject [data-seat].seat:hover { color: #b45309 !important; }
        /* Não usar pointer-events: none no SVG: senão o clique cai no container e vira arraste */
        #mapaContainer svg { max-width: none !important; height: auto !important; }
    </style>

    @if($svgContent)
    <script>
    (function() {
        var networkMapId = {{ $network_map->id }};
        var seatGetUrl = "{{ url('admin/network-maps/'.$network_map->id) }}/seats/";
        var seatUpdateUrl = "{{ url('admin/network-maps/'.$network_map->id) }}/seats/";
        var csrfToken = "{{ csrf_token() }}";
        var seatLabels = @json($seatLabels ?? []);

        var mapZoomLevel = 1, mapTranslateX = 0, mapTranslateY = 0;
        var mapPanStartX, mapPanStartY, mapPanStartTranslateX, mapPanStartTranslateY, mapPanning = false;
        var mapShowNames = false;

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

        function applySeatLabels() {
            var container = document.getElementById('svgContainer');
            if (!container) return;
            [].forEach.call(container.querySelectorAll('[data-seat]'), function(el) {
                var code = el.getAttribute('data-seat');
                var original = el.getAttribute('data-original-text') || code;
                var text = mapShowNames && seatLabels[code] ? seatLabels[code] : original;
                el.textContent = text;
            });
        }
        function setLabelToggle(showNames) {
            mapShowNames = showNames;
            var btnCodes = document.getElementById('mapShowCodes');
            var btnNames = document.getElementById('mapShowNames');
            if (btnCodes && btnNames) {
                if (showNames) {
                    btnCodes.classList.remove('btn-engehub-yellow', 'border-gray-400');
                    btnCodes.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
                    btnNames.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
                    btnNames.classList.add('btn-engehub-yellow', 'border-gray-400');
                } else {
                    btnNames.classList.remove('btn-engehub-yellow', 'border-gray-400');
                    btnNames.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
                    btnCodes.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
                    btnCodes.classList.add('btn-engehub-yellow', 'border-gray-400');
                }
            }
            applySeatLabels();
        }

        function openSeatEditModal(code) {
            var modal = document.getElementById('seatEditModal');
            var codeEl = document.getElementById('seatEditCode');
            var codeReadonly = document.getElementById('seatEditCodeReadonly');
            var codeInput = document.getElementById('seatEditCodeInput');
            if (!modal || !codeEl) return;
            codeEl.textContent = code;
            codeReadonly.value = code;
            codeInput.value = code;
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
            document.getElementById('seatEditSetor').value = '';
            document.getElementById('seatEditObservacoes').value = '';
            document.getElementById('seatEditCollaboratorName').value = '';
            document.getElementById('seatEditComputerName').value = '';
            document.getElementById('seatEditPoint1Code').value = code + '-01';
            document.getElementById('seatEditPoint1Ip').value = '';
            document.getElementById('seatEditPoint1Mac').value = '';
            document.getElementById('seatEditPoint2Code').value = code + '-02';
            document.getElementById('seatEditPoint2Ip').value = '';
            document.getElementById('seatEditPoint2Mac').value = '';

            fetch(seatGetUrl + encodeURIComponent(code), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.success || !data.seat) return;
                    var s = data.seat;
                    document.getElementById('seatEditSetor').value = s.setor || '';
                    document.getElementById('seatEditObservacoes').value = s.observacoes || '';
                    if (s.current_assignment) {
                        document.getElementById('seatEditCollaboratorName').value = s.current_assignment.collaborator_name || '';
                        document.getElementById('seatEditComputerName').value = s.current_assignment.computer_name || '';
                    }
                    var pts = s.network_points || [];
                    if (pts[0]) {
                        document.getElementById('seatEditPoint1Code').value = pts[0].code || '';
                        document.getElementById('seatEditPoint1Ip').value = pts[0].ip || '';
                        document.getElementById('seatEditPoint1Mac').value = pts[0].mac_address || '';
                    }
                    if (pts[1]) {
                        document.getElementById('seatEditPoint2Code').value = pts[1].code || '';
                        document.getElementById('seatEditPoint2Ip').value = pts[1].ip || '';
                        document.getElementById('seatEditPoint2Mac').value = pts[1].mac_address || '';
                    }
                })
                .catch(function() {});
        }
        function closeSeatEditModal() {
            var modal = document.getElementById('seatEditModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.add('hidden');
            }
        }

        function openSeatViewModal(code) {
            var modal = document.getElementById('seatViewModal');
            var codeEl = document.getElementById('seatViewCode');
            var content = document.getElementById('seatViewContent');
            var editBtn = document.getElementById('seatViewEditBtn');
            if (!modal || !content) return;
            codeEl.textContent = code;
            content.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-2 border-amber-500 border-t-transparent mx-auto"></div><p class="mt-2 text-gray-600">Carregando...</p></div>';
            modal.style.display = 'flex';
            editBtn.onclick = function() { closeSeatViewModal(); openSeatEditModal(code); };
            fetch(seatGetUrl + encodeURIComponent(code), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.success || !data.seat) {
                        content.innerHTML = '<p class="text-red-600">Erro ao carregar dados da mesa.</p>';
                        return;
                    }
                    var s = data.seat;
                    var html = '<div class="space-y-3 text-sm">';
                    html += '<p><strong>Código:</strong> ' + (s.code || '-') + '</p>';
                    if (s.setor) html += '<p><strong>Setor:</strong> ' + s.setor + '</p>';
                    if (s.observacoes) html += '<p><strong>Observações:</strong> ' + s.observacoes + '</p>';
                    if (s.current_assignment) {
                        html += '<h4 class="font-semibold text-gray-800 mt-2">Colaborador</h4><ul class="list-disc list-inside text-gray-700"><li>' + (s.current_assignment.collaborator_name || '-') + '</li><li>Computador: ' + (s.current_assignment.computer_name || '-') + '</li></ul>';
                    } else {
                        html += '<p class="text-gray-500">Nenhum colaborador atribuído.</p>';
                    }
                    var pts = s.network_points || [];
                    if (pts.length) {
                        html += '<h4 class="font-semibold text-gray-800 mt-2">Pontos de rede</h4><table class="w-full text-sm"><thead><tr class="border-b"><th class="text-left py-1">Código</th><th class="text-left py-1">IP</th><th class="text-left py-1">MAC</th></tr></thead><tbody>';
                        pts.forEach(function(p) { html += '<tr class="border-b"><td class="py-1">' + (p.code || '-') + '</td><td class="py-1">' + (p.ip || '-') + '</td><td class="py-1">' + (p.mac_address || '-') + '</td></tr>'; });
                        html += '</tbody></table>';
                    }
                    html += '</div>';
                    content.innerHTML = html;
                })
                .catch(function() { content.innerHTML = '<p class="text-red-600">Erro ao carregar. Tente novamente.</p>'; });
        }
        function closeSeatViewModal() {
            var modal = document.getElementById('seatViewModal');
            if (modal) modal.style.display = 'none';
        }
        window.closeSeatViewModal = closeSeatViewModal;

        document.addEventListener('DOMContentLoaded', function() {
            var container = document.getElementById('mapaContainer');
            var wrapper = document.getElementById('svgWrapper');
            if (container && wrapper) {
                mapApplyTransform();
                container.style.cursor = 'grab';
                container.addEventListener('mousedown', function(e) {
                    if (e.target.closest('[data-seat]')) return;
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
                container.addEventListener('mousemove', function(e) {
                    if (mapPanning) return;
                    container.style.cursor = e.target.closest('[data-seat]') ? 'pointer' : 'grab';
                });
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
                container.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    mapZoomAtPoint(e.clientX, e.clientY, e.deltaY < 0);
                }, { passive: false });
            }
            // Clique na mesa: delegação no DOCUMENTO (capture=true) para garantir que o clique seja sempre capturado
            document.addEventListener('click', function(e) {
                var seatEl = e.target.closest('[data-seat]');
                var mapa = document.getElementById('mapaContainer');
                if (seatEl && mapa && mapa.contains(seatEl)) {
                    e.preventDefault();
                    e.stopPropagation();
                    openSeatViewModal(seatEl.getAttribute('data-seat'));
                }
            }, true);
            var zoomIn = document.getElementById('mapZoomIn');
            var zoomOut = document.getElementById('mapZoomOut');
            var zoomReset = document.getElementById('mapZoomReset');
            var showCodes = document.getElementById('mapShowCodes');
            var showNames = document.getElementById('mapShowNames');
            if (zoomIn) zoomIn.addEventListener('click', mapZoomIn);
            if (zoomOut) zoomOut.addEventListener('click', mapZoomOut);
            if (zoomReset) zoomReset.addEventListener('click', mapResetZoom);
            if (showCodes) showCodes.addEventListener('click', function() { setLabelToggle(false); });
            if (showNames) showNames.addEventListener('click', function() { setLabelToggle(true); });

            function initSeatClick() {
                var svgContainer = document.getElementById('svgContainer');
                if (!svgContainer) return;
                var svgEl = svgContainer.querySelector('svg') || svgContainer;
                var re = /^[A-Z]+\d{2}$/;
                var count = 0;
                function markIfLeaf(el) {
                    var t = (el.textContent || '').trim();
                    if (!re.test(t)) return;
                    if (el.children.length > 0) return;
                    el.setAttribute('data-seat', t);
                    el.setAttribute('data-original-text', t);
                    el.classList.add('seat');
                    el.style.cursor = 'pointer';
                    el.style.pointerEvents = 'auto';
                    count++;
                }
                [].forEach.call(svgEl.querySelectorAll('text'), markIfLeaf);
                [].forEach.call(svgEl.querySelectorAll('tspan'), markIfLeaf);
                var foreignObjects = svgEl.querySelectorAll('foreignObject');
                [].forEach.call(foreignObjects, function(fo) {
                    [].forEach.call(fo.querySelectorAll('*'), markIfLeaf);
                });
                applySeatLabels();
                if (count === 0) {
                    console.warn('EngeHub mapa: nenhum texto de mesa (A01, B01...) encontrado no SVG.');
                } else if (Object.keys(seatLabels).some(function(k) { return seatLabels[k]; })) {
                    setLabelToggle(true);
                }
            }
            initSeatClick();
            setTimeout(initSeatClick, 200);

            document.getElementById('seatEditForm').addEventListener('submit', function(e) {
                e.preventDefault();
                var code = document.getElementById('seatEditCodeInput').value;
                var form = e.target;
                var collaboratorName = (form.collaborator_name && form.collaborator_name.value) ? form.collaborator_name.value.trim() : '';
                var payload = {
                    setor: form.setor.value,
                    observacoes: form.observacoes.value,
                    collaborator_name: collaboratorName,
                    computer_name: form.computer_name.value,
                    point_1_code: form.point_1_code.value,
                    point_1_ip: form.point_1_ip.value,
                    point_1_mac: form.point_1_mac.value,
                    point_2_code: form.point_2_code.value,
                    point_2_ip: form.point_2_ip.value,
                    point_2_mac: form.point_2_mac.value
                };
                fetch(seatUpdateUrl + encodeURIComponent(code), {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                }).then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        seatLabels[code] = collaboratorName || null;
                        setLabelToggle(true);
                        closeSeatEditModal();
                        if (typeof data.message !== 'undefined') alert(data.message);
                    } else {
                        alert(data.message || 'Erro ao atualizar.');
                    }
                }).catch(function() { alert('Erro ao atualizar. Tente novamente.'); });
            });
        });
        window.closeSeatEditModal = closeSeatEditModal;
    })();
    </script>
    @endif
@endsection
