<b class="mt-2">
Total data :  {{$total}}<br/>
Total data yang akan di import :  {{$total_import}} <br/>
@if($total_duplikasi!=0)
	 <span style="color:red">
	 	Warning : Terdapat {{$total_duplikasi}} duplikasi NIK pada database, NIK yang sama tidak akan diimport
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
				<td class="td_black" align="center"><br/><b>No</b></td>
				<td class="td_black" align="center"><br/><b>Nama Kompetitor</b></td>
				<td class="td_black" align="center"><br/><b>Nama Fakultas</b></td>
				<td class="td_black" align="center"><br/><b>Nama Program Studi</b></td>
				<td class="td_black" align="center"><br/><b>Biaya hingga Lulus</b></td>
				<td class="td_black" align="center"><br/><b>Tahun</b></td>
			</tr>
			@foreach($import as $imp)
				<tr class="tr_black {{$imp['duplicate']}}">
					<td class="td_black" align="center">{{ $imp['no'] }}</td>
					<td class="td_black">{{ $imp['competitor_name'] }}</td>
					<td class="td_black">{{ $imp['competitor_fakultas'] }}</td>
					<td class="td_black" align="right">{{ $imp['competitor_jurusan'] }}</td>
					<td class="td_black" align="right">{{ $imp['competitor_value'] }}</td>
					<td class="td_black" align="right">{{ $imp['tahun_akademik'] }}</td>
				</tr>
			@endforeach
	</table>
</div>