<div id="content-rencana_kerja_detail">
    <div class="col-12">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                  <div class="">
                      <h5 class="fw-bold">Detail Rencana Kerja</h5>
                  </div>
                  <div class="ms-auto d-flex">
                      <div class="align-self-center me-3 data-loader" style="display: none">
                          <i class="bx bx-loader-circle font-size-24"></i>
                      </div>
                      @if(acc_create(session('level_id'),$id_menu)=="1")
                        <button id="btnAdd" type="button" data-bs-toggle="modal" data-bs-target="#modal-add" class="btn btn-outline-primary m-auto"><i class="bx bx-plus"></i> Tambah</button>
                      @endif
                  </div>
            </div>
            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>Quarter</th>
                                        <th>Pencapaian</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ajax load --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-add">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="form_add" method="POST" autocomplete="off">
                  {!! csrf_field() !!}
                  <input type="hidden" name="rencana_kerja_id" value="{{$parameter}}">
                  <div class="modal-header">
                        <h5 class="modal-title">Tambah Detail Rencana Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                        <div class="col-6">
                          <label>Pencapaian</label>
                          <input type="text" class="form-control" name="rencana_kerja_detail_pencapaian" placeholder="Masukkan Nama Rencana Kerja" maxlength="80" required>
                        </div>
                        
                        <div class="col-6">
                          <label>Status</label>
                          <input type="text" class="form-control" name="rencana_kerja_detail_status" placeholder="Masukkan Status"  maxlength="100" required>
                        </div>

                        <div class="col-12 mt-3">
                          <label>Catatan</label>
                          <textarea class="form-control" name="rencana_kerja_detail_catatan" placeholder="Masukkan Catatan" required></textarea>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="reset" class="btn btn-light ms-auto">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
            </div>
        </div>
    </div> 

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-edit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="form_edit" method="POST" autocomplete="off">
                  {!! csrf_field() !!}
                  <input type="hidden" name="parameter" id="parameter">
                  <div class="modal-header">
                        <h5 class="modal-title">Ubah Detail Rencana Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                        <div class="col-6">
                          <label>Pencapaian</label>
                          <input type="text" class="form-control" name="rk_dt_pencapaian" id="rencana_kerja_detail_pencapaian" placeholder="Masukkan Nama Rencana Kerja" maxlength="80" required>
                        </div>
                        
                        <div class="col-6">
                          <label>Status</label>
                          <input type="text" class="form-control" name="rk_dt_status" id="rencana_kerja_detail_status" placeholder="Masukkan Status"  maxlength="100" required>
                        </div>

                        <div class="col-12 mt-3">
                          <label>Catatan</label>
                          <textarea class="form-control" name="rk_dt_catatan" id="rencana_kerja_detail_catatan" placeholder="Masukkan Catatan" required></textarea>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="reset" class="btn btn-light ms-auto">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-view">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">Detail Rencana Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                        
                        <div class="col-6">
                          <label>Quarter</label><br>
                          <span id="view_rencana_kerja_detail_quarter"></span>
                        </div>

                        <div class="col-6">
                          <label>Verifikasi</label><br>
                          <span id="view_rencana_kerja_detail_verifikasi"></span>
                        </div>

                        <div class="col-6 mt-3">
                          <label>Pencapaian</label><br>
                          <span id="view_rencana_kerja_detail_pencapaian"></span>
                        </div>
                        
                        <div class="col-6 mt-3">
                          <label>Status</label><br/>
                          <span id="view_rencana_kerja_detail_status"></span>
                        </div>

                        <div class="col-12 mt-3">
                          <label>Catatan</label><br/>
                          <span id="view_rencana_kerja_detail_catatan"></span>
                        </div>
                      </div>
                  </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal-verif">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="form_verif" method="POST" autocomplete="off">
                  {!! csrf_field() !!}
                  <input type="hidden" name="parameter" id="parameter_verif">
                  <div class="modal-header">
                        <h5 class="modal-title">Verifikasi Rencana Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                        <div class="col-6">
                          <label>Quarter</label><br>
                          <span id="verif_rencana_kerja_detail_quarter"></span>
                        </div>

                        <div class="col-6">
                          <label>Verifikasi</label><br>
                          <span id="verif_rencana_kerja_detail_verifikasi"></span>
                        </div>

                        <div class="col-6 mt-3">
                          <label>Pencapaian</label><br>
                          <span id="verif_rencana_kerja_detail_pencapaian"></span>
                        </div>
                        
                        <div class="col-6 mt-3">
                          <label>Status</label><br/>
                          <span id="verif_rencana_kerja_detail_status"></span>
                        </div>

                        <div class="col-12 mt-3">
                          <label>Catatan</label><br/>
                          <span id="verif_rencana_kerja_detail_catatan"></span>
                        </div>

                        <div class="col-12 mt-3">
                          <label>Verifikasi</label><br/>
                          <label>
                            <input type="radio" name="verifikasi" value="1" required> Ya
                          </label> 
                            &nbsp;
                          <label>   
                           <input type="radio" name="verifikasi" value="2" required> Revisi
                          </label> 
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="reset" class="btn btn-light ms-auto">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </form>
            </div>
        </div>
    </div>

  
