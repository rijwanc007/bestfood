@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="css/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<style>
    #dataTable{
        width:2000px !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{ route('sale.add') }}"  class="btn btn-primary btn-sm font-weight-bolder"> 
                        <i class="fas fa-plus-circle"></i> Add New</a>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="Memo No." name="memo_no" col="col-md-3" />
                        <div class="form-group col-md-3">
                            <label for="name">Choose Your Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-filed">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <x-form.selectbox labelName="Order Received By" name="salesmen_id" col="col-md-3" class="selectpicker" onchange="getRouteList(this.value)">
                            @if (!$salesmen->isEmpty())
                                @foreach ($salesmen as $value)
                                    <option value="{{ $value->id }}">{{ $value->name.' - '.$value->phone }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <x-form.selectbox labelName="District" name="district_id" col="col-md-3" class="selectpicker" onchange="getUpazilaList(this.value)">
                            @if (!$locations->isEmpty())
                                @foreach ($locations as $location)
                                    @if ($location->type == 1)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <x-form.selectbox labelName="Upazila" name="upazila_id" col="col-md-3" class="selectpicker" onchange="getRouteList(this.value)">
                            @if (!$locations->isEmpty())
                                @foreach ($locations as $location)
                                    @if ($location->type == 2)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <x-form.selectbox labelName="Route" name="route_id" col="col-md-3" class="selectpicker" onchange="getAreaList(this.value);">
                            @if (!$locations->isEmpty())
                                @foreach ($locations as $location)
                                    @if ($location->type == 3)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <x-form.selectbox labelName="Area" name="area_id" col="col-md-3" class="selectpicker" onchange="customer_list(this.value)">
                            @if (!$locations->isEmpty())
                                @foreach ($locations as $location)
                                    @if ($location->type == 4)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Customer" name="customer_id" col="col-md-3" class="selectpicker"/>
                        

                        <x-form.selectbox labelName="Payment Status" name="payment_status" col="col-md-3" class="selectpicker">
                            @foreach (PAYMENT_STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>

                        <div class="col-md-9">
                            <div style="margin-top:28px;">     
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                    data-toggle="tooltip" data-theme="dark" title="Reset">
                                    <i class="fas fa-undo-alt"></i></button>
    
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                    data-toggle="tooltip" data-theme="dark" title="Search">
                                    <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        @if (permission('sale-bulk-delete'))
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        @endif
                                        <th>Sl</th>
                                        <th>Memo No.</th>
                                        <th>Sale By</th>
                                        <th>Customer Name</th>
                                        <th>Total Item</th>
                                        <th>Total</th>
                                        <th>Tax Rate(%)</th>
                                        <th>Order Tax</th>
                                        <th>Discount</th>
                                        <th>Labor Cost</th>
                                        <th>Shipping Cost</th>
                                        <th>Grand Total</th>
                                        <th>Previous Due</th>
                                        <th>Net Total</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>SR Commission Rate(%)</th>
                                        <th>SR Total Commission</th>
                                        <th>Sale Date</th>
                                        <th>Payment Status</th>
                                        <th>Payment Method</th>
                                        <th>Delivery Status</th>
                                        <th>Delivery Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@include('sale::delivery-modal')
@endsection

@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="js/moment.js"></script>
<script src="js/knockout-3.4.2.js"></script>
<script src="js/daterangepicker.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
$('.daterangepicker-filed').daterangepicker({
    callback: function(startDate, endDate, period){
        var start_date = startDate.format('YYYY-MM-DD');
        var end_date   = endDate.format('YYYY-MM-DD');
        var title = start_date + ' To ' + end_date;
        $(this).val(title);
        $('input[name="start_date"]').val(start_date);
        $('input[name="end_date"]').val(end_date);
    }
});
var table;
$(document).ready(function(){
    $('.date').datetimepicker({format: 'YYYY-MM-DD',ignoreReadonly: true});
    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": false, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 25, //number of data show per page
        "language": { 
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('sale.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.memo_no        = $("#form-filter #memo_no").val();
                data.start_date     = $("#form-filter #start_date").val();
                data.end_date       = $("#form-filter #end_date").val();
                data.salesmen_id    = $("#form-filter #salesmen_id").val();
                data.customer_id    = $("#form-filter #customer_id").val();
                data.district_id     = $("#form-filter #district_id").val();
                data.upazila_id     = $("#form-filter #upazila_id").val();
                data.route_id       = $("#form-filter #route_id").val();
                data.area_id        = $("#form-filter #area_id").val();
                data.payment_status = $("#form-filter #payment_status").val();
                data._token         = _token;
            }
        },
        "columnDefs": [{
                @if (permission('sale-bulk-delete'))
                "targets": [0,24],
                @else
                "targets": [23],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('sale-bulk-delete'))
                "targets": [1,5,7,17,18,19,20,21,22,23],
                @else
                "targets": [0,1,4,6,16,17,18,19,20,21,22],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('sale-bulk-delete'))
                "targets": [6,8,9,10,11,12,13,14,15,16],
                @else
                "targets": [5,7,8,9,10,11,12,13,14,15],
                @endif
                "className": "text-right"
            },

        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column','columns': ':gt(0)'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "{{ $page_title }} List",
                "orientation": "landscape", //portrait
                "pageSize": "legal", //A3,A5,A6,legal,letter
                "exportOptions": {
                    @if (permission('sale-bulk-delete'))
                    columns: ':visible:not(:eq(24))' 
                    @else 
                    columns: ':visible:not(:eq(23))' 
                    @endif
                },
                customize: function (win) {
                    $(win.document.body).addClass('bg-white');
                    $(win.document.body).find('table thead').css({'background':'#034d97'});
                    $(win.document.body).find('table tfoot tr').css({'background-color':'#034d97'});
                    $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).find('h1').css('font-size', '15px');
                    $(win.document.body).find('table').css( 'font-size', 'inherit' );
                },
            },
            {
                "extend": 'csv',
                'text':'CSV',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "{{ $page_title }} List",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                "exportOptions": {
                    @if (permission('sale-bulk-delete'))
                    columns: ':visible:not(:eq(24))' 
                    @else 
                    columns: ':visible:not(:eq(23))' 
                    @endif
                }
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "{{ $page_title }} List",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                "exportOptions": {
                    @if (permission('sale-bulk-delete'))
                    columns: ':visible:not(:eq(24))' 
                    @else 
                    columns: ':visible:not(:eq(23))' 
                    @endif
                }
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "{{ $page_title }} List",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                "orientation": "landscape", //portrait
                "pageSize": "legal", //A3,A5,A6,legal,letter
                "exportOptions": {
                    @if (permission('sale-bulk-delete'))
                    columns: ':visible:not(:eq(24))' 
                    @else 
                    columns: ':visible:not(:eq(23))' 
                    @endif
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                    doc.styles.tableHeader.fontSize = 7;
                    doc.pageMargins = [5,5,5,5];
                }  
            },
            @if (permission('sale-bulk-delete'))
            {
                'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                'text':'Delete',
                action:function(e,dt,node,config){
                    multi_delete();
                }
            }
            @endif
        ],
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#form-filter .selectpicker').selectpicker('refresh');
        $('#form-filter #start_date').val('');
        $('#form-filter #end_date').val('');
        table.ajax.reload();
    });


    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('sale.delete') }}";
        delete_data(id, url, table, row, name);
    });

    function multi_delete(){
        let ids = [];
        let rows;
        $('.select_data:checked').each(function(){
            ids.push($(this).val());
            rows = table.rows($('.select_data:checked').parents('tr'));
        });
        if(ids.length == 0){
            Swal.fire({
                type:'error',
                title:'Error',
                text:'Please checked at least one row of table!',
                icon: 'warning',
            });
        }else{
            let url = "{{route('sale.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    //Add Delivery
    $(document).on('click', '.add_delivery', function () {
        $('#delivery_form')[0].reset();
        $('#delivery_form').find('.is-invalid').removeClass('is-invalid');
        $('#delivery_form').find('.error').remove();
        $('.selectpicker').selectpicker('refresh');
        $('#delivery_modal #sale_id').val($(this).data('id'));
        $('#delivery_modal #delivery_status').val($(this).data('status'));
        $('#delivery_modal #delivery_date').val($(this).data('date'));
        $('.selectpicker').selectpicker('refresh');
        $('#delivery_modal').modal({
            keyboard: false,
            backdrop: 'static',
        });
        $('#delivery_modal .modal-title').html( '<i class="fas fa-truck"></i> <span>Change Delivery Status</span>');  
    });

    $(document).on('click','#delivery-save-btn', function(e){
        e.preventDefault();
        let form = document.getElementById('delivery_form');
        let formData = new FormData(form);
        $.ajax({
            url: "{{route('sale.delivery.status.update')}}",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $('#delivery-save-btn').addClass('spinner spinner-white spinner-right');
            },
            complete: function(){
                $('#delivery-save-btn').removeClass('spinner spinner-white spinner-right');
            },
            success: function (data) {
                $('#delivery_form').find('.is-invalid').removeClass('is-invalid');
                $('#delivery_form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        var key = key.split('.').join('_');
                        $('#delivery_form input#' + key).addClass('is-invalid');
                        $('#delivery_form select#' + key).parent().addClass('is-invalid');
                        $('#delivery_form #' + key).parent().append(
                        '<small class="error text-danger">' + value + '</small>');
                                                    
                    });
                } else {
                    notification(data.status, data.message);
                    if (data.status == 'success') {
                        table.ajax.reload(null, false);
                        $('#delivery_modal').modal('hide');
                    }
                }

            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
        
    });

});


