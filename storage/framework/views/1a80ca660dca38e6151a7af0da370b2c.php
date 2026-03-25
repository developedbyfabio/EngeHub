<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['title' => null, 'icon' => 'fas fa-cog']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['title' => null, 'icon' => 'fas fa-cog']); ?>
<?php foreach (array_filter((['title' => null, 'icon' => 'fas fa-cog']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>


<div class="flex justify-between items-center">
    <?php if(isset($left)): ?>
        <div><?php echo e($left); ?></div>
    <?php else: ?>
        <div class="flex flex-col">
            <?php if(isset($beforeTitle)): ?>
                <div class="leading-tight mb-0.5"><?php echo e($beforeTitle); ?></div>
            <?php endif; ?>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="<?php echo e($icon); ?> mr-2 flex-shrink-0" style="color: #E9B32C; font-size: 1.25rem;"></i>
                <?php echo e($title ?? ''); ?>

            </h2>
        </div>
    <?php endif; ?>
    <?php if(isset($actions)): ?>
        <div class="flex items-center gap-3 flex-wrap justify-end">
            <?php echo e($actions); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH /srv/www/Engehub/resources/views/components/page-header.blade.php ENDPATH**/ ?>