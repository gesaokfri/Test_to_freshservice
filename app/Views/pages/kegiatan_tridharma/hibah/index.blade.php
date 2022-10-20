@extends('layout.index')
@section('title', 'Hibah')

@section('content')
    <div class="main-content" style="overflow: unset">
        <div class="page-content">
            
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Hibah</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ base_url() }}/universitas">Dashboard</a></li>
                                    <li class="breadcrumb-item">Kegiatan Tridharma PT</li>
                                    <li class="breadcrumb-item active">Hibah</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card p-3">

                            <div id="content">
                                {{-- ajax load --}}
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('pages.kegiatan_tridharma.hibah.filter-modal')
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function() {
            Go("hibah", "{{get_menu($id_menu, 'menu')}}", 'res');
        });
        function refresh(){
            Go("hibah", "{{get_menu($id_menu, 'menu')}}", 'res');
        }
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
    </script>
@endsection