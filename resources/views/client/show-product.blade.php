@extends('layouts.client')
@section('content-client')
<style>
  /* Button bo tròn hồng */
  .button_group .btn {
    border-radius: 999px !important;
    background-color: var(--accent) !important;
    color: white !important;
    border: none !important;
    padding: 8px 20px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease;
  }

  .button_group .btn:hover {
    background-color: #e91e63de !important;
    color: white !important;
    transform: translateY(-2px);
  }

  /* Product card bo góc */
  .product-thumb {
    border-radius: 8px !important;
    overflow: hidden !important;
  }

  .product-imageblock {
    border-radius: 8px !important;
    overflow: hidden !important;
  }

  .product-imageblock img {
    border-radius: 8px !important;
  }

  .item {
    border-radius: 8px !important;
  }

  /* Tất cả img bo góc */
  img {
    border-radius: 8px !important;
  }
</style>
<div class="container">
  <div class="row ">
    <!-- =====  BANNER STRAT  ===== -->
    <div class="col-sm-12">
      <div class="breadcrumb ptb_20">
        <h1>Danh Sách Sản Phẩm</h1>
      </div>
    </div>
    <!-- =====  BREADCRUMB END===== -->
    <div id="column-left" class="col-sm-4 col-lg-3 ">
      <form action="" method="get">
        <div class="filter left-sidebar-widget mb_50">
          <div class="heading-part mtb_20 ">
            <h2 class="main_title">Danh Mục Sản Phẩm</h2>
          </div>
          <div class="filter-block">
            <div id="slider-range" class="mtb_20"></div>
            <div class="list-group">
              <div class="list-group-item mb_10">
                <label>Color</label>
                <div id="filter-group1">
                  @foreach ($categories as $category)
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" class="check-category" value="{{ $category->slug }}" {{ ($categorySlug == $category->slug) ? 'checked' : '' }} name="category_slug"> {{ $category->name }}
                    </label>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="filter left-sidebar-widget mb_50">
          <div class="heading-part mtb_20 ">
            <h2 class="main_title">Thương Hiệu</h2>
          </div>
          <div class="filter-block">
            <div id="slider-range" class="mtb_20"></div>
            <div class="list-group">
              <div class="list-group-item mb_10">
                <div id="filter-group1">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" class="check-branch" value="" name="brand_id" {{ ($request->brand_id == '') ? 'checked' : '' }}> Tất cả
                    </label>
                  </div>
                  @foreach ($brands as $brand)
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" class="check-branch" value="{{ $brand->id }}" {{ ($request->brand_id == $brand->id) ? 'checked' : '' }} name="brand_id"> {{ $brand->name }}
                    </label>
                  </div>
                  @endforeach
                </div>
                <div class="heading-part mtb_20 ">
                  <h2 class="main_title">Khoảng Giá</h2>
                </div>
                <div class="form-group" style="display: flex; align-items: center;">
                  <input type="text" id="min-price" class="form-control price-filter" value="{{ $request->min_price ?? '' }}" placeholder="Giá từ" name="min_price">
                  <span style="border-top: 1px; width: 50px;"></span>
                  <input type="text" id="max-price" class="form-control price-filter" value="{{ $request->max_price ?? '' }}" placeholder="Giá đến" name="max_price">
                </div>
                <button id="filter-price" class="btn btn-primary" url="{{ $request->fullUrl() }}">Lọc Sản Phẩm</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="left-special left-sidebar-widget mb_50">
        <div class="heading-part mb_10 ">
          <h2 class="main_title">Sản Phẩm Bán Chạy</h2>
        </div>
        <div id="left-special" class="owl-carousel">
          <ul class="row ">
            @foreach ($bellingProducts as $bellingProduct)
            <li class="item product-layout-left mb_20">
              <div class="product-list col-xs-4">
                <div class="product-thumb">
                  <div class="image product-imageblock">
                    <a href="{{ route('user.products_detail', $bellingProduct->id) }}">
                      <img loading="lazy" class="img-responsive" title="iPod Classic" alt="iPod Classic" src="{{ asset("asset/client/images/products/small/$bellingProduct->img") }}">
                      <img loading="lazy" class="img-responsive" title="iPod Classic" alt="iPod Classic" src="{{ asset("asset/client/images/products/small/$bellingProduct->img") }}">
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-xs-8">
                <div class="caption product-detail">
                  <h6 class="product-name"><a href="{{ route('user.products_detail', $bellingProduct->id) }}">{{ $bellingProduct->name }}</a></h6>
                  <div class="rating">
                    <x-avg-stars :number="$bellingProduct->avg_rating" />
                  </div>
                  <span class="price"><span class="amount"><span class="currencySymbol"></span>{{ format_number_to_money($bellingProduct->price_sell) }} VND</span>
                  </span>
                </div>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-sm-8 col-lg-9 mtb_20">
      <div class="row">
        @if (count($products) > 0)
        @foreach ($products as $product)
        <div class="product-layout  product-grid  col-md-4 col-sm-6 col-xs-12 ">
          <div class="item">
            <div class="product-thumb clearfix mb_30">
              <div class="image product-imageblock"> <a href="{{ route('user.products_detail', $product->id) }}">
                  <img loading="lazy" style="height: 400px; object-fit: contain;" data-name="product_image" src="{{ asset("asset/client/images/products/small/$product->img") }}" alt="iPod Classic" title="iPod Classic" class="img-responsive" />
                  <img loading="lazy" style="height: 400px; object-fit: contain;" src="{{ asset("asset/client/images/products/small/$product->img") }}" alt="iPod Classic" title="iPod Classic" class="img-responsive" /> </a>
              </div>
              <div class="caption product-detail text-center">
                <h6 data-name="product_name" class="product-name mt_20">
                  <a href="{{ route('user.products_detail', $product->id) }}" style="display: inline-block; height: 40px;" title="Casual Shirt With Ruffle Hem">{{ $product->name }}</a>
                </h6>
                <div class="rating">
                  <x-avg-stars :number="$product->avg_rating" />
                </div>
                <span class="price"><span class="amount"><span class="currencySymbol"></span>{{ format_number_to_money($product->price_sell) }} VND</span>
                </span>
              </div>
              <div class="button_group">
                <a href="{{ route('user.products_detail', $product->id) }}" class="btn btn-primary" type="button">Xem Chi Tiết</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        @else
        <h3 class="title" style="padding-top: 20px;">Không có sản phẩm</h3>
        @endif
      </div>
      <div class="pagination-nav text-center mt_50">
        @if (count($products) > 0)
        <div class="text-center">
          <ul class="pagination">
            {{ $products->links('vendor.pagination.default') }}
          </ul>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@vite(['resources/client/js/show-product.js'])
@endsection