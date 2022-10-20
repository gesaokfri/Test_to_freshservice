<div class="table-responsive">
  <table id="datatable-jumlah_pengabdian" class="table table-bordered w-100">
    <thead>
        <tr>
            <th>Skema Pengabdian</th>
            <th>Judul</th>
            <th>Ketua Pengabdi</th>
            <th>Anggaran (Dalam Jutaan)</th>
            <th>Tahun</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<script>
  $(document).ready(function() {
    var tableAjax = $('#datatable-jumlah_pengabdian').DataTable({
      serverSide: true,
      processing: true,
      stateSave : true,
      ajax      : {
        url     : "tridharma/resjumlahpengabdian",
        type    : "POST",
        dataType: "json",
        data    : {
          "{{ csrf_token() }}" : "{{ csrf_hash() }}"
        }
      },
      columns : [
        {data : 'skemaPengabdian'},
        {data : 'Judul', sortable : false, orderable : false},
        {data : 'namaPengabdi'},
        {data : 'Dana'},
        {data : 'Tahun'}
      ],
      "order" : [[ 4 , 'desc' ]]
    });

    $(".dataTables_length select").addClass('form-select form-select-sm');
  });
</script>