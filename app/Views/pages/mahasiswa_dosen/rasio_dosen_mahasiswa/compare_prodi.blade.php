<div class="col-lg-12">
  <div id="compare-rasio" class="apex-charts" dir="ltr"></div>
  {{-- @foreach ($dataCompareRasioDosenMahasiswa as $item)
    <div class="card p-3 pb-0" style="box-shadow: 0 2px 6px rgba(0, 0, 0, 0.111)">

      <div class="d-flex">
        <div class="d-flex flex-column">
          <h6 class="align-self-center">{{ $item["nama_prodi"] }}</h6>
        </div>
        <div class="ms-auto align-self-center">
            <span class="text-muted font-size-18">{{ $LastFiveYears[0] }} - {{ $LastFiveYears[4] }}</span>
        </div>
      </div>

      <div class="row mt-3">
        @foreach ($LastFiveYears as $item2)
          <div class="col">
            <div class="card p-2 border">
              <div class="d-flex">
                <span class="btn btn-light fw-bold">1 : {{ round($item["rasioMahasiswa"][$item2], 1) }}</span>
                <div class="d-flex flex-column ms-auto">
                  <span><small class="badge bg-light">Tahun</small> {{ $item2 }}</span>
                  <span class="text-end fw-bold">{{ $item["jumlah_dosen"][$item2] }} : {{ $item["jumlah_mahasiswa"][$item2] }}</span>
                </div>
              </div>
              <div class="position-relative my-3">
                <div style="height: 7px; background: rgb(253, 222, 84); border-radius: 50px; position: absolute; width: 100%"></div>
                <div style="height: 7px; background: rgb(100, 165, 255); border-radius: 50px; position: absolute; width: {{ $item["persenDosen"][$item2] }}%"></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  @endforeach --}}
</div>
<script>
  var options = {
    series: {!!$dataRasio!!},
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
    }
  };

  var chart = new ApexCharts(document.querySelector("#compare-rasio"), options);
  chart.render();
</script>