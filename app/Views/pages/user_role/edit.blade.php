<script type="text/javascript">
  var breadcumb = `<ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/beranda">Dashboard</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item" style="cursor:pointer" onclick="go_back()">User Role</li>
                    <li class="breadcrumb-item active">Ubah User Role</li>
                </ol>`;
  $("#breadcrumb_add_edit").show();
  $("#breadcrumb_add_edit").html(breadcumb);
</script>
<div class="row">
		<div class="col-12">
			<div class="d-flex">
				<div class="">
						<h5 class="fw-bold">Ubah User Role</h5>
						<span class="text-muted">Ubah data user role</span>
				</div>
				<div class="ms-auto d-flex">
					<button type="button" id="btn_back" class="btn btn-light waves-effect waves-light align-self-center ms-auto" onclick="go_back()"><i class="bx bx-left-arrow-alt"></i> Kembali</button><br><br>
				</div>
			</div>
		</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<form id="form_edit_user_role" autocomplete="off">
				<input type="hidden" name="parameter" id="parameter" value="{{$id}}">
				{!! csrf_field() !!}
				<div class="card-body">
					<div class="row">
			            <div class="col-md-5">
			              <div class="mb-3">
			                <label class="form-label">Account Type <span class="text-danger">*</span></label>
			                <input type="text" placeholder="Account Type" name="account_type" class="form-control" required id="account_type_edit" value="{{$account}}">
			              </div>
			            </div>
			            <div class="col-md-5">
			              <div class="mb-3">
			                <label class="form-label">Parent Account Type</label>
			                <select name="parent" class="form-select" id="parent_account_edit">
			                  <option value="">-- Choose Parent --</option>
			                  @foreach($parent_account as $data)
			                  	<option value="{{$data->id_account_type}}">{{$data->account_type}}</option>
			                  @endforeach
			                </select>
			              </div>
			            </div>
			            <div class="col-md-2">
			               <div class="form-group"><br/>
			                 <button id="btn_check" onclick="check_all()" type="button" class="btn btn-info waves-effect waves-light m-1"><i class="fa fa-check"></i>  Check All
			                </button>
			              </div>
			            </div>
		            </div>
		            <div class="row">
			          	<div class="col-md-12">
			          		<table id="tt" class="easyui-treegrid fixedtable" style="width: 100%; height: 400px;" 
			          		data-options="
			                url: '{{base_url('')}}/user_role/get_data_update?id_parameter={{ $id ?? '' }}&{{csrf_token()}}={{csrf_hash()}}',
			                method: 'get',
			                idField: 'id',
			                treeField: 'menu',
			                onLoadSuccess:function(){$('.easyui-treegrid').treegrid('expandAll');}
			            	">
			                <thead>
			                  <tr>
			                    <th data-options="field:'menu'" width="40%">Menu</th>
			                    <th data-options="field:'read'" width="10%">
			                      <center>Baca</center></th>
			                    <th data-options="field:'add'" width="10%">
			                      <center>Tambah</center></th>
			                    <th data-options="field:'edit'" width="10%">
			                      <center>Ubah</center></th>
			                    <th data-options="field:'delete'" width="10%">
			                      <center>Hapus</center></th>
			                    <th data-options="field:'upload'" width="10%">
			                      <center>Unggah<center></th>
			                    <th data-options="field:'download'" width="10%">
			                      <center>Unduh<center></th>
			                    <th data-options="field:'check_all'" width="5%">
			                      <center>Semua<center></th>
			                    <th data-options="field:'uncheck_all'" width="5%">
			                      <center>Bersihkan<center></th>
			                  </tr>
			                </thead>
			              </table>
			          	</div>
			       </div>
				</div>
				<div class="card-footer bg-transparent border-top text-muted d-flex">
					<button type="submit" class="btn btn-primary waves-effect waves-light ms-auto"><i class="bx bx-save"></i> Ubah Data</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript" src="{{base_url('')}}/assets/libs/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
	$("#parent_account_edit").val("{{$parent_id}}");
	$("#form_edit_user_role").on("submit", function(event){
		event.preventDefault();
		action("form_edit_user_role", "Ubah User Role", "Apakah anda ingin mengubah User Role?", "question", 'user_role/save', refresh,'toast');
	});
  function check_all(){
      $("#btn_check").attr("class","btn btn-danger");
      $("#btn_check").attr("onclick","uncheck_all()");
      $("#btn_check").html('<i class="fa fa-times"></i>&nbsp; Uncheck All');
      $('input:checkbox').prop('checked',true);
  }
  function uncheck_all(){
    $("#btn_check").attr("class","btn btn-info");
    $("#btn_check").attr("onclick","check_all()");
    $("#btn_check").html('<i class="fa fa-check"></i>&nbsp; Check All');
    $('input:checkbox').prop('checked',false);
  }
  function check_row(men_id) {
    $('#read_'+men_id).prop('checked', true);
    $('#add_' +men_id).prop('checked', true);
    $('#edit_'+men_id).prop('checked', true);
    $('#delete_'+men_id).prop('checked', true);
    $('#upload_'+men_id).prop('checked', true);
    $('#download_'+men_id).prop('checked', true);
  }
  function uncheck_row(men_id) {
    $('#read_'+men_id).prop('checked', false);
    $('#add_'+men_id).prop('checked', false);
    $('#edit_'+men_id).prop('checked', false);
    $('#delete_'+men_id).prop('checked', false);
    $('#upload_'+men_id).prop('checked', false);
    $('#download_'+men_id).prop('checked', false);
  }
</script>