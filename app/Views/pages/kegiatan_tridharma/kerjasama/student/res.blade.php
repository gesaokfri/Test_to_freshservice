<div class="table-responsive">
  <table id="datatable-student_InboundOutbound" class="table table-bordered w-100">
    <thead>
        <tr>
            <th>Inbound/Outbound</th>
            <th>Prodi</th>
            <th>Nama</th>
            <th>Institusi</th>
            <th>Negara</th>
            <th>Tahun</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<script>
  $(document).ready(function() {
    var tableAjax = $('#datatable-student_InboundOutbound').DataTable({
      serverSide: true,
      processing: true,
      stateSave : true,
      ajax      : {
        url     : "kerjasama/dtStudentInboundOutbound",
        type    : "POST",
        dataType: "json",
        data    : {   "{{ csrf_token() }}" : "{{ csrf_hash() }}"   }
      },
      columns : [
        {data : 'InbOut'},
        {data : 'Prodi'},
        {data : 'Nama'},
        {data : 'Institusi'},
        {data : 'Negara'},
        {data : 'Tahun'}
      ],
      "order" : [[ 5 , 'desc' ]]
    });

    $(".dataTables_length select").addClass('form-select form-select-sm');
  });
</script>