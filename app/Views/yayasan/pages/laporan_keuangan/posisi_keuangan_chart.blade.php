<div id="chart_posisi_keuangan" class="apex-charts" dir="ltr"></div>
<table class="table table-hover table-sm mt-3">
  <thead class="table-light">
      <tr>
          <th>Keterangan</th>
          @foreach ($tableListTahun as $item)
            <th class="text-end">{{ $item }}</th>
          @endforeach
      </tr>
  </thead>
  <tbody>
        @foreach ($tablePosisiKeuangan as $item)
        <tr>
          <td>{{ $item["name"] }}</td>
          @for ($i = 0; $i <= 4; $i++)
            <td class="text-end">
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
  var options = {
          series: {!! $chartPosisiKeuangan !!},
          chart: {
            type: 'bar',
            height: 350,
            stacked: false,
            toolbar: 'false',
          },
          plotOptions: {
            bar: {
              horizontal: false,
              endingShape: 'rounded',
              columnWidth: "40%"
            },
          },
          dataLabels: {
            enabled: false,
            style: {
              colors: ['#fff']
            }
          },
          colors: ['#269ffb','#ff6077','#26e7a5','#febb3b'],
          xaxis: {
            categories: {!! $LastFiveYears !!},
          },
          stroke: {
            width: 1,
            colors: ['#fff']
          },
          yaxis: {
            title: {
              text: "Dalam Miliar Rupiah",
            },
            labels: {
              formatter: function(val) {
                if (val >= 1) {
                  return Math.round(val);
                } else {
                  return Math.round(val * 10) / 10;
                }
              }
            }
          },
          fill: {
            opacity: 1
          },
          legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40
          }
        };

        var chart = new ApexCharts(document.querySelector("#chart_posisi_keuangan"), options);
        chart.render();
</script>