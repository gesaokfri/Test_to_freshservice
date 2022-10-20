<form id="form_edit" method="POST" autocomplete="off" enctype="multipart/form-data">
  {!! csrf_field() !!}
	{{-- save hidden id parameter ('id_user') --}}
  <input type="hidden" name="parameter" value="{{ $data['id_user'] }}">
  <div class="row">
    
    <div class="col-12">
      <label>Photo</label>
      <input type="file" class="form-control" name="photo">
			<input type="hidden" name="hidden_photo" value="{{ $data['photo'] }}">
    </div>
    
    <div class="col-md-8 mt-3">
      <label>Nama Lengkap</label>
      <input type="text" class="form-control" name="name" placeholder="Masukkan nama lengkap" value="{{ $data['name'] }}">
    </div>
    
    <div class="col-md-4 mt-3">
      <label>Role</label>
      <select class="selectbox" name="account_type" id="select-role" data-placeholder="Pilih jenis role">
        <option value=""></option>
        @foreach ($roleList as $item)
          <option value="{{ $item['id_account_type'] }}">{{ $item['account_type'] }}</option>
        @endforeach
      </select>
    </div>
    
    <div class="col-12 mt-3">
      <label>Email</label>
      <input type="text" class="form-control" name="email" placeholder="Masukkan alamat email" value="{{ $data['email'] }}">
    </div>
    
    <div class="col-lg-6 mt-3">
      <label>Username / Nama Pengguna</label>
      <input type="text" class="form-control" name="username" placeholder="Masukkan nama pengguna" value="{{ $data['username'] }}">
    </div>

    <div class="col-lg-6 mt-3">
      <label>Kata Sandi</label>
      <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi">
    </div>

  </div>
  
  <div class="d-flex gap-3 mt-3">
    <button type="reset" class="btn btn-light ms-auto">Reset</button>
    <button type="submit" class="btn btn-primary">Ubah</button>
  </div>

</form>

<script type="text/javascript">
	$(document).ready(function(){
		$("#select-role").val('{{ $data["account_type"] }}').trigger('change');
	});
  $("#form_edit").on("submit", function(event){
      event.preventDefault();
      action("form_edit", "Ubah User?", "Apakah anda yakin untuk mengubah data User?", "warning", 'user_management/update',refresh,'toast');
  });
</script>