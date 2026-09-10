@extends('layouts.admin')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form method="GET">
                            <div class="card-header text-end">
                                <div class="d-flex">
                                    <div class="input-group">
                                    <span class="input-group-text">
                                     <i class="far fa-calendar-alt"></i>
                                    </span>
                                        <input type="text" name="reservation" class="form-control float-right" id="reservation">
                                    </div>
                                    <div style="width: 150px">
                                        <button class="btn btn-primary">Lọc Dữ Liệu</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Tổng Doanh Thu</h5>
                            <div class="d-flex align-items-center">
                                <div class="ps-3">
                                    <h6>{{ format_number_to_money($revenue) }} VND</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Tổng Chi Phí Nhập Hàng </h5>
                            <div class="d-flex align-items-center">
                                <div class="ps-3">
                                    <h6>{{ format_number_to_money($total_import) }} VND</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Tổng Lợi Nhuận</h5>
                            <div class="d-flex align-items-center">
                                <div class="ps-3">
                                    <h6>{{ format_number_to_money($profit) }} VND</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Tổng Chi Phí Vận Chuyển </h5>
                            <div class="d-flex align-items-center">
                                <div class="ps-3">
                                    <h6>{{ format_number_to_money($fee) }} VND</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="col-12">
        <x-table-crud
          :headers="$tableStatisRevAndPro['headers']"
          :list="$tableStatisRevAndPro['list']"
          :actions="$tableStatisRevAndPro['actions']"
          :routes="$tableStatisRevAndPro['routes']"
          :filter="false"
        />
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
    <!-- /.container-fluid -->
</section>
@vite(['resources/admin/js/statistical.js'])
@endsection
