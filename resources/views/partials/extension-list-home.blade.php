{{-- Botão flutuante + modal tela cheia: SVG da lista de ramais com pan/zoom e busca por nome (estilo mapa de rede). --}}
@php
    /** @var string $extensionListSvgUrl */
@endphp

<button type="button"
        id="extListFabBtn"
        class="ext-list-fab fixed bottom-6 right-6 z-[90] flex items-center gap-2 rounded-full border-2 border-gray-900 bg-[#E9B32C] px-4 py-3 text-sm font-bold text-black outline-none transition hover:bg-[#d19d20] focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 sm:bottom-8 sm:right-8 sm:px-5 sm:py-3.5"
        title="Abrir lista de ramais em tela cheia"
        aria-label="Lista de Ramais">
    <i class="fas fa-phone-alt text-base sm:text-lg" aria-hidden="true"></i>
    <span class="max-w-[9.5rem] leading-tight sm:max-w-none">Lista de Ramais</span>
</button>

<div id="extListFullscreenModal"
     class="ext-list-fs-modal fixed inset-0 z-[200] flex items-center justify-center bg-black/55 p-3 sm:p-5 md:p-6"
     role="dialog"
     aria-modal="true"
     aria-labelledby="extListFullscreenTitle">
    <div class="ext-list-fs-panel flex h-[calc(100dvh-1.5rem)] max-h-[calc(100dvh-1.5rem)] w-full max-w-[calc(100vw-1.5rem)] min-h-0 flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
        <div class="flex flex-shrink-0 flex-wrap items-center gap-x-2 gap-y-2 border-b border-gray-200 bg-gray-50 px-2 py-2 sm:px-3">
            <h2 id="extListFullscreenTitle" class="flex flex-shrink-0 items-center text-sm font-bold text-gray-900 sm:text-base">
                <i class="fas fa-phone-alt mr-1.5 text-amber-600 sm:mr-2"></i>
                Lista de Ramais — tela cheia
            </h2>
            <div class="flex min-w-0 flex-1 flex-wrap items-center gap-2 justify-end sm:justify-center">
                <label class="sr-only" for="extListSearchInput">Buscar por nome</label>
                <input type="search" id="extListSearchInput" name="ext_list_search" autocomplete="off" placeholder="Buscar por nome…"
                       class="min-w-[8rem] flex-1 rounded-md border border-gray-300 px-2 py-1.5 text-sm shadow-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 sm:max-w-xs sm:flex-none">
                <div id="extListSearchNav" class="hidden flex items-center gap-0.5 shrink-0">
                    <button type="button" id="extListSearchPrev" class="h-9 w-9 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300" title="Resultado anterior">&lsaquo;</button>
                    <span id="extListSearchStatus" class="min-w-[5.5rem] px-1 text-center text-xs font-semibold text-gray-800 tabular-nums"></span>
                    <button type="button" id="extListSearchNext" class="h-9 w-9 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300" title="Próximo resultado">&rsaquo;</button>
                </div>
                <span id="extListSearchFeedback" class="hidden text-xs text-amber-800 max-w-[11rem] leading-tight"></span>
            </div>
            <div class="flex flex-shrink-0 flex-wrap items-center justify-end gap-1.5">
                <span class="hidden text-xs text-gray-500 lg:inline">Zoom:</span>
                <button type="button" id="extListZoomOutBtn" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100" title="Diminuir zoom">&minus;</button>
                <span id="extListZoomLabel" class="min-w-[3rem] text-center text-xs font-semibold text-gray-800 tabular-nums sm:text-sm">100%</span>
                <button type="button" id="extListZoomInBtn" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100" title="Aumentar zoom">&plus;</button>
                <button type="button" id="extListZoomResetBtn" class="rounded-lg border border-gray-300 bg-white px-2 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 sm:px-3">Redefinir</button>
                <button type="button" id="extListCloseFullscreenBtn"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-gray-800 sm:px-4">
                    <i class="fas fa-times"></i>
                    Fechar
                </button>
            </div>
        </div>
        <div id="extListMapContainer" class="relative min-h-0 flex-1 overflow-hidden bg-gray-100">
            <div id="extListLoading" class="flex flex-col items-center justify-center gap-2 py-16 text-gray-600">
                <div class="h-10 w-10 animate-spin rounded-full border-2 border-amber-500 border-t-transparent"></div>
                <p class="text-sm">Carregando lista…</p>
            </div>
            <div id="extListError" class="hidden px-6 py-12 text-center text-sm text-red-600"></div>
            <div id="extListSvgWrapper" class="ext-list-svg-wrapper hidden absolute left-0 top-0 block p-4">
                <div id="extListSvgMount" class="ext-list-svg-theme"></div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var SVG_URL = @json($extensionListSvgUrl);
    if (!SVG_URL) return;

    var modal = document.getElementById('extListFullscreenModal');
    var fab = document.getElementById('extListFabBtn');
    var closeBtn = document.getElementById('extListCloseFullscreenBtn');
    var mapContainer = document.getElementById('extListMapContainer');
    var loadingEl = document.getElementById('extListLoading');
    var errEl = document.getElementById('extListError');
    var wrapEl = document.getElementById('extListSvgWrapper');
    var mountEl = document.getElementById('extListSvgMount');
    var searchInput = document.getElementById('extListSearchInput');
    var searchNav = document.getElementById('extListSearchNav');
    var searchPrev = document.getElementById('extListSearchPrev');
    var searchNext = document.getElementById('extListSearchNext');
    var searchStatus = document.getElementById('extListSearchStatus');
    var searchFeedback = document.getElementById('extListSearchFeedback');
    var zoomInBtn = document.getElementById('extListZoomInBtn');
    var zoomOutBtn = document.getElementById('extListZoomOutBtn');
    var zoomResetBtn = document.getElementById('extListZoomResetBtn');
    var zoomLabel = document.getElementById('extListZoomLabel');

    var svgRoot = null;
    var searchTargets = [];
    var searchMatches = [];
    var searchIndex = 0;
    var searchDebounce = null;
    var loadPromise = null;

    var mapZoomLevel = 1;
    var mapTranslateX = 0;
    var mapTranslateY = 0;
    var mapPanning = false;
    var mapPanStartX = 0, mapPanStartY = 0;
    var mapPanStartTranslateX = 0, mapPanStartTranslateY = 0;
    var mapRAF = null;
    function normalizeSearch(s) {
        return String(s || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function hasLetter(s) {
        return /[a-zA-ZáàãâéíóôúçÁÀÃÂÉÍÓÔÚÇ]/.test(s);
    }

    function isLeafTextGraphic(el) {
        var kids = el.querySelectorAll('text, tspan');
        for (var i = 0; i < kids.length; i++) {
            if (kids[i] !== el) return false;
        }
        return true;
    }

    function insideForeignObject(el) {
        return !!(el.closest && el.closest('foreignObject'));
    }

    function buildSearchTargets(svg) {
        var targets = [];
        var seen = new WeakSet();
        function add(el) {
            if (!el || seen.has(el)) return;
            seen.add(el);
            targets.push(el);
        }
        svg.querySelectorAll('text, tspan').forEach(function(el) {
            if (!isLeafTextGraphic(el)) return;
            if (insideForeignObject(el)) return;
            var t = (el.textContent || '').replace(/\s+/g, ' ').trim();
            if (t.length < 2 || !hasLetter(t)) return;
            add(el);
        });
        svg.querySelectorAll('foreignObject').forEach(function(fo) {
            var t = (fo.textContent || '').replace(/\s+/g, ' ').trim();
            if (t.length < 2 || !hasLetter(t)) return;
            add(fo);
        });
        return targets;
    }

    function labelForTarget(el) {
        return (el.textContent || '').replace(/\s+/g, ' ').trim();
    }

    function clearSearchHighlightClasses() {
        if (!svgRoot) return;
        svgRoot.querySelectorAll('.ext-list-svg-hit, .ext-list-svg-active').forEach(function(n) {
            n.classList.remove('ext-list-svg-hit', 'ext-list-svg-active');
        });
    }

    function applySearchHighlightClasses() {
        clearSearchHighlightClasses();
        searchMatches.forEach(function(el) {
            el.classList.add('ext-list-svg-hit');
        });
        if (searchMatches[searchIndex]) {
            searchMatches[searchIndex].classList.add('ext-list-svg-active');
        }
    }

    function mapApplyTransform() {
        if (!wrapEl) return;
        var s = mapZoomLevel;
        if (Math.abs(s - 1) < 0.0001) {
            s = 1;
        }
        var tx = Math.round(mapTranslateX);
        var ty = Math.round(mapTranslateY);
        wrapEl.style.transform = 'translate3d(' + tx + 'px,' + ty + 'px,0) scale(' + s + ')';
        if (zoomLabel) zoomLabel.textContent = Math.round(mapZoomLevel * 100) + '%';
    }

    function mapScheduleTransform() {
        if (mapRAF !== null) return;
        mapRAF = requestAnimationFrame(function() {
            mapRAF = null;
            mapApplyTransform();
        });
    }

    function mapZoomAtPoint(clientX, clientY, zoomIn) {
        var rect = mapContainer ? mapContainer.getBoundingClientRect() : null;
        if (!rect) return;
        var mouseX = clientX - rect.left;
        var mouseY = clientY - rect.top;
        var newScale = zoomIn ? Math.min(5, mapZoomLevel + 0.25) : Math.max(0.25, mapZoomLevel - 0.25);
        if (newScale === mapZoomLevel) return;
        var contentX = (mouseX - mapTranslateX) / mapZoomLevel;
        var contentY = (mouseY - mapTranslateY) / mapZoomLevel;
        mapTranslateX = mouseX - contentX * newScale;
        mapTranslateY = mouseY - contentY * newScale;
        mapZoomLevel = newScale;
        mapApplyTransform();
    }

    /** Zoom 100% e centraliza o SVG na área útil (horizontal e vertical). */
    function mapResetView() {
        centerSvgInContainer();
    }

    function centerSvgInContainer() {
        if (!mapContainer || !wrapEl) return;
        mapZoomLevel = 1;
        mapTranslateX = 0;
        mapTranslateY = 0;
        mapApplyTransform();
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                if (!mapContainer || !wrapEl) return;
                var cw = mapContainer.clientWidth;
                var ch = mapContainer.clientHeight;
                var sw = wrapEl.offsetWidth;
                var sh = wrapEl.offsetHeight;
                mapTranslateX = Math.round((cw - sw) / 2);
                mapTranslateY = Math.round((ch - sh) / 2);
                mapApplyTransform();
            });
        });
    }

    /** Apenas alterna o realce “ativo” entre ocorrências, sem mover o mapa. */
    function focusSearchResult(index) {
        if (!searchMatches.length) return;
        if (index < 0) index = searchMatches.length - 1;
        if (index >= searchMatches.length) index = 0;
        searchIndex = index;
        applySearchHighlightClasses();
        updateSearchStatusUI();
    }

    function updateSearchStatusUI() {
        if (!searchNav || !searchStatus) return;
        if (!searchMatches.length) {
            searchNav.classList.add('hidden');
            searchStatus.textContent = '';
            return;
        }
        searchNav.classList.remove('hidden');
        searchStatus.textContent = (searchIndex + 1) + ' / ' + searchMatches.length;
    }

    function runSearchFromInput() {
        if (!searchFeedback || !svgRoot) return;
        var qRaw = searchInput ? String(searchInput.value || '').trim() : '';
        searchFeedback.classList.add('hidden');
        searchFeedback.textContent = '';
        searchMatches = [];
        searchIndex = 0;
        clearSearchHighlightClasses();

        if (!qRaw) {
            updateSearchStatusUI();
            return;
        }

        var q = normalizeSearch(qRaw);
        searchTargets.forEach(function(el) {
            var lab = normalizeSearch(labelForTarget(el));
            if (lab.indexOf(q) !== -1) {
                searchMatches.push(el);
            }
        });

        if (!searchMatches.length) {
            searchFeedback.textContent = 'Nenhum resultado encontrado';
            searchFeedback.classList.remove('hidden');
            updateSearchStatusUI();
            if (typeof window.showToast === 'function') {
                window.showToast('Nenhum resultado encontrado para esta busca.', 'warning', 3500);
            }
            return;
        }
        searchIndex = 0;
        applySearchHighlightClasses();
        updateSearchStatusUI();
    }

    function showToastSafe(msg, type, ms) {
        if (typeof window.showToast === 'function') {
            window.showToast(msg, type || 'success', ms || 3200);
        }
    }

    function openModal() {
        if (!modal) return;
        modal.classList.add('ext-list-fs-open');
        document.body.classList.add('overflow-hidden');
        ensureSvgLoaded().then(function() {
            if (loadingEl) loadingEl.classList.add('hidden');
            if (wrapEl) wrapEl.classList.remove('hidden');
            if (searchTargets.length === 0 && svgRoot) {
                searchTargets = buildSearchTargets(svgRoot);
            }
            centerSvgInContainer();
            runSearchFromInput();
        }).catch(function(err) {
            console.error(err);
            if (loadingEl) loadingEl.classList.add('hidden');
            if (errEl) {
                errEl.classList.remove('hidden');
                errEl.textContent = 'Não foi possível carregar a lista. Tente novamente ou reenvie o SVG no painel administrativo.';
            }
            showToastSafe('Erro ao carregar a lista de ramais.', 'error', 4000);
        });
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('ext-list-fs-open');
        document.body.classList.remove('overflow-hidden');
    }

    function ensureSvgLoaded() {
        if (svgRoot) return Promise.resolve();
        if (loadPromise) return loadPromise;
        loadPromise = fetch(SVG_URL, {
            credentials: 'same-origin',
            headers: { 'Accept': 'image/svg+xml, application/xml, text/xml, */*' }
        }).then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.text();
        }).then(function(txt) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(txt, 'image/svg+xml');
            var errNode = doc.querySelector('parsererror');
            if (errNode) throw new Error('SVG inválido');
            var svg = doc.querySelector('svg');
            if (!svg) throw new Error('Sem elemento svg');
            if (!mountEl) throw new Error('Mount ausente');
            mountEl.innerHTML = '';
            mountEl.appendChild(document.importNode(svg, true));
            svgRoot = mountEl.querySelector('svg');
            if (!svgRoot) throw new Error('SVG não montado');
            searchTargets = buildSearchTargets(svgRoot);
            if (errEl) errEl.classList.add('hidden');
            return svgRoot;
        });
        return loadPromise;
    }

    if (fab) fab.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Escape') return;
        if (modal && modal.classList.contains('ext-list-fs-open')) {
            closeModal();
            e.preventDefault();
        }
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(runSearchFromInput, 200);
        });
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchDebounce);
                runSearchFromInput();
            }
        });
    }
    if (searchPrev) searchPrev.addEventListener('click', function() { focusSearchResult(searchIndex - 1); });
    if (searchNext) searchNext.addEventListener('click', function() { focusSearchResult(searchIndex + 1); });

    if (zoomInBtn) zoomInBtn.addEventListener('click', function() {
        if (!mapContainer) return;
        var r = mapContainer.getBoundingClientRect();
        mapZoomAtPoint(r.left + r.width / 2, r.top + r.height / 2, true);
    });
    if (zoomOutBtn) zoomOutBtn.addEventListener('click', function() {
        if (!mapContainer) return;
        var r = mapContainer.getBoundingClientRect();
        mapZoomAtPoint(r.left + r.width / 2, r.top + r.height / 2, false);
    });
    if (zoomResetBtn) zoomResetBtn.addEventListener('click', mapResetView);

    if (mapContainer && wrapEl) {
        mapContainer.addEventListener('mousedown', function(e) {
            if (e.target.closest && e.target.closest('a[href], button')) return;
            e.preventDefault();
            mapPanning = true;
            mapPanStartX = e.clientX;
            mapPanStartY = e.clientY;
            mapPanStartTranslateX = mapTranslateX;
            mapPanStartTranslateY = mapTranslateY;
            mapContainer.style.cursor = 'grabbing';
            mapContainer.style.userSelect = 'none';
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
                mapContainer.style.cursor = 'grab';
                mapContainer.style.userSelect = '';
            }
        });

        mapContainer.addEventListener('wheel', function(e) {
            if (!modal || !modal.classList.contains('ext-list-fs-open')) return;
            e.preventDefault();
            mapZoomAtPoint(e.clientX, e.clientY, e.deltaY < 0);
        }, { passive: false });

        mapContainer.style.cursor = 'grab';
    }
})();
</script>
