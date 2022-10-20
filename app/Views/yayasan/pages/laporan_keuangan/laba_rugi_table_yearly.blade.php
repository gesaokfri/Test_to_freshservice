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
                <td colspan="9">{{$list_group['laba_rugi_name']}}</td>
            </tr>
            @foreach($sub_List[$list_group['laba_rugi_id']] as $list_sub)
                <tr>
                    <td>{{$list_sub['laba_rugi_number']}}</td>
                    <td colspan="2">{{$list_sub['laba_rugi_name']}}</td>
                    <td align="right">{{$sub_val_now[$list_sub['laba_rugi_id']]}}</td>
                    <td align="right">{{$sub_val_now[$list_sub['laba_rugi_id']]}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($child_List[$list_sub['laba_rugi_id']] as $list_child)
                    <tr>
                        <td></td>
                        <td>{{$list_child['laba_rugi_number']}}</td>
                        <td>{{$list_child['laba_rugi_name']}}</td>
                        <td align="right">{{$child_val_now[$list_child['laba_rugi_id']]}}</td>
                        <td align="right">{{$child_val_before[$list_child['laba_rugi_id']]}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>

    {{-- footer table --}}
    <tfoot>
        <tr></tr>
    </tfoot>
</table>