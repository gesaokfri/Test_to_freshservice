<!DOCTYPE html>
  <html>
  
  @include('layout.partials.head')

  <body>

    {{-- Loader --}}
    
    <div id="layout-wrapper" style="background: url('{{base_url('')}}/assets/images/design/portal/atma.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;">
      @include('layout.partials.loader')

      {{-- Start Content --}}
      @yield('content')

    </div>

    {{-- Inc Vendor Scripts --}}
    @include('layout.partials.vendor-scripts')
    
    <!-- App js -->
    <script src="{{base_url('')}}/assets/js/app.js"></script>
    @include('layout.partials.function-scripts')

    {{-- Inc Each Page Script --}}
    @yield('script')
    
  </body>

  </html>