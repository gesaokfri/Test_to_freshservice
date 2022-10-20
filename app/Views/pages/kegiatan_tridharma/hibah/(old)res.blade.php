<div id="content-total_mahasiswa">
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
                      <button id="btnAdd" onclick="Go('hibah', '{{get_menu($id_menu, 'menu')}}', 'import', 'Import Excel')" class="btn btn-outline-primary m-auto"><i class="bx bx-spreadsheet"></i> Import Excel</button> 
                      &nbsp;&nbsp; 
                      <button id="btnAdd" onclick="Go('hibah', '{{get_menu($id_menu, 'menu')}}', 'create', 'Tambah')" class="btn btn-outline-primary m-auto"><i class="bx bx-plus"></i> Tambah</button>
                    @endif
                </div>
          </div>
            <div class="card-body">

                {{-- Chart Hibah Penelitian --}}
                <div class="row">
                    <div class="col-12" id="chart-hibah_penelitian">
                        
                    </div>
                </div>

                {{-- Data Hibah --}}
                <div class="row mt-4">
                    <div class="col-12" id="dataHibah">
                      <table id="dataTable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
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
                    </div>
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
                url     : "hibah/read",
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
            'select': {
                'style': 'multi'
            },
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
            columns : [
                {
                    data : 'hibahID',
                    'sortable': false,
                    'searchable': false,
                },
                {data : 'hibahName'},
                {data : 'hibahPeriode'},
                {data : 'hibahPriceReceived'},
                {data : 'hibahPriceCompanion'},
                {data : 'hibahAction',className: "text-center td-action",sortable : false,orderable : false,searchable: false}
            ]
        });
        $(".dataTables_length select").addClass('form-select form-select-sm');

        chartHibah();
    });

    function chartHibah() {
        $.ajax({
            url      : "hibah/chart",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}"
            },
            success  : function(data) {
                $('#chart-hibah_penelitian').html(data);
            }
        })
    }

</script>