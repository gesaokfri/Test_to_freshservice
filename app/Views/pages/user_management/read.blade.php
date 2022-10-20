<div id="content-total_mahasiswa">
  <div class="col-12">
      <div class="card border pt-3 h-100">
          <div class="card-header bg-white d-flex" id="div_add_title">
                <div class="">
                    <h5 class="fw-bold">User Management</h5>
                    <span class="text-muted">List data user management</span>
                </div>
                <div class="ms-auto d-flex">
                    <div class="align-self-center me-3 data-loader" style="display: none">
                        <i class="bx bx-loader-circle font-size-24"></i>
                    </div>
                    @if(acc_create(session('level_id'),$id_menu)=="1")
                      <button id="btnAdd" onclick="Go('{{get_menu($id_menu, 'link')}}', '{{get_menu($id_menu, 'menu')}}', 'create', 'Tambah')" class="btn btn-outline-primary m-auto me-2"><i class="bx bx-plus"></i> Tambah</button>
                    @endif
                </div>
          </div>
          <div class="card-body">
              <div class="col-12">
                <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                      <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Tipe User</th>
                        <th>Email</th>
                        <th>Aksi</th>
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


<script>
  $(document).ready(function() {
      var tableAjax = $('#dataTable').DataTable({
        serverSide: true,
        processing: true,
        stateSave : true,
        ajax      : {
          url     : "user_management/read",
          type    : "POST",
          dataType: "json",
          data    : {
            "{{csrf_token()}}" : "{{csrf_hash()}}"
          }
        },
        columns : [
          {data : 'photo'},
          {data : 'name'},
          {data : 'account_type'},
          {data : 'email'},
          {data : 'act',className: "td-action",sortable : false,orderable : false,searchable: false}
        ]

      });
      $(".dataTables_length select").addClass('form-select form-select-sm');
  });
</script>