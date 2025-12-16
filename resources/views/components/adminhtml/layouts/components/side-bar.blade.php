  <aside
      class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
      id="sidenav-main">
      <div class="sidenav-header">
          <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
              aria-hidden="true" id="iconSidenav"></i>
          <a class="navbar-brand m-0" href="{{ url('adminhtml/ahome/home-list') }}">
              <img src="/source/adminhtml/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
              <span class="ms-1 font-weight-bold text-white">adoc.dev Dashboard</span>
          </a>
      </div>
      <hr class="horizontal light mt-0 mb-2">
      <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
          <ul class="navbar-nav">

              @foreach ($listRouter as $router)
                  <li class="nav-item">
                      <a class="nav-link text-white {{ $currentRouter->uri === $router->uri ? 'bg-gradient-primary' : '' }}"
                          href="{{ url($router->uri) }}">
                          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            {{-- link for i tag icon view more: https://www.w3schools.com/icons/ --}}
                              <i
                                  class="material-icons opacity-10">{{ $router->bindingFields()['route_icon'] ?? '' }}</i>
                          </div>
                          <span
                              class="nav-link-text ms-1">{{ $router->bindingFields()['route_name'] ?? $router->uri }}</span>
                      </a>
                  </li>
              @endforeach
              <li class="nav-item mt-3">
                  <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages
                  </h6>
              </li>
          </ul>
      </div>
      <div class="sidenav-footer position-absolute w-100 bottom-0 ">
          <div class="mx-3">
              <a class="btn bg-gradient-primary mt-4 w-100"
                  href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree"
                  type="button">Upgrade to pro</a>
          </div>
      </div>
  </aside>
