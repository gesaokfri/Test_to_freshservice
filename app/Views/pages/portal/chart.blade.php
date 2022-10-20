<div id="chart-headcount" class="apex-charts" dir="ltr"></div>
<style>
    #chart-headcount .apexcharts-text.apexcharts-xaxis-label tspan:last-of-type{
        fill: #fcaf17;
        font-weight: 600;
    }
</style>
<script>
    var options = {
        series: [{
            name: 'Wanita',
            data: {!! $wanita !!},
            color : '#ed1a3a'
        }, {
            name: 'Pria',
            data: {!! $pria !!},
            color: '#00a54f'
        }],
        chart: {
            type: 'line',
            height: 350,
            toolbar: false,
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 8,
                opacity: 0.2
            },
        },
        stroke: {
            width: 3,
        },
        markers: {
            style: 'inverted',
            size: 6
        },
        xaxis: {
            categories: {!! $xCategories !!},
            labels: {
                show: true,
                style: {
                    fontSize: '15px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 500,
                    cssClass: 'apexcharts-yaxis-label',
                }
            },
        },
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#fff']
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            offsetX: 40
        },
        yaxis: {
            title: {
                text: "Jumlah Karyawan",
                style: {
                    fontSize: '12px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 400,
                    cssClass: 'apexcharts-yaxis-label',
                }
            },
            labels: {
                show: true,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 500,
                    cssClass: 'apexcharts-yaxis-label',
                }
            },
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-headcount"), options);
    chart.render();
</script>