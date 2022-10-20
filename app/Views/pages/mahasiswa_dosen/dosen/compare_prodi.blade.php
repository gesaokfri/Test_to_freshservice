<div class="row">
    <div class="col-lg-12 align-self-center">
        <div id="compare-dosen" class="apex-charts" dir="ltr"></div>
        <div class="d-flex flex-column mt-5">
          <table class="table table-hover table-sm">
            <thead class="table-light">
              <tr>
                <th>Nama Prodi</th>
                <th class="text-end">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($TableCompareJumlahDosen as $item)
              <tr>
                <td>{{ $item["nama_prodi"] }}</td>
                <td class="text-end">{{ $item["jumlah_dosen"][0] }}</td>
              </tr>
              @endforeach
              <tr>
                <td><h6>Total</h6></td>
                <td class="text-end"><b>{{ $TableTotalKeseluruhanProdi }}</b></td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>
<script>
  var options = {
    series: [{
      name: 'Jumlah Dosen',
      data: {!!$jumlahDosen!!}
    }],
    chart: {
        height: 350,
        type: 'area',
        toolbar: {
            show: false
        },
    },
    colors: {!! $ChartColor !!},
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'straight',
        width: 2,
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.45,
            opacityTo: 0.05,
            stops: [20, 100, 100, 100]
        },
    },
    xaxis: {
        categories: {!! $dataProdi !!},
    },
    markers: {
        size: 3,
        strokeWidth: 3,

        hover: {
            size: 4,
            sizeOffset: 2
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'right',
    },
  };

  var chart = new ApexCharts(document.querySelector("#compare-dosen"), options);
  chart.render();
</script>