<div id="content-rencana_kerja">
    <div class="col-12">
        <div class="card border pt-3 h-100">
            <div class="card-header bg-white d-flex">
                  <div class="">
                      <h5 class="fw-bold">{{get_menu($id_menu, 'menu')}}</h5>
                  </div>
                  <div class="ms-auto d-flex">
                      <div class="align-self-center me-3 data-loader" style="display: none">
                          <i class="bx bx-loader-circle font-size-24"></i>
                      </div>
                      @if(acc_create(session('level_id'),$id_menu)=="1")
                        <button id="btnAdd" onclick="Go('{{get_menu($id_menu, 'link')}}', '{{get_menu($id_menu, 'menu')}}', 'create', 'Tambah')" class="btn btn-outline-primary m-auto"><i class="bx bx-plus"></i> Tambah</button>
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
                                        <th></th>
                                        <th>No</th>
                                        <th>Group</th>
                                        <th>Rencana Kerja</th>
                                        <th>PIC</th>
                                        <th>Kegiatan</th>
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
                <form id="anu"></form>
            </div>
        </div>
    </div>
  </div>

<script>
    $(document).ready(function() {
        var tableAjax = $('#datatable').DataTable({
            serverSide: true,
            processing: true,
            stateSave : true,
            ajax      : {
                url     : "rencana_kerja/read",
                type    : "POST",
                dataType: "json",
                data    : {
                "{{csrf_token()}}" : "{{csrf_hash()}}"
                }
            },
            dom: "<'row'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end gap-3'Bf>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }
            ],
            buttons: {
                buttons: [
                    {
                        text: 'Hapus',
                        name: 'main',
                        className: 'btn bg-danger bg-soft text-danger ms-auto',
                        attr:  {
                            id: 'deleteSelected'
                        }
                    }
                ],
                dom: {
                    button: {
                        className: 'btn'
                    }
                }
            },
            'select': {
                'style': 'multi'
            },
            columns : [
                {data : 'rkId', className: "text-center",sortable : false,orderable : false,searchable: false},
                {data : 'rkNo'},
                {data : 'rkGroup'},
                {data : 'rkName'},
                {data : 'rkPic'},
                {data : 'rkKegiatan'},
                {data : 'rkAction',className: "text-center td-action",sortable : false,orderable : false,searchable: false}
            ]
        });
        
        // submit form on delete selected button clicked
        $('#deleteSelected').on("click", function(){
            $("#anu").trigger('submit');
        });
        // ajax the delete proccess of delete selected data
        $('#anu').on("submit", function(event) {
            event.preventDefault();
            var rows_selected = tableAjax.column(0).checkboxes.selected();
            // Iterate over all selected checkboxes
            $("input[type=hidden][name='id[]']").remove();
            $.each(rows_selected, function(index, rowId){
                $('#anu').append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'id[]')
                        .val(rowId)
                );
            });
            var params = $("input[type=hidden][name='id[]']").map(function(){
                return $(this).val()
            }).get();
            $.ajax({
                url: 'rencana_kerja/delete-selected',
                method: 'post',
                dataType: 'JSON',
                data :  { 
                    "{{csrf_token()}}": "{{csrf_hash()}}",
                    'params' : params
                },
                success: function(res) {
                    tableAjax.ajax.reload();
                    tableAjax.column().checkboxes.deselectAll();

                }
            });
        });
    });
</script>