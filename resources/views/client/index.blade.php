@extends('layouts.client')

@section('content-client')
<style>
  :root {
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;

    --primary-pink: #ff1b6b;
    --accent-gold: #ffd700;
    --text-dark: #222;
    --card-border: rgba(0, 0, 0, 0.05);
  }

  /* ================= HEADER TOÀN TRANG ================= */
  /* Nền trắng cho thanh trên và header */
  #top,
  #header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f3f3f3;
  }

  /* Chữ + icon trong header màu hồng */
  #top,
  #top a,
  #top i,
  #top span,
  #header a,
  #header i,
  #header span {
    color: var(--primary-pink) !important;
  }

  /* Riêng tên shop MinMup Shop màu vàng */
  #header .site-name {
    color: var(--accent-gold) !important;
    font-weight: 700;
    letter-spacing: 0.5px;
  }

  /* ================= BANNER ================= */
  .banner {
    margin-top: 15px;
  }

  .main-banner img {
    width: 100%;
    height: auto;
    max-height: 550px;
    object-fit: cover;
    border-radius: var(--radius-lg);
  }

  /* ================= CARD SẢN PHẨM ================= */
  .product-grid {
    padding: 4px;
  }

  .product-grid .product-thumb {
    border-radius: var(--radius-md);
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;
    border: 1px solid var(--card-border);
  }

  .product-grid .product-thumb:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.10);
  }

  .product-imageblock {
    position: relative;
    background: #fafafa;
  }

  /* Ảnh sản phẩm bo góc phía trên */
  .product-thumb__img-product {
    border-radius: var(--radius-md) var(--radius-md) 0 0;
    width: 100%;
    height: 230px;
    object-fit: contain;
    background: #fff;
  }

  /* Caption dưới ảnh */
  .caption.product-detail {
    padding: 12px 10px 6px;
    color: var(--text-dark);
  }

  /* Tên sản phẩm */
  .product-name {
    min-height: 42px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
  }

  .product-name a {
    color: var(--text-dark);
  }

  .product-name a:hover {
    color: var(--primary-pink);
  }

  /* Giá */
  .caption.product-detail .price {
    display: block;
    margin-top: 4px;
    font-weight: 600;
    color: #e74c3c;
  }

  /* ===== NÚT XEM CHI TIẾT ================= */
  .button_group {
    padding: 8px 10px 12px;
    text-align: center;
  }

  .button_group .btn {
    border-radius: 999px;
    /* bo tròn kiểu pill */
    padding: 6px 18px;
    font-size: 13px;
    background-color: var(--primary-pink);
    border-color: var(--primary-pink);
  }

  .button_group .btn:hover,
  .button_group .btn:focus {
    background-color: #e1155f;
    border-color: #e1155f;
  }

  /* ===== OWL CAROUSEL NAV BUTTONS ===== */
  .nArrivals,
  .Bestsellers,
  .Featured {
    position: relative;
  }

  #product-tab {
    position: relative;
  }

  .heading-part {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .nArrivals .owl-nav,
  .Bestsellers .owl-nav,
  .Featured .owl-nav {
    display: flex !important;
    gap: 10px;
  }

  .owl-carousel .owl-nav button {
    background: var(--primary-pink) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 50% !important;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 18px !important;
    transition: all 0.3s ease !important;
    opacity: 0.7;
    cursor: pointer !important;
  }

  .owl-carousel .owl-nav button:hover {
    background: #e1155f !important;
    transform: scale(1.1) !important;
    opacity: 1 !important;
  }

  .owl-carousel .owl-nav button.owl-prev::before {
    content: "❮" !important;
  }

  .owl-carousel .owl-nav button.owl-next::before {
    content: "❯" !important;
  }

  .owl-carousel .owl-nav button:before {
    content: "" !important;
  }

  /* ================= TIÊU ĐỀ KHỐI SẢN PHẨM ================= */
  .heading-part .main_title {
    position: relative;
    display: inline-block;
    padding-bottom: 6px;
    font-weight: 700;
    color: var(--text-dark);
  }

  .heading-part .main_title::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 60%;
    height: 3px;
    border-radius: 999px;
    background: var(--primary-pink);
  }

  /* ================= RESPONSIVE ================= */
  /* Mobile banner thấp hơn chút cho đỡ to */
  @media (max-width: 767px) {
    .main-banner img {
      max-height: 260px;
    }

    .product-thumb__img-product {
      height: 200px;
    }
  }

  /* ===== CĂN LẠI THANH TÌM KIẾM TRONG HEADER ===== */

  /* Ô input trong header (chỉ có 1 ô text nên bắt luôn) */
  #header input[type="text"] {
    height: 48px;
    /* chiều cao ổn hơn */
    line-height: 48px;
    /* text & placeholder nằm đúng giữa */
    padding-top: 0;
    padding-bottom: 0;
  }

  /* Nếu form tìm kiếm là input-group với nút bên phải (bootstrap style) */
  #header #search,
  #header .input-group {
    display: flex;
    align-items: center;
    /* căn giữa input + nút theo trục dọc */
  }

  /* Nút search chứa icon */
  #header #search .input-group-btn>.btn,
  #header .input-group-btn>.btn {
    height: 48px;
    width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    /* icon nằm đúng giữa nút */
    border-radius: 0 999px 999px 0;
    /* nếu muốn bo tròn phía phải */
  }

  /* Icon kính lúp bên trong nút */
  #header .input-group-btn>.btn i {
    font-size: 18px;
  }
