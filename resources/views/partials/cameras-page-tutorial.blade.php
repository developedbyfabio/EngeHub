{{-- Tour guiado da página Câmeras: DVRs, expandir, fotos, checklists e histórico. --}}
<script>
(function() {
    var LS_NEVER = 'engehub_cameras_page_tutorial_never';
    var LS_AUTO_DONE = 'engehub_cameras_page_tutorial_auto_v1_done';

    var steps = [];
    var backdropEl = null;
    var tooltipEl = null;
    var highlightClass = 'cameras-page-tutorial-highlight';
    var highlightZBoost = '115';
    var currentIndex = -1;
    var lastTarget = null;
    var highlightedEls = [];
    var tourListenersOn = false;

    function buildSteps() {
        var s = [];
        if (document.getElementById('camerasTutorialDvrCard')) {
            s.push({
                selector: '#camerasTutorialDvrCard',
                noScroll: true,
                noHighlight: true,
                title: 'DVRs da empresa',
                body: 'Aqui aparecem os <strong>DVRs</strong> cadastrados e ativos. Cada linha resume nome, local, quantidade de câmeras, status e uma <strong>miniatura</strong> quando há fotos registradas.'
            });
        }
        if (document.getElementById('camerasTutorialExpandBtn')) {
            s.push({
                selector: '#camerasTutorialExpandBtn',
                title: 'Expandir câmeras',
                body: 'Clique na <strong>seta</strong> para expandir o DVR e ver a <strong>lista de câmeras</strong> (nome, canal, foto e status). Use a seta novamente para recolher.'
            });
        }
        if (document.getElementById('camerasTutorialDvrPhotoBtn')) {
            s.push({
                selector: '#camerasTutorialDvrPhotoBtn',
                title: 'Visualizar fotos',
                body: 'Ao clicar na <strong>miniatura</strong> na coluna Foto (do DVR ou de uma câmera na lista expandida), abre-se o <strong>visualizador em tela cheia</strong>, com opção de navegar entre as imagens quando houver mais de uma.'
            });
        }
        if (document.getElementById('camerasTutorialChecklistsBtn')) {
            s.push({
                selector: '#camerasTutorialChecklistsBtn',
                title: 'Checklists finalizados',
                body: 'O botão <strong>Checklists</strong> abre o histórico dos <strong>últimos checklists concluídos</strong>. Você pode filtrar por período e DVR, abrir detalhes, PDF e download conforme as permissões da sua conta.'
            });
        }
        if (document.getElementById('camerasTutorialIniciarChecklistBtn')) {
            s.push({
                selector: '#camerasTutorialIniciarChecklistBtn',
                title: 'Novo checklist',
                body: 'Se você tem permissão, use <strong>Iniciar Novo Checklist</strong> para abrir uma inspeção: escolha os DVRs e, se quiser, câmeras específicas, e preencha o fluxo de evidências no checklist.'
            });
        }
        if (document.getElementById('camerasTutorialDvrHistoricoBtn')) {
            s.push({
                selector: '#camerasTutorialDvrHistoricoBtn',
                title: 'Histórico de fotos do DVR',
                body: 'O ícone de <strong>histórico</strong> na coluna Ações abre a linha do tempo das <strong>últimas fotos enviadas</strong> para aquele DVR, permitindo <strong>navegar</strong> entre os envios anteriores registrados no sistema.'
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
            if (el.dataset.cptTutorialPosition === '1') {
                el.style.position = '';
                delete el.dataset.cptTutorialPosition;
            }
        }
        highlightedEls = [];
        lastTarget = null;
    }

    function removeTourListeners() {
        if (!tourListenersOn) return;
        window.removeEventListener('resize', onResizeOrScroll);
        window.removeEventListener('scroll', onResizeOrScroll, true);
        window.removeEventListener('keydown', onKey);
        tourListenersOn = false;
    }

    function onResizeOrScroll() {
        if (currentIndex < 0 || !tooltipEl) return;
        var step = steps[currentIndex];
        var t = (step && step.noHighlight) ? null : lastTarget;
        if (step && (step.noHighlight || t)) {
            requestAnimationFrame(function() { placeTooltip(t); });
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
        document.documentElement.classList.remove('cameras-page-tutorial-active');
        document.body.classList.remove('cameras-page-tutorial-active');
    }

    function placeTooltip(target) {
        if (!tooltipEl) return;
        var pad = 10;
        var tw = tooltipEl.offsetWidth || 300;
        var th = tooltipEl.offsetHeight || 140;
        if (!target) {
            var left = Math.max(pad, (window.innerWidth - tw) / 2);
            var top = Math.max(pad, Math.min(window.innerHeight * 0.12, window.innerHeight - th - pad));
            tooltipEl.style.left = left + 'px';
            tooltipEl.style.top = top + 'px';
            return;
        }
        var rect = target.getBoundingClientRect();
        var left = rect.left + rect.width / 2 - tw / 2;
        var top = rect.bottom + pad;
        if (top + th > window.innerHeight - pad) {
            top = rect.top - th - pad;
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
        var target = step.selector ? document.querySelector(step.selector) : null;
        if (!target) {
            destroyTour();
            return;
        }

        currentIndex = index;
        clearHighlight();
        lastTarget = null;
        if (!step.noScroll) {
            try {
                target.scrollIntoView({ block: 'nearest', behavior: 'smooth', inline: 'nearest' });
            } catch (e) {}
        }
        if (!step.noHighlight) {
            var cs = window.getComputedStyle(target);
            if (cs.position === 'static') {
                target.style.position = 'relative';
                target.dataset.cptTutorialPosition = '1';
            }
            target.classList.add(highlightClass);
            target.style.zIndex = highlightZBoost;
            highlightedEls.push(target);
            lastTarget = target;
        }

        var titleEl = document.getElementById('cpt-title');
        var bodyEl = document.getElementById('cpt-body');
        var stepEl = document.getElementById('cpt-step');
        if (titleEl) titleEl.textContent = step.title;
        if (bodyEl) bodyEl.innerHTML = step.body;
        if (stepEl) stepEl.textContent = (index + 1) + '/' + steps.length;

        requestAnimationFrame(function() {
            requestAnimationFrame(function() { placeTooltip(step.noHighlight ? null : lastTarget); });
        });
    }

    function buildUi() {
        destroyTour();
        document.documentElement.classList.add('cameras-page-tutorial-active');
        document.body.classList.add('cameras-page-tutorial-active');

        backdropEl = document.createElement('div');
        backdropEl.id = 'cpt-backdrop';
        backdropEl.className = 'fixed inset-0 z-[110] bg-black/40';
        backdropEl.setAttribute('aria-hidden', 'true');

        tooltipEl = document.createElement('div');
        tooltipEl.id = 'cpt-tooltip';
        tooltipEl.className = 'fixed z-[130] w-[min(22rem,calc(100vw-2rem))] rounded-xl border border-gray-200 bg-white p-4 shadow-2xl pointer-events-auto';
        tooltipEl.setAttribute('role', 'dialog');
        tooltipEl.setAttribute('aria-modal', 'true');
        tooltipEl.innerHTML =
            '<div class="flex items-start justify-between gap-2 mb-1.5">' +
            '<p id="cpt-title" class="text-sm font-semibold text-gray-900 leading-tight flex-1 min-w-0"></p>' +
            '<span id="cpt-step" class="shrink-0 rounded-md border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-bold tabular-nums text-amber-900" aria-label="Passo do tutorial"></span>' +
            '</div>' +
            '<p id="cpt-body" class="text-xs text-gray-600 leading-snug mb-4"></p>' +
            '<div class="flex flex-wrap items-center gap-x-3 gap-y-2">' +
            '<button type="button" id="cpt-never" class="text-xs text-gray-500 hover:text-gray-800 underline decoration-gray-400 underline-offset-2">Não ver mais</button>' +
            '<div class="flex flex-wrap gap-2 ml-auto">' +
            '<button type="button" id="cpt-skip" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">Pular tutorial</button>' +
            '<button type="button" id="cpt-next" class="rounded-md px-3 py-1.5 text-xs font-semibold text-black shadow-sm" style="background-color:#E9B32C">Entendi</button>' +
            '</div></div>';

        document.body.appendChild(backdropEl);
        document.body.appendChild(tooltipEl);

        document.getElementById('cpt-never').addEventListener('click', function() {
            markNever();
            destroyTour();
        });
        document.getElementById('cpt-skip').addEventListener('click', function() {
            markAutoDone();
            destroyTour();
        });
        document.getElementById('cpt-next').addEventListener('click', function() {
            if (currentIndex >= steps.length - 1) {
                markAutoDone();
                destroyTour();
            } else {
                showStep(currentIndex + 1);
            }
        });

        window.addEventListener('resize', onResizeOrScroll);
        window.addEventListener('scroll', onResizeOrScroll, true);
        window.addEventListener('keydown', onKey);
        tourListenersOn = true;
    }

    function firstStepTargetExists() {
        if (!steps.length) return false;
        return !!document.querySelector(steps[0].selector);
    }

    function startTour() {
        steps = buildSteps();
        if (!steps.length || !firstStepTargetExists()) return;
        buildUi();
        showStep(0);
    }

    function init() {
        var replay = document.getElementById('camerasTutorialReplayBtn');
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
html.cameras-page-tutorial-active,
body.cameras-page-tutorial-active {
    overflow: hidden !important;
    overscroll-behavior: none;
}
.cameras-page-tutorial-highlight {
    outline: 3px solid #E9B32C !important;
    outline-offset: 3px !important;
    box-shadow: 0 0 0 4px rgba(233, 179, 44, 0.35) !important;
    border-radius: 0.375rem;
}
body.cameras-page-tutorial-active #cpt-backdrop {
    pointer-events: auto;
}
body.cameras-page-tutorial-active #cpt-tooltip {
    pointer-events: auto;
}
</style>
