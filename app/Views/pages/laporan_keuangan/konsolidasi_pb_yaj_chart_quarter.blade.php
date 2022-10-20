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
              stroke: {
                  show: true,
                  width: 2,
                  colors: ['transparent']
              },
              series: {!! $chartPBYAJ !!},
              colors: ['#00a54f','#ed1a3a'],
              xaxis: {
                   categories: {!! $ListQuarter !!},
                   labels: {
                        style: {
                            colors: '#000',
                            fontSize: '14px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            fontWeight: 500,
                            cssClass: 'apexcharts-yaxis-label',
                        },
                   }
              },
              yaxis: {
                  labels: {
                    enabled: true,
                    formatter: function (val) {
                           result = Math.round(val/1000000000);
                           return result;
                    },
                    style: {
                        colors: '#000',
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 500,
                        cssClass: 'apexcharts-yaxis-label',
                    },
                  }
              },
              grid: {
                  borderColor: '#f1f1f1',
              },
              fill: {
                  opacity: 1
          
              },
              tooltip: {
                  y: {
                      formatter: function (val) {
                        return Math.round(val/1000000000);
                      }
                  }
              }
          }
          
          var chart = new ApexCharts(
            document.querySelector("#chart_realisasi_pb_yaj"),
            options
          );
          
          chart.render();   
</script>