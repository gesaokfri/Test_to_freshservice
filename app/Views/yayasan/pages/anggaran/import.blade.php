<style type="text/css">
	.table_black {
      border-collapse: collapse;
    }

    .table_black, .th_black, .tr_black .td_black {
      border: 1px solid black;
      white-space: nowrap;
    }
</style>
<form id="form-import_anggaran" method="POST" autocomplete="off">
  {!! csrf_field() !!}

	<table>
		<tr>
			<td>
				<label>Upload Excel <span style="color:red">*</span></label><br/><br/>
				<input type="file" name="excel_file" id="excel_file_anggaran" required>
			</td>
			<td><br/><br/>
				<button type="button" onclick="import_review()" class="btn btn-primary">Upload</button>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br/>
				- Download sample import <a href="{{base_url('import/sample/xls/sample_anggaran_import.xls')}}" style="color:blue;cursor:pointer;" download>click here</a><br/>
				<div id="resource_data"></div>
			</td>
		</tr>
	</table>
	<div class="row" id="data_import_table"></div>
   
</form>

<script type="text/javascript">
    function save_import(){
    	action("form-import_anggaran", "Import Anggaran", "Apakah anda ingin import data angggaran ?", "warning", 'anggaran/import_save',refresh,'toast');
    }

    function import_review(){
		if( document.getElementById("excel_file_anggaran").files.length == 0 ){
			$('.toast').toast('show');
			$('.toast').addClass('bg-danger bg-soft');
			$('.toast-message').text('No File Selected');
		} else {
			$.ajax({
				url : 'anggaran/import_preview',
				type : 'post',
				dataType : 'html',
				data: new FormData($('#form-import_anggaran')[0]),  // Form ID
				processData: false,
				contentType: false,
				beforeSend : function() {
					$(".loader").show();
				},
				success : function(data) {
					$("#data_import_table").html("");
					$("#data_import_table").html(data);
				},
				complete : function() {
					$(".loader").hide();
				}
			})
		}
	}
</script>