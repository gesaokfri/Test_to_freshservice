<div class="row">
  <div class="col-lg-12 align-self-center">
      <div id="filter-fakultas" class="apex-charts" dir="ltr"></div>
      <div class="d-flex flex-column mt-5">
        <table class="table table-hover table-sm">
          <thead class="table-light">
            <tr>
              <th>Nama Fakultas</th>
              <th>Jumlah Rasio</th>
              <th>Kapasitas Ideal</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($dataRasioTable as $item)
            <tr>
              <td>{{ $item['fakultas'] }}</td>
              <td>1 : {{ $item['rasio'] }}</td>
              <td>{{ $item['kapasitas'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
</div>

<script>
  // Init chart compare mahasiswa
  var options = {
      series: [{
        name: 'Rasio',
        data: {!! $dataRasio !!},
      }],
      chart: {
          height: 320,
          type: 'bar',
          toolbar: 'false',
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
      colors: {!! $dataColor !!},
      xaxis: {
        categories: {!! $dataFakultas !!},
      },
      stroke: {
          curve: 'smooth',
          width: 3,
      },
      tooltip: {
        y: {
          formatter: function (val) {
              return "1 : " + val;
          }
        }
      },
      yaxis: {
        labels: {
          formatter: function (value) {
            return "1 : " + value;
          }
        },
      },
      markers: {
        style: 'inverted',
        size: 6
      },
      legend: {
        show: false
      }
  };
  var chart = new ApexCharts(document.querySelector("#filter-fakultas"), options);
  chart.render();
</script>