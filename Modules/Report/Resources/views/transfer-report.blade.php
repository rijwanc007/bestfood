@extends('layouts.app')

@section('title', $page_title)
@push('styles')
<link href="plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="css/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
<style>
    #dataTable{
        width: 2000px !important;
    }
    #dataTable ul li{
        border-bottom: 1px solid #EBEDF3;
        margin-bottom: 5px;
    }
    #dataTable ul li:last-child{
        border-bottom: 0;
        margin-bottom: 0;
    }

    #dataTable tbody tr td:nth-child(7),
    #dataTable tbody tr td:nth-child(8),
    #dataTable tbody tr td:nth-child(9),
    #dataTable tbody tr td:nth-child(10),
    #dataTable tbody tr td:nth-child(11),
    #dataTable tbody tr td:nth-child(12),
    #dataTable tbody tr td:nth-child(13),
    #dataTable tbody tr td:nth-child(14),
    #dataTable tbody tr td:nth-child(15){
        padding-left: 0px !important;
        padding-right: 0px !important;
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
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="Chalan No." name="chalan_no" col="col-md-4" />
                        <div class="form-group col-md-4">
                            <label for="name">Choose Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-filed">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" col="col-md-4" required="required" class="selectpicker">
                            @if (!$warehouses->isEmpty())
                            @foreach ($warehouses as $id => $name)
                                <option value="{{ $id }}" data-name="{{ $name }}">{{ $name }}</option>
                            @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.textbox labelName="Product Name" name="product_name" col="col-md-6" />
                        <input type="hidden" class="form-control" name="product_id" id="product_id">
                    
                        <div class="col-md-6">      
                                <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                data-toggle="tooltip" data-theme="dark" title="Reset">
                                <i class="fas fa-undo-alt"></i></button>

                                <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                data-toggle="tooltip" data-theme="dark" title="Search">
                                <i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover" style="width: 2000px !important;">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Warehosue Name</th>
                                        <th>Batch No.</th>
                                        <th>Chalan No.</th>
                                        <th>Date</th>
                                        <th>Item</th>
                                        <th>Product Description</th>
                                        <th>Unit</th>
                                        <th>Base Unit</th>
                                        <th>Qty Unit</th>
                                        <th>Qty Base Unit</th>
                                        <th>Unit Price</th>
                                        <th>Base Unit Price</th>
                                        <th>Tax</th>
                                        <th>Subtotal</th>
                                        <th>Total Tax</th>
                                        <th>Total</th>
                                        <th>Shipping Cost</th>
                                        <th>Labor Cost</th>
                                        <th>Grand Total</th>
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right !important;font-weight:bold;">Grand Total</th>
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
<script src="js/moment.js"></script>
<script src="js/knockout-3.4.2.js"></script>
<script src="js/daterangepicker.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script>
var table;
$(document).ready(function(){
    $('.daterangepicker-filed').daterangepicker({
        callback: function(startDate, endDate, period){
            var start_date = startDate.format('YYYY-MM-DD');
            var end_date   = endDate.format('YYYY-MM-DD');
            var title      = start_date + ' To ' + end_date;
            $(this).val(title);
            $('input[name="start_date"]').val(start_date);
            $('input[name="end_date"]').val(end_date);
        }
    });
    $('#product_name').autocomplete({
        source: function( request, response ) {
            // Fetch data
            $.ajax({
                url:"{{url('stock/product-search')}}",
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
            "url": "{{route('transfer.report.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.chalan_no    = $("#form-filter #chalan_no").val();
                data.start_date   = $("#form-filter #start_date").val();
                data.end_date     = $("#form-filter #end_date").val();
                data.product_id   = $("#form-filter #product_id").val();
                data.warehouse_id = $("#form-filter #warehouse_id").val();
                data._token       = _token;
            }
        },
        "columnDefs": [{
                "targets": [6,7,8,9,10,11,12,13,14],
                "orderable": false,
            },
            {
                "targets": [0,1,2,3,4,5,7,8,9,10],
                "className": "text-center"
            },
            {
                "targets": [11,12,13,14,15,16,17,18,19],
                "className": "text-right"
            }

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
                "title": "{{ $page_title }}",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
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
                "title": "{{ $page_title }}",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}",
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
                "title": "{{ $page_title }}",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}",
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
                "title": "{{ $page_title }}",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    } 
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                    doc.styles.tableHeader.fontSize = 7;
                    doc.styles.tableFooter.fontSize = 7;
                    doc.pageMargins = [5,5,5,5];
                },
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

            total = api.column(19).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api.column(19, { page: 'current'}).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column(19).footer() ).html('= '+number_format(total));
        }
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#product_name,#product_id,#start_date,#end_date,#warehouse_id').val('');
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });

});
</script>
@endpush