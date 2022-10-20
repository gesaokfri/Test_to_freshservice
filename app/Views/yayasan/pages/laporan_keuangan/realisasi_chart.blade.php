<div id="chart-realisasi_apb" class="apex-charts" dir="ltr"></div>
<table class="table table-hover table-sm mt-3">
  <thead class="table-light">
      <tr>
        <th rowspan="2">Keterangan</th>
        @foreach ($tableListTahun as $item)
          <th colspan="2" class="text-center">{{ $item }}</th>
        @endforeach
      </tr>
      <tr class="text-center">
        @foreach ($tableListTahun as $item)
          <th>APB</th>
          <th>Realisasi</th>
        @endforeach
      </tr>
  </thead>
  <tbody>
        @foreach ($TableRealisasiAPB as $item)
        <tr>
          <td>{{ $item["name"] }}</td>
          @for ($i = 0; $i <= 4; $i++)
            <td class="text-center">{{ number_format(round($item["dataAPB"][$i])) }}</td>
            <td class="text-center">
            @php
              if ($item["data"][$i] < 1) {
                echo number_format(round($item["data"][$i], 2));
              } else {
                echo number_format(round($item["data"][$i]));
              }
            @endphp
            </td>
          @endfor
        </tr>
       @endforeach
  </tbody>
</table>
<script type="text/javascript">
  // Init  chart compare mahasiswa
  var options = {
      series: {!! $ChartRealisasiAPB !!},
      chart: {
          height: 320,
          type: 'line',
          toolbar: 'false',
      },
      plotOptions: {
        bar: {
          horizontal: false,
          endingShape: 'rounded',
          columnWidth: '35%',
        },
      },
      dataLabels: {
        enabled: false,
      },
      colors: {!! $dataColor !!},
      xaxis: {
        categories: {!! $LastFiveYears !!},
      },
      fill: {
        type: ['solid', 'solid', 'solid', 'gradient'],
        gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.45,
            opacityTo: 0.05,
            stops: [20, 100, 100, 100]
        },
      },
      stroke: {
        width: [0,0,3,3]
      },
      markers: {
        style: 'inverted',
        size: 6
      },
      yaxis: {
        axisTicks: {
          show: true,
        },
        axisBorder: {
          show: true
        },
        title: {
          text: "Dalam Miliar Rupiah",
        },
        labels: {
          formatter: function(val) {
            return Math.round(val);   
          }
        }
      }
  };
  var chart = new ApexCharts(document.querySelector("#chart-realisasi_apb"), options);
  chart.render();
      // var options = {
      //     series: {!! $ChartRealisasiAPB !!},
      //     chart: {
      //     height: 350,
      //     type: 'line',
      //     stacked: false
      //   },
      //   dataLabels: {
      //     enabled: false
      //   },
      //   stroke: {
      //     width: [3,3,3,3]
      //   },
      //   xaxis: {
      //     categories: {!! $LastFiveYears !!},
      //   },
      //   colors: ['#269ffb','#febb3b','#26e7a5','#ff6077'],
      //   yaxis: [
      //     {
      //       axisTicks: {
      //         show: true,
      //       },
      //       axisBorder: {
      //         show: true
      //       },
      //       title: {
      //         text: "Dalam Rupiah",
      //       },
      //       labels: {
      //         enabled: true,
      //         formatter: function (val) {
      //                result = val/1000000;
      //                return result;
      //         }
      //       }
      //     },
      //   ],
      //   tooltip: {
      //     fixed: {
      //       enabled: true,
      //       position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
      //       offsetY: 30,
      //       offsetX: 60
      //     },
      //     y: {
      //         formatter: function (val) {
      //           return val/1000000 + " Juta";
      //         }
      //     }
      //   }
      // };

      // var chart = new ApexCharts(document.querySelector("#chart_realisasi_apb"), options);
      // chart.render();
</script>