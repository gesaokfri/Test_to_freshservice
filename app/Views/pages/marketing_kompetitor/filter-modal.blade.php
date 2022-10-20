<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-markom">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_markom">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Marketing / Kompetitor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="selectbox" id="filter-competitor_name" multiple="multiple" data-placeholder="Pilih kompetitor" data-maximum-selection-length="5">
                        @foreach ($competitorName as $value)
                            <option value="{{ $value["competitor_name"] }}">{{ $value["competitor_name"]  }}</option>
                        @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <select class="selectbox" id="filter-competitor_program" data-placeholder="Pilih prodi">
                        <option></option>
                        @foreach ($competitorProdi as $value)
                            <option value="{{ $value["NamaProdi"] }}">{{ $value["NamaProdi"]  }}</option>
                        @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <select class="selectbox" id="filter-competitor_year" data-placeholder="Pilih tahun terakhir">
                        <option></option>
                        @foreach ($competitorTahunTerakhir as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('marketingkompetitor')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>