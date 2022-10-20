<div id="chart-cashflow_detail"></div>
<!--<table class="table table-hover table-bordered table-sm mt-3">
  <thead class="table-light">
      <tr>
          <th>Keterangan</th>
          @foreach ($tableProdi as $item)
            <th class="text-end">{{ $item }}</th>
          @endforeach
      </tr>
  </thead>
  <tbody>
        <tr>
            <td><b>PENDAPATAN</b></td>
            @foreach ($tableProdi as $item)
            <td></td>
            @endforeach
        </tr>
        @php 
            $i=0; 
            $arrPendapatan = [];
            $arrBeban = [];
        @endphp
        @foreach ($TableCashflow as $item)
                <tr>
                    <td>{{ $item["cashflow_name"] }}</td>
                    @foreach ($TableCashflowValue[$item["cashflow_name"]]  as $val)
                    @php 
                        $nilai = (empty($val['cashflow_value']) ? 0 : $val['cashflow_value']);
                        if($i==0 || $i==1){ //Pendapatan
                            if(!array_key_exists($val['remark'],$arrPendapatan)){
                                $Newdata  =  array ($nilai);
                                $arrPendapatan[$val['remark']] = $Newdata;
                            }
                            else {
                                $getVal = @$arrPendapatan[$val['remark']][0];
                                if(!empty($getVal)){
                                    $total    = $getVal + (int)$nilai;
                                    $Newdata  =  array ($total);
                                    $arrPendapatan[$val['remark']] = $Newdata;
                                }
                            }
                        }
                        else{  //Beban
                            if(!array_key_exists($val['remark'],$arrBeban)){
                                $Newdata  =  array ($nilai);
                                $arrBeban[$val['remark']] = $Newdata;
                            }
                            else {
                                $getVal = @$arrBeban[$val['remark']][0];
                                if(!empty($getVal)){
                                    $total    = $getVal + (int)$nilai;
                                    $Newdata  =  array ($total);
                                    $arrBeban[$val['remark']] = $Newdata;
                                }
                            }
                        }
                        $cshValue = $nilai/1000000000; 
                    @endphp
                     <td class="text-end">{{number_format(round($cshValue,2))}}</td>
                    @endforeach
                </tr>
                @if($i==1)
                    <tr>
                        <td><b>Jumlah Pendapatan</b></td>
                        @foreach ($TableCashflowValue[$item["cashflow_name"]]  as $val)
                            @php
                             $totalPendapatan = $arrPendapatan[$val['remark']][0]/1000000000; 
                            @endphp
                            <td class="text-end">{{number_format(round($totalPendapatan,2))}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><b>BEBAN</b></td>
                        @foreach ($TableCashflowValue[$item["cashflow_name"]]  as $val)
                            <td></td>
                        @endforeach
                    </tr>
                @endif

              

            @php $i++; @endphp

              @if($i==count($TableCashflow))
                    <tr>
                        <td><b>Jumlah Beban</b></td>
                        @foreach ($TableCashflowValue[$item["cashflow_name"]]  as $val)
                            @php
                             $totalBeban = $arrBeban[$val['remark']][0]/1000000000; 
                            @endphp
                            <td class="text-end">{{number_format(round($totalBeban,2))}}</td>
                        @endforeach
                    </tr>
                @endif
       @endforeach
  </tbody>
</table>-->
<script type="text/javascript">
$("#profit_title").html("Profit Tahun {{$year}} (Dalam miliar rupiah)");
var options = {
    series: [
        {
            name: 'Loss',
            data: {!! $data_net_loss !!},
            color: "#e43131"
        },
        {
            name: 'Profit',
            data: {!! $data_net_profit !!},
            color: "#00e363"
        }
    ],
    tooltip: {
        shared: false,
        y: {
              formatter: function (val) {
                var result =  Math.round(Math.abs(val)/1000000000 * 100)/100;
                if (result < 1 && result> 0) {
                    return  result;
                }
                else {
                    return  Math.round(result);
                }
              }
           }
    },
    chart: {
        height: 350,
        type: 'bar',
        stacked: true,
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        // style: {
        //     colors: ['#000000'],
        //     fontFamily: 'Poppins'
        // },
        // formatter: function (val) {
        //    var result =  Math.round(Math.abs(val)/1000000000 * 100)/100;
        //    //return Math.round(result);
        //    return result;
        // }
        enabled:false
    },
    plotOptions: {
        bar: {
            horizontal: true,
            columnWidth: '35%',
            endingShape: 'rounded'
        }
    },
    xaxis: {
        categories: {!! $categories !!},
        labels: {
            style: {
                colors: '#000',
                fontSize: '14px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 500,
                cssClass: 'apexcharts-yaxis-label',
            },
            formatter: function (val) {
               var result =  Math.round(Math.abs(val)/1000000000 * 100)/100;
               //return Math.round(result);
                return result;
            }
        }
    },
    yaxis: {
    }
}

var chart = new ApexCharts(document.querySelector("#chart-cashflow_detail"), options);
chart.render();

function backProfit(){
    $("#div_cashflow_detail").hide();   
    $("#chart-cashflow").show();
    $("#btn_back_profit").hide();  
    $("#btn_filter_profit").show();
    FilterCashflow('{{$year}}'); 
}
</script>