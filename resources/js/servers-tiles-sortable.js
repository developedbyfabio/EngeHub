import Sortable from 'sortablejs';

const STORAGE_KEY_TILES = 'engehub_servers_tile_order_v1';
const STORAGE_KEY_BALLOONS = 'engehub_servers_balloon_order_v1';
const STORAGE_KEY_FS_ZOOM = 'engehub_servers_fs_zoom_v1';

const ZOOM_MIN = 0.55;
const ZOOM_MAX = 1.5;
const ZOOM_STEP = 0.1;

/** Instâncias Sortable da página principal. */
let mainSortableInstances = [];

/** Instâncias Sortable do modal tela cheia (destruir ao fechar). */
let fullscreenSortableInstances = [];

function destroySortableList(arr) {
    arr.forEach((s) => {
        try {
            s.destroy();
        } catch {
            /* ignore */
        }
    });
    arr.length = 0;
}

function readJsonStore(key) {
    try {
        const raw = localStorage.getItem(key);
        if (!raw) return {};
        const parsed = JSON.parse(raw);
        return typeof parsed === 'object' && parsed !== null && !Array.isArray(parsed) ? parsed : {};
    } catch {
        return {};
    }
}

function writeJsonStore(key, store) {
    try {
        localStorage.setItem(key, JSON.stringify(store));
    } catch {
        /* quota / private mode */
    }
}

function applySavedTileOrder(container, savedIds) {
    if (!savedIds || !Array.isArray(savedIds) || savedIds.length === 0) {
        return;
    }
    const items = [...container.querySelectorAll('.server-tile-sortable-item')];
    if (items.length === 0) {
        return;
    }
    const byId = new Map(items.map((n) => [String(n.dataset.serverId), n]));
    const used = new Set();
    const ordered = [];

    for (const rawId of savedIds) {
        const sid = String(rawId);
        const node = byId.get(sid);
        if (node) {
            ordered.push(node);
            used.add(sid);
        }
    }
    for (const item of items) {
        const sid = String(item.dataset.serverId);
        if (!used.has(sid)) {
            ordered.push(item);
        }
    }
    ordered.forEach((n) => container.appendChild(n));
}

function applySavedBalloonOrder(container, savedKeys) {
    if (!savedKeys || !Array.isArray(savedKeys) || savedKeys.length === 0) {
        return;
    }
    const items = [...container.querySelectorAll('.server-balloon-cluster-item')];
    if (items.length === 0) {
        return;
    }
    const byKey = new Map(items.map((n) => [String(n.dataset.balloonKey), n]));
    const used = new Set();
    const ordered = [];

    for (const k of savedKeys) {
        const sk = String(k);
        const node = byKey.get(sk);
        if (node) {
            ordered.push(node);
            used.add(sk);
        }
    }
    for (const item of items) {
        const sk = String(item.dataset.balloonKey);
        if (!used.has(sk)) {
            ordered.push(item);
        }
    }
    ordered.forEach((n) => container.appendChild(n));
}

function initServersBalloonsSortable(root, scope, instancesOut) {
    const lists = root.querySelectorAll('.server-balloon-groups-sortable');
    const store = readJsonStore(STORAGE_KEY_BALLOONS);

    lists.forEach((el) => {
        const dcKey = el.dataset.dcSortKey;
        if (!dcKey) {
            return;
        }

        applySavedBalloonOrder(el, store[dcKey]);

        const balloonGroupName = `engehub-balloon-dc-${dcKey}-${scope}`;

        const s = Sortable.create(el, {
            animation: 200,
            easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
            delay: 200,
            delayOnTouchOnly: true,
            handle: '.balloon-cluster-drag-handle',
            draggable: '.server-balloon-cluster-item',
            ghostClass: 'server-balloon-sortable-ghost',
            chosenClass: 'server-balloon-sortable-chosen',
            dragClass: 'server-balloon-sortable-drag',
            fallbackOnBody: true,
            swapThreshold: 0.55,
            emptyInsertThreshold: 8,
            group: {
                name: balloonGroupName,
                pull: true,
                put: true,
            },
            onMove(evt) {
                if (!evt.dragged.classList.contains('server-balloon-cluster-item')) {
                    return false;
                }
                if (!evt.to.classList.contains('server-balloon-groups-sortable')) {
                    return false;
                }
                if (evt.to !== el) {
                    return false;
                }
                return true;
            },
            onEnd() {
                const keys = [...el.querySelectorAll('.server-balloon-cluster-item')].map((n) =>
                    String(n.dataset.balloonKey)
                );
                const next = readJsonStore(STORAGE_KEY_BALLOONS);
                next[dcKey] = keys;
                writeJsonStore(STORAGE_KEY_BALLOONS, next);
            },
        });
        if (instancesOut) {
            instancesOut.push(s);
        }
    });
}

