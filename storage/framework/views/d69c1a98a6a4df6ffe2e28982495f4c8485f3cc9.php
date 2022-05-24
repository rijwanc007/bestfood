<div class="subheader py-2  subheader-solid " id="kt_subheader">
    <div  class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-right flex-wrap mr-1">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"> <?php echo e($page_title); ?></h5>
        </div>
        
        <div class="d-flex align-items-center pt-4">
            <?php if(request()->is('/') || request()->is('asm/dashboard')): ?>
            <div class="filter-toggle btn-group float-right" style="margin-bottom: 1rem;margin-right: 1rem;">
                <div class="btn btn-primary btn-sm today-btn data-btn active" data-start_date="<?php echo e(date('Y-m-d')); ?>" data-end_date="<?php echo e(date('Y-m-d')); ?>">Today</div>
                <div class="btn btn-primary btn-sm week-btn data-btn" data-start_date="<?php echo e(date('Y-m-d',strtotime('-7 day'))); ?>" data-end_date="<?php echo e(date('Y-m-d')); ?>">This Week</div>
                <div class="btn btn-primary btn-sm month-btn data-btn" data-start_date="<?php echo e(date('Y-m').'-01'); ?>" data-end_date="<?php echo e(date('Y-m-d')); ?>">This Month</div>
                <div class="btn btn-primary btn-sm year-btn data-btn" data-start_date="<?php echo e(date('Y').'-01-01'); ?>" data-end_date="<?php echo e(date('Y').'-12-31'); ?>">This Year</div>
            </div>
            <?php endif; ?>
            

            <ol class="breadcrumb float-right pull-right">
                <li><a href="<?php echo e(route('dashboard')); ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                <?php if(!empty($breadcrumb)): ?>
                    <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!isset($item['link'])): ?>
                        <li class="active"><?php echo e($item['name']); ?></li>
                        <?php else: ?> 
                        <li><a href="<?php echo e($item['link']); ?>"><?php echo e($item['name']); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ol>
        </div>
    </div>
</div><?php /**PATH /home/demoff/bestfood/resources/views/layouts/includes/sub-header.blade.php ENDPATH**/ ?>