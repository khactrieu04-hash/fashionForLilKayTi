@extends('layouts.client')
@section('content-client')
<div class="container_fullwidth" style="min-height: calc(100vh - 389px);">
    <div class="container">
        <div class="hot-products">
            <h3 class="title" style="padding: 20px 0px;">
                @if (count($products) > 0)
                    Kết quả tìm kiếm cho từ khoá '<span style="color:#f7544a;">{{ $contentSearch }}</span>'
                @else
                    Chúng tôi không tìm thấy sản phẩm '<span style="color:#f7544a;">{{ $contentSearch }}</span>' nào       
                @endif
            </h3>
            <form class="row" method="GET">
                <input type="hidden" value="{{ $contentSearch }}" hidden name="keyword" >
                <div class="col-sm-3">
                    <div class="form-group">
                        <select class="form-control form-select" name="category">
                            <option disabled selected>Chọn danh mục</option>
                            <option value="" >Tất cả</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ ($category->id == $categoryKey) ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select class="form-control form-select" name="brand">
                            <option disabled selected>Chọn thương hiệu</option>
                            <option value="" >Tất cả</option>
                            @foreach ($brands as $item)
                                <option value="{{ $item->id }}" {{ ($item->id == $brand) ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group" style="display: flex; align-items: center;">
                        <input type="text" class="form-control price-filter" value="{{ $minPrice }}" placeholder="Giá từ" name="min_price">
                        <span style="border-top: 1px; width: 50px;"></span>
                        <input type="text" class="form-control price-filter" value="{{ $maxPrice }}" placeholder="Giá đến" name="max_price">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <button class="price-filter btn btn-primary">Lọc tìm kiếm</button>
                    </div>
                </div>
            </form>
           
            <div class="row">
                @if (count($products) > 0)
                  @foreach ($products as $product)
                      <div class="product-layout  product-grid  col-md-4 col-sm-6 col-xs-12 ">
                          <div class="item">
                          <div class="product-thumb clearfix mb_30">
                              <div class="image product-imageblock"> 
                                <a href="{{ route('user.products_detail', $product->id) }}" style="display: flex; justify-content: center;"> 
                                  <img style="height: 400px; object-fit: contain;" data-name="product_image" src="{{ asset("asset/client/images/products/small/$product->img") }}" alt="iPod Classic" title="iPod Classic" class="img-responsive" /> 
                                  <img style="height: 400px; object-fit: contain;" src="{{ asset("asset/client/images/products/small/$product->img") }}" alt="iPod Classic" title="iPod Classic" class="img-responsive" /> 
                                </a>
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
        </div>
        <div class="text-center">
            <ul class="pagination">
                {{ $products->links('vendor.pagination.default') }}
            </ul>
        </div>
    </div>
</div>
@vite(['resources/client/css/search.css'])
@endsection