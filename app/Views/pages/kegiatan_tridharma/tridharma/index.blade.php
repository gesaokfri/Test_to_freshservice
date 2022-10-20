@extends('layout.index')
@section('title', 'Tridharma')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Penelitian dan Pengabdian</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ base_url() }}/universitas">Dashboard</a></li>
                                    <li class="breadcrumb-item">Kegiatan Tridharma PT</li>
                                    <li class="breadcrumb-item active">Penelitian dan Pengabdian</li>
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
                                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#penelitian" role="tab" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">PENELITIAN</span> 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#pengabdian" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">PENGABDIAN</span> 
                                            </a>
                                        </li>
                                    </ul>
    
                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="penelitian" role="tabpanel">
                                            <p class="mb-0">
                                                @include('pages.kegiatan_tridharma.tridharma.penelitian.data-container')
                                            </p>
                                        </div>
                                        <div class="tab-pane" id="pengabdian" role="tabpanel">
                                            <p class="mb-0">
                                                @include('pages.kegiatan_tridharma.tridharma.pengabdian.data-container')
                                            </p>
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

    @include('pages.kegiatan_tridharma.tridharma.filter-modal')

@endsection

@section('script')
<script>
    $(document).ready(function() {
        @if (!empty($_SESSION["jumlahPenelitian"]["jumlahPenelitianFakultas"]))
            chartJumlahPenelitian("{!! $_SESSION['jumlahPenelitian']['jumlahPenelitianFakultas'] !!}")
            jumlahPenelitian("{!! $_SESSION['jumlahPenelitian']['jumlahPenelitianFakultas'] !!}")
        @else
            chartJumlahPenelitian("");
            jumlahPenelitian("");
        @endif

        @if (!empty($_SESSION["jumlahPengabdian"]["jumlahPengabdianFakultas"]))
            chartJumlahPengabdian("{!! $_SESSION['jumlahPengabdian']['jumlahPengabdianFakultas'] !!}")
            jumlahPengabdian("{!! $_SESSION['jumlahPengabdian']['jumlahPengabdianFakultas'] !!}")
        @else
            chartJumlahPengabdian("");
            jumlahPengabdian("");
        @endif
    });

    function chartJumlahPenelitian(kodeFakultas = "") {
        $.ajax({
            url      : "tridharma/chartjumlahpenelitian",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "fakultas"     : kodeFakultas
            },
            beforeSend: function() {
                $("#content-jumlah_penelitian .data-loader").fadeIn();
                $("#chart-jumlah_penelitian").slideUp();
                $("#jumlah_penelitian").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-jumlah_penelitian").hide();
                $("#jumlah_penelitian").hide();
                $('#chart-jumlah_penelitian').html(data);
                if(kodeFakultas){
                    var selectedFakultas =  $("#filter_fakultas_jumlah_penelitian option:selected").text();
                    $('#fakultasPenelitian').html('Jumlah penelitian Fakultas' + ' ' + selectedFakultas);
                } else{
                    $('#fakultasPenelitian').html('Jumlah penelitian seluruh Fakultas');
                }
            },
            complete : function() {
                $("#content-jumlah_penelitian .data-loader").fadeOut();
                $("#chart-jumlah_penelitian").slideDown();
                $("#jumlah_penelitian").slideDown();
                $.skylo('end');
            }
        })
    }
    
    function jumlahPenelitian(kodeFakultas = "") {
        $.ajax({
            url      : "tridharma/jumlah_penelitian",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "kode_fakultas"     : kodeFakultas
            },
            beforeSend: function() {
                $("#content-jumlah_penelitian .data-loader").fadeIn();
                $("#chart-jumlah_penelitian").slideUp();
                $("#jumlah_penelitian").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-jumlah_penelitian").hide();
                $("#jumlah_penelitian").hide();
                $('#jumlah_penelitian').html(data);
                if(kodeFakultas){
                    var selectedFakultas =  $("#filter_fakultas_jumlah_penelitian option:selected").text();
                    $('#fakultasPenelitian').html('Jumlah penelitian Fakultas' + ' ' + selectedFakultas);
                } else{
                    $('#fakultasPenelitian').html('Jumlah penelitian seluruh Fakultas');
                }
            },
            complete : function() {
                $("#content-jumlah_penelitian .data-loader").fadeOut();
                $("#chart-jumlah_penelitian").slideDown();
                $("#jumlah_penelitian").slideDown();
                $.skylo('end');
            }
        })
    }

    function chartJumlahPengabdian(kodeFakultas = "") {
        $.ajax({
            url      : "tridharma/chartjumlahpengabdian",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "fakultas"     : kodeFakultas
            },
            beforeSend: function() {
                $("#content-jumlah_pengabdian .data-loader").fadeIn();
                $("#chart-jumlah_pengabdian").slideUp();
                $("#jumlah_pengabdian").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-jumlah_pengabdian").hide();
                $("#jumlah_pengabdian").hide();
                $('#chart-jumlah_pengabdian').html(data);
                if(kodeFakultas){
                    var selectedFakultas =  $("#filter_fakultas_jumlah_pengabdian option:selected").text();
                    $('#fakultasPengabdian').html('Jumlah pengabdian Fakultas' + ' ' + selectedFakultas);
                } else{
                    $('#fakultasPengabdian').html('Jumlah pengabdian seluruh Fakultas');
                }
            },
            complete : function() {
                $("#content-jumlah_pengabdian .data-loader").fadeOut();
                $("#chart-jumlah_pengabdian").slideDown();
                $("#jumlah_pengabdian").slideDown();
                $.skylo('end');
            }
        })
    }

    function jumlahPengabdian(kodeFakultas = "") {
        $.ajax({
            url      : "tridharma/jumlah_pengabdian",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "kode_fakultas"     : kodeFakultas
            },
            beforeSend: function() {
                $("#content-jumlah_pengabdian .data-loader").fadeIn();
                $("#chart-jumlah_pengabdian").slideUp();
                $("#jumlah_pengabdian").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-jumlah_pengabdian").hide();
                $("#jumlah_pengabdian").hide();
                $('#jumlah_pengabdian').html(data);
                if(kodeFakultas){
                    var selectedFakultas =  $("#filter_fakultas_jumlah_pengabdian option:selected").text();
                    $('#fakultasPengabdian').html('Jumlah pengabdian Fakultas' + ' ' + selectedFakultas);
                } else{
                    $('#fakultasPengabdian').html('Jumlah pengabdian seluruh Fakultas');
                }
            },
            complete : function() {
                $("#content-jumlah_pengabdian .data-loader").fadeOut();
                $("#chart-jumlah_pengabdian").slideDown();
                $("#jumlah_pengabdian").slideDown();
                $.skylo('end');
            }
        })
    }

    $("#form-filter_jumlah_penelitian").on('submit', function(event) {
        event.preventDefault();
        var kode_fakultas = $("#filter_fakultas_jumlah_penelitian").val();
        chartJumlahPenelitian(kode_fakultas);
        jumlahPenelitian(kode_fakultas);
        $("#filter-jumlah_penelitian").modal('hide');
    });

    $("#form-filter_jumlah_pengabdian").on('submit', function(event) {
        event.preventDefault();
        var kode_fakultas = $("#filter_fakultas_jumlah_pengabdian").val();
        chartJumlahPengabdian(kode_fakultas);
        jumlahPengabdian(kode_fakultas);
        $("#filter-jumlah_pengabdian").modal('hide');
    });
</script>
@endsection