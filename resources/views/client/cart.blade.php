@extends('layouts.client')
@section('content-client')
<style>
  td{
    vertical-align: middle!important;
  }
</style>
<div class="container">
  <div class="row ">
    <!-- =====  BANNER STRAT  ===== -->
    <div class="col-sm-12">
      <div class="breadcrumb ptb_20">
        <h1>Giỏ Hàng</h1>
        <ul>
          <li><a href="index.html">Trang chủ</a></li>
          <li class="active">Giỏ hàng</li>
        </ul>
      </div>
    </div>
    <!-- =====  BREADCRUMB END===== -->
    @if (count(\Cart::getContent()) <= 0)
      <h3 class="text-center">Giỏ hàng của bạn đang không có sản phẩm nào</h3>
      <div class="text-center" style="padding-top: 50px">
        <a href="{{ route('user.home') }}" class="btn btn-primary">Mua Ngay</a>
      </div>
    @else
      <div class="col-sm-12 col-lg-12 mtb_20">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="text-center">Hình Ảnh</td>
                  <td class="text-center">Chi Tiết</td>
                  <td class="text-center">Số Lượng</td>
                  <td class="text-center">Giá</td>
                  <td class="text-center">Tổng Tiền</td>
                  <td class="text-center">Thao Tác</td>
                </tr>
              </thead>
              <tbody>
                @foreach ($carts as $cart)
                  <form action="{{ route('cart.update') }}" method="post">
                    @csrf
                    <input class="hidden" type="text" value="{{ $cart->id }}" name="id">
                    <tr>
                      <td class="text-center">
                        <a href="#">
                          <img style="width: 100px;" src="{{ asset("asset/client/images/products/small/" . $cart->attributes->image . "") }}" alt="iPod Classic" title="iPod Classic">
                        </a>
                      </td>
                      <td class="text-center">
                        <div class="shop-details">
                          <div class="productname">
                              {{ $cart->name }}
                          </div>
                          <p>
                              Màu sản phẩm : 
                              <strong>
                                  {{ $cart->attributes->color }}
                              </strong>
                          </p>
                          <p>
                              Kích thước sản phẩm
                              <strong>
                                  {{ $cart->attributes->size }}
                              </strong>
                          </p>
                        </div>
                      </td>
                      <td class="text-center">
                        <div style="max-width: 200px;" class="input-group btn-block">
                          <input type="text" class="form-control quantity" size="1" value="{{ $cart->quantity }}" name="quantity">
                        </div>
                      </td>
                      <td class="text-center">{{ format_number_to_money($cart->price) }}</td>
                      <td class="text-center">{{ format_number_to_money($cart->price * $cart->quantity) }}</td>
                      <td class="text-center">
                        <span class="input-group-btn">
                          <button class="btn btn-primary" title="" data-toggle="tooltip" data-original-title="Cập nhật"><i class="fa fa-refresh"></i></button>
                          <a href="{{ route('cart.delete', $cart->id) }}" class="btn btn-primary" title="" data-toggle="tooltip" data-original-title="Xóa"><i class="fa fa-times-circle"></i></a>
                        </span>
                      </td>
                    </tr>
                  </form>
                @endforeach
              </tbody>
            </table>
          </div>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-8">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td class="text-right"><strong>Tiền Sản Phẩm:</strong></td>
                  <td class="text-right">{{ format_number_to_money(Cart::getTotal()) }} VNĐ</td>
                </tr>
                <tr>
                  <td class="text-right"><strong>Phí Vận Chuyển:</strong></td>
                  <td class="text-right">{{ format_number_to_money($fee) }} VNĐ</td>
                </tr>
                <tr>
                  <td class="text-right"><strong>Tổng tiền:</strong></td>
                  <td class="text-right">{{ format_number_to_money($fee + Cart::getTotal()) }} VNĐ</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <a href="{{ route('user.home') }}" class="btn btn-primary">Tiếp Tục Mua Hàng</a>
        <form action="{{ route('checkout.index') }}">
          <input class="btn pull-right mt_30 btn-primary" type="submit" value="Thanh Toán" />
        </form>
      </div>
    @endif
  </div>
</div>
@endsection