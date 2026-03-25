
<?php
    $p = $prefix ?? '';
    $clientFs = !empty($clientSideOnlyFullscreen);
    $formId = $p === 'fs_' ? 'serversFiltersFormFs' : 'serversFiltersForm';
    $labelClass = $clientFs ? 'mr-1.5 text-xs font-medium text-gray-600' : 'text-sm font-medium text-gray-700 mr-2';
    $selectClass = $clientFs
        ? 'max-w-[10rem] border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-xs py-1.5'
        : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm';
    $onchangeAttr = $clientFs ? 'onchange="window.applyServersFullscreenFilters?.()"' : 'onchange="this.form.submit()"';
    $optAllDc = $clientFs ? 'Todos' : 'Todos os Datacenters';
    $optAllOs = $clientFs ? 'Todos' : 'Todos os Sistemas';
    $optAllGrp = $clientFs ? 'Todos' : 'Todos os Grupos';
?>
<?php if($clientFs): ?>
<div id="<?php echo e($formId); ?>" role="group" aria-label="Filtros da vista em tela cheia" class="flex flex-wrap items-center gap-x-2 gap-y-1.5 sm:gap-x-3">
<?php else: ?>
<form method="GET" action="<?php echo e(route('servers.index')); ?>" id="<?php echo e($formId); ?>" class="flex min-w-0 flex-wrap items-end gap-4 sm:items-center">
<?php endif; ?>
    <?php if($datacenters->count() > 0): ?>
        <div class="flex flex-shrink-0 items-center">
            <label for="<?php echo e($p); ?>datacenter_id" class="<?php echo e($labelClass); ?>">
                <i class="fas fa-building <?php echo e($clientFs ? 'mr-0.5 sm:mr-1' : 'mr-1'); ?>"></i>
                <?php if($clientFs): ?><span class="hidden sm:inline">DC</span><?php else: ?> Datacenter <?php endif; ?>
            </label>
            <select <?php if (! ($clientFs)): ?> name="datacenter_id" <?php endif; ?>
                    id="<?php echo e($p); ?>datacenter_id"
                    <?php echo $onchangeAttr; ?>

                    class="<?php echo e($selectClass); ?>">
                <option value=""><?php echo e($optAllDc); ?></option>
                <?php $__currentLoopData = $datacenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $datacenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($datacenter->id); ?>"
                            <?php echo e($selectedDatacenter == $datacenter->id ? 'selected' : ''); ?>>
                        <?php echo e($datacenter->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    <?php endif; ?>

    <div class="flex flex-shrink-0 items-center">
        <label for="<?php echo e($p); ?>operating_system" class="<?php echo e($labelClass); ?>">
            <i class="fas fa-desktop <?php echo e($clientFs ? 'mr-0.5 sm:mr-1' : 'mr-1'); ?>"></i>
            <?php if($clientFs): ?><span class="hidden sm:inline">SO</span><?php else: ?> Sistema Operacional <?php endif; ?>
        </label>
        <select <?php if (! ($clientFs)): ?> name="operating_system" <?php endif; ?>
                id="<?php echo e($p); ?>operating_system"
                <?php echo $onchangeAttr; ?>

                class="<?php echo e($selectClass); ?>">
            <option value=""><?php echo e($optAllOs); ?></option>
            <option value="Linux" <?php echo e($selectedOperatingSystem == 'Linux' ? 'selected' : ''); ?>>Linux</option>
            <option value="Windows" <?php echo e($selectedOperatingSystem == 'Windows' ? 'selected' : ''); ?>>Windows</option>
            <option value="Outros" <?php echo e($selectedOperatingSystem == 'Outros' ? 'selected' : ''); ?>>Outros</option>
        </select>
    </div>

    <?php if($serverGroups->count() > 0): ?>
        <div class="flex flex-shrink-0 items-center">
            <label for="<?php echo e($p); ?>server_group_id" class="<?php echo e($labelClass); ?>">
                <i class="fas fa-folder <?php echo e($clientFs ? 'mr-0.5 sm:mr-1' : 'mr-1'); ?>"></i>
                Grupo
            </label>
            <select <?php if (! ($clientFs)): ?> name="server_group_id" <?php endif; ?>
                    id="<?php echo e($p); ?>server_group_id"
                    <?php echo $onchangeAttr; ?>

                    class="<?php echo e($selectClass); ?>">
                <option value=""><?php echo e($optAllGrp); ?></option>
                <?php $__currentLoopData = $serverGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($group->id); ?>"
                            <?php echo e($selectedServerGroup == $group->id ? 'selected' : ''); ?>>
                        <?php echo e($group->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    <?php endif; ?>

    <?php if($clientFs): ?>
        <button type="button" id="serversFsClearFiltersBtn"
                class="whitespace-nowrap rounded-md border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 sm:px-3 sm:py-2 sm:text-sm">
            Limpar Filtros
        </button>
        <span id="serversFsFilterSummary" class="hidden text-xs text-gray-500"></span>
    <?php endif; ?>
<?php if($clientFs): ?>
</div>
<?php else: ?>
</form>
<?php endif; ?>
<?php /**PATH /srv/www/Engehub/resources/views/servers/partials/filters-form.blade.php ENDPATH**/ ?>