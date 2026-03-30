@php
    $deviceApiBaseResolved = $deviceApiBase ?? (
        ($filiaisMode ?? false)
            ? url('/filiais/network-maps/'.$network_map->id.'/devices')
            : url('/admin/network-maps/'.$network_map->id.'/devices')
    );
@endphp
(function() {
    var deviceApiBase = @json($deviceApiBaseResolved);
    var deviceUpdateBase = @json(($canEditDevicesEffective ?? false) ? url('/admin/network-maps/'.$network_map->id.'/devices') : null);
    var csrfToken = "{{ csrf_token() }}";
    var canEditDevices = @json($canEditDevicesEffective ?? false);
    var deviceLabels = @json($deviceLabels ?? []);
    var DEVICE_REGEX = /^(SEAT|PRINTER|TV|SCAN|PHONE|AP)-[A-Z0-9\-]+$/;
    var OUTLET_REGEX = /^[A-Z]\d{2}$/;

    var mapZoomLevel = 1, mapTranslateX = 0, mapTranslateY = 0;
    var mapPanStartX, mapPanStartY, mapPanStartTranslateX, mapPanStartTranslateY, mapPanning = false;
    var mapShowNames = false;
    var mapRAF = null;

    var mapLayerFilters = {
        OUTLET: true,
        SEAT: true,
        PRINTER: true,
        TV: true,
        SCAN: true,
        PHONE: true,
        AP: true
    };
    var MAP_FILTER_LABELS = {
        OUTLET: 'Pontos (tomadas)',
        SEAT: 'Mesas',
        PRINTER: 'Impressoras',
        TV: 'TVs',
        SCAN: 'Scanners',
        PHONE: 'Telefones',
        AP: 'Access points'
    };

    var seatSearchResults = [];
    var seatSearchIndex = 0;
    var seatSearchHighlightedEl = null;
    var seatSearchDebounce = null;
    var SEAT_SEARCH_TARGET_ZOOM = 1.25;
    var SEAT_SEARCH_ANIM_FRAMES = 10;

    function mapDeviceSaveErrorMessage(data) {
        var msg = (data && data.message) ? String(data.message) : 'Não foi possível salvar.';
        if (data && data.errors && typeof data.errors === 'object') {
            try {
                var lines = [];
                Object.keys(data.errors).forEach(function(key) {
                    var v = data.errors[key];
                    if (Array.isArray(v)) lines = lines.concat(v);
                    else if (v) lines.push(String(v));
                });
                if (lines.length) msg = lines.join(' ');
            } catch (err) { /* mantém msg */ }
        }
        return msg;
    }
    function mapDeviceNotifySuccess(message) {
        var msg = (message && String(message).trim()) ? String(message).trim() : 'Salvo com sucesso.';
        if (typeof window.showToast === 'function') {
            window.showToast(msg, 'success', 4500);
        } else {
            alert(msg);
        }
    }
    function mapDeviceNotifyError(message) {
        var msg = (message && String(message).trim()) ? String(message).trim() : 'Erro ao salvar.';
        if (typeof window.showToast === 'function') {
            window.showToast(msg, 'error', 6500);
        } else {
            alert(msg);
        }
    }

    function updateZoomLevelDisplay() {
        var zel = document.getElementById('zoomLevel');
        if (zel) {
            zel.textContent = Math.round(mapZoomLevel * 100) + '%';
        }
    }

    function deviceUrl(type, code) {
        return deviceApiBase + '/' + encodeURIComponent(type) + '/' + encodeURIComponent(code);
    }
    function devicePutUrl(type, code) {
        return deviceUpdateBase + '/' + encodeURIComponent(type) + '/' + encodeURIComponent(code);
    }
    function fetchDevice(type, code) {
        return fetch(deviceUrl(type, code), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); });
    }
    function showModal(id) {
        var m = document.getElementById(id);
        if (m) { m.style.display = 'flex'; }
    }
    function hideModal(id) {
        var m = document.getElementById(id);
        if (m) { m.style.display = 'none'; }
    }

    function escapeHtmlDevice(s) {
        var d = document.createElement('div');
        d.textContent = s == null ? '' : String(s);
        return d.innerHTML;
    }
    function escapeAttrDevice(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;');
    }
    function devicePhotoBlockHtml(det) {
        if (!det || !det.device_photo_url) return '';
        return '<div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 mb-2"><img src="' + escapeAttrDevice(det.device_photo_url) + '" alt="" class="w-full max-h-52 object-cover"></div>';
    }
    function resetGenericDevicePhotoUi(suffix, det) {
        var base = 'device' + suffix + 'Edit';
        var wrap = document.getElementById(base + 'PhotoPreviewWrap');
        var img = document.getElementById(base + 'PhotoPreview');
        var fileIn = document.getElementById(base + 'DevicePhoto');
        var rem = document.getElementById(base + 'RemovePhoto');
        if (fileIn) fileIn.value = '';
        if (rem) rem.checked = false;
        if (!wrap || !img) return;
        var url = det && det.device_photo_url ? det.device_photo_url : '';
        if (url) {
            img.src = url;
            wrap.classList.remove('hidden');
        } else {
            img.removeAttribute('src');
            wrap.classList.add('hidden');
        }
    }
    function resetOutletPhotoUi(det) {
        var wrap = document.getElementById('deviceOutletEditPhotoPreviewWrap');
        var img = document.getElementById('deviceOutletEditPhotoPreview');
        var fileIn = document.getElementById('deviceOutletEditDevicePhoto');
        var rem = document.getElementById('deviceOutletEditRemovePhoto');
        if (fileIn) fileIn.value = '';
        if (rem) rem.checked = false;
        if (!wrap || !img) return;
        var url = det && det.device_photo_url ? det.device_photo_url : '';
        if (url) {
            img.src = url;
            wrap.classList.remove('hidden');
        } else {
            img.removeAttribute('src');
            wrap.classList.add('hidden');
        }
    }
    function wireDeviceEditPhotoPreview(suffix) {
        var base = 'device' + suffix + 'Edit';
        var fileIn = document.getElementById(base + 'DevicePhoto');
        var wrap = document.getElementById(base + 'PhotoPreviewWrap');
        var img = document.getElementById(base + 'PhotoPreview');
        if (!fileIn || !wrap || !img) return;
        fileIn.addEventListener('change', function() {
            var f = fileIn.files && fileIn.files[0];
            if (!f) return;
            img.src = URL.createObjectURL(f);
            wrap.classList.remove('hidden');
        });
    }
    function seatKindLabelPt(kind) {
        if (kind === 'desktop') return 'Desktop';
        if (kind === 'notebook') return 'Notebook';
        return '—';
    }
    function deviceSidePanelBody() {
        return document.getElementById('deviceSidePanelBody');
    }
    function showDeviceSidePanel() {
        var p = document.getElementById('deviceSidePanel');
        if (p) {
            p.classList.add('map-device-panel--open');
            p.setAttribute('aria-hidden', 'false');
        }
    }
    function hideDeviceSidePanel() {
        var p = document.getElementById('deviceSidePanel');
        if (p) {
            p.classList.remove('map-device-panel--open');
            p.setAttribute('aria-hidden', 'true');
        }
        var b = deviceSidePanelBody();
        if (b) {
            b.innerHTML = '';
        }
    }
    function setDeviceSidePanelLoading() {
        var b = deviceSidePanelBody();
        if (b) {
            b.innerHTML = '<div class="text-center py-6"><div class="animate-spin rounded-full h-8 w-8 border-2 border-amber-500 border-t-transparent mx-auto"></div><p class="mt-2 text-sm text-gray-600">Carregando...</p></div>';
        }
    }
    function setDeviceSidePanelTitle(iconClass, htmlInner) {
        var t = document.getElementById('deviceSidePanelTitle');
        if (t) {
            t.innerHTML = '<i class="fas ' + iconClass + ' mr-2 text-amber-600"></i>' + htmlInner;
        }
    }
    function wireDeviceSidePanelEdit(handler) {
        var foot = document.getElementById('deviceSidePanelFooter');
        var btn = document.getElementById('deviceSidePanelEditBtn');
        if (!foot || !btn) {
            return;
        }
        if (canEditDevices && typeof handler === 'function') {
            foot.classList.remove('hidden');
            btn.onclick = handler;
        } else {
            foot.classList.add('hidden');
            btn.onclick = null;
        }
    }

    function mapApplyTransform() {
        var w = document.getElementById('svgWrapper');
        if (!w) return;
        var s = mapZoomLevel;
        if (Math.abs(s - 1) < 0.0001) {
            s = 1;
        }
        var tx = Math.round(mapTranslateX);
        var ty = Math.round(mapTranslateY);
        w.style.transform = 'translate3d(' + tx + 'px,' + ty + 'px,0) scale(' + s + ')';
    }
    function mapScheduleTransform() {
        if (mapRAF !== null) return;
        mapRAF = requestAnimationFrame(function() {
            mapRAF = null;
            mapApplyTransform();
        });
    }
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
        updateZoomLevelDisplay();
    }
    function mapZoomIn() {
        var container = document.getElementById('mapaContainer');
        if (container) {
            var r = container.getBoundingClientRect();
            mapZoomAtPoint(r.left + r.width / 2, r.top + r.height / 2, true);
        } else {
            mapZoomLevel = Math.min(5, mapZoomLevel + 0.25);
            mapApplyTransform();
            updateZoomLevelDisplay();
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
            updateZoomLevelDisplay();
        }
    }
    function mapResetZoom() {
        mapZoomLevel = 1;
        mapTranslateX = 0;
        mapTranslateY = 0;
        mapApplyTransform();
        var el = document.getElementById('zoomLevel');
        if (el) {
            el.textContent = '100%';
        }
    }

    function getSeatCollaboratorSearchLabel(el) {
        if (!el) {
            return '';
        }
        var full = el.getAttribute('data-device-full') || '';
        var raw = deviceLabels[full];
        var meta = (raw != null && String(raw).trim() !== '') ? String(raw).trim() : '';
        if (meta) {
            return meta;
        }
        if (mapShowNames) {
            var t = (el.textContent || '').trim();
            var orig = (el.getAttribute('data-original-text') || '').trim();
            if (t && orig && t !== orig) {
                return t;
            }
        }
        return '';
    }

    function clearSeatSearchHighlight() {
        if (seatSearchHighlightedEl) {
            seatSearchHighlightedEl.classList.remove('device-search-highlight');
            seatSearchHighlightedEl = null;
        }
    }

    function applySeatSearchHighlight(el) {
        clearSeatSearchHighlight();
        if (!el) {
            return;
        }
        seatSearchHighlightedEl = el;
        el.classList.add('device-search-highlight');
    }

    function computeTransformCenterElement(el, targetScale) {
        var container = document.getElementById('mapaContainer');
        if (!container || !el) {
            return null;
        }
        var cr = container.getBoundingClientRect();
        var er = el.getBoundingClientRect();
        if (er.width < 0.5 && er.height < 0.5) {
            return null;
        }
        var mouseX = er.left + er.width / 2 - cr.left;
        var mouseY = er.top + er.height / 2 - cr.top;
        var contentX = (mouseX - mapTranslateX) / mapZoomLevel;
        var contentY = (mouseY - mapTranslateY) / mapZoomLevel;
        var tx1 = mouseX - contentX * targetScale;
        var ty1 = mouseY - contentY * targetScale;
        var contCx = cr.width / 2;
        var contCy = cr.height / 2;
        return { tx: tx1 + (contCx - mouseX), ty: ty1 + (contCy - mouseY), s: targetScale };
    }

    function animateMapToTransform(endTx, endTy, endS) {
        var startTx = mapTranslateX;
        var startTy = mapTranslateY;
        var startS = mapZoomLevel;
        var n = SEAT_SEARCH_ANIM_FRAMES;
        var f = 0;
        function smoothstep(t) {
            return t * t * (3 - 2 * t);
        }
        function tick() {
            f += 1;
            var t = smoothstep(f / n);
            mapTranslateX = startTx + (endTx - startTx) * t;
            mapTranslateY = startTy + (endTy - startTy) * t;
            mapZoomLevel = startS + (endS - startS) * t;
            mapApplyTransform();
            if (f < n) {
                requestAnimationFrame(tick);
            } else {
                mapTranslateX = Math.round(endTx);
                mapTranslateY = Math.round(endTy);
                mapZoomLevel = Math.abs(endS - 1) < 0.0001 ? 1 : endS;
                mapApplyTransform();
                updateZoomLevelDisplay();
            }
        }
        requestAnimationFrame(tick);
    }

    /**
     * Centraliza o mapa em um elemento .device via pan/zoom (transform), sem scroll da página nem do #mapaContainer.
     * options: { zoom?: number (default = busca colaborador), animated?: boolean (default true) }
     */
    window.engeHubNetworkMapFocusOnElement = function(el, options) {
        options = options || {};
        var targetZoom = options.zoom != null ? Number(options.zoom) : SEAT_SEARCH_TARGET_ZOOM;
        if (!el || !isFinite(targetZoom) || targetZoom <= 0) {
            return false;
        }
        var end = computeTransformCenterElement(el, targetZoom);
        if (!end) {
            return false;
        }
        if (options.animated === false) {
            mapTranslateX = Math.round(end.tx);
            mapTranslateY = Math.round(end.ty);
            mapZoomLevel = Math.abs(end.s - 1) < 0.0001 ? 1 : end.s;
            mapApplyTransform();
            updateZoomLevelDisplay();
        } else {
            animateMapToTransform(end.tx, end.ty, end.s);
        }
        return true;
    };

    function updateSeatSearchStatusUI() {
        var nav = document.getElementById('mapSearchNav');
        var status = document.getElementById('mapSearchStatus');
        var feedback = document.getElementById('mapSearchFeedback');
        if (!nav || !status) {
            return;
        }
        if (!seatSearchResults.length) {
            nav.classList.add('hidden');
            nav.classList.remove('flex');
            status.textContent = '';
            return;
        }
        nav.classList.remove('hidden');
        nav.classList.add('flex');
        var label = getSeatCollaboratorSearchLabel(seatSearchResults[seatSearchIndex]) || '—';
        if (label.length > 24) {
            label = label.slice(0, 22) + '…';
        }
        status.textContent = label + ' (' + (seatSearchIndex + 1) + '/' + seatSearchResults.length + ')';
        if (feedback) {
            feedback.classList.add('hidden');
            feedback.textContent = '';
        }
    }

    function focusSeatSearchResult(index) {
        if (!seatSearchResults.length) {
            return;
        }
        if (index < 0) {
            index = seatSearchResults.length - 1;
        }
        if (index >= seatSearchResults.length) {
            index = 0;
        }
        seatSearchIndex = index;
        var el = seatSearchResults[seatSearchIndex];
        var end = computeTransformCenterElement(el, SEAT_SEARCH_TARGET_ZOOM);
        if (!end) {
            updateSeatSearchStatusUI();
            return;
        }
        applySeatSearchHighlight(el);
        animateMapToTransform(end.tx, end.ty, end.s);
        updateSeatSearchStatusUI();
    }

    function runSeatSearchFromInput() {
        var input = document.getElementById('mapCollaboratorSearch');
        var feedback = document.getElementById('mapSearchFeedback');
        if (!input) {
            return;
        }
        var q = String(input.value || '').trim().toLowerCase();
        clearSeatSearchHighlight();
        seatSearchResults = [];
        seatSearchIndex = 0;
        if (!q) {
            if (feedback) {
                feedback.classList.add('hidden');
                feedback.textContent = '';
            }
            updateSeatSearchStatusUI();
            return;
        }
        var svgWrap = document.getElementById('svgContainer');
        if (!svgWrap) {
            return;
        }
        [].forEach.call(svgWrap.querySelectorAll('.device[data-type="SEAT"]'), function(el) {
            if (el.classList.contains('map-layer-filter-hidden')) {
                return;
            }
            var label = getSeatCollaboratorSearchLabel(el);
            if (!label) {
                return;
            }
            if (label.toLowerCase().indexOf(q) === -1) {
                return;
            }
            seatSearchResults.push(el);
        });
        if (!seatSearchResults.length) {
            if (feedback) {
                feedback.textContent = 'Nenhum resultado encontrado';
                feedback.classList.remove('hidden');
            }
            updateSeatSearchStatusUI();
            return;
        }
        if (feedback) {
            feedback.classList.add('hidden');
            feedback.textContent = '';
        }
        focusSeatSearchResult(0);
    }

    function applyMapLayerFilters() {
        var container = document.getElementById('svgContainer');
        if (!container) return;
        [].forEach.call(container.querySelectorAll('.device[data-type]'), function(el) {
            var t = el.getAttribute('data-type');
            if (!t || mapLayerFilters[t] === undefined) return;
            if (mapLayerFilters[t]) {
                el.classList.remove('map-layer-filter-hidden');
            } else {
                el.classList.add('map-layer-filter-hidden');
            }
        });
    }
    function notifyMapFilterChange(type) {
        var label = MAP_FILTER_LABELS[type] || type;
        var on = mapLayerFilters[type];
        var msg = on ? (label + ' visíveis no mapa.') : (label + ' ocultos no mapa.');
        if (typeof window.showToast === 'function') {
            window.showToast(msg, 'success', 3200);
        }
    }

    function applyDeviceLabels() {
        var container = document.getElementById('svgContainer');
        if (!container) return;
        [].forEach.call(container.querySelectorAll('.device[data-type="SEAT"]'), function(el) {
            var full = el.getAttribute('data-device-full') || '';
            var original = el.getAttribute('data-original-text') || full;
            var showName = mapShowNames && deviceLabels[full];
            el.textContent = showName ? deviceLabels[full] : original;
        });
        applyMapLayerFilters();
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
        applyDeviceLabels();
        var si = document.getElementById('mapCollaboratorSearch');
        if (si && String(si.value || '').trim()) {
            runSeatSearchFromInput();
        }
    }

    function openSeatModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-chair', 'Mesa — <span class="text-gray-900 font-semibold">SEAT-' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openSeatEditModal(code);
        });
        fetchDevice('SEAT', code).then(function(data) {
            if (!data.success || !data.device) {
                body.innerHTML = '<p class="text-red-600">Erro ao carregar.</p>';
                return;
            }
            var d = data.device;
            var det = d.details || {};
            var collab = (det.collaborator_name || '').trim();
            var titleInner = collab
                ? escapeHtmlDevice(collab)
                : ('SEAT-' + escapeHtmlDevice(code));
            setDeviceSidePanelTitle('fa-chair', 'Mesa — <span class="text-gray-900 font-semibold">' + titleInner + '</span>');
            var html = '<div class="space-y-3 text-sm">';
            if (det.workstation_photo_url) {
                html += '<div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50"><img src="' + escapeAttrDevice(det.workstation_photo_url) + '" alt="" class="w-full max-h-52 object-cover"></div>';
            }
            html += '<p><strong>Código:</strong> ' + escapeHtmlDevice(d.full_code || '-') + '</p>';
            if (d.setor) html += '<p><strong>Setor:</strong> ' + escapeHtmlDevice(d.setor) + '</p>';
            var pcName = (det.computer_name || '').trim();
            var kindPt = seatKindLabelPt(det.computer_kind);
            var tipoEstacao = (det.computer_kind === 'desktop' || det.computer_kind === 'notebook') ? kindPt : '—';
            html += '<p><strong>Tipo de Estação:</strong> ' + escapeHtmlDevice(tipoEstacao) + '</p>';
            html += '<p><strong>Nome do PC:</strong> ' + (pcName ? escapeHtmlDevice(pcName) : '—') + '</p>';
            html += '<p><strong>IP:</strong> ' + escapeHtmlDevice((det.computer_ip && String(det.computer_ip).trim()) ? det.computer_ip : '—') + '</p>';
            if (d.observacoes) html += '<p><strong>Observações:</strong> ' + escapeHtmlDevice(d.observacoes) + '</p>';
            if (!collab) html += '<p class="text-gray-500 text-xs mt-1">Sem colaborador associado — o título acima usa o código da mesa.</p>';
            html += '</div>';
            body.innerHTML = html;
        }).catch(function() {
            body.innerHTML = '<p class="text-red-600">Erro ao carregar.</p>';
        });
    }
    window.closeDeviceSeatViewModal = hideDeviceSidePanel;

    function deviceSeatEditResetPhotoUi(det) {
        var wrap = document.getElementById('deviceSeatEditPhotoPreviewWrap');
        var img = document.getElementById('deviceSeatEditPhotoPreview');
        var fileIn = document.getElementById('deviceSeatEditWorkstationPhoto');
        var rem = document.getElementById('deviceSeatEditRemovePhoto');
        if (fileIn) fileIn.value = '';
        if (rem) rem.checked = false;
        if (!wrap || !img) return;
        if (det && det.workstation_photo_url) {
            img.src = det.workstation_photo_url;
            wrap.classList.remove('hidden');
        } else {
            img.removeAttribute('src');
            wrap.classList.add('hidden');
        }
    }

    function openSeatEditModal(code) {
        var modal = document.getElementById('deviceSeatEditModal');
        if (!modal) return;
        document.getElementById('deviceSeatEditFullCode').textContent = 'SEAT-' + code;
        document.getElementById('deviceSeatEditCodeReadonly').value = code;
        document.getElementById('deviceSeatEditCodeRaw').value = code;
        document.getElementById('deviceSeatEditObservacoes').value = '';
        document.getElementById('deviceSeatEditCollaboratorName').value = '';
        document.getElementById('deviceSeatEditComputerName').value = '';
        document.getElementById('deviceSeatEditComputerKind').value = '';
        document.getElementById('deviceSeatEditComputerIp').value = '';
        deviceSeatEditResetPhotoUi(null);
        showModal('deviceSeatEditModal');
        fetchDevice('SEAT', code).then(function(data) {
            if (!data.success || !data.device) return;
            var d = data.device;
            var det = d.details || {};
            var m = d.metadata || {};
            document.getElementById('deviceSeatEditObservacoes').value = d.observacoes || '';
            document.getElementById('deviceSeatEditCollaboratorName').value = m.collaborator_name || det.collaborator_name || '';
            document.getElementById('deviceSeatEditComputerName').value = m.computer_name || det.computer_name || '';
            var kind = m.computer_kind || det.computer_kind || '';
            document.getElementById('deviceSeatEditComputerKind').value = (kind === 'desktop' || kind === 'notebook') ? kind : '';
            document.getElementById('deviceSeatEditComputerIp').value = m.computer_ip || det.computer_ip || '';
            deviceSeatEditResetPhotoUi(det);
        }).catch(function() {});
    }
    window.closeDeviceSeatEditModal = function() { hideModal('deviceSeatEditModal'); };

    function openPrinterModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-print', 'Impressora — <span class="text-gray-900 font-semibold">PRINTER-' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openPrinterEdit(code);
        });
        fetchDevice('PRINTER', code).then(function(data) {
            if (!data.success) { body.innerHTML = '<p class="text-red-600">Erro ao carregar.</p>'; return; }
            var det = data.device.details || {};
            var html = '<div class="space-y-2 text-sm">' + devicePhotoBlockHtml(det);
            html += '<p><strong>IP:</strong> ' + (det.ip || '-') + '</p><p><strong>Modelo:</strong> ' + (det.model || '-') + '</p>' + (data.device.observacoes ? '<p><strong>Obs.:</strong> ' + escapeHtmlDevice(data.device.observacoes) + '</p>' : '') + '</div>';
            body.innerHTML = html;
        }).catch(function() { body.innerHTML = '<p class="text-red-600">Erro.</p>'; });
    }
    window.closeDevicePrinterViewModal = hideDeviceSidePanel;

    function openPrinterEdit(code) {
        var modal = document.getElementById('devicePrinterEditModal');
        if (!modal) return;
        document.getElementById('devicePrinterEditFullCode').textContent = 'PRINTER-' + code;
        modal.querySelector('.device-edit-code').value = code;
        document.getElementById('devicePrinterEditIp').value = '';
        document.getElementById('devicePrinterEditModel').value = '';
        modal.querySelector('.device-edit-observacoes').value = '';
        resetGenericDevicePhotoUi('Printer', null);
        showModal('devicePrinterEditModal');
        fetchDevice('PRINTER', code).then(function(data) {
            if (!data.success) return;
            var m = data.device.metadata || {};
            var det = data.device.details || {};
            document.getElementById('devicePrinterEditIp').value = det.ip || m.ip || '';
            document.getElementById('devicePrinterEditModel').value = det.model || m.model || '';
            modal.querySelector('.device-edit-observacoes').value = data.device.observacoes || '';
            resetGenericDevicePhotoUi('Printer', det);
        }).catch(function() {});
    }
    window.closeDevicePrinterEditModal = function() { hideModal('devicePrinterEditModal'); };

    function openTvModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-tv', 'TV — <span class="text-gray-900 font-semibold">TV-' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openTvEdit(code);
        });
        fetchDevice('TV', code).then(function(data) {
            if (!data.success) { body.innerHTML = '<p class="text-red-600">Erro.</p>'; return; }
            var det = data.device.details || {};
            var html = '<div class="space-y-2 text-sm">' + devicePhotoBlockHtml(det);
            html += '<p><strong>Localização:</strong> ' + escapeHtmlDevice(det.location || '-') + '</p>';
            if (data.device.observacoes) html += '<p><strong>Obs.:</strong> ' + escapeHtmlDevice(data.device.observacoes) + '</p>';
            html += '</div>';
            body.innerHTML = html;
        }).catch(function() { body.innerHTML = '<p class="text-red-600">Erro.</p>'; });
    }
    window.closeDeviceTvViewModal = hideDeviceSidePanel;
    function openTvEdit(code) {
        var modal = document.getElementById('deviceTvEditModal');
        document.getElementById('deviceTvEditFullCode').textContent = 'TV-' + code;
        modal.querySelector('.device-edit-code').value = code;
        document.getElementById('deviceTvEditLocation').value = '';
        modal.querySelector('.device-edit-observacoes').value = '';
        resetGenericDevicePhotoUi('Tv', null);
        showModal('deviceTvEditModal');
        fetchDevice('TV', code).then(function(data) {
            if (!data.success) return;
            var det = data.device.details || {};
            document.getElementById('deviceTvEditLocation').value = det.location || '';
            modal.querySelector('.device-edit-observacoes').value = data.device.observacoes || '';
            resetGenericDevicePhotoUi('Tv', det);
        }).catch(function() {});
    }
    window.closeDeviceTvEditModal = function() { hideModal('deviceTvEditModal'); };

    function openScannerModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-barcode', 'Scanner — <span class="text-gray-900 font-semibold">SCAN-' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openScannerEdit(code);
        });
        fetchDevice('SCAN', code).then(function(data) {
            if (!data.success) { body.innerHTML = '<p class="text-red-600">Erro.</p>'; return; }
            var det = data.device.details || {};
            var html = '<div class="space-y-2 text-sm">' + devicePhotoBlockHtml(det);
            html += '<p><strong>Setor:</strong> ' + escapeHtmlDevice(det.sector || data.device.setor || '-') + '</p>';
            if (data.device.observacoes) html += '<p><strong>Obs.:</strong> ' + escapeHtmlDevice(data.device.observacoes) + '</p>';
            html += '</div>';
            body.innerHTML = html;
        }).catch(function() { body.innerHTML = '<p class="text-red-600">Erro.</p>'; });
    }
    window.closeDeviceScanViewModal = hideDeviceSidePanel;
    function openScannerEdit(code) {
        var modal = document.getElementById('deviceScanEditModal');
        document.getElementById('deviceScanEditFullCode').textContent = 'SCAN-' + code;
        modal.querySelector('.device-edit-code').value = code;
        document.getElementById('deviceScanEditSector').value = '';
        modal.querySelector('.device-edit-observacoes').value = '';
        resetGenericDevicePhotoUi('Scan', null);
        showModal('deviceScanEditModal');
        fetchDevice('SCAN', code).then(function(data) {
            if (!data.success) return;
            var det = data.device.details || {};
            var m = data.device.metadata || {};
            document.getElementById('deviceScanEditSector').value = det.sector || m.sector || '';
            modal.querySelector('.device-edit-observacoes').value = data.device.observacoes || '';
            resetGenericDevicePhotoUi('Scan', det);
        }).catch(function() {});
    }
    window.closeDeviceScanEditModal = function() { hideModal('deviceScanEditModal'); };

    function openPhoneModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-phone', 'Telefone — <span class="text-gray-900 font-semibold">PHONE-' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openPhoneEdit(code);
        });
        fetchDevice('PHONE', code).then(function(data) {
            if (!data.success) { body.innerHTML = '<p class="text-red-600">Erro.</p>'; return; }
            var det = data.device.details || {};
            var html = '<div class="space-y-2 text-sm">' + devicePhotoBlockHtml(det);
            html += '<p><strong>Ramal:</strong> ' + escapeHtmlDevice(det.extension || '-') + '</p>';
            if (data.device.observacoes) html += '<p><strong>Obs.:</strong> ' + escapeHtmlDevice(data.device.observacoes) + '</p>';
            html += '</div>';
            body.innerHTML = html;
        }).catch(function() { body.innerHTML = '<p class="text-red-600">Erro.</p>'; });
    }
    window.closeDevicePhoneViewModal = hideDeviceSidePanel;
    function openPhoneEdit(code) {
        var modal = document.getElementById('devicePhoneEditModal');
        document.getElementById('devicePhoneEditFullCode').textContent = 'PHONE-' + code;
        modal.querySelector('.device-edit-code').value = code;
        document.getElementById('devicePhoneEditExtension').value = '';
        modal.querySelector('.device-edit-observacoes').value = '';
        resetGenericDevicePhotoUi('Phone', null);
        showModal('devicePhoneEditModal');
        fetchDevice('PHONE', code).then(function(data) {
            if (!data.success) return;
            var det = data.device.details || {};
            document.getElementById('devicePhoneEditExtension').value = det.extension || '';
            modal.querySelector('.device-edit-observacoes').value = data.device.observacoes || '';
            resetGenericDevicePhotoUi('Phone', det);
        }).catch(function() {});
    }
    window.closeDevicePhoneEditModal = function() { hideModal('devicePhoneEditModal'); };

    function openApModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-wifi', 'Access Point — <span class="text-gray-900 font-semibold">AP-' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openApEdit(code);
        });
        fetchDevice('AP', code).then(function(data) {
            if (!data.success) { body.innerHTML = '<p class="text-red-600">Erro.</p>'; return; }
            var det = data.device.details || {};
            var html = '<div class="space-y-2 text-sm">' + devicePhotoBlockHtml(det);
            html += '<p><strong>SSID:</strong> ' + escapeHtmlDevice(det.ssid || '-') + '</p><p><strong>Localização:</strong> ' + escapeHtmlDevice(det.location || '-') + '</p>';
            if (data.device.observacoes) html += '<p><strong>Obs.:</strong> ' + escapeHtmlDevice(data.device.observacoes) + '</p>';
            html += '</div>';
            body.innerHTML = html;
        }).catch(function() { body.innerHTML = '<p class="text-red-600">Erro.</p>'; });
    }
    window.closeDeviceApViewModal = hideDeviceSidePanel;
    function openApEdit(code) {
        var modal = document.getElementById('deviceApEditModal');
        document.getElementById('deviceApEditFullCode').textContent = 'AP-' + code;
        modal.querySelector('.device-edit-code').value = code;
        document.getElementById('deviceApEditSsid').value = '';
        document.getElementById('deviceApEditLocation').value = '';
        modal.querySelector('.device-edit-observacoes').value = '';
        resetGenericDevicePhotoUi('Ap', null);
        showModal('deviceApEditModal');
        fetchDevice('AP', code).then(function(data) {
            if (!data.success) return;
            var det = data.device.details || {};
            document.getElementById('deviceApEditSsid').value = det.ssid || '';
            document.getElementById('deviceApEditLocation').value = det.location || '';
            modal.querySelector('.device-edit-observacoes').value = data.device.observacoes || '';
            resetGenericDevicePhotoUi('Ap', det);
        }).catch(function() {});
    }
    window.closeDeviceApEditModal = function() { hideModal('deviceApEditModal'); };

    function outletTypeLabel(v) {
        if (v === 'network') return 'Rede';
        if (v === 'phone') return 'Telefone';
        return 'Não definido';
    }

    function openOutletModal(code) {
        var body = deviceSidePanelBody();
        if (!body) return;
        setDeviceSidePanelTitle('fa-plug', 'Ponto — <span class="text-gray-900 font-semibold">' + escapeHtmlDevice(code) + '</span>');
        setDeviceSidePanelLoading();
        showDeviceSidePanel();
        wireDeviceSidePanelEdit(function() {
            hideDeviceSidePanel();
            openOutletEdit(code);
        });
        fetchDevice('OUTLET', code).then(function(data) {
            if (!data.success || !data.device) { body.innerHTML = '<p class="text-red-600">Erro ao carregar.</p>'; return; }
            var det = data.device.details || {};
            var html = '<div class="space-y-3 text-sm">' + devicePhotoBlockHtml(det);
            html += '<p><strong>Código do ponto:</strong> ' + escapeHtmlDevice(data.device.code || code) + '</p>';
            html += '<p><strong>Tipo do ponto:</strong> ' + escapeHtmlDevice(outletTypeLabel(det.outlet_type)) + '</p>';
            if (data.device.observacoes) html += '<p><strong>Observações:</strong> ' + escapeHtmlDevice(data.device.observacoes) + '</p>';
            html += '</div>';
            body.innerHTML = html;
        }).catch(function() { body.innerHTML = '<p class="text-red-600">Erro ao carregar.</p>'; });
    }
    window.closeDeviceOutletViewModal = hideDeviceSidePanel;

    function openOutletEdit(code) {
        var modal = document.getElementById('deviceOutletEditModal');
        if (!modal) return;
        document.getElementById('deviceOutletEditPointCode').textContent = code;
        var hid = document.getElementById('deviceOutletEditCodeHidden');
        if (hid) hid.value = code;
        var sel = document.getElementById('deviceOutletEditOutletType');
        if (sel) sel.value = '';
        var obs = document.getElementById('deviceOutletEditObservacoes');
        if (obs) obs.value = '';
        resetOutletPhotoUi(null);
        showModal('deviceOutletEditModal');
        fetchDevice('OUTLET', code).then(function(data) {
            if (!data.success || !data.device) return;
            var det = data.device.details || {};
            var m = data.device.metadata || {};
            var ot = det.outlet_type || m.outlet_type || '';
            if (sel) sel.value = (ot === 'network' || ot === 'phone') ? ot : '';
            if (obs) obs.value = data.device.observacoes || '';
            resetOutletPhotoUi(det);
        }).catch(function() {});
    }
    window.closeDeviceOutletEditModal = function() { hideModal('deviceOutletEditModal'); };

    function openDevicePanel(type, code) {
        switch (type) {
            case 'SEAT': openSeatModal(code); break;
            case 'PRINTER': openPrinterModal(code); break;
            case 'TV': openTvModal(code); break;
            case 'SCAN': openScannerModal(code); break;
            case 'PHONE': openPhoneModal(code); break;
            case 'AP': openApModal(code); break;
            case 'OUTLET': openOutletModal(code); break;
            default: console.warn('Tipo não suportado:', type);
        }
    }
    function openDeviceModalByType(type, code) {
        openDevicePanel(type, code);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('mapaContainer');
        var wrapper = document.getElementById('svgWrapper');
        if (container && wrapper) {
            mapApplyTransform();
            container.style.cursor = 'grab';
            container.addEventListener('mousedown', function(e) {
                if (e.target.closest('[data-code].device')) return;
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
                container.style.cursor = e.target.closest('[data-code].device') ? 'pointer' : 'grab';
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

        document.addEventListener('click', function(e) {
            var el = e.target.closest('[data-code].device');
            var mapa = document.getElementById('mapaContainer');
            if (!el || !mapa || !mapa.contains(el)) return;
            if (el.classList.contains('map-layer-filter-hidden')) return;
            var type = el.getAttribute('data-type');
            var code = el.getAttribute('data-code');
            if (!type || !code) return;
            e.preventDefault();
            e.stopPropagation();
            openDevicePanel(type, code);
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

        var mapSearchInput = document.getElementById('mapCollaboratorSearch');
        if (mapSearchInput) {
            mapSearchInput.addEventListener('input', function() {
                clearTimeout(seatSearchDebounce);
                seatSearchDebounce = setTimeout(runSeatSearchFromInput, 280);
            });
            mapSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(seatSearchDebounce);
                    runSeatSearchFromInput();
                }
            });
        }
        var mapSearchPrev = document.getElementById('mapSearchPrev');
        var mapSearchNext = document.getElementById('mapSearchNext');
        if (mapSearchPrev) {
            mapSearchPrev.addEventListener('click', function() {
                focusSeatSearchResult(seatSearchIndex - 1);
            });
        }
        if (mapSearchNext) {
            mapSearchNext.addEventListener('click', function() {
                focusSeatSearchResult(seatSearchIndex + 1);
            });
        }
        var deviceSidePanelCloseBtn = document.getElementById('deviceSidePanelClose');
        if (deviceSidePanelCloseBtn) {
            deviceSidePanelCloseBtn.addEventListener('click', hideDeviceSidePanel);
        }

        var mapFilterPanel = document.getElementById('mapFilterPanel');
        var mapFilterToggleBtn = document.getElementById('mapFilterToggleBtn');
        var mapFilterPanelClose = document.getElementById('mapFilterPanelClose');
        function setMapFilterPanelOpen(open) {
            if (!mapFilterPanel || !mapFilterToggleBtn) return;
            if (open) {
                mapFilterPanel.classList.remove('hidden');
                mapFilterToggleBtn.setAttribute('aria-expanded', 'true');
                mapFilterToggleBtn.classList.add('ring-2', 'ring-amber-500', 'ring-offset-1');
            } else {
                mapFilterPanel.classList.add('hidden');
                mapFilterToggleBtn.setAttribute('aria-expanded', 'false');
                mapFilterToggleBtn.classList.remove('ring-2', 'ring-amber-500', 'ring-offset-1');
            }
        }
        if (mapFilterToggleBtn && mapFilterPanel) {
            mapFilterToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                setMapFilterPanelOpen(mapFilterPanel.classList.contains('hidden'));
            });
        }
        if (mapFilterPanelClose) {
            mapFilterPanelClose.addEventListener('click', function(e) {
                e.stopPropagation();
                setMapFilterPanelOpen(false);
            });
        }
        [].forEach.call(document.querySelectorAll('.map-filter-check'), function(cb) {
            cb.addEventListener('change', function() {
                var t = cb.getAttribute('data-filter-type');
                if (!t || mapLayerFilters[t] === undefined) return;
                mapLayerFilters[t] = cb.checked;
                applyMapLayerFilters();
                if (t === 'SEAT' && !cb.checked) {
                    clearSeatSearchHighlight();
                    seatSearchResults = [];
                    seatSearchIndex = 0;
                    updateSeatSearchStatusUI();
                }
                notifyMapFilterChange(t);
            });
        });

        function markDeviceLeaf(el) {
            var t = (el.textContent || '').trim();
            if (el.children.length > 0) return false;
            if (DEVICE_REGEX.test(t)) {
                var parts = t.split('-');
                var dtype = parts[0];
                var dcode = parts.slice(1).join('-');
                el.setAttribute('data-type', dtype);
                el.setAttribute('data-code', dcode);
                el.setAttribute('data-device-full', t);
                el.setAttribute('data-original-text', t);
            } else if (OUTLET_REGEX.test(t)) {
                el.setAttribute('data-type', 'OUTLET');
                el.setAttribute('data-code', t);
                el.setAttribute('data-device-full', 'OUTLET-' + t);
                el.setAttribute('data-original-text', t);
            } else {
                return false;
            }
            el.classList.add('device');
            el.style.cursor = 'pointer';
            el.style.pointerEvents = 'auto';
            return true;
        }
        function initDeviceMarkers() {
            var svgContainer = document.getElementById('svgContainer');
            if (!svgContainer) return;
            var svgEl = svgContainer.querySelector('svg') || svgContainer;
            var count = 0;
            [].forEach.call(svgEl.querySelectorAll('text'), function(el) { if (markDeviceLeaf(el)) count++; });
            [].forEach.call(svgEl.querySelectorAll('tspan'), function(el) { if (markDeviceLeaf(el)) count++; });
            [].forEach.call(svgEl.querySelectorAll('foreignObject'), function(fo) {
                [].forEach.call(fo.querySelectorAll('*'), function(el) { if (markDeviceLeaf(el)) count++; });
            });
            applyDeviceLabels();
            if (count === 0) {
                console.warn('EngeHub mapa: nenhum dispositivo (TIPO-… ou ponto A01) encontrado no SVG.');
            } else if (Object.keys(deviceLabels).some(function(k) { return deviceLabels[k]; })) {
                setLabelToggle(true);
            }
        }
        initDeviceMarkers();
        setTimeout(initDeviceMarkers, 200);

        var seatForm = document.getElementById('deviceSeatEditForm');
        var seatPhotoInput = document.getElementById('deviceSeatEditWorkstationPhoto');
        var seatPhotoPreviewWrap = document.getElementById('deviceSeatEditPhotoPreviewWrap');
        var seatPhotoPreviewImg = document.getElementById('deviceSeatEditPhotoPreview');
        if (seatPhotoInput && seatPhotoPreviewWrap && seatPhotoPreviewImg) {
            seatPhotoInput.addEventListener('change', function() {
                var f = seatPhotoInput.files && seatPhotoInput.files[0];
                if (!f) {
                    return;
                }
                var url = URL.createObjectURL(f);
                seatPhotoPreviewImg.src = url;
                seatPhotoPreviewWrap.classList.remove('hidden');
            });
        }
        if (canEditDevices && deviceUpdateBase && seatForm) {
            seatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var code = document.getElementById('deviceSeatEditCodeRaw').value;
                var fd = new FormData(seatForm);
                fd.append('_method', 'PUT');
                var collab = document.getElementById('deviceSeatEditCollaboratorName');
                var cn = collab && collab.value ? String(collab.value).trim() : '';
                fetch(devicePutUrl('SEAT', code), {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: fd
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        var full = 'SEAT-' + code;
                        deviceLabels[full] = cn || null;
                        setLabelToggle(true);
                        closeDeviceSeatEditModal();
                        mapDeviceNotifySuccess(data.message);
                    } else {
                        mapDeviceNotifyError(mapDeviceSaveErrorMessage(data));
                    }
                }).catch(function() { mapDeviceNotifyError('Erro ao atualizar.'); });
            });
        }

        function bindSimpleDeviceEdit(formId, type) {
            var form = document.getElementById(formId);
            if (!canEditDevices || !deviceUpdateBase || !form) return;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var code = form.querySelector('.device-edit-code').value;
                var fd = new FormData(form);
                fd.append('_method', 'PUT');
                fetch(devicePutUrl(type, code), {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: fd
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        var modalWrap = form.closest('.device-modal');
                        if (modalWrap && modalWrap.id) hideModal(modalWrap.id);
                        mapDeviceNotifySuccess(data.message);
                    } else {
                        mapDeviceNotifyError(mapDeviceSaveErrorMessage(data));
                    }
                }).catch(function() { mapDeviceNotifyError('Erro ao salvar.'); });
            });
        }
        bindSimpleDeviceEdit('devicePrinterEditForm', 'PRINTER');
        bindSimpleDeviceEdit('deviceTvEditForm', 'TV');
        bindSimpleDeviceEdit('deviceScanEditForm', 'SCAN');
        bindSimpleDeviceEdit('devicePhoneEditForm', 'PHONE');
        bindSimpleDeviceEdit('deviceApEditForm', 'AP');

        ['Printer', 'Tv', 'Scan', 'Phone', 'Ap', 'Outlet'].forEach(function(suf) {
            wireDeviceEditPhotoPreview(suf);
        });

        var outletForm = document.getElementById('deviceOutletEditForm');
        if (canEditDevices && deviceUpdateBase && outletForm) {
            outletForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var codeEl = document.getElementById('deviceOutletEditCodeHidden');
                var code = codeEl ? codeEl.value : '';
                if (!code) return;
                var fd = new FormData(outletForm);
                fd.append('_method', 'PUT');
                fetch(devicePutUrl('OUTLET', code), {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: fd
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        closeDeviceOutletEditModal();
                        mapDeviceNotifySuccess(data.message);
                    } else {
                        mapDeviceNotifyError(mapDeviceSaveErrorMessage(data));
                    }
                }).catch(function() { mapDeviceNotifyError('Erro ao salvar.'); });
            });
        }
    });
})();
