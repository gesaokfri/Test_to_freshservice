<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_realisasi_Kenaikan_PB">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_realisasi_Kenaikan_PB" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter : Pendapatan & Beban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label>Tipe Filter</label>
                            <select onchange="getTypeChartRealiasi('filter_type_chartRealisasi_Kenaikan_PB')" class="form-select" name="tipeFilter" id="filter_type_chartRealisasi_Kenaikan_PB" required>
                                <option value="">Pilih Filter</option>
                                <option value="tahun">Per 5 Tahun</option>
                                <option value="quarter">Per Quarter Setahun</option>
                                <option value="quater_komparasi">Per Quarter Komparasi</option>
                                <option value="tahun_bulan">Per Tahun & Bulan</option>
                            </select>
                        </div>
                        <div class="col-6" id="div_tahun_chartRealisasi_Kenaikan_PB" style="display:none">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="year" id="filter_year_chartRealisasi_Kenaikan_PB" required>
                        </div>
                        <div id="div_quarter_chartRealisasi_Kenaikan_PB" class="col-6 mt-3" style="display:none">
                            <label>Quarter</label>
                            <select class="form-select" name="quarter" id="filter_quarter_chartRealisasi_Kenaikan_PB">
                                <option value="">Pilih Quarter</option>
                                <option value="Q1">Q1</option>
                                <option value="Q2">Q2</option>
                                <option value="Q3">Q3</option>
                                <option value="Q4">Q4</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3" id="div_costumDate1_chartRealisasi_Kenaikan_PB" style="display:none">
                                <label>Dari</label>
                                <input type="text" class="form-control yearmonthpicker" name="fromDate" id="filter_date1_chartRealisasi_Kenaikan_PB" required>
                            </div> 
                            <div class="col-6 mt-3" id="div_costumDate2_chartRealisasi_Kenaikan_PB" style="display:none">
                                <label>Ke</label>
                                <input type="text" class="form-control yearmonthpicker" name="toDate" id="filter_date2_chartRealisasi_Kenaikan_PB" required>
                            </div>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_realisasi_APB">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_realisasi_APB" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter : Realisasi Terhadap APB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="year" id="filter_year_chartRealisasi_APB" required>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_Posisi_Keuangan">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_Posisi_Keuangan" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter : Posisi Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="year" id="filter_year_chart_Posisi_Keuangan" required>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_capex">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_capex" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter : Aset Tetap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearpicker" name="periode" id="filter_year_chart_Capex" required>
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

{{-- 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211 --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_kassetarakas">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_kassetarakas" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik Kas Setara Kas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <label>Tahun</label>
                        <input type="text" class="form-control yearmonthpicker" name="year" id="filter_year_chartKsk" required>
                    </div>
                    <div class="col-12 mt-3">
                        <label>Tipe Filter</label>
                        <select onchange="getTypeChart('filter_type_investasiKsk','div_quarter_chartKsk','filter_quarter_KasSetaraKas')" class="form-select" name="tipeFilter" id="filter_type_investasiKsk" required>
                            <option value="tahun">Per 5 Tahun</option>
                            <option value="quarter">Per Quarter Setahun</option>
                            <option value="quarter_komparasi">Per Quarter Komparasi</option>
                        </select>
                    </div>
                    <div id="div_quarter_chartKsk" class="col-12 mt-3" style="display:none">
                        <label>Quarter</label>
                        <select class="form-select" name="typechart" id="filter_quarter_KasSetaraKas">
                            <option value="">Pilih Quarter</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_trend_pendapatan">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_trend" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik Trend Pendapatan Investasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="periode" id="filter_periodeTrend" required>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_investasi">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_investasi" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik Investasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <label>Tahun</label>
                        <input type="text" class="form-control yearmonthpicker" name="year" id="filter_year_chartInvestasi" required>
                    </div>
                    <div class="col-12 mt-3">
                        <label>Tipe Filter</label>
                        <select onchange="getTypeChart('filter_type_investasiChart','div_quarter_chartInvestasi','filter_quarter_Investasi')" class="form-select" name="tipeFilter" id="filter_type_investasiChart" required>
                            <option value="tahun">Per 5 Tahun</option>
                            <option value="quarter">Per Quarter Setahun</option>
                            <option value="quarter_komparasi">Per Quarter Komparasi</option>
                        </select>
                    </div>
                    <div id="div_quarter_chartInvestasi" class="col-12 mt-3" style="display:none">
                        <label>Quarter</label>
                        <select class="form-select" name="typechart" id="filter_quarter_Investasi">
                            <option value="">Pilih Quarter</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_pengeluaranvescapex">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_pengeluaranvescapex" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik Pengeluaran Investasi Capex</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="periode" id="filter_periodePenvescapex" required>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_asettetap">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_asettetap" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik Aset Tetap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="periode" id="filter_periodeAsetTetap" required>
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

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-filter_asettetap_konsolidasi">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_asettetap_konsolidasi" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik Investasi CAPEX</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control yearmonthpicker" name="periode" id="filter_periodeAsetTetapKonsolidasi" required>
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
{{-- 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211 --}}