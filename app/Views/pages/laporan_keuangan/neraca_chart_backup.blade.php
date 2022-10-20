<div id="chart_neraca" class="apex-charts" dir="ltr"></div>

<script type="text/javascript">
    
    lap_keu_neraca('{{$chart}}');
    // chartrender Lap Keu Neraca
    function lap_keu_neraca(chartType=''){
        var typeChart = "";
        if(chartType){
            typeChart = chartType;
        }
        else {
            typeChart = "bar";
        }

        if(typeChart=="bar"){
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
                        columnWidth: '25%',
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
                series: [{
                    name: 'Aset',
                    data: [{{$aset_total_before}},{{$aset_total_now}}]
                }, {
                    name: 'Kewajiban',
                    data: [{{$kewajiban_total_before}},{{$kewajiban_total_now}}]
                }, {
                    name: 'Modal',
                    data: [{{$modal_total_before}},{{$modal_total_now}}]
                }],
                colors: ['#00a54f','#faaf18','#ed1a3a'],
                xaxis: {
                    categories: ['{{$year_month_before}}','{{$year_month}}'],
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Jutaan)',
                        style: {
                            fontWeight:  '500',
                        },
                    },
                    labels: {
                        formatter: function (val) {
                            var result =  val/1000000;
                            return result;
                        }
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
                            var result =  val/1000000;
                            return result+ " Juta";
                        }
                    }
                }
            }
            var chart = new ApexCharts(
                document.querySelector("#chart_neraca"),
                options
            );
            chart.render();    
        }
        else if(typeChart=="pie"){
            
        }
    }
</script>