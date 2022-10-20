@extends('yayasan.layout.index')
@section('title','Laporan Keuangan')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Laporan Keuangan (Dalam Jutaan)</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/yayasan">Dashboard</a></li>
                                    <li class="breadcrumb-item">Laporan Keuangan</li>
                                    <li class="breadcrumb-item active">Data</li>
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#neraca" role="tab" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-file"></i></span>
                                                <span class="d-none d-sm-block">Neraca</span> 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#laba_rugi" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="fas fa-file"></i></span>
                                                <span class="d-none d-sm-block">Laba Rugi</span> 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#cashflow" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="fas fa-file"></i></span>
                                                <span class="d-none d-sm-block">Cashflow</span> 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#capex" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="fas fa-file"></i></span>
                                                <span class="d-none d-sm-block">CAPEX</span> 
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="neraca" role="tabpanel">
                                            <div class="row">
		                                        <div class="col-12" id="div_neracaTable"></div>
		                                    </div>
                                        </div>
                                        <div class="tab-pane" id="laba_rugi" role="tabpanel">
                                            <div class="row">
		                                        <div class="col-12" id="div_labarugiTable"></div>
		                                    </div>
                                        </div>
                                        <div class="tab-pane" id="cashflow" role="tabpanel">
                                            <div class="row">
		                                        <div class="col-12" id="div_cashflowTable"></div>
		                                    </div>
                                        </div>
                                        <div class="tab-pane" id="capex" role="tabpanel">
                                            <div class="row">
		                                        <div class="col-12" id="div_capexTable"></div>
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

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_neraca">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="form-filter_neraca" autocomplete="off">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <h5 class="modal-title">Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label>Periode</label>
                                <input type="text" class="form-control yearmonthpicker" name="periode" id="filter_periodeNeraca" placeholder="Masukkan periode" required>
                            </div>
                            <div class="col-6">
                                <label>Grafik</label>
                                <select class="form-select" name="typechart" id="filter_typechartNeraca" required>
                                    <option value="bar">Bar Chart</option>
                                    <!--<option value="">Pilih Grafik</option>-->
                                    <!--<option value="pie">Pie Chart</option>-->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" style="display: none" >Reset</button>
                        <button type="submit" class="btn btn-primary">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="floating-filter">
        <button class="btn btn-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#modal-filter_neraca"><i class="mdi mdi-filter"></i></button>
    </div>
    
@endsection

@section('script')
<script>
    TableRes();
    function TableRes(divId,period,chartType) {
        $.ajax({
            url: "laporan_keuangan/neraca-table",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period, chart : chartType},
            success: function(res) {
                $("#div_neracaTable").html(res);
            }
        });

        $.ajax({
            url: "laporan_keuangan/laba_rugi-table",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period, chart : chartType},
            success: function(res) {
                $("#div_labarugiTable").html(res);
            }
        });

        $.ajax({
            url: "laporan_keuangan/cashflow-table",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period, chart : chartType},
            success: function(res) {
                $("#div_cashflowTable").html(res);
            }
        });

        $.ajax({
            url: "laporan_keuangan/capex-table",
            type: 'POST',
            dataType: 'html',
            data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : period, chart : chartType},
            success: function(res) {
                $("#div_capexTable").html(res);
            }
        });
    }
</script>
@endsection