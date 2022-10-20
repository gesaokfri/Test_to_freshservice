<form id="form_edit" method="POST" autocomplete="off">
  {!! csrf_field() !!}
  <input type="hidden" name="parameter" value="{{$data['rencana_kerja_id']}}">
  <div class="row">
    
    <div class="col-lg-4 mt-3">
      <label>Group</label>
      <select class="selectbox" name="rencana_kerja_group" id="rencana_kerja_group" data-placeholder="Pilih Group" required>
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
      <input type="text" class="form-control" name="rencana_kerja_name" placeholder="Masukkan Nama Rencana Kerja" maxlength="100" value="{{$data['rencana_kerja_name']}}" required>
    </div>
    
    <div class="col-6 mt-3">
      <label>Kegiatan</label>
      <input type="text" class="form-control" name="rencana_kerja_kegiatan" placeholder="Masukkan Kegiatan"  maxlength="255" value="{{$data['rencana_kerja_kegiatan']}}" required>
    </div>

    <div class="col-6 mt-3">
      <label>PIC</label>
      <input type="text" maxlength="100" class="form-control" name="rencana_kerja_pic" placeholder="Masukkan Nama PIC" value="{{$data['rencana_kerja_pic']}}" required>
    </div>
    

  </div>
  
  <div class="d-flex gap-3 mt-3">
    <button type="reset" class="btn btn-light ms-auto">Reset</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>

</form>

<script type="text/javascript">
  $("#rencana_kerja_group").val("{{$data['rencana_kerja_group']}}");
  $("#form_edit").on("submit", function(event){
      event.preventDefault();
      action("form_edit", "Ubah Rencana Kerja ?", "Apakah anda ingin mengubah <br/> data Rencana Kerja ?", "warning", 'rencana_kerja/update',refresh,'toast');
  });
</script>