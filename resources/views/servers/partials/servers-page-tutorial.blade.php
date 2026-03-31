{{-- Tour guiado da página Servidores: filtros, cartão/modal e tela cheia (localStorage + botão Tutorial). --}}
<script>
(function() {
    var LS_NEVER = 'engehub_servers_page_tutorial_never';
    var LS_AUTO_DONE = 'engehub_servers_page_tutorial_auto_v1_done';

    var steps = [];
    var backdropEl = null;
    var tooltipEl = null;
    var highlightClass = 'servers-page-tutorial-highlight';
    var currentIndex = -1;
    /** Alvo para posicionamento relativo do balão (passos normais). */
    var lastTarget = null;
    /** Elementos que receberam outline (um ou vários .server-tile). */
    var highlightedEls = [];
    /** Balão fixo à esquerda do quadro de servidores, mesmo critério visual do passo 6/6 do mapa. */
    var useFixedServerBoardTooltip = false;
    var tourListenersOn = false;

    function buildSteps() {
        var s = [];
        if (document.getElementById('serversTutorialFilterDc')) {
            s.push({
                selector: '#serversTutorialFilterDc',
                title: 'Filtro por datacenter',
                body: 'Restrinja a lista ao <strong>data center</strong> escolhido. Útil quando você quer ver só a infraestrutura de um site ou local físico. <strong>Todos os Datacenters</strong> mostra o conjunto completo respeitando os outros filtros.'
            });
        }
        if (document.getElementById('serversTutorialFilterOs')) {
            s.push({
                selector: '#serversTutorialFilterOs',
                title: 'Filtro por sistema operacional',
                body: 'Filtre por <strong>Linux</strong>, <strong>Windows</strong> ou <strong>Outros</strong> para focar em servidores de uma mesma família. Combine com datacenter e grupo para achar rapidamente um host.'
            });
        }
        if (document.getElementById('serversTutorialFilterGrp')) {
            s.push({
                selector: '#serversTutorialFilterGrp',
                title: 'Filtro por grupo',
                body: 'Os <strong>grupos</strong> organizam servidores por função ou time (por exemplo Infraestrutura, Desenvolvimento). Escolha um grupo para ver só os cartões daquela categoria.'
            });
        }
        if (document.querySelector('#servers-main-board .server-tile')) {
            s.push({
                allServerTiles: true,
                selector: '#servers-main-board',
                title: 'Detalhes do servidor',
                body: 'Clique no <strong>cartão do servidor</strong> (área do nome/IP, não no ícone de arrastar) para abrir uma <strong>janela com informações</strong>: IP, status, links úteis e, quando disponível, ping em tempo real.'
            });
        }
        if (document.getElementById('serversOpenFullscreenBtn')) {
            s.push({
                selector: '#serversOpenFullscreenBtn',
                title: 'Tela cheia',
                body: 'Use <strong>Tela cheia</strong> para ver <strong>todos os servidores monitorados</strong> em um painel ampliado, com filtros próprios e zoom — ideal para monitores grandes ou apresentações, sem perder a visão por datacenter e grupo.'
            });
        }
        return s;
    }

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
        for (var i = 0; i < highlightedEls.length; i++) {
            var el = highlightedEls[i];
            el.classList.remove(highlightClass);
            el.style.zIndex = '';
            if (el.dataset.sptTutorialPosition === '1') {
                el.style.position = '';
                delete el.dataset.sptTutorialPosition;
            }
        }
        highlightedEls = [];
        lastTarget = null;
    }

    function removeTourListeners() {
        if (!tourListenersOn) return;
        window.removeEventListener('resize', onResize);
        window.removeEventListener('keydown', onKey);
        tourListenersOn = false;
    }

    function onResize() {
        if (currentIndex >= 0 && tooltipEl) {
            var anchor = useFixedServerBoardTooltip ? document.getElementById('servers-main-board') : lastTarget;
            if (anchor) placeTooltip(anchor);
        }
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
        useFixedServerBoardTooltip = false;
        document.documentElement.classList.remove('servers-page-tutorial-active');
        document.body.classList.remove('servers-page-tutorial-active');
    }

    function placeTooltip(target) {
        if (!tooltipEl) return;
        var pad = 10;
        var tw = tooltipEl.offsetWidth || 300;
        var th = tooltipEl.offsetHeight || 140;
        var left;
        var top;

        if (useFixedServerBoardTooltip) {
            var boardBox = document.getElementById('servers-main-board');
            if (boardBox) {
                var mr = boardBox.getBoundingClientRect();
                var insetLeft = 22;
                left = mr.left + insetLeft;
                top = mr.top + mr.height * 0.5 - th * 0.5;
            } else if (target) {
                var rectFb = target.getBoundingClientRect();
                left = rectFb.left + 22;
                top = rectFb.top + rectFb.height * 0.5 - th * 0.5;
            } else {
                return;
            }
        } else {
            if (!target) return;
            var rect = target.getBoundingClientRect();
            left = rect.left + rect.width / 2 - tw / 2;
            top = rect.bottom + pad;
            if (top + th > window.innerHeight - pad) {
                top = rect.top - th - pad;
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

        if (step.allServerTiles) {
            var root = document.getElementById('servers-main-board');
            if (!root) {
                destroyTour();
                return;
            }
            var tiles = root.querySelectorAll('.server-tile');
            if (!tiles.length) {
                destroyTour();
                return;
            }
            currentIndex = index;
            clearHighlight();
            useFixedServerBoardTooltip = true;
            lastTarget = root;
            for (var t = 0; t < tiles.length; t++) {
                var tile = tiles[t];
                var csTile = window.getComputedStyle(tile);
                if (csTile.position === 'static') {
                    tile.style.position = 'relative';
                    tile.dataset.sptTutorialPosition = '1';
                }
                tile.classList.add(highlightClass);
                tile.style.zIndex = '115';
                highlightedEls.push(tile);
            }
            target = root;
        } else {
            if (step.selector) {
                target = document.querySelector(step.selector);
            }
            if (!target) {
                destroyTour();
                return;
            }
            currentIndex = index;
            clearHighlight();
            useFixedServerBoardTooltip = false;
            lastTarget = target;
            try {
                target.scrollIntoView({ block: 'nearest', behavior: 'smooth', inline: 'nearest' });
            } catch (e) {}
            var cs = window.getComputedStyle(target);
            if (cs.position === 'static') {
                target.style.position = 'relative';
                target.dataset.sptTutorialPosition = '1';
            }
            target.classList.add(highlightClass);
            target.style.zIndex = '115';
            highlightedEls.push(target);
        }

        var titleEl = document.getElementById('spt-title');
        var bodyEl = document.getElementById('spt-body');
        var stepEl = document.getElementById('spt-step');
        if (titleEl) titleEl.textContent = step.title;
        if (bodyEl) bodyEl.innerHTML = step.body;
        if (stepEl) stepEl.textContent = (index + 1) + '/' + steps.length;

        requestAnimationFrame(function() {
            requestAnimationFrame(function() { placeTooltip(target); });
        });
    }

    function buildUi() {
        destroyTour();
        document.documentElement.classList.add('servers-page-tutorial-active');
        document.body.classList.add('servers-page-tutorial-active');

        backdropEl = document.createElement('div');
        backdropEl.id = 'spt-backdrop';
        backdropEl.className = 'fixed inset-0 z-[110] bg-black/40';
        backdropEl.setAttribute('aria-hidden', 'true');

        tooltipEl = document.createElement('div');
        tooltipEl.id = 'spt-tooltip';
        tooltipEl.className = 'fixed z-[130] w-[min(22rem,calc(100vw-2rem))] rounded-xl border border-gray-200 bg-white p-4 shadow-2xl pointer-events-auto';
        tooltipEl.setAttribute('role', 'dialog');
        tooltipEl.setAttribute('aria-modal', 'true');
        tooltipEl.innerHTML =
            '<div class="flex items-start justify-between gap-2 mb-1.5">' +
            '<p id="spt-title" class="text-sm font-semibold text-gray-900 leading-tight flex-1 min-w-0"></p>' +
            '<span id="spt-step" class="shrink-0 rounded-md border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-bold tabular-nums text-amber-900" aria-label="Passo do tutorial"></span>' +
            '</div>' +
            '<p id="spt-body" class="text-xs text-gray-600 leading-snug mb-4"></p>' +
            '<div class="flex flex-wrap items-center gap-x-3 gap-y-2">' +
            '<button type="button" id="spt-never" class="text-xs text-gray-500 hover:text-gray-800 underline decoration-gray-400 underline-offset-2">Não ver mais</button>' +
            '<div class="flex flex-wrap gap-2 ml-auto">' +
            '<button type="button" id="spt-skip" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">Pular tutorial</button>' +
            '<button type="button" id="spt-next" class="rounded-md px-3 py-1.5 text-xs font-semibold text-black shadow-sm" style="background-color:#E9B32C">Entendi</button>' +
            '</div></div>';

        document.body.appendChild(backdropEl);
        document.body.appendChild(tooltipEl);

        document.getElementById('spt-never').addEventListener('click', function() {
            markNever();
            destroyTour();
        });
        document.getElementById('spt-skip').addEventListener('click', function() {
            markAutoDone();
            destroyTour();
        });
        document.getElementById('spt-next').addEventListener('click', function() {
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

    function firstStepTargetExists() {
        if (!steps.length) return false;
        var st = steps[0];
        if (st.allServerTiles) return !!document.querySelector('#servers-main-board .server-tile');
        return !!document.querySelector(st.selector);
    }

    function startTour() {
        steps = buildSteps();
        if (!steps.length || !firstStepTargetExists()) return;
        buildUi();
        showStep(0);
    }

    function init() {
        var replay = document.getElementById('serversPageTutorialReplayBtn');
        if (replay) {
            replay.addEventListener('click', function(e) {
                e.preventDefault();
                startTour();
            });
        }
        if (shouldAutoStart()) {
            setTimeout(function() {
                startTour();
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
html.servers-page-tutorial-active,
body.servers-page-tutorial-active {
    overflow: hidden !important;
    overscroll-behavior: none;
}
.servers-page-tutorial-highlight {
    outline: 3px solid #E9B32C !important;
    outline-offset: 3px !important;
    box-shadow: 0 0 0 4px rgba(233, 179, 44, 0.35) !important;
    border-radius: 0.375rem;
}
body.servers-page-tutorial-active #spt-backdrop {
    pointer-events: auto;
}
body.servers-page-tutorial-active #spt-tooltip {
    pointer-events: auto;
}
</style>
