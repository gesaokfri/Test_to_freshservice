<div class="accordion" id="accordion1">
  <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-sumberdana" aria-expanded="true" aria-controls="accordion-sumberdana">
          Grafik Sumber Dana Beasiswa (Penerimaan)
          </button>
      </h2>
      <div id="accordion-sumberdana" class="accordion-collapse collapse show" aria-labelledby="headingOne">
          <div class="accordion-body">

              {{-- carousel inside accordion --}}
              <div id="carousel-sumberdana" class="carousel slide" data-bs-interval="false">
                  <div class="carousel-inner">
                      
                      {{-- first item -> chart sumberdana beasiswa --}}
                      <div class="carousel-item active">
                          <div class="d-flex">
                              <span class="text-muted fst-italic">Data sumber dana beasiswa {{ $tableListTahun[0] }} - {{ $tableListTahun[4] }}</span>
                          </div>
                          <div id="chart-sumberdana1" class="apex-charts" dir="ltr"></div>

                          <table class="table table-bordered table-hover table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>Nama Donatur</th>
                                    @foreach ($tableListTahun as $item)
                                    <th>{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tableSumberDana as $item)
                                    <tr>
                                        <td>{{ $item["name"] }}</td>
                                        @for ($i = 0; $i <= 4; $i++)
                                        <td>
                                        @php
                                            if ($item["data"][$i] > 0 && $item["data"][$i] < 1) {
                                                echo round($item["data"][$i], 1);
                                            } else {
                                                echo number_format(round($item["data"][$i]));
                                            }
                                        @endphp
                                        </td>
                                        @endfor
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
    // chartrender sumberdana-1
    var options = {
        series: {!! $chartSumberDana !!},
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
        colors: {!! $chartColor !!},
        xaxis: {
          categories: {!! $chartCategories !!},
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    if (val >= 1) {
                        return Math.round(val);
                    } else {
                        return Math.round(val * 10) / 10;
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
        document.querySelector("#chart-sumberdana1"),
        options
    );

    chart.render();
</script>