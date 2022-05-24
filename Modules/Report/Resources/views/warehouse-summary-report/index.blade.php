@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link href="css/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<style>
    #summary_data .card-label{
        font-size: 18px !important;
        font-weight: bolder;
        text-align: center;
    }
    #summary_data .card-body{
        text-align: center;
    }
    #summary_data .card.card-custom .card-header{
        justify-content: center !important;
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
                        <div class="form-group col-md-4">
                            <label for="name">Choose Your Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-filed">
                                <input type="hidden" id="start_date" name="start_date">
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                        </div>
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" col="col-md-4" class="selectpicker">
                            @if (!$warehouses->isEmpty())
                            @foreach ($warehouses as $id => $name)
                                <option value="{{ $id }}" data-name="{{ $name }}">{{ $name }}</option>
                            @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="col-md-4">
                            <div style="margin-top:28px;">     
                                    <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                    data-toggle="tooltip" data-theme="dark" title="Reset">
                                    <i class="fas fa-undo-alt"></i></button>
    
                                    <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                    data-toggle="tooltip" data-theme="dark" onclick="summary_data()" title="Search">
                                    <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="col-md-12" style="position: relative;">
                        <div class="row" id="summary_data">
                        
                        </div>
                        <div class="col-md-12 d-none" id="table-loader" style="position: absolute;top:80px;left:0;">
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
<script src="js/moment.js"></script>
<script src="js/knockout-3.4.2.js"></script>
<script src="js/daterangepicker.min.js"></script>
<script>
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

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('input[name="start_date"]').val('');
        $('input[name="end_date"]').val('');
    });
});

function summary_data()
{
    let warehouse_id = document.getElementById('warehouse_id').value;
    let start_date = document.getElementById('start_date').value;
    let end_date = document.getElementById('end_date').value;
    if (start_date && end_date) {
        if(warehouse_id)
        {
            $.ajax({
                url:"{{ route('warehouse.summary.data') }}",
                type:"POST",
                data:{warehouse_id:warehouse_id,start_date:start_date,end_date:end_date,_token:_token},
                beforeSend: function(){
                    $('#table-loader').removeClass('d-none');
                },
                complete: function(){
                    $('#table-loader').addClass('d-none');
                },
                success:function(data){
                    $('#summary_data').empty().html(data);
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }else{
            notification('error','Please select warehouse!');
        }
    } else {
        notification('error','Please choose date!');
    }
    

}

</script>
@endpush