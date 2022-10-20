<div id="content-total_mahasiswa_baru">
    <div class="col-12 mt-3">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                  <div id="single-chart_total_mahasiswa_baru">
                      <h5 class="fw-bold">Total Calon Mahasiswa Baru</h5>
                      <span class="text-muted">Jumlah Calon mahasiswa baru seluruh fakultas <span id="fakultasMahasiswaBaru"></span> <span id="angkatanMahasiswaBaru"></span> {{ "(Per " . get_date_indonesia(date("Y-m"), "month_year") . ")" }}</span>
                  </div>
                  <div id="compare-chart_total_mahasiswa_baru" style="display: none">
                      <h5 class="fw-bold">Komparasi Jumlah Calon Mahasiswa Baru</h5>
                      <span class="text-muted">Jumlah Calon Mahasiswa Baru <span id="fakultasCompareMahasiswaBaru"></span> {{ "(Per " . get_date_indonesia(date("Y-m"), "month_year") . ")" }}</span>
                  </div>
                  <div class="ms-auto d-flex">
                      <div class="align-self-center me-3 data-loader" style="display: none">
                          <i class="bx bx-loader-circle font-size-24"></i>
                      </div>
                      {{-- <button class="btn btn-outline-primary m-auto" data-bs-target="#filter-total_mhs_baru" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button> --}}
                  </div>
            </div>
            <div class="card-body">
                {{-- Total Mahasiswa Baru --}}
                <div class="col-12" id="total_mahasiswa_baru">
    
                </div>

                {{-- Compare Total Mahasiswa Baru --}}
                <div class="col-12" id="compare_total_mahasiswa_baru">
                    
                </div>
            </div>
        </div>
    </div>
</div>