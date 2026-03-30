{{-- Tour guiado dos controles do mapa de rede: primeira visita, localStorage e botão "Tutorial". --}}
<script>
(function() {
    var LS_NEVER = 'engehub_network_map_tutorial_never';
    var LS_AUTO_DONE = 'engehub_network_map_tutorial_auto_v1_done';

    var steps = [
        {
            selector: '#mapFilterToggleBtn',
            title: 'Filtro e camadas',
            body: 'Use este botão para abrir <strong>Camadas do mapa</strong>. Você pode mostrar ou ocultar tomadas, mesas, impressoras, TVs, telefones e access points — a alteração é imediata.'
        },
        {
            selector: '#mapTutorialSearchWrap',
            title: 'Buscar colaborador',
            body: 'Digite um nome para localizar a <strong>mesa</strong> no desenho. Quando houver vários resultados, use as setas para navegar entre eles.'
        },
        {
            selector: '#mapTutorialLabelToggles',
            title: 'Códigos e nomes',
            body: 'Alterne entre <strong>Códigos</strong> (identificadores técnicos) e <strong>Nomes</strong> (pessoa ou recurso) exibidos nas mesas e equipamentos.'
        },
        {
            selector: '#mapTutorialZoomControls',
            title: 'Zoom e visão',
            body: 'Use <strong>+</strong> e <strong>−</strong> para ampliar ou reduzir o mapa. <strong>Reset</strong> restaura o zoom inicial e a posição.'
        },
        {
            randomDevice: true,
            selector: '#mapaContainer',
            title: 'Detalhes ao clicar',
            body: 'Clique em uma <strong>mesa</strong>, <strong>tomada</strong>, impressora ou outro elemento no desenho (como o exemplo destacado) para abrir o painel com <strong>informações</strong> do item. Se você tiver permissão de edição, também poderá alterar dados por ali.'
        }
    ];

    var backdropEl = null;
    var tooltipEl = null;
    var highlightClass = 'network-map-tutorial-highlight';
    var searchHighlightClass = 'device-search-highlight';
    var currentIndex = -1;
    var lastTarget = null;
    var tourListenersOn = false;
    /** Passo 5/5: balão fixo à esquerda da área do mapa (sem seguir o retângulo da mesa). */
    var useLeftAnchoredTooltip = false;

    function shouldAutoStart() {
        try {
            if (localStorage.getItem(LS_NEVER) === '1') return false;
            if (localStorage.getItem(LS_AUTO_DONE) === '1') return false;
        } catch (e) {}
        return true;
    }

    function markAutoDone() {
        try { localStorage.setItem(LS_AUTO_DONE, '1'); } catch (e) {}
    }

    function markNever() {
        try {
            localStorage.setItem(LS_NEVER, '1');
            localStorage.setItem(LS_AUTO_DONE, '1');
        } catch (e) {}
    }

    function clearHighlight() {
        if (lastTarget) {
            lastTarget.classList.remove(highlightClass);
            lastTarget.classList.remove(searchHighlightClass);
            lastTarget.style.zIndex = '';
            if (lastTarget.dataset.nmtTutorialPosition === '1') {
                lastTarget.style.position = '';
                delete lastTarget.dataset.nmtTutorialPosition;
            }
            lastTarget = null;
        }
    }

    function pickRandomTutorialDevice() {
        var root = document.getElementById('svgContainer') || document.querySelector('.svg-map-theme');
        if (!root) return null;
        function visibleList(sel) {
            var out = [];
            var all = root.querySelectorAll(sel);
            for (var i = 0; i < all.length; i++) {
                var el = all[i];
                if (el.classList.contains('map-layer-filter-hidden')) continue;
                out.push(el);
            }
            return out;
        }
        var seats = visibleList('.device[data-type="SEAT"][data-code]');
        if (seats.length > 0) {
            return seats[Math.floor(Math.random() * seats.length)];
        }
        var any = visibleList('.device[data-code]');
        if (any.length === 0) return null;
        return any[Math.floor(Math.random() * any.length)];
    }

    function removeTourListeners() {
        if (!tourListenersOn) return;
        window.removeEventListener('resize', onResize);
        window.removeEventListener('keydown', onKey);
        tourListenersOn = false;
    }

    function onResize() {
        if (currentIndex >= 0 && lastTarget && tooltipEl) placeTooltip(lastTarget);
    }

    function onKey(e) {
        if (currentIndex < 0) return;
        if (e.key === 'Escape') {
            markAutoDone();
            destroyTour();
        }
    }

    function destroyTour() {
        removeTourListeners();
        clearHighlight();
        if (backdropEl && backdropEl.parentNode) backdropEl.parentNode.removeChild(backdropEl);
        if (tooltipEl && tooltipEl.parentNode) tooltipEl.parentNode.removeChild(tooltipEl);
        backdropEl = null;
        tooltipEl = null;
        currentIndex = -1;
        useLeftAnchoredTooltip = false;
        document.body.classList.remove('network-map-tutorial-active');
    }

    function placeTooltip(target) {
        if (!tooltipEl || !target) return;
        var pad = 10;
        var tw = tooltipEl.offsetWidth || 300;
        var th = tooltipEl.offsetHeight || 140;
        var left;
        var top;

        if (useLeftAnchoredTooltip) {
            var mapBox = document.querySelector('.mapa-rede-forcelight');
            if (mapBox) {
                var mr = mapBox.getBoundingClientRect();
                var insetLeft = 22;
                left = mr.left + insetLeft;
                top = mr.top + mr.height * 0.5 - th * 0.5;
            } else {
                var rectMap = target.getBoundingClientRect();
                left = rectMap.left + 22;
                top = rectMap.top + rectMap.height * 0.5 - th * 0.5;
            }
        } else {
            var rect = target.getBoundingClientRect();
            if (target.id === 'mapaContainer') {
                left = rect.left + rect.width / 2 - tw / 2;
                top = rect.top + Math.min(Math.max(72, rect.height * 0.18), rect.height - th - pad * 2);
            } else {
                left = rect.left + rect.width / 2 - tw / 2;
                top = rect.bottom + pad;
                if (top + th > window.innerHeight - pad) {
                    top = rect.top - th - pad;
                }
            }
        }

        left = Math.max(pad, Math.min(left, window.innerWidth - tw - pad));
        top = Math.max(pad, Math.min(top, window.innerHeight - th - pad));
        tooltipEl.style.left = left + 'px';
        tooltipEl.style.top = top + 'px';
    }

    function showStep(index) {
        if (index < 0 || index >= steps.length) {
            destroyTour();
            return;
        }
        var step = steps[index];
        var target = null;
        var deviceHighlightOnly = false;

        if (step.randomDevice) {
            var dev = pickRandomTutorialDevice();
            if (dev) {
                target = dev;
                deviceHighlightOnly = true;
            }
        }
        if (!target && step.selector) {
            target = document.querySelector(step.selector);
        }
        if (!target) {
            destroyTour();
            return;
        }

        currentIndex = index;
        clearHighlight();
        lastTarget = target;
        useLeftAnchoredTooltip = !!step.randomDevice;

        if (!deviceHighlightOnly) {
            try { target.scrollIntoView({ block: 'nearest', behavior: 'smooth', inline: 'nearest' }); } catch (e) {}
            if (target.id !== 'mapaContainer') {
                var cs = window.getComputedStyle(target);
                if (cs.position === 'static') {
                    target.style.position = 'relative';
                    target.dataset.nmtTutorialPosition = '1';
                }
                target.classList.add(highlightClass);
                target.style.zIndex = '273';
            }
        }

        /* Passo 5: destaque + teletransporte via transform (sem scroll) no bloco abaixo. */

        var titleEl = document.getElementById('nmt-title');
        var bodyEl = document.getElementById('nmt-body');
        var stepEl = document.getElementById('nmt-step');
        if (titleEl) titleEl.textContent = step.title;
        if (bodyEl) bodyEl.innerHTML = step.body;
        if (stepEl) stepEl.textContent = (index + 1) + '/' + steps.length;

        if (deviceHighlightOnly) {
            target.classList.add(searchHighlightClass);
            requestAnimationFrame(function() {
                if (typeof window.engeHubNetworkMapFocusOnElement === 'function') {
                    window.engeHubNetworkMapFocusOnElement(target, { animated: true });
                }
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() { placeTooltip(target); });
                });
            });
        } else {
            requestAnimationFrame(function() {
                requestAnimationFrame(function() { placeTooltip(target); });
            });
        }
    }

    function buildUi() {
        destroyTour();
        document.body.classList.add('network-map-tutorial-active');

        backdropEl = document.createElement('div');
        backdropEl.id = 'nmt-backdrop';
        backdropEl.className = 'fixed inset-0 z-[270] bg-black/40';
        backdropEl.setAttribute('aria-hidden', 'true');

        tooltipEl = document.createElement('div');
        tooltipEl.id = 'nmt-tooltip';
        tooltipEl.className = 'fixed z-[272] w-[min(22rem,calc(100vw-2rem))] rounded-xl border border-gray-200 bg-white p-4 shadow-2xl pointer-events-auto';
        tooltipEl.setAttribute('role', 'dialog');
        tooltipEl.setAttribute('aria-modal', 'true');
        tooltipEl.innerHTML =
            '<div class="flex items-start justify-between gap-2 mb-1.5">' +
            '<p id="nmt-title" class="text-sm font-semibold text-gray-900 leading-tight flex-1 min-w-0"></p>' +
            '<span id="nmt-step" class="shrink-0 rounded-md border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-bold tabular-nums text-amber-900" aria-label="Passo do tutorial"></span>' +
            '</div>' +
            '<p id="nmt-body" class="text-xs text-gray-600 leading-snug mb-4"></p>' +
            '<div class="flex flex-wrap items-center gap-x-3 gap-y-2">' +
            '<button type="button" id="nmt-never" class="text-xs text-gray-500 hover:text-gray-800 underline decoration-gray-400 underline-offset-2">Não ver mais</button>' +
            '<div class="flex flex-wrap gap-2 ml-auto">' +
            '<button type="button" id="nmt-skip" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">Pular tutorial</button>' +
            '<button type="button" id="nmt-next" class="rounded-md px-3 py-1.5 text-xs font-semibold text-black shadow-sm" style="background-color:#E9B32C">Entendi</button>' +
            '</div></div>';

        document.body.appendChild(backdropEl);
        document.body.appendChild(tooltipEl);

        document.getElementById('nmt-never').addEventListener('click', function() {
            markNever();
            destroyTour();
        });
        document.getElementById('nmt-skip').addEventListener('click', function() {
            markAutoDone();
            destroyTour();
        });
        document.getElementById('nmt-next').addEventListener('click', function() {
            if (currentIndex >= steps.length - 1) {
                markAutoDone();
                destroyTour();
            } else {
                showStep(currentIndex + 1);
            }
        });

        window.addEventListener('resize', onResize);
        window.addEventListener('keydown', onKey);
        tourListenersOn = true;
    }

    function startTour() {
        if (!document.querySelector(steps[0].selector)) return;
        buildUi();
        showStep(0);
    }

    function init() {
        var replay = document.getElementById('networkMapTutorialReplayBtn');
        if (replay) {
            replay.addEventListener('click', function(e) {
                e.preventDefault();
                startTour();
            });
        }
        if (shouldAutoStart()) {
            setTimeout(function() {
                if (document.querySelector(steps[0].selector)) {
                    startTour();
                }
            }, 850);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
<style>
.network-map-tutorial-highlight {
    outline: 3px solid #E9B32C !important;
    outline-offset: 3px !important;
    box-shadow: 0 0 0 4px rgba(233, 179, 44, 0.35) !important;
    border-radius: 0.375rem;
}
body.network-map-tutorial-active #nmt-backdrop {
    pointer-events: none;
}
body.network-map-tutorial-active #nmt-tooltip {
    pointer-events: auto;
}
</style>
