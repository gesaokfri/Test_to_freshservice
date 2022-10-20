<div class="table-responsive">
  <table id="datatable-faculty_InboundOutbound" class="table table-bordered w-100">
    <thead>
        <tr>
            <th>Inbound/Outbound</th>
            <th>Tipe</th>
            <th>Institusi</th>
            <th>Negara</th>
            <th>Kegiatan</th>
            <th>Nama Visiting Prof</th>
            <th>Dosen Penggerak</th>
            <th>Tahun</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<script>
  $(document).ready(function() {
    var tableAjax = $('#datatable-faculty_InboundOutbound').DataTable({
      serverSide: true,
      processing: true,
      stateSave : true,
      ajax      : {
        url     : "kerjasama/dtFacultyInboundOutbound",
        type    : "POST",
        dataType: "json",
        data    : {   "{{ csrf_token() }}" : "{{ csrf_hash() }}"   }
      },
      columns : [
        {data : 'InbOut'},
        {data : 'Tipe'},
        {data : 'Institusi'},
        {data : 'Negara'},
        {data : 'Kegiatan'},
        {data : 'namaVisiting', sortable : false, orderable : false},
        {data : 'dosenPenggerak'},
        {data : 'Tahun'}
      ],
      "order" : [[ 7 , 'desc' ]]
    });

    $(".dataTables_length select").addClass('form-select form-select-sm');
  });
</script>