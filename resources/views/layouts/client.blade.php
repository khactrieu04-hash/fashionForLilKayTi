<!DOCTYPE html>
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->

<head>
  <!-- =====  BASIC PAGE NEEDS  ===== -->
  <meta charset="utf-8">
  <title>{{ setting_website()->name }}</title>
  <!-- =====  SEO MATE  ===== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="distribution" content="global">
  <meta name="revisit-after" content="2 Days">
  <meta name="robots" content="ALL">
  <meta name="rating" content="8 YEARS">
  <meta name="Language" content="en-us">
  <meta name="GOOGLEBOT" content="NOARCHIVE">
  <!-- =====  MOBILE SPECIFICATION  ===== -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="viewport" content="width=device-width">
  <!-- =====  CSS  ===== -->
  <link rel="stylesheet" href="{{ asset('asset/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/magnific-popup.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset/client/css/owl.carousel.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('asset/client/images/favicon.png') }}') }}">
  <link rel="apple-touch-icon" href="{{ asset('asset/client/images/apple-touch-icon.png') }}') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('asset/client/images/apple-touch-icon-72x72.png') }}') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('asset/client/images/apple-touch-icon-114x114.png') }}') }}">
  @vite(['resources/client/css/home.css'])
</head>
<style>
  :root {
    --transition-fast: 260ms;
    --transition-medium: 540ms;
    --transition-slow: 900ms;
    --accent: #E91E63;
    /* Hồng chủ đạo */
    --accent-gold: #FFD700;
    /* Vàng cho MinMup Shop */
  }

  .product-imageblock {
    height: auto;
    border-radius: 8px;
    overflow: hidden;
  }

  /* Bo tròn 4 góc tất cả hình ảnh */
  img,
  .img-responsive {
    border-radius: 8px;
  }

  .product-thumb__img-product {
    height: 400px !important;
    object-fit: cover !important;
    transition: transform var(--transition-medium) ease,
      filter var(--transition-medium) ease;
  }

  .cart__shopping {
    height: 40px;
  }

  .shopcart i {
    transition: transform var(--transition-fast) ease,
      color var(--transition-fast) ease;
  }

  .shopcart a:hover i {
    transform: translateY(-3px) scale(1.05);
    color: var(--accent);
  }

  .login,
  .register {
    font-size: 15px;
    font-weight: 500;
    color: var(--accent) !important;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 4px;
  }

  .login:hover,
  .register:hover {
    cursor: pointer !important;
  }

  /* Dropdown menu items styling */
  .dropdown-menu>li>a:hover {
    background-color: var(--accent) !important;
    color: #000000 !important;
  }

  .none-hover:hover {
    color: unset;
  }

  .button_group {
    display: flex;
    justify-content: center;
    padding-top: 10px;
  }

  .invalid-feedback {
    color: #fa5460;
  }

  /* =============== TÊN SHOP =============== */
  .logo-main {
    height: 100px !important;
    /* to hơn */
  }

  /* Tên shop to, nổi bật */
  .site-name {
    font-weight: 700;
    color: var(--accent-gold) !important;
    font-size: 56px;
    margin-left: 15px;
    display: inline-block;
    vertical-align: middle;
    font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
    letter-spacing: 0.4px;
    line-height: 1;
    text-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
    transition: transform var(--transition-slow) ease,
      color var(--transition-fast) ease;
    white-space: nowrap;
  }

  /* Đảm bảo container cao vừa logo */
  .navbar-header {
    display: flex;
    align-items: center;
    flex: 1;
  }

  .navbar-brand {
    display: flex;
    align-items: center;
    margin-right: auto;
  }

  .navbar-brand:hover .site-name {
    transform: translateY(-2px);
  }

  /* =============== HEADER & MENU =============== */
  .header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f3f3f3;
  }

  .header .container,
  .header .row,
  .header .navbar {
    background: #ffffff !important;
  }

  /* Link + icon hồng, KHÔNG áp vào span để tránh ảnh hưởng site-name */
  .header a,
  .header i {
    color: var(--accent) !important;
  }

  /* Giữ vàng cho tên shop */
  .header .site-name {
    color: var(--accent-gold) !important;
    font-weight: 700;
    font-size: 24px;
    margin-left: 0px;
  }

  /* Item menu */
  .header .navbar-nav>li>a {
    color: var(--accent) !important;
    font-weight: 600;
    text-transform: uppercase;
    background: transparent !important;
    transition: color var(--transition-fast) ease,
      transform var(--transition-fast) ease;
  }

  /* Hover / focus: chữ đen, không nền đỏ */
  .header .navbar-nav>li>a:hover,
  .header .navbar-nav>li>a:focus {
    transform: translateY(-1.5px);
    color: #000000 !important;
    background: transparent !important;
  }

  /* Active: chữ đen, không nền đỏ */
  .header .navbar-nav>.active>a,
  .header .navbar-nav>.active>a:hover,
  .header .navbar-nav>.active>a:focus {
    background: transparent !important;
    color: #000000 !important;
  }

  /* header-top trắng + text hồng */
  .header-top {
    background: #fff !important;
  }

  .header-top .contact,
  .header-top .header-top-right a,
  .header-top .header-top-right span {
    color: var(--accent) !important;
  }

  /* =============== THANH TÌM KIẾM =============== */
  .main-search {
    margin-top: 30px;
    background: transparent !important;
    /* bỏ nền đen cũ */
    padding: 0 !important;
  }

  /* Form: nền hồng rất nhạt, bo tròn, không viền đen */
  .main-search form {
    display: flex;
    align-items: center;
    width: 100%;
    height: 42px;
    padding: 0 14px;
    background: #fff4fa;
    /* hồng nhạt */
    border-radius: 999px;
    border: 1px solid #f7c1dd;
    box-shadow: 0 0 0 1px rgba(233, 30, 99, 0.03);
  }

  .main-search .form-control.input-lg {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    color: #333 !important;
    font-size: 14px;
    height: 100%;
    line-height: 42px;
    padding: 0 8px 0 0;
    flex: 1 1 auto;
  }

  .main-search .form-control.input-lg::placeholder {
    color: #c47ba4;
  }

  .main-search .input-group-btn {
    margin-left: auto;
    position: static;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .main-search .btn,
  .main-search .btn-default,
  .main-search .btn.btn-lg {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
  }

  /* Icon kính lúp hồng, căn giữa đẹp */
  .main-search .btn.btn-lg i,
  .main-search .btn i {
    color: var(--accent) !important;
    font-size: 18px !important;
    width: 20px !important;
    height: 20px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    line-height: 1 !important;
  }

  @media (max-width: 767px) {
    .main-search form {
      height: 38px;
      padding: 0 10px;
    }
  }

  /* menu */
  #menu.navbar-nav>li>a {
    background: transparent !important;
    color: var(--accent) !important;
    /* hồng */
    font-weight: 600;
    text-transform: uppercase;
  }

  /* Hover + focus + active: bỏ nền đỏ, chữ đen */
  #menu.navbar-nav>li>a:hover,
  #menu.navbar-nav>li>a:focus,
  #menu.navbar-nav>li.active>a,
  #menu.navbar-nav>li.active>a:hover,
  #menu.navbar-nav>li.active>a:focus {
    background: transparent !important;
    color: #000000 !important;
    /* ĐEN */
    box-shadow: none !important;
  }

  /* =============== NÚT & ẢNH SẢN PHẨM =============== */
  .btn {
    transition: transform var(--transition-medium) cubic-bezier(.2, .8, .2, 1),
      box-shadow var(--transition-medium) ease;
  }

  .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.10);
  }

  .product-thumb__img-product:hover {
    transform: scale(1.02);
    filter: brightness(1.01);
  }

  /* Hiệu ứng fade-in / slide-up */
  .animate-on-scroll {
    opacity: 0;
    transform: translateY(8px);
    transition: opacity var(--transition-slow) ease,
      transform var(--transition-slow) ease;
    will-change: opacity, transform;
  }

  .animate-on-scroll.in-view {
    opacity: 1;
    transform: translateY(0);
  }

  .brand .item,
  .footer-block {
    transition: transform var(--transition-medium) ease,
      opacity var(--transition-medium) ease;
  }

  /* =============== RESPONSIVE / GIẢM ANIMATION =============== */
  @media (max-width: 767px) {
    :root {
      --transition-fast: 180ms;
      --transition-medium: 260ms;
      --transition-slow: 360ms;
    }

    .site-name {
      font-size: 20px;
    }

    .navbar-brand:hover .site-name {
      transform: none;
    }

    .header .navbar-nav>li>a:hover {
      transform: none;
    }

    .btn:hover {
      transform: none;
      box-shadow: none;
    }

    .product-thumb__img-product:hover {
      transform: none;
      filter: none;
    }

    .animate-on-scroll {
      transform: translateY(6px);
    }
  }

  @media (prefers-reduced-motion: reduce) {
    * {
      transition: none !important;
      animation: none !important;
    }
  }
