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
                    <label>Pilih Tahun</label>
                    <select class="selectbox" name="tahun" id="filter-tahun">
                        <option value="">Seluruh Tahun</option>
                        @foreach ($tahun as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <label class="mt-3">Pilih Quarter</label>
                    <select class="selectbox" name="quarter" id="filter-quarter">
                        <option value="">Seluruh Quarter</option>
                        <option value="q1">Quarter 1</option>
                        <option value="q2">Quarter 2</option>
                        <option value="q3">Quarter 3</option>
                        <option value="q4">Quarter 4</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('headcount')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>