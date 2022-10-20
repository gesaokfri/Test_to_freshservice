<form id="form_add" method="POST" autocomplete="off">
  {!! csrf_field() !!}
  <div class="row">
    
    <div class="col-12">
      <label>Nama Kompetitor</label>
      <input type="text" class="form-control" name="competitor_name" placeholder="Masukkan nama kompetitor" required>
    </div>
    
    <div class="col-lg-6 mt-3">
      <label>Fakultas Kompetitor</label>
      {{-- <select class="selectbox" name="competitor_fakultas" data-placeholder="Pilih fakultas">
        <option>Pilih fakultas</option>
        <option>Anu</option>
      </select> --}}
      <input type="text" class="form-control" name="competitor_fakultas" placeholder="Fakultas Kompetitor" required>
    </div>

    <div class="col-lg-6 mt-3">
      <label>Prodi Kompetitor</label>
      {{-- <select class="selectbox" name="competitor_prodi" data-placeholder="Pilih prodi">
        <option>Pilih prodi</option>
        <option>Anu</option>
      </select> --}}
      <input type="text" class="form-control" name="competitor_prodi" placeholder="Prodi Kompetitor" required>
    </div>

    <div class="col-lg-6 mt-3">
      <label>Value Kompetitor</label>
      <div class="input-group">
        <div class="input-group-text">Rp</div>
        <input type="number" class="form-control" name="competitor_value" placeholder="Masukkan value kompetitor" required>
      </div>
    </div>

    <div class="col-lg-6 mt-3">
      <label>Tahun Akademik</label>
      <div class="input-daterange input-group" id="tahunAkademik" data-date-format="yyyy" data-date-min-view-mode="2" data-date-autoclose="true" data-provide="datepicker" data-date-container="#tahunAkademik">
        <input type="text" class="form-control" name="tahun_akademik" placeholder="Pilih tahun akademik" required>
      </div>
    </div>

  </div>
  
  <div class="d-flex gap-3 mt-3">
    <button type="reset" class="btn btn-light ms-auto">Reset</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>

</form>

<script type="text/javascript">
  $("#form_add").on("submit", function(event){
      event.preventDefault();
      action("form_add", "Tambah Marketing / Kompetitor?", "Apakah anda ingin menambah data Marketing / Kompetitor? ?", "warning", 'marketing_kompetitor/save',refresh,'toast');
  });
</script>