function initServersTilesSortable(root, scope, instancesOut) {
    const lists = root.querySelectorAll('.server-sortable-list');
    const store = readJsonStore(STORAGE_KEY_TILES);

    lists.forEach((el) => {
        const key = el.dataset.sortKey;
        if (!key) {
            return;
        }

        applySavedTileOrder(el, store[key]);

        const tileGroupName = `engehub-tile-${key}-${scope}`;

        const s = Sortable.create(el, {
            animation: 180,
            easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
            delay: 180,
            delayOnTouchOnly: true,
            handle: '.server-tile-drag-handle',
            draggable: '.server-tile-sortable-item',
            ghostClass: 'server-tile-sortable-ghost',
            chosenClass: 'server-tile-sortable-chosen',
            dragClass: 'server-tile-sortable-drag',
            fallbackOnBody: true,
            swapThreshold: 0.65,
            emptyInsertThreshold: 8,
            group: {
                name: tileGroupName,
                pull: true,
                put: true,
            },
            onMove(evt) {
                if (!evt.dragged.classList.contains('server-tile-sortable-item')) {
                    return false;
                }
                if (!evt.to.classList.contains('server-sortable-list')) {
                    return false;
                }
                if (evt.to !== el) {
                    return false;
                }
                return true;
            },
            onEnd() {
                const ids = [...el.querySelectorAll('.server-tile-sortable-item')].map((n) =>
                    parseInt(n.dataset.serverId, 10)
                );
                const next = readJsonStore(STORAGE_KEY_TILES);
                next[key] = ids;
                writeJsonStore(STORAGE_KEY_TILES, next);
            },
        });
        if (instancesOut) {
            instancesOut.push(s);
        }
    });
}

/**
 * @param {ParentNode} root
 * @param {'main'|'fs'} scope — grupos Sortable distintos entre página e modal
 * @param {Sortable[]|null} instancesOut — se informado, cada Sortable criado é empilhado (para destroy)
 */
export function initServersPageSortableIn(root, scope, instancesOut = null) {
    if (!root) {
        return;
    }
    initServersBalloonsSortable(root, scope, instancesOut);
    initServersTilesSortable(root, scope, instancesOut);
}

export function destroyFullscreenSortables() {
    destroySortableList(fullscreenSortableInstances);
}

/** Reaplica ordem salva no quadro principal e recria Sortable (após editar na tela cheia). */
function refreshMainBoardLayoutFromStorage() {
    const main = document.getElementById('servers-main-board');
    if (!main) {
        return;
    }
    destroySortableList(mainSortableInstances);

    const bStore = readJsonStore(STORAGE_KEY_BALLOONS);
    main.querySelectorAll('.server-balloon-groups-sortable').forEach((el) => {
        const dcKey = el.dataset.dcSortKey;
        if (dcKey) {
            applySavedBalloonOrder(el, bStore[dcKey]);
        }
    });

    const tStore = readJsonStore(STORAGE_KEY_TILES);
    main.querySelectorAll('.server-sortable-list').forEach((el) => {
        const key = el.dataset.sortKey;
        if (key) {
            applySavedTileOrder(el, tStore[key]);
        }
    });

    initServersPageSortableIn(main, 'main', mainSortableInstances);
}

export function initServersPageSortable() {
    const main = document.getElementById('servers-main-board');
    if (!main) {
        return;
    }
    destroySortableList(mainSortableInstances);
    initServersPageSortableIn(main, 'main', mainSortableInstances);
}

function readSavedZoom() {
    const v = parseFloat(localStorage.getItem(STORAGE_KEY_FS_ZOOM) || '1');
    if (Number.isNaN(v)) {
        return 1;
    }
    return Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, v));
}

function applyZoomScale(scale) {
    const root = document.getElementById('serversFsZoomRoot');
    const label = document.getElementById('fsZoomLabel');
    if (root) {
        root.style.transform = `scale(${scale})`;
    }
    if (label) {
        label.textContent = `${Math.round(scale * 100)}%`;
    }
    window.__serversFsZoom = scale;
}

function persistZoom(scale) {
    try {
        localStorage.setItem(STORAGE_KEY_FS_ZOOM, String(scale));
    } catch {
        /* ignore */
    }
}

function resetFullscreenFilterSelects() {
    const dc = document.getElementById('fs_datacenter_id');
    const os = document.getElementById('fs_operating_system');
    const grp = document.getElementById('fs_server_group_id');
    if (dc) dc.value = '';
    if (os) os.value = '';
    if (grp) grp.value = '';
}

/**
 * Filtra tiles do mapa em tela cheia apenas no cliente (sem GET / sem fechar o modal).
 */
