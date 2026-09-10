<?php

namespace App\Services;

use App\Repository\Eloquent\OrderRepository;
use App\Repository\Eloquent\UserRepository;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class DashboardService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * DashboardService constructor.
     *
     * @param OrderRepository $orderRepository
     * @param UserRepository  $userRepository
     */
    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository  = $userRepository;
    }

    /**
     * Dashboard tổng quan
     */
    public function index($request)
    {
        // ===== 1. Xử lý khoảng ngày filter trên dashboard =====
        // Mặc định: đầu tháng -> cuối tháng hiện tại
        $startDay = Carbon::now()->startOfMonth()->toDateString();
        $endDay   = Carbon::now()->endOfMonth()->toDateString();

        $param       = $request->get('reservation');      // "d/m/Y - d/m/Y"
        $reservation = explode(' - ', $param);

        if (
            $param !== null
            && $this->isValidDate($reservation[0] ?? '')
            && $this->isValidDate($reservation[1] ?? '')
        ) {
            $startDay = Carbon::createFromFormat('d/m/Y', trim($reservation[0]))->format('Y-m-d');
            $endDay   = Carbon::createFromFormat('d/m/Y', trim($reservation[1]))->format('Y-m-d');
        }

        // ===== 2. Chỉ số tổng quan =====

        // Tổng doanh thu (giữ logic cũ: đơn status = 3)
        $revenue = $this->orderRepository->getRevenue();
        $revenue = $revenue[0]->total ?? 0;

        // Lấy danh sách đơn hàng trong khoảng ngày (không lấy Đã Hủy)
        $ordersList = $this->orderRepository->getOrdersByRange($startDay, $endDay);
        // Tổng đơn hàng = đúng số record trong danh sách
        $orders = $ordersList->count();

        // Tổng sản phẩm tồn kho
        $products = $this->orderRepository->getProductTotal();

        // Lợi nhuận (đơn đã hoàn tất, logic cũ)
        $profit = $this->orderRepository->getProfit();

        // Tổng khách hàng
        $users = count($this->userRepository->all());

        // Tổng nhân sự / admin
        $admins = count($this->userRepository->admins());

        // ===== 3. Thống kê doanh thu theo ngày trong khoảng đã chọn =====
        $salesStatisticsByDays = $this->orderRepository->salesStatisticsByFromTo($startDay, $endDay);

        $daysArray  = [];
        $parameters = [];

        foreach ($salesStatisticsByDays as $day) {
            $daysArray[]                    = $day->report_date;
            $parameters[$day->report_date] = $day->total_revenue;
        }

        // ===== 4. Sản phẩm bán chạy =====
        $bestSellProducts         = $this->orderRepository->bestSellProducts();
        $labelBestSellProduct     = [];
        $parameterBestSellProduct = [];

        foreach ($bestSellProducts as $product) {
            $labelBestSellProduct[]     = $product->name;
            $parameterBestSellProduct[] = $product->total_sold;
        }

        // ===== 5. Sản phẩm được đánh giá nhiều nhất =====
        $bestProductReviews          = $this->orderRepository->bestProductReviews();
        $labelBestProductReview      = [];
        $parameterBestProductReview  = [];

        foreach ($bestProductReviews as $product) {
            $labelBestProductReview[]     = $product->name;
            $parameterBestProductReview[] = $product->sum;
        }

        // ===== 6. Đơn hàng gần đây (HIỆN HẾT, KHÔNG GIỚI HẠN 10) =====
        // Dùng luôn danh sách $ordersList để đảm bảo số đơn trùng với card "Tổng Đơn Hàng"
        $list = $ordersList;

        $tableCrud = [
            'headers' => [
                [
                    'text' => 'Mã HD',
                    'key'  => 'id',
                ],
                [
                    'text' => 'Tên KH',
                    'key'  => 'user_name',
                ],
                [
                    'text' => 'Email',
                    'key'  => 'user_email',
                ],
                [
                    'text'   => 'Tổng Tiền',
                    'key'    => 'total_money',
                    'format' => true,
                ],
                [
                    'text' => 'PT Thanh Toán',
                    'key'  => 'payment_name',
                ],
                [
                    'text' => 'Ngày Đặt Hàng',
                    'key'  => 'created_at',
                ],
                [
                    'text'   => 'Trạng Thái',
                    'key'    => 'order_status',
                    'status' => [
                        [
                            'text'  => 'Chờ Xử Lý',
                            'value' => 0,
                            'class' => 'badge bg-warning',
                        ],
                        [
                            'text'  => 'Đã xác nhận',
                            'value' => 1,
                            'class' => 'badge bg-info',
                        ],
                        [
                            'text'  => 'Đã Hủy',
                            'value' => 2,
                            'class' => 'badge bg-danger',
                        ],
                        [
                            'text'  => 'Đã Nhận Hàng',
                            'value' => 3,
                            'class' => 'badge bg-success',
                        ],
                        [
                            'text'  => 'Đang Giao Hàng',
                            'value' => 4,
                            'class' => 'badge bg-info',
                        ],
                    ],
                ],
            ],
            'actions' => [
                'text'        => 'Thao Tác',
                'create'      => false,
                'createExcel' => false,
                'edit'        => true,
                'deleteAll'   => false,
                'delete'      => true,
                'viewDetail'  => false,
            ],
            'routes' => [
                'delete' => 'admin.orders_delete',
                'edit'   => 'admin.orders_edit',
            ],
            'list' => $list,
        ];

        return [
            'title'                      => TextLayoutTitle('dashboard'),
            'revenue'                    => $revenue,
            'orders'                     => $orders,               // ✅ card "Tổng Đơn Hàng"
            'products'                   => $products,
            'profit'                     => $profit,
            'users'                      => $users,
            'days'                       => json_encode($daysArray),
            'parameters'                 => json_encode($parameters),
            'tableCrud'                  => $tableCrud,            // ✅ bảng "Đơn Hàng Gần Đây" = cùng 31 đơn
            'labelBestSellProduct'       => json_encode($labelBestSellProduct),
            'parameterBestSellProduct'   => json_encode($parameterBestSellProduct),
            'labelBestProductReview'     => json_encode($labelBestProductReview),
            'parameterBestProductReview' => json_encode($parameterBestProductReview),
            'admins'                     => $admins,
        ];
    }

    /**
     * Trang "Thống Kê Chi Tiết"
     */
    public function statistical($request)
    {
        // Mặc định: 3 tháng gần đây
        $startDay = Carbon::now()->subMonths(3)->startOfMonth()->toDateString();
        $endDay   = Carbon::now()->endOfMonth()->toDateString();

        $param       = $request->get('reservation');
        $reservation = explode(' - ', $param);

        if (
            $param !== null
            && $this->isValidDate($reservation[0] ?? '')
            && $this->isValidDate($reservation[1] ?? '')
        ) {
            $startDay = Carbon::createFromFormat('d/m/Y', trim($reservation[0]))->format('Y-m-d');
            $endDay   = Carbon::createFromFormat('d/m/Y', trim($reservation[1]))->format('Y-m-d');
        }

        $statisticalRevenueAndProfit = $this->orderRepository->statistical($startDay, $endDay);

        $revenue     = 0;
        $profit      = 0;
        $fee         = 0;
        $totalImport = 0;

        foreach ($statisticalRevenueAndProfit as $item) {
            $revenue     += $item->revenue;
            $profit      += $item->profit;
            $fee         += $item->transport_fee;
            $totalImport += $item->total_import;
        }

        $tableStatisRevAndPro = [
            'headers' => [
                [
                    'text' => 'Mã HD',
                    'key'  => 'id',
                ],
                [
                    'text' => 'Phương Thức TT',
                    'key'  => 'name',
                ],
                [
                    'text'   => 'Tổng Tiền',
                    'key'    => 'revenue',
                    'format' => true,
                ],
                [
                    'text'   => 'Tổng Tiền Nhập Hàng',
                    'key'    => 'total_import',
                    'format' => true,
                ],
                [
                    'text'   => 'Phí Vận Chuyển',
                    'key'    => 'transport_fee',
                    'format' => true,
                ],
                [
                    'text'   => 'Lợi Nhuận',
                    'key'    => 'profit',
                    'format' => true,
                ],
                [
                    'text' => 'Ngày Đặt Hàng',
                    'key'  => 'created_at',
                ],
            ],
            'actions' => [
                'text'        => 'Thao Tác',
                'create'      => false,
                'createExcel' => false,
                'edit'        => false,
                'deleteAll'   => false,
                'delete'      => false,
                'viewDetail'  => false,
            ],
            'routes' => [],
            'list'   => $statisticalRevenueAndProfit,
        ];

        return [
            'tableStatisRevAndPro' => $tableStatisRevAndPro,
            'revenue'              => $revenue,
            'profit'               => $profit,
            'fee'                  => $fee,
            'total_import'         => $totalImport,
            'title'                => 'Thống Kê Chi Tiết',
        ];
    }

    /**
     * Check ngày hợp lệ (format d/m/Y)
     */
    function isValidDate($date)
    {
        try {
            $carbonDate = Carbon::createFromFormat('d/m/Y', $date);
            $date       = $carbonDate->format('Y/m/d');
            return strtotime($date) !== false;
        } catch (InvalidFormatException $e) {
            return false;
        }
    }
}
