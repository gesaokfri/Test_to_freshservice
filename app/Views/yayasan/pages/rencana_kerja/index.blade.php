@extends('yayasan.layout.index')
@section('title','Rencana Kerja')

@section('content')
    <div class="main-content" style="overflow: unset">
        <div class="page-content">
            
            <div class="container-fluid">
                
                @include('yayasan.layout.partials.breadcrumb')
                
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
    </div>

    


@endsection
@section('script')
    <script>
        $(document).ready(function() {
            Go("{{get_menu($id_menu, 'link')}}", "{{get_menu($id_menu, 'menu')}}", 'res');
            selectbox();
        });
        function refresh(){
            Go("{{get_menu($id_menu, 'link')}}", "{{get_menu($id_menu, 'menu')}}", 'res');
        }
        function DetailRencanaKerja(id){
            $.ajax({
                url: "rencana_kerja_detail/res",
                type: 'POST',
                dataType: 'html',
                data :  { 
                    "{{csrf_token()}}": "{{csrf_hash()}}",
                    "parameter"       : id,
                },
                beforeSend : function() {
                    $(".data-loader").fadeIn();
                    $("#content").slideUp();
                    $.skylo('start');
                },
                success: function(res) {
                    $('#content').html(res);
                    $("#content").slideDown();
                    selectbox();
                },
                complete : function() {
                    $(".data-loader").fadeOut();
                    $.skylo('end');
                }
            });
        }
    </script>
@endsection