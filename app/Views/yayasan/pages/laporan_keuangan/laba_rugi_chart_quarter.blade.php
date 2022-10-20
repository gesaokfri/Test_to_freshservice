<div id="chart_realisasi_kenaikan_pb" class="apex-charts" dir="ltr"></div>

<script type="text/javascript">
     var options = {
            series: {!! $chartKenaikanPB !!},
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
            colors: ['#00a54f','#ed1a3a'],
            yaxis: {
                  labels: {
                    enabled: true,
                    formatter: function (val) {
                           return val+"%";
                    }
                  }
            },
            xaxis: {
              categories: {!! $ListQuarter !!},
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                      return val+"%";
                    }
                }
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
            document.querySelector("#chart_realisasi_kenaikan_pb"),
            options
        );

        chart.render();
</script>