</style>



<body>
  <!-- =====  LODER  ===== -->
  <div class="loder"></div>
  <div class="wrapper">
    <!-- =====  HEADER START  ===== -->
    <header id="header">
      <div class="header-top" style="background: #fff;">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 col-sm-4">
              <div class="header-top-left">
                <div class="contact"><a>Call now !</a> <span class="hidden-xs hidden-sm hidden-md" style="color: #E91E63;">0364910537</span></div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-8">
              <ul class="header-top-right text-right">
                @if (!Auth::check())
                <li class="account">
                  <a href="{{ route('user.login') }}" class="login">
                    <i class="far fa-user"></i> Đăng Nhập
                  </a>
                </li>
                <li class="account">
                  <a href="{{ route('user.register') }}" class="login">
                    <i class="fas fa-key"></i> Đăng Ký
                  </a>
                </li>
                @else
                <li class="language dropdown">
                  <span class="dropdown-toggle login" id="dropdownMenu1" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" role="button"><i style="padding-right: 5px;" class="fas fa-user"></i>Thông Tin Cá Nhân
                    <span class="caret"></span>
                  </span>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="{{ route('profile.index') }}">Thông tin cá nhân</a></li>
                    <li><a href="{{ route('order_history.index') }}">Lịch sử mua hàng</a></li>
                    <li><a href="{{ route('user.logout') }}">Đăng xuất</a></li>
                  </ul>
                </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="header">
        <div class="container">
          <div class="row">
            <div class="navbar-header col-xs-6 col-sm-4" style="text-align: unset; padding-left: 0;">
              <a class="navbar-brand none-hover" href="{{ route('user.home') }}" style="display:inline-flex;align-items:center;gap:0;padding:0;margin:0;">
                <img class="logo-main" alt="OYEENok" src="{{ asset('asset/client/images/logo.png') }}" style="object-fit:contain;">
                <span class="site-name">MinMup Shop</span>
              </a>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div class="main-search mt_40">
                <form action="{{ route('user.search') }}" method="get">
                  <input id="search-input" name="keyword" placeholder="Tìm kiếm" class="form-control input-lg"
                    autocomplete="off" type="text">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
                  </span>
                </form>
              </div>
            </div>
            <div class="col-xs-6 col-sm-4 shopcart">
              <div id="cart" class="btn-group btn-block mtb_40">
                <a style="float: right;padding-left: 30px;" href="{{ route('cart.index') }}">
                  <i class="fas fa-shopping-cart" style="font-size: 25px; color: var(--accent);"></i>
                </a>
              </div>
            </div>
          </div>
          <nav class="navbar">
            <p>Menu</p>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse"> <span
                class="i-bar"><i class="fa fa-bars"></i></span></button>
            <div class="collapse navbar-collapse js-navbar-collapse">
              <ul id="menu" class="nav navbar-nav">
                <li>
                  <a href="{{ route('user.home') }}">Trang Chủ</a>
                </li>
                @foreach (category_header() as $category)
                <li>
                  <a href="{{ route('user.products', $category->slug) }}">{{ $category->name }}</a>
                </li>
                @endforeach
                <li>
                  <a href="{{ route('user.introduction') }}">Giới Thiệu</a>
                </li>
              </ul>
            </div>
            <!-- /.nav-collapse -->
          </nav>
        </div>
      </div>
    </header>
    <!-- =====  HEADER END  ===== -->

    <!-- =====  CONTAINER START  ===== -->
    @yield('content-client')
    <div class="container">
      <div id="brand_carouse" class="ptb_30 text-center">
        <div class="type-01">
          <div class="heading-part mb_10 ">
            <h2 class="main_title">Thương Hiệu</h2>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="brand owl-carousel ptb_20">
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand1.png") }}" alt="Disney" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand2.png") }}" alt="Dell" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand3.png") }}" alt="Harley" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand4.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand5.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand6.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand7.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand8.png") }}" alt="Canon" class="img-responsive" /></a> </div>
                <div class="item text-center"> <a href="#"><img loading="lazy" src="{{ asset("asset/client/images/brand/brand9.png") }}" alt="Canon" class="img-responsive" /></a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- =====  CONTAINER END  ===== -->
    <!-- =====  FOOTER START  ===== -->
    <div class="footer pt_60">
      <div class="container">
        <div class="row">
          {{-- <div class="footer-top pb_60 mb_30">
            <div class="col-xs-12 col-sm-6">
              <div class="footer-logo"> <a href="{{ route('user.home') }}"> <img src="{{ asset('asset/client/images/footer-logo.png') }}" alt="OYEENok"> </a>
        </div>
        <div class="footer-desc">Lorem ipsum doLorem ipsum dolor sit amet, consectetur adipisicagna.</div>
      </div>
      <!-- =====  testimonial  ===== -->
      <div class="col-xs-12 col-sm-6">
        <div class="Testimonial">
          <div class="client owl-carousel">
            <div class="item client-detail">
              <div class="client-avatar"> <img alt="" src="{{ asset('asset/client/images/user1.jpg') }}"> </div>
              <div class="client-title"><strong>joseph Lui</strong></div>
              <div class="client-designation mb_10"> - php Developer</div>
              <p><i class="fa fa-quote-left" aria-hidden="true"></i>Lorem ipsum dolor sit amet, volumus oporteat
                his at sea in Rem ipsum dolor sit amet, sea in odio ..</p>
            </div>
            <div class="item client-detail">
              <div class="client-avatar"> <img alt="" src="{{ asset('asset/client/images/user2.jpg') }}"> </div>
              <div class="client-title"><strong>joseph Lui</strong></div>
              <div class="client-designation mb_10"> - php Developer</div>
              <p><i class="fa fa-quote-left" aria-hidden="true"></i>Lorem ipsum dolor sit amet, volumus oporteat
                his at sea in Rem ipsum dolor sit amet, sea in odio ..</p>
            </div>
            <div class="item client-detail">
              <div class="client-avatar"> <img alt="" src="{{ asset('asset/client/images/user3.jpg') }}"> </div>
              <div class="client-title"><strong>joseph Lui</strong></div>
              <div class="client-designation mb_10"> - php Developer</div>
              <p><i class="fa fa-quote-left" aria-hidden="true"></i>Lorem ipsum dolor sit amet, volumus oporteat
                his at sea in Rem ipsum dolor sit amet, sea in odio ..</p>
            </div>
          </div>
        </div>
      </div>
      <!-- =====  testimonial end ===== -->
    </div> --}}
  </div>
  <div class="row">
    <div class="col-md-3 footer-block">
      <h6 class="footer-title ptb_20">Về Chúng Tôi</h6>
      <ul>
        <li><a href="#">Thông tin giao hàng</a></li>
        <li><a href="#">Chính sách bảo mật</a></li>
        <li><a href="#">Điều khoản & Điều kiện</a></li>
        <li><a href="#">Liên hệ với chúng tôi</a></li>
      </ul>
    </div>
    <div class="col-md-3 footer-block">
      <h6 class="footer-title ptb_20">Dịch Vụ</h6>
      <ul>
        <li><a href="#">Bản đồ</a></li>
        <li><a href="#">Danh sách yêu thích</a></li>
        <li><a href="#">Tài khoản của tôi</a></li>
        <li><a href="#">Lịch sử đặt hàng</a></li>
      </ul>
    </div>
    <div class="col-md-3 footer-block">
      <h6 class="footer-title ptb_20">Tiện ích bổ sung</h6>
      <ul>
        <li><a href="#">Thương hiệu</a></li>
        <li><a href="#">Giấy chứng nhận quà tặng</a></li>
        <li><a href="#">Khuyến mãi</a></li>
        <li><a href="#">Bản tin</a></li>
      </ul>
    </div>
    <div class="col-md-3 footer-block">
      <h6 class="footer-title ptb_20">Liên Hệ</h6>
      <ul>
        <li>Cơ sở 1 Mỹ Yên Tây Ninh</li>
        <li>Cơ sở 2 Mỹ Tho Đồng Tháp</li>
        <li>0346792997
        </li>
        <li>MinMupShop@gmail.com</li>
      </ul>
    </div>
  </div>
  </div>
  <div class="footer-bottom mt_60 ptb_20">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="social_icon">
            <ul>
              <li><a href="https://www.facebook.com/profile.php?id=100026087362147&mibextid=LQQJ4d"><i class="fa fa-facebook"></i></a></li>
              <li><a href="#"><i class="fa fa-google"></i></a></li>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
              <li><a href="#"><i class="fa fa-twitter"></i></a></li>
              <li><a href="#"><i class="fa fa-rss"></i></a></li>
            </ul>
          </div>
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
          <div class="payment-icon text-right">
            <ul>
              <li><i class="fa fa-cc-paypal "></i></li>
              <li><i class="fa fa-cc-visa"></i></li>
              <li><i class="fa fa-cc-discover"></i></li>
              <li><i class="fa fa-cc-mastercard"></i></li>
              <li><i class="fa fa-cc-amex"></i></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- =====  FOOTER END  ===== -->
  </div>
  <a id="scrollup"></a>
  @if (Session::has('success'))
  <span id="toast__js" message="{{ session('success') }}" type="success"></span>
  @elseif (Session::has('error'))
  <span id="toast__js" message="{{ session('error') }}" type="error"></span>
  @endif
  <script defer src="{{ asset('asset/client/js/jQuery_v3.1.1.min.js') }}"></script>
  <script defer src="{{ asset('asset/client/js/owl.carousel.min.js') }}"></script>
  <script defer src="{{ asset('asset/client/js/bootstrap.min.js') }}"></script>
  <script defer src="{{ asset('asset/client/js/jquery.magnific-popup.js') }}"></script>
  <script defer src="{{ asset('asset/client/js/jquery.firstVisitPopup.js') }}"></script>
  <script defer src="{{ asset('asset/client/js/custom.js') }}"></script>
  <script defer src="{{ asset('asset/admin/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <script defer src="{{ asset('asset/admin/plugins/jquery-validation/jquery.validate.js') }}"></script>

  @vite(['resources/admin/js/toast-message.js'])
  <script>
    // Gentle on-scroll animations using IntersectionObserver
    (function() {
      function ready(fn) {
        if (document.readyState != 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
      }
      ready(function() {
        var selectors = [
          '.product-thumb__img-product', '.brand .item', '.footer-block', '.main-search', '.navbar-brand', '.nav li', '.shopcart', '.heading-part', '.type-01 .item'
        ];
        var nodes = [];
        selectors.forEach(function(s) {
          document.querySelectorAll(s).forEach(function(el) {
            nodes.push(el);
          });
        });

        nodes = Array.from(new Set(nodes));
        nodes.forEach(function(el) {
          el.classList.add('animate-on-scroll');
        });

        if ('IntersectionObserver' in window) {
          var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
              if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                io.unobserve(entry.target);
              }
            });
          }, {
            threshold: 0.12
          });
          nodes.forEach(function(el) {
            io.observe(el);
          });
        } else {
          // fallback
          nodes.forEach(function(el) {
            el.classList.add('in-view');
          });
        }
      });
    })();
  </script>

  <!-- Chatbox AI -->
  @include('components.chatbox')
</body>

</html>