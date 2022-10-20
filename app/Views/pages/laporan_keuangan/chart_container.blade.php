<div class="row">
    <div class="col-12">
        <div class="accordion" id="accordionAnu">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnu" aria-expanded="true" aria-controls="collapseAnu">
                        Pendapatan dan Beban
                    </button>
                </h2>
                <div id="collapseAnu" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionAnu">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <div class="ms-auto">
                                    <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_realisasi_Kenaikan_PB" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>Pendapatan Beban UNIKA</span><br/>
                                <span class="text-muted">Dalam miliar rupiah</span>
                                <div id="div_ChartRealisasi_PB_YAJ"></div>
                            </div>
                            <div class="col-md-6" id="div_realisasi_Kenaikan_PB">
                                <span>Kenaikan Pendapatan vs Beban</span><br/>
                                <span class="text-muted">Dalam persen</span>
                                <div id="div_ChartRealisasi_Kenaikan_PB"></div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div id="div_TablePendapatanBeban"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12" id="div_realisasi_APB">
        <div class="accordion" id="accordion3">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                Realisasi Terhadap APB
                </button>
            </h2>
                <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordion3">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="text-muted">Dalam miliar rupiah</span>
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_realisasi_APB" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div_ChartRealisasi_APB"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12" id="div_realisasi_APB">
        <div class="accordion" id="accordion4">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                Posisi Keuangan
                </button>
            </h2>
                <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordion4">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="text-muted">Dalam miliar rupiah</span>
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_Posisi_Keuangan" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div_Chart_Posisi_Keuangan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-12 mt-3" id="div_ksk">
    <div class="accordion" id="accordion9">
        <div class="accordion-item">
        <h2 class="accordion-header" id="headingNine">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
            Kas Setara Kas
            </button>
        </h2>
            <div id="collapseNine" class="accordion-collapse collapse show" aria-labelledby="headingNine" data-bs-parent="#accordion9">
                <div class="accordion-body">
                    <div class="d-flex">
                        <span class="text-muted">Dalam miliar rupiah</span>
                        <div class="ms-auto">
                                <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_kassetarakas" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                        </div>
                    </div>
                    <div id="div-kas_setarakasChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12" id="div_profit">
        <div class="accordion" id="accordion5">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                Profit
                </button>
            </h2>
                <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordion5">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="fst-italic" id="profit_title">Profit</span>
                            <div class="ms-auto">
                                  <button id="btn_filter_profit" class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_cashflow" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                                  
                                  <button id="btn_back_profit" class="btn btn-outline-primary my-auto" onclick="backProfit()" style="display:none"><i class="bx bx-arrow-back"></i> Kembali</button>
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
    <div class="col-12" id="div_capex">
        <div class="accordion" id="accordion8">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                Aset Tetap
                </button>
            </h2>
                <div id="collapseEight" class="accordion-collapse collapse show" aria-labelledby="headingEight" data-bs-parent="#accordion8">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="text-muted">Dalam miliar rupiah</span>
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
<div class="row mt-3">
    <div class="col-12" id="div_capex">
        <div class="accordion" id="accordion9">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                Piutang Aging
                </button>
            </h2>
                <div id="collapseNine" class="accordion-collapse collapse show" aria-labelledby="headingEight" data-bs-parent="#accordion9">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <div class="d-flex flex-column">
                                <h4 class="card-title" id="monthPiutangAging"></h4>
                                <small class="text-muted">Dalam miliar rupiah </small>
                            </div>
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_PiutangAging" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        
                        <table class="table table-hover table-sm mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Aging</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="tablePiutangAging"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>