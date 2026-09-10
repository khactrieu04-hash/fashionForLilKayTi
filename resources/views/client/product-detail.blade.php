@extends('layouts.client')
@section('content-client')
<style>
  .product_name--link{
    pointer-events: none;
  }
  .star{
    visibility: hidden !important;
    width: 0px;
    height: 0px;
  }
  .rating .fa-star{
    color: #b1b1b1;
  }
</style>
<div class="container">
    <div class="row ">
      <!-- =====  BREADCRUMB END===== -->
      <div class="col-12 mtb_20">
        <div class="row mt_10 ">
          <div class="col-md-4">
            <div class="text-center">
                <a class="thumbnails"> 
                    <img data-name="product_image" src="{{ asset("asset/client/images/products/small/$product->img") }}" alt="" />
                </a>
            </div>
            <div id="product-thumbnail" class="owl-carousel">
              @foreach ($productColor as $color)
                <div class="item">
                  <div class="image-additional">
                    <a class="thumbnail" href="{{ asset("asset/client/images/products/small/$color->img") }}" data-fancybox="group1"> 
                      <img style="height: 100px; object-fit: contain;" src="{{ asset("asset/client/images/products/small/$color->img") }}" alt="" />
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="col-md-8 prodetail caption product-thumb" style="padding-bottom: 30px;">
            <h4 data-name="product_name" class="product-name">
              <a class="product_name--link" title="Casual Shirt With Ruffle Hem">{{ $product->name }}</a>
            </h4>
            <div class="rating">
              <x-avg-stars :number="$avgRating" />
            </div>
            <span class="price mb_20">
              <span class="amount">{{ format_number_to_money($product->price_sell) }} VNĐ</span>
            </span>
            <hr>
            <ul class="list-unstyled product_info mtb_20">
              <li>
                <label>Mã Sản Phẩm:</label>
                <span>{{ $product->id }}</span>
              </li>
              <li>
              <li>
                <label>Thương Hiệu:</label>
                <span>{{ $product->brand->name }}</span>
              </li>
              <li>
                <label>Đã bán:</label>
                <span>{{ $productSold->sum ?? 0}}</span>
              </li>
            </ul>
            <hr>
            <p class="product-desc mtb_30">{!! $product->description !!}</p>
            <form action="{{ route('cart.store') }}" method="POST">
              @csrf
              <div id="product" style="padding-top: 10px;">
                <div class="form-group">
                  <div class="row">
                    <div class="Color col-md-3">
                      <label>Màu Sắc</label>
                      <select name="product_color" id="data-color" class="selectpicker form-control">
                        @foreach ($productColor as $color)
                          <option value="{{ $color->id }}">
                            {{ $color->color_name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="Sort-by col-md-3">
                      <label>Kích Thước</label>
                      <select name="id" data-sizes="{{ json_encode($productSize) }}" id="data-size" class="selectpicker form-control">
                       
                      </select>
                    </div>
                    <div class="Color col-md-3">
                      <label>Số lượng</label>
                      <input name="quantity" min="1" value="1" type="number">
                    </div>
                    <div class="Color col-md-3">
                      <label>Số lượng Còn</label>
                      <input disabled id="quantity_remain" type="number">
                    </div>
                  </div>
                </div>
                <div class="button-group mt_30">
                  <div class="text-center">
                    <button class="btn btn-primary">Thêm Vào Giỏ Hàng</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="heading-part text-center mb_10">
              <h2 class="main_title mt_50">Sản Phẩm Liên Quan</h2>
            </div>
            <div class="related_pro box">
              <div class="product-layout  product-grid related-pro  owl-carousel mb_50">
                @foreach ($relatedProducts as $relatedProduct)
                  <div class="item">
                    <div class="product-thumb">
                      <div class="image product-imageblock"> 
                        <a href="{{ route('user.products_detail', $relatedProduct->id) }}"> 
                          <img style="height: 450px; object-fit: contain;" data-name="product_image" src="{{ asset("asset/client/images/products/small/$relatedProduct->img") }}" alt="iPod Classic" title="iPod Classic" class="img-responsive"> 
                          <img style="height: 450px; object-fit: contain;" src="{{ asset("asset/client/images/products/small/$relatedProduct->img") }}" alt="iPod Classic" title="iPod Classic" class="img-responsive"> 
                        </a>
                      </div>
                      <div class="caption product-detail text-center">
                        <h6 data-name="product_name" class="product-name mt_20">
                          <a href="{{ route('user.products_detail', $relatedProduct->id) }}" title="Casual Shirt With Ruffle Hem">{{ $relatedProduct->name }}</a>
                        </h6>
                        <div class="rating">
                          <x-avg-stars :number="$relatedProduct->avg_rating" />
                        </div>
                        <span class="price"><span class="amount">{{ format_number_to_money($relatedProduct->price_sell) }} VNĐ</span>
                        </span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      @if ($checkReviewProduct)
      <div class="col-md-12">
        <div class="heading-part text-center mb_10">
          <h2 class="main_title mt_50">Đánh Giá Sản Phẩm</h2>
        </div>
        <div class="form-row">
          <form method="POST" action="{{ route('product_review.store', $product->id) }}">
            @csrf
            <label class="review-lable">
              Chọn sao cho sản phẩm
            </label>
            <div class="rating">
              <input class="star" type="radio" hidden id="star1" name="rating" value="1" />
              <label for="star1" title="Poor" id="icon-star1">
                  <i class="fas fa-star"></i>
              </label>
              <input class="star" type="radio" hidden id="star2" name="rating" value="2" />
              <label for="star2" title="Fair" id="icon-star2">
                  <i class="fas fa-star"></i>
              </label>
              <input class="star" type="radio" hidden id="star3" name="rating" value="3" />
              <label for="star3" title="Good" id="icon-star3">
                  <i class="fas fa-star"></i>
              </label>
              <input class="star" type="radio" hidden id="star4" name="rating" value="4" />
              <label for="star4" title="Very Good" id="icon-star4">
                  <i class="fas fa-star"></i>
              </label>
              <input class="star" type="radio" hidden id="star5" name="rating" value="5" />
              <label for="star5" title="Excellent" id="icon-star5">
                  <i class="fas fa-star"></i>
              </label>
            </div>
            <div class="form-group required">
              <div class="col-sm-12">
                <label class="control-label" for="input-review">Nội Dung Đánh Giá</label>
                <textarea name="content" rows="5" id="input-review" class="form-control"></textarea>
                <div class="help-block">
                  <button class="btn btn-primary">Đánh Giá</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      @endif
      <div class="col-md-12">
        <div class="heading-part text-center mb_10">
          <h2 class="main_title mt_50">Các Đánh Giá Sản Phẩm</h2>
        </div>
        <div class="col-md-12">
          <div class="review__comment-list" style="padding-top: 30px;">
            <div class="row">
              @if (count($productReviews) > 0)
                  <div class="review__comment-header">
                    <div class="row">
                      <div class="col-sm-4 review__comment-header--title">
                        Thành viên
                      </div>
                      <div class="col-sm-8 review__comment-header--title">
                        Nội dung đánh giá
                      </div>
                    </div>
                  </div>
                  <div class="review__comment-list" style="padding-top: 30px;">
                    <div class="row">
                      @foreach ($productReviews as $productReview)
                        <div class="col-sm-4">
                          <span class="review__comment-author">{{ $productReview->user_name }}</span>
                          <div class="review__comment-time">
                            <span>{{ $productReview->created_at }}</span>
                          </div>
                        </div>
                        <div class="col-sm-8">
                          <div class="review__comment-rating">
                            <x-stars number="{{ $productReview->rating }}"/>
                          </div>
                          <div class="review__comment-content">
                            <p>
                              {{ $productReview->content }}
                            </p>
                          </div>
                        </div>
                        <div class="col-sm-12 review_comment-line"></div>
                      @endforeach
                    </div>
                  </div>
                @else 
                  <p class="text-center review-comment-null">Chưa có đánh giá nào</p>
                @endif
              @if (count($productReviews) > 0)
                <div class="text-center">
                    <ul class="pagination">
                        {{ $productReviews->links('vendor.pagination.default') }}
                    </ul>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@vite(['resources/client/js/product-detail.js', 'resources/client/css/product-review.css'])
@endsection