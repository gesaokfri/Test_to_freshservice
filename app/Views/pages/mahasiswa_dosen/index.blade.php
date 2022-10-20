@extends('layout.index')
@section('title', 'Mahasiswa dan Dosen')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Mahasiswa dan Dosen</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/beranda">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Mahasiswa dan Dosen</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden p-3">
                            <div class="row">

                                @include('pages.mahasiswa_dosen.mahasiswa.data-container')

                                @include('pages.mahasiswa_dosen.mahasiswa_baru.data-container')

                                @include('pages.mahasiswa_dosen.dosen.data-container')

                                @include('pages.mahasiswa_dosen.rasio_dosen_mahasiswa.data-container')

                            </div>
                        </div>
                    </div>
                </div>

                <div class="floating-filter">
                    <button class="btn btn-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#globalModal"><i class="mdi mdi-filter"></i></button>
                </div>
            </div>
        </div>
    </div>

    @include('pages.mahasiswa_dosen.filter-modal')

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            @php
                (empty($_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaFakultas']) ? $compareMahasiswaF = "" : $compareMahasiswaF = $_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaFakultas']);
                (empty($_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaProdi']) ? $compareMahasiswaP = "" : $compareMahasiswaP = $_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaProdi']);
                (empty($_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaTahunAngkatan']) ? $compareMahasiswaTA = "" : $compareMahasiswaTA = $_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaTahunAngkatan']);
                (empty($_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaTahun']) ? $compareMahasiswaT = "" : $compareMahasiswaT = $_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaTahun']);
                (empty($_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaBulan']) ? $compareMahasiswaB = "" : $compareMahasiswaB = $_SESSION['compareTotalMahasiswa']['compareTotalMahasiswaBulan']);
                

                (empty($_SESSION['totalMahasiswa']['totalMahasiswaFakultas']) ? $totalMahasiswaF = "" : $totalMahasiswaF = $_SESSION['totalMahasiswa']['totalMahasiswaFakultas']);
                (empty($_SESSION['totalMahasiswa']['totalMahasiswaStatus']) ? $totalMahasiswaS = "" : $totalMahasiswaS = $_SESSION['totalMahasiswa']['totalMahasiswaStatus']);
                (empty($_SESSION['totalMahasiswa']['totalMahasiswaTahunAngkatan']) ? $totalMahasiswaTA = "" : $totalMahasiswaTA = $_SESSION['totalMahasiswa']['totalMahasiswaTahunAngkatan']);
                (empty($_SESSION['totalMahasiswa']['totalMahasiswaTahun']) ? $totalMahasiswaT = "" : $totalMahasiswaT = $_SESSION['totalMahasiswa']['totalMahasiswaTahun']);
                (empty($_SESSION['totalMahasiswa']['totalMahasiswaBulan']) ? $totalMahasiswaB = "" : $totalMahasiswaB = $_SESSION['totalMahasiswa']['totalMahasiswaBulan']);
            @endphp
            @if (!empty($_SESSION["compareTotalMahasiswa"]))
                GetProdi("{!! $compareMahasiswaF !!}")
                compareTotalMahasiswa(
                "{!! $compareMahasiswaF !!}", 
                "{!! $compareMahasiswaP !!}", 
                "{!! $compareMahasiswaTA !!}",
                "{!! $compareMahasiswaT !!}",
                "{!! $compareMahasiswaB !!}"
                )
            @else
                totalMahasiswa(
                "{!! $totalMahasiswaF !!}", 
                "{!! $totalMahasiswaS !!}", 
                "{!! $totalMahasiswaTA !!}", 
                "{!! $totalMahasiswaT !!}", 
                "{!! $totalMahasiswaB !!}"
                )
            @endif

            @if (!empty($_SESSION["compareTotalMahasiswaBaru"]["compareTotalMahasiswaBaruFakultas"]))
                @if (!empty($_SESSION["compareTotalMahasiswaBaru"]["compareTotalMahasiswaBaruProdi"]))
                    @if (!empty($_SESSION["compareTotalMahasiswaBaru"]["compareTotalMahasiswaBaruTahunAngkatan"]))
                        compareTotalMahasiswaBaru("{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruFakultas'] !!}", "{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruProdi'] !!}", "{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruTahunAngkatan'] !!}")
                    @else
                        compareTotalMahasiswaBaru("{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruFakultas'] !!}", "{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruProdi'] !!}", "")
                    @endif
                @else
                    @if (!empty($_SESSION["compareTotalMahasiswaBaru"]["compareTotalMahasiswaBaruTahunAngkatan"]))
                        compareTotalMahasiswaBaru("{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruFakultas'] !!}", "", "{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruTahunAngkatan'] !!}")
                    @else
                        compareTotalMahasiswaBaru("{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruFakultas'] !!}", "", "")
                    @endif
                @endif
            @else
                @if (!empty($_SESSION["compareTotalMahasiswaBaru"]["compareTotalMahasiswaBaruTahunAngkatan"]))
                    compareTotalMahasiswaBaru("", "", "{!! $_SESSION['compareTotalMahasiswaBaru']['compareTotalMahasiswaBaruTahunAngkatan'] !!}")
                @else
                    @if (!empty($_SESSION["totalMahasiswaBaru"]["totalMahasiswaBaruFakultas"]))
                        @if (!empty($_SESSION["totalMahasiswaBaru"]["totalMahasiswaBaruAngkatan"]))
                            totalMahasiswaBaru("{!! $_SESSION['totalMahasiswaBaru']['totalMahasiswaBaruFakultas'] !!}", "{!! $_SESSION['totalMahasiswaBaru']['totalMahasiswaBaruAngkatan'] !!}")
                        @else
                            totalMahasiswaBaru("{!! $_SESSION['totalMahasiswaBaru']['totalMahasiswaBaruFakultas'] !!}", "")
                        @endif
                    @else
                        @if (!empty($_SESSION["totalMahasiswaBaru"]["totalMahasiswaBaruAngkatan"]))
                            totalMahasiswaBaru("", "{!! $_SESSION['totalMahasiswaBaru']['totalMahasiswaBaruAngkatan'] !!}")
                        @else
                            totalMahasiswaBaru("", "")
                        @endif
                    @endif
                @endif
            @endif

            @if (!empty($_SESSION["compareTotalDosen"]["compareTotalDosenFakultas"]))
                compareTotalDosen("{!! $_SESSION['compareTotalDosen']['compareTotalDosenFakultas'] !!}")
            @else
                @if (!empty($_SESSION["totalDosen"]["totalDosenJabatanAkademik"]))
                    totalDosen("", 1);
                @else
                    @if (!empty($_SESSION["totalDosen"]["totalDosenFakultas"]))
                        totalDosen("{!! $_SESSION['totalDosen']['totalDosenFakultas'] !!}", "");
                    @else
                        totalDosen("", 1);
                    @endif
                @endif
            @endif

            @php   
                (empty($_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaFakultas']) ? $compareRasioDosenMahasiswaF = "" : $compareRasioDosenMahasiswaF = $_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaFakultas']);
                (empty($_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaProdi']) ? $compareRasioDosenMahasiswaP = "" : $compareRasioDosenMahasiswaP = $_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaProdi']);
                (empty($_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaTahunAngkatan']) ? $compareRasioDosenMahasiswaTA = "" : $compareRasioDosenMahasiswaTA = $_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaTahunAngkatan']);
                (empty($_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaTahun']) ? $compareRasioDosenMahasiswaT = "" : $compareRasioDosenMahasiswaT = $_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaTahun']);
                (empty($_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaBulan']) ? $compareRasioDosenMahasiswaB = "" : $compareRasioDosenMahasiswaB = $_SESSION['compareRasioDosenMahasiswa']['compareRasioDosenMahasiswaBulan']);


                (empty($_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaFakultas']) ? $rasioDosenMahasiswaF = "" : $rasioDosenMahasiswaF = $_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaFakultas']);
                (empty($_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaTahunAngkatan']) ? $rasioDosenMahasiswaTA = "" : $rasioDosenMahasiswaTA = $_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaTahunAngkatan']);
                (empty($_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaTahun']) ? $rasioDosenMahasiswaT = "" : $rasioDosenMahasiswaT = $_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaTahun']);
                (empty($_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaBulan']) ? $rasioDosenMahasiswaB = "" : $rasioDosenMahasiswaB = $_SESSION['rasioDosenMahasiswa']['rasioDosenMahasiswaBulan']);
            @endphp
            @if (!empty($_SESSION["compareRasioDosenMahasiswa"]))
                compareRasioDosenMahasiswa(
                "{!! $compareRasioDosenMahasiswaF !!}",
                "{!! $compareRasioDosenMahasiswaP !!}",
                "{!! $compareRasioDosenMahasiswaTA !!}",
                "{!! $compareRasioDosenMahasiswaT !!}",
                "{!! $compareRasioDosenMahasiswaB !!}"
                )
            @else
                rasioDosenMahasiswa(
                "{!! $rasioDosenMahasiswaF !!}",
                "{!! $rasioDosenMahasiswaTA !!}",
                "{!! $rasioDosenMahasiswaT !!}",
                "{!! $rasioDosenMahasiswaB !!}"
                );
            @endif

            @if (!empty($_SESSION["globalCompare"]))
                getPreference(1);
            @elseif (!empty($_SESSION["globalFilter"]))
                getPreference(2);
            @endif
        });
        
        function totalMahasiswa(kodefakultas = "", status = "", tahunangkatan = "", tahun = "", bulan = "") {
            $.ajax({
                url      : "mahasiswa_dan_dosen/total_mahasiswa",
                type     : "POST",
                dataType : "html",
                data     : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "kode_fakultas"     : kodefakultas,
                    "status"            : status,
                    "tahunangkatan"     : tahunangkatan,
                    "tahun"             : tahun,
                    "bulan"             : bulan
                },
                beforeSend: function() {
                    $("#content-total_mahasiswa .data-loader").fadeIn();
                    $("#total_mahasiswa").slideUp();
                    $("#compare_total_mahasiswa").slideUp();
                    $.skylo('start');
                },
                success  : function(data) {
                    $("#total_mahasiswa").hide();
                    $("#compare_total_mahasiswa").hide();
                    $("#compare_fakultas_mahasiswa").val('').trigger('change');
                    $("#compare_tahun_mahasiswa").val('').trigger('change');
                    $("#single-chart").show();
                    $("#compare-chart").hide();
                    $('#total_mahasiswa').html(data);
                    if(status){
                        $('#statusMahasiswa').html(status);
                    } else {
                        $('#statusMahasiswa').html('');
                    }
                    if(kodefakultas){
                        var selectedFakultas =  $("#filter_fakultas_global option:selected").text();
                        $('#fakultasMahasiswa').html(selectedFakultas);
                    } else {
                        $('#fakultasMahasiswa').html('');
                    }
                    if(tahunangkatan) {
                        $('#angkatanMahasiswa').html('angkatan ' + tahunangkatan);
                    } else {
                        $('#angkatanMahasiswa').html('seluruh angkatan');
                    }
                    if(tahun) {
                        $('#tahunMahasiswa').html('tahun ' + tahun);
                    } else {
                        $('#tahunMahasiswa').html('tahun ' + {{ $latestYear }});
                    }
                    if(bulan) {
                        $('#bulanMahasiswa').html('bulan ' + bulan);
                    } else {
                        $('#bulanMahasiswa').html('bulan ' + {{ $latestMonth }});
                    }
                },
                complete : function() {
                    $("#content-total_mahasiswa .data-loader").fadeOut();
                    $("#total_mahasiswa").slideDown();
                    $.skylo('end');
                }
            })
        }

        function totalMahasiswaBaru(kodeFakultas = "", tahunAngkatan = "") {
            $.ajax({
                url : "mahasiswa_dan_dosen/total_mahasiswa_baru",
                type: "POST",
                dataType : "html",
                data : {
                    "{{ csrf_token() }}" : "{{ csrf_hash() }}",
                    "kode_fakultas" : kodeFakultas,
                    "tahunangkatan" : tahunAngkatan
                },
                beforeSend: function() {
                    $("#content-total_mahasiswa_baru .data-loader").fadeIn();
                    $("#total_mahasiswa_baru").slideUp();
                    $("#compare_total_mahasiswa_baru").slideUp();
                    $.skylo('start');
                },
                success : function(data) {
                    $("#total_mahasiswa_baru").hide();
                    $("#compare_total_mahasiswa_baru").hide();
                    $("#filter_fakultas_mahasiswa_baru").val('').trigger('change');
                    $("#single-chart_total_mahasiswa_baru").show();
                    $("#compare-chart_total_mahasiswa_baru").hide();
                    $('#total_mahasiswa_baru').html(data);
                    if(kodeFakultas) {
                        var selectedFakultas =  $("#filter_fakultas_global option:selected").text();
                        $('#fakultasMahasiswaBaru').html(selectedFakultas);
                    } else {
                        $('#fakultasMahasiswaBaru').html('');
                    }
                    if(tahunAngkatan) {
                        $('#angkatanMahasiswaBaru').html('angkatan ' + tahunAngkatan);
                    } else {
                        $('#angkatanMahasiswaBaru').html('angkatan ' + {{ $tahunTerbaruTotalMahasiswaBaru }});
                    }
                },
                complete : function() {
                    $("#content-total_mahasiswa_baru .data-loader").fadeOut();
                    $("#total_mahasiswa_baru").slideDown();
                    $.skylo('end');
                }
            })
        }

        function totalDosen(fakultas = "", jabatanAkademik = "") {
            $.ajax({
                url     : "mahasiswa_dan_dosen/total_dosen",
                type    : "POST",
                dataType: "html",
                data    : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "fakultas"          : fakultas,
                    "jabatanAkademik"   : jabatanAkademik,
                },
                beforeSend : function() {
                    $("#content-total_dosen .data-loader").fadeIn();
                    $("#total_dosen").slideUp();
                    $("#compare_total_dosen").slideUp();
                    $.skylo('start');
                },
                success : function(data) {
                    $("#total_dosen").hide();
                    $("#compare_total_dosen").hide();
                    $("#single-chart_total_dosen").show();
                    $("#compare-chart_total_dosen").hide();
                    $('#total_dosen').html(data);
                    if (jabatanAkademik != "") {
                        $('#filterDosen').html("Jumlah seluruh Dosen / Jabatan Akademik");
                    } else {
                        if (fakultas == "") {
                            $('#filterDosen').html("Jumlah Dosen seluruh Fakultas");
                        } else {
                            var selectedFakultas =  $("#filter_fakultas_global option:selected").text();
                            $('#filterDosen').html("Jumlah Dosen Fakultas" + " " + selectedFakultas);
                        }
                    }
                },
                complete : function() {
                    $("#content-total_dosen .data-loader").fadeOut();
                    $("#total_dosen").slideDown();
                    $.skylo('end');
                }
            })
        }

        function rasioDosenMahasiswa(fakultas = "", tahunangkatan = "", tahun = "", bulan = "") {
            $.ajax({
                url     : "mahasiswa_dan_dosen/rasio_dosen_mahasiswa",
                type    : "POST",
                dataType: "html",
                data    : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    kode_fakultas       : fakultas,
                    tahunAngkatan       : tahunangkatan,
                    tahun               : tahun,
                    bulan               : bulan
                },
                beforeSend : function() {
                    $("#content-rasio_dosen_mhs .data-loader").fadeIn();
                    $('#rasio_dosen_mahasiswa').slideUp();
                    $('#compare_rasio_dosen_mahasiswa').slideUp();
                    $("#legend-rasio").addClass('d-none');
                },
                success : function(data) {
                    $('#rasio_dosen_mahasiswa').hide();
                    $('#compare_rasio_dosen_mahasiswa').hide();
                    $('#compare-chart_rasiodosenmahasiswa').hide();
                    $('#single-chart_rasiodosenmahasiswa').show();
                    $('#rasio_dosen_mahasiswa').html(data);
                    $("#legend-rasio").removeClass('d-none');
                    if(fakultas){
                        var selectedFakultas =  $("#filter_fakultas_global option:selected").text();
                        $('#fakultasRasioDosenMhs').html(selectedFakultas);
                    } else {
                        $('#fakultasRasioDosenMhs').html('');
                    }
                    if(tahunangkatan) {
                        $('#angkatanRasioDosenMhs').html('angkatan ' + tahunangkatan);
                    } else {
                        $('#angkatanRasioDosenMhs').html('seluruh angkatan');
                    }
                    if(tahun) {
                        $('#tahunRasioDosenMhs').html('tahun ' + tahun);
                    } else {
                        $('#tahunRasioDosenMhs').html('tahun ' + {{ $latestYear }});
                    }
                    if(bulan) {
                        $('#bulanRasioDosenMhs').html('bulan ' + bulan);
                    } else {
                        $('#bulanRasioDosenMhs').html('bulan ' + {{ $latestMonth }});
                    }
                },
                complete : function() {
                    $("#content-rasio_dosen_mhs .data-loader").fadeOut();
                    $('#rasio_dosen_mahasiswa').slideDown();
                    $("#legend-rasio").addClass('d-flex');
                }
            })
        }

        function compareTotalMahasiswa(fakultas = "", prodi = "", tahunangkatan = "", tahun = "", bulan = "") {
            $.ajax({
                url      : "mahasiswa_dan_dosen/compare_total_mahasiswa",
                type     : "POST",
                dataType : "html",
                data     : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "fakultascompare"   : fakultas,
                    "prodicompare"      : prodi,
                    "angkatancompare"   : tahunangkatan,
                    "tahuncompare"      : tahun,
                    "bulancompare"      : bulan
                },
                beforeSend: function() {
                    $("#content-total_mahasiswa .data-loader").fadeIn();
                    $("#total_mahasiswa").slideUp();
                    $("#compare_total_mahasiswa").slideUp();
                    $.skylo('start');
                },
                success  : function(data) {
                    $("#total_mahasiswa").hide();
                    $("#compare_total_mahasiswa").hide();
                    $("#single-chart").hide();
                    $("#filter_fakultas_mahasiswa").val('').trigger('change');
                    $("#filter_status_mahasiswa").val('').trigger('change');
                    $("#filter_tahunangkatan_mahasiswa").val('').trigger('change');
                    $("#compare-chart").show();
                    if (prodi) {
                        var selectedProdi =  $("#compare_prodi_global option:selected").text();
                        $('#fakultasCompareMahasiswa').html("Prodi " + selectedProdi);
                    } else {
                        if(fakultas){
                            var selectedFakultas =  $("#compare_fakultas_global option:selected").text();
                            $('#fakultasCompareMahasiswa').html("seluruh fakultas " + selectedFakultas);
                        } else {
                            $('#fakultasCompareMahasiswa').html('seluruh fakultas');
                        }
                    }
                    if(tahunangkatan) {
                        $('#angkatanCompareMahasiswa').html('angkatan ' + tahunangkatan);
                    } else {
                        $('#angkatanCompareMahasiswa').html('seluruh angkatan');
                    }
                    $('#compare_total_mahasiswa').html(data);
                },
                complete : function() {
                    $("#content-total_mahasiswa .data-loader").fadeOut();
                    $("#compare_total_mahasiswa").slideDown();
                    $.skylo('end');
                }
            })
        }

        function compareTotalMahasiswaBaru(fakultas = "", prodi = "", tahunangkatan = "") {
            $.ajax({
                url      : "mahasiswa_dan_dosen/compare_total_mahasiswabaru",
                type     : "POST",
                dataType : "html",
                data     : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "fakultascompare"   : fakultas,
                    "prodicompare"      : prodi,
                    "tahuncompare"      : tahunangkatan
                },
                beforeSend: function() {
                    $("#content-total_mahasiswa_baru .data-loader").fadeIn();
                    $("#total_mahasiswa_baru").slideUp();
                    $("#compare_total_mahasiswa_baru").slideUp();
                    $.skylo('start');
                },
                success  : function(data) {
                    $("#total_mahasiswa_baru").hide();
                    $("#compare_total_mahasiswa_baru").hide();
                    $("#single-chart_total_mahasiswa_baru").hide();
                    $("#filter_fakultas_mahasiswa_baru").val('').trigger('change');
                    $("#compare-chart_total_mahasiswa_baru").show();
                    if (prodi) {
                        var selectedProdii =  $("#compare_prodi_global option:selected").text();
                        $('#fakultasCompareMahasiswaBaru').html("Prodi " + selectedProdii);
                    } else {
                        if(fakultas){
                            var selectedFakultass =  $("#compare_fakultas_global option:selected").text();
                            $('#fakultasCompareMahasiswaBaru').html("seluruh fakultas " + selectedFakultass);
                        } else {
                            $('#fakultasCompareMahasiswaBaru').html('seluruh fakultas');
                        }
                    }
                    $('#compare_total_mahasiswa_baru').html(data);
                },
                complete : function() {
                    $("#content-total_mahasiswa_baru .data-loader").fadeOut();
                    $("#compare_total_mahasiswa_baru").slideDown();
                    $.skylo('end');
                }
            })
        }

        function compareTotalDosen(fakultas = "") {
            $.ajax({
                url      : "mahasiswa_dan_dosen/compare_total_dosen",
                type     : "POST",
                dataType : "html",
                data     : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "fakultascompare"   : fakultas
                },
                beforeSend: function() {
                    $("#content-total_dosen .data-loader").fadeIn();
                    $("#total_dosen").slideUp();
                    $("#compare_total_dosen").slideUp();
                    $.skylo('start');
                },
                success  : function(data) {
                    $("#total_dosen").hide();
                    $("#compare_total_dosen").hide();
                    $("#single-chart_total_dosen").hide();
                    $("#compare-chart_total_dosen").show();
                    $('#compare_total_dosen').html(data);
                    if(fakultas){
                        var selectedFakultas =  $("#compare_fakultas_global option:selected").text();
                        $('#fakultasCompareDosen').html(selectedFakultas);
                    } else {
                        $('#fakultasCompareDosen').html('');
                    }
                },
                complete : function() {
                    $("#content-total_dosen .data-loader").fadeOut();
                    $("#compare_total_dosen").slideDown();
                    $.skylo('end');
                }
            })
        }

        function compareRasioDosenMahasiswa(fakultas = "", prodi = "", tahunangkatan = "", tahun = "", bulan = "") {
            $.ajax({
                url      : "mahasiswa_dan_dosen/compare_rasio_dosenmahasiswa",
                type     : "POST",
                dataType : "html",
                data     : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "fakultascompare"   : fakultas,
                    "prodicompare"      : prodi,
                    "angkatancompare"   : tahunangkatan,
                    "tahuncompare"      : tahun,
                    "bulancompare"      : bulan
                },
                beforeSend: function() {
                    $("#content-rasio_dosen_mhs .data-loader").fadeIn();
                    $("#rasio_dosen_mahasiswa").slideUp();
                    $("#compare_rasio_dosen_mahasiswa").slideUp();
                    $("#legend-rasio").addClass('d-none');
                    $.skylo('start');
                },
                success  : function(data) {
                    $("#rasio_dosen_mahasiswa").hide();
                    $("#compare_rasio_dosen_mahasiswa").hide();
                    $("#single-chart_rasiodosenmahasiswa").hide();
                    $("#compare-chart_rasiodosenmahasiswa").show();
                    $('#compare_rasio_dosen_mahasiswa').html(data);
                    $("#legend-rasio").removeClass('d-none');
                    if(fakultas){
                        var selectedFakultas =  $("#compare_fakultas_global option:selected").text();
                        $('#fakultasCompareRasioDosenMhs').html(selectedFakultas);
                    } else {
                        $('#fakultasCompareRasioDosenMhs').html('');
                    }
                     if(tahunangkatan) {
                        $('#angkatanCompareRasioDosenMhs').html('angkatan ' + tahunangkatan);
                    } else {
                        $('#angkatanCompareRasioDosenMhs').html('seluruh angkatan');
                    }
                },
                complete : function() {
                    $("#content-rasio_dosen_mhs .data-loader").fadeOut();
                    $("#compare_rasio_dosen_mahasiswa").slideDown();
                    $("#legend-rasio").addClass('d-flex');
                    $.skylo('end');
                }
            })
        }

        function setPreference(compare = "", filter = "") {
            $.ajax({
                url      : "mahasiswa_dan_dosen/set_preference",
                type     : "POST",
                dataType : "html",
                data     : {
                    "{{ csrf_token() }}": "{{ csrf_hash() }}",
                    "7e1f2f97"          : compare,
                    "7dde607"           : filter
                }
            });
        }

        function GetProdi(kodefakultas = "") {
            if (kodefakultas != "") {
                $.ajax({
                    url      : "mahasiswa_dan_dosen/get_prodi",
                    type     : "POST",
                    dataType : "html",
                    data     : {
                        "{{ csrf_token() }}": "{{ csrf_hash() }}",
                        "kodefakultas"   : kodefakultas
                    },
                    success  : function(data) {
                        $("#prodiGlobalCompare").show();
                        $("#compare_prodi_global").empty();
                        $("#compare_prodi_global").append(data);
                    }
                });
            } else {
                $("#compare_prodi_global").empty();
                $("#prodiGlobalCompare").hide();
            }
        }

        function getPreference(val){
            var defaultData = `<span>Tampilkan data default pada dashboard</span>`;
            var perbandinganData = `
                <select class="form-select" name="fakultascompare" id="compare_fakultas_global" onchange="GetProdi(this.value)">
                    <option value="">Seluruh Fakultas</option>
                    @foreach ($fakultas as $item)
                    <option value="{{ $item['KodeFakultas'] }}" @if (!empty($compareMahasiswaF)) @if ($compareMahasiswaF === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                    @endforeach
                </select>
                <div class="mt-3" id="prodiGlobalCompare" style="display: none">
                    <select class="form-select" name="prodicompare" id="compare_prodi_global">
                    </select>
                </div>
                <div class="mt-3"></div>
                <select class="form-select" name="tahunangkatancompare" id="compare_tahunangkatan_global">
                    <option value="">Seluruh Angkatan</option>
                    @foreach ($angkatan as $item)
                        <option value="{{ $item['TahunAngkatan'] }}" @if (!empty($compareMahasiswaTA)) @if ($compareMahasiswaTA === $item['TahunAngkatan']) selected @endif @endif>{{ $item['TahunAngkatan'] }}</option>
                    @endforeach
                </select>
                <div class="mt-3"></div>
                <select class="form-select" name="tahuncompare" id="compare_tahun_global">
                    <option value="">Tahun</option>
                    @foreach ($tahun as $item)
                    <option value="{{ $item }}" @if (!empty($compareMahasiswaT)) @if ($compareMahasiswaT == $item) selected @endif @endif>{{ $item }}</option>
                    @endforeach
                </select>
                <div class="mt-3"></div>
                <select class="form-select" name="bulancompare" id="compare_bulan_global">
                    <option value="">Bulan</option>
                    @foreach ($bulan as $item)
                    <option value="{{ $item }}" @if (!empty($compareMahasiswaB)) @if ($compareMahasiswaB == $item) selected @endif @endif>{{ $item }}</option>
                    @endforeach
                </select>
                <div class="modal-footer px-0">
                    <button type="button" class="btn btn-light" onclick="reset_global_compare()">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            `;
            var filterData = `
                <select class="form-select" name="fakultas" id="filter_fakultas_global">
                    <option value="">Semua Fakultas</option>
                    @foreach ($fakultas as $item)
                        <option value="{{ $item['KodeFakultas'] }}" @if (!empty($totalMahasiswaF)) @if ($totalMahasiswaF === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                    @endforeach
                </select>
                <div class="mt-3"></div>
                <select class="form-select" name="status" id="filter_status_global">
                    <option value="">Semua Status</option>
                    @foreach ($status as $item)
                        <option value="{{$item}}" @if (!empty($totalMahasiswaS)) @if ($totalMahasiswaS === $item) selected @endif @endif>{{$item}}</option>
                    @endforeach
                </select>
                <div class="mt-3"></div>
                <select class="form-select" name="tahunangkatan" id="filter_tahunangkatan_global">
                    <option value="">Seluruh Angkatan</option>
                    @foreach ($angkatan as $item)
                        <option value="{{ $item['TahunAngkatan'] }}" @if (!empty($totalMahasiswaTA)) @if ($totalMahasiswaTA === $item['TahunAngkatan']) selected @endif @endif>{{ $item['TahunAngkatan'] }}</option>
                    @endforeach
                </select>
                <div class="mt-3"></div>
                <select class="form-select" name="tahun" id="filter_tahun_global">
                    <option value="">Tahun</option>
                    @foreach ($tahun as $item)
                        <option value="{{ $item }}" @if (!empty($totalMahasiswaT)) @if ($totalMahasiswaT == $item) selected @endif @endif>{{ $item }}</option>
                    @endforeach
                </select>
                <div class="mt-3"></div>
                <select class="form-select" name="bulan" id="filter_bulan_global">
                    <option value="">Bulan</option>
                    @foreach ($bulan as $item)
                        <option value="{{ $item }}" @if (!empty($totalMahasiswaB)) @if ($totalMahasiswaB == $item) selected @endif @endif>{{ $item }}</option>
                    @endforeach
                </select>
                <div class="modal-footer px-0">
                    <button type="button" class="btn btn-light" onclick="reset_global_filter()">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            `;

            $("#appendPreference").slideUp();
            $("#appendPreference").empty();
            if(val == 0){
                $("#appendPreference").append(defaultData);
            } else if(val == 1){
                $("#appendPreference").append(perbandinganData);
            } else{
                $("#appendPreference").append(filterData);
            }
            $("#appendPreference").slideDown();
        }

        $("#form-comparefilter_global").on('submit', function(event) {
            event.preventDefault();
            if ($("#select-preference").val() == 1) {
                var fakultascompare = $("#compare_fakultas_global").val();
                var prodicompare    = $("#compare_prodi_global").val();
                var angkatancompare = $("#compare_tahunangkatan_global").val();
                var tahuncompare    = $("#compare_tahun_global").val();
                var bulancompare    = $("#compare_bulan_global").val();
                compareTotalMahasiswa(fakultascompare, prodicompare, angkatancompare, tahuncompare, bulancompare);
                compareTotalMahasiswaBaru(fakultascompare, prodicompare, tahuncompare);
                compareTotalDosen(fakultascompare);
                compareRasioDosenMahasiswa(fakultascompare, prodicompare, angkatancompare, tahuncompare, bulancompare);
                setPreference("7ce6e717", "");
            } else if ($("#select-preference").val() == 2) {
                var fakultasfilter      = $("#filter_fakultas_global").val();
                var statusfilter        = $("#filter_status_global").val();
                var tahunangkatanfilter = $("#filter_tahunangkatan_global").val();
                var tahunfilter         = $("#filter_tahun_global").val();
                var bulanfilter         = $("#filter_bulan_global").val();
                totalMahasiswa(fakultasfilter, statusfilter, tahunangkatanfilter, tahunfilter, bulanfilter);
                totalMahasiswaBaru(fakultasfilter, tahunangkatanfilter);
                totalDosen(fakultasfilter, "");
                rasioDosenMahasiswa(fakultasfilter, tahunangkatanfilter, tahunfilter, bulanfilter);
                setPreference("", "7ce6e717");
            }
            $("#globalModal").modal('hide');
        });

        $("#form-compare_mhs").on('submit', function(event) {
            event.preventDefault();
            var fakultascompare = $("#compare_fakultas_mahasiswa").val();
            var prodicompare    = $("#compare_prodi_mahasiswa").val();
            var tahuncompare    = $("#compare_tahun_mahasiswa").val();
            compareTotalMahasiswa(fakultascompare, prodicompare, tahuncompare);
            $("#compare-total_mhs").modal('hide');
        });

        $("#form-filter_mhs").on('submit', function(event) {
            event.preventDefault();
            var kode_fakultas = $("#filter_fakultas_mahasiswa").val();
            var status        = $("#filter_status_mahasiswa").val();
            var tahunangkatan = $("#filter_tahunangkatan_mahasiswa").val();
            totalMahasiswa(kode_fakultas, status, tahunangkatan);
            $("#filter-total_mhs").modal('hide');
        });
        
        $("#form-filter_mhs_baru").on('submit', function(event) {
            event.preventDefault();
            var kode_fakultas = $("#filter_fakultas_mahasiswa_baru").val();
            totalMahasiswaBaru(kode_fakultas);
            $("#filter-total_mhs_baru").modal('hide');
        });
        
        $("#form-filter_dosen").on('submit', function(event) {
            event.preventDefault();
            let restSelected = $("input[type='radio'][name='totalDosen']:checked");
            if (restSelected.val() == "jabatan_akademik") {
                totalDosen("", 1);
            } else {
                totalDosen(1, "");
            }
            $("#filter-total_dosen").modal('hide');
        });

        $("#form-filter_rasio_dosen_mhs").on('submit', function(event) {
            event.preventDefault();
            var kode_fakultas = $("#filter_fakultas_rasio_dosen_mahasiswa").val();
            rasioDosenMahasiswa(kode_fakultas);
            $("#filter-rasio_dosen_mhs").modal('hide');
        });
    </script>

@endsection 