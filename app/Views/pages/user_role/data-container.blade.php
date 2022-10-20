<div id="content-total_mahasiswa">
  <div class="col-12">
      <div class="card border pt-3 h-100">
          <div class="card-header bg-white d-flex" id="div_add_title">
                <div class="">
                    <h5 class="fw-bold">User Role</h5>
                    <span class="text-muted">List data user role</span>
                </div>
                <div class="ms-auto d-flex">
                    <div class="align-self-center me-3 data-loader" style="display: none">
                        <i class="bx bx-loader-circle font-size-24"></i>
                    </div>
                    @if(acc_create(session('level_id'),$id_menu)=="1")
                     <button class="btn btn-outline-primary m-auto" onclick="go_add()"><i class="bx bx-plus"></i> Tambah</button>
                    @endif
                </div>
          </div>
          <div class="card-body">
              <div class="col-12" id="dataUserRole">
                <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                      <tr>
                          <th>Account Type</th>
                          <th>Parent Account Type</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div id="content-add-edit"></div>
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
          url     : "user_role/res",
          type    : "POST",
          dataType: "json",
          data    : {
            "{{csrf_token()}}" : "{{csrf_hash()}}"
          }
        },
        columns : [
          {data : 'account_type'},
          {data : 'parent'},
          {data : 'act',className: "td-action",sortable : false,orderable : false,searchable: false}
        ]

      });
      $(".dataTables_length select").addClass('form-select form-select-sm');
  });

  function get_account_type(id) {
    $.ajax({
      url : "{{base_url()}}/user_role/get_data",
      type : 'post',
      data : {
        "{{csrf_token()}}" : "{{csrf_hash()}}",
        id : id
      },
      success : function(data) {
        $("#content-add-edit").show();
        $("#content-add-edit").html(data);
        $("#dataUserRole").hide();
        $("#breadcrumb_index").hide();
        $('#div_add_title').attr('style','display:none !important');
      }
    });
  }

  function go_add() {
    $.ajax({
      url : "user_role/add",
      type : "POST",
      data : {
        "{{csrf_token()}}" : "{{csrf_hash()}}"
      },
      success : function(data) {
        $("#content-add-edit").show();
        $("#content-add-edit").html(data);
        $("#dataUserRole").hide();
        $("#breadcrumb_index").hide();
        $('#div_add_title').attr('style','display:none !important');
      }
    });
  }

  function go_back() {
    $("#content-add-edit").empty();
    $("#dataUserRole").show();
    $("#breadcrumb_index").show();
    $("#breadcrumb_add_edit").hide();
    $("#breadcrumb_add_edit").html("");
    $("#div_add_title").show();
  }

  function refresh() {
    $("#dataTable").DataTable().ajax.reload();
    $("#content-add-edit").empty();
    $("#content-add-edit").hide();
    $("#dataUserRole").show();
    $("#div_add_title").show();
  }
</script>