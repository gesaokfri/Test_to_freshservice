@extends('layout.index')
@section('title','Grafik Laporan Keuangan')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Laporan Keuangan</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/universitas">Dashboard</a></li>
                                    <li class="breadcrumb-item">Laporan Keuangan</li>
                                    <li class="breadcrumb-item active">Grafik</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden">
                            <div class="row">

                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    @include('pages.laporan_keuangan.chart_container')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    @include('pages.laporan_keuangan.filter-modal')
@endsection


@section('script')
<script>
yearpickerModal('filter_year_chartRealisasi_Kenaikan_PB','modal-filter_realisasi_Kenaikan_PB');
yearpickerModal('filter_year_chartRealisasi_APB','modal-filter_realisasi_APB');
yearpickerModal('filter_year_chart_Posisi_Keuangan','modal-filter_Posisi_Keuangan');
yearpickerModal('filter_year_chartKsk', 'modal-filter_kassetarakas');
yearpickerModal('filter_year_chart_Cashflow','modal-filter_cashflow');
// yearpickerModal('filter_year_chart_asetTetap','modal-filter_asetTetap');
yearpickerModal('filter_year_chart_Capex','modal-filter_capex');
yearpickerModal('filter_year_piutangAging','modal-filter_PiutangAging');
yearmonthpickerModal('filter_date1_chartRealisasi_Kenaikan_PB','modal-filter_realisasi_Kenaikan_PB');
yearmonthpickerModal('filter_date2_chartRealisasi_Kenaikan_PB','modal-filter_realisasi_Kenaikan_PB');


FilterPiutangAging();
FilterRealisasiPBYAJ("tahun","{{$year}}");
FilterKenaikanPB("tahun","{{$year}}");
FilterPendapatanBebanTable("tahun","{{$year}}");

FilterRealisasiAPB("{{$year}}");

FilterPosisiKeuangan("{{$year}}");

FilterKasSetaraKas("", "{{ $year_month }}", "");

FilterCashflow("{{$year}}");

// FilterAsetTetap("{{$year}}");

