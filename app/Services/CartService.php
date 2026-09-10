<?php

namespace App\Services;

use App\Helpers\TextSystemConst;
use App\Repository\Eloquent\ProductSizeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CartService
{
     /**
     * @var ProductSizeRepository
     */
    private $productSizeRepository;

    /**
     * CartService constructor.
     *
     * @param ProductSizeRepository $categoryRepository
     */
    public function __construct(ProductSizeRepository $productSizeRepository)
    {
        $this->productSizeRepository = $productSizeRepository;
    }
    public function index()
    {
        try {
            return ['carts' => \Cart::getContent(), 'fee' => $this->getTransportFee()];
        } catch (\Exception){
            return [];
        }
    }

    public function store(Request $request)
    {
        // lấy thông tin sản phẩm
        $product = $this->productSizeRepository->getProductSize($request->id);
        // kiểm tra xem sản phẩm được thêm vào giỏ hàng có tồn tại không
        if (! $product) {
            return redirect()->route('user.home');
        }
        // lấy toàn bộ sản phẩm có trong giỏ hàng
        $carts = \Cart::getContent()->toArray();
        //Nếu giỏ hàng không rỗng và kiểm tra xem sản phẩm được thêm có tồn tại trong giỏ hàng chưa
        if (! empty($carts) && array_key_exists($request->id, $carts)) {
            // khi thêm vào nếu số lượng vượt quá trong kho thì sẽ báo lỗi
            if ($carts[$request->id]['quantity'] + $request->quantity > $product->products_size_quantity) {
                return back()->with('error', TextSystemConst::ADD_CART_ERROR_QUANTITY);
            }
        }

        //nếu người dùng thêm sản phẩm lớn hơn với số lượng kho thì báo lỗi
        if ($request->quantity > $product->products_size_quantity) {
            return back()->with('error', TextSystemConst::ADD_CART_ERROR_QUANTITY);
        }
        // thêm sản phẩm vào giỏ hàng hoặc cập số lượng nếu như sản phảm đó đã tồn trong giỏ hàng
        \Cart::add([
            'id' => $request->id,
            'name' => $product->product_name,
            'price' => $product->product_price_sell,
            'quantity' => $request->quantity,
            'attributes' => array(
                'image' => $product->product_img,
                'size' => $product->size_name,
                'color' => $product->color_name,
                'import_price' => $product->price_import,
                'updated_at' => $product->updated_at,
                'product_id' => $product->product_id
            )
        ]);

        // chuyển hướng người dùng đến trang giỏ hàng
        return redirect()->route('cart.index');
    }

    // cập nhật giỏ hàng
    public function update(Request $request)
    {
        $product = $this->productSizeRepository->getProductSize($request->id);
        // dd($product);
        // nếu người dùng cập nhật số lượng hơn trong kho thì báo lỗi
        if($request->quantity > $product->products_size_quantity) {
            return back()->with('error', TextSystemConst::ADD_CART_ERROR_QUANTITY);
        }

        // cập nhật lại số lượng trong kho
        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        // chuyển hướng dùng về lại trang giỏ hàng
        return redirect()->route('cart.index')->with('success', TextSystemConst::UPDATE_CART_SUCCESS);;
    }

    public function delete($id)
    {
        \Cart::remove($id);

        return redirect()->route('cart.index');
    }

    public function clearAllCart()
    {
        \Cart::clear();

        return redirect()->route('cart.index');
    }

    public function getTransportFee()
    {
        //get service id
        $fromDistrict = "2027";
        $shopId = "3577591";
        $toDistrict = Auth::user()->address->district;
        $response = Http::withHeaders([
            'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services', [
            "shop_id" => $shopId,
            "from_district" => $fromDistrict,
            "to_district" => $toDistrict,
        ]);
        $serviceId = $response['data'][0]['service_type_id'];

        //data get fee
        $dataGetFee = [
            "service_type_id" => $serviceId,
            "insurance_value" => 500000,
            "coupon" => null,
            "from_district_id" => $fromDistrict,
            "to_district_id" => Auth::user()->address->district,
            "to_ward_code" => Auth::user()->address->ward,
            "height" => 15,
            "length" => 15,
            "weight" => 1000,
            "width" => 15
        ];
        
        $response = Http::withHeaders([
            'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
        ])->get('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $dataGetFee);
        return $response['data']['total'];
    }
}
?>
