<form id="form_add" method="POST" autocomplete="off" enctype="multipart/form-data">
  {!! csrf_field() !!}
  <div class="row">
    
    <div class="col-12">
      <label>Photo</label>
      <input type="file" class="form-control" name="photo">
    </div>
    
    <div class="col-md-8 mt-3">
      <label>Nama Lengkap</label>
      <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" required>
    </div>
    
    <div class="col-md-4 mt-3">
      <label>Role</label>
      <select class="selectbox" name="account_type" data-placeholder="Pilih jenis role">
        <option value=""></option>
        @foreach ($roleList as $item)
          <option value="{{ $item['id_account_type'] }}">{{ $item['account_type'] }}</option>
        @endforeach
      </select>
    </div>
    
    <div class="col-12 mt-3">
      <label>Email</label>
      <input type="text" class="form-control" name="email" placeholder="Masukkan alamat email" required>
    </div>
    
    <div class="col-lg-6 mt-3">
      <label>Username / Nama Pengguna</label>
      <input type="text" class="form-control" name="username" placeholder="Masukkan nama pengguna" required>
    </div>

    <div class="col-lg-6 mt-3">
      <label>Kata Sandi</label>
      <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi" required>
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
      action("form_add", "Tambah User?", "Apakah anda yakin untuk menambah data User?", "warning", 'user_management/store',refresh,'toast');
  });
</script>