function customer_list()
{
    let upazila_id = document.getElementById('upazila_id').value;
    let route_id = document.getElementById('route_id').value;
    let area_id = document.getElementById('area_id').value;
    $.ajax({
        url:"{{ url('customer-list') }}",
        type:"POST",
        data:{upazila_id:upazila_id,route_id:route_id,area_id:area_id,_token:_token},
        dataType:"JSON",
        success:function(data){
            html = `<option value="">Select Please</option>`;
            $.each(data, function(key, value) {
                html += `<option value="${value.id}">${value.name} - ${value.mobile} (${value.shop_name})</option>`;
            });
            $('#form-filter #customer_id').empty().append(html);
            $('#form-filter #customer_id.selectpicker').selectpicker('refresh');
      
        },
    });

}
function getUpazilaList(district_id){
    $.ajax({
        url:"{{ url('district-id-wise-upazila-list') }}/"+district_id,
        type:"GET",
        dataType:"JSON",
        success:function(data){
            html = `<option value="">Select Please</option>`;
            $.each(data, function(key, value) {
                html += '<option value="'+ key +'">'+ value +'</option>';
            });
            $('#form-filter #upazila_id').empty().append(html);
            $('.selectpicker').selectpicker('refresh');
        },
    });
}
function getRouteList(upazila_id){
    $.ajax({
        url:"{{ url('upazila-id-wise-route-list') }}/"+upazila_id,
        type:"GET",
        dataType:"JSON",
        success:function(data){
            html = `<option value="">Select Please</option>`;
            $.each(data, function(key, value) {
                html += '<option value="'+ key +'">'+ value +'</option>';
            });
            $('#form-filter #route_id').empty().append(html);
            $('.selectpicker').selectpicker('refresh');
        },
    });
}
function getAreaList(route_id,selector,area_id=''){
    $.ajax({
        url:"{{ url('route-id-wise-area-list') }}/"+route_id,
        type:"GET",
        dataType:"JSON",
        success:function(data){
            html = `<option value="">Select Please</option>`;
            $.each(data, function(key, value) {
                html += '<option value="'+ key +'">'+ value +'</option>';
            });
            $('#form-filter #area_id').empty().append(html);
            $('.selectpicker').selectpicker('refresh');
        },
    });
}

</script>
@endpush