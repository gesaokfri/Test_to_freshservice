<div id="chart_realisasi_pb_yaj" class="apex-charts" dir="ltr"></div>

<script type="text/javascript">
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
              series: {!! $chartPBYAJ !!},
              colors: ['#00a54f','#ed1a3a'],
              xaxis: {
                   categories: {!! $ListQuarter !!}
              },
              grid: {
                  borderColor: '#f1f1f1',
              },
              fill: {
                  opacity: 1
          
              }
          }
          
          var chart = new ApexCharts(
            document.querySelector("#chart_realisasi_pb_yaj"),
            options
          );
          
          chart.render();   
</script>