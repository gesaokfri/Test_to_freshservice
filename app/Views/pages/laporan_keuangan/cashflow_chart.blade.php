<div id="chart-cashflow"></div>
<div id="div_cashflow_detail" style="display:none;"></div>

<script type="text/javascript">
$("#profit_title").html("Profit Tahun {{$year}} (Dalam miliar rupiah)");
var options = {
    series: [
        {
            name: 'Loss',
            data: {!! $data_net_loss !!},
            id:   {!! $data_id !!},
            color: "#e43131"
        },
        {
            name: 'Profit',
            data: {!! $data_net_profit !!},
            id:   {!! $data_id !!},
            color: "#00e363"
        }
    ],
    chart: {
        height: 350,
        type: 'bar',
        stacked: true,
        toolbar: {
            show: false
        },
        events: {
          dataPointSelection:
              (event, chartContext, config) => {
                var i = config.dataPointIndex;
                console.log(i);

                var id = config.w.config.series[1].id[i];
                console.log(id);
                detailCashflow(id,'{{$year}}');
              }
        }
    },
    tooltip: {
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
        labels: {
          style: {
            colors: '#000',
            fontSize: '12px',
            fontFamily: 'Helvetica, Arial, sans-serif',
            fontWeight: 500,
          }
        }
    }
}

var chart = new ApexCharts(document.querySelector("#chart-cashflow"), options);
chart.render();
</script>