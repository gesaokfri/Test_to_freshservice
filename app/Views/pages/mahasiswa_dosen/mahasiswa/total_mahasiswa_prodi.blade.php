<div class="row">
    <div class="col-lg-4 align-self-center">
        <div id="total_mahasiswa_chart" class="apex-charts" dir="ltr"></div>
        <div class="d-flex">
            <span class="fst-italic mx-auto mt-3 mb-0">Grafik prodi mahasiswa</span>
        </div>
    </div>
    <div class="col-lg-8 mt-3 align-self-center">
        <div class="row">
            @foreach ($dataCardMahasiswa as $item)
            @if (empty($_SESSION["totalMahasiswa"]["totalMahasiswaStatus"]))
            <div class="col-12 col-sm-4 mt-3" data-toggle="tooltip" data-placement="left" data-bs-html="true" title="
            Aktif <b>{{ $item["jumlah_aktif"] }}</b> <br>
            Bolos <b>{{ $item["jumlah_bolos"] }}</b> <br>
            Cuti <b>{{ $item["jumlah_cuti"] }}</b> <br>">
            @endif
            <div class="card hoverable p-3 pb-0" style="background: #f3f3f37a; cursor: default;">
                <h6>{{ $item['NamaProdi'] }}</h6>
                <div class="d-flex mt-2 gap-3">
                    <div class="apex-donut-customlegend align-self-center" style="background-color: {{$item['color']}}"></div>
                    <h5 class="align-self-center ms-2 mb-0 fw-bold">{{int_to_rp($item['JmlMhs'])}}</h5>
                    <div class="bg-light p-1 rounded" style="background: #e1e1e1 !important">
                        <span class="align-self-end ms-1" style="margin-top: 2px">
                        @php
                            if ($totalJumlahMahasiswaProdi != 0) {
                                echo number_format(intval($item['JmlMhs'])/intval($totalJumlahMahasiswaProdi)*100, 2, '.', ',');
                            } else {
                                echo 0;
                            }
                        @endphp
                        %</span>
                    </div>
                </div>
            </div>
            @if (empty($_SESSION["totalMahasiswa"]["totalMahasiswaStatus"]))
            </div>
            @endif
            @endforeach
            <div class="col-4 mt-3">
                <div class="card hoverable p-3 pb-0 h-100" style="background: #ffbc902d; cursor: default;">
                    <h6>Total</h6>
                    <div class="d-flex mt-2 gap-3">
                        <div class="apex-donut-customlegend align-self-center" style="background-color: #fcaf17"></div>
                        <h5 class="align-self-center ms-2 mb-0 fw-bold">{{int_to_rp($totalJumlahMahasiswaProdi)}}</h5>
                            <div class="bg-primary bg-soft rounded p-1">
                                <span class="align-self-end ms-1" style="margin-top: 2px">100%</span>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(
        {container:'body', trigger: 'hover', placement:"left"}
        );   
    });

    
    // Total Mahasiswa chart
    var options = {
            chart: {
                    height: 230,
                    type: 'pie',
            }, 
            stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
            series: {!!$jumlahMahasiswaProdi!!},
            labels: {!!$namaProdi!!},
            colors: {!!$color!!},
            legend: {
                    show: false,
            },
            responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                                height: 240
                        },
                        legend: {
                                show: false
                        },
                    }
            }],
            dataLabels: {
                enabled: true,
            },
        }

        var chart = new ApexCharts(
            document.querySelector("#total_mahasiswa_chart"),
            options
        );

        chart.render();
</script>