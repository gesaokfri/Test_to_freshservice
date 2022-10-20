<!DOCTYPE html>
  <html>
  
  @include('layout.partials.head')

  <body>

    {{-- Loader --}}
    
    <div id="layout-wrapper">
      @include('layout.partials.loader')

      {{-- Inc Menu (Topbar & Sidebar) --}}
      @include('layout.partials.menu')
              
        {{-- Start Content --}}
        <div class="toast-container position-absolute bottom-0 end-0 p-3" style="z-index: 99999;">

          <!-- Then put toasts within -->
          <div class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 23px; right: 23px;">
              <div class="d-flex">
                  <div class="toast-body">
                      <div class="toast-message"></div>
                  </div>
                  <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
          </div>
        </div>
        @yield('content')

        {{-- Inc Footer --}}
        @include('layout.partials.footer')

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