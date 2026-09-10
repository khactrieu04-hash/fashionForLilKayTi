@extends('layouts.client')
@section('content-client')
<style>
  .title-steps{
    padding: 20px 0px;
    font-weight: 600;
  }
</style>
<div class="container_fullwidth">
    <div class="container">
      <form action="{{ route('checkout.index') }}" method="POST" id="form__js">
        <input style="visibility: hidden;" id="total-order-input" value="{{ Cart::getTotal() }}" type="text" hidden>
        <input style="visibility: hidden;" id="total-order-const" value="{{ Cart::getTotal() }}" type="text" hidden>
        <input style="visibility: hidden;" id="address" value="" type="text" hidden name="address">
        @csrf
        <div class="row">
          <div class="col-md-7">
            <ol class="checkout-steps">
                <h4 class="title-steps">
                  Thông Tin Cá Nhân
                </h4>
                <div class="step-description">
                  <div class="your-details">
                    <div class="form-group">
                      <label for="exampleInputPassword1">Họ Và Tên</label>
                      <input type="text" class="form-control" value="{{ $fullName }}" id="name" name="name" placeholder="Nhập họ và tên">
                      @if ($errors->get('name'))
                        <span id="name-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('name')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Email</label>
                      <input type="text" class="form-control" value="{{ $email }}" id="email" name="email" placeholder="Nhập địa chỉ email">
                      @if ($errors->get('email'))
                        <span id="email-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('email')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Số điện thoại</label>
                      <input type="text" class="form-control" value="{{ $phoneNumber }}" id="phone_number" name="phone_number" placeholder="Nhập số điện thoại">
                      @if ($errors->get('phone_number'))
                        <span id="phone_number-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('phone_number')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Tỉnh, Thành Phố</label>
                      <select class="form-control form-select" id="city" name="city">
                        @foreach ($citys as $item)
                            <option value="{{ $item['ProvinceID'] }}"
                            @if ( $item['ProvinceID'] == $city)
                                selected
                            @endif
                            >{{ $item['NameExtension'][1] }}</option>
                        @endforeach
                      </select>
                      @if ($errors->get('city'))
                        <span id="city-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('city')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Quận, Huyện</label>
                      <select class="form-control form-select" id="district" name="district">
                        @foreach ($districts as $item)
                            <option value="{{ $item['DistrictID'] }}"
                            @if ( $item['DistrictID'] == $district)
                                selected
                            @endif
                            >{{ $item['DistrictName'] }}</option>
                        @endforeach
                      </select>
                      @if ($errors->get('district'))
                        <span id="district-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('district')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Phường Xã</label>
                      <select class="form-control form-select" id="ward" name="ward">
                        @foreach ($wards as $item)
                            <option value="{{ $item['WardCode'] }}"
                            @if ( $item['WardCode'] == $ward)
                              selected
                            @endif
                            >{{ $item['WardName'] }}</option>
                        @endforeach
                      </select>
                      @if ($errors->get('ward'))
                        <span id="ward-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('ward')) }}
                        </span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Địa Chỉ Nhà</label>
                      <input type="text" class="form-control" value="{{ $apartment_number}}" id="apartment_number" name="apartment_number" aria-describedby="emailHelp" placeholder="Nhập địa chỉ nhà">
                      @if ($errors->get('apartment_number'))
                        <span id="apartment_number-error" class="error invalid-feedback" style="display: block">
                          {{ implode(", ",$errors->get('apartment_number')) }}
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
            </ol>
          </div>
          <div class="col-md-5">
            <div>
                <ol class="checkout-steps">
                    <h4 class="title-steps">
                      Thông Tin Đơn Hàng
                    </h4>
                    <div class="step-description">
                      <div class="your-details">
                        <div class="info-order">
                          <div class="info__order-box">
                            <span>Tổng tiền sản phẩm</span>
                            <span id="total-product">{{ format_number_to_money(Cart::getTotal()) }}</span>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="info__order-box">
                            <span>Phí vận chuyển</span>
                            <span id="fee">0</span>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="info__order-box">
                            <span>Áp dụng giảm giá</span>
                            <span>0</span>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="info__order-box">
                            <span>Tổng đơn hàng</span>
                            <span id="total-order">0</span>
                          </div>
                        </div>
                        <div class="info-order">
                          <div class="payment-method">
                            <span>Chọn phương thức thanh toán</span>
                            @if ($errors->get('payment_method'))
                              <span id="payment_method-error" class="error invalid-feedback" style="display: block">
                                {{ implode(", ",$errors->get('payment_method')) }}
                              </span>
                            @endif
                          </div>
                          @foreach ($payments as $payment)
                          <div class="payment-method-select">
                            <label for="{{ $payment->id }}" class="payment-method-select--check">
                              <div>
                                <input type="radio" value="{{ $payment->id }}" name="payment_method" id="{{ $payment->id }}" @if ($payment->id == 1)
                                checked
                                @endif>
                                <span class="label-momo">
                                  {{ $payment->name }}
                                </span>
                              </div>
                              <img src="{{ asset("asset/imgs/$payment->img") }}" alt="">
                            </label>
                          </div>
                          @endforeach
                          <div style="padding-top: 40px;" class="text-center">
                            <button class="btn btn-primary">Thanh Toán Đơn Hàng</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </ol>
            </div>
          </div>
        </div>
      </form>
      <div class="clearfix">
      </div>
    </div>
  </div>
@vite(['resources/client/js/checkout.js', 'resources/client/css/checkout.css'])

@endsection