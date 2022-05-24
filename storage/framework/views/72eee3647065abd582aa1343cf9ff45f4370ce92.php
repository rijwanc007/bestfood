<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <base href="<?php echo e(asset('/')); ?>" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e(config('settings.title') ? config('settings.title') : config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
		<link rel="icon" type="image/png" href="<?php echo e('storage/'.LOGO_PATH.config('settings.favicon')); ?>">
		<link rel="shortcut icon" href="<?php echo e('storage/'.LOGO_PATH.config('settings.favicon')); ?>" />
        <?php echo $__env->make('layouts.includes.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </head>
    <body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-minimize-hoverable aside-fixed page-loading">
        <div id="preloader">
            <div id="loading">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw text-primary" aria-hidden="true" style="font-size: 80px;"></i>
            </div>
        </div>
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.layout','data' => []]); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
    </body>
</html>
<?php /**PATH /home/demoff/bestfood/resources/views/layouts/app.blade.php ENDPATH**/ ?>