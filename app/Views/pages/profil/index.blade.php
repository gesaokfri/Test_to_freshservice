@extends('layout.index')
@section('title', 'Profil')

@section('content')
    <div class="main-content" style="overflow: unset">
        <div class="page-content">
            
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Profil Saya</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/beranda">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Profil Saya</li>
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
                                <div id="content-marketing_kompetitor">
                                    <div class="col-12">
                                        <div class="card border pt-3 h-100">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12 d-flex flex-column">

                                                        <form id="form-edit-profil" method="POST" autocomplete="off" enctype="multipart/form-data">
                                                            {!! csrf_field() !!}
                                                            <input type="hidden" name="parameter" value="{{ session('session_id') }}">
                                                            <div class="row">
                                                                <div class="col-lg-3 p-0 pe-0 pe-lg-3">
                                                                    <div class="card shadow p-4 h-100">
                                                                        <div class="rounded-circle overflow-hidden bg-dark" style="
                                                                            border:4px solid #fcaf17;">
                                                                            <img src="{{ base_url('') }}/assets/images/design/user/{{ session('photo') }}" alt="" class="img-fluid" id="img-holder"
                                                                            style="
                                                                                height: 233px; 
                                                                                object-fit:cover; 
                                                                                object-position:center;
                                                                                border-radius: 50%;
                                                                                cursor: pointer;
                                                                                width: 100%;
                                                                                "
                                                                            onclick="event.preventDefault();$('#photo').trigger('click')">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <button class="btn bg-primary bg-soft w-100" onclick="event.preventDefault();$('#photo').trigger('click')">Ubah Foto</button>
                                                                            <input type="file" class="d-none" id="photo" name="photo" accept="image/png, image/jpeg, image/jpg">
                                                                        </div>
                                                                        <span class="text-muted mt-3">
                                                                            Besar file: maksimum 5.000.000 bytes (5 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9 p-0 mt-3 mt-lg-0">
                                                                    <div class="card shadow p-4 h-100">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <label for="">Nama</label>
                                                                                <input type="text" class="form-control" value="{{ session('name') }}" readonly>
                                                                            </div>
                                                                            <div class="col-lg-12 mt-3">
                                                                                <label for="">Email</label>
                                                                                <input type="text" class="form-control" value="{{ session('email') }}" readonly>
                                                                            </div>
                                                                            <div class="col-lg-12 mt-3">
                                                                                <label for="">Username / Nama Pengguna</label>
                                                                                <input type="text" class="form-control" value="{{ session('username') }}" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3 p-3" id="button-container">
                                                                            <button type="reset" href="javascript:void(0)" class="btn ms-auto btn-light" style="display: none" onclick="resetImage()">Reset</button>
                                                                            <button type="submit" href="javascript:void(0)" class="btn btn-primary" style="display: none" id="save-image">Simpan</button>
                                                                        </div>

                                                                        <div class="mt-3">
                                                                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modal-profil">Ubah Kata Sandi</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modal-profil" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form id="form-edit-password" method="POST" autocomplete="off">
                                                                {!! csrf_field() !!}
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Ubah Kata Sandi</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="">
                                                                            <label>Kata Sandi Lama</label>
                                                                            <input type="password" name="old_password" class="form-control" placeholder="Masukkan kata sandi lama" id="old-password">
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <label>Kata Sandi Baru</label>
                                                                            <input type="password" name="new_password" class="form-control" placeholder="Masukkan kata sandi baru" id="new-password">
                                                                            <small id="password-length" class="mt-3"></small>
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <label>Konfirmasi Kata Sandi Baru</label>
                                                                            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi baru" id="new-password-confirmation">
                                                                            <small id="check-password" class="mt-3"></small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batalkan</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </div>
                                                                </form>
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
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function (){
            $("#SwitchCheckSizemd").change(function () {
                if ($(this).is(":checked")) {
                $("#lbl-toggleChart").text("BAR");
                } else {
                $("#lbl-toggleChart").text("PIE");
                }
            });

            $("#photo").on('change', function(){
                readURL(this);
            });

            $('#new-password').on('keyup', function(){
                var password        = $('#new-password').val();

                if(password.length < 8 && password.length > 0) {
                    $('#password-length').show();
                    $('#password-length').html("Minimal 8 karakter").css('color', 'red');
                    $('#new-password').css('border', '1px solid #fb6767');
                } else {
                    if(password.length == 0) {
                        $('#password-length').hide();
                        $('#new-password').css('border', '1px solid #d3d3d3');
                    } else {
                        $('#password-length').hide();
                        $('#new-password').css('border', '1px solid #80cf80');
                    }
                }

                var confirmPassword = $('#new-password-confirmation').val();
                if(confirmPassword != password) {
                    if(confirmPassword.length != 0) {
                        $('#check-password').html("Password tidak sama!").css('color', 'red');
                        $('#new-password-confirmation').css('border', '1px solid #fb6767');
                    }
                } else {
                    if(confirmPassword.length != 0) {
                        if(password.length < 8) {
                            $('#check-password').html("Minimal 8 karakter!").css('color', 'red');
                        } else {
                            $('#check-password').html("Password sama!").css('color', 'green');
                            $('#new-password-confirmation').css('border', '1px solid #80cf80');
                        }
                    }
                }
            });

            $('#new-password-confirmation').on('keyup', function(){
                var password        = $('#new-password').val();
                var confirmPassword = $('#new-password-confirmation').val();
                if(confirmPassword.length == 0) {
                    $('#check-password').hide();
                    $('#new-password-confirmation').css('border', '1px solid #d3d3d3');
                } else {
                    if(confirmPassword != password) {
                        $('#check-password').show();
                        $('#check-password').html("Password tidak sama!").css('color', 'red');
                        $('#new-password-confirmation').css('border', '1px solid #fb6767');
                    } else {
                        if(confirmPassword.length < 8 && confirmPassword.length > 0) {
                            $('#check-password').show();
                            $('#check-password').html("Minimal 8 karakter!").css('color', 'red');
                        } else {
                            $('#check-password').show();
                            $('#check-password').html("Password sama!").css('color', 'green');
                            $('#new-password-confirmation').css('border', '1px solid #80cf80');
                        }
                    }
                }
            });
        })

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                $('#img-holder').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
                $('#button-container button').show();
            } else {
                alert('select a file to see preview');
                $('#img-holder').attr('src', '');
            }
        }

        function resetImage(input) {
            $('#img-holder').attr('src', '{{ base_url("assets/images/design/user/default.png") }}');
            $('#button-container button').hide();
        }

        function refresh(){
            location.reload();
        }

        $("#form-edit-profil").on("submit", function(event){
            event.preventDefault();
            action("form-edit-profil", "Ubah Profil", "Apakah anda yakin untuk menyimpan perubahan?", "warning", '{{ base_url("universitas/profile/update") }}', refresh);
            
        });

        $("#form-edit-password").on("submit", function(event){
            event.preventDefault();
            action("form-edit-password", "Ubah Password", "Apakah anda yakin untuk menyimpan perubahan?", "warning", '{{ base_url("universitas/profile/update_password") }}', refresh);
            
        });
    </script>
@endsection