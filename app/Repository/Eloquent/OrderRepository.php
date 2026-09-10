<?php

namespace App\Repository\Eloquent;

use App\Models\Order;
use App\Repository\OrderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * OrderRepository constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * Get all orders (trang Quản Lý Đơn Hàng)
     */
    public function getAllOrders($params)
    {
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->select('users.name as user_name', 'users.email as user_email', 'payments.name as payment_name', 'orders.*')
            ->orderByDesc('orders.created_at')
            ->whereNull('orders.deleted_at');

        // Filter trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            if ($params['status'] >= 0 && $params['status'] <= 4) {
                $query->where('order_status', $params['status']);
            }
        }

        // ===== Filter theo khoảng ngày (d/m/Y - d/m/Y) =====
        // Mặc định: 3 tháng gần đây
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate   = Carbon::now()->endOfMonth();

        if (!empty($params['reservation'])) {
            [$fromStr, $toStr] = explode(' - ', $params['reservation']);

            try {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($fromStr))->startOfDay();
                $endDate   = Carbon::createFromFormat('d/m/Y', trim($toStr))->endOfDay();
            } catch (\Exception $e) {
                // Nếu parse lỗi thì giữ default
            }
        }

        $query->whereBetween('orders.created_at', [
            $startDate->toDateTimeString(),
            $endDate->toDateTimeString(),
        ]);

        // Filter phương thức thanh toán
        if (isset($params['payment']) && $params['payment'] !== '') {
            if ($params['payment'] >= 0 && $params['payment'] <= 3) {
                $query->where('payment_id', $params['payment']);
            }
        }

        return $query->get();
    }

    /**
     * Get order details
     */
    public function getOrderDetail($id)
    {
        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products_size', 'order_details.product_size_id', '=', 'products_size.id')
            ->join('sizes', 'products_size.size_id', '=', 'sizes.id')
            ->join('products_color', 'products_size.product_color_id', '=', 'products_color.id')
            ->join('colors', 'products_color.color_id', '=', 'colors.id')
            ->join('products', 'products_color.product_id', '=', 'products.id')
            ->select(
                'orders.*',
                'order_details.unit_price',
                'order_details.quantity',
                'sizes.name as size_name',
                'products_color.img as products_color_img',
                'colors.name as color_name',
                'products.name as product_name',
                'products.id as product_id',
                'products.img as product_img'
            )
            ->where('orders.id', $id)
            ->get();
    }

    /**
     * Get customer information of the order
     */
    public function getInfoUserOfOrder($id)
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->select(
                'users.id as user_id',
                'orders.name as user_name',
                'orders.email as user_email',
                'orders.phone as user_phone_number',
                'orders.address as user_address',
                'payments.name as payment_name',
                'orders.transport_fee as orders_transport_fee'
            )
            ->where('orders.id', $id)
            ->first();
    }

    /**
     * Tổng doanh thu (đơn đã nhận hàng)
     */
    public function getRevenue()
    {
        return DB::select("
            SELECT SUM(total_money - transport_fee) AS total
            FROM orders
            WHERE order_status = 3
        ");
    }

    /**
     * Tổng số đơn hàng (tất cả, trừ Đã Hủy) – dùng chỗ khác nếu cần
     */
    public function getOrderTotal()
    {
        return DB::table('orders')
            ->where('order_status', '!=', 2)
            ->count();
    }

    /**
     * LẤY DANH SÁCH ĐƠN THEO KHOẢNG NGÀY (dùng cho dashboard)
     * - loại bỏ Đã Hủy (order_status != 2)
     */
    public function getOrdersByRange(string $startDate, string $endDate)
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->select(
                'users.name as user_name',
                'users.email as user_email',
                'payments.name as payment_name',
                'orders.*'
            )
            ->whereNull('orders.deleted_at')
            ->where('orders.order_status', '!=', 2) // bỏ đơn đã hủy
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate])
            ->orderByDesc('orders.id')
            ->get();
    }

    /**
     * Tổng đơn hàng theo khoảng ngày (trùng với danh sách trên)
     */
    public function getOrderTotalByRange(string $startDate, string $endDate)
    {
        return $this->getOrdersByRange($startDate, $endDate)->count();
    }

    /**
     * Sản phẩm tồn kho
     */
    public function getProductTotal()
    {
        return DB::table('products_size')
            ->join('products_color', 'products_color.id', '=', 'products_size.product_color_id')
            ->join('products', 'products.id', '=', 'products_color.product_id')
            ->whereNull('products_color.deleted_at')
            ->whereNull('products.deleted_at')
            ->sum('products_size.quantity');
    }

    /**
     * Tổng số lượng sản phẩm đã bán
     */
    public function getTotalProductSold()
    {
        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status', 3)
            ->sum('order_details.quantity');
    }

    /**
     * Lợi nhuận
     */
    public function getProfit()
    {
        return DB::select('
            SELECT
                SUM(order_details.quantity * order_details.unit_price)
                - SUM(order_details.quantity * order_details.import_price) AS profit
            FROM order_details
            JOIN orders ON orders.id = order_details.order_id
            WHERE orders.order_status = 3;
        ')[0]->profit ?? 0;
    }

    /**
     * Sales statistics by day (tháng hiện tại)
     */
    public function salesStatisticsByDay()
    {
        return DB::select('
            SELECT DAY(created_at) AS day, SUM(total_money) AS total
            FROM orders
            WHERE MONTH(orders.created_at) = MONTH(CURRENT_DATE())
              AND YEAR(orders.created_at) = YEAR(CURRENT_DATE())
              AND orders.order_status = 3
            GROUP BY DAY(orders.created_at);
        ');
    }

    /**
     * Sales statistics by from to
     */
    public function salesStatisticsByFromTo($startDate, $endDate)
    {
        return DB::select("
            WITH RECURSIVE DateRange AS (
                SELECT '$startDate' AS report_date
                UNION
                SELECT DATE_ADD(report_date, INTERVAL 1 DAY)
                FROM DateRange
                WHERE report_date < '$endDate'
            )
            SELECT
                dr.report_date,
                IFNULL(SUM(o.total_money - o.transport_fee), 0) AS total_revenue
            FROM
                DateRange dr
            LEFT JOIN
                orders o ON DATE(o.created_at) = dr.report_date
                AND o.order_status = 3
            GROUP BY
                dr.report_date
            ORDER BY
                dr.report_date;
        ");
    }

    /**
     * 10 đơn hàng mới – (vẫn giữ, nếu chỗ khác dùng)
     */
    public function getNewOrders()
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->select('users.name as user_name', 'users.email as user_email', 'payments.name as payment_name', 'orders.*')
            ->whereNull('orders.deleted_at')
            ->orderByDesc('orders.id')
            ->limit(10)
            ->get();
    }

    /**
     * Đơn hàng theo user
     */
    public function getOrderByUser($id)
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('payments', 'orders.payment_id', '=', 'payments.id')
            ->select('users.name as user_name', 'users.email as user_email', 'payments.name as payment_name', 'orders.*')
            ->where('user_id', $id)
            ->whereNull('orders.deleted_at')
            ->orderByDesc('orders.id')
            ->paginate(Order::ORDER_NUMBER_ITEM['history']);
    }

    /**
     * Thống kê chi tiết doanh thu – lợi nhuận
     */
    public function statistical($start, $end)
    {
        return DB::table('orders as o')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            ->join('payments as pm', 'pm.id', '=', 'o.payment_id')
            ->select(
                'o.id',
                'pm.name',
                'o.created_at',
                'o.transport_fee',
                DB::raw('o.total_money as revenue'),
                DB::raw('SUM(od.quantity * od.import_price) as total_import'),
                DB::raw('SUM((od.quantity * od.unit_price) - (od.quantity * od.import_price)) as profit')
            )
            // tính cho ĐÃ XÁC NHẬN + ĐÃ NHẬN HÀNG, tùy ý bạn chỉnh mảng này
            ->whereIn('o.order_status', [1, 3])
            ->whereBetween(DB::raw('DATE(o.created_at)'), [$start, $end])
            ->groupBy('o.id', 'pm.name', 'o.created_at', 'o.transport_fee', 'total_money')
            ->get();
    }

    public function bestSellProducts()
    {
        return DB::select('
        SELECT
            SUM(od.quantity) AS total_sold,
            p.id,
            p.name,
            p.img,
            p.price_sell,
            COALESCE(AVG(pr.rating), 0) AS avg_rating
        FROM orders AS o
        JOIN order_details AS od      ON o.id = od.order_id
        JOIN products_size AS ps      ON ps.id = od.product_size_id
        JOIN products_color AS pc     ON pc.id = ps.product_color_id
        JOIN products AS p            ON p.id = pc.product_id
        LEFT JOIN product_reviews pr  ON pr.product_id = p.id
        WHERE o.order_status = 3
          AND p.deleted_at IS NULL
        GROUP BY
            p.id, p.name, p.img, p.price_sell
        ORDER BY total_sold DESC
        LIMIT 10
    ');
    }

    public function bestProductReviews()
    {
        return DB::select('
            SELECT
                COUNT(*) AS sum,
                products.id,
                products.name
            FROM product_reviews
            JOIN products ON products.id = product_reviews.product_id
            GROUP BY products.id, products.name
            ORDER BY sum DESC
            LIMIT 10;
        ');
    }
}
