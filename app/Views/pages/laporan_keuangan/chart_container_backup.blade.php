<div class="row">
    <div class="col-6" id="div_neraca">
        <div class="accordion" id="accordion1">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Grafik Neraca
                </button>
            </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion1">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_neraca" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div_neracaChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6" id="div_labarugi">
        <div class="accordion" id="accordion2">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                Grafik Laba Rugi
                </button>
            </h2>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordion2">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span style="cursor:pointer;display:none;"  style="display:none;" title="Back" onclick="backPieLabaRugi()" id="remarkPieLabaRugi" class="text-muted fst-italic"></span>
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_labarugi" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div_laba_rugiChart"></div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12" id="div_cashflow">
        <div class="accordion" id="accordion3">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                Grafik Profit
                </button>
            </h2>
                <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordion3">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_cashflow" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div_cashflowChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12" id="div_cashflow">
        <div class="accordion" id="accordion4">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                Grafik Capital Expenditure (CAPEX)
                </button>
            </h2>
                <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordion4">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_capex" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div_capexChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>