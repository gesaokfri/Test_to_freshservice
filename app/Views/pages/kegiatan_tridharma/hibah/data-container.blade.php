<div id="content-total_mahasiswa">
  <div class="col-12">
      <div class="card border pt-3 h-100">
          <div class="card-header bg-white d-flex">
                <div class="">
                    <h5 class="fw-bold">Hibah</h5>
                    <span class="text-muted">List data hibah<span id="hibah"></span> </span>
                </div>
                <div class="ms-auto d-flex">
                    <div class="align-self-center me-3 data-loader" style="display: none">
                        <i class="bx bx-loader-circle font-size-24"></i>
                    </div>
                    @if(acc_create(session('level_id'),$id_menu)=="1")
                      <button class="btn btn-outline-primary m-auto" data-bs-target="#addModal" data-bs-toggle="modal"><i class="bx bx-plus"></i> Tambah</button>
                    @endif
                </div>
          </div>
          <div class="card-body">
              <div class="col-12" id="dataHibah">
                <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                      <tr>
                          <th>Pemberi Hibah</th>
                          <th>Periode</th>
                          <th>Dana Diterima (IDR)</th>
                          <th>Dana Pendamping (IDR)</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                
                <script>
                  $(document).ready(function() {
                      var tableAjax = $('#dataTable').DataTable({
                        serverSide: true,
                        processing: true,
                        stateSave : true,
                        ajax      : {
                          url     : "hibah/res",
                          type    : "POST",
                          dataType: "json",
                          data    : {
                            "{{csrf_token()}}" : "{{csrf_hash()}}"
                          }
                        },
                        columns : [
                          {data : 'hibahName'},
                          {data : 'hibahPeriode'},
                          {data : 'hibahPriceReceived'},
                          {data : 'hibahPriceCompanion'},
                          {data : 'hibahAction',className: "td-action",sortable : false,orderable : false,searchable: false}
                        ]

                      });
                      $(".dataTables_length select").addClass('form-select form-select-sm');
                  });

                  function edit(id){
                    $.ajax({
                        url: "hibah/detail",
                        type: 'POST',
                        dataType: 'JSON',
                        data : {"{{csrf_token()}}" : "{{csrf_hash()}}", parameter : id},
                        success: function(data) {
                            $("#div_add_member").html("");
                            $("#div_add_member_edit").html("");
                            if(data.status=="OK"){
                                $("#hibah_name").val(data.name);
                                $("#hibah_pendamping").val(data.price_companion);
                                $("#hibah_terima").val(data.price_received);
                                $("#jumlah_hibah_pengabdian").val(data.total_pengabdian);
                                $("#jumlah_hibah_penelitian").val(data.total_penelitian);
                                $("#jumlah_hibah_penelitian_pengabdian").val(data.total_penelitian_pegabdian);
                                $("#lembaga").val(data.institution);
                                $("#tahun_semester").val(data.periode);
                                $("#parameter").val(data.id);
                                $("#pic_pengabdian_penelitan").val(data.pic);

                                if(data.member){
                                    var str_member = data.member;
                                    var str_array_member = str_member.split(';');
                                    for(var i = 0; i < str_array_member.length-1; i++) {
                                        var mbr    = str_array_member[i];
                                        rest_member('div_add_member_edit',mbr);
                                    }
                                }

                                $("#updateModal").modal('show');
                            }
                            else{
                                Swal.fire({
                                    title: 'Terjadi Kesalahan',
                                    html: data.message,
                                    icon: 'error',
                                  }).then((result) => {
                                    $("#updateModal").modal('hide');
                               })
                            }
                        }
                    });
                  }
                </script>
              </div>

          </div>
      </div>
  </div>
</div>

