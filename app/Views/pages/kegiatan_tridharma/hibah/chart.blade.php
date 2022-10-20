<div class="accordion" id="accordion1">
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Grafik Hibah
        </button>
    </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion1">
            <div class="accordion-body">
                <div class="d-flex">
                    <span class="text-muted fst-italic">Data hibah dalam 5 tahun terakhir (Dalam Jutaan)</span>
                    {{-- <button class="btn btn-outline-primary ms-auto" data-bs-target="#filter-markom" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button> --}}
                </div>
                <div id="column_chart" class="apex-charts" dir="ltr"></div>

                <table class="table table-bordered table-hover table-sm mt-3">
                    <thead>
                        <tr>
                            <th>Skema</th>
                            @foreach ($TableListTahun as $item)
                            <th>{{ $item }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($TableAnggaranHibah as $item)
                            <tr>
                                <td>{{ $item["Skema"] }}</td>
                                @for ($i = 0; $i <= 4; $i++)
                                <td>{{ number_format($item["jumlah_anggaran"][$i]/1000000) }}</td>
                                @endfor
                                <td>
                                @php
                                $totalPerSkema = 0;
                                for ($i=0; $i <= 4; $i++) {
                                $totalPerSkema += $item["jumlah_anggaran"][$i];
                                }
                                @endphp
                                <b>{{ number_format($totalPerSkema/1000000) }}</b>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><h6>Total</h6></td>
                            @foreach ($TableAnggaranPerTahun as $item)
                            <td>
                            <b>{{ number_format($item/1000000) }}</b>
                            </td>
                            @endforeach
                            <td><b>{{ number_format($TableTotalKeseluruhan/1000000) }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
  function number_format (number, decimals, decPoint, thousandsSep) { 
     number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
     var n = !isFinite(+number) ? 0 : +number
     var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
     var sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep
     var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
     var s = ''

     var toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec)
      return '' + (Math.round(n * k) / k)
        .toFixed(prec)
     }

     // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
     s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
     if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
     }
     if ((s[1] || '').length < prec) {
      s[1] = s[1] || ''
      s[1] += new Array(prec - s[1].length + 1).join('0')
     }

     return s.join(dec)
    }

  var options = {
        chart: {
            height: 300,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '35%',
                endingShape: 'rounded'	
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: {!! $chartHibah !!},
        colors: ['#34c38f', '#fcaf17', '#f46a6a'],
        xaxis: {
            categories: {!! $LastFiveYears !!},
        },
        yaxis: {
            title: {
                text: 'Hibah',
                style: {
                    fontWeight:  '500',
                },
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        fill: {
            opacity: 1
    
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return number_format(val)
                }
            }
        }
    }
    
    var chart = new ApexCharts(
        document.querySelector("#column_chart"),
        options
    );
    
    chart.render();
</script>