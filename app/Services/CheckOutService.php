<?php

namespace App\Services;

use App\Http\Requests\CheckOutRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductSize;
use App\Repository\Eloquent\OrderDetailRepository;
use App\Repository\Eloquent\OrderRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckOutService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderDetailRepository
     */
    private $orderDetailRepository;

    /**
     * CheckOutService constructor.
     */
    public function __construct(
        OrderRepository      $orderRepository,
        OrderDetailRepository $orderDetailRepository
    ) {
        $this->orderRepository      = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    /**
     * Lấy dữ liệu hiển thị trang thanh toán
     */
    public function index()
    {
        try {
            $city             = old('city') ?? Auth::user()->address->city;
            $district         = old('district') ?? Auth::user()->address->district;
            $ward             = old('ward') ?? Auth::user()->address->ward;
            $apartment_number = old('apartment_number') ?? Auth::user()->address->apartment_number;
            $phoneNumber      = old('phone_number') ?? Auth::user()->phone_number;
            $fullName         = old('full_name') ?? Auth::user()->name;
            $email            = old('email') ?? Auth::user()->email;

            // province
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');
            $citys = json_decode($response->body(), true);

            // district
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => $city,
            ]);
            $districts = json_decode($response->body(), true);

            // ward
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => $district,
            ]);
            $wards = json_decode($response->body(), true);

            $payments = Payment::where('status', Payment::STATUS['active'])->get();

            return [
                'citys'            => $citys['data'],
                'districts'        => $districts['data'],
                'wards'            => $wards['data'],
                'city'             => $city,
                'district'         => $district,
                'ward'             => $ward,
                'apartment_number' => $apartment_number,
                'phoneNumber'      => $phoneNumber,
                'email'            => $email,
                'fullName'         => $fullName,
                'payments'         => $payments,
            ];
        } catch (Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * Thanh toán COD (hoặc hình thức không online)
     */
    public function store(CheckOutRequest $request)
    {
        try {
            if ($this->checkProductUpdateAfterAddCard()) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Sản phẩm bạn mua đã được thay đổi thông tin');
            }

            $fee = (string) $this->getTransportFee($request->district, $request->ward);

            $dataOrder = [
                'id'           => time() . mt_rand(111, 999),
                'payment_id'   => $request->payment_method,
                'user_id'      => Auth::id(),
                'total_money'  => \Cart::getTotal() + $fee,
                'order_status' => Order::STATUS_ORDER['wait'],
                'transport_fee' => $fee,
                'note'         => null,
                'name'         => Session::get('info_order.name'),
                'email'        => Session::get('info_order.email'),
                'phone'        => Session::get('info_order.phone'),
                'address'      => Session::get('info_order.address'),
            ];

            DB::beginTransaction();

            $order = $this->orderRepository->create($dataOrder);

            foreach (\Cart::getContent() as $product) {
                $orderDetail = [
                    'order_id'        => $order->id,
                    'product_size_id' => $product->id,
                    'unit_price'      => $product->price,
                    'quantity'        => $product->quantity,
                    'import_price'    => $product->attributes->import_price,
                ];
                $this->orderDetailRepository->create($orderDetail);
            }

            DB::commit();
            \Cart::clear();

            return redirect()->route('order_history.index');
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();

            // Nếu lỗi thì sync lại số lượng trong giỏ
            foreach (\Cart::getContent() as $product) {
                $productSize = ProductSize::where('id', $product->id)->first();
                if ($productSize && $productSize->quantity < $product->quantity) {
                    \Cart::update(
                        $product->id,
                        [
                            'quantity' => [
                                'relative' => false,
                                'value'    => $productSize->quantity
                            ],
                        ]
                    );
                }
            }

            return redirect()->route('cart.index')
                ->with('error', 'Có lỗi xảy ra vui lòng kiểm tra lại');
        }
    }

    /**
     * Thanh toán online Momo
     */
    public function paymentMomo(CheckOutRequest $request)
    {
        if ($this->checkProductUpdateAfterAddCard()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Sản phẩm bạn mua đã được thay đổi thông tin');
        }

        $orderId = (string) (time() . mt_rand(111, 999));
        $amount  = (string) (\Cart::getTotal() + $this->getTransportFee($request->district, $request->ward));

        $redirectUrl = route('checkout.callback_momo');
        $ipnUrl      = route('checkout.callback_momo'); // demo: dùng chung 1 URL

        return $this->payWithMoMo($orderId, $amount, $redirectUrl, $ipnUrl);
    }

    /**
     * Lấy phí vận chuyển từ GHN
     */
    public function getTransportFee($district, $ward)
    {
        $fromDistrict = "2027";
        $shopId       = "3577591";

        // Lấy service type
        $response = Http::withHeaders([
            'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
            "shop_id"       => $shopId,
            "from_district" => $fromDistrict,
            "to_district"   => $district,
        ]);

        $serviceId = $response['data'][0]['service_type_id'];

        // Lấy phí
        $dataGetFee = [
            "service_type_id"   => $serviceId,
            "insurance_value"   => 500000,
            "coupon"            => null,
            "from_district_id"  => $fromDistrict,
            "to_district_id"    => $district,
            "to_ward_code"      => $ward,
            "height"            => 15,
            "length"            => 15,
            "weight"            => 1000,
            "width"             => 15
        ];

        $response = Http::withHeaders([
            'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $dataGetFee);

        return $response['data']['total'];
    }

    /**
     * CALLBACK MOMO (redirect + ipn đều bắn về đây)
     */
    public function callbackMomo(Request $request)
    {
        // Nếu muốn chặt chẽ hơn thì bật verify chữ ký:
        // if (!$this->checkSignature($request)) { ... }

        if ((int) $request->resultCode === 0) {
            $district = Session::get('info_order.district');
            $ward     = Session::get('info_order.ward');

            $dataOrder = [
                'id'           => $request->orderId, // orderId bạn đã gửi lúc tạo thanh toán
                'payment_id'   => Payment::METHOD['momo'],
                'user_id'      => Auth::id(),
                'total_money'  => (float) $request->amount,
                'order_status' => Order::STATUS_ORDER['wait'],
                'transport_fee' => $this->getTransportFee($district, $ward),
                'note'         => null,
                'name'         => Session::get('info_order.name'),
                'email'        => Session::get('info_order.email'),
                'phone'        => Session::get('info_order.phone'),
                'address'      => Session::get('info_order.address'),
            ];

            try {
                DB::beginTransaction();

                $order = $this->orderRepository->create($dataOrder);

                foreach (\Cart::getContent() as $product) {
                    $orderDetail = [
                        'order_id'        => $order->id,
                        'product_size_id' => $product->id,
                        'unit_price'      => $product->price,
                        'quantity'        => $product->quantity,
                        'import_price'    => $product->attributes->import_price
                    ];
                    $this->orderDetailRepository->create($orderDetail);
                }

                DB::commit();
                \Cart::clear();

                return redirect()->route('order_history.index');
            } catch (Exception $e) {
                Log::error($e);
                DB::rollBack();
                return redirect()->route('cart.index')
                    ->with('error', 'Thanh toán MoMo thành công nhưng tạo đơn bị lỗi, vui lòng liên hệ hỗ trợ.');
            }
        }

        // Thanh toán thất bại hoặc user hủy
        return redirect()->route('checkout.index')
            ->with('error', 'Thanh toán MoMo không thành công hoặc đã bị hủy.');
    }

    /**
     * Kiểm tra chữ ký MoMo (đang để riêng, bạn có thể dùng nếu cần)
     */
    public function checkSignature(Request $request)
    {
        $partnerCode   = $request->partnerCode;
        $accessKey     = $request->accessKey;
        $requestId     = (string) $request->requestId;
        $amount        = (string) $request->amount;
        $orderId       = (string) $request->orderId;
        $orderInfo     = $request->orderInfo;
        $orderType     = $request->orderType;
        $transId       = $request->transId;
        $message       = $request->message;
        $localMessage  = $request->localMessage;
        $responseTime  = $request->responseTime;
        $errorCode     = $request->errorCode;
        $payType       = $request->payType;
        $extraData     = "";
        $secretKey     = env('MOMO_SECRET_KEY');

        $rawHash = "partnerCode=" . $partnerCode .
            "&accessKey=" . $accessKey .
            "&requestId=" . $requestId .
            "&amount=" . $amount .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&transId=" . $transId .
            "&message=" . $message .
            "&localMessage=" . $localMessage .
            "&responseTime=" . $responseTime .
            "&errorCode=" . $errorCode .
            "&payType=" . $payType .
            "&extraData=" . $extraData;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        return hash_equals($signature, $request->signature);
    }

    /**
     * Gọi API tạo lệnh thanh toán MoMo v2
     */
    public function payWithMoMo($orderId, $amount, $redirectUrl, $ipnUrl)
    {
        $endPoint   = env('MOMO_END_POINT');
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey  = env('MOMO_ACCESS_KEY');
        $serectkey  = env('MOMO_SECRET_KEY');

        $orderInfo  = "Thanh toán qua MoMo";
        $extraData  = "";
        $requestId  = (string) (time() . mt_rand(111, 999));
        $requestType = "captureWallet";

        $rawHash = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $serectkey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "YeenOkShop",
            "storeId"     => "YeenOkShopStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        $result = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ])->post($endPoint, $data);

        $jsonResult = json_decode($result->body(), true);

        return redirect($jsonResult['payUrl']);
    }

    /**
     * Xoá các sản phẩm trong giỏ nếu đã bị sửa thông tin sau khi thêm vào giỏ
     */
    private function checkProductUpdateAfterAddCard()
    {
        $ids = [];

        foreach (\Cart::getContent() as $product) {
            $productNew = DB::table('products')
                ->where('id', $product->attributes->product_id)
                ->first();

            if ($productNew && $productNew->updated_at != $product->attributes->updated_at) {
                $ids[] = $product->id;
            }
        }

        foreach ($ids as $id) {
            \Cart::remove($id);
        }

        return count($ids) > 0;
    }
}
