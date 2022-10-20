<div id="chart_asetTetapKonsolidasi" class="apex-charts" dir="ltr"></div>

<table class="table table-hover table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th rowspan="2">Keterangan</th>
      @foreach ($tableCategories as $item)
        <th colspan="2" class="text-center">{{ $item }}</th>
      @endforeach
    </tr>
    <tr class="text-center">
      @foreach ($tableCategories as $item)
        <th>APB</th>
        <th>Realisasi</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($tableAsetTetap as $item)
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
    <tr>
        <td><b>Jumlah</b></td>
        @php
          for ($i = 1; $i <= 5; $i++) {
            echo "<td class='text-center'>
            <b>" . number_format(round($tableTotalAPBPerCategories[$i])) . "</b>
            </td>";
            echo "<td class='text-center'>
            <b>" . number_format(round($tableTotalPerCategories[$i])) . "</b>
            </td>";
          }
        @endphp
    </tr>
    <tr>
      <td colspan="11">
        <span class="fst-italic text-muted">Pertumbuhan terhadap tahun sebelumnya</span>
      </td>
    </tr>
      <tr>
          <td>Increase (decrease) %</td>
          @php
            for ($i = 1; $i <= 5; $i++) {
              if (!empty($tableTotalAPBPerCategories[$i-1])) {
                if( $tableTotalAPBPerCategories[$i - 1] > 0 && $tableTotalAPBPerCategories[$i] > 0) {
                    $anu = (($tableTotalAPBPerCategories[$i] - $tableTotalAPBPerCategories[$i - 1]) / $tableTotalAPBPerCategories[$i - 1]) * 100;
                } else {
                    if ( $tableTotalAPBPerCategories[$i] >= 0) {
                        if ($tableTotalAPBPerCategories[$i - 1] > 0) {
                            $han = 100;
                        } else {
                            $han = -100;
                        }
                        $anu = (($tableTotalAPBPerCategories[$i]-$tableTotalAPBPerCategories[$i - 1])/$tableTotalAPBPerCategories[$i - 1])*($han);
                    } else {
                        $anu = (($tableTotalAPBPerCategories[$i]-$tableTotalAPBPerCategories[$i - 1])/$tableTotalAPBPerCategories[$i - 1])*100;
                        if($anu == 0) {
                            $anu = 0;
                        }
                    }
                }
                echo "<td class='text-center'>" . number_format(round($anu, 2)) . "%</td>";
              } else {
                echo "<td class='text-center'>0%</td>";
              }

              if (!empty($tableTotalPerCategories[$i-1])) {
                if( $tableTotalPerCategories[$i - 1] > 0 && $tableTotalPerCategories[$i] > 0) {
                    $anu = (($tableTotalPerCategories[$i] - $tableTotalPerCategories[$i - 1]) / $tableTotalPerCategories[$i - 1]) * 100;
                } else {
                    if ( $tableTotalPerCategories[$i] >= 0) {
                        if ($tableTotalPerCategories[$i - 1] > 0) {
                            $han = 100;
                        } else {
                            $han = -100;
                        }
                        $anu = (($tableTotalPerCategories[$i]-$tableTotalPerCategories[$i - 1])/$tableTotalPerCategories[$i - 1])*($han);
                    } else {
                        $anu = (($tableTotalPerCategories[$i]-$tableTotalPerCategories[$i - 1])/$tableTotalPerCategories[$i - 1])*100;
                        if($anu == 0) {
                            $anu = 0;
                        }
                    }
                }
                echo "<td class='text-center'>" . number_format(round($anu, 2)) . "%</td>";
              } else {
                echo "<td class='text-center'>0%</td>";
              }
            }
          @endphp
      </tr>
  </tbody>
</table>

<script>
  var options = {
    chart: {
      height: 300,
      type: 'bar',
      toolbar: {
        show: false,
      }
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
    series: {!!$chartAsetTetapKonsolidasi!!},
    colors: ['#34c38f', '#fcaf17', '#f46a6a'],
    xaxis: {
      categories: {!!$chartCategories!!}
    },
    grid: {
      borderColor: '#f1f1f1',
    },
    fill: {
      opacity: 1

    }
  }

  var chart = new ApexCharts(
    document.querySelector("#chart_asetTetapKonsolidasi"),
    options
  );

  chart.render();
</script>