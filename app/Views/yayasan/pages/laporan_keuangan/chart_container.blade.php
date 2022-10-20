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
                                <span>Pendapatan Beban Yayasan</span><br>
                                <span class="text-muted">Dalam miliar rupiah</span>
                                <div id="div_ChartRealisasi1"></div>
                            </div>
                            <div class="col-md-6" id="div_realisasi_Kenaikan_PB">
                                <span>Kenaikan Pendapatan vs Beban</span><br>
                                <span class="text-muted">Dalam persen</span>
                                <div id="div_ChartRealisasi2"></div>
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

    <div class="col-12 mt-3" id="div_realisasi_APB">
        <div class="accordion" id="accordion8">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                Realisasi Terhadap APB
                </button>
            </h2>
                <div id="collapseEight" class="accordion-collapse collapse show" aria-labelledby="headingEight" data-bs-parent="#accordion8">
                    <div class="accordion-body">
                        <div class="d-flex">
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

    <div class="col-12 mt-3" id="div_realisasi_APB">
        <div class="accordion" id="accordion9">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingNine">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                Posisi Keuangan
                </button>
            </h2>
                <div id="collapseNine" class="accordion-collapse collapse show" aria-labelledby="headingNine" data-bs-parent="#accordion9">
                    <div class="accordion-body">
                        <div class="d-flex">
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

    <div class="col-12 mt-3" id="div_ksk">
        <div class="accordion" id="accordion2">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                Kas Setara Kas
                </button>
            </h2>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordion2">
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

    <div class="col-12 mt-3" id="div-trend_investasi">
        <div class="accordion" id="accordion3">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                Trend Pendapatan Investasi
                </button>
            </h2>
                <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordion3">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="text-muted">Dalam miliar rupiah</span>
                            <div class="ms-auto">
                                  <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_trend_pendapatan" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div-trend_investasiChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-3" id="div_cashflow">
        <div class="accordion" id="accordion6">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                Aset Tetap
                </button>
            </h2>
                <div id="collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingSix" data-bs-parent="#accordion6">
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
    
    <div class="col-12 mt-3" id="div-investasi">
        <div class="accordion" id="accordion4">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                Investasi
                </button>
            </h2>
                <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordion4">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="text-muted">Dalam miliar rupiah</span>
                            <div class="ms-auto">
                                    <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_investasi" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div-investasiChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 mt-3" id="div-asettetap_konsolidasi">
        <div class="accordion" id="accordion11">
            <div class="accordion-item">
            <h2 class="accordion-header" id="headingEleven">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="true" aria-controls="collapseEleven">
                Penambahan atau Penurunan Investasi CAPEX
                </button>
            </h2>
                <div id="collapseEleven" class="accordion-collapse collapse show" aria-labelledby="headingEleven" data-bs-parent="#accordion11">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <span class="text-muted">Dalam miliar rupiah</span>
                            <div class="ms-auto">
                                <button class="btn btn-outline-primary my-auto" data-bs-target="#modal-filter_asettetap_konsolidasi" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                            </div>
                        </div>
                        <div id="div-asettetap_konsolidasiChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
