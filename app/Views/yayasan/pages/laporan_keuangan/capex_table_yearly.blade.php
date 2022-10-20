<table class="table table-bordered">
    {{-- header table --}}
    <thead class="table-light">
        <tr>
            <th align="center" rowspan="2" colspan="3">KETERANGAN</th>
            <th align="center">Bulan Berjalan</th>
            <th align="center">Bulan Sebelumnnya</th>
            <th align="center">YTD thn berjalan</th>
            <th align="center">YTD thn lalu</th>
            <th align="center">Anggaran tahun berjalan</th>
            <th align="center">Anggaran tahun sebelumnya</th>
        </tr>
        <tr>
            <th align="center">{{$lbl_year_month_ind}}</th>
            <th align="center">{{$lbl_year_month_before_ind}}</th>
            <th align="center">Januari - {{$lbl_year_month_ind}}</th>
            <th align="center">Januari - {{$lbl_ytd_before_ind}}</th>
            <th align="center">APB {{$lbl_year_now}}</th>
            <th align="center">APB {{$lbl_year_min1}}</th>
        </tr>
    </thead>

    {{-- isi content body table, berisi perulangan data --}}
    <tbody>
        @foreach($group_List as $list_group)
            <tr>
                <td>{{$list_group['capex_number']}}</td>
                <td colspan="2">{{$list_group['capex_name']}}</td>
                <td align="right">{{$group_val_now[$list_group['capex_id']]}}</td>
                <td align="right">{{$group_val_before[$list_group['capex_id']]}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>

    {{-- footer table --}}
    <tfoot>
        <tr></tr>
    </tfoot>
</table>