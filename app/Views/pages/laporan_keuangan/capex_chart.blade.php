<div id="chart_capex" class="apex-charts" dir="ltr"></div>
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
    @foreach ($TableCapex as $item)
        <tr>
        <td>{{ $item["name"] }}</td>
        @for ($i = 0; $i <= 4; $i++)
          <td class="text-end">
          @php
              if ($item["data"][$i] < 1 && $item["data"][$i] > 0) {
                  echo round($item["data"][$i], 1);
                } else {
                  echo number_format(round($item["data"][$i], 2));
              }
          @endphp
          </td>
        @endfor
        </tr>
    @endforeach
  <tr>
      <td>Jumlah</td>
      @php
          for ($i = 1; $i <= 5; $i++) {
            echo "<td class='text-end'>
            <b>" . number_format(round($TableCapexSum[$i])) . "</b>
            </td>";
          }
      @endphp
  </tr>
</table>
<script type="text/javascript">
    // chartrender Lap Keu  Capex
        var options = {
            series: {!! $CapexData !!},
            chart: {
              type: 'bar',
              height: 350,
              toolbar: false
            },
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded'
              },
            },
            dataLabels: {
              enabled: false
            },
            yaxis: {
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
            stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
            },
            xaxis: {
              categories: {!! $LastFiveYears !!},
            },
            colors: {!! $dataColor !!},
            fill: {
              opacity: 1
            },
        };

        var chart = new ApexCharts(
            document.querySelector("#chart_capex"),
            options
        );
        chart.render();
</script>