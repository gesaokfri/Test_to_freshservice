<div id="chart_asetTetap" class="apex-charts" dir="ltr"></div>

<table class="table table-hover table-sm mt-3">
    <thead class="table-light">
      <tr>
          <th>Keterangan</th>
          @foreach ($tableCategories as $item)
            <th class="text-center">{{ $item }}</th>
          @endforeach
      </tr>
  </thead>
  <tbody>
      @foreach ($tableAsetTetap as $item)
        <tr>
        <td>{{ $item["name"] }}</td>
        @for ($i = 0; $i <= 4; $i++)
        <td class="text-end">{{ number_format(round($item["data"][$i]), 2) }}</td>
        @endfor
        </tr>
      @endforeach
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
        series: {!! $chartAsetTetap !!},
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
    document.querySelector("#chart_asetTetap"),
    options
    );
        
    chart.render();   
</script>