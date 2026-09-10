<?php

namespace App\Services;

use App\Helpers\TextSystemConst;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductSize;
use App\Repository\Eloquent\OrderRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrderHistoryService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * OrderService constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        // lấy tất cả đơn hàng thuộc người dùng đang nhập
        $orderHistorys = $this->orderRepository->getOrderByUser(Auth::user()->id);

        return ['orderHistorys' => $orderHistorys];
    }

    public function show(Order $order)
    {
        return [
            //lấy thông tin đơn hàng
            'order' => $order,
            // lấy chi tiết của đơn hàng
            'order_details' => $this->orderRepository->getOrderDetail($order->id),
            // lấy thông tin của khách hàng mua đơn hàng
            'infomationUser' => $this->orderRepository->getInfoUserOfOrder($order->id),
        ];
    }

    public function update(Order $order)
    {
        try {
            switch($order->order_status){
                case 0:
                    // nếu trạng thái đang là chờ xử lý thì cập nhật thành hủy
                    $this->orderRepository->update($order, ['order_status' => Order::STATUS_ORDER['cancel']]);
                    $orderDetails = OrderDetail::where('order_id', $order->id)->get();
                    // khi hủy đơn hàng sẽ hoàn trả số lượng vào lại kho
                    foreach($orderDetails as $orderDetail) {
                        $productSize = ProductSize::where('id', $orderDetail->product_size_id)->first();
                        $productSize->update(['quantity' => $productSize->quantity + $orderDetail->quantity]);
                    }
                    return back()->with('success', TextSystemConst::MESS_ORDER_HISTORY['cancel']);
                case 4:
                    // nếu trạng thái đang là đang giao hàng thì cập nhật thành đã nhận hàng
                    $this->orderRepository->update($order, ['order_status' => Order::STATUS_ORDER['received']]);
                    return back()->with('success', TextSystemConst::MESS_ORDER_HISTORY['confirm']);
                case 2:
                    // nếu trạng thái đang là hủy thì cập thành xóa
                    $this->orderRepository->delete($order);
                    return back()->with('success', TextSystemConst::MESS_ORDER_HISTORY['delete']);
                case 3:
                    // nếu trạng thái đang là đã nhận hàng thì cập thành xóa
                    $this->orderRepository->delete($order);
                    return back()->with('success', TextSystemConst::MESS_ORDER_HISTORY['delete']);
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
?>
