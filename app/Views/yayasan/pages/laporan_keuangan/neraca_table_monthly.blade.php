<table class="table table-bordered">
    {{-- header table --}}
    <thead class="table-light">
        <tr>
            <th rowspan="2" colspan="3">Keterangan</th>
            <th>Bulan Berjalan</th>
            <th>Bulan Sebelumnnya</th>
            <th>31 Des tahun terdekat</th>
            <th>31 Des 2 tahun sebelumnya</th>
        </tr>
        <tr>
            <th align="center">{{$lbl_year_month_ind}}</th>
            <th align="center">{{$lbl_year_month_before_ind}}</th>
            <th align="center">31 Desember {{$lbl_year_min1}}</th>
            <th align="center">31 Desember {{$lbl_year_min2}}</th>
        </tr>
    </thead>

    {{-- isi content body table, berisi perulangan data --}}
    <tbody>
        @foreach($group_List as $list_group)
            <tr>
                <td colspan="3">{{$list_group['neraca_name']}}</td>
                <td align="right">{{$group_val_now[$list_group['neraca_id']]}}</td>
                <td align="right">{{$group_val_before[$list_group['neraca_id']]}}</td>
                <td align="right"></td>
                <td align="right"></td>
            </tr>
            @foreach($sub_List[$list_group['neraca_id']] as $list_sub)
                <tr>
                    <td>{{$list_sub['neraca_number']}}</td>
                    <td colspan="2">{{$list_sub['neraca_name']}}</td>
                    <td align="right">{{$sub_val_now[$list_sub['neraca_id']]}}</td>
                    <td align="right">{{$sub_val_before[$list_sub['neraca_id']]}}</td>
                    <td align="right"></td>
                    <td align="right"></td>
                </tr>
                @foreach($child_List[$list_sub['neraca_id']] as $list_child)
                    <tr>
                        <td></td>
                        <td>{{$list_child['neraca_number']}}</td>
                        <td>{{$list_child['neraca_name']}}</td>
                        <td align="right">{{$child_val_now[$list_child['neraca_id']]}}</td>
                        <td align="right">{{$child_val_before[$list_child['neraca_id']]}}</td>
                        <td align="right"></td>
                        <td align="right"></td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>

    {{-- footer table --}}
    <tfoot>
        <tr>
            <th colspan="3">Jumah Kewajiban dan Modal</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>