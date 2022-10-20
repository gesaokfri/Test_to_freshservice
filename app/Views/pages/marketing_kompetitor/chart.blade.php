<div class="accordion" id="accordion1">
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Grafik Marketing / Kompetitor
        </button>
    </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion1">
            <div class="accordion-body">
                <div class="d-flex">
                    <span class="text-muted fst-italic">Data marketing/kompetitor @if (!empty($chartNamaProdi)) {{ "Prodi " .  $chartNamaProdi . " (" . $LFY[0] . " - " . $LFY[4] . ")" }} @endif </span>
                    <button class="btn btn-outline-primary ms-auto" data-bs-target="#filter-markom" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                </div>
                <div id="column_chart" class="apex-charts" dir="ltr"></div>
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
      series: {!! $chartMarketingCompetitor !!},
      colors: ['#34c38f', '#fcaf17', '#f46a6a', '#85929E'],
      xaxis: {
          categories: {!! $LastFiveYears !!},
      },
      yaxis: {
          title: {
              text: 'Rp. (Rupiah)',
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
                return val
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