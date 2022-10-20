<form id="form_edit" method="POST" autocomplete="off">
  {!! csrf_field() !!}
  <input type="hidden" name="parameter" id="parameter">
  <div class="row">
    
    <div class="col-12">
      <label>Nama Kompetitor</label>
      <input type="text" class="form-control" name="competitor_name" placeholder="Masukkan nama kompetitor" value="{{$data['competitor_name']}}">
    </div>

    <div class="col-lg-6 mt-3">
      <label>Fakultas Kompetitor</label>
      <input type="text" class="form-control" name="competitor_fakultas" placeholder="Masukkan nama fakultas" value="{{ $data["competitor_fakultas"] }}">
    </div>

    <div class="col-lg-6 mt-3">
      <label>Prodi Kompetitor</label>
      <input type="text" class="form-control" name="competitor_prodi" placeholder="Masukkan nama prodi" value="{{ $data["competitor_jurusan"] }}">
    </div>

    <div class="col-lg-6 mt-3">
      <label>Value Kompetitor</label>
      <div class="input-group">
        <div class="input-group-text">Rp</div>
        <input type="number" class="form-control" name="competitor_value" placeholder="Masukkan value kompetitor" value="{{$data['competitor_value']}}">
      </div>
    </div>

    <div class="col-lg-6 mt-3">
      <label>Tahun Akademik</label>
      <div class="input-daterange input-group" id="tahunAkademik" data-date-format="yyyy" data-date-min-view-mode="2" data-date-autoclose="true" data-provide="datepicker" data-date-container="#tahunAkademik">
        <input type="number" class="form-control" name="tahun_akademik" placeholder="Pilih tahun akademik" value="{{$data['tahun_akademik']}}">
      </div>
    </div>

  </div>
  
  <div class="d-flex gap-3 mt-3">
    <button type="button" class="btn btn-light ms-auto" onclick="refresh()">Reset</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>

</form>

<script type="text/javascript">
  $("#form_edit").on("submit", function(event){
      event.preventDefault();
      action("form_edit", "Ubah Marketing / Kompetitor?", "Apakah anda ingin mengubah data Marketing / Kompetitor? ?", "warning", 'marketing_kompetitor/update',refresh,'toast');
  });
</script>