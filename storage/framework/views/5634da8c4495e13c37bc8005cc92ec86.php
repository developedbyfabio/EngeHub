<?php $__env->startSection('header'); ?>
    <?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Servidores','icon' => 'fas fa-server']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Servidores','icon' => 'fas fa-server']); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <?php if(($serversAllCount ?? 0) > 0): ?>
                <button type="button"
                        id="serversOpenFullscreenBtn"
                        class="inline-flex items-center justify-center gap-2 rounded-md border-2 border-gray-800 bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                    <i class="fas fa-expand-alt"></i>
                    Tela cheia
                </button>
            <?php endif; ?>
            <?php if(auth()->user()?->hasFullAccess() ?? false): ?>
                <a href="<?php echo e(route('admin.servers.index')); ?>" class="page-header-btn-secondary">
                    <i class="fas fa-cog mr-2"></i>
                    Gerenciar Servidores
                </a>
            <?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div id="servers-main-board" class="servers-board-scope">
                    <?php
                        $hasMainFilters = $selectedDatacenter || $selectedOperatingSystem || $selectedServerGroup;
                        $mainClearBtnClass = 'inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-md border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2';
                    ?>
                    <div class="mb-6 flex min-w-0 flex-col gap-3 lg:flex-row lg:flex-wrap lg:items-end lg:justify-between lg:gap-4">
                        <div class="min-w-0 flex-1">
                            <?php echo $__env->make('servers.partials.filters-form', ['prefix' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="flex flex-shrink-0 flex-nowrap items-end gap-2">
                            <?php if($hasMainFilters): ?>
                                <a href="<?php echo e(route('servers.index')); ?>"
                                   class="<?php echo e($mainClearBtnClass); ?>"
                                   aria-label="Limpar filtros"
                                   title="Limpar filtros">
                                    <i class="fas fa-times text-base" aria-hidden="true"></i>
                                </a>
                            <?php else: ?>
                                <span class="<?php echo e($mainClearBtnClass); ?> invisible pointer-events-none select-none" aria-hidden="true" tabindex="-1"></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($servers->count() > 0): ?>
                        <?php echo $__env->make('servers.partials.filter-active-banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('servers.partials.datacenter-balloons', ['headingPrefix' => ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php elseif(($serversAllCount ?? 0) > 0): ?>
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-filter text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum servidor com estes filtros</h3>
                            <p class="text-gray-500 mb-4">Limpe ou ajuste os filtros acima, ou abra a <strong>tela cheia</strong> para ver todos os servidores monitorados.</p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-server text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum servidor cadastrado</h3>
                            <p class="text-gray-500 mb-4">Não há servidores cadastrados no sistema ainda.</p>
                            <?php if(auth()->user()?->hasFullAccess() ?? false): ?>
                                <a href="<?php echo e(route('admin.servers.index')); ?>"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Gerenciar Servidores
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal mapa em tela cheia -->
<div id="serversFullscreenModal"
     class="servers-fs-modal fixed inset-0 z-[200] items-center justify-center bg-black/55 p-3 sm:p-5 md:p-6"
     role="dialog"
     aria-modal="true"
     aria-labelledby="serversFullscreenTitle">
    <div class="flex max-h-[calc(100dvh-1.5rem)] w-full max-w-[calc(100vw-1.5rem)] flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl">
        <div class="flex flex-shrink-0 flex-wrap items-center gap-x-2 gap-y-2 border-b border-gray-200 bg-gray-50 px-2 py-2 sm:px-3">
            <h2 id="serversFullscreenTitle" class="flex-shrink-0 text-sm font-bold text-gray-900 sm:text-base">
                <i class="fas fa-server mr-1.5 text-amber-600 sm:mr-2"></i>
                Servidores — tela cheia
            </h2>
            <div class="flex min-w-0 flex-1 flex-wrap items-center justify-end sm:justify-center">
                <?php echo $__env->make('servers.partials.filters-form', ['prefix' => 'fs_', 'clientSideOnlyFullscreen' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="flex flex-shrink-0 flex-wrap items-center justify-end gap-1.5 sm:gap-2">
                <span class="hidden text-xs text-gray-500 lg:inline">Zoom:</span>
                <button type="button" id="fsZoomOutBtn"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100"
                        title="Diminuir zoom" aria-label="Diminuir zoom">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                <span id="fsZoomLabel" class="min-w-[3rem] text-center text-xs font-semibold text-gray-800 tabular-nums sm:text-sm">100%</span>
                <button type="button" id="fsZoomInBtn"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100"
                        title="Aumentar zoom" aria-label="Aumentar zoom">
                    <i class="fas fa-plus text-xs"></i>
                </button>
                <button type="button" id="fsZoomResetBtn"
                        class="rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 sm:px-3">
                    Redefinir
                </button>
                <button type="button" id="serversCloseFullscreenBtn"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-gray-800 sm:px-4">
                    <i class="fas fa-times"></i>
                    Fechar
                </button>
            </div>
        </div>
        <div id="serversFsScrollArea" class="min-h-0 flex-1 overflow-auto bg-gray-100/80">
            <div id="serversFsZoomRoot" class="will-change-transform">
                <div id="servers-fullscreen-board" class="servers-board-scope min-w-0 px-3 py-4 sm:px-6 sm:py-6">
                    <?php if(!empty($serversByDatacenterFullscreen)): ?>
                        <?php echo $__env->make('servers.partials.datacenter-balloons', [
                            'headingPrefix' => 'fs-',
                            'serversByDatacenter' => $serversByDatacenterFullscreen,
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal detalhes do servidor -->
<div id="serverDetailModal" class="fixed inset-0 z-[220] hidden flex items-center justify-center bg-gray-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="serverDetailTitle">
    <div id="serverDetailModalPanel" class="relative flex max-h-[min(92vh,calc(100dvh-1.5rem))] w-full max-w-lg flex-col rounded-2xl border border-gray-200 bg-white shadow-xl" onclick="event.stopPropagation()">
        <div id="serverDetailBody" class="min-h-0 flex-1 overflow-y-auto px-5 pb-5 pt-4 sm:px-6 sm:pt-5">
            
        </div>
    </div>
</div>

<script>
window.SERVERS_MAP = <?php echo json_encode($serversJson, 15, 512) ?>;
window.SERVERS_CAN_PING = <?php echo json_encode(auth()->check(), 15, 512) ?>;

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    const d = document.createElement('div');
    d.textContent = String(str);
    return d.innerHTML;
}

function openServerDetailModal(serverId) {
    const s = window.SERVERS_MAP[String(serverId)];
    if (!s) return;
    const modal = document.getElementById('serverDetailModal');
    const body = document.getElementById('serverDetailBody');
    const osIcon = s.operating_system === 'Linux' ? 'fab fa-linux text-green-600'
        : (s.operating_system === 'Windows' ? 'fab fa-windows text-blue-600' : 'fas fa-desktop text-gray-600');
    const logoHtml = s.logo_url
        ? `<img src="${escapeHtml(s.logo_url)}" alt="" class="h-16 w-16 object-contain rounded-lg border border-gray-100 bg-white p-1">`
        : `<div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 text-gray-500"><i class="fas fa-server text-3xl"></i></div>`;

    let links = '';
    if (s.webmin_url) {
        links += `<a href="${escapeHtml(s.webmin_url)}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-lg bg-purple-600 px-3 py-2 text-xs font-semibold text-white hover:bg-purple-700"><i class="fas fa-cog mr-2"></i>Webmin</a>`;
    }
    if (s.nginx_url) {
        links += `<a href="${escapeHtml(s.nginx_url)}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700"><i class="fas fa-globe mr-2"></i>Nginx</a>`;
    }
    if (window.SERVERS_CAN_PING && s.monitor_status) {
        links += `<button type="button" id="serverDetailPingBtn" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 disabled:opacity-60"><i class="fas fa-sync-alt mr-2"></i>Ping</button>`;
    }

    body.innerHTML = `
        <div class="mb-4 flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
            <div class="flex min-w-0 flex-1 items-start gap-3">
                ${logoHtml}
                <div class="min-w-0">
                    <h3 id="serverDetailTitle" class="text-lg font-bold text-gray-900 leading-tight">${escapeHtml(s.name)}</h3>
                    <span id="serverDetailStatusBadge" class="mt-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white ${s.status_class || 'bg-gray-500'}">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-white"></span>${escapeHtml(s.status_text)}
                    </span>
                </div>
            </div>
            <button type="button" onclick="closeServerDetailModal()" class="flex-shrink-0 rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600" aria-label="Fechar">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <dl class="space-y-3 text-sm">
            <div class="flex flex-wrap items-center gap-2">
                <dt class="font-medium text-gray-500">IP</dt>
                <dd class="font-mono text-gray-900">${escapeHtml(s.ip_address)}</dd>
                <button type="button" id="serverDetailCopyIpBtn" class="inline-flex items-center rounded border border-gray-200 bg-gray-50 px-2 py-0.5 text-xs text-gray-600 shadow-sm transition hover:bg-gray-100 hover:scale-105 active:scale-95" title="Copiar IP" aria-label="Copiar IP">
                    <i class="fas fa-copy" aria-hidden="true"></i>
                </button>
            </div>
            ${s.data_center ? `<div><dt class="font-medium text-gray-500 inline mr-2">Data center</dt><dd class="inline text-gray-900">${escapeHtml(s.data_center)}</dd></div>` : ''}
            ${s.group ? `<div><dt class="font-medium text-gray-500 inline mr-2">Grupo</dt><dd class="inline text-gray-900">${escapeHtml(s.group)}</dd></div>` : ''}
            ${s.operating_system ? `<div class="flex items-center gap-2"><dt class="font-medium text-gray-500">Sistema</dt><dd class="inline-flex items-center gap-2 text-gray-900"><i class="${osIcon}"></i>${escapeHtml(s.operating_system)}</dd></div>` : ''}
            <div>
                <dt class="font-medium text-gray-500 inline mr-2">Tempo de resposta</dt>
                <dd id="serverDetailResponseTime" class="inline text-gray-900">${s.response_time != null ? escapeHtml(String(s.response_time)) + ' ms' : '—'}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500 inline mr-2">Última verificação</dt>
                <dd id="serverDetailLastCheck" class="inline text-gray-900">${s.last_status_check ? escapeHtml(s.last_status_check) : '—'}</dd>
            </div>
            ${s.description ? `<div class="pt-1"><dt class="font-medium text-gray-500 mb-1 block">Descrição</dt><dd class="text-gray-800 leading-relaxed">${escapeHtml(s.description)}</dd></div>` : ''}
        </dl>
        ${links ? `<div class="mt-6 flex flex-wrap gap-2 border-t border-gray-100 pt-4">${links}</div>` : ''}
    `;

    document.getElementById('serverDetailCopyIpBtn')?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        copyToClipboard(s.ip_address, this);
    });

    const pingBtn = document.getElementById('serverDetailPingBtn');
    if (pingBtn) {
        pingBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            checkServerStatus(s.id, this);
        });
    }

    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeServerDetailModal() {
    const modal = document.getElementById('serverDetailModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    const fsOpen = document.getElementById('serversFullscreenModal')?.classList.contains('servers-fs-open');
    if (!fsOpen) {
        document.body.classList.remove('overflow-hidden');
    }
}

document.getElementById('serverDetailModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeServerDetailModal();
    }
});

/** Atualiza o modal e SERVERS_MAP após ping sem recarregar a página. */
function applyServerDetailPingResult(serverId, data) {
    const key = String(serverId);
    const map = window.SERVERS_MAP[key];
    if (map && typeof map === 'object') {
        map.status_text = data.status_text;
        map.status_class = data.status_class;
        map.response_time = data.response_time;
        map.last_status_check = data.last_check;
    }
    const badge = document.getElementById('serverDetailStatusBadge');
    if (badge) {
        const cls = data.status_class || 'bg-gray-500';
        badge.className =
            'mt-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white ' + cls;
        badge.innerHTML =
            '<span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-white"></span>' +
            escapeHtml(data.status_text || '');
    }
    const rt = document.getElementById('serverDetailResponseTime');
    if (rt) {
        rt.textContent = data.response_time != null && data.response_time !== '' ? String(data.response_time) + ' ms' : '—';
    }
    const lc = document.getElementById('serverDetailLastCheck');
    if (lc) {
        lc.textContent = data.last_check || '—';
    }
}

document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    const fsModal = document.getElementById('serversFullscreenModal');
    if (fsModal?.classList.contains('servers-fs-open')) {
        if (typeof window.closeServersFullscreenModal === 'function') {
            window.closeServersFullscreenModal();
        }
        e.preventDefault();
        return;
    }
    closeServerDetailModal();
});

