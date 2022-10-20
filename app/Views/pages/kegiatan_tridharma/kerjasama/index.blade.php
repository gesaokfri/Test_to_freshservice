@extends('layout.index')
@section('title', 'Kerja Sama')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Kerja Sama</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ base_url() }}/universitas">Dashboard</a></li>
                                    <li class="breadcrumb-item">Kegiatan Tridharma PT</li>
                                    <li class="breadcrumb-item active">Kerja Sama</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden">
                            <div class="row">

                                <div class="card-body">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#faculty" role="tab" aria-selected="true">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">FACULTY INBOUND & OUTBOUND</span> 
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#student" role="tab" aria-selected="false">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">STUDENT INBOUND & OUTBOUND</span> 
                                            </a>
                                        </li>
                                    </ul>
    
                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="faculty" role="tabpanel">
                                            <p class="mb-0">
                                                @include('pages.kegiatan_tridharma.kerjasama.faculty.data-container')
                                            </p>
                                        </div>
                                        <div class="tab-pane" id="student" role="tabpanel">
                                            <p class="mb-0">
                                                @include('pages.kegiatan_tridharma.kerjasama.student.data-container')
                                            </p>
                                        </div>
                                    </div>
    
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('pages.kegiatan_tridharma.kerjasama.filter-modal')

@endsection

@section('script')
<script>
    $(document).ready(function() {
        chartFaculty();
        resFaculty();
        
        chartStudent();
        resStudent();
    });

    function chartFaculty() {
        $.ajax({
            url      : "kerjasama/chartFacultyInboundOutbound",
            type     : "POST",
            dataType : "html",
            data     : {    "{{ csrf_token() }}": "{{ csrf_hash() }}"   },
            beforeSend: function() {
                $("#content-faculty_InboundOutbound .data-loader").fadeIn();
                $("#chart-faculty_InboundOutbound").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-faculty_InboundOutbound").hide();
                $('#chart-faculty_InboundOutbound').html(data);
            },
            complete : function() {
                $("#content-faculty_InboundOutbound .data-loader").fadeOut();
                $("#chart-faculty_InboundOutbound").slideDown();
                $.skylo('end');
            }
        })
    }

    function resFaculty() {
        $.ajax({
            url      : "kerjasama/resFacultyInboundOutbound",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}"
            },
            beforeSend: function() {
                $("#content-faculty_InboundOutbound .data-loader").fadeIn();
                $("#chart-faculty_InboundOutbound").slideUp();
                $("#dt-faculty_InboundOutbound").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-faculty_InboundOutbound").hide();
                $("#dt-faculty_InboundOutbound").hide();
                $('#dt-faculty_InboundOutbound').html(data);
            },
            complete : function() {
                $("#content-faculty_InboundOutbound .data-loader").fadeOut();
                $("#chart-faculty_InboundOutbound").slideDown();
                $("#dt-faculty_InboundOutbound").slideDown();
                $.skylo('end');
            }
        })
    }

    function chartStudent() {
        $.ajax({
            url      : "kerjasama/chartStudentInboundOutbound",
            type     : "POST",
            dataType : "html",
            data     : {    "{{ csrf_token() }}": "{{ csrf_hash() }}"   },
            beforeSend: function() {
                $("#content-student_InboundOutbound .data-loader").fadeIn();
                $("#chart-student_InboundOutbound").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-student_InboundOutbound").hide();
                $('#chart-student_InboundOutbound').html(data);
            },
            complete : function() {
                $("#content-student_InboundOutbound .data-loader").fadeOut();
                $("#chart-student_InboundOutbound").slideDown();
                $.skylo('end');
            }
        })
    }

    function resStudent() {
        $.ajax({
            url      : "kerjasama/resStudentInboundOutbound",
            type     : "POST",
            dataType : "html",
            data     : {
                "{{ csrf_token() }}": "{{ csrf_hash() }}"
            },
            beforeSend: function() {
                $("#content-student_InboundOutbound .data-loader").fadeIn();
                $("#chart-student_InboundOutbound").slideUp();
                $("#dt-student_InboundOutbound").slideUp();
                $.skylo('start');
            },
            success  : function(data) {
                $("#chart-student_InboundOutbound").hide();
                $("#dt-student_InboundOutbound").hide();
                $('#dt-student_InboundOutbound').html(data);
            },
            complete : function() {
                $("#content-student_InboundOutbound .data-loader").fadeOut();
                $("#chart-student_InboundOutbound").slideDown();
                $("#dt-student_InboundOutbound").slideDown();
                $.skylo('end');
            }
        })
    }
</script>
@endsection