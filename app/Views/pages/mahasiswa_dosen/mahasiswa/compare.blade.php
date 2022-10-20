<div class="row justify-content-center">
  <div class="col-12">
    <div id="compare-mahasiswa" class="apex-charts" dir="ltr"></div>
  </div>
  <div class="col-lg-12 align-self-center">
      <div class="d-flex flex-column mt-5">
        <table class="table table-hover table-sm">
          <thead class="table-light">
            <tr>
              <th>Nama Fakultas</th>
              @foreach ($TableListTahun as $item)
              <th class="text-end">{{ $item }}</th>
              @endforeach
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($TableCompareJumlahMahasiswa as $item)
            <tr>
              <td>{{ $item["nama_fakultas"] }}</td>
              @for ($i = 0; $i <= 4; $i++)
              <td class="text-end">{{ $item["jumlah_mahasiswa"][$i] }}</td>
              @endfor
              <td class="text-end">
              @php
              $totalPerFakultas = 0;
              for ($i=0; $i <= 4; $i++) {
                $totalPerFakultas += $item["jumlah_mahasiswa"][$i];
              }
              @endphp
              <b>{{ $totalPerFakultas }}</b>
              </td>
            </tr>
            @endforeach
            <tr>
              <td><h6>Total</h6></td>
              @foreach ($TableTotalPerTahun as $item)
              <td class="text-end">
              <b>{{ $item }}</b>
              </td>
              @endforeach
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
      series: {!! $ChartCompareJumlahMahasiswa !!},
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
          endingShape: 'rounded',
        },
      },
      dataLabels: {
          enabled: false
      },
      colors: {!! $ChartColor !!},
      xaxis: {
        categories: {!! $LastFiveYears !!},
      },
      stroke: {
          width: 3,
      },
      markers: {
        style: 'inverted',
        size: 6
      }
  };
  var chart = new ApexCharts(document.querySelector("#compare-mahasiswa"), options);
  chart.render();
</script>