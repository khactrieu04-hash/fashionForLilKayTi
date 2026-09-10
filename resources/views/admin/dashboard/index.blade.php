@extends('layouts.admin')
@section('content')
    <!-- Main content -->
    <section class="section dashboard">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card" style="background: #5ECAEE;">
                <!-- small box -->
              <x-box-dashboard :data="$revenue" title="Tổng Doanh Thu" route="doanhthu" boxtype="<i class='bi bi-currency-dollar'></i>"/>
              </div>
            </div>
          <!-- ./col -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card" style="background: #9FE080;">
            <!-- small box -->
            <x-box-dashboard :data="$orders" title="Tổng Đơn Hàng" route="donhang" boxtype="<i class='bi bi-cart'></i>"/>
          </div>
        </div>
          <!-- ./col -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card" style="background: #FF771D;">
            <!-- small box -->
            <x-box-dashboard :data="$admins" title="Tổng Nhân Sự" route="sanpham" boxtype="<i class='bi bi-people'></i>"/>
          </div>
        </div>
        <div class="col-xxl-6 col-md-6">
          <div class="card info-card sales-card" style="background: #EE6666;">
            <!-- small box -->
            <x-box-dashboard :data="$profit" title="Tổng Lợi Nhuận" route="loinhuan" boxtype="<i class='bi bi-currency-dollar'></i>"/>
          </div>
        </div>
        <div class="col-xxl-6 col-md-12">
          <div class="card info-card sales-card" style="background: #5470C6">
            <!-- small box -->
            <x-box-dashboard :data="$products" title="Tổng Sản Phẩm Tồn Kho" route="tonkho" boxtype="<i class='bi bi-shop'></i>"/>
          </div>
        </div>
          {{-- <div class="col-lg-4 col-6">
            <!-- small box -->
            <x-box-dashboard :data="$users" title="Tổng Khách Hàng" route="khachhang" boxtype="warning"/>
          </div> --}}
          <div class="col-md-12">
             <!-- STACKED BAR CHART -->
             <div class="card card-success">
              <div class="card-header">
                <div class="row">
                    <div class="card-title col-6">
                        <h3 style="font-size: 18px;font-weight: 600;color: #012970; padding-left: 10px;">Biểu đồ thống kê doanh thu </h3>
                    </div>
                    <form class="card-title col-6 row" method="GET">
                        <div class="col-8">
                            <div class="input-group text-end">
                            <span class="input-group-text">
                             <i class="far fa-calendar-alt"></i>
                            </span>
                                <input type="text" name="reservation" class="form-control float-right" id="reservation">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group">
                                <button class="btn btn-primary"><i class="bi bi-filter-square-fill"></i> Lọc</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="data-statistics" days="{{ $days }}" parameters="{{ $parameters }}"></div>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedBarChart" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                </div>
              </div>
              <div class="card-body">
                <!-- Line Chart -->
                <div id="barChart"></div>
                <!-- End Line Chart -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-12">
            <!-- PIE CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Sản phẩm bán chạy nhất</h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <canvas id="pieChart" label="{{ $labelBestSellProduct }}" data="{{ $parameterBestSellProduct }}" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Đơn Hàng Gần Đây</h3>
              </div>
              <x-table-crud
                :headers="$tableCrud['headers']"
                :list="$tableCrud['list']"
                :actions="$tableCrud['actions']"
                :routes="$tableCrud['routes']"
              />
            </div>
            <!-- /.card -->
          </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    @vite(['resources/admin/js/dashboard.js'])
@endsection
