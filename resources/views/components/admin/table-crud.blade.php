@props(['headers', 'list', 'actions', 'routes', 'filter' => false])
<div class="card">
    <!-- /.card-header -->
    @if ($actions['create'] || $actions['createExcel'] || $actions['deleteAll'])
        <div class="card-header">
            <div class="row">
                <div class="col-6 d-flex">
                    @if ($actions['create'])
                        <a
                            href="{{ (isset($routes['create'])) ? route($routes['create']) : '#'}}"
                            class="btn btn-primary next-link__js">
                            Thêm Mới
                        </a>
                    @endif
                    @if ($actions['createExcel'])
                        <button class="btn btn-success ml-1">Excel</button>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @if ($filter)
        <div class="card-header">
            <form action="" method="get">
                <div class="row">
                    <div class="mb-3 col-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                             <i class="far fa-calendar-alt"></i>
                            </span>
                            <input type="text" name="reservation" class="form-control float-right" id="reservation">
                        </div>
                    </div>
                    <div class="mb-3 col-sm-3">
                        <select class="form-select" aria-label="Default select example" name="status">
                            <option value="" {{ (request()->status == '') ? 'selected' : '' }}>Trạng thái đơn hàng (Tất cả)</option>
                            <option value="0" {{ (request()->status == '0') ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="1" {{ (request()->status == '1') ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="1" {{ (request()->status == '4') ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="3" {{ (request()->status == '3') ? 'selected' : '' }}>Đã nhận hàng</option>
                            <option value="2" {{ (request()->status == '2') ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="mb-3 col-sm-3">
                        <select class="form-select" aria-label="Default select example" name="payment">
                            <option value="" {{ (request()->payment == '') ? 'selected' : '' }}>Phương thức TT (Tất cả)</option>
                            <option value="1" {{ (request()->payment == '1') ? 'selected' : '' }}>Khi nhận hàng</option>
                            <option value="2" {{ (request()->payment == '2') ? 'selected' : '' }}>Ví điện tử momo</option>
                            <option value="3" {{ (request()->payment == '3') ? 'selected' : '' }}>VNPAY</option>
                        </select>
                    </div>
                    <div class="mb-3 col-sm-3">
                        <button class="btn btn-primary">Lọc dữ liệu</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <div class="card-body">
        <table id="table-crud" class="table table-bordered table-striped">
            <thead>
                <tr style="text-align: left;">
                    @foreach ($headers as $header)
                        <th style="text-align: left;">{{ $header['text'] }}</th>
                    @endforeach
                    @if($actions['viewDetail'] || $actions['edit'] || $actions['delete'])
                        <th style="text-align: left;">{{ $actions['text'] }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                    <tr id="{{ $item->id }}" style="text-align: left;">
                        @foreach ($headers as $header)
                            <td style="text-align: left;">
                                @if (! isset($header['status']) && ! isset($header['img']))
                                    @php
                                        $value = is_array($item_value = data_get($item, $header['key'])) ? nl2br(implode(PHP_EOL, $item_value)) : $item_value
                                    @endphp
                                    @if (isset($header['format']))
                                        {{ format_number_to_money ($value) }}
                                    @else
                                        {{$value}}
                                    @endif
                                @elseif (isset($header['img']))
                                    @php
                                        $value = is_array($item_value = data_get($item, $header['key'])) ? nl2br(implode(PHP_EOL, $item_value)) : $item_value
                                    @endphp
                                    <img style="{{ $header['img']['style'] }}" src="{{ asset($header['img']['url']) . '/' . $value }}" alt="">
                                @else
                                    @foreach ($header['status'] as $status)
                                        @php
                                            $value = is_array($item_value = data_get($item, $header['key'])) ? nl2br(implode(PHP_EOL, $item_value)) : $item_value
                                        @endphp
                                        @if ($value == $status['value'])
                                            <span class="{{ $status['class'] }}">{{$status['text']}}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                        @if($actions['viewDetail'] || $actions['edit'] || $actions['delete'])
                            <td style="text-align: left;">
                                @if ($actions['edit'])
                                    <a href="{{ isset($routes['edit']) ? route($routes['edit'], $item->id) : '#' }}" id="edit-customer" class="btn btn-primary next-link__js">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if ($actions['viewDetail'])
                                    <button id="btn-table-view-detail__js" class="btn btn-info">
                                        <i class="fas fa-history"></i>
                                    </button>
                                @endif
                                @if ($actions['delete'])
                                    <form style="display: inline;" action="{{route($routes['delete'])}}" method="POST" id="form-delete__js">
                                        @csrf
                                        <input type="text" name="id" value="{{$item->id}}" hidden>
                                    </form>
                                    <button id="delete__js" class="btn btn-danger" url="{{route($routes['delete'])}}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@php
$vite = ['resources/admin/js/table-data.js', 'resources/admin/js/toast-message.js'];
if (request()->is('admin/orders*')) {
    $vite[] = 'resources/admin/js/filter-order.js';
}
@endphp
@vite($vite)
