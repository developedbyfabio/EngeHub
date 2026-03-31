{{-- Tour guiado da página Início: abas, cartões, favoritos e lista de ramais. --}}
<script>
(function() {
    var LS_NEVER = 'engehub_home_page_tutorial_never';
    var LS_AUTO_DONE = 'engehub_home_page_tutorial_auto_v1_done';

    var steps = [];
    var backdropEl = null;
    var tooltipEl = null;
    var highlightClass = 'home-page-tutorial-highlight';
    var currentIndex = -1;
    var lastTarget = null;
    var highlightedEls = [];
    var useFixedPortalTooltip = false;
    var tourListenersOn = false;

    function isElementVisible(el) {
        if (!el) return false;
        var e = el;
        while (e && e !== document.body) {
            var st = window.getComputedStyle(e);
            if (st.display === 'none' || st.visibility === 'hidden') return false;
            e = e.parentElement;
        }
        var r = el.getBoundingClientRect();
        return r.width > 0 && r.height > 0;
    }

    function collectVisibleHomeCards() {
        var root = document.getElementById('homeTutorialPortalShell');
        if (!root) return [];
        var cards = root.querySelectorAll('.cards-grid > [data-card-id]');
        var out = [];
        for (var i = 0; i < cards.length; i++) {
            if (isElementVisible(cards[i])) out.push(cards[i]);
        }
        return out;
    }

    function firstVisibleFavoriteStar() {
        var root = document.getElementById('homeTutorialPortalShell');
        if (!root) return null;
        var stars = root.querySelectorAll('.favorite-star');
        for (var i = 0; i < stars.length; i++) {
            if (isElementVisible(stars[i])) return stars[i];
        }
        return null;
    }

    function buildSteps() {
        var s = [];
        if (document.getElementById('homeTutorialTabStrip')) {
            s.push({
                selector: '#homeTutorialTabStrip',
                title: 'Abas de sistemas',
                body: 'Use as <strong>abas no topo</strong> (Favoritos, categorias, etc.) para alternar entre os grupos de sistemas aos quais você tem acesso. Só aparecem pastas e sistemas <strong>liberados para o seu usuário</strong>.'
            });
        }
        if (collectVisibleHomeCards().length > 0) {
            s.push({
                allHomeCards: true,
                selector: '#homeTutorialPortalShell',
                title: 'Sistemas e acesso',
                body: 'Cada cartão representa um sistema. Use <strong>Acessar</strong> (ou copiar IP em servidores) para abrir o recurso. O botão <strong>Logins</strong> mostra credenciais salvas <strong>quando você tem permissão</strong> e existem logins cadastrados. O indicador <strong>Online/Offline</strong> aparece nos sistemas com monitoramento — passe o mouse para ver detalhes.'
            });
        }
        if (firstVisibleFavoriteStar()) {
            s.push({
                selector: null,
                favoriteStar: true,
                title: 'Favoritos',
                body: 'Clique na <strong>estrela</strong> para favoritar ou remover. Os favoritos ficam na aba <strong>Favoritos</strong> no topo. Ao marcar o <strong>primeiro</strong> favorito, a página pode <strong>recarregar</strong> para exibir essa aba; depois você alterna entre as abas normalmente.'
            });
        }
        if (document.getElementById('extListFabBtn')) {
            s.push({
                selector: '#extListFabBtn',
                title: 'Lista de Ramais',
                body: 'O botão flutuante <strong>Lista de Ramais</strong> abre a <strong>lista telefônica</strong> em tela cheia, com busca e zoom — útil para achar ramais e contatos rapidamente.'
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
            if (el.dataset.hptTutorialPosition === '1') {
                el.style.position = '';
                delete el.dataset.hptTutorialPosition;
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
        if (currentIndex < 0 || !tooltipEl) return;
        if (useFixedPortalTooltip) {
            placeTooltip(document.getElementById('homeTutorialPortalShell'));
        } else if (lastTarget) {
            placeTooltip(lastTarget);
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
        useFixedPortalTooltip = false;
        document.documentElement.classList.remove('home-page-tutorial-active');
        document.body.classList.remove('home-page-tutorial-active');
    }

    function placeTooltip(target) {
        if (!tooltipEl) return;
        var pad = 10;
        var tw = tooltipEl.offsetWidth || 300;
        var th = tooltipEl.offsetHeight || 140;
        var left;
        var top;

        if (useFixedPortalTooltip) {
            var panel = document.getElementById('homeTutorialPortalShell');
            if (panel) {
                var mr = panel.getBoundingClientRect();
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

        if (step.allHomeCards) {
            var tiles = collectVisibleHomeCards();
            if (!tiles.length) {
                destroyTour();
                return;
            }
            var root = document.getElementById('homeTutorialPortalShell');
            if (!root) {
                destroyTour();
                return;
            }
            currentIndex = index;
            clearHighlight();
            useFixedPortalTooltip = true;
            lastTarget = root;
            for (var t = 0; t < tiles.length; t++) {
                var card = tiles[t];
                var csTile = window.getComputedStyle(card);
                if (csTile.position === 'static') {
                    card.style.position = 'relative';
                    card.dataset.hptTutorialPosition = '1';
                }
                card.classList.add(highlightClass);
                card.style.zIndex = '115';
                highlightedEls.push(card);
            }
            target = root;
        } else if (step.favoriteStar) {
            var star = firstVisibleFavoriteStar();
            if (!star) {
                destroyTour();
                return;
            }
            currentIndex = index;
            clearHighlight();
            useFixedPortalTooltip = false;
            lastTarget = star;
            try {
                star.scrollIntoView({ block: 'nearest', behavior: 'smooth', inline: 'nearest' });
            } catch (e) {}
            var cs = window.getComputedStyle(star);
            if (cs.position === 'static') {
                star.style.position = 'relative';
                star.dataset.hptTutorialPosition = '1';
            }
            star.classList.add(highlightClass);
            star.style.zIndex = '115';
            highlightedEls.push(star);
            target = star;
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
            useFixedPortalTooltip = false;
            lastTarget = target;
            try {
                target.scrollIntoView({ block: 'nearest', behavior: 'smooth', inline: 'nearest' });
            } catch (e) {}
            var cs = window.getComputedStyle(target);
            if (cs.position === 'static') {
                target.style.position = 'relative';
                target.dataset.hptTutorialPosition = '1';
            }
            target.classList.add(highlightClass);
                target.style.zIndex = '115';
            highlightedEls.push(target);
        }

        var titleEl = document.getElementById('hpt-title');
        var bodyEl = document.getElementById('hpt-body');
        var stepEl = document.getElementById('hpt-step');
        if (titleEl) titleEl.textContent = step.title;
        if (bodyEl) bodyEl.innerHTML = step.body;
        if (stepEl) stepEl.textContent = (index + 1) + '/' + steps.length;

        requestAnimationFrame(function() {
            requestAnimationFrame(function() { placeTooltip(target); });
        });
    }

    function buildUi() {
        destroyTour();
        document.documentElement.classList.add('home-page-tutorial-active');
        document.body.classList.add('home-page-tutorial-active');

        backdropEl = document.createElement('div');
        backdropEl.id = 'hpt-backdrop';
        backdropEl.className = 'fixed inset-0 z-[110] bg-black/40';
        backdropEl.setAttribute('aria-hidden', 'true');

        tooltipEl = document.createElement('div');
        tooltipEl.id = 'hpt-tooltip';
        tooltipEl.className = 'fixed z-[130] w-[min(22rem,calc(100vw-2rem))] rounded-xl border border-gray-200 bg-white p-4 shadow-2xl pointer-events-auto';
        tooltipEl.setAttribute('role', 'dialog');
        tooltipEl.setAttribute('aria-modal', 'true');
        tooltipEl.innerHTML =
            '<div class="flex items-start justify-between gap-2 mb-1.5">' +
            '<p id="hpt-title" class="text-sm font-semibold text-gray-900 leading-tight flex-1 min-w-0"></p>' +
            '<span id="hpt-step" class="shrink-0 rounded-md border border-amber-200 bg-amber-50 px-2 py-0.5 text-xs font-bold tabular-nums text-amber-900" aria-label="Passo do tutorial"></span>' +
            '</div>' +
            '<p id="hpt-body" class="text-xs text-gray-600 leading-snug mb-4"></p>' +
            '<div class="flex flex-wrap items-center gap-x-3 gap-y-2">' +
            '<button type="button" id="hpt-never" class="text-xs text-gray-500 hover:text-gray-800 underline decoration-gray-400 underline-offset-2">Não ver mais</button>' +
            '<div class="flex flex-wrap gap-2 ml-auto">' +
            '<button type="button" id="hpt-skip" class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">Pular tutorial</button>' +
            '<button type="button" id="hpt-next" class="rounded-md px-3 py-1.5 text-xs font-semibold text-black shadow-sm" style="background-color:#E9B32C">Entendi</button>' +
            '</div></div>';

        document.body.appendChild(backdropEl);
        document.body.appendChild(tooltipEl);

        document.getElementById('hpt-never').addEventListener('click', function() {
            markNever();
            destroyTour();
        });
        document.getElementById('hpt-skip').addEventListener('click', function() {
            markAutoDone();
            destroyTour();
        });
        document.getElementById('hpt-next').addEventListener('click', function() {
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
        return !!document.querySelector(steps[0].selector);
    }

    function startTour() {
        steps = buildSteps();
        if (!steps.length || !firstStepTargetExists()) return;
        buildUi();
        showStep(0);
    }

    function init() {
        var replay = document.getElementById('homeTutorialReplayBtn');
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
html.home-page-tutorial-active,
body.home-page-tutorial-active {
    overflow: hidden !important;
    overscroll-behavior: none;
}
.home-page-tutorial-highlight {
    outline: 3px solid #E9B32C !important;
    outline-offset: 3px !important;
    box-shadow: 0 0 0 4px rgba(233, 179, 44, 0.35) !important;
    border-radius: 0.375rem;
}
body.home-page-tutorial-active #hpt-backdrop {
    pointer-events: auto;
}
body.home-page-tutorial-active #hpt-tooltip {
    pointer-events: auto;
}
</style>
