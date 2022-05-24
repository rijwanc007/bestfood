<div class="col-md-12">
    <div class="row">
        <div class="table-responsive col-9">
            <table class="table table-borderless">
                <tr>
                    <td><b>Name</b></td><td><b>:</b></td><td><?php echo e($salesmen->name); ?></td>
                    <td><b>Username</b></td><td><b>:</b></td><td><?php echo e($salesmen->username); ?></td>
                </tr>
                <tr>
                    <td><b>Phone</b></td><td><b>:</b></td><td><?php echo e($salesmen->phone); ?></td>
                    <td><b>Email</b></td><td><b>:</b></td><td><?php echo $salesmen->email ? $salesmen->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>'; ?></td>
                </tr>
                
                <tr>
                    <td><b>NID No.</b></td><td><b>:</b></td><td><?php echo e($salesmen->nid_no); ?></td>
                    <td><b>Monthly Target Value</b></td><td><b>:</b></td><td><?php echo e(number_format($salesmen->monthly_target_value,2,'.','')); ?> Tk</td>
                </tr>
                <tr>
                    <td><b>Commission Rate</b></td><td><b>:</b></td><td><?php echo e(number_format($salesmen->cpr,2,'.','')); ?>%</td>
                    <td><b>Warehouse</b></td><td><b>:</b></td><td><?php echo e($salesmen->warehouse->name); ?></td>
                    
                </tr>
                <tr>
                    <td><b>District</b></td><td><b>:</b></td><td><?php echo e($salesmen->district->name); ?></td>
                    <td><b>Upazila</b></td><td><b>:</b></td><td><?php echo e($salesmen->upazila->name); ?></td>
                    
                </tr>
                <tr>
                    <td><b>Address</b></td><td><b>:</b></td><td><?php echo e($salesmen->address); ?></td>
                    <td><b>Status</b></td><td><b>:</b></td><td><?php echo STATUS_LABEL[$salesmen->status]; ?></td>
                    
                </tr>
                <tr>
                    <td><b>Created By</b></td><td><b>:</b></td><td><?php echo e($salesmen->created_by); ?></td>
                    <td><b>Modified By</b></td><td><b>:</b></td><td><?php echo e($salesmen->modified_by); ?></td>
                    
                </tr>
                <tr>
                    <td><b>Create Date</b></td><td><b>:</b></td><td><?php echo e($salesmen->created_at ? date(config('settings.date_format'),strtotime($salesmen->created_at)) : ''); ?></td>
                    <td><b>Modified Date</b></td><td><b>:</b></td><td><?php echo e($salesmen->updated_at ? date(config('settings.date_format'),strtotime($salesmen->updated_at)) : ''); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-3 text-center">
            <?php if($salesmen->avatar): ?>
                <img src='storage/<?php echo e(SALESMEN_AVATAR_PATH.$salesmen->avatar); ?>' alt='<?php echo e($salesmen->name); ?>' style='width:150px;'/>
            <?php else: ?>
                <img src='images/male.svg' alt='Default Image' style='width:150px;'/>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <h6 class="bg-primary text-center text-white" style="width: 250px;padding: 5px; margin: 10px auto 5px auto;">Day wise visiting routes</h6>
        <table class="table table-bordered">
            <thead class="bg-primary">
                <th>Day</th>
                <th>Route</th>
            </thead>
            <tbody>
                <?php if(!$salesmen->routes->isEmpty()): ?>
                    <?php $__currentLoopData = $salesmen->routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(DAYS[$route->pivot->day]); ?></td>
                            <td><?php echo e($route->name); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div><?php /**PATH /home/demoff/bestfood/Modules/SalesMen/Resources/views/view-data.blade.php ENDPATH**/ ?>