<div class="form-group <?php echo e($col ?? ''); ?> <?php echo e($required ?? ''); ?>" >
    <label for="<?php echo e($name); ?>"><?php echo e($labelName); ?></label>
    <select name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" class="form-control <?php echo e($class ?? ''); ?>"
      <?php if(!empty($onchange)): ?> onchange="<?php echo e($onchange); ?>" <?php endif; ?> data-live-search="true" 
      data-live-search-placeholder="Search">
        <option value="">Select Please</option>
        <?php echo e($slot); ?>

    </select>
</div><?php /**PATH /home/demoff/bestfood/resources/views/components/form/selectbox.blade.php ENDPATH**/ ?>