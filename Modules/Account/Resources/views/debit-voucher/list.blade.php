@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link href="css/daterangepicker.min.css" rel="stylesheet" type="text/css" />
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
                    @if (permission('debit-voucher-add'))
                    <a href="{{ route('debit.voucher.create') }}"  class="btn btn-primary btn-sm font-weight-bolder"> 
                        <i class="fas fa-plus-circle"></i> Add New</a>
                        @endif
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row justify-content-center">
                        <div class="form-group col-md-3">
                            <label for="name">Choose Your Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-filed" value="">
                                <input type="hidden" id="start_date" name="start_date" value="">
                                <input type="hidden" id="end_date" name="end_date" value="">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="voucher_no">Voucher No</label>
                            <input type="text" class="form-control" name="voucher_no" id="voucher_no"/>
                        </div>

                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" col="col-md-3" class="selectpicker">
                            @if (!$warehouses->isEmpty())
                            @foreach ($warehouses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                            @endif
                        </x-form.selectbox>

                        <div class="col-md-3">
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
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Sl</th>
                                            <th>Warehouse</th>
                                            <th>Voucher No.</th>
                                            <th>Date</th>
                                            <th>Remark</th>
                                            <th>Debit</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
@include('account::debit-voucher.view-modal')
@endsection

@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="js/moment.js"></script>
<script src="js/knockout-3.4.2.js"></script>
<script src="js/daterangepicker.min.js"></script>
<script>
var table;
$(document).ready(function(){
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
            "url": "{{route('debit.voucher.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.start_date   = $("#form-filter #start_date").val();
                data.end_date     = $("#form-filter #end_date").val();
                data.voucher_no     = $("#form-filter #voucher_no").val();
                data.warehouse_id     = $("#form-filter #warehouse_id").val();
                data._token            = _token;
            }
        },
        "columnDefs": [
            {
                "targets": [8],
                "orderable": false,
                "className": "text-center"
            },
            {
                "targets": [0,1,2,3,7],
                "className": "text-center"
            },
            {
                "targets": [5],
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
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: ':visible:not(:eq(8))' 
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
                    ccolumns: ':visible:not(:eq(8))'  
                }
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "{{ $page_title }} List",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                "exportOptions": {
                    ccolumns: ':visible:not(:eq(8))'  
                }
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "{{ $page_title }} List",
                "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    ccolumns: ':visible:not(:eq(8))'  
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                    doc.styles.tableHeader.fontSize = 7;
                    doc.pageMargins = [5,5,5,5];
                }  
            },
        ],
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#form-filter #start_date').val("");
        $('#form-filter #end_date').val("");
        $('#form-filter #voucher_no').val("");
        $('#form-filter #warehouse_id').val("");
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });

    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('voucher.approval.delete') }}";
        delete_data(id, url, table, row, name);
    });

    $(document).on('click', '.view_data', function () {
        let id = $(this).data('id');
        let name  = $(this).data('name');
        if (id) {
            $.ajax({
                url: "{{route('debit.voucher.view')}}",
                type: "POST",
                data: { id: id,_token: _token},
                success: function (data) {
                    $('#view_modal #view-data').html('');
                    $('#view_modal #view-data').html(data);
                    $('#view_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#view_modal .modal-title').html(
                            '<i class="fas fa-eye text-white"></i> <span> ' + name + ' Details</span>');
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });

});
</script>
@endpush