<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

      <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
          <img src="{{ asset('asset/admin/v1/assets/img/logo.png') }}" alt="">
          <span class="d-none d-lg-block">{{ (Auth::guard('admin')->user()->id == 1) ? 'Quản Trị Viên' : 'Nhân Viên'}}</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
      </div><!-- End Logo -->

      <div class="search-bar">
        {{-- <form class="search-form d-flex align-items-center" method="POST" action="#">
          <input type="text" name="query" placeholder="Search" title="Enter search keyword">
          <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form> --}}
      </div><!-- End Search Bar -->

      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
          <li class="nav-item dropdown pe-3">
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              <img style="    max-height: 36px;width: 36px;object-fit: cover;" src="{{ asset('asset/admin/v1/assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
              <span class="d-none d-md-block ps-2">{{ Auth::guard('admin')->user()->name }}</span>
            </a><!-- End Profile Iamge Icon -->
          </li><!-- End Profile Nav -->
        </ul>
      </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#dashboard-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-speedometer2"></i><span>{{ TextLayoutSidebar("dashboard") }}</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="dashboard-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    @php
                        $isRouteUser = Route::is('admin.home');
                    @endphp
                    <a href="{{ route('admin.home') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Tổng Quan</span>
                    </a>
                </li>
                <li>
                    @php
                        $isRouteUser = Route::is('admin.statistical');
                    @endphp
                    <a href="{{ route('admin.statistical') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Thống Kê</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Quản Lý Tài Khoản</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              @php
                $isRouteUser = request()->is('admin/users*');
              @endphp
              <a href="{{ route('admin.users_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Khách Hàng</span>
              </a>
            </li>
            <li>
              @php
                $isRouteUser = request()->is('admin/staffs*');
                $isShow = Auth::guard('admin')->user()->role_id == 1;
              @endphp
                @if ($isShow)
                <a href="{{ route('admin.staffs_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Nhân Viên</span>
                </a>
                @endif
            </li>
          </ul>
        </li><!-- End Components Nav -->

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Quản Lý Bán Hàng</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              @php
                $isRouteUser = request()->is('admin/categories*');
              @endphp
              <a href="{{ route('admin.category_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Danh mục</span>
              </a>
            </li>
            <li>
              @php
                 $isRouteUser = request()->is('admin/products*');
              @endphp
              <a href="{{ route('admin.product_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Sản Phẩm</span>
              </a>
            </li>
            <li>
              @php
                 $isRouteUser = request()->is('admin/colors*');
              @endphp
              <a href="{{ route('admin.colors_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Màu Sắc</span>
              </a>
            </li>
            <li>
              @php
                 $isRouteUser = request()->is('admin/sizes*');
              @endphp
              <a href="{{ route('admin.sizes_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Kích Thước</span>
              </a>
            </li>
            <li>
              @php
                 $isRouteUser = request()->is('admin/brands*');
              @endphp
              <a href="{{ route('admin.brands_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Thương Hiệu</span>
              </a>
            </li>
            @if (Auth::guard('admin')->user()->role_id == 1)
            <li>
              @php
                 $isRouteUser = request()->is('admin/payments*');
              @endphp
              <a href="{{ route('admin.payments_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Cổng Thanh Toán</span>
              </a>
            </li>
            @endif
            <li>
              @php
                 $isRouteUser = request()->is('admin/orders*');
              @endphp
              <a href="{{ route('admin.orders_index') }}" class="{{ ($isRouteUser) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Đơn Hàng</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Quản Lý Cá Nhân</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('admin.profile_change-profile') }}" class="{{ (Route::is('admin.profile_change-profile')) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Thông tin</span>
              </a>
            </li>
            <li>
              <a href="{{ route('admin.profile_change-password') }}" class="{{ (Route::is('admin.profile_change-password')) ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Mật Khẩu</span>
              </a>
            </li>
          </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item">
          @php
            $isRouteUser = request()->is('admin/setting*')
          @endphp
          @if ($isShow)
          <a class="nav-link {{ ($isRouteUser) ? $isRouteUser : 'collapsed' }}" href="{{ route('admin.setting_index') }}">
            <i class="bi bi-gear"></i>
            <span>Cài Đặt</span>
          </a>
          @endif
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
          <a class="nav-link {{ (Route::is('admin.logout')) ? '' : 'collapsed' }}" href="{{ route('admin.logout') }}">
            <i class="bi bi-arrow-return-left"></i>
            <span>Đăng Xuất</span>
          </a>
        </li><!-- End Dashboard Nav -->
      </ul>

    </aside><!-- End Sidebar-->
