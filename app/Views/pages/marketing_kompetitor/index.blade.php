@extends('layout.index')
@section('title', 'Marketing Kompetitor')

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
        @include('pages.marketing_kompetitor.filter-modal')
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
            $('.select2-competitor_name').select2();
        }

        $("#form-filter_markom").on('submit', function(event) {
            event.preventDefault();
            var competitors = $("#filter-competitor_name").val();
            var prodi       = $("#filter-competitor_program").val();
            var tahun       = $("#filter-competitor_year").val();
            chartMarketingKompetitor(competitors, prodi, tahun);
            $("#filter-markom").modal('hide');
        });
    </script>
@endsection