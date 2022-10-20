@extends('yayasan.layout.index')
@section('title', 'Headcount')

@section('content')
    <div class="main-content" style="overflow: unset">
        <div class="page-content">
            
            <div class="container-fluid">
                
                @include('layout.partials.breadcrumb')

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
        @include('yayasan.pages.headcount.filter-modal')
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            Go("{{get_menu($id_menu, 'link')}}", "{{get_menu($id_menu, 'menu')}}", 'res');
        });

        $("#form-headcount").on('submit', function(event) {
            event.preventDefault();
            var tahun = $("#filter-tahun").val();
            var quarter       = $("#filter-quarter").val();
            ChartHeadcount(tahun, quarter);
            $("#filter-headcount").modal('hide');
        });
    </script>
@endsection