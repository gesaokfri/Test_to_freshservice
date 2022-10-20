{{-- <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="compare-total_mhs">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-compare_mhs">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Compare Total Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Fakultas</label>
                    <select class="form-select" name="fakultascompare" id="compare_fakultas_mahasiswa" onchange="GetProdi(this.value)">
                        <option value="">Seluruh Fakultas</option>
                        @foreach ($fakultas as $item)
                        <option value="{{ $item['KodeFakultas'] }}" @if (!empty($_SESSION["compareTotalMahasiswa"]["compareTotalMahasiswaFakultas"])) @if ($_SESSION["compareTotalMahasiswa"]["compareTotalMahasiswaFakultas"] === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                        @endforeach
                    </select>
                    <div class="mt-3" id="prodiCompareTotalMahasiswa" style="display: none">
                        <label>Prodi</label>
                        <select class="form-select" name="prodicompare" id="compare_prodi_mahasiswa">
                        </select>
                    </div>
                    <div class="mt-3"></div>
                    <label>Tahun Terakhir</label>
                    <select class="form-select" name="tahuncompare" id="compare_tahun_mahasiswa">
                        <option value="">Pilih Tahun Terakhir</option>
                        @foreach ($tahunCompareTotalMahasiswa as $item)
                        <option value="{{ $item }}" @if (!empty($_SESSION["compareTotalMahasiswa"]["compareTotalMahasiswaTahunAngkatan"])) @if ($_SESSION["compareTotalMahasiswa"]["compareTotalMahasiswaTahunAngkatan"] == $item) selected @endif @endif>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_compare('totalMahasiswa')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

{{-- <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-total_mhs">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_mhs">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Total Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="fakultas" id="filter_fakultas_mahasiswa">
                        <option value="">Semua Fakultas</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item['KodeFakultas'] }}" @if (!empty($_SESSION["totalMahasiswa"]["totalMahasiswaFakultas"])) @if ($_SESSION["totalMahasiswa"]["totalMahasiswaFakultas"] === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                        @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <select class="form-select" name="status" id="filter_status_mahasiswa">
                        <option value="">Semua Status</option>
                        @foreach ($status as $item)
                            <option value="{{$item}}" @if (!empty($_SESSION["totalMahasiswa"]["totalMahasiswaStatus"])) @if ($_SESSION["totalMahasiswa"]["totalMahasiswaStatus"] === $item) selected @endif @endif>{{$item}}</option>
                        @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <select class="form-select" name="tahunangkatan" id="filter_tahunangkatan_mahasiswa">
                        <option value="">Seluruh Angkatan</option>
                        @foreach ($angkatan as $item)
                            <option value="{{ $item['TahunAngkatan'] }}" @if (!empty($_SESSION["totalMahasiswa"]["totalMahasiswaTahunAngkatan"])) @if ($_SESSION["totalMahasiswa"]["totalMahasiswaTahunAngkatan"] === $item['TahunAngkatan']) selected @endif @endif>{{ $item['TahunAngkatan'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('totalMahasiswa')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

{{-- <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-total_mhs_baru">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_mhs_baru">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Total Mahasiswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="fakultas" id="filter_fakultas_mahasiswa_baru">
                        <option value="">Semua Fakultas</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item['KodeFakultas'] }}" @if (!empty($_SESSION["totalMahasiswaBaru"]["totalMahasiswaBaruFakultas"])) @if ($_SESSION["totalMahasiswaBaru"]["totalMahasiswaBaruFakultas"] === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('totalMahasiswaBaru')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

{{-- <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-total_dosen">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_dosen">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Total Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="totalDosen" value="jabatan_akademik" id="filter_jabatan_akademik_dosen" @if (empty($_SESSION["totalDosen"])) checked @else @if ($_SESSION["totalDosen"]["totalDosenJabatanAkademik"] == 1 || $_SESSION["totalDosen"]["totalDosenJabatanAkademik"] == "") checked @endif @endif>
                        <label class="form-check-label" for="filter_jabatan_akademik_dosen">
                            Jabatan Akademik
                        </label>
                    </div>
                    <div class="mt-3"></div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="totalDosen" value="fakultas" id="filter_fakultas_dosen" @if(!empty($_SESSION["totalDosen"])) @if($_SESSION["totalDosen"]["totalDosenFakultas"] == 1) checked @endif @endif>
                        <label class="form-check-label" for="filter_fakultas_dosen">
                            Fakultas
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('totalDosen')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

{{-- <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="filter-rasio_dosen_mhs">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-filter_rasio_dosen_mhs">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title">Filter Rasio Dosen Terhadap Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="fakultas" id="filter_fakultas_rasio_dosen_mahasiswa">
                        <option value="">Semua Fakultas</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item['KodeFakultas'] }}" @if (!empty($_SESSION["rasioDosenMahasiswa"])) @if ($_SESSION["rasioDosenMahasiswa"]["rasioDosenMahasiswaFakultas"] === $item['KodeFakultas']) selected @endif @endif>{{ $item['NamaFakultas'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" onclick="reset_filter('rasioDosenMahasiswa')">Reset</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="globalModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan Seluruh Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Pilih Preferensi Tampilan</label>
                <select class="form-select" id="select-preference" onchange="getPreference(this.value)">
                    <option value="0">Default</option>
                    <option value="1" {{ (!empty($_SESSION["globalCompare"])) ? "selected" : "" }}>Perbandingan</option>
                    <option value="2" {{ (!empty($_SESSION["globalFilter"])) ? "selected" : "" }}>Filter</option>
                </select>
                <form id="form-comparefilter_global">
                {!! csrf_field() !!}
                <div class="mt-3" id="appendPreference">
                    Tampilkan data default pada dashboard
                </div>
                </form>
            </div>
        </div>
    </div>
</div>