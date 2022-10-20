<div id="content-rasio_dosen_mhs">
    <div class="col-12 mt-3">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                <div id="single-chart_rasiodosenmahasiswa">
                    <h5 class="fw-bold">Rasio Dosen Terhadap Mahasiswa</h5>
                    <span class="text-muted">Rasio dosen terhadap mahasiswa seluruh fakultas <span id="fakultasRasioDosenMhs"></span> <span id="angkatanRasioDosenMhs"></span> <span id="tahunRasioDosenMhs"></span> <span id="bulanRasioDosenMhs"></span> </span>
                </div>
                <div id="compare-chart_rasiodosenmahasiswa" style="display: none">
                    <h5 class="fw-bold">Komparasi Rasio Dosen Terhadap Mahasiswa</h5>
                    <span class="text-muted">Rasio Dosen terhadap Mahasiswa seluruh fakultas <span id="fakultasCompareRasioDosenMhs"></span> <span id="angkatanCompareRasioDosenMhs"></span> </span>
                </div>
                <div class="ms-auto d-flex">
                    <div class="align-self-center me-3 data-loader" style="display: none">
                        <i class="bx bx-loader-circle font-size-24"></i>
                    </div>
                    {{-- <div class="gap-3" id="legend-rasio">
                        <div class="d-flex">
                            <div class="apex-donut-customlegend align-self-center" style="background-color:rgb(100, 165, 255)"></div>
                            <span class="align-self-center ms-2 mb-0">Dosen</span>
                        </div>
                        <div class="d-flex">
                            <div class="apex-donut-customlegend align-self-center" style="background-color:rgb(253, 222, 84)"></div>
                            <span class="align-self-center ms-2 mb-0">Mahasiswa</span>
                        </div>
                    </div> --}}
                    {{-- <button class="btn btn-outline-primary m-auto" data-bs-target="#filter-rasio_dosen_mhs" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button> --}}
                </div>
            </div>
            <div class="card-body">
                {{-- Rasio Dosen Terhadap Mahasiswa --}}
                <div class="col-12" id="rasio_dosen_mahasiswa">
    
                </div>
                
                {{-- Compare Rasio Dosen Terhadap Mahasiswa --}}
                <div class="col-12" id="compare_rasio_dosen_mahasiswa">
    
                </div>
            </div>
        </div>
    </div>
</div>