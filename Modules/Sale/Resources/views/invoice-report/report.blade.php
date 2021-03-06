<div class="row">
  
<style>
    body,html {
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
    }

    .invoice {
        /* position: relative; */
        background: #fff !important;
        /* min-height: 680px; */
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #036;
    }

    .invoice .company-details {
        text-align: right
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0;
    }

    .invoice .contacts {
        margin-bottom: 20px;
    }

    .invoice .invoice-to {
        text-align: left;
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0;
    }

    .invoice .invoice-details {
        text-align: right;
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #036;
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px;
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #036;
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }

    .invoice table th {
        background: #036;
        color: #fff;
        padding: 15px;
        border-bottom: 1px solid #fff
    }

    .invoice table td {
        padding: 15px;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        white-space: nowrap;
    }

    .invoice table td h3 {
        margin: 0;
        color: #036;
    }

    .invoice table .qty {
        text-align: center;
    }

    .invoice table .price,
    .invoice table .discount,
    .invoice table .tax,
    .invoice table .total {
        text-align: right;
    }

    .invoice table .no {
        color: #fff;
        background: #036
    }

    .invoice table .total {
        background: #036;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        border-top: 1px solid #aaa;
        font-weight: bold;
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    /* .invoice table tfoot tr:last-child td {
        color: #036;
        border-top: 1px solid #036
    } */

    .invoice table tfoot tr td:first-child {
        border: none
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }

    .invoice a {
        content: none !important;
        text-decoration: none !important;
        color: #036 !important;
    }

    .page-header,
    .page-header-space {
        height: 100px;
    }

    .page-footer,
    .page-footer-space {
        height: 20px;

    }

    .page-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }

    .page-header {
        position: fixed;
        top: 0mm;
        width: 100%;
        border-bottom: 1px solid black;
    }

    .page {
        page-break-after: always;
    }
    .dashed-border{
        width:180px;height:2px;margin:0 auto;padding:0;border-top:1px dashed #454d55 !important;
    }

    @media screen {
        .no_screen {display: none;}
        .no_print {display: block;}
        thead {display: table-header-group;} 
        tfoot {display: table-footer-group;}
        button {display: none;}
        body {margin: 0;}
    }

    @media print {

        body,
        html {
            /* background: #fff !important; */
            -webkit-print-color-adjust: exact !important;
            font-family: sans-serif;
            /* font-size: 12px !important; */
            margin-bottom: 100px !important;
        }

        .m-0 {
            margin: 0 !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0 !important;
        }

        .no_screen {
            display: block !important;
        }

        .no_print {
            display: none;
        }

        a {
            content: none !important;
            text-decoration: none !important;
            color: #036 !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .float-left {
            float: left !important;
        }

        .float-right {
            float: right !important;
        }

        .text-bold {
            font-weight: bold !important;
        }

        .invoice {
            /* font-size: 11px!important; */
            overflow: hidden !important;
            background: #fff !important;
            margin-bottom: 100px !important;
        }

        .invoice footer {
            position: absolute;
            bottom: 0;
            left: 0;
            /* page-break-after: always */
        }

        /* .invoice>div:last-child {
            page-break-before: always
        } */
        .hidden-print {
            display: none !important;
        }
        .dashed-border{
            width:180px;height:2px;margin:0 auto;padding:0;border-top:1px dashed #454d55 !important;
        }
    }

    @page {
        /* size: auto; */
        margin: 5mm 5mm;

    }
</style>
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4></h4>
                </div>
            </div>
            <div id="invoice">
                  <div class="invoice overflow-auto">
                  <table width="100%" style="margin:0;padding:0;">
                    <tr>
                        <td width="100%" class="text-center">
                            <h3 style="margin:0;">{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</h3>
                            @if(config('settings.contact_no'))<p style="font-weight: normal;margin:0;"><b>Contact: </b>{{ config('settings.contact_no') }}, @if(config('settings.email'))<b>Email: </b>{{ config('settings.email') }}@endif</p>@endif
                            @if(config('settings.address'))<p style="font-weight: normal;margin:0;">{{ config('settings.address') }}</p>@endif
                            <p style="font-weight: normal;margin:0;"><b>Date: </b>{{ $start_date.' to '.$end_date }}</p>
                        </td>
                    </tr>
                  </table>
                  <div style="width: 100%;height:3px;border-top:1px solid #036;border-bottom:1px solid #036;"></div>
                  @if(!$sales->isEmpty())
                    @foreach($sales as $sale)
                    <table>
                      <tr>
                          <td width="50%">
                              <div class="invoice-to">
                              <h4 class="name m-0">Memo No: #{{ $sale->memo_no }}</h4>
                                  <div class="to">{{ $sale->customer->shop_name }}</div>
                                  <div class="to">{{ $sale->customer->name }}</div>
                              </div>
                          </td>
                      </tr>
                  </table>
                  <table border="0" cellspacing="0" cellpadding="0">
                      <thead>
                          <tr>
                              <th class="text-center">SL</th>
                              <th class="text-left">DESCRIPTION</th>
                              <th class="text-center">UNIT</th>
                              <th class="text-center">QUANTITY</th>
                          </tr>
                      </thead>
                      <tbody>
                          @if (!$sale->sale_products->isEmpty())
                              @foreach ($sale->sale_products as $key => $item)
                                  @php
                                      $unit_name = '';
                                      if($item->pivot->sale_unit_id)
                                      {
                                          $unit_name = DB::table('units')->where('id',$item->pivot->sale_unit_id)->value('unit_name');
                                      }
                                  @endphp
                                  <tr>
                                      <td class="text-center no">{{ $key+1 }}</td>
                                      <td class="text-left">{{ $item->name }}</td>
                                      <td class="text-center">{{$unit_name }}</td>
                                      <td class="text-center qty">{{ $item->pivot->qty }}</td>
                                  </tr>
                              @endforeach
                          @endif
                      </tbody>
                      
                      <tfoot>
                          <tr>
                              <td  colspan="3" class="text-right">TOTAL</td>
                              <td class="text-center">{{ number_format($sale->total_qty) }}</td>
                          </tr>
                      </tfoot>
                  </table>
                    @endforeach
                    
                  <!-- <table border="0" cellspacing="0" cellpadding="0">
                    <tfoot>
                          <tr>
                              <td  colspan="4" class="text-right">TOTAL</td>
                              <td class="text-center">{{ number_format(4) }}</td>
                              <td class="text-center"></td>
                          </tr>
                      </tfoot>
                  </table> -->
                  @endif
                  

                </div>
            </div>
        </div>
    </div>
</div>
