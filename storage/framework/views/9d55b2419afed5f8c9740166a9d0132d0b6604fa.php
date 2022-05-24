

<?php $__env->startSection('title', $page_title); ?>

<?php $__env->startPush('styles'); ?>
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="<?php echo e($page_icon); ?> text-primary"></i> <?php echo e($sub_title); ?></h3>
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row justify-content-center">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form.textbox','data' => ['labelName' => 'Product Name','name' => 'product_name','required' => 'required','col' => 'col-md-3']]); ?>
<?php $component->withName('form.textbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['labelName' => 'Product Name','name' => 'product_name','required' => 'required','col' => 'col-md-3']); ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
                        <input type="hidden" class="form-control bg-brand" name="product_id" id="product_id">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form.selectbox','data' => ['labelName' => 'Warehouse','name' => 'warehouse_id','col' => 'col-md-3','required' => 'required','class' => 'selectpicker']]); ?>
<?php $component->withName('form.selectbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['labelName' => 'Warehouse','name' => 'warehouse_id','col' => 'col-md-3','required' => 'required','class' => 'selectpicker']); ?>
                            <?php if(!$warehouses->isEmpty()): ?>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                         <?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
                        <div class="col-md-6">
                            <div style="margin-top:28px;">      
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                    data-toggle="tooltip" data-theme="dark" title="Reset">
                                    <i class="fas fa-undo-alt"></i></button>
    
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                    data-toggle="tooltip" data-theme="dark" title="Search" onclick="load_data()">
                                    <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row" style="position:relative;">
                        <div class="col-sm-12" id="product_list">

                        </div>
                        <div class="col-md-12 d-none" id="table-loader" style="position: absolute;top:120px;left:0;">
                            <div style="width: 120px;
                            height: 70px;
                            background: white;
                            text-align: center;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border: 1px solid #ddd;
                            border-radius: 5px;
                            margin: 0 auto;">
                                <i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.printarea.js"></script>
<script>
$(document).ready(function(){
    $('#product_name').autocomplete({
        source: function( request, response ) {
            // Fetch data
            $.ajax({
                url:"<?php echo e(url('stock/product-search')); ?>",
                type: 'post',
                dataType: "json",
                data: { _token: _token,search: request.term},
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        response: function(event, ui) {
            if (ui.content.length == 1) {
                $('#product_name').val(ui.content[0].value);
                $('#product_id').val(ui.content[0].id);
                $(this).autocomplete( "close" );
            };
        },
        select: function (event, ui) {
            $('#product_name').val(ui.item.value);
            $('#product_id').val(ui.item.id);
            // var data = ui.item.value;
        },
    }).data('ui-autocomplete')._renderItem = function (ul, item) {
        return $("<li class='ui-autocomplete-row'></li>")
            .data("item.autocomplete", item)
            .append(item.label)
            .appendTo(ul);
    };

    $('#product_name').on('keyup',function(){
        if($(this).val() == ''){ $('#product_id').val(''); }
    });
    

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#product_id').val('');
        $('#form-filter .selectpicker').selectpicker('refresh');
        $('#product_list').html('');
    });

    
    $(document).on('click','#print-report',function(){
        var mode = 'iframe'; // popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        $("#report").printArea(options);
    });

});

function load_data()
{
    var warehouse_id = $('#warehouse_id option:selected').val();
    var product_id   = $('#product_id').val();
    if(product_id)
    {
        $.ajax({
            url: "<?php echo e(route('product.stock.datatable.data')); ?>",
            type: "POST",
            data: {product_id:product_id,warehouse_id:warehouse_id,_token:_token},
            beforeSend: function(){
                $('#table-loader').removeClass('d-none');
            },
            complete: function(){
                $('#table-loader').addClass('d-none');
            },
            success: function (data) {
                $('#product_list').empty().html(data);
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }else{
        notification('error','Please select product');
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/demoff/bestfood/Modules/Stock/Resources/views/product/index.blade.php ENDPATH**/ ?>