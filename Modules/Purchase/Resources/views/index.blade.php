

@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="css/daterangepicker.min.css" rel="stylesheet" type="text/css" />
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
                <div class="card-toolbar">
                    <!--begin::Button-->
                    @if (permission('purchase-add'))
                    <a href="{{ route('purchase.create') }}"  class="btn btn-primary btn-sm font-weight-bolder"> 
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
                    <div class="row">
                        <x-form.textbox labelName="Memo No." name="memo_no" col="col-md-4" />
                       
                        <div class="form-group col-md-4">
                            <label for="name">Choose Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-filed">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <x-form.selectbox labelName="Supplier" name="supplier_id" col="col-md-4" class="selectpicker">
                            @if (!$suppliers->isEmpty())
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Purchase Status" name="purchase_status" col="col-md-4" class="selectpicker">
                            @foreach (PURCHASE_STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Payment Status" name="payment_status" col="col-md-4" class="selectpicker">
                            @foreach (PAYMENT_STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>

                        <div class="col-md-4">
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
                                        @if (permission('purchase-bulk-delete'))
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        @endif
                                        <th>Sl</th>
                                        <th>Memo No.</th>
                                        <th>Supplier Name</th>
                                        <th>Total Item</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Labor Cost</th>
                                        <th>Tax Rate(%)</th>
                                        <th>Order Tax</th>
                                        <th>Shippping Cost</th>
                                        <th>Grand Total</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Purchase Date</th>
                                        <th>Purchase Status</th>
                                        <th>Payment Status</th>
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
@include('purchase::payment.add')

<!-- Start :: Payment List Modal -->
<div class="modal fade" id="payment_view_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <!-- Modal Content -->
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close text-white"></i>
                </button>
            </div>
            <!-- /modal header -->
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="payment-list">
                            <thead class="bg-primary">
                                <th class="text-center">Date</th>
                                <th class="text-right">Paid Amount</th>
                                <th class="text-center">Payment Method</th>
                                <th>Account</th>
                                <th>Ref. No</th>
                                <th>Note</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
            <!-- /modal footer -->
        </div>
        <!-- /modal content -->
    </div>
</div>
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
                var title      = start_date + ' To ' + end_date;
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
                "url": "{{route('purchase.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.memo_no       = $("#form-filter #memo_no").val();
                    data.supplier_id     = $("#form-filter #supplier_id option:selected").val();
                    data.from_date       = $("#form-filter #start_date").val();
                    data.to_date         = $("#form-filter #end_date").val();
                    data.purchase_status = $("#form-filter #purchase_status option:selected").val();
                    data.payment_status  = $("#form-filter #payment_status option:selected").val();
                    data._token          = _token;
                }
            },
            "columnDefs": [{
                    @if (permission('purchase-bulk-delete'))
                    "targets": [0,17],
                    @else
                    "targets": [16],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    @if (permission('purchase-bulk-delete'))
                    "targets": [1,2,4,14,15,16],
                    @else
                    "targets": [0,1,3,13,14,15],
                    @endif
                    "className": "text-center"
                },
                {
                    @if (permission('purchase-bulk-delete'))
                    "targets": [5,6,7,8,9,10,11,12,13],
                    @else
                    "targets": [4,5,6,7,8,9,10,11,12],
                    @endif
                    "className": "text-right"
                },

            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
    
            "buttons": [
                @if (permission('purchase-report'))
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
                        @if (permission('purchase-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(17))' 
                        @else
                        columns: ':visible:not(:eq(16))' 
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
                        @if (permission('purchase-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(17))' 
                        @else
                        columns: ':visible:not(:eq(16))' 
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
                        @if (permission('purchase-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(17))' 
                        @else
                        columns: ':visible:not(:eq(16))' 
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
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        @if (permission('purchase-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(17))' 
                        @else
                        columns: ':visible:not(:eq(16))' 
                        @endif
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                        doc.styles.tableHeader.fontSize = 7;
                        doc.pageMargins = [5,5,5,5];
                    }  
                },
                @endif
                @if (permission('purchase-bulk-delete'))
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
            $('input[name="start_date"]').val('');
            $('input[name="end_date"]').val('');
            table.ajax.reload();
        });
    
        $(document).on('click', '.delete_data', function () {
            let id    = $(this).data('id');
            let name  = $(this).data('name');
            let row   = table.row($(this).parent('tr'));
            let url   = "{{ route('purchase.delete') }}";
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
                let url = "{{route('purchase.bulk.delete')}}";
                bulk_delete(ids,url,table,rows);
            }
        }

        //Payment Add Modal Show
        $(document).on('click', '.add_payment', function () {
            let id = $(this).data('id');
            let due = $(this).data('due');
            if(id && due)
            {
                $('#payment_form')[0].reset();
                $('#payment_form').find('.is-invalid').removeClass('is-invalid');
                $('#payment_form').find('.error').remove();
                $('.cheque_number').addClass('d-none');
                $('.selectpicker').selectpicker('refresh');
                if (id) {
                    $('#payment_modal #payment_id').val('');
                    $('#payment_modal #purchase_id').val(id);
                    $('#payment_modal #amount,#payment_modal #due_amount').val(due);
                    $('#payment_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#payment_modal .modal-title').html(
                        '<i class="fas fa-dollar-sign"></i> <span>Add Payment</span>');
                }
            }
        });
        //Payment Add Modal Show
        $(document).on('click', '.edit-payment', function () {
            let id = $(this).data('id');
            let purchase_id = $(this).data('purchaseid');
            let amount = $(this).data('amount');
            let due = $(this).data('due');
            let payment_method = $(this).data('paymentmethod');
            let account_id = $(this).data('accountid');
            let cheque_no = $(this).data('chequeno');
            let payment_note = $(this).data('note');
            if(id)
            {
                $('#payment_form')[0].reset();
                $('#payment_form').find('.is-invalid').removeClass('is-invalid');
                $('#payment_form').find('.error').remove();
                $('.cheque_number').addClass('d-none');
                $('.selectpicker').selectpicker('refresh');
                if (id) {
                    $('#payment_modal #payment_id').val(id);
                    $('#payment_modal #purchase_id').val(purchase_id);
                    $('#payment_modal #amount').val(amount);
                    $('#payment_modal #due_amount').val(due);
                    $('#payment_modal #payment_method').val(payment_method);
                    $('#payment_modal #account_id').val(account_id);
                    $('#payment_modal #payment_note').val(payment_note);
                    account_list(payment_method,account_id);
                    if(payment_method == 2){
                        $('.cheque_number').removeClass('d-none');
                        $('#cheque_number').val(cheque_no);
                    }else{
                        $('.cheque_number').addClass('d-none');
                        $('#cheque_number').val('');
                    }
                    $('.selectpicker').selectpicker('refresh');
                    $('#payment_view_modal').modal('hide');
                    $('#payment_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#payment_modal .modal-title').html(
                        '<i class="fas fa-edit"></i> <span>Edit Payment</span>');
                }
            }
        });

        $(document).on('click','#payment-save-btn', function(e){
            e.preventDefault();
            let id = $('#payment_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            let form = document.getElementById('payment_form');
            let formData = new FormData(form);
            $.ajax({
                url: "{{route('purchase.payment.store.or.update')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#payment-save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function(){
                    $('#payment-save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function (data) {
                    $('#payment_form').find('.is-invalid').removeClass('is-invalid');
                    $('#payment_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#payment_form input#' + key).addClass('is-invalid');
                            $('#payment_form textarea#' + key).addClass('is-invalid');
                            $('#payment_form select#' + key).parent().addClass('is-invalid');
                            $('#payment_form #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                                                        
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            if (method == 'update') {
                                table.ajax.reload(null, false);
                            } else {
                                table.ajax.reload();
                            }
                            $('#payment_modal').modal('hide');
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
            
        });

        //View Payment List
        $(document).on('click', '.view_payment_list', function () {
            let id = $(this).data('id');
            if (id) {
                payment_list(id);
                $('#payment_view_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#payment_view_modal .modal-title').html(
                    '<i class="fas fa-file-invoice-dollar"></i> <span>Payment List</span>');
            }
        });

        //Delete Payment
        $(document).on('click', '.delete-payment', function () {
            let id    = $(this).data('id');
            let purchase_id    = $(this).data('purchaseid');
            Swal.fire({
                title: 'Are you sure to delete data?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('purchase.payment.delete') }}",
                        type: "POST",
                        data: { id: id, _token: _token},
                        dataType: "JSON",
                    }).done(function (response) {
                        if (response.status == "success") {
                            Swal.fire("Deleted", response.message, "success").then(function () {
                                payment_list(purchase_id);
                                table.ajax.reload(null, false);
                            });
                        }
                        if (response.status == "error") {
                            Swal.fire('Oops...', response.message, "error");
                        }
                    }).fail(function () {
                        Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                    });
                }
            });
        });

        $(document).on('change', '#payment_method', function () {
            if($('#payment_method option:selected').val() == 2)
            {
                $('.cheque_number').removeClass('d-none');
            }else{
                $('.cheque_number').addClass('d-none');
            }
            account_list($('#payment_method option:selected').val());
        });
    });
    function account_list(payment_method,account_id='')
    {
        $.ajax({
                url: "{{route('account.list')}}",
                type: "POST",
                data: { payment_method: payment_method,_token: _token},
                success: function (data) {
                    $('#payment_form #account_id').html('');
                    $('#payment_form #account_id').html(data);
                    $('#payment_form #account_id.selectpicker').selectpicker('refresh');
                    if(account_id)
                    {
                        $('#payment_form #account_id').val(account_id);
                        $('#payment_form #account_id.selectpicker').selectpicker('refresh');
                    }
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
    }
    function payment_list(id)
    {
        $.ajax({
            url: "{{route('purchase.payment.show')}}",
            type: "POST",
            data: { id: id,_token: _token},
            success: function (data) {

                $('#payment_view_modal #payment-list tbody').html();
                $('#payment_view_modal #payment-list tbody').html(data);
                
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }
    </script>
@endpush