FilterCapex("{{$year}}");



    $("#form-filter_realisasi_Kenaikan_PB").on('submit', function(event) {
        event.preventDefault();
        var filtertype  = $("#filter_type_chartRealisasi_Kenaikan_PB").val();
        var year        = $("#filter_year_chartRealisasi_Kenaikan_PB").val();
        if(filtertype){
            if(filtertype=="tahun_bulan"){
                var yearMonth1  = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
                var yearMonth2  = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
                var countMonth = diffMonth(yearMonth1,yearMonth2);
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
                    $("#modal-filter_realisasi_Kenaikan_PB").modal('hide');
                    FilterRealisasiPBYAJ(filtertype);
                    FilterKenaikanPB(filtertype,year);
                    FilterPendapatanBebanTable(filtertype,year);
                }
            }
            else {
                var quarter  = $("#filter_quarter_chartRealisasi_Kenaikan_PB").val();
                FilterRealisasiPBYAJ(filtertype,year,quarter);
                FilterKenaikanPB(filtertype,year,quarter);
                FilterPendapatanBebanTable(filtertype,year,quarter);
                $("#modal-filter_realisasi_Kenaikan_PB").modal('hide');
            }
            
        }
    });

    $("#form-filter_realisasi_APB").on('submit', function(event) {
        event.preventDefault();
        var year        = $("#filter_year_chartRealisasi_APB").val();
        if(year){
            FilterRealisasiAPB(year);
            $("#modal-filter_realisasi_APB").modal('hide');
        }
    });

    $("#form-filter_Posisi_Keuangan").on('submit', function(event) {
        event.preventDefault();
        var year        = $("#filter_year_chart_Posisi_Keuangan").val();
        if(year){
            FilterPosisiKeuangan(year);
            $("#modal-filter_Posisi_Keuangan").modal('hide');
        }
    });

    $("#form-filter_kassetarakas").on('submit', function(event) {
        event.preventDefault();
        var filtertype = $("#filter_type_investasiKsk").val();
        var period     = $("#filter_year_chartKsk").val();
        var quarter    = $("#filter_quarter_KasSetaraKas").val();
        if(filtertype && period){
            FilterKasSetaraKas(filtertype, period, quarter);
            $("#modal-filter_kassetarakas").modal('hide');
        }
    });

    $("#form-filter_cashflow").on('submit', function(event) {
        event.preventDefault();
        var fakultas    = $("#filter_type_chartCashflowProfit").val();
        var year        = $("#filter_year_chart_Cashflow").val();
        if(year){
            FilterCashflow(year,fakultas);
            $("#modal-filter_cashflow").modal('hide');
        }
    });

    $("#form-filter_asetTetap").on('submit', function(event) {
        event.preventDefault();
        var year = $("#filter_year_chart_asetTetap").val();
        if(year){
            FilterAsetTetap(year);
            $("#modal-filter_asetTetap").modal('hide');
        }
    });

    $("#form-filter_capex").on('submit', function(event) {
        event.preventDefault();
        var year = $("#filter_year_chart_Capex").val();
        if(year){
            FilterCapex(year);
            $("#modal-filter_capex").modal('hide');
        }
    });

    $("#form-filter_PiutangAging").on('submit', function(event) {
        event.preventDefault();
        var jenjang = $("#filter_aging_jenjang").val();
        var prodi   = $("#filter_aging_prodi").val();
        var year    = $("#filter_year_piutangAging").val();
            FilterPiutangAging(jenjang,prodi,year);
            $("#modal-filter_PiutangAging").modal('hide');
    });

    function getProdiPiutangAging(fromID,toID){
        var from = $("#"+fromID).val();
        $.ajax({
            url: "{{base_url('')}}/prodi_PiutangAging",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter  : from},
            success: function(res) {
                $("#"+toID).html(res);
            }
        });
    }

    function getTypeChart(fromID,divId,toID){
        var data = $("#"+fromID).val();
        if(data){
            if(data=="tahun" || data=="quarter"){
                $("#"+toID).val("");
                $("#"+toID).prop('required',false);
                $("#"+divId).hide();
            }
            else {
                $("#"+toID).val("");
                $("#"+toID).prop('required',true);
                $("#"+divId).show();
            }
        }
        else {
            $("#"+toID).val("");
            $("#"+divId).hide();
        }
    }

    function getTypeChartRealiasi(fromID){
        var data = $("#"+fromID).val();
        if(data){
            if(data=="tahun" || data=="quarter"){

                //Tahun Show
                $("#filter_year_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_year_chartRealisasi_Kenaikan_PB").prop('required',true);
                $("#div_tahun_chartRealisasi_Kenaikan_PB").show();

                //Quarter Hide
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_quarter_chartRealisasi_Kenaikan_PB").hide();

                //Tahun Bulan Hide
                $("#filter_date1_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date2_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date1_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#filter_date2_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_costumDate1_chartRealisasi_Kenaikan_PB").hide();
                $("#div_costumDate2_chartRealisasi_Kenaikan_PB").hide();
            }
            else if(data=="quater_komparasi") {
                //Tahun Show
                $("#filter_year_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_year_chartRealisasi_Kenaikan_PB").prop('required',true);
                $("#div_tahun_chartRealisasi_Kenaikan_PB").show();

                //Quarter Show
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',true);
                $("#div_quarter_chartRealisasi_Kenaikan_PB").show();

                //Tahun Bulan Hide
                $("#filter_date1_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date2_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date1_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#filter_date2_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_costumDate1_chartRealisasi_Kenaikan_PB").hide();
                $("#div_costumDate2_chartRealisasi_Kenaikan_PB").hide();
            }
            else if(data=="tahun_bulan") {
                //Tahun Bulan Show
                $("#filter_date1_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date2_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date1_chartRealisasi_Kenaikan_PB").prop('required',true);
                $("#filter_date2_chartRealisasi_Kenaikan_PB").prop('required',true);
                $("#div_costumDate1_chartRealisasi_Kenaikan_PB").show();
                $("#div_costumDate2_chartRealisasi_Kenaikan_PB").show();

                //Tahun Hide
                $("#filter_year_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_year_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_tahun_chartRealisasi_Kenaikan_PB").hide();

                //Quarter Hide
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_quarter_chartRealisasi_Kenaikan_PB").hide();
            }
        }
        else {
            //Tahun Bulan Hide
            $("#filter_date1_chartRealisasi_Kenaikan_PB").val("");
            $("#filter_date2_chartRealisasi_Kenaikan_PB").val("");
            $("#filter_date1_chartRealisasi_Kenaikan_PB").prop('required',false);
            $("#filter_date2_chartRealisasi_Kenaikan_PB").prop('required',false);
            $("#div_costumDate1_chartRealisasi_Kenaikan_PB").hide();
            $("#div_costumDate2_chartRealisasi_Kenaikan_PB").hide();

            //Tahun Hide
            $("#filter_year_chartRealisasi_Kenaikan_PB").val("");
            $("#filter_year_chartRealisasi_Kenaikan_PB").prop('required',false);
            $("#div_tahun_chartRealisasi_Kenaikan_PB").hide();

            //Quarter Hide
            $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
            $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',false);
            $("#div_quarter_chartRealisasi_Kenaikan_PB").hide();
        }
    }

    function FilterRealisasiPBYAJ(filter,year='',quarter='') {
        if(filter!="tahun_bulan"){
            $.ajax({
                url: "laporan_keuangan/konsolidasi_pb_yaj-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            Year    : year, 
                            Quarter : quarter 
                       },
                success: function(res) {
                    $("#div_ChartRealisasi_PB_YAJ").html(res);
                }
            });
        }
        else if(filter=="tahun_bulan"){
            var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
            var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
            if(fromDate && toDate){
                $.ajax({
                    url: "laporan_keuangan/konsolidasi_pb_yaj-filter",
                    type: 'POST',
                    dataType: 'html',
                    data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                                Filter  : filter, 
                                From    : fromDate, 
                                To      : toDate 
                           },
                    success: function(res) {
                        $("#div_ChartRealisasi_PB_YAJ").html(res);
                    }
                });
            }
        }
    }

    function FilterKenaikanPB(filter,year,quarter='') {
        if(filter!="tahun_bulan"){
            $.ajax({
                url: "laporan_keuangan/kenaikan_pb-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            Year    : year, 
                            Quarter : quarter 
                       },
                success: function(res) {
                    $("#div_ChartRealisasi_Kenaikan_PB").html(res);
                }
            });
        }
        else if(filter=="tahun_bulan"){
            var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
            var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
            $.ajax({
                url: "laporan_keuangan/kenaikan_pb-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            From    : fromDate, 
                            To      : toDate 
                       },
                success: function(res) {
                    $("#div_ChartRealisasi_Kenaikan_PB").html(res);
                }
            });
        }
    }

    function FilterPendapatanBebanTable(filter,year,quarter='') {
        if(filter!="tahun_bulan"){
            $.ajax({
                url: "laporan_keuangan/pendapatan_beban-table",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            Year    : year, 
                            Quarter : quarter 
                       },
                success: function(res) {
                    $("#div_TablePendapatanBeban").html(res);
                }
            });
        }
        else if(filter=="tahun_bulan"){
            var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
            var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
            $.ajax({
                url: "laporan_keuangan/pendapatan_beban-table",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            From    : fromDate, 
                            To      : toDate 
                       },
                success: function(res) {
                    $("#div_TablePendapatanBeban").html(res);
                }
            });
        }
    }

    function FilterRealisasiAPB(year) {
        $.ajax({
            url: "laporan_keuangan/realisasi_apb-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                        Year : year 
                   },
            success: function(res) {
                $("#div_ChartRealisasi_APB").html(res);
            }
        });
    } 

    function FilterPosisiKeuangan(year) {
        $.ajax({
            url: "laporan_keuangan/posisi_keuangan-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                        Year : year 
                   },
            success: function(res) {
                $("#div_Chart_Posisi_Keuangan").html(res);
            }
        });
    }

    function FilterKasSetaraKas(filterType, period, quarter) {
        $.ajax({
            url: "laporan_keuangan/kas-setara-kas-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", tipeFilter : filterType, parameter : period, q : quarter},
            success: function(res) {
                $("#div-kas_setarakasChart").html(res);
            }
        });
    }

    function FilterCashflow(year,fakultas='') {
        if(fakultas){
            detailCashflow(fakultas,year);
        }
        else {
             $.ajax({
                url: "laporan_keuangan/cashflow-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", Year : year, Fakultas : fakultas},
                success: function(res) {
                    $("#div_cashflowChart").html(res);
                }
            });
        }
       
    }

    function detailCashflow(id,yearData){
        $.ajax({
            url: "laporan_keuangan/cashflow_detail-filter",
            type: 'POST',
            dataType: 'html',
            data : {
                        "{{csrf_token()}}" : "{{csrf_hash()}}", 
                        parameter : id, 
                        year      : yearData, 
                   },
            success: function(res) {
                if(res){
                    $("#chart-cashflow").hide();
                    $("#div_cashflow_detail").show();
                    $("#btn_filter_profit").hide();
                    $("#btn_back_profit").show();
                    $("#div_cashflow_detail").html(res);
                }
            }
        });
    }

    function FilterAsetTetap(year) {
        $.ajax({
            url: "laporan_keuangan/aset_tetap-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", Year : year},
            success: function(res) {
                $("#div_asetTetapChart").html(res);
            }
        });
    } 

    function FilterCapex(year) {
        $.ajax({
            url: "laporan_keuangan/capex-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", Year : year},
            success: function(res) {
                $("#div_capexChart").html(res);
            }
        });
    }

    function FilterPiutangAging(jenjang='',prodi='',year='') {
        $.ajax({
            url: "laporan_keuangan/piutang_aging-filter",
            type: 'POST',
            dataType: 'json',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                        jenjang  : jenjang, 
                        prodi    : prodi, 
                        year     : year 
                   },
            success: function(res) {
                $("#monthPiutangAging").html(res.latestMonth);
                $("#tablePiutangAging").html(res.table);
            }
        });
    }

    function number_format (number, decimals, decPoint, thousandsSep) { 
     number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
     var n = !isFinite(+number) ? 0 : +number
     var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
     var sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep
     var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
     var s = ''

     var toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec)
      return '' + (Math.round(n * k) / k)
        .toFixed(prec)
     }

     // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
     s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
     if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
     }
     if ((s[1] || '').length < prec) {
      s[1] = s[1] || ''
      s[1] += new Array(prec - s[1].length + 1).join('0')
     }

     return s.join(dec)
    }

    function number_format_short( n, precision = 1 ) {
      var n_format=0;
      var suffix=0;
      if (n < 900) {
          // 0 - 900
          n_format = number_format(n, precision);
          suffix = '';
      } else if (n < 900000) {
          // 0.9k-850k
          n_format = number_format(n / 1000, precision);
          suffix = 'K';
      } else if (n < 900000000) {
          // 0.9m-850jt
          n_format = number_format(n / 1000000, precision);
          suffix = 'Jt';
      } else if (n < 900000000000) {
          // 0.9b-850m
          n_format = number_format(n / 1000000000, precision);
          suffix = 'M';
      } else {
          // 0.9t+
          n_format = number_format(n / 1000000000000, precision);
          suffix = 'T';
      }

      if ( precision > 0 ) {
          var dotzero = '.'.repeat(precision);
          n_format = n_format.replace(dotzero+"0",'');
      }
      return n_format+" "+suffix;
    }   
</script>
@endsection