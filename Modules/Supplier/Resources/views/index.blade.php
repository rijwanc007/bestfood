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
                <div class="card-toolbar">
                    <!--begin::Button-->
                    @if (permission('supplier-add'))
                    <a href="javascript:void(0);" onclick="showNewFormModal('Add New Supplier','Save')" class="btn btn-primary btn-sm font-weight-bolder"> 
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
                        <x-form.textbox labelName="Name" name="name" col="col-md-3" />
                        <x-form.textbox labelName="Mobile" name="mobile" col="col-md-3" />
                        <x-form.textbox labelName="Email" name="email" col="col-md-3" />
                        <x-form.selectbox labelName="Status" name="status" col="col-md-3" class="selectpicker">
                            @foreach (STATUS as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <div class="col-md-12"> 
                            <div style="margin-top:0px;">    
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
                                        @if (permission('supplier-bulk-delete'))
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                                <label class="custom-control-label" for="select_all"></label>
                                            </div>
                                        </th>
                                        @endif
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th>Zipcode</th>
                                        <th>Status</th>
                                        <th>Balance</th>
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
@include('supplier::modal')
@endsection

@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script>
    var table;
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
                "url": "{{route('supplier.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.name   = $("#form-filter #name").val();
                    data.mobile = $("#form-filter #mobile").val();
                    data.email  = $("#form-filter #email").val();
                    data.status = $("#form-filter #status").val();
                    data._token = _token;
                }
            },
            "columnDefs": [{

                    @if (permission('supplier-bulk-delete'))
                    "targets": [0,10],
                    @else
                    "targets": [9],
                    @endif
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    @if (permission('supplier-bulk-delete'))
                    "targets": [1,4,6,7,8],
                    @else
                    "targets": [0,3,5,6,7],
                    @endif
                    "className": "text-center"
                },
                {
                    @if (permission('supplier-bulk-delete'))
                    "targets": [9],
                    @else
                    "targets": [8],
                    @endif
                    "className": "text-right",
                    "orderable": false,
                },
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
    
            "buttons": [
                @if (permission('supplier-report'))
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
                        @if (permission('supplier-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(10))' 
                        @else
                        columns: ':visible:not(:eq(9))' 
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
                       @if (permission('supplier-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(10))' 
                        @else
                        columns: ':visible:not(:eq(9))' 
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
                       @if (permission('supplier-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(10))' 
                        @else
                        columns: ':visible:not(:eq(9))' 
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
                       @if (permission('supplier-bulk-delete'))
                        columns: ':visible:not(:eq(0),:eq(10))' 
                        @else
                        columns: ':visible:not(:eq(9))' 
                        @endif
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
                        doc.styles.tableHeader.fontSize = 7;
                        doc.pageMargins = [5,5,5,5];
                    }  
                },
                @endif 
                @if (permission('supplier-bulk-delete'))
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
            table.ajax.reload();
        });
    
        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            let url = "{{route('supplier.store.or.update')}}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            store_or_update_data(table, method, url, formData);
        });
    
        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (id) {
                $.ajax({
                    url: "{{route('supplier.edit')}}",
                    type: "POST",
                    data: { id: id,_token: _token},
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status == 'error'){
                            notification(data.status,data.message)
                        }else{
                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #name,#store_or_update_form #old_name').val(data.name);
                            $('#store_or_update_form #company_name').val(data.company_name);
                            $('#store_or_update_form #mobile').val(data.mobile);
                            $('#store_or_update_form #email').val(data.email);
                            $('#store_or_update_form #city').val(data.city);
                            $('#store_or_update_form #zipcode').val(data.zipcode);
                            $('#store_or_update_form #address').val(data.address);
                            $('#store_or_update_form #details').val(data.details);
                            $('#store_or_update_form .pbalance').addClass('d-none');
                            // $('#store_or_update_form #previous_balance, #store_or_update_form #old_previous_balance').val(data.previous_balance.debit);
                            $('#store_or_update_form #type.selectpicker').selectpicker('refresh');
                            $('#store_or_update_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit text-white"></i> <span>Edit ' + data.name + '</span>');
                            $('#store_or_update_modal #save-btn').text('Update');
                        }
                        
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        });
    
        $(document).on('click', '.delete_data', function () {
            let id    = $(this).data('id');
            let name  = $(this).data('name');
            let row   = table.row($(this).parent('tr'));
            let url   = "{{ route('supplier.delete') }}";
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
                let url = "{{route('supplier.bulk.delete')}}";
                bulk_delete(ids,url,table,rows);
            }
        }

        $(document).on('click', '.change_status', function () {
            let id     = $(this).data('id');
            let name   = $(this).data('name');
            let status = $(this).data('status');
            let row    = table.row($(this).parent('tr'));
            let url    = "{{ route('supplier.change.status') }}";
            change_status(id, url, table, row, name, status);
        });
    
    
    });
    function showNewFormModal(modal_title, btn_text) {
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form #update_id').val('');
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        $('#store_or_update_form .selectpicker').selectpicker('refresh');
        $('#store_or_update_form .pbalance').removeClass('d-none');

        $('#store_or_update_modal').modal({
            keyboard: false,
            backdrop: 'static',
        });
        $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square text-white"></i> '+modal_title);
        $('#store_or_update_modal #save-btn').text(btn_text);
    }
    </script>
@endpush
