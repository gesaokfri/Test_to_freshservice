<table class="table table-hover table-sm mt-3">

  @if($filter_type=="tahun")
      <thead class="table-light">
          <tr>
              <th>Keterangan</th>
              @foreach ($tableListHeader as $item)
                <th class="text-end">{{ $item }}</th>
              @endforeach
          </tr>
      </thead>
      <tbody>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>{{ $item["name"] }}</td>
              @for ($i = 0; $i <= 4; $i++)
              <td class="text-end">
              @php
                if ($item["real"][$i] < 1) {
                  echo round($item["real"][$i], 2);
                } else {
                  echo round($item["real"][$i]);
                }
                @endphp
              </td>
              @endfor
            </tr>
           @endforeach
            <tr>
              <td colspan="6">
                <span class="fst-italic text-muted">Pertumbuhan terhadap tahun sebelumnya</span>
              </td>
            </tr>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>% {{ $item["name"] }}</td>
                @for ($i = 0; $i <= 4; $i++)
                <td class="text-end">{{ $item["persen"][$i] }} %</td>
                @endfor
            </tr>
            @endforeach
      </tbody>

  @elseif($filter_type=="quarter")
      <thead class="table-light">
          <tr>
              <th>Keterangan</th>
              @foreach ($tableListHeader as $item)
                <th class="text-end">{{ $item }}</th>
              @endforeach
          </tr>
      </thead>
      <tbody>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>{{ $item["name"] }}</td>
              @for ($i = 0; $i <= 3; $i++)
              <td class="text-end">
              @php
              if ($item["real"][$i] < 1) {
                echo round($item["real"][$i], 2);
              } else {
                echo round($item["real"][$i]);
              }
              @endphp
              </td>
              @endfor
            </tr>
           @endforeach
            <tr>
              <td colspan="6"><br/></td>
            </tr>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>% {{ $item["name"] }}</td>
                @for ($i = 0; $i <= 3; $i++)
                <td class="text-end">{{ $item["persen"][$i] }} %</td>
                @endfor
            </tr>
            @endforeach
      </tbody>
    @elseif ($filter_type=="quater_komparasi")
    <thead class="table-light">
          <tr>
              <th>Keterangan</th>
              @foreach ($tableListHeader as $item)
                <th class="text-end">{{ $item }}</th>
              @endforeach
          </tr>
      </thead>
      <tbody>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>{{ $item["name"] }}</td>
              @for ($i = 0; $i <= 4; $i++)
              <td class="text-end">
              @php
              if ($item["real"][$i] < 1) {
                echo round($item["real"][$i], 2);
              } else {
                echo round($item["real"][$i]);
              }
              @endphp
              </td>
              @endfor
            </tr>
           @endforeach
            <tr>
              <td colspan="6"><br/></td>
            </tr>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>% {{ $item["name"] }}</td>
                @for ($i = 0; $i <= 4; $i++)
                <td class="text-end">{{ $item["persen"][$i] }} %</td>
                @endfor
            </tr>
            @endforeach
      </tbody>
    @elseif($filter_type=="tahun_bulan")
      <thead class="table-light">
          <tr>
              <th>Keterangan</th>
              @foreach ($tableListHeader as $item)
                <th class="text-end">{{ $item }}</th>
              @endforeach
          </tr>
      </thead>
      <tbody>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>{{ $item["name"] }}</td>
              @for ($i = 0; $i < $countMonth; $i++)
              <td class="text-end">
              @php
              if ($item["real"][$i] < 1) {
                echo number_format(round($item["real"][$i], 2));
              } else {
                echo number_format(round($item["real"][$i]));
              }
              @endphp
              </td>
              @endfor
            </tr>
           @endforeach
            <tr>
              <td colspan="6">
                <span class="fst-italic text-muted">Pertumbuhan terhadap tahun sebelumnya</span>
              </td>
            </tr>
            @foreach ($tablePendapatanBeban as $item)
            <tr>
              <td>% {{ $item["name"] }}</td>
                @for ($i = 0; $i < $countMonth; $i++)
                <td class="text-end">{{ $item["persen"][$i] }} %</td>
                @endfor
            </tr>
            @endforeach
      </tbody>
   @endif
</table>