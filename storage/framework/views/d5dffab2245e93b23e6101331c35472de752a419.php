<div class="form-group <?php echo e($col ?? ''); ?> <?php echo e($required ?? ''); ?>" >
    <label for="<?php echo e($name); ?>"><?php echo e($labelName); ?></label>
    <textarea name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" class="form-control <?php echo e($class ?? ''); ?>" 
     placeholder="<?php echo e($placeholder ?? ''); ?>"><?php echo e($value ?? ''); ?></textarea>
</div><?php /**PATH /home/demoff/bestfood/resources/views/components/form/textarea.blade.php ENDPATH**/ ?>