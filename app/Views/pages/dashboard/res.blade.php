@extends('layout.index')
@section('title', 'Dashboard')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Dashboard UNIKA</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                {{-- content --}}
                <div class="portal-statistic row m-auto">
                  <div class="col-12 px-0">
                    <div class="card overflow-hidden p-3">
                      <div class="row">
                        <div class="col-12">
                          <div class="card border p-2 mb-0" style="height: unset">
                            <span class="font-size-20">Data per {{ get_date_indonesia(date('Y-m-d',strtotime("-1 months", strtotime(date("Y-m-d")))),$jns='month') }} {{ date('Y') }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-lg-6">
                          <div class="cashflow-item card border p-3">
                            <div class="row my-auto">
                              <div class="col-lg-6">
                                <div class="d-flex">
                                  @if (empty($surplus))
                                    {{-- jangan tampilkan arrow --}}
                                  @else
                                    {{-- tampilkan arrow --}}
                                    @if ($surplus > 0)
                                      <img src="{{ base_url('assets/images/design/portal') }}/arrow.svg" alt="" class="bank arrow-up">
                                      @else
                                      <img src="{{ base_url('assets/images/design/portal') }}/arrow.svg" alt="" class="bank arrow-down">                                        
                                    @endif
                                  @endif
                                  <div class="d-flex flex-column align-self-center">
                                    @if (empty($surplus))
                                      <span class="fw-bold font-size-17">Surplus</span>
                                    @else
                                      {{-- tampilkan arrow --}}
                                      @if ($surplus > 0)
                                      <span class="fw-bold font-size-17">Surplus</span>
                                      @elseif ($surplus < 0)
                                      <span class="fw-bold font-size-17">Defisit</span>
                                      @endif
                                    @endif
                                    <span>Pertumbuhan 
                                      {{ get_date_indonesia(date('Y-m-d',strtotime("-1 months", strtotime(date("Y-m-d")))),$jns='month') }}
                                      terhadap
                                      {{ get_date_indonesia(date('Y-m-d',strtotime("-2 months", strtotime(date("Y-m-d")))),$jns='month') }} 
                                      {{ date('Y') }}
                                    </span>
                                    <span class="cashflow-value">{{ $surplus }}%</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <div class="d-flex">
                                  @if (empty($kasDanBank))
                                    {{-- jangan tampilkan arrow --}}
                                  @else
                                    {{-- tampilkan arrow --}}
                                    @if ($kasDanBank > 0)
                                      <img src="{{ base_url('assets/images/design/portal') }}/arrow.svg" alt="" class="bank arrow-up">
                                      @else
                                      <img src="{{ base_url('assets/images/design/portal') }}/arrow.svg" alt="" class="bank arrow-down">                                        
                                    @endif
                                  @endif
                                  <div class="d-flex flex-column align-self-center">
                                    <span class="fw-bold font-size-17">Kas Setara Kas</span>
                                    <span>Pertumbuhan
                                      {{ get_date_indonesia(date('Y-m-d',strtotime("-1 months", strtotime(date("Y-m-d")))),$jns='month') }}
                                      terhadap
                                      {{ get_date_indonesia(date('Y-m-d',strtotime("-2 months", strtotime(date("Y-m-d")))),$jns='month') }} 
                                      {{ date('Y') }}
                                    </span>
                                    <span class="cashflow-value">{{ $kasDanBank }}%</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="card border">
                            <div class="d-flex gap-3 justify-content-center my-auto">
                              <span class="align-self-center font-size-18">Current Ratio</span>
                              <div class="d-flex flex-column align-self-center">
                                <div class="bg-light px-3 py-1 rounded">
                                  <h1 class="fw-bold text-center mb-0">{{$ratio}}</h1>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="card border p-0">
                            <div class="d-flex justify-content-around my-auto">
                              <div class="align-self-center p-3">
                                <img src="{{ base_url('assets/images/design/portal') }}/employee.svg" alt="" class="img-fluid" width="50">
                              </div>
                              <div class="d-flex flex-column align-self-center">
                                <span class="text-center">Jumlah karyawan UNIKA Bulan {{ bulan_indo($bulan) }} Tahun {{ $tahun }}</span>
                                <h1 class="fw-bold text-center mb-0 mt-3">{{$karyawan['Jumlah']}}</h1>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <div class="col-lg-7">
                          <div class="card border d-flex">
                            <div class="card-body">
                              <div class="d-flex flex-column">
                                <h5 class="page-title">Piutang Mahasiswa</h5>
                                <span class="fst-italic">Data tahun {{date('Y')}} dalam miliar</span>
                              </div>
                              <div id="column_chart" class="apex-charts my-auto" dir="ltr"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-5">
                          <div class="card border p-0 mb-0">
                            <div class="card-body">

                              <div class="row">
                                <h5 class="page-title">Pendapatan dan Beban</h5>
                                <span class="fst-italic">Data bulan {{bulan_indo(date('m', strtotime("-1 Months")))}} tahun {{date('Y')}} dalam miliar</span>
                                <div class="col-lg-7 align-self-center">
                                  <div id="aktivitas_chart" class="apex-charts mt-4" dir="ltr"></div>
                                </div>
                                <div class="col-lg-5 align-self-center">
                                  <div class="bg-light d-flex flex-column gap-3 p-3 mt-4 rounded">
                                    <div class="d-flex flex-column">
                                      <span>Pendapatan</span>
                                      <div class="d-flex gap-2">
                                        <i class="mdi mdi-circle" style="color: #25dc62"></i>
                                        <span><b>{{ $pendapatanBeban['pendapatan'] }}</b></span>
                                        <small>{{ $pendapatanBeban['persenPendapatan'] }} %</small>
                                      </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                      <span>Beban</span>
                                      <div class="d-flex gap-2">
                                        <i class="mdi mdi-circle" style="color: #ed1a3b"></i>
                                        <span><b>{{ $pendapatanBeban['beban'] }}</b></span>
                                        <small>{{ $pendapatanBeban['persenBeban'] }} %</small>
                                      </div>
                                    </div>
                                    <hr class="my-3"/>
                                    <div class="d-flex flex-column">
                                      @if (empty($pendapatanBeban['surplus']))
                                        <span>Surplus</span>
                                      @else
                                        {{-- tampilkan arrow --}}
                                        @if ($pendapatanBeban['surplus'] > 0)
                                        <span>Surplus</span>
                                        @elseif ($pendapatanBeban['surplus'] < 0)
                                        <span>Defisit</span>
                                        @endif
                                      @endif
                                      <div class="d-flex gap-2">
                                        <i class="mdi mdi-circle" style="color: #b4b4b4"></i>
                                        <span>
                                          <b>{{ $pendapatanBeban['surplus'] }}</b>
                                        </span>
                                        <small>
                                          <b>{{ $pendapatanBeban['persenSurplus'] }} %</b>
                                        </small>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
      var options = {
        chart: {
            height: 300,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '28%',
                endingShape: 'rounded',
                distributed: true
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [{
            name: 'Jumlah Nominal',
            data: [
              {!!$piutang['past']!!},
              {!!$piutang['present']!!}
            ]
            }
        ],
        colors: ['#00a79d', '#f15a22'],
        xaxis: {
                categories: [
                  {!!$piutang['monthPass']!!},
                  {!!$piutang['monthPresent']!!}
                ]
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        fill: {
            opacity: 1

        },
        legend: {
          show: false
        },
      }

      var chart = new ApexCharts(
        document.querySelector("#column_chart"),
        options
      );

      chart.render();

      var options = {
        chart: {
            height: 220,
            type: 'pie',
        }, 
        stroke: {
            show: false
        },
        series: [
          {!! $pendapatanBeban['chartPendapatan'] !!},
          {!! $pendapatanBeban['chartBeban'] !!}
        ],
        labels: ["Pendapatan", "Beban"],
        colors: ["#25dc62","#ed1a3b"],
        legend: {
            show: false,
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    height: 140
                },
                legend: {
                    show: false
                },
            }
        }]

      }

      var chart = new ApexCharts(
        document.querySelector("#aktivitas_chart"),
        options
      );

      chart.render();
    </script>
@endsection