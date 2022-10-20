<!DOCTYPE html>
<html lang="en">
<head>
  
  @include('pages.portal.head')
  
</head>
<body>
  @include('layout.partials.loader')
  
  @include('pages.portal.topbar')
  
  @yield('content')
  
  @include('pages.portal.footer')

  @include('pages.portal.scripts')
  <!-- App js -->
  <script src="{{base_url('')}}/assets/js/app.js"></script>
  
  @include('pages.portal.function-scripts')


  @yield('script')

  <script>

    $("#btn-reset-hc").hide();
    $("#btn-reset-pb").hide();
    $("#btn-reset-hc").on("click", function(){
        $("#btn-reset-hc").hide();
    });
    $("#btn-reset-pb").on("click", function(){
        $("#btn-reset-pb").hide();
        $("#filter_type_chartRealisasi_Kenaikan_PB").val("").trigger("change");
    });
    yearpickerModal('year-headcount','filter-heacount');
    
    $("#form-headcount").on('submit', function(event) {
        event.preventDefault();
        var tipe    = $("#filter-tipe").val();
        var tahun   = $("#filter-tahun").val();
        var quarter = $("#filter-quarter").val();
        headcountChart(tipe, tahun, quarter);
        $("#filter-headcount").modal('hide');
    });

    function getTipeFilter(tipe,quarter_div,quarter_value){
        var data = $("#"+tipe).val();
        if(data){
            if(data=="tahun" || data=="quarter"){
                $("#"+quarter_value).val("");
                $("#"+quarter_value).prop('required',false);
                $("#"+quarter_div).hide();
                if (data == "tahun") {
                    $("#btn-reset-hc").hide();
                } else {
                    $("#btn-reset-hc").show();
                }
            }
            else {
                $("#"+quarter_value).val("");
                $("#"+quarter_value).prop('required',true);
                $("#"+quarter_div).show();
            }
        }
        else {
            $("#"+quarter_value).val("");
            $("#"+quarter_div).hide();
        }
    }

    $("#filter-tahun").on("change", function(){
        if ($("#filter-tahun").val()!="" || $("#filter-tipe").val()=="quarter") {
            $("#btn-reset-hc").show();
        } else {
            $("#btn-reset-hc").hide();
        }
    });

    function getTypeChartRealiasi(fromID){
        var data = $("#"+fromID).val();
        if(data){
            if(data=="tahun" || data=="quarter"){

                //Tahun Show
                $("#filter-year-pb-konsolidasi").val("");
                $("#filter-year-pb-konsolidasi").prop('required',true);
                $("#div_tahun_chartRealisasi_Kenaikan_PB").show();

                //Quarter Hide
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#filter-quarter-pb-container").hide();

                //Tahun Bulan Hide
                $("#filter_date1_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date2_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date1_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#filter_date2_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_costumDate1_chartRealisasi_Kenaikan_PB").hide();
                $("#div_costumDate2_chartRealisasi_Kenaikan_PB").hide();
                $("#tipe-filter-container").removeClass("col-12");
                $("#tipe-filter-container").addClass("col-6");
                $("#btn-reset-pb").show();
            }
            else if(data=="quater_komparasi") {
                //Tahun Show
                $("#filter-year-pb-konsolidasi").val("");
                $("#filter-year-pb-konsolidasi").prop('required',true);
                $("#div_tahun_chartRealisasi_Kenaikan_PB").show();

                //Quarter Show
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',true);
                $("#filter-quarter-pb-container").show();

                //Tahun Bulan Hide
                $("#filter_date1_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date2_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_date1_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#filter_date2_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#div_costumDate1_chartRealisasi_Kenaikan_PB").hide();
                $("#div_costumDate2_chartRealisasi_Kenaikan_PB").hide();
                $("#tipe-filter-container").removeClass("col-12");
                $("#tipe-filter-container").addClass("col-6");
                $("#btn-reset-pb").show();
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
                $("#filter-year-pb-konsolidasi").val("");
                $("#filter-year-pb-konsolidasi").prop('required',false);
                $("#div_tahun_chartRealisasi_Kenaikan_PB").hide();

                //Quarter Hide 
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").val("");
                $("#filter_quarter_chartRealisasi_Kenaikan_PB").prop('required',false);
                $("#filter-quarter-pb-container").hide();
                $("#tipe-filter-container").removeClass("col-6");
                $("#tipe-filter-container").addClass("col-12");
                $("#btn-reset-pb").show();
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
            $("#filter-quarter-pb-container").hide();
            $("#tipe-filter-container").removeClass("col-6");
            $("#tipe-filter-container").addClass("col-12");
            $("#btn-reset-pb").hide();
        }
    }

    $("#filter-quarter-pb-container").on("change", function(){
        if ($("#filter-quarter-pb-container").val()!="" || $("#filter-tipe").val()=="quarter") {
            $("#btn-reset-pb").show();
        } else {
            $("#btn-reset-pb").hide();
        }
    });
  </script>
</body>
</html>