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
				<td class="td_black" rowspan="2" align="center"><br/><b>No</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>Nama Hibah</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>Periode</b></td>
				<td class="td_black" colspan="3" align="center"><br/><b>Jumlah Hibah</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>Dana Yang Diterima (IDR)</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>Dana Pendamping (IDR)</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>Lembaga Pemberi Dana</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>PIC Pengabdian/Penelitian</b></td>
				<td class="td_black" rowspan="2" align="center"><br/><b>Member Pengabdian/Penelitian</b></td>
			</tr>
			<tr class="tr_black">
				<td class="td_black"><b>Penelitian Direncanakan/Dilakukan</b></td>
				<td class="td_black"><b>Pengabdian Direncanakan/Dilakukan</b></td>
				<td class="td_black"><b>Penelitian dan Pengabdian Dipublikasikan</b></td>
			</tr>
			@foreach($import as $imp)
				<tr class="tr_black {{$imp['duplicate']}}">
					<td class="td_black" align="center">{{ $imp['no'] }}</td>
					<td class="td_black">{{ $imp['nama'] }}</td>
					<td class="td_black">{{ $imp['periode'] }}</td>
					<td class="td_black" align="right">{{ $imp['jmlh_penelitian_direncanakan_dilakukan'] }}</td>
					<td class="td_black" align="right">{{ $imp['jmlh_pengabdian_direncanakan_dilakukan'] }}</td>
					<td class="td_black" align="right">{{ $imp['jmlh_penelitian_pengabdian_direncanakan_dilakukan'] }}</td>
					<td class="td_black" align="right">{{ $imp['dana_terima'] }}</td>
					<td class="td_black" align="right">{{ $imp['dana_pendamping'] }}</td>
					<td class="td_black">{{ $imp['lembaga'] }}</td>
					<td class="td_black">{{ $imp['pic'] }}</td>
					<td class="td_black">{{ $imp['member'] }}</td>
				</tr>
			@endforeach
	</table>
</div>