@extends('yayasan.layout.index')
@section('title','Grafik Laporan Keuangan')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Grafik Laporan Keuangan</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/yayasan">Dashboard</a></li>
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
                                    @include('yayasan.pages.laporan_keuangan.chart_container')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    @include('yayasan.pages.laporan_keuangan.filter-modal')
    
@endsection

@section('script')

<script>
   yearmonthpickerModal('filter_date1_chartRealisasi_Kenaikan_PB','modal-filter_realisasi_Kenaikan_PB');
   yearmonthpickerModal('filter_date2_chartRealisasi_Kenaikan_PB','modal-filter_realisasi_Kenaikan_PB');
   yearpickerModal('filter_year_chartRealisasi_Kenaikan_PB','modal-filter_realisasi_Kenaikan_PB');
   yearpickerModal('filter_year_chartRealisasi_APB','modal-filter_realisasi_APB');
   yearpickerModal('filter_year_chart_Posisi_Keuangan','modal-filter_Posisi_Keuangan');
   yearmonthpickerModal('filter_periodeCashflow','modal-filter_cashflow');
   yearpickerModal('filter_year_chart_Capex','modal-filter_capex');

   FilterRealisasi1("tahun","{{$year}}");
   FilterRealisasi2("tahun","{{$year}}");
   FilterPendapatanBebanTable("tahun","{{$year}}");
   FilterRealisasiAPB("{{$year}}");
   FilterPosisiKeuangan("{{$year}}");
   FilterCapex("{{$year}}","bar");

    // 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
    yearpickerModal('filter_year_chartKsk', 'modal-filter_kassetarakas');
    yearpickerModal('filter_periodeTrend', 'modal-filter_trend_pendapatan');
    yearpickerModal('filter_year_chartInvestasi', 'modal-filter_investasi');
    // yearpickerModal('filter_periodePenvescapex', 'modal-filter_pengeluaranvescapex');
    // yearpickerModal('filter_periodeAsetTetap', 'modal-filter_asettetap');
    yearpickerModal('filter_periodeAsetTetapKonsolidasi', 'modal-filter_asettetap_konsolidasi');
   

    FilterKasSetaraKas("", "{{ $year_month }}", "");

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

    FilterTrendInvestasi("{{ $year_month }}");

    function FilterTrendInvestasi(period, chartType) {
        $.ajax({
            url: "laporan_keuangan/trend-investasi-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period},
            success: function(res) {
                $("#div-trend_investasiChart").html(res);
            }
        });
    }

    $("#form-filter_trend").on('submit', function(event) {
        event.preventDefault();
        var period        = $("#filter_periodeTrend").val();
        if(period){
            FilterTrendInvestasi(period);
            $("#modal-filter_trend_pendapatan").modal('hide');
        }
    });

    FilterInvestasi("", "{{ $year_month }}", "");

    function FilterInvestasi(filterType, period, quarter) {
        $.ajax({
            url: "laporan_keuangan/investasi-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", tipeFilter : filterType, parameter : period, q : quarter},
            success: function(res) {
                $("#div-investasiChart").html(res);
            }
        });
    }

    $("#form-filter_investasi").on('submit', function(event) {
        event.preventDefault();
        var filtertype = $("#filter_type_investasiChart").val();
        var period     = $("#filter_year_chartInvestasi").val();
        var quarter    = $("#filter_quarter_Investasi").val();
        if(filtertype && period){
            FilterInvestasi(filtertype, period, quarter);
            $("#modal-filter_investasi").modal('hide');
        }
    });

    // FilterPengeluaranvescapex("{{ $year_month }}");

    function FilterPengeluaranvescapex(period) {
        $.ajax({
            url: "laporan_keuangan/pengeluaran-investasi-capex-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period},
            success: function(res) {
                $("#div-pengeluaranvescapexChart").html(res);
            }
        });
    }

    $("#form-filter_pengeluaranvescapex").on('submit', function(event) {
        event.preventDefault();
        var period    = $("#filter_periodePenvescapex").val();
        if(period){
            FilterPengeluaranvescapex(period);
            $("#modal-filter_pengeluaranvescapex").modal('hide');
        }
    });

    // FilterAsetTetap("{{ $year_month }}");

    function FilterAsetTetap(period) {
        $.ajax({
            url: "laporan_keuangan/aset-tetap-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period},
            success: function(res) {
                $("#div-asettetapChart").html(res);
            }
        });
    }

    $("#form-filter_asettetap").on('submit', function(event) {
        event.preventDefault();
        var period    = $("#filter_periodeAsetTetap").val();
        if(period){
            FilterAsetTetap(period);
            $("#modal-filter_asettetap").modal('hide');
        }
    });

    FilterAsetTetapKonsolidasi("{{ $year_month }}");

    function FilterAsetTetapKonsolidasi(period) {
        $.ajax({
            url: "laporan_keuangan/aset-tetap-konsolidasi-filter",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period},
            success: function(res) {
                $("#div-asettetap_konsolidasiChart").html(res);
            }
        });
    }

    $("#form-filter_asettetap_konsolidasi").on('submit', function(event) {
        event.preventDefault();
        var period    = $("#filter_periodeAsetTetapKonsolidasi").val();
        if(period){
            FilterAsetTetapKonsolidasi(period);
            $("#modal-filter_asettetap_konsolidasi").modal('hide');
        }
    });
    // 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211

    $("#form-filter_realisasi_Kenaikan_PB").on('submit', function(event) {
        event.preventDefault();
        var filtertype  = $("#filter_type_chartRealisasi_Kenaikan_PB").val();
        var year        = $("#filter_year_chartRealisasi_Kenaikan_PB").val();
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
                    $("#modal-filter_realisasi_Kenaikan_PB").modal('hide');
                    FilterRealisasi1(filtertype);
                    FilterRealisasi2(filtertype);
                    FilterPendapatanBebanTable(filtertype);
                }
            }
            else {
                var quarter  = $("#filter_quarter_chartRealisasi_Kenaikan_PB").val();
                FilterRealisasi1(filtertype,year,quarter);
                FilterRealisasi2(filtertype,year,quarter);
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

    $("#form-filter_capex").on('submit', function(event) {
        event.preventDefault();
        var year = $("#filter_year_chart_Capex").val();
        if(year){
            FilterCapex(year);
            $("#modal-filter_capex").modal('hide');
        }
    });

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
    
    function FilterRealisasi1(filter,year='',quarter='') {
        if(filter!="tahun_bulan"){
            $.ajax({
                url: "laporan_keuangan/neraca-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            Year    : year,
                            Quarter : quarter 
                       },
                success: function(res) {
                    $("#div_ChartRealisasi1").html(res);
                }
            });
        }
        else if(filter=="tahun_bulan"){
            var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
            var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
            if(fromDate && toDate){
                $.ajax({
                    url: "laporan_keuangan/neraca-filter",
                    type: 'POST',
                    dataType: 'html',
                    data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                                Filter  : filter, 
                                From    : fromDate, 
                                To      : toDate 
                           },
                    success: function(res) {
                        $("#div_ChartRealisasi1").html(res);
                    }
                });
            }
        }
    }

    function FilterRealisasi2(filter,year='',quarter='') {
        if(filter!="tahun_bulan"){
            $.ajax({
                url: "laporan_keuangan/laba_rugi-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            Year    : year, 
                            Quarter : quarter 
                       },
                success: function(res) {
                    $("#div_ChartRealisasi2").html(res);
                }
            });
        }
        else if(filter=="tahun_bulan"){
            var fromDate = $("#filter_date1_chartRealisasi_Kenaikan_PB").val();
            var toDate   = $("#filter_date2_chartRealisasi_Kenaikan_PB").val();
            $.ajax({
                url: "laporan_keuangan/laba_rugi-filter",
                type: 'POST',
                dataType: 'html',
                data : {"{{csrf_token()}}" : "{{csrf_hash()}}", 
                            Filter  : filter, 
                            From    : fromDate, 
                            To      : toDate 
                       },
                success: function(res) {
                    $("#div_ChartRealisasi2").html(res);
                }
            });
        }
    }

    function FilterPendapatanBebanTable(filter,year='',quarter='') {
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
        else {
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