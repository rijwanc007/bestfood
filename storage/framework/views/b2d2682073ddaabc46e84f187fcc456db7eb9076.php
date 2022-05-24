<div id="kt_header_mobile" class="header-mobile  header-mobile-fixed ">
    <div class="mobile-logo">

            <?php if(config('settings.logo')): ?>
            <a href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('storage/'.LOGO_PATH.config('settings.logo'))); ?>" style="width: 90px;" alt="Logo" />
            </a>
            <?php else: ?>
            <h3 class="text-white"><?php echo e(config('settings.title') ? config('settings.title') : env('APP_NAME')); ?></h3>
            <?php endif; ?>
    </div>
    <div class="d-flex align-items-center">

        <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle"><span></span></button>

        

        <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
            <span class="svg-icon svg-icon-xl"><i class="fas fa-user text-white"></i></span>
        </button>

    </div>
</div><?php /**PATH /home/demoff/bestfood/resources/views/components/mobile-header.blade.php ENDPATH**/ ?>