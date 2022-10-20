<div class="row">

  <div class="col-lg-4 align-self-center">
    <div id="total_dosen_chart" class="apex-charts" dir="ltr"></div>
    <div class="d-flex">
      <span class="fst-italic mx-auto mt-3 mb-0">Grafik dosen prodi</span>
    </div>
  </div>

  <div class="col-lg-8 mt-3 align-self-center">
    <div class="row">

      @foreach ($dataCardDosen as $item)
      <div class="col-12 col-sm-4 mt-3">
        <div class="card hoverable p-3 pb-0 h-100" style="background: #f3f3f37a; cursor: default;">
          <h6>{{ $item['nama_prodi'] }}</h6>
          <div class="d-flex mt-2 gap-3">
              <div class="apex-donut-customlegend align-self-center" style="background-color: {{ $item['color'] }}"></div>
              <h5 class="align-self-center ms-2 mb-0 fw-bold">{{ int_to_rp($item['jumlah_dosen']) }}</h5>
              <div class="bg-light p-1 rounded" style="background: #e1e1e1 !important">
                  <span class="align-self-end ms-1" style="margin-top: 2px">{{number_format(intval($item['jumlah_dosen'])/intval($totalJumlahDosen)*100, 2, '.', ',') }}%</span>
                </div>
          </div>
        </div>
      </div>
      @endforeach
      <div class="col-4 mt-3">
        <div class="card hoverable p-3 pb-0 h-100" style="background: #ffbc902d; cursor: default;">
            <h6>Total</h6>
            <div class="d-flex mt-2 gap-3">
                <div class="apex-donut-customlegend align-self-center" style="background-color: #fcaf17"></div>
              <h5 class="align-self-center ms-2 mb-0 fw-bold">{{ int_to_rp($totalJumlahDosen) }}</h5>
              <div class="bg-primary bg-soft rounded p-1">
                    <span class="align-self-end ms-1" style="margin-top: 2px">100%</span>
                </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<script>
  // Total Dosen chart
  var options = {
    chart: {
      height: 230,
      type  : 'pie',
    }, 
    plotOptions: {
      pie: {
        donut: {
          size  : '75%',
          labels: {
            show: true,
            name: {
              show: false,
            },
            value: {
              show: true,
            },
          }
        }
      }
    },
    stroke: {
      show  : true,
      width : 1,
      colors: ['#fff']
    },
    series: {!! $jumlahDosen !!},
    labels: {!! $namaProdi !!},
    colors: {!! $color !!},
    legend: {
      show: false,
    },
    responsive: [{
      breakpoint: 600,
      options   : {
        chart: {
          height: 240
        },
        legend: {
          show: false
        },
      }
    }],
    dataLabels: {
      enabled: true,
    },
  }

  var chart = new ApexCharts(
    document.querySelector("#total_dosen_chart"),
    options
  );

  chart.render();
</script>