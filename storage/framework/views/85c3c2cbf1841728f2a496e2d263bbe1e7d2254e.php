

<?php $__env->startSection('title', $page_title); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="<?php echo e($page_icon); ?> text-primary"></i> <?php echo e($sub_title); ?></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="<?php echo e(route('role')); ?>" class="btn btn-secondary btn-sm font-weight-bolder"> 
                        <i class="fas fa-arrow-circle-left"></i> Back
                    </a>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form id="saveDataForm" method="post">
                            <?php echo csrf_field(); ?> 
                            <div class="row">
                                <input type="hidden" name="update_id" value="<?php if(isset($role)): ?><?php echo e($role->id); ?><?php endif; ?>" id="update_id">
                                <div class="form-group col-md-12 required">
                                    <label for="role_name">Role Name</label>
                                    <input type="text" class="form-control" name="role_name" id="role_name" value="<?php if(isset($role)): ?><?php echo e($role->role_name); ?><?php endif; ?>" placeholder="Enter role name">
                                </div>
                                <div class="col-md-12">
                                    <ul id="permission" class="text-left">
                                        <?php if(!empty($permission_modules)): ?>
                                            <?php $__currentLoopData = $permission_modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($menu->submenu->isEmpty()): ?>
                                                    <li>
                                                        <input type="checkbox" name="module[]" class="module" value="<?php echo e($menu->id); ?>"
                                                        <?php if(isset($role_module)): ?> <?php if(collect($role_module)->contains($menu->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> > 
                                                        <?php echo $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name; ?>

                                                        <?php if(!$menu->permission->isEmpty()): ?>
                                                            <ul>
                                                                <?php $__currentLoopData = $menu->permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li><input type="checkbox" name="permission[]" value="<?php echo e($permission->id); ?>"
                                                                        <?php if(isset($role_permission)): ?> <?php if(collect($role_permission)->contains($permission->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> />
                                                                            <?php echo e($permission->name); ?>

                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php else: ?> 
                                                <li>
                                                    <input type="checkbox" name="module[]" class="module" value="<?php echo e($menu->id); ?>"
                                                    <?php if(isset($role_module)): ?> <?php if(collect($role_module)->contains($menu->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> > 
                                                    <?php echo $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name; ?>

                                                    <ul>
                                                        <?php $__currentLoopData = $menu->submenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($submenu->submenu->isEmpty()): ?>
                                                                <li>
                                                                    <input type="checkbox" name="module[]" class="module" value="<?php echo e($submenu->id); ?>"
                                                                    <?php if(isset($role_module)): ?> <?php if(collect($role_module)->contains($submenu->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> >
                                                                        <?php echo e($submenu->module_name); ?>

                                                                    <?php if(!$submenu->permission->isEmpty()): ?>
                                                                        <ul>
                                                                            <?php $__currentLoopData = $submenu->permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li><input type="checkbox" name="permission[]" value="<?php echo e($permission->id); ?>" 
                                                                                <?php if(isset($role_permission)): ?> <?php if(collect($role_permission)->contains($permission->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> />
                                                                                <?php echo e($permission->name); ?>

                                                                            </li>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </ul>
                                                                    <?php endif; ?>
                                                                </li>
                                                            <?php else: ?>
                                                            <li>
                                                                <input type="checkbox" name="module[]" class="module" value="<?php echo e($submenu->id); ?>"
                                                                <?php if(isset($role_module)): ?> <?php if(collect($role_module)->contains($submenu->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> > 
                                                                <?php echo $submenu->module_name; ?>

                                                                <ul>
                                                                    <?php $__currentLoopData = $submenu->submenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_submenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li>
                                                                        <input type="checkbox" name="module[]" class="module" value="<?php echo e($sub_submenu->id); ?>"
                                                                        <?php if(isset($role_module)): ?> <?php if(collect($role_module)->contains($sub_submenu->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> >
                                                                            <?php echo e($sub_submenu->module_name); ?>

                                                                        <?php if(!$sub_submenu->permission->isEmpty()): ?>
                                                                            <ul>
                                                                                <?php $__currentLoopData = $sub_submenu->permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <li><input type="checkbox" name="permission[]" value="<?php echo e($permission->id); ?>" 
                                                                                    <?php if(isset($role_permission)): ?> <?php if(collect($role_permission)->contains($permission->id)): ?> <?php echo e('checked'); ?> <?php endif; ?> <?php endif; ?> />
                                                                                    <?php echo e($permission->name); ?>

                                                                                </li>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </ul>
                                                                        <?php endif; ?>
                                                                    </li>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </ul>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="col-md-12 pt-4 text-center">
                                    <?php if(isset($role)): ?>
                                    <a href="<?php echo e(route('role')); ?>" class="btn btn-danger btn-sm font-weight-bolder">Cancel</a>
                                    <?php else: ?>
                                    <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"><?php if(isset($role)): ?> <?php echo e('Update'); ?> <?php else: ?> <?php echo e('Save'); ?> <?php endif; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="js/tree.js"></script>
<script>
$(document).ready(function(){
    $('#permission').treed(); //intialized tree js
    $('input[type=checkbox]').click(function(){
        $(this).next().find('input[type=checkbox]').prop('checked',this.checked);
        $(this).parents('ul').prev('input[type=checkbox]').prop('checked', function(){
            return $(this).next().find(':checked').length;
        });
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('saveDataForm');
        let formData = new FormData(form);
        if($('.module:checked').length >= 1){
            $.ajax({
                url: "<?php echo e(route('role.store.or.update')); ?>",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function(){
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function (data) {
                    $('#saveDataForm').find('.is-invalid').removeClass('is-invalid');
                    $('#saveDataForm').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#saveDataForm input#' + key).addClass('is-invalid');
                            $('#saveDataForm #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("<?php echo e(route('role')); ?>");
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }else{
            notification('error','Please check at least one menu');
        }
        
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/demoff/bestfood/resources/views/role/form.blade.php ENDPATH**/ ?>