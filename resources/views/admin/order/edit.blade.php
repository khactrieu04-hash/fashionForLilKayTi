@extends('layouts.admin')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Thông tin đơn hàng</h3>
            <div class="card-tools"></div>
          </div>

          {{-- THÔNG TIN KHÁCH HÀNG --}}
          <div class="col-md-12 mt-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Thông tin khách hàng</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Mã KH</th>
                      <th>Họ Tên</th>
                      <th>Số Điện Thoại</th>
                      <th>Email</th>
                      <th>Địa Chỉ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $infomation_user['id'] }}</td>
                      <td>{{ $infomation_user['name'] }}</td>
                      <td>{{ $infomation_user['phone_number'] }}</td>
                      <td>{{ $infomation_user['email'] }}</td>
                      <td>{{ $infomation_user['address']}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          {{-- CHI TIẾT ĐƠN HÀNG --}}
          <div class="col-md-12 mt-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Chi tiết đơn hàng</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 100px;">Mã SP</th>
                      <th>Tên SP</th>
                      <th>Hình Ảnh</th>
                      <th>Màu</th>
                      <th>Kích Thước</th>
                      <th>Số Lượng</th>
                      <th>Đơn Giá</th>
                      <th>Thành Tiền</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $totalProductMoney = 0; ?>
                    @foreach ($order_details as $order_detail)
                    <?php $totalProductMoney +=  $order_detail->unit_price *  $order_detail->quantity; ?>
                    <tr>
                      <td>{{ $order_detail->product_id }}</td>
                      <td>{{ $order_detail->product_name }}</td>
                      <td class="text-center">
                        <img style="width: 70px; height:auto; object-fit: cover;"
                          src="{{ asset("asset/client/images/products/small/$order_detail->products_color_img") }}"
                          alt="">
                      </td>
                      <td>{{ $order_detail->color_name }}</td>
                      <td>{{ $order_detail->size_name }}</td>
                      <td>{{ $order_detail->quantity }}</td>
                      <td>{{ format_number_to_money($order_detail->unit_price) }}</td>
                      <td>{{ format_number_to_money($order_detail->unit_price *  $order_detail->quantity) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="7">Tổng Tiền Sản Phẩm</td>
                      <td><b>{{ format_number_to_money($totalProductMoney) }} VND</b></td>
                    </tr>
                    <tr>
                      <td colspan="7">Phí Vận Chuyển</td>
                      <td><b>{{ format_number_to_money($infomation_user['orders_transport_fee']) }} VND</b></td>
                    </tr>
                    <tr>
                      <td colspan="7">Phương Thức Thanh Toán</td>
                      <td><b>{{ $infomation_user['payment_name'] }}</b></td>
                    </tr>
                    <tr>
                      <td colspan="7">Tổng Tiền Đơn Hàng</td>
                      <td><b>{{ format_number_to_money($order->total_money) }} VND</b></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          {{-- NÚT HÀNH ĐỘNG --}}
          <div class="action col-md-12"
            style="padding-top: 10px;padding-bottom: 20px; padding-right: 30px;padding-left: 30px;">
            {{-- Chỉ cho xử lý khi chưa ĐÃ NHẬN HÀNG --}}
            @if ($order->order_status != 3)
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-lg">
              Xử Lý Đơn Hàng
            </button>
            @endif
            <button class="btn btn-success" style="margin-left: 10px;" id="print-order">In Hóa Đơn</button>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- MODAL XỬ LÝ ĐƠN HÀNG --}}
  <x-modal-view-detail size="modal-lg" title="Xử Lý Đơn Hàng">
    <form action="{{ route('admin.orders_update', $order->id) }}" method="post" class="row g-3">
      @csrf
      <div class="form-group">
        <select class="form-control" name="order_status" id="order_status">
          {{-- thêm đầy đủ trạng thái, có ĐÃ NHẬN HÀNG = 3 --}}
          <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
          <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Xác Nhận</option>
          <option value="4" {{ $order->order_status == 4 ? 'selected' : '' }}>Vận chuyển</option>
          <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Đã nhận hàng</option>
          <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Hủy</option>
        </select>
      </div>
      <div class="form-group">
        <x-admin-input id="note" type="text" name="note" placeholder="Nhập Ghi Chú" />
      </div>
      <div class="form-group text-center">
        <button class="btn btn-primary">
          Xử Lý
        </button>
      </div>
    </form>
  </x-modal-view-detail>

</section>

{{-- dữ liệu cho in hóa đơn --}}
<div id="data-order"
  info-customer="{{ json_encode($infomation_user) }}"
  products="{{ json_encode($order_details) }}">
</div>

@vite(['resources/admin/js/edit-order.js'])
@endsection