export function applyServersFullscreenFilters() {
    const board = document.getElementById('servers-fullscreen-board');
    if (!board) {
        return;
    }

    const dc = document.getElementById('fs_datacenter_id')?.value?.trim() || '';
    const os = document.getElementById('fs_operating_system')?.value?.trim() || '';
    const grp = document.getElementById('fs_server_group_id')?.value?.trim() || '';

    let visibleTileCount = 0;

    board.querySelectorAll('.servers-fs-dc-section').forEach((section) => {
        const secDc = String(section.dataset.fsDcId ?? '');
        const dcMatch = !dc || secDc === dc;

        if (!dcMatch) {
            section.classList.add('hidden');
            return;
        }

        section.classList.remove('hidden');

        let sectionHasVisible = false;
        section.querySelectorAll('.server-balloon-cluster-item').forEach((cluster) => {
            let clusterHasVisible = false;
            cluster.querySelectorAll('.server-tile-sortable-item').forEach((tile) => {
                const tDc = String(tile.dataset.fsDcId ?? '');
                const tOs = String(tile.dataset.fsOs ?? '');
                const tGrp = String(tile.dataset.fsGroupId ?? '');
                const match =
                    (!dc || tDc === dc) && (!os || tOs === os) && (!grp || tGrp === grp);
                if (match) {
                    tile.classList.remove('hidden');
                    clusterHasVisible = true;
                    visibleTileCount += 1;
                } else {
                    tile.classList.add('hidden');
                }
            });
            if (clusterHasVisible) {
                cluster.classList.remove('hidden');
                sectionHasVisible = true;
            } else {
                cluster.classList.add('hidden');
            }
        });

        if (!sectionHasVisible) {
            section.classList.add('hidden');
        }
    });

    const summary = document.getElementById('serversFsFilterSummary');
    if (summary) {
        const hasFilter = !!(dc || os || grp);
        if (hasFilter) {
            summary.classList.remove('hidden');
            summary.textContent =
                visibleTileCount === 1 ? '1 servidor visível' : `${visibleTileCount} servidores visíveis`;
        } else {
            summary.classList.add('hidden');
            summary.textContent = '';
        }
    }
}

export function openServersFullscreenModal() {
    const modal = document.getElementById('serversFullscreenModal');
    const fsBoard = document.getElementById('servers-fullscreen-board');
    if (!modal || !fsBoard) {
        return;
    }

    destroyFullscreenSortables();

    modal.classList.add('servers-fs-open');
    document.body.classList.add('overflow-hidden');

    const z = readSavedZoom();
    applyZoomScale(z);

    resetFullscreenFilterSelects();
    initServersPageSortableIn(fsBoard, 'fs', fullscreenSortableInstances);
    applyServersFullscreenFilters();
}

export function closeServersFullscreenModal() {
    const modal = document.getElementById('serversFullscreenModal');
    destroyFullscreenSortables();
    if (modal) {
        modal.classList.remove('servers-fs-open');
    }
    refreshMainBoardLayoutFromStorage();
    const detailOpen = document.getElementById('serverDetailModal')?.classList.contains('hidden') === false;
    if (!detailOpen) {
        document.body.classList.remove('overflow-hidden');
    }
}

function nudgeZoom(delta) {
    const cur = typeof window.__serversFsZoom === 'number' ? window.__serversFsZoom : readSavedZoom();
    const next = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, Math.round((cur + delta) * 100) / 100));
    applyZoomScale(next);
    persistZoom(next);
}

export function bindServersFullscreenUi() {
    window.openServersFullscreenModal = openServersFullscreenModal;
    window.closeServersFullscreenModal = closeServersFullscreenModal;
    window.applyServersFullscreenFilters = applyServersFullscreenFilters;

    document.getElementById('serversOpenFullscreenBtn')?.addEventListener('click', () => openServersFullscreenModal());
    document.getElementById('serversCloseFullscreenBtn')?.addEventListener('click', () => closeServersFullscreenModal());
    document.getElementById('fsZoomInBtn')?.addEventListener('click', () => nudgeZoom(ZOOM_STEP));
    document.getElementById('fsZoomOutBtn')?.addEventListener('click', () => nudgeZoom(-ZOOM_STEP));
    document.getElementById('fsZoomResetBtn')?.addEventListener('click', () => {
        applyZoomScale(1);
        persistZoom(1);
    });

    document.getElementById('serversFullscreenModal')?.addEventListener('click', function (e) {
        if (e.target === this) {
            closeServersFullscreenModal();
        }
    });

    ['fs_datacenter_id', 'fs_operating_system', 'fs_server_group_id'].forEach((id) => {
        document.getElementById(id)?.addEventListener('change', () => applyServersFullscreenFilters());
    });
    document.getElementById('serversFsClearFiltersBtn')?.addEventListener('click', () => {
        resetFullscreenFilterSelects();
        applyServersFullscreenFilters();
    });
}
