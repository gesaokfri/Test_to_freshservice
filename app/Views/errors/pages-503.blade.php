@section('title', 'Tidak Memiliki Akses!')

<!doctype html>
<html lang="en">

  @include('layout.partials.head')

<body>
    @if (empty($_SESSION["error"]))
        <script>
            window.location.replace("{{ base_url('/') }}");    
        </script>
    @else
        @php
            unset($_SESSION["error"]);
        @endphp
    @endif

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1 class="display-2 fw-medium">5<i class="bx bx-buoy bx-spin text-primary display-3"></i>3</h1>
                        <h4 class="text-uppercase">Kamu tidak memiliki akses untuk halaman ini!</h4>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light" href="{{ base_url('/') }}">Kembali ke Portal</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-6">
                    <div>
                        <img src="assets/images/error-img.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inc Vendor Scripts --}}
    @include('layout.partials.vendor-scripts')

    <script src="{{base_url('')}}/assets/js/app.js"></script>

</body>

</html>