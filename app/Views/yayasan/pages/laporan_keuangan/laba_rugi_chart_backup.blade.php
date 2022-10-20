<div id="chart_laba_rugi_pie" class="apex-charts" dir="ltr"></div>
<div class="d-flex bg-light p-3 rounded justify-content-center">
  <div class="">
    <h5 class="mb-0">{{ $resultLabel }}</h3>
    <span>Nilai {{ $resultLabel }} sebesar:</span>
  </div>
  <div class="d-flex flex-column ms-auto">
    <h5 class="fw-bold ms-auto text-end mb-0 text-{{ $resultClassLabel }}">{{ number_format(round($resultPercentage, 1)) }}%</h5>
    <span class="ms-auto">Rp {{ $resultSurDef/1000000 }} Juta</span>
  </div>
</div>
<div id="chart_laba_rugi_beban" class="apex-charts" dir="ltr" style="display:none;">
</div>
<div id="chart_laba_rugi_pendapatan" class="apex-charts" dir="ltr" style="display:none;" >
<script type="text/javascript">
    
    lap_keu_labarugi('{{$chart}}');

     // chartrend Lap Keu Laba Rugi
    function lap_keu_labarugi(chartType){
        var options = {
            series: [{!!$laba_rugi_pendapatan_total!!}, {!!$laba_rugi_beban_total!!}],
            chart: {
                height: 300,
                type: 'pie',
                events: {
                  dataPointSelection:
                      (event, chartContext, config) => {
                        var label = config.w.config.labels[config.dataPointIndex];
                        detailPieLabaRugi(label);
                      }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        var result =  val/1000000;
                        return "Rp "+result+ " Juta";
                    }
                }
            },
            colors: ['#00a54f','#ed1a3a'],
            labels: ['Pendapatan', 'Beban'],
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
                legend: {
                  position: 'bottom'
                }
              }
            }]
        };
        var chart = new ApexCharts(
            document.querySelector("#chart_laba_rugi_pie"),
            options
        );
        chart.render();

        // chartrender Lap Keu Laba Rugi Beban
        var options = {
               series: [{
                  name: '{{$yearmonth_before}}',  
                  data: [{!! $LabRgBebanDataBefore !!}]
                }, {
                  name: '{{$yearmonth_now}}',
                  data: [{!! $LabRgBebanDataNow !!}]
                }],
              chart: {
              type: 'bar',
              height: 350
            },
            plotOptions: {
              bar: {
                borderRadius: 4,
                horizontal: true,
              }
            },
            dataLabels: {
              enabled: false
            },
            xaxis: {
                categories: {!! $LabRgBebanCategories !!},
                labels: {
                    formatter: function (val) {
                        var result =  val/1000000;
                        return result;
                    }
                },
                title: {
                    text: 'Rupiah (Jutaan)',
                    style: {
                        fontWeight:  '500',
                    },
                }
            },
            colors: ['#00a54f','#faaf18'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        var result =  val/1000000;
                        return result+ " Juta";
                    }
                }
            }
        };
        var chart = new ApexCharts(
            document.querySelector("#chart_laba_rugi_beban"),
            options
        );
        chart.render();

        // chartrender Lap Keu Laba Rugi Pendapatan
        var options = {
               series: [{
                  name: '{{$yearmonth_before}}',  
                  data: [{!! $LabRgPendapatanDataBefore !!}]
                }, {
                  name: '{{$yearmonth_now}}',
                  data: [{!! $LabRgPendapatanDataNow !!}]
                }],
              chart: {
              type: 'bar',
              height: 350
            },
            plotOptions: {
              bar: {
                borderRadius: 4,
                horizontal: true,
              }
            },
            dataLabels: {
              enabled: false
            },
            xaxis: {
                categories: {!! $LabRgPendapatanCategories !!},
                labels: {
                    formatter: function (val) {
                        var result =  val/1000000;
                        return result;
                    }
                },
                title: {
                    text: 'Rupiah (Jutaan)',
                    style: {
                        fontWeight:  '500',
                    },
                }
            },
            colors: ['#00a54f','#faaf18'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        var result =  val/1000000;
                        return result+ " Juta";
                    }
                }
            }
        };
        var chart = new ApexCharts(
            document.querySelector("#chart_laba_rugi_pendapatan"),
            options
        );
        chart.render();
    }

     function backPieLabaRugi(){
        $("#chart_laba_rugi_pendapatan").hide();  
        $("#chart_laba_rugi_beban").hide();
        $("#remarkPieLabaRugi").hide();
        $("#chart_laba_rugi_pie").show();   
    }
    function detailPieLabaRugi(label){
        if(label=="Pendapatan"){
         $("#chart_laba_rugi_pendapatan").show();  
         $("#remarkPieLabaRugi").html("<i class='bx bx-arrow-back'></i> Grafik Laba Rugi Pendapatan {{$yearmonth_before}} - {{$yearmonth_now}}");
         $("#remarkPieLabaRugi").show();
         $("#chart_laba_rugi_pie").hide();   
         $("#chart_laba_rugi_beban").hide();   
        }
        else if(label=="Beban"){
         $("#chart_laba_rugi_beban").show();
         $("#remarkPieLabaRugi").html("<i class='bx bx-arrow-back'></i> Grafik Laba Rugi Beban {{$yearmonth_before}} - {{$yearmonth_now}}");
         $("#remarkPieLabaRugi").show();   
         $("#chart_laba_rugi_pie").hide();   
         $("#chart_laba_rugi_pendapatan").hide();   
        }
    }
</script>