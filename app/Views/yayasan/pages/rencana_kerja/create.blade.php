<form id="form_add" method="POST" autocomplete="off">
  {!! csrf_field() !!}
  <div class="row">
    
    <div class="col-lg-4 mt-3">
      <label>Group</label>
      <select class="selectbox" name="rencana_kerja_group" data-placeholder="Pilih Group" required>
        <option value="">Pilih Group</option>
        @php $no=0; @endphp
        @foreach(rencana_kerja_group() as $rk)
         <option value="{{$no}}">{{$rk}}</option>
         @php $no++; @endphp
        @endforeach
      </select> 
    </div>

    <div class="col-8 mt-3">
      <label>Rencana Kerja</label>
      <input type="text" class="form-control" name="rencana_kerja_name" placeholder="Masukkan Nama Rencana Kerja" maxlength="100" required>
    </div>
    
    <div class="col-6 mt-3">
      <label>Kegiatan</label>
      <input type="text" class="form-control" name="rencana_kerja_kegiatan" placeholder="Masukkan Kegiatan"  maxlength="255" required>
    </div>

    <div class="col-6 mt-3">
      <label>PIC</label>
      <input type="text" maxlength="100" class="form-control" name="rencana_kerja_pic" placeholder="Masukkan Nama PIC" required>
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
      action("form_add", "Tambah Rencana Kerja ?", "Apakah anda ingin menambah <br/> data Rencana Kerja ?", "warning", 'rencana_kerja/save',refresh,'toast');
  });
</script>