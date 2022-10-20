<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-headcount">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-headcount">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Marketing / Kompetitor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label>Tipe Filter</label>
                            <select onchange="getTipeFilter('filter-tipe','quarter_div','filter-quarter')" class="form-select" name="tipeFilter" id="filter-tipe" required>
                                <option value="tahun">Per 5 Tahun</option>
                                <option value="quarter">Per Quarter Setahun</option>
                                {{-- <option value="compare-quarter">Per Quarter Komparasi</option> --}}
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Jangka Waktu</label>
                            <select class="form-select" name="tahun" id="filter-tahun">
                                <option value="">Seluruh Tahun</option>
                                @foreach ($tahun as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="quarter_div" class="col-6 mt-3" style="display:none">
                            <label>Quarter</label>
                            <select class="form-select" name="quarter" id="filter-quarter">
                                <option value="">Pilih Quarter</option>
                                <option value="03">Q1</option>
                                <option value="06">Q2</option>
                                <option value="09">Q3</option>
                                <option value="12">Q4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" id="btn-reset-hc">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-pb-konsolidasi">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-pb-konsolidasi" autocomplete="off">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter : Pendapatan & Beban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="tipe-filter-container">
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
                            <input placeholder="Pilih tahun" type="text" class="form-control yearmonthpicker" name="year" id="filter-year-pb-konsolidasi" required>
                        </div>
                        <div id="filter-quarter-pb-container" class="col-12 mt-3" style="display:none">
                            <label>Quarter</label>
                            <select class="form-select" name="quarter" id="filter_quarter_chartRealisasi_Kenaikan_PB">
                                <option value="">Pilih Quarter</option>
                                <option value="Q1">Q1</option>
                                <option value="Q2">Q2</option>
                                <option value="Q3">Q3</option>
                                <option value="Q4">Q4</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3" id="div_costumDate1_chartRealisasi_Kenaikan_PB" style="display:none">
                            <div class="input-group input-daterange">
                                <div class="d-flex flex-column w-50">
                                    <label>Dari</label>
                                    <input type="text" class="form-control" placeholder="Pilih bulan dan tahun" id="filter_date1_chartRealisasi_Kenaikan_PB" name="fromDate">
                                </div>
                                <div class="d-flex flex-column w-50">
                                    <label>Ke</label>
                                    <input type="text" class="form-control" placeholder="Pilih bulan dan tahun" id="filter_date2_chartRealisasi_Kenaikan_PB" name="toDate">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" id="btn-reset-pb">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
  </div>