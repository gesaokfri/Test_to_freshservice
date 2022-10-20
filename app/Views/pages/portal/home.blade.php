@extends('pages.portal.index')
@section('title', 'Portal')

@section('content')
<!-- START: Content (Application List Card) -->
<div class="main-content ms-0" id="portalContent">
  <div class="container-fluid p-0">

    <!-- hero section start -->
    <section class="section hero-section bg-ico-hero" id="home" style="background: rgb(0, 0, 0)">
      <div class="bg-overlay" style="background: url('{{base_url('')}}/assets/images/design/portal/atma.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      opacity: 0.5;
      "></div>
      <div class="container-fluid" style="padding-right: 6rem!important;">
          <div class="row align-items-center">
              <div class="col-lg-12">
                  <div class="text-white-50 text-end">
                    <h1 class="text-white font-weight-bold hero-title">AtmaSIAP</h1>
                    <span class="text-white mb-3 font-size-24 text-end">
                      <blockquote class="fst-italic">
                        Pada awal mula ada satu tekad <br>
                        Dan tekad itu menjiwai seluruh umat <br>
                        Dan tekad itu adalah "Untuk Tuhan dan Tanah Air" <br>
                      </blockquote>
                    </span>
                    <span class="text-white font-size-17">- Frans Seda</span>
                  </div>
              </div>
              <div class="button-items mt-4 d-flex">
                <a href="#app" class="btn btn-lg btn-primary ms-auto">Daftar Aplikasi</a>
            </div>
          </div>
          <!-- end row -->
      </div>
      <!-- end container -->
    </section>
    <!-- hero section end -->
    
    <!-- Visi misi start -->
    <section class="section bg-white" id="visimisi">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1>Visi Misi Atma Jaya</h1>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row align-items-center pt-4">
                <div class="col-md-12 ms-auto">
                    <div class="mt-4 mt-md-auto">
                        <div class="d-flex align-items-center mb-2">
                            <div class="features-number font-weight-semibold display-4 me-3" style="opacity: .5">01</div>
                            <h1 class="mb-0">Visi</h1>
                        </div>
                        <p class="font-size-22">Meningkatkan martabat kemanusiaan melalui lembaga pendidikan, penelitian, pelatihan, dan layanan kesehatan yang Kristiani, Unggul, Profesional, dan Peduli</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row align-items-center mt-2 pt-md-2">
                <div class="col-md-12">
                    <div class="mt-4 mt-md-0">
                        <div class="d-flex align-items-center mb-2">
                            <div class="features-number font-weight-semibold display-4 me-3" style="opacity: .5">02</div>
                            <h1 class="mb-0">Misi</h1>
                        </div>
                        <div class="mt-4 font-size-22">
                            <div class="d-flex">
                              <i class="mdi mdi-circle-medium text-success me-1"></i>
                              <p class="mb-2">
                                Membangun komunitas Atma Jaya yang semakin kuat dalam iman, persaudaraan sejati, dan budaya kasih
                              </div>
                            </p>
                            <div class="d-flex">
                              <i class="mdi mdi-circle-medium text-success me-1"></i>
                              <p class="mb-2">
                                Mengembangkan komunitas Atma Jaya sebagai kader bangsa yang unggul, berwatak, kritis, serta mempunyai semangat belajar seumur hidup
                              </div>
                            </p>
                            <div class="d-flex">
                              <i class="mdi mdi-circle-medium text-success me-1"></i>
                              <p class="mb-2">
                                Melaksanakan kepemimpinan dan manajemen secara transparan, akuntabel, responsibel, mandiri, dan adil
                              </div>
                            </p>
                            <div class="d-flex">
                              <i class="mdi mdi-circle-medium text-success me-1"></i>
                              <p class="mb-2">
                                Meningkatkan perwujudan martabat manusia serta kesejahteraan sosial dengan mengembangkan sikap peduli, solider, plural, dan lebih banyak berpihak pada yang lemah
                              </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- Visi misi end -->
    
    <!-- KUPP start -->
    <section class="section bg-white" id="kupp">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1>KUPP</h1>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row align-items-center pt-4">
                <div class="col-md-3">
                    <div class="mt-4 mt-md-auto">
                        <div class="card border">
                          <div class="card-body">
                            <h4><b>K</b>ristiani</h4>
                            <span class="font-size-18">
                              Iman Katolik merupakan landasan utama dari keseluruhan proses.
                            </span>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mt-4 mt-md-auto">
                        <div class="card border">
                          <div class="card-body">
                            <h4><b>U</b>nggul</h4>
                            <span class="font-size-18">
                              Dorongan untuk terus menerus menjadi yang terbaik di bidang keilmuannya, yang akan menjamin setiap individu bisa mengabdikan diri bagi kepentingan masyarakat.
                            </span>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mt-4 mt-md-auto">
                        <div class="card border">
                          <div class="card-body">
                            <h4><b>P</b>rofesional</h4>
                            <span class="font-size-18">
                              Merupakan praktik atau pendekataan dalam penyelesaian tugas, dengan mengedepankan prinsip-prinsip tata Kelola yang handal, sehingga menjamin hasil yang berkualitas.
                            </span>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mt-4 mt-md-auto">
                        <div class="card border">
                          <div class="card-body">
                            <h4><b>P</b>eduli</h4>
                            <span class="font-size-18">
                              Merupakan orientasi pokok dari perwujudan nilai-nilai Kristiani yang dilengkapi dengan kompetensi yang unggul dan profesional.
                            </span>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>
        <!-- end container -->
    </section>
    <!-- KUPP end -->
    
    <!-- Headcount start -->
    <section class="section bg-white" id="headcount">
        <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="text-center mb-5">
                      <h1>Headcount</h1>
                  </div>
              </div>
          </div>
          <div class="card border">
            <div class="card-body">
              <div class="d-flex">
                <span class="font-size-18 fst-italic">Data Karyawan Tetap per Tahun</span>
                <button class="btn btn-outline-primary ms-auto" data-bs-target="#filter-headcount" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
              </div>
              <!-- end row -->
              <div class="row align-items-center pt-4">
                <div class="col-12">
                  <div id="chart-container"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end container -->
    </section>
    <!-- Headcount end -->

    {{-- Konsolidasi start --}}
    <section class="bg-white pb-4" id="konsolidasi">
      <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <h1>Laporan Keuangan - Konsolidasi</h1>
                </div>
            </div>
        </div>
        <div class="card border">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="d-flex">
                    <div class="ms-auto">
                        <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-pb-konsolidasi" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Pendapatan Beban Konsolidasi</span><br/>
                        <span class="text-muted">Dalam miliar rupiah</span>
                        <div id="chart-pb-konsolidasi"></div>
                    </div>
                    <div class="col-md-6" id="div_realisasi_Kenaikan_PB">
                        <span>Kenaikan Pendapatan vs Beban</span><br/>
                        <span class="text-muted">Dalam persen</span>
                        <div id="chart-kenaikan-pb-konsolidasi"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div id="table-pb-konsolidasi"></div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    {{-- Konsolidasi end --}}

    <section class="bg-white" id="app">
      <div class="page-content pt-4">
        <div class="item-menu row mt-3 px-3 justify-content-center">
          <div class="col-10">
            <div class="card-body">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="text-center mb-5">
                          <h2>Dashboard</h2>
                      </div>
                  </div>
              </div>
              <div class="bg-primary bg-soft rounded p-3">
                <div class="row m-auto">
                  
  
                  {{-- Item --}}
                  <div class="col-6 col-md">
                    <div class="card h-100 mb-0">
                      <div class="card-header bg-white">
                        <img src="{{ base_url('assets/images/design/portal') }}/kantor_yayasan.png" alt="" class="img-fluid">
                      </div>
                      <div class="card-body text-center">
                        <h4 class="card-title">Yayasan <br/>Atma Jaya</h4>
                      </div>
                      <a href="{{ base_url('yayasan') }}" class="stretched-link"></a>
                    </div>
                  </div>
          
                  <div class="col-6 col-md">
                    <div class="card h-100 mb-0">
                      <div class="card-header bg-white">
                        <img src="{{ base_url('assets/images/design/portal') }}/logo-full.png" alt="" class="img-fluid">
                      </div>
                      <div class="card-body text-center">
                        <h4 class="card-title">Universitas</h4>
                      </div>
                      <a href="{{ base_url('universitas') }}" class="stretched-link"></a>
                    </div>
                  </div>
          
                  <div class="col-6 col-md">
                    <div class="card h-100 mb-0">
                      <div class="card-header bg-white">
                        <img src="{{ base_url('assets/images/design/portal') }}/layout.png" alt="" class="img-fluid">
                      </div>
                      <div class="card-body text-center">
                        <h4 class="card-title">PT Atma Jaya Karya Medika</h4>
                      </div>
                      <a href="javascript:void()" class="stretched-link"></a>
                    </div>
                  </div>
          
                  <div class="col-6 col-md">
                    <div class="card h-100 mb-0">
                      <div class="card-header bg-white">
                        <img src="{{ base_url('assets/images/design/portal') }}/layout.png" alt="" class="img-fluid">
                      </div>
                      <div class="card-body text-center">
                        <h4 class="card-title">PT Atma Jaya Mitra Nusantara</h4>
                      </div>
                      <a href="javascript:void()" class="stretched-link"></a>
                    </div>
                  </div>
          
                  <div class="col-6 col-md">
                    <div class="card h-100 mb-0">
                      <div class="card-header bg-white">
                        <img src="{{ base_url('assets/images/design/portal') }}/apotek.png" alt="" class="img-fluid">
                      </div>
                      <div class="card-body text-center">
                        <h4 class="card-title">Apotek</h4>
                      </div>
                      <a href="javascript:void()" class="stretched-link"></a>
                    </div>
                  </div>
          
                  <div class="col-6 col-md">
                    <div class="card h-100 mb-0">
                      <div class="card-header bg-white">
                        <img src="{{ base_url('assets/images/design/portal') }}/klinik.png" alt="" class="img-fluid">
                      </div>
                      <div class="card-body text-center">
                        <h4 class="card-title">Klinik</h4>
                      </div>
                      <a href="javascript:void()" class="stretched-link"></a>
                    </div>
                  </div>
                  {{-- End Item --}}
          
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

  @include('pages.portal.filter-modal')

