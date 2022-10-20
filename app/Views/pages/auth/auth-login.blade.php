@extends('pages.auth.index')
@section('title', 'Login')

@section('content')
<div class="account-pages" style="background: rgba(0, 0, 0, 0.281)">
    <div class="container">
        <div class="row justify-content-center min-vh-100">
            <div class="col-md-8 col-lg-6 col-xl-5 d-flex">
                <div class="m-auto w-100">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Selamat Datang !</h5>
                                        <p>Masuk untuk melanjutkan ke dashboard AtmaSIAP.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ base_url() }}/assets/images/design/profile-img.png" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="/" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ base_url() }}/assets/images/design/logo/icon.png" alt="" class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                
                                {{-- Fail Auth --}}
                                <div class="alert alert-danger error-message"role="alert"></div>

                                <form method="POST" id="form-login" autocomplete="off">
    
                                    {!! csrf_field() !!}
    
                                    <div class="my-3">
                                        <label for="username" class="form-label">Nama Pengguna</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukan nama pengguna">
                                        <small class="validator" id="validator-username"></small>
                                    </div>
    
                                    <div class="mb-3">
                                        <label class="form-label">Kata Sandi</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" name="password" placeholder="Masukan kata sandi" aria-label="Password" aria-describedby="password-addon">
                                            <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                        <small class="validator" id="validator-password"></small>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Masuk</button>
                                    </div>
    
                                </form>
                            </div>
    
                        </div>
                    </div>
                    <div class="mt-5 text-center">
    
                        <div>
                            <p class="text-light">© <script>
                                    document.write(new Date().getFullYear())
                                </script> Dashboard Atma Jaya || All rights reserved
                            </p>
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
            $('#loader').fadeOut();
        });

        function su(form_id) {

        }
        $("#form-login").on("submit", function(event) {
            event.preventDefault();
            
            $.ajax({
                url       : "{{base_url()}}/login/process_login",
                type      : "POST",
                data      : $("#form-login").serialize(),
                dataType  : "json",
                statusCode: {
                    403 : function(response) {
                        Swal.fire({
                        title            : 'Sesi Telah Habis',
                        text             : 'refresh halaman',
                        icon             : 'info',
                        allowOutsideClick: false
                        }).then((whenOK) => {
                            if(whenOK.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                },
                beforeSend: function() {
                    $("#loader").fadeIn();
                },
                success : function(data) {
                    if(data.status == "Invalid"){
                        $('.validator').show();
                        Object.keys(data.validation).forEach(key => {
                            $('#validator-'+key).html(data.validation[key]);
                        });
                        $("#loader").fadeOut();
                        $('.error-message').hide();
                    } else {
                        if(data.status == "OK") {
                            window.location.href = "{{ base_url() }}";
                        } else {
                            $('.error-message').show();
                            $('.error-message').html(data.message);
                            $('.validator').hide();
                            $("#loader").fadeOut();
                        }
                    }
                },
            });
        });
    </script>
@endsection