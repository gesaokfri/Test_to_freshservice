<div class="row">
    <div class="col-lg-12 align-self-center">
        <div id="compare-dosen" class="apex-charts" dir="ltr"></div>
        <div class="d-flex flex-column mt-5">
          <table class="table table-hover table-sm">
            <thead class="table-light">
              <tr>
                <th>Nama Fakultas</th>
                <th class="text-end">Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($TableCompareJumlahDosen as $item)
              <tr>
                <td>{{ $item["nama_fakultas"] }}</td>
                <td class="text-end">{{ $item["jumlah_dosen"][0] }}</td>
              </tr>
              @endforeach
              <tr>
                <td><h6>Total</h6></td>
                <td class="text-end"><b>{{ $TableTotalKeseluruhan }}</b></td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>

<script>
  // Init chart compare mahasiswa
  var options = {
      series: [{
        name: 'Jumlah Dosen',
        data: {!! $jumlahDosen !!},
      }],
      chart: {
          height: 320,
          type: 'bar',
          toolbar: 'false',
          // dropShadow: {
          //     enabled: true,
          //     color: '#000',
          //     top: 18,
          //     left: 7,
          //     blur: 8,
          //     opacity: 0.2
          // },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '19%',
          endingShape: 'rounded',
          distributed: true,
        },
      },
      dataLabels: {
          enabled: false
      },
      colors: {!! $ChartColor !!},
      xaxis: {
        categories: {!! $dataFakultas !!},
      },
      stroke: {
          curve: 'straight',
          width: 3,
      },
      markers: {
        style: 'inverted',
        size: 6
      },
      legend: {
        show: false
      }
  };
  var chart = new ApexCharts(document.querySelector("#compare-dosen"), options);
  chart.render();
</script>