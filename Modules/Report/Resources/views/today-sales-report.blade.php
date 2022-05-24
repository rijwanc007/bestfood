@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="Memo No." name="memo_no" col="col-md-3" />
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" col="col-md-3" required="required" onchange="getSalesmenList(this.value)" class="selectpicker">
                            @if (!$warehouses->isEmpty())
                            @foreach ($warehouses as $id => $name)
                                <option value="{{ $id }}" data-name="{{ $name }}">{{ $name }}</option>
                            @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Order Received By" name="salesmen_id" col="col-md-3" class="selectpicker"/>

                        <x-form.selectbox labelName="District" name="district_id" col="col-md-3" class="selectpicker" onchange="getUpazilaList(this.value)">
                            @if (!$districts->isEmpty())
                                @foreach ($districts as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <x-form.selectbox labelName="Upazila" name="upazila_id" col="col-md-3" class="selectpicker" onchange="getRouteList(this.value)"/>

                        <x-form.selectbox labelName="Route" name="route_id" col="col-md-3" class="selectpicker" onchange="getAreaList(this.value);"/>

                        <x-form.selectbox labelName="Area" name="area_id" col="col-md-3" class="selectpicker" onchange="customer_list(this.value)"/>

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
                        <div class="col-sm-12">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Memo No.</th>
                                        <th>Sale By</th>
                                        <th>Upazila</th>
                                        <th>Route</th>
                                        <th>Area</th>
                                        <th>Customer Name</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-primary">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right !important;font-weight:bold;">Total</th>
                                        <th style="text-align: right !important;font-weight:bold;"></th>
                                    </tr>
                                </tfoot>
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
@endsection

@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script>
var table;
var warehouse_name = '';
$(document).ready(function(){
    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
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
            "url": "{{route('today.sales.report.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.memo_no        = $("#form-filter #memo_no").val();
                data.warehouse_id   = $("#form-filter #warehouse_id").val();
                data.salesmen_id    = $("#form-filter #salesmen_id").val();
                data.customer_id    = $("#form-filter #customer_id").val();
                data.district_id    = $("#form-filter #district_id").val();
                data.upazila_id     = $("#form-filter #upazila_id").val();
                data.route_id       = $("#form-filter #route_id").val();
                data.area_id        = $("#form-filter #area_id").val();
                data.payment_status = $("#form-filter #payment_status").val();
                data._token         = _token;
            }
        },
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6],
                "className": "text-center"
            },
            {
                "targets": [7],
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
                "title": function(){
                    return "{{ $page_title }} <br>"+warehouse_name;
                },
                "orientation": "landscape", //portrait
                "pageSize": "legal", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                },
                customize: function (win) {
                    $(win.document.body).addClass('bg-white');
                    $(win.document.body).find('table thead').css({'background':'#034d97'});
                    $(win.document.body).find('table tfoot tr').css({'background-color':'#034d97'});
                    $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).find('h1').css('font-size', '15px');
                    $(win.document.body).find('table').css( 'font-size', 'inherit' );
                },
                footer:true
            },
            {
                "extend": 'csv',
                'text':'CSV',
                'className':'btn btn-secondary btn-sm text-white',
                "title": function(){
                    return "{{ $page_title }} <br>"+warehouse_name;
                },
                "filename": function(){
                    return  warehouse_name+"-{{ strtolower(str_replace(' ','-',$page_title)) }}";
                },
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    } 
                },
                footer:true
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": function(){
                    return "{{ $page_title }} <br>"+warehouse_name;
                },
                "filename": function(){
                    return  warehouse_name+"-{{ strtolower(str_replace(' ','-',$page_title)) }}";
                },
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                },
                footer:true
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": function(){
                    return "{{ $page_title }} <br>"+warehouse_name;
                },
                "filename": function(){
                    return  warehouse_name+"-{{ strtolower(str_replace(' ','-',$page_title)) }}";
                },
                "orientation": "landscape", //portrait
                "pageSize": "legal", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                    doc.styles.tableHeader.fontSize = 7;
                    doc.pageMargins = [5,5,5,5];
                } ,
                footer:true
            },
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            total = api.column(7).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api.column(7, { page: 'current'}).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $(api.column(7).footer()).html('= '+number_format(total));
        }
    });

    $('#btn-filter').click(function () {
        if($("#form-filter #warehouse_id option:selected").val())
        {
            table.ajax.reload();
        }else{
            notification('error','Please select warehouse!');
        }
    });

    $('#btn-reset').click(function () {
        window.location.replace("{{ url('today-sales-report') }}");
    });

    $(document).on('change','#warehouse_id',function(){
        warehouse_name = $("#form-filter #warehouse_id option:selected").data('name');
    });
});
function getSalesmenList(warehouse_id){
    $.ajax({
        url:"{{ url('warehouse-wise-salesmen-list') }}/"+warehouse_id,
        type:"GET",
        dataType:"JSON",
        success:function(data){
            html = `<option value="">Select Please</option>`;
            $.each(data, function(key, value) {
                html += '<option value="'+ key +'">'+ value +'</option>';
            });
            $('#form-filter #salesmen_id').empty().append(html);
            $('.selectpicker').selectpicker('refresh');
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

function customer_list()
{
    let district_id = document.getElementById('district_id').value;
    let upazila_id = document.getElementById('upazila_id').value;
    let route_id = document.getElementById('route_id').value;
    let area_id = document.getElementById('area_id').value;
    $.ajax({
        url:"{{ url('customer-list') }}",
        type:"POST",
        data:{district_id:district_id,upazila_id:upazila_id,route_id:route_id,area_id:area_id,_token:_token},
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
</script>
@endpush