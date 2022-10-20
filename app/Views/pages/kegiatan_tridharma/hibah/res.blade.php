<div id="content-total_mahasiswa">
    <div class="col-12">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                <div class="ms-auto d-flex">
                    <div class="align-self-center me-3 data-loader" style="display: none">
                        <i class="bx bx-loader-circle font-size-24"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">

                {{-- Chart Hibah Penelitian --}}
                <div class="row">
                    <div class="col-12" id="chart-hibah_penelitian">
                        
                    </div>
                </div>

                {{-- Data Hibah --}}
                <div class="row mt-4">
                    <div class="col-12" id="dataHibah">
                      <table id="dataTable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                              <th>Institusi Pendana</th>
                              <th>Periode</th>
                              <th>Anggaran (Dalam Jutaan)</th>
                              <th>Ketua Peneliti/Pengabdi</th>
                              <th>Tahun</th>
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

<script>
  $(document).ready(function() {
    var tableAjax = $('#dataTable').DataTable({
      serverSide: true,
      processing: true,
      stateSave : true,
      ajax      : {
        url     : "hibah/read",
        type    : "POST",
        dataType: "json",
        data    : {
          "{{csrf_token()}}" : "{{csrf_hash()}}"
        }
      },
      columns : [
        {data : 'hibahInstitusi'},
        {data : 'hibahPeriode'},
        {data : 'hibahDana', sortable : false, orderable : false},
        {data : 'hibahPIC'},
        {data : 'hibahTahun'}
      ],
      "order" : [[ 4 , 'desc' ]]
    });
    
    $(".dataTables_length select").addClass('form-select form-select-sm');
    
    chartHibah();
  });

  function chartHibah() {
    $.ajax({
      url      : "hibah/chart",
      type     : "POST",
      dataType : "html",
      data     : {
          "{{ csrf_token() }}": "{{ csrf_hash() }}"
      },
      success  : function(data) {
          $('#chart-hibah_penelitian').html(data);
      }
    })
  }
</script>