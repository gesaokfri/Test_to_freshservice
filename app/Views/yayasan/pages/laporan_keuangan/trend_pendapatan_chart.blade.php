<div id="chart-trend_pendapatan_investasi" class="apex-charts" dir="ltr"></div>
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
      @foreach ($tableTrendPendapatanInvestasi as $item)
        <tr>
          <td>{{ $item["name"] }}</td>
          @for ($i = 0; $i <= 4; $i++)
          <td class="text-end">
          @php
            if ($item["data"][$i] < 1 && $item["data"][$i] > 0) {
              echo round($item["data"][$i], 1);
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

<script>
  var options = {
    series: {!! $chartTrendPendapatanInvestasi !!},
    chart: {
        height: 320,
        type: 'line',
        toolbar: 'false',
        dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 8,
            opacity: 0.2
        },
    },
    dataLabels: {
        enabled: false
    },
    yaxis: {
      labels: {
        formatter: function(val) {
          if (val < 1 && val > 0) {
            return Math.round(val * 10) / 10;
          } else {
            return Math.round(val);
          }
        }
      }
    },
    colors: {!! $chartColor !!},
    xaxis: {
      categories: {!! $chartCategories !!},
    },
    stroke: {
      // curve: 'smooth',
      width: 3,
    },
    markers: {
      style: 'inverted',
      size: 6
    }
  };

  var chart = new ApexCharts(
      document.querySelector("#chart-trend_pendapatan_investasi"),
      options
  );

  chart.render();
</script>