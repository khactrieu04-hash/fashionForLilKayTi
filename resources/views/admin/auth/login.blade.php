@extends('layouts.admin-auth')
@section('content-auth')
<main>
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="{{ asset('asset/admin/v1/assets/img/logo.png') }}" alt="">
                <span class="d-none d-lg-block">Quản Trị WEBSITE</span>
              </a>
            </div><!-- End Logo -->

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">ĐĂNG NHẬP HỆ THỐNG</h5>
                  @if ($errors->get('disable_reason'))
                    <p class="text-center small error invalid-feedback" style="display: block">{{ implode(", ",$errors->get('disable_reason')) }}</p>
                  @endif
                  @if ($errors->get('email'))
                          {{-- <span id="email-error" class="error invalid-feedback" style="display: block">
                            {{ implode(", ",$errors->get('email')) }}
                          </span> --}}
                          <p class="text-center small error invalid-feedback" style="display: block">{{ implode(", ",$errors->get('email')) }}</p>
                        @endif
                </div>
                
                <form class="row g-3" action="{{ route('admin.login') }}" method="post" id="login-form__js">
                  @csrf
                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Email</label>
                    <div class="input-group has-validation">
                      <input type="text" name="email" value="{{ old('email') }}" class="form-control" id="email" required>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Mật Khẩu</label>
                    <input type="password" name="password" class="form-control" id="yourPassword" required>
                    <div class="invalid-feedback">
                      @if ($errors->get('password'))
                        <span id="password-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('password')) }}
                        </span>
                      @endif
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Đăng Nhập</button>
                  </div>
                 
                </form>

              </div>
            </div>

            <div class="credits">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            </div>

          </div>
        </div>
      </div>

    </section>

  </div>
</main><!-- End #main -->
@vite(['resources/common/js/login.js'])
@endsection