</div>
<!-- END: Content (Application List Card) -->

<script>
  $(document).ready(function() {
    selectbox('filter-headcount');
    yearpickerModal("filter-year-pb-konsolidasi", "modal-pb-konsolidasi");
    yearmonthpickerModal('filter_date1_chartRealisasi_Kenaikan_PB','modal-pb-konsolidasi');
    yearmonthpickerModal('filter_date2_chartRealisasi_Kenaikan_PB','modal-pb-konsolidasi');
    rangeYearMonthPickerModal('input-daterange','modal-pb-konsolidasi');
  });
  
  headcountChart("tahun","","");
  @php $year = date("Y"); @endphp
  chartPbKonsolidasi("tahun","{{$year}}");
  chartKenaikanPbKonsolidasi("tahun","{{$year}}");
  tablePbKonsolidasi("tahun","{{$year}}");

  function headcountChart(tipe, tahun, quarter = ""){
      $.ajax({
          url      : "chart-headcount",
          type     : "POST",
          dataType : "html",
          data     : {
              "{{ csrf_token() }}": "{{ csrf_hash() }}",
              "tipe"              : tipe,
              "tahun"             : tahun,
              "quarter"           : quarter
          },
          before : function() {
              $(".data-loader").fadeIn();
          },
          success  : function(data) {
              $('#chart-container').html(data);
          },
          complete : function() {
              $(".data-loader").fadeOut();
          }
      });
  }

  function chartPbKonsolidasi(filter,year='',quarter='') {
    if(filter!="tahun_bulan"){
      $.ajax({
          url: "chart-pb-konsolidasi",
          type: 'POST',
          dataType: 'html',
          data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                      Filter  : filter, 
                      Year    : year, 
                      Quarter : quarter 
                },
          success: function(res) {
              $("#chart-pb-konsolidasi").html(res);
          }
      });
    }
    else if(filter=="tahun_bulan"){
        var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
        var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
        if(fromDate && toDate){
            $.ajax({
                url: "chart-pb-konsolidasi",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            From    : fromDate, 
                            To      : toDate 
                       },
                success: function(res) {
                    $("#chart-pb-konsolidasi").html(res);
                }
            });
        }
    }
  }
  
  function chartKenaikanPbKonsolidasi(filter,year='',quarter='') {
    if(filter!="tahun_bulan"){
      $.ajax({
          url: "chart-kenaikan-pb-konsolidasi",
          type: 'POST',
          dataType: 'html',
          data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                      Filter  : filter, 
                      Year    : year, 
                      Quarter : quarter 
                },
          success: function(res) {
              $("#chart-kenaikan-pb-konsolidasi").html(res);
          }
      });
    }
    else if(filter=="tahun_bulan"){
        var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
        var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
        $.ajax({
            url: "chart-kenaikan-pb-konsolidasi",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                        Filter  : filter, 
                        From    : fromDate, 
                        To      : toDate 
                   },
            success: function(res) {
                $("#chart-kenaikan-pb-konsolidasi").html(res);
            }
        });
    }
  }

  function tablePbKonsolidasi(filter,year='',quarter='') {
    if(filter!="tahun_bulan"){
      $.ajax({
          url: "table-pb-konsolidasi",
          type: 'POST',
          dataType: 'html',
          data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                      Filter  : filter, 
                      Year    : year, 
                      Quarter : quarter 
                },
          success: function(res) {
              $("#table-pb-konsolidasi").html(res);
          }
      });
    }
    else if(filter=="tahun_bulan"){
        var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
        var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
        $.ajax({
            url: "table-pb-konsolidasi",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                        Filter  : filter, 
                        From    : fromDate, 
                        To      : toDate 
                   },
            success: function(res) {
                $("#table-pb-konsolidasi").html(res);
            }
        });
    }
  }

  $("#form-pb-konsolidasi").on('submit', function(event) {
      event.preventDefault();
        var filtertype  = $("#filter_type_chartRealisasi_Kenaikan_PB").val();
        var year        = $("#filter-year-pb-konsolidasi").val();
        if(filtertype){
            if(filtertype=="tahun_bulan"){
                var yearMonth1  = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
                var yearMonth2  = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
                var countMonth  = diffMonth(yearMonth1,yearMonth2);
                if(countMonth>5){
                   Swal.fire({
                        title : 'Terjadi Kesalahan',
                        html  : 'Total Bulan Lebih Dari 5 Bulan',
                        icon  : 'error' 
                   });
                }
                else if(countMonth<0){
                   Swal.fire({
                        title : 'Terjadi Kesalahan',
                        html  : 'Range Waktu tidak sesuai',
                        icon  : 'error' 
                   });
                }
                else {
                    $("#modal-pb-konsolidasi").modal('hide');
                    chartPbKonsolidasi(filtertype);
                    chartKenaikanPbKonsolidasi(filtertype);
                    tablePbKonsolidasi(filtertype);
                }
            }
            else {
                var quarter  = $("#filter_quarter_chartRealisasi_Kenaikan_PB").val();
                chartPbKonsolidasi(filtertype,year,quarter);
                chartKenaikanPbKonsolidasi(filtertype,year,quarter);
                tablePbKonsolidasi(filtertype,year,quarter);
                $("#modal-pb-konsolidasi").modal('hide');
            }
        }

  });
</script>

@section('script')
  <script> 
  function yearmonthpickerModal(id,modalID){
     $("#"+id).datepicker( {
      format: "yyyy-mm",
      viewMode: "months", 
      minViewMode: "months",
      autoclose : true,
      container: '#'+modalID,
      startDate: '2018-01'
    });
  }

  function yearpickerModal(id,modalID){
    $("#"+id).datepicker( {
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      autoclose : true,
      container: '#'+modalID,
      startDate: '2018'
    });
  }

  function rangeYearMonthPickerModal(id,modalID){
    $("."+id).datepicker( {
      format: "yyyy-mm",
      viewMode: "months", 
      minViewMode: "months",
      autoclose : true,
      container: '#'+modalID,
      startDate: '2018-01'
    });
  }
  </script>
@endsection

@endsection