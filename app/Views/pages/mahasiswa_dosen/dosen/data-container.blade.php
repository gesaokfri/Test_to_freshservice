<div id="content-total_dosen">
    <div class="col-12 mt-3">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                  <div id="single-chart_total_dosen">
                      <h5 class="fw-bold">Total Dosen</h5>
                      <span class="text-muted" id="filterDosen"></span><span class="text-muted">{{ " (Per " . get_date_indonesia(date("Y-m"), "month_year") . ")" }}</span>
                  </div>
                  <div id="compare-chart_total_dosen" style="display: none">
                      <h5 class="fw-bold">Komparasi Jumlah Dosen</h5>
                      <span class="text-muted">Jumlah Dosen seluruh fakultas <span id="fakultasCompareDosen"></span> {{ "(Per " . get_date_indonesia(date("Y-m"), "month_year") . ")" }}</span>
                  </div>
                  <div class="ms-auto d-flex">
                      <div class="align-self-center me-3 data-loader" style="display: none">
                          <i class="bx bx-loader-circle font-size-24"></i>
                      </div>
                      {{-- <button class="btn btn-outline-primary m-auto" data-bs-target="#filter-total_dosen" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button> --}}
                  </div>
            </div>
            <div class="card-body">
                {{-- Total Dosen --}}
                <div class="col-12" id="total_dosen">
    
                </div>

                {{-- Compare Total Dosen --}}
                <div class="col-12" id="compare_total_dosen">
                    
                </div>
            </div>
        </div>
    </div>
</div>