// Função para copiar IP para clipboard com animação
function copyToClipboard(text, button) {
    // Usar a API moderna do clipboard se disponível
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => {
            showCopyFeedback(button);
        }).catch(() => {
            fallbackCopyTextToClipboard(text, button);
        });
    } else {
        fallbackCopyTextToClipboard(text, button);
    }
}

// Fallback para navegadores mais antigos
function fallbackCopyTextToClipboard(text, button) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyFeedback(button);
        } else {
            console.error('Falha ao copiar texto');
        }
    } catch (err) {
        console.error('Erro ao copiar texto: ', err);
    }
    
    document.body.removeChild(textArea);
}

// Mostrar feedback visual de cópia
function showCopyFeedback(button) {
    if (!button) return;
    const icon = button.querySelector('i');
    if (!icon) return;
    if (!button.dataset.copyDefaultClass) {
        button.dataset.copyDefaultClass = button.className;
    }
    const originalIconClass = icon.className;
    icon.className = 'fas fa-check text-xs';
    button.className =
        button.dataset.copyDefaultClass +
        ' border-green-400 bg-green-50 text-green-700 scale-110 transition-transform duration-200';
    button.title = 'Copiado!';

    window.clearTimeout(button._copyFeedbackTimer);
    button._copyFeedbackTimer = window.setTimeout(() => {
        icon.className = originalIconClass;
        button.className = button.dataset.copyDefaultClass;
        button.title = 'Copiar IP';
    }, 2000);
}
</script>

<script>
function checkServerStatus(serverId, buttonEl) {
    const button = buttonEl || (typeof event !== 'undefined' ? event.currentTarget : null);
    if (!button) return;
    const originalContent = button.innerHTML;
    
    // Mostrar loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Pingando...';
    button.disabled = true;
    
    fetch(`/servers/${serverId}/check-status`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            applyServerDetailPingResult(serverId, data);
        } else {
            alert(data.message || 'Erro ao verificar status do servidor');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao verificar status do servidor');
    })
    .finally(() => {
        // Restaurar botão
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /srv/www/Engehub/resources/views/servers/index.blade.php ENDPATH**/ ?>