<script>
    $("#form_add").on("submit", function(event){
          event.preventDefault();
          action("form_add", "Tambah Detail Rencana Kerja ?", "Apakah anda ingin menambah <br/> data Detail Rencana Kerja ?", "warning", 'rencana_kerja_detail/save',refresh,'toast');
    });
    $("#form_edit").on("submit", function(event){
          event.preventDefault();
          action("form_edit", "Ubah Detail Rencana Kerja ?", "Apakah anda ingin mengubah <br/> data Detail Rencana Kerja ?", "warning", 'rencana_kerja_detail/update',refresh,'toast');
    });
    $("#form_verif").on("submit", function(event){
          event.preventDefault();
          action("form_verif", "Verifikasi Detail Rencana Kerja ?", "Apakah anda ingin verifikasi <br/> data Detail Rencana Kerja ?", "warning", 'rencana_kerja_detail/verify',refresh,'toast');
    });
    $(document).ready(function() {
        var tableAjax = $('#datatable').DataTable({
            serverSide: true,
            processing: true,
            stateSave : true,
            ajax      : {
                url     : "rencana_kerja_detail/read",
                type    : "POST",
                dataType: "json",
                data    : {
                    "{{csrf_token()}}" : "{{csrf_hash()}}",
                    "parameter" : "{{$parameter}}"
                }
            },
            columns : [
                {data : 'rkQuarter'},
                {data : 'rkPencapaian'},
                {data : 'rkStatus'},
                {data : 'rkVerifikasi'},
                {data : 'rkAction',className: "text-center td-action",sortable : false,orderable : false,searchable: false}
            ]
        });
    });
    function edit(id){
         $.ajax({
          url : "rencana_kerja_detail/edit",
          type : "post",
          dataType : 'json',
          data : {
            "{{csrf_token()}}" : "{{csrf_hash()}}",
            "parameter" : id
          },
          success : function(res) {
            $("#rencana_kerja_detail_pencapaian").val("");
            $("#rencana_kerja_detail_catatan").val("");
            $("#rencana_kerja_detail_status").val("");
            $("#parameter").val("");
            if(res.status=="OK"){
                $("#modal-edit").modal('show');
                $("#parameter").val(res.id);
                $("#rencana_kerja_detail_pencapaian").val(res.pencapaian);
                $("#rencana_kerja_detail_status").val(res.status_rk);
                $("#rencana_kerja_detail_catatan").val(res.catatan);
            }
            else {
               $("#modal-edit").modal('hide');
            }
          }
        });
    }
    function detail(id,typeview){
         $.ajax({
          url : "rencana_kerja_detail/edit",
          type : "post",
          dataType : 'json',
          data : {
            "{{csrf_token()}}" : "{{csrf_hash()}}",
            "parameter" : id
          },
          success : function(res) {
            if(typeview=="verifikasi"){
                $("#verif_rencana_kerja_detail_quarter").html("");
                $("#verif_rencana_kerja_detail_verifikasi").html("");
                $("#verif_rencana_kerja_detail_pencapaian").html("");
                $("#verif_rencana_kerja_detail_status").html("");
                $("#verif_rencana_kerja_detail_catatan").html("");
                $("#parameter_verif").val("");
                if(res.status=="OK"){
                    $("#modal-verif").modal('show');
                    $("#parameter_verif").val(res.id);
                    $("#verif_rencana_kerja_detail_quarter").html(res.quarter);
                    $("#verif_rencana_kerja_detail_verifikasi").html(res.verifikasi);
                    $("#verif_rencana_kerja_detail_pencapaian").html(res.pencapaian);
                    $("#verif_rencana_kerja_detail_status").html(res.status_rk);
                    $("#verif_rencana_kerja_detail_catatan").html(res.catatan);
                }
                else {
                   $("#modal-verif").modal('hide');
                }
            }
            else {
                $("#view_rencana_kerja_detail_quarter").html("");
                $("#view_rencana_kerja_detail_verifikasi").html("");
                $("#view_rencana_kerja_detail_pencapaian").html("");
                $("#view_rencana_kerja_detail_status").html("");
                $("#view_rencana_kerja_detail_catatan").html("");
                if(res.status=="OK"){
                    $("#modal-view").modal('show');
                    $("#view_rencana_kerja_detail_quarter").html(res.quarter);
                    $("#view_rencana_kerja_detail_verifikasi").html(res.verifikasi);
                    $("#view_rencana_kerja_detail_pencapaian").html(res.pencapaian);
                    $("#view_rencana_kerja_detail_status").html(res.status_rk);
                    $("#view_rencana_kerja_detail_catatan").html(res.catatan);
                }
                else {
                   $("#modal-view").modal('hide');
                }
            }   
          }
        });
    }
    function refresh(){
        $("#modal-add").modal('hide');
        DetailRencanaKerja('{{$parameter}}');
    }
</script>