<div class="form-group <?php echo e($col ?? ''); ?> <?php echo e($required ?? ''); ?>" >
    <label for="<?php echo e($name); ?>"><?php echo e($labelName); ?></label>
    <input type="<?php echo e($type ?? 'text'); ?>" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" class="form-control <?php echo e($class ?? ''); ?>" value="<?php echo e($value ?? ''); ?>" 
    <?php if(!empty($onkeyup)): ?> onkeyup="<?php echo e($onkeyup ?? ''); ?>" <?php endif; ?>  placeholder="<?php echo e($placeholder ?? ''); ?>" <?php echo e($property ?? ''); ?>>
</div><?php /**PATH /home/demoff/bestfood/resources/views/components/form/textbox.blade.php ENDPATH**/ ?>