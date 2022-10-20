@extends('layout.index')
@section('title', 'Laporan Beasiswa')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Laporan Beasiswa</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ base_url() }}/universitas">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Laporan Beasiswa</li>
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

                                 <!-- Nav tabs -->
                                 <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#penelitian" role="tab" aria-selected="true">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">JENIS BEASISWA</span> 
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#pengabdian" role="tab" aria-selected="false">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">SUMBER DANA BEASISWA</span> 
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="penelitian" role="tabpanel">
                                        <p class="mb-0">
                                            @include('pages.laporan_beasiswa.jenis.data-container')
                                        </p>
                                    </div>
                                    <div class="tab-pane" id="pengabdian" role="tabpanel">
                                        <p class="mb-0">
                                            @include('pages.laporan_beasiswa.sumber_dana.data-container')
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
    
    @include('pages.laporan_beasiswa.filter-modal')

@endsection

@section('script')
<script>
    $(document).ready(function () {
        selectbox();
        chartJenisBeasiswa("", "");
        chartSumberDana("", "");
    });

    function chartJenisBeasiswa(name = "", year ="") {
        $.ajax({
            url      : "laporan_beasiswa/jenisbeasiswa",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "name"              : name,
                "year"              : year
            },
            beforeSend: function() {
                $("#content-jenis .data-loader").fadeIn();
                $("#section-1").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#section-1").hide();
                $('#section-1').html(data);
            },
            complete : function() {
                $("#content-jenis .data-loader").fadeOut();
                $("#section-1").slideDown();
                $.skylo('end');
            }
        })
    }

    function chartSumberDana(name = "", year ="") {
        $.ajax({
            url      : "laporan_beasiswa/sumberdanabeasiswa",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "name"              : name,
                "year"              : year
            },
            beforeSend: function() {
                $("#content-sumberdana .data-loader").fadeIn();
                $("#section-1_sumberdana").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#section-1_sumberdana").hide();
                $('#section-1_sumberdana').html(data);
            },
            complete : function() {
                $("#content-sumberdana .data-loader").fadeOut();
                $("#section-1_sumberdana").slideDown();
                $.skylo('end');
            }
        })
    }

    $("#form-filter_beasiswa").on('submit', function(event) {
        event.preventDefault();
        var program = $("#filter-beasiswa_code").val();
        var year    = $("#filter-beasiswa_year").val();
        chartJenisBeasiswa(program, year);
        $("#modal-jenisBeasiswa").modal('hide');
    });

    $("#form-filter_beasiswa2").on('submit', function(event) {
        event.preventDefault();
        var name = $("#filter-donatur_name").val();
        var year = $("#filter-donatur_year").val();
        chartSumberDana(name, year);
        $("#modal-sumberdanaBeasiswa").modal('hide');
    });
</script>
@endsection