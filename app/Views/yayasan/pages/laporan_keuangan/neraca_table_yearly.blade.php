<table class="table table-bordered">
    {{-- header table --}}
    <thead class="table-light">
        <tr>
            <th rowspan="2" colspan="3">Keterangan</th>
            <th>{{$lbl_year_now}}</th>
            <th>{{$lbl_year_min1}}</th>
            <th>{{$lbl_year_min2}}</th>
            <th>{{$lbl_year_min3}}</th>
            <th>{{$lbl_year_min4}}</th>
        </tr>
    </thead>

    {{-- isi content body table, berisi perulangan data --}}
    <tbody>
            <tr>
                <td colspan="3">test</td>
                <td align="right">1</td>
                <td align="right">2</td>
                <td align="right">3</td>
                <td align="right">4</td>
            </tr>
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