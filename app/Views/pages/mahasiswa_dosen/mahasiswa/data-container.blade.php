<div id="content-total_mahasiswa">
    <div class="col-12">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                  <div id="single-chart">
                      <h5 class="fw-bold">Total Mahasiswa</h5>
                      <span class="text-muted">Jumlah mahasiswa <span id="statusMahasiswa"></span> seluruh fakultas <span id="fakultasMahasiswa"></span> <span id="angkatanMahasiswa"></span> <span id="tahunMahasiswa"></span> <span id="bulanMahasiswa"></span> {{ "(Per " . get_date_indonesia(date("Y-m"), "month_year") . ")" }}</span>
                  </div>
                  <div id="compare-chart" style="display: none">
                      <h5 class="fw-bold">Komparasi Jumlah Mahasiswa</h5>
                      <span class="text-muted">Jumlah Mahasiswa <span id="fakultasCompareMahasiswa"></span> <span id="angkatanCompareMahasiswa"></span> <span id="tahunCompareMahasiswa"></span> <span id="bulanCompareMahasiswa"></span> {{ "(Per " . get_date_indonesia(date("Y-m"), "month_year") . ")" }}</span>
                  </div>
                  <div class="ms-auto d-flex gap-3">
                      <div class="align-self-center me-3 data-loader" style="display: none">
                          <i class="bx bx-loader-circle font-size-24"></i>
                      </div>
                      {{-- <button class="btn btn-outline-primary my-auto" data-bs-target="#compare-total_mhs" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Compare</button>
                      <button class="btn btn-outline-primary my-auto" data-bs-target="#filter-total_mhs" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button> --}}
                  </div>
            </div>
            <div class="card-body">

                {{-- Total Mahasiswa --}}
                <div class="col-12" id="total_mahasiswa">
                    
                </div>

                {{-- Compare Total Mahasiswa --}}
                <div class="col-12" id="compare_total_mahasiswa">
                    
                </div>

            </div>
        </div>
    </div>
</div>