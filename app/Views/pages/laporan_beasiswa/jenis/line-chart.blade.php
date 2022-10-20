<div class="accordion" id="accordion1">
  <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Grafik Jenis Beasiswa
          </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
          <div class="accordion-body">

              {{-- carousel inside accordion --}}
              <div id="carouselExampleControls" class="carousel slide" data-bs-interval="false">
                  <div class="carousel-inner">
                      
                      {{-- first item -> chart jenis beasiswa --}}
                      <div class="carousel-item active">
                          <div class="d-flex">
                            <span class="text-muted fst-italic">Data jenis beasiswa {{ $tableBeaCode[0] }} {{ $tableListTahun[0] }} - {{ $tableListTahun[4] }}</span>
                          </div>
                          <div id="chart-jenis1" class="apex-charts" dir="ltr"></div>

                          <table class="table table-bordered table-hover table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>Beasiswa Code</th>
                                    <th>Nama Beasiswa</th>
                                    <th>Jumlah Dana Diterima</th>
                                    <th>Jumlah Dana Dikeluarkan</th>
                                    <th>Jumlah Dana Administration</th>
                                    <th>Periode/Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tableBeaCode as $key => $item)
                                    <tr>
                                        <td>{{ $item }}</td>
                                        <td>{{ beacode_to_progname($item, "program_name") }}</td>
                                        @php
                                        $total = 0;
                                        for ($i = 0; $i <= 4; $i++) {
                                          $total += $tableJenisBeasiswa[0]["data"][$i];
                                        }
                                        @endphp
                                        <td>
                                        @php
                                        if ($total > 0 && $total < 1) {
                                            echo round($total, 1);
                                        } else {
                                            echo number_format(round($total));
                                        }
                                        @endphp
                                        </td>
                                        @php
                                        $total = 0;
                                        for ($i = 0; $i <= 4; $i++) {
                                          $total += $tableJenisBeasiswa[1]["data"][$i];
                                        }
                                        @endphp
                                        <td>
                                        @php
                                        if ($total > 0 && $total < 1) {
                                            echo round($total, 1);
                                        } else {
                                            echo number_format(round($total));
                                        }
                                        @endphp
                                        </td>
                                        @php
                                        $total = 0;
                                        for ($i = 0; $i <= 4; $i++) {
                                          $total += $tableJenisBeasiswa[2]["data"][$i];
                                        }
                                        @endphp
                                        <td>
                                        @php
                                        if ($total > 0 && $total < 1) {
                                            echo round($total, 1);
                                        } else {
                                            echo number_format(round($total));
                                        }
                                        @endphp
                                        </td>
                                        <td>{{ $tableListTahun[0] }} - {{ $tableListTahun[4] }}</td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                      </div>

                  </div>
              </div>
              
          </div>
      </div>
  </div>
</div>

<script>
    // chartrender jenis-1
    var options = {
        series: {!! $chartJenisBeasiswa !!},
        chart: {
            height: 320,
            type: 'line',
            toolbar: 'false',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 8,
                opacity: 0.2
            },
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#00a54f','#faaf18','#ed1a3a'],
        xaxis: {
          categories: {!! $chartCategories !!},
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    if (val > 0 && val < 1) {
                        return Math.round(val * 10) / 10;
                    } else {
                        return Math.round(val);
                    }
                }
            }
        },
        stroke: {
        //   curve: 'smooth',
            width: 3,
        },
        markers: {
          style: 'inverted',
          size: 6
        }
    };

    var chart = new ApexCharts(
        document.querySelector("#chart-jenis1"),
        options
    );

    chart.render();
</script>