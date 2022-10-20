<form id="form_edit" method="POST" autocomplete="off">
  {!! csrf_field() !!}
  <input type="hidden" name="parameter" value="{{$data['budget_id']}}" id="parameter">
  <div class="row">
  
    <div class="col-lg-2 mt-3">
      <label>Tahun & Bulan</label>
      <input type="text" class="form-control" name="budget_period" placeholder="Masukkan Tahun & Bulan" value="{{$data['budget_period']}}">
    </div>

    <div class="col-lg-4 mt-3">
      <label>Tipe</label>
      <select class="form-control" name="tipe" id="tipe" required>
        <option value="">Pilih Tipe</option>
        <option value="1">Pendapatan</option>
        <option value="2">Beban</option>
      </select>
    </div>

    <div class="col-lg-6 mt-3">
      <label>Value</label>
      <div class="input-group">
        <div class="input-group-text">Rp</div>
        <input type="number" class="form-control" name="budget_value" placeholder="Masukkan value" value="{{$data['budget_value']}}">
      </div>
    </div>


  </div>
  
  <div class="d-flex gap-3 mt-3">
    <button type="button" class="btn btn-light ms-auto" onclick="refresh()">Reset</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>

</form>

<script type="text/javascript">
  $("#tipe").val("{{$data['budget_type']}}").trigger('change');
  $("#form_edit").on("submit", function(event){
      event.preventDefault();
      action("form_edit", "Ubah Anggaran ?", "Apakah anda ingin mengubah data Anggaran ? ?", "warning", 'anggaran/update',refresh,'toast');
  });
</script>