</style>

<!-- =====  BANNER START  ===== -->
<div class="banner">
  <div class="main-banner owl-carousel">
    <div class="item">
      <a href="#">
        <img src="{{ asset('asset/client/images/main_banner1.jpg') }}" alt="Main Banner" class="img-responsive" />
      </a>
    </div>
    <div class="item">
      <a href="#">
        <img src="{{ asset('asset/client/images/main_banner2_1.jpg') }}" alt="Main Banner" class="img-responsive" />
      </a>
    </div>
    <div class="item">
      <a href="#">
        <img src="{{ asset('asset/client/images/main_banner3.jpg') }}" alt="Main Banner" class="img-responsive" />
      </a>
    </div>
    <div class="item">
      <a href="#">
        <img src="{{ asset('asset/client/images/main_banner4.jpg') }}" alt="Main Banner" class="img-responsive" />
      </a>
    </div>
  </div>
</div>
<!-- =====  BANNER END  ===== -->

<!-- =====  CONTAINER START  ===== -->
<div class="container">
  <div class="row">
    <div class="col-sm-12 mtb_10">
      <!-- =====  PRODUCT TAB – BÁN CHẠY ===== -->
      <div id="product-tab" class="mt_50">
        <div class="heading-part mb_10">
          <h2 class="main_title">Sản Phẩm Bán Chạy</h2>
        </div>
        <div class="tab-content clearfix box">
          <div class="tab-pane active" id="nArrivals">
            <div class="nArrivals owl-carousel">
              @foreach ($bellingProducts as $bellingProduct)
              <div class="product-grid">
                <div class="item">
                  <div class="product-thumb">
                    <div class="image product-imageblock">
                      <a href="{{ route('user.products_detail', $bellingProduct->id) }}">
                        <img
                          data-name="product_image"
                          src="{{ asset("asset/client/images/products/small/$bellingProduct->img") }}"
                          alt="{{ $bellingProduct->name }}"
                          title="{{ $bellingProduct->name }}"
                          class="img-responsive product-thumb__img-product">
                        <img
                          src="{{ asset("asset/client/images/products/small/$bellingProduct->img") }}"
                          alt="{{ $bellingProduct->name }}"
                          title="{{ $bellingProduct->name }}"
                          class="img-responsive product-thumb__img-product">
                      </a>
                    </div>
                    <div class="caption product-detail text-center">
                      <div class="rating">
                        <x-avg-stars :number="$bellingProduct->avg_rating" />
                      </div>
                      <h6 data-name="product_name" class="product-name">
                        <a href="{{ route('user.products_detail', $bellingProduct->id) }}" title="{{ $bellingProduct->name }}">
                          {{ $bellingProduct->name }}
                        </a>
                      </h6>
                      <span class="price">
                        <span class="amount">
                          <span class="currencySymbol"></span>
                          {{ format_number_to_money($bellingProduct->price_sell) }} VNĐ
                        </span>
                      </span>
                    </div>
                    <div class="button_group">
                      <a href="{{ route('user.products_detail', $bellingProduct->id) }}"
                        class="btn btn-primary"
                        type="button">
                        Xem Chi Tiết
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <!-- =====  PRODUCT TAB  END ===== -->

      <!-- =====  PRODUCT TAB – SẢN PHẨM MỚI NHẤT ===== -->
      <div id="product-tab" class="mt_50">
        <div class="heading-part mb_10">
          <h2 class="main_title">Sản Phẩm Mới Nhất</h2>
        </div>
        <div class="tab-content clearfix box">
          <div class="tab-pane active" id="nArrivals">
            <div class="tab-pane" id="Featured">
              <div class="Featured owl-carousel">
                @foreach ($newProducts as $newProduct)
                <div class="product-grid">
                  <div class="item">
                    <div class="product-thumb mb_30">
                      <div class="image product-imageblock">
                        <a href="{{ route('user.products_detail', $newProduct->id) }}">
                          <img
                            data-name="product_image"
                            src="{{ asset("asset/client/images/products/small/$newProduct->img") }}"
                            alt="{{ $newProduct->name }}"
                            title="{{ $newProduct->name }}"
                            class="img-responsive product-thumb__img-product">
                          <img
                            src="{{ asset("asset/client/images/products/small/$newProduct->img") }}"
                            alt="{{ $newProduct->name }}"
                            title="{{ $newProduct->name }}"
                            class="img-responsive product-thumb__img-product">
                        </a>
                      </div>
                      <div class="caption product-detail text-center">
                        <div class="rating">
                          <x-avg-stars :number="$newProduct->avg_rating" />
                        </div>
                        <h6 data-name="product_name" class="product-name">
                          <a href="{{ route('user.products_detail', $newProduct->id) }}"
                            title="{{ $newProduct->name }}">
                            {{ $newProduct->name }}
                          </a>
                        </h6>
                        <span class="price">
                          <span class="amount">
                            <span class="currencySymbol"></span>
                            {{ format_number_to_money($newProduct->price_sell) }} VNĐ
                          </span>
                        </span>
                      </div>
                      <div class="button_group">
                        <a href="{{ route('user.products_detail', $newProduct->id) }}"
                          class="btn btn-primary"
                          type="button">
                          Xem Chi Tiết
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- =====  PRODUCT TAB  END ===== -->
    </div>
  </div>
</div>
<!-- =====  CONTAINER END  ===== -->

@vite(['resources/client/css/home.css'])
@endsection