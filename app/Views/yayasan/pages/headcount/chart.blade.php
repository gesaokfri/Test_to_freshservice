<div id="chart-headcount" class="apex-charts" dir="ltr"></div>

<script>
    var options = {
        series: [{
            name: 'Wanita',
            data: {!! $jumlahWanita !!},
            color : '#fd5f5f'
        }, {
            name: 'Pria',
            data: {!! $jumlahPria !!},
            color: '#3ff167'
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: false,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "20%",
                endingShape: 'rounded'
            },
        },
        stroke: {
            width: 1,
            colors: ['#fff']
        },
        xaxis: {
            categories: {!! $tahun !!},
        },
        dataLabels: {
            enabled: false,
            style: {
                colors: ['#fff']
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            offsetX: 40
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-headcount"), options);
    chart.render();
</script>