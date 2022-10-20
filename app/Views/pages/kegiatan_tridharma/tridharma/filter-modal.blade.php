<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-jumlah_penelitian">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_jumlah_penelitian">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Jumlah Penelitian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="fakultas" id="filter_fakultas_jumlah_penelitian">
                        <option value="">Semua Fakultas</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item['KodeFakultas'] }}" @if (!empty($_SESSION["jumlahPenelitian"]["jumlahPenelitianFakultas"])) @if ($_SESSION["jumlahPenelitian"]["jumlahPenelitianFakultas"] === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('jumlahPenelitian')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-jumlah_pengabdian">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_jumlah_pengabdian">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Jumlah Pengabdian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="fakultas" id="filter_fakultas_jumlah_pengabdian">
                        <option value="">Semua Fakultas</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item['KodeFakultas'] }}" @if (!empty($_SESSION["jumlahPengabdian"]["jumlahPengabdianFakultas"])) @if ($_SESSION["jumlahPengabdian"]["jumlahPengabdianFakultas"] === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('jumlahPengabdian')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>