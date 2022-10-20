@if(acc_create(session('level_id'),$id_menu)=="1")
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="addModal">
        <div class="modal-dialog modal-dialog-centered" style="width:800px;">
            <div class="modal-content" style="height:600px;overflow-y:auto;">
                <form id="form_add" method="POST" autocomplete="off">
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Hibah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Pemberi Hibah</small></label>
                                <input type="text" name="hibah_name" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Periode Hibah</small></label>
                                  <select name="tahun_semester" class="form-select" data-placeholder="Pilih tahun semester" required>
                                    <option value="">Pilih Periode Hibah</option>
                                    @foreach($tahun_semester as $item)
                                      <option value="{{$item['kode_semester']}}">{{get_semester($item['kode_semester'])}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Jumlah Hibah penelitian yang dilakukan/direncanakan</small></label>
                                <input type="text" name="jumlah_hibah_penelitian" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Jumlah Hibah pengabdian yang dilakukan/direncanakan</small></label>
                                <input type="text" name="jumlah_hibah_pengabdian" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Jumlah Hibah penelitian & pengabdian yang dipublikasikan</small></label>
                                <input type="text" name="jumlah_hibah_penelitian_pengabdian" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><small class="text-uppercase">Dana Yang Diterima (IDR)</small></label>
                                <input type="text" name="hibah_terima" class="form-control price" required>
                            </div>
                            <div class="col-md-6">
                                <label><small class="text-uppercase">Dana Pendamping (IDR)</small></label>
                                <input type="text" name="hibah_pendamping" class="form-control price" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Lembaga Pemberi Dana</small></label>
                                <input type="text" name="lembaga" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">PIC Pengabdian/Penelitian</small></label>
                                <input type="text" name="pic_pengabdian_penelitan" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Member Pengabdian/Penelitian</small></label>
                                <input type="text" name="member_pengabdian_penelitan[]" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div id="div_add_member"></div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <center>
                                    <button onclick="add_member('div_add_member')" type="button" class="btn btn-success">
                                        <i class="bx bx-plus"></i> Tambah Member
                                    </button>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="refresh()">Reset</button>
                        <button type="submit" class="btn btn-primary">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if(acc_update(session('level_id'),$id_menu)=="1")
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="updateModal">
        <div class="modal-dialog modal-dialog-centered" style="width:800px">
            <div class="modal-content" style="height:600px;overflow-y:auto;">
                <form id="form_update" method="POST" autocomplete="off">
                    {!! csrf_field() !!}
                    <input type="hidden" name="parameter" id="parameter" required>
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Hibah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Pemberi Hibah</small></label>
                                <input type="text" name="hibah_name" id="hibah_name" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Periode Hibah</small></label>
                                  <select name="tahun_semester" id="tahun_semester" class="form-select" data-placeholder="Pilih tahun semester" required>
                                    <option value="">Pilih Periode Hibah</option>
                                    @foreach($tahun_semester as $item)
                                      <option value="{{$item['kode_semester']}}">{{get_semester($item['kode_semester'])}}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Jumlah Hibah penelitian yang dilakukan/direncanakan</small></label>
                                <input type="text" name="jumlah_hibah_penelitian" id="jumlah_hibah_penelitian" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Jumlah Hibah pengabdian yang dilakukan/direncanakan</small></label>
                                <input type="text" name="jumlah_hibah_pengabdian" id="jumlah_hibah_pengabdian" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Jumlah Hibah penelitian & pengabdian yang dipublikasikan</small></label>
                                <input type="text" name="jumlah_hibah_penelitian_pengabdian" id="jumlah_hibah_penelitian_pengabdian" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <label><small class="text-uppercase">Dana Yang Diterima (IDR)</small></label>
                                <input type="text" name="hibah_terima" id="hibah_terima" class="form-control price" required>
                            </div>
                            <div class="col-md-6">
                                <label><small class="text-uppercase">Dana Pendamping (IDR)</small></label>
                                <input type="text" name="hibah_pendamping" id="hibah_pendamping" class="form-control price" required>
                            </div>
                        </div>
                         <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">Lembaga Pemberi Dana</small></label>
                                <input type="text" name="lembaga" id="lembaga" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label><small class="text-uppercase">PIC Pengabdian/Penelitian</small></label>
                                <input type="text" name="pic_pengabdian_penelitan" id="pic_pengabdian_penelitan" maxlength="150" class="form-control" required>
                            </div>
                        </div>
                        <div id="div_add_member_edit"></div>
                        <div class="mt-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <center>
                                    <button onclick="add_member('div_add_member_edit')" type="button" class="btn btn-success">
                                        <i class="bx bx-plus"></i> Tambah Member
                                    </button>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" onclick="refresh()">Reset</button>
                        <button type="submit" class="btn btn-primary">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script type="text/javascript">
    $("#form_add").on("submit", function(event){
        event.preventDefault();
        action("form_add", "Tambah Hibah", "Apakah anda ingin menambah data hibah ?", "warning", 'hibah/save',refresh,'toast');
    });

    $("#form_update").on("submit", function(event){
        event.preventDefault();
        action("form_update", "Ubah Hibah", "Apakah anda ingin mengubah data hibah ?", "warning", 'hibah/update',refresh,'toast');
    });

    function add_member(divId){
        var rand_string = randomString(5);
        var id = Math.floor(1000 + Math.random() * 9000)+rand_string;
        var add_div = '<div id="rowMember_'+id+'">';
            add_div += '<div class="mt-3"></div>';
            add_div +='<div class="row">';
             add_div +='<div class="col-md-10">';
                add_div +='<label><small class="text-uppercase">Member Pengabdian/Penelitian</small></label>';
                add_div +='<input type="text" name="member_pengabdian_penelitan[]" maxlength="150" class="form-control" required>';
             add_div +='</div>';
            add_div +='<div class="col-md-2"><br/><button onclick="delete_member(\''+id+'\')" type="button" class="btn btn-danger inner">Delete</button></div>';
            add_div +='</div></div>';
            $("#"+divId).append(add_div);
    }

    function rest_member(divId,member){
        var rand_string = randomString(5);
        var id = Math.floor(1000 + Math.random() * 9000)+rand_string;
        var add_div = '<div id="rowMember_'+id+'">';
            add_div += '<div class="mt-3"></div>';
            add_div +='<div class="row">';
             add_div +='<div class="col-md-10">';
                add_div +='<label><small class="text-uppercase">Member Pengabdian/Penelitian</small></label>';
                add_div +='<input type="text" value="'+member+'" name="member_pengabdian_penelitan[]" maxlength="150" class="form-control" required>';
             add_div +='</div>';
            add_div +='<div class="col-md-2"><br/><button onclick="delete_member(\''+id+'\')" type="button" class="btn btn-danger inner">Delete</button></div>';
            add_div +='</div></div>';
            $("#"+divId).append(add_div);
    }

    function delete_member(row){
        $("#rowMember_"+row).remove();
    }

    function refresh(){
        $('#dataTable').DataTable().ajax.reload();

        //Close Modal
        $("#addModal").modal('hide');
        $("#updateModal").modal('hide');

        //Reset Form
        $('#form_add').trigger("reset");
        $('#form_edit').trigger("reset");
        $(".select2").val("").trigger("change");
        $("#div_add_member").html("");
        $("#div_add_member_edit").html("");
    }
</script>