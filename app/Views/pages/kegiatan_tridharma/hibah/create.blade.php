<form id="form_add" method="POST" autocomplete="off">
  {!! csrf_field() !!}

	<div class="row">
		<div class="col-lg-8">
			<label>Pemberi Hibah</label>
			<input type="text" name="hibah_name" maxlength="150" class="form-control" required>
		</div>
		<div class="col-lg-4">
			<label>Periode Hibah</label>
			<select name="tahun_semester" class="form-select" data-placeholder="Pilih tahun semester" required>
				<option value="">Pilih Periode Hibah</option>
				@foreach($tahun_semester as $item)
				<option value="{{$item['kode_semester']}}">{{get_semester($item['kode_semester'])}}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-lg-4">
			<label>Jumlah Hibah Penelitian Direncanakan/Dilakukan</label>
			<input type="text" name="jumlah_hibah_penelitian" class="form-control" required>
		</div>
		<div class="col-lg-4">
			<label>Jumlah Hibah Pengabdian Direncanakan/Dilakukan</label>
			<input type="text" name="jumlah_hibah_pengabdian" class="form-control" required>
		</div>
		<div class="col-lg-4">
			<label>Jumlah Hibah Penelitian & Pengabdian Dipublikasikan</label>
			<input type="text" name="jumlah_hibah_penelitian_pengabdian" class="form-control" required>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-lg-6">
			<label>Dana Yang Diterima (IDR)</label>
			<input type="text" name="hibah_terima" class="form-control price" required>
		</div>
		<div class="col-lg-6">
			<label>Dana Pendamping (IDR)</label>
			<input type="text" name="hibah_pendamping" class="form-control price" required>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-lg-12">
			<label>Lembaga Pemberi Dana</label>
			<input type="text" name="lembaga" maxlength="150" class="form-control" required>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-lg-12">
			<label>PIC Pengabdian/Penelitian</label>
			<input type="text" name="pic_pengabdian_penelitan" maxlength="150" class="form-control" required>
		</div>
	</div>

	<div class="row mt-3">
		<div class="col-lg-12">
			<label>Member Pengabdian/Penelitian</label>
			<input type="text" name="member_pengabdian_penelitan[]" maxlength="150" class="form-control" required>
		</div>
	</div>
	
	<div id="div_add_member"></div>
	
	<div class="col-lg-12">
		<center>
			<button onclick="add_member('div_add_member')" type="button" class="btn btn-success">
				<i class="bx bx-plus"></i> Tambah Member
			</button>
		</center>
	</div>

    <div class="d-flex gap-3 mt-4">
        <button type="button" class="btn btn-light" onclick="refresh()">Reset</button>
        <button type="submit" class="btn btn-primary">Proses</button>
    </div>
</form>

<script type="text/javascript">
    $("#form_add").on("submit", function(event){
        event.preventDefault();
        action("form_add", "Tambah Hibah", "Apakah anda ingin menambah data hibah ?", "warning", 'hibah/save',refresh,'toast');
    });
</script>