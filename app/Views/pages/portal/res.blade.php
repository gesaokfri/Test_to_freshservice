{{-- <div class="col-12">
  <div class="row">
    <h5>Data per bulan Februari - Maret 2022</h5>
    <div class="col-lg-6">
      <div class="cashflow-item card border p-3">
        <div class="row my-auto">
          <div class="col-lg-6">
            <div class="d-flex">
              <img src="{{ base_url('assets/images/design/portal') }}/arrow.svg" alt="" class="profit arrow-up">
              <div class="d-flex flex-column align-self-center">
                <span>Profit</span>
                <span class="cashflow-value">{{$cashflow}}%</span>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex">
              <img src="{{ base_url('assets/images/design/portal') }}/arrow.svg" alt="" class="bank arrow-down">
              <div class="d-flex flex-column align-self-center">
                <span>Kas dan Bank</span>
                <span class="cashflow-value">{{$cashbank}}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card border">
        <div class="d-flex gap-3 justify-content-center my-auto">
          <div class="align-self-center d-flex flex-column text-start">
            <span class="me-auto align-self-center font-size-18">Current Ratio</span>
          </div>
          <div class="align-self-center display-5 fw-bold">{{$ratio}}</div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card border p-0">
        <div class="d-flex justify-content-around my-auto">
          <div class="align-self-center p-3">
            <img src="{{ base_url('assets/images/design/portal') }}/employee.svg" alt="" class="img-fluid" width="50">
          </div>
          <div class="align-self-center ">
            <span>Total karyawan Atma Jaya</span>
            <h1 class="fw-bold text-center">{{$karyawan}}</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-lg-9">
      <div class="card border d-flex">
        <div id="column_chart" class="apex-charts my-auto" dir="ltr"></div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card border p-0 mb-0">
        <div class="card-body">
          <div id="aktivitas_chart" class="apex-charts" dir="ltr"></div>
          <hr class="my-4">
          <div class="d-flex flex-column gap-3">
            <div class="d-flex gap-2">
              <i class="mdi mdi-circle" style="color: #f15a22"></i>
              <span>Sisa Hasil Usaha</span>
            </div>
            <div class="d-flex gap-2">
              <i class="mdi mdi-circle" style="color: #00a79d"></i>
              <span>Pendapatan</span>
            </div>
            <div class="d-flex gap-2">
              <i class="mdi mdi-circle" style="color: #ed1a3b"></i>
              <span>Beban</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  
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
            columnWidth: '18%',
            endingShape: 'rounded',
            distributed: true
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
    series: [{
        name: 'Jumlah Dana',
        data: {!!$columnchart!!}
        }
    ],
    colors: ['#00a79d', '#f15a22', '#4a6a95', '#ed1a3b'],
    xaxis: {
            categories: [
                ['Aset Lancar'], 
                ['Aset Tidak Lancar'], 
                ['Kewajiban'], 
                ['Modal'],
            ]
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
            var result =  val/1000000;
            return "Rp" + result + " Juta"
        }
      }
        
    },
    legend: {
      itemMargin: {
          horizontal: 15,
          vertical: 30
      },
      markers: {
        width: 12,
        height: 12,
        radius: 12,
        customHTML: undefined,
        onClick: undefined,
        offsetX: 0,
        offsetY: 0
      }
    },
    yaxis: {
        title: {
            text: 'Rupiah (Jutaan)',
            style: {
                fontWeight:  '500',
            },
        },
        labels: {
            formatter: function (val) {
                var result =  val/1000000;
                return result;
            }
        }
    }
    }

    var chart = new ApexCharts(
    document.querySelector("#column_chart"),
    options
    );

    chart.render();

    var options = {
    chart: {
        height: 220,
        type: 'pie',
    }, 
    stroke: {
        show: false
    },
    series: [44, 105, 41],
    labels: ["Sisa Hasil Usaha", "Pendapatan", "Beban"],
    colors: ["#f15a22", "#00a79d","#ed1a3b"],
    legend: {
        show: false,
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 140
            },
            legend: {
                show: false
            },
        }
    }]

    }

    var chart = new ApexCharts(
    document.querySelector("#aktivitas_chart"),
    options
    );

    chart.render();

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
  
  function number_format_short( n, precision = 1 ) {
      var n_format=0;
      var suffix=0;
      if (n < 900) {
          // 0 - 900
          n_format = number_format(n, precision);
          suffix = '';
      } else if (n < 900000) {
          // 0.9k-850k
          n_format = number_format(n / 1000, precision);
          suffix = 'K';
      } else if (n < 900000000) {
          // 0.9m-850jt
          n_format = number_format(n / 1000000, precision);
          suffix = 'Jt';
      } else if (n < 900000000000) {
          // 0.9b-850m
          n_format = number_format(n / 1000000000, precision);
          suffix = 'M';
      } else {
          // 0.9t+
          n_format = number_format(n / 1000000000000, precision);
          suffix = 'T';
      }

      if ( precision > 0 ) {
          var dotzero = '.'.repeat(precision);
          n_format = n_format.replace(dotzero+"0",'');
      }
      return n_format+" "+suffix;
    }   
</script> --}}