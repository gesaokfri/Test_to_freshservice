<div id="content">
    <div class="col-12">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                  <div class="">
                      <h5 class="fw-bold">{{get_menu($id_menu, 'menu')}}</h5>
                      <span class="text-muted">Data per tahun <span id="anu"></span></span>
                  </div>
                  <div class="ms-auto d-flex">
                      <div class="align-self-center me-3 data-loader" style="display: none">
                          <i class="bx bx-loader-circle font-size-24"></i>
                      </div>
                      <div class="d-flex">
                          <button class="btn btn-outline-primary ms-auto" data-bs-target="#filter-headcount" data-bs-toggle="modal"><i class="bx bx-filter-alt"></i> Filter</button>
                      </div>
                  </div>
            </div>
            <div class="card-body">
                
  
                {{-- Chart Marketing Kompetitor --}}
                <div class="row">
                    <div class="col-12">
                        <div id="chart-container"></div>
                    </div>
                </div>

                {{-- Total Mahasiswa --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered w-100 text-center">
                                <thead>
                                    <tr>
                                        <th colspan="2">Jenis Kelamin</th>
                                        <th rowspan="2">Dosen PHK</th>
                                        <th rowspan="2">Tahun</th>
                                    </tr>
                                    <tr>
                                        <th>Pria</th>
                                        <th>Wanita</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
        var tableAjax = $('#datatable').DataTable({
            serverSide: true,
            processing: true,
            stateSave : true,
            ajax      : {
                url     : "headcount/read",
                type    : "POST",
                dataType: "json",
                data    : {
                "{{csrf_token()}}" : "{{csrf_hash()}}"
                }
            },
            columns : [
                {data : 'jumlahPria'},
                {data : 'jumlahWanita'},
                {data : 'jumlahPhk'},
                {data : 'tahun'},
            ]
        });

        selectbox('filter-headcount');
        ChartHeadcount("","");
    });
      

    function ChartHeadcount(tahun = "", quartile = ""){
        $.ajax({
            url      : "headcount/chart",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}",
                "tahun"             : tahun,
                "quartile"          : quartile,
            },
            before : function() {
                $(".data-loader").fadeIn();
                $.skylo('start');
            },
            success  : function(data) {
                if(tahun == ""){
                    $("#anu").text(new Date().getFullYear());
                } else{
                    $("#anu").text(tahun);
                }
                $('#chart-container').html(data);
            },
            complete : function() {
                $(".data-loader").fadeOut();
                $.skylo('end');
            }
        });
    }
  </script>