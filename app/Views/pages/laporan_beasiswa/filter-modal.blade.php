{{-- modal filter sumber dana beasiswa --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-jenisBeasiswa">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_beasiswa">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Tahun Jenis Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="selectbox" id="filter-beasiswa_code" multiple="multiple" data-placeholder="Pilih beasiswa" data-maximum-selection-length="5">
                        @foreach ($beasiswaCode as $value)
                            <option value="{{ $value["BeasiswaCode"] }}">{{ beacode_to_progname($value["BeasiswaCode"], "program_name")  }}</option>
                        @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <select class="selectbox" id="filter-beasiswa_year">
                        <option value="">Seluruh periode</option>
                        @foreach ($beasiswaYear as $value)
                            <option value="{{ $value["Tahun"] }}">{{ $value["Tahun"] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('jenisbeasiswa')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- modal filter sumber dana beasiswa --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-sumberdanaBeasiswa">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_beasiswa2">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Tahun Sumber Dana Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="selectbox" id="filter-donatur_name" multiple="multiple" data-placeholder="Pilih donatur" data-maximum-selection-length="5">
                        @foreach ($beasiswaDonatur as $value)
                            <option value="{{ $value["DonaturName"] }}">{{ $value["DonaturName"]  }}</option>
                        @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <select class="selectbox" id="filter-donatur_year">
                        <option value="">Seluruh periode</option>
                        @foreach ($donaturYear as $value)
                            <option value="{{ $value["Tahun"] }}">{{ $value["Tahun"] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('sumberdanabeasiswa')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>