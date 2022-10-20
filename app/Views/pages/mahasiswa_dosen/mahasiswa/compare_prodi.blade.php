<div class="row">
    <div class="col-lg-12 align-self-center">
        <div id="compare-mahasiswa" class="apex-charts" dir="ltr"></div>
        <div class="d-flex flex-column mt-5">
          <table class="table table-hover table-sm">
            <thead class="table-light">
              <tr>
                <th>Nama Prodi</th>
                @foreach ($TableListTahun as $item)
                <th class="text-end">{{ $item }}</th>
                @endforeach
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($TableCompareJumlahMahasiswa as $item)
              <tr>
                <td>{{ $item["nama_prodi"] }}</td>
                @for ($i = 0; $i <= 4; $i++)
                <td class="text-end">{{ $item["jumlah_mahasiswa"][$i] }}</td>
                @endfor
                <td class="text-end">
                @php
                $totalPerProdi = 0;
                for ($i=0; $i <= 4; $i++) {
                  $totalPerProdi += $item["jumlah_mahasiswa"][$i];
                }
                @endphp
                <b>{{ $totalPerProdi }}</b>
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
                <td class="text-end"><b>{{ $TableTotalKeseluruhanProdi }}</b></td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>

<script>
  var options = {
    series: {!! $ChartCompareJumlahMahasiswa !!},
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
        categories: {!! $LastFiveYears !!},
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

  var chart = new ApexCharts(document.querySelector("#compare-mahasiswa"), options);
  chart.render();
</script>
