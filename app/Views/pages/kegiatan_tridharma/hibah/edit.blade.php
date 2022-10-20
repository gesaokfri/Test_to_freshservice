<form id="form_edit" method="POST" autocomplete="off">
  {!! csrf_field() !!}
  <input type="hidden" name="parameter" id="parameter" required>

    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">Pemberi Hibah</small></label>
            <input type="text" name="hibah_name" value="{{$data['hibah_name']}}" maxlength="150" class="form-control" required>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">Periode Hibah</small></label>
            <select name="tahun_semester" value="{{$data['hibah_periode']}}" class="form-select" data-placeholder="Pilih tahun semester" required>
                <option>Pilih Periode Hibah</option>
                @foreach($tahun_semester as $item)
                <option value="{{$item['kode_semester']}}">{{get_semester($item['kode_semester'])}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">Jumlah Hibah penelitian yang dilakukan/direncanakan</small></label>
            <input type="text" name="jumlah_hibah_penelitian" value="{{$data['hibah_total_penelitian']}}" class="form-control" required>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">Jumlah Hibah pengabdian yang dilakukan/direncanakan</small></label>
            <input type="text" name="jumlah_hibah_pengabdian" value="{{$data['hibah_total_pengabdian']}}" class="form-control" required>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">Jumlah Hibah penelitian & pengabdian yang dipublikasikan</small></label>
            <input type="text" name="jumlah_hibah_penelitian_pengabdian" value="{{$data['hibah_total_penelitian_pengabdian']}}" class="form-control" required>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-6">
            <label><small class="text-uppercase">Dana Yang Diterima (IDR)</small></label>
            <input type="text" name="hibah_terima" value="{{$data['hibah_price_received']}}" class="form-control price" required>
        </div>
        <div class="col-md-6">
            <label><small class="text-uppercase">Dana Pendamping (IDR)</small></label>
            <input type="text" name="hibah_pendamping" value="{{$data['hibah_price_companion']}}" class="form-control price" required>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">Lembaga Pemberi Dana</small></label>
            <input type="text" name="lembaga" value="{{$data['hibah_institution']}}" maxlength="150" class="form-control" required>
        </div>
    </div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <label><small class="text-uppercase">PIC Pengabdian/Penelitian</small></label>
            <input type="text" name="pic_pengabdian_penelitan" value="{{$data['hibah_pic']}}" maxlength="150" class="form-control" required>
        </div>
    </div>
    <div id="div_add_member_edit"></div>
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-12">
            <center>
                <button onclick="add_member('div_add_member_edit')" type="button" class="btn btn-success">
                    <i class="bx bx-plus"></i> Tambah Member
                </button>
            </center>
        </div>
    </div>

    <button type="button" class="btn btn-light" onclick="refresh()">Reset</button>
    <button type="submit" class="btn btn-primary">Proses</button>
</form>

<script type="text/javascript">
  $("#form_edit").on("submit", function(event){
        event.preventDefault();
        action("form_edit", "Ubah Hibah", "Apakah anda ingin mengubah data hibah ?", "warning", 'hibah/update',refresh,'toast');
    });
</script>