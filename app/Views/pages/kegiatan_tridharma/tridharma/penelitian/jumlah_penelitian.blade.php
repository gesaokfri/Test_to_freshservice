<div class="table-responsive">
  <table id="datatable" class="table table-bordered w-100">
    <thead>
        <tr>
            <th>Skema Penelitian</th>
            <th>Judul</th>
            <th>Ketua Peneliti</th>
            <th>Anggaran (Dalam Jutaan)</th>
            <th>Tahun</th>
        </tr>
    </thead>
  </table>
</div>

<script>
  $(document).ready(function() {
    var tableAjax = $('#datatable').DataTable({
      serverSide: true,
      processing: true,
      stateSave : true,
      ajax      : {
        url     : "tridharma/resjumlahpenelitian",
        type    : "POST",
        dataType: "json",
        data    : {
          "{{csrf_token()}}" : "{{csrf_hash()}}"
        }
      },
      columns : [
        {data : 'skemaPenelitian'},
        {data : 'Judul', sortable : false, orderable : false},
        {data : 'namaPeneliti'},
        {data : 'Dana'},
        {data : 'Tahun'}
      ],
      "order" : [[ 4 , 'desc' ]]
    });
    
    $(".dataTables_length select").addClass('form-select form-select-sm');
  });
</script>