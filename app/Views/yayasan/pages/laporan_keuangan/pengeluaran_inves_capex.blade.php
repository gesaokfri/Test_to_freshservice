<div id="chart_pengeluaranvescapes" class="apex-charts" dir="ltr"></div>
<table class="table table-hover table-sm mt-3">
    <thead class="table-light">
      <tr>
          <th>Keterangan</th>
          @foreach ($tableCategories as $item)
            <th class="text-end">{{ $item }}</th>
          @endforeach
      </tr>
  </thead>
  <tbody>
        @foreach ($tablePengeluaranInvesCapex as $item)
            <tr>
            <td>{{ $item["name"] }}</td>
            @for ($i = 0; $i <= 4; $i++)
            <td class="text-end">{{ number_format(round($item["data"][$i]), 2) }}</td>
            @endfor
            </tr>
        @endforeach
      <tr>
          <td>Jumlah</td>
          @php
            for ($i = 1; $i <= 5; $i++) {
              echo "<td class='text-end'>
              <b>" . number_format(round($tableTotalPerCategories[$i]), 2) . "</b>
              </td>";
            }
          @endphp
      </tr>
      <tr>
          <td colspan="6"><br></td>
      </tr>
      <tr>
          <td>Increase (decrease) %</td>
          @php
            for ($i = 1; $i <= 5; $i++) {
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
                    echo "<td class='text-end'>" . number_format(round($anu, 2)) . "%</td>";
                } else {
                    echo "<td class='text-end'>0%</td>";
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
                return Math.round(val);   
                }
            }
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: {!! $chartPengeluaranInvesCapex !!},
        colors: ['#34c38f', '#fcaf17', '#f46a6a'],
        xaxis: {
          categories: {!! $chartCategories !!}
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        fill: {
            opacity: 1
    
        }
    }
        
    var chart = new ApexCharts(
    document.querySelector("#chart_pengeluaranvescapes"),
    options
    );
        
    chart.render();   
</script>