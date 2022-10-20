<div id="content-jumlah_pengabdian">
   
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border pt-3 h-100">
                <div class="card-header bg-white d-flex">
                        <div class="">
                            <h5 class="fw-bold">Jumlah Pengabdian</h5>
                            <span class="text-muted"><span id="fakultasPengabdian"></span></span>
                        </div>
                        <div class="ms-auto d-flex">
                            <div class="align-self-center me-3 data-loader" style="display: none">
                                <i class="bx bx-loader-circle font-size-24"></i>
                            </div>
                            <button class="btn btn-outline-primary m-auto" data-bs-target="#filter-jumlah_pengabdian" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                        </div>
                </div>
                <div class="card-body">

                    {{-- Chart Jumlah Pengabdian --}}
                    <div class="row">
                        <div class="col-12" id="chart-jumlah_pengabdian">
                            
                        </div>
                    </div>

                    {{-- Jumlah Pengabdian --}}
                    <div class="row mt-4">
                        <div class="col-12" id="jumlah_pengabdian">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>