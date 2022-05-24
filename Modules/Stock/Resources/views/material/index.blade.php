@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
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
                    <div class="row justify-content-center">
                        <x-form.textbox labelName="Material Name" name="material_name" col="col-md-3" />
                        <input type="hidden" class="form-control bg-brand" name="material_id" id="material_id">
                        <x-form.selectbox labelName="Category" name="category_id" col="col-md-3" class="selectpicker">
                            <option value="0" selected>All Category</option>
                            @if (!$categories->isEmpty())
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" col="col-md-3" required="required" class="selectpicker">
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
                        <div class="col-sm-12" id="material_list">

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
@endsection

@push('scripts')
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.printarea.js"></script>
<script>
$(document).ready(function(){
    $('#material_name').autocomplete({
        source: function( request, response ) {
            // Fetch data
            $.ajax({
                url:"{{url('stock/material-search')}}",
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
                $('#material_name').val(ui.content[0].value);
                $('#material_id').val(ui.content[0].id);
                $(this).autocomplete( "close" );
            };
        },
        select: function (event, ui) {
            $('#material_name').val(ui.item.value);
            $('#material_id').val(ui.item.id);
            // var data = ui.item.value;
        },
    }).data('ui-autocomplete')._renderItem = function (ul, item) {
        return $("<li class='ui-autocomplete-row'></li>")
            .data("item.autocomplete", item)
            .append(item.label)
            .appendTo(ul);
    };
    
    $('#material_name').on('keyup',function(){
        if($(this).val() == ''){ $('#material_id').val(''); }
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#material_id').val('');
        $('#form-filter .selectpicker').selectpicker('refresh');
        $('#material_list').html('');
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
    if(warehouse_id)
    {
        $.ajax({
            url: "{{ route('material.stock.report.datatable.data') }}",
            type: "POST",
            data: {material_id:$('#material_id').val(),category_id:$('#category_id option:selected').val(),warehouse_id:warehouse_id,_token:_token},
            beforeSend: function(){
                $('#table-loader').removeClass('d-none');
            },
            complete: function(){
                $('#table-loader').addClass('d-none');
            },
            success: function (data) {
                $('#material_list').html('');
                $('#material_list').html(data);
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }else{
        notification('error','Please select warehouse');
    }
}
</script>
@endpush