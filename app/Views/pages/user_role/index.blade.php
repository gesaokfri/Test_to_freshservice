@extends('layout.index')
@section('title', 'User Role')

@section('content')
		<!-- Easyui -->
	<link rel="stylesheet" href="{{base_url('')}}/assets/libs/easyui/themes/default/easyui.css">
	<link rel="stylesheet" href="{{base_url('')}}/assets/libs/easyui/themes/icon.css">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">User Role</h4>

                            <div class="page-title-right" id="breadcrumb_index">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/beranda">Dashboard</a></li>
                                    <li class="breadcrumb-item">Master Data</li>
                                    <li class="breadcrumb-item active">User Role</li>
                                </ol>
                            </div>
                            <div class="page-title-right" id="breadcrumb_add_edit" style="display:none"></div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden p-3">
                            <div class="row">

                                @include('pages/user_role/data-container')

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection