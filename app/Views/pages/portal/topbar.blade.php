<header id="page-topbar" style="position: unset;">
  <div class="navbar-header py-md-5">
      <div class="d-flex">
          <!-- LOGO -->
          <div class="navbar-brand-box">
              <a href="{{ base_url('') }}" class="logo logo-dark">
                  <span class="logo-sm">
                      <img src="{{ base_url('assets/images/design/logo') }}/icon.png" alt="" height="40">
                  </span>
                  <span class="logo-lg">
                      <img src="{{ base_url('assets/images/design/portal') }}/kantor_yayasan.png" alt="" height="60">
                  </span>
              </a>
          </div>
      </div>

      <div class="d-flex">

          <div class="align-self-center dropdown d-none d-lg-inline-block ms-1">
              <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                  <i class="bx bx-fullscreen"></i>
              </button>
          </div>

          <div class="align-self-center dropdown d-inline-block">
              <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
              data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="rounded-circle header-profile-user" src="{{base_url('assets/images')}}/design/user/{{ session('photo') }}" alt="Header Avatar">
                  <span class="d-none d-xl-inline-block ms-1" key="t-henry">Hi, {{ explode(' ', session('name'))[0]}}</span>
                  <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item text-danger" href="{{ base_url() }}/logout"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
              </div>
          </div>

      </div>
  </div>
</header>