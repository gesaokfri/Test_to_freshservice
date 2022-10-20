<b class="mt-2">
Total data :  {{$total}}<br/>
Total data yang akan di import :  {{$total_import}} <br/>
@if($total_duplikasi!=0)
	 <span style="color:red">
	 	Warning : Terdapat {{$total_duplikasi}} duplikasi pada database, Data yang sama tidak akan diimport
	 </span><br/>
@endif
</b><br/>
@if($total_import!=0)
<div class="row">
		<div class="col-md-12 mb-3">
				<center>
					<button type="button" onclick="save_import()" class="btn btn-success"><i class="fa fa-upload"></i> Import</button>
				</center>
		</div>
</div>
@endif
<br/><br/>
<div style="overflow-x:auto;overflow-y:auto;max-height:500px;">
	<table class="table_black table" cellspacing="0">
			<tr class="tr_black">
				<td class="td_black" align="center"><br/><b>Tipe</b></td>
				<td class="td_black" align="center"><br/><b>Tahun</b></td>
				<td class="td_black" align="center"><br/><b>No</b></td>
				<td class="td_black" align="center"><br/><b>Group</b></td>
				<td class="td_black" align="center"><br/><b>Rencana Kerja</b></td>
				<td class="td_black" align="center"><br/><b>PIC</b></td>
				<td class="td_black" align="center"><br/><b>Kegiatan</b></td>
			</tr>
			@foreach($import as $imp)
				<tr class="tr_black {{$imp['duplicate']}}">
					<td class="td_black" align="center">{{ $imp['rj_type'] }}</td>
					<td class="td_black" align="center">{{ $imp['rj_tahun'] }}</td>
					<td class="td_black">{{ $imp['rj_no'] }}</td>
					<td class="td_black">{{ $imp['rj_group'] }}</td>
					<td class="td_black" align="right">{{ $imp['rj_rencana'] }}</td>
					<td class="td_black" align="right">{{ $imp['rj_pic'] }}</td>
					<td class="td_black" align="right">{{ $imp['rj_kegiatan'] }}</td>
				</tr>
			@endforeach
	</table>
</div>