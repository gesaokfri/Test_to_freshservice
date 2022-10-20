<div class="accordion" id="accordion1">
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Grafik Pengabdian
        </button>
    </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion1">
            <div class="accordion-body">
                <div class="d-flex">
                    <span class="text-muted fst-italic">Data pengabdian.</span>
                </div>
                <div id="pengabdian_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // chartrender PENGABDIAN
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
                columnWidth: '70%',
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
        series: {!! $chartJumlahPengabdian !!},
        colors: {!! $ChartColor !!},
        xaxis: {
            categories: {!! $LastFiveYears !!},
        },
        yaxis: {
            title: {
                text: 'Jumlah Pengabdian',
                style: {
                    fontWeight:  '500',
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
                    return val + " Pengabdian"
                }
            }
        }
    }
    
    var chart = new ApexCharts(
        document.querySelector("#pengabdian_chart"),
        options
    );
    
    chart.render();
</script>