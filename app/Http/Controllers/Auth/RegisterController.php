<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\TextSystemConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Repository\Eloquent\AddressRepository;
use App\Repository\Eloquent\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository   $userRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(UserRepository $userRepository, AddressRepository $addressRepository)
    {
        $this->userRepository   = $userRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Hiển thị màn hình đăng kí
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            // lấy tỉnh thành phố
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/province');
            $citys = json_decode($response->body(), true);

            // lấy quận huyện
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => old('city') ?? $citys['data'][0]['ProvinceID'],
            ]);
            $districts = json_decode($response->body(), true);

            //lấy phường xã
            $response = Http::withHeaders([
                'token' => '24d5b95c-7cde-11ed-be76-3233f989b8f3'
            ])->get('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => old('district') ?? $districts['data'][0]['DistrictID'],
            ]);
            $wards = json_decode($response->body(), true);

            // kiểm tra xem người dùng đã nhập đầy thông tin hay chưa (client-side validate)
            $rules = [
                'email' => [
                    'required' => true,
                    'email'    => true,
                ],
                'password' => [
                    'required'             => true,
                    'minlength'            => 8,
                    'maxlength'            => 24,
                    'checklower'           => true,
                    'checkupper'           => true,
                    'checkdigit'           => true,
                    'checkspecialcharacter' => true,
                ],
                'password_confirm' => [
                    'required'             => true,
                    'minlength'            => 8,
                    'maxlength'            => 24,
                    'checklower'           => true,
                    'checkupper'           => true,
                    'checkdigit'           => true,
                    'checkspecialcharacter' => true,
                    'equalTo'              => "#password"
                ],
                'name' => [
                    'required'  => true,
                    'minlength' => 1,
                    'maxlength' => 30,
                ],
                'apartment_number' => [
                    'required' => true,
                ],
                'city' => [
                    'required' => true,
                ],
                'district' => [
                    'required' => true,
                ],
                'ward' => [
                    'required' => true,
                ],
                'phone_number' => [
                    'required'   => true,
                    'validPhone' => true
                ],
            ];

            // Hiển thị thông báo lỗi khi người dùng chưa nhập đủ thông tin
            $messages = [
                'name' => [
                    'required'  => __('message.required', ['attribute' => 'Họ và tên']),
                    'minlength' => __('message.min', ['min' => 1, 'attribute' => 'Họ và tên']),
                    'maxlength' => __('message.max', ['max' => 30, 'attribute' => 'Họ và tên']),
                ],
                'email' => [
                    'required' => __('message.required', ['attribute' => 'email']),
                    'email'    => __('message.email'),
                ],
                'password' => [
                    'required'             => __('message.required', ['attribute' => 'mật khẩu']),
                    'minlength'            => __('message.min', ['attribute' => 'Mật khẩu', 'min' => 8]),
                    'maxlength'            => __('message.max', ['attribute' => 'Mật khẩu', 'max' => 24]),
                    'checklower'           => __('message.password.at_least_one_lowercase_letter_is_required'),
                    'checkupper'           => __('message.password.at_least_one_uppercase_letter_is_required'),
                    'checkdigit'           => __('message.password.at_least_one_digit_is_required'),
                    'checkspecialcharacter' => __('message.password.at_least_special_characte_is_required'),
                ],
                'password_confirm' => [
                    'required'             => __('message.required', ['attribute' => 'mật khẩu']),
                    'minlength'            => __('message.min', ['attribute' => 'Mật khẩu', 'min' => 8]),
                    'maxlength'            => __('message.max', ['attribute' => 'Mật khẩu', 'max' => 24]),
                    'checklower'           => __('message.password.at_least_one_lowercase_letter_is_required'),
                    'checkupper'           => __('message.password.at_least_one_uppercase_letter_is_required'),
                    'checkdigit'           => __('message.password.at_least_one_digit_is_required'),
                    'checkspecialcharacter' => __('message.password.at_least_special_characte_is_required'),
                    'equalTo'              => 'Xác nhận mật khẩu không đúng',
                ],
                'phone_number' => [
                    'required'   => __('message.required', ['attribute' => 'số điện thoại']),
                    'validPhone' => "Số điện thoại không hợp lệ"
                ],
                'city' => [
                    'required' => __('message.required', ['attribute' => 'tỉnh, thành phố']),
                ],
                'district' => [
                    'required' => __('message.required', ['attribute' => 'quận, huyện']),
                ],
                'ward' => [
                    'required' => __('message.required', ['attribute' => 'phường, xã']),
                ],
                'apartment_number' => [
                    'required' => __('message.required', ['attribute' => 'số nhà']),
                ],
            ];

            return view('auth.register', [
                'citys'     => $citys['data'],
                'districts' => $districts['data'],
                'wards'     => $wards['data'],
                'rules'     => $rules,
                'messages'  => $messages,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route('user.login');
        }
    }

    /**
     * Xử lý đăng ký & lưu vào CSDL
     */
    public function store(UserRegisterRequest $request)
    {
        try {
            // lấy tất cả dữ liệu hợp lệ từ phía người dùng
            $data = $request->validated();

            // dữ liệu tài khoản
            $userData = [
                'name'             => $data['name'],
                'email'            => $data['email'],
                'password'         => $data['password'],
                'phone_number'     => $data['phone_number'],
                'role_id'          => Role::ROLE['user'],
                // ✅ coi như tài khoản đã được xác minh luôn
                'email_verified_at' => now(),
            ];

            // dữ liệu địa chỉ
            $addressData = [
                'city'             => $data['city'],
                'district'         => $data['district'],
                'ward'             => $data['ward'],
                'apartment_number' => $data['apartment_number'],
            ];

            DB::beginTransaction();

            // thêm tài khoản vào database
            $user = $this->userRepository->create($userData);

            // thêm địa chỉ vào database
            $addressData['user_id'] = $user->id;
            $this->addressRepository->updateOrCreate($addressData);

            DB::commit();

            // Đăng ký thành công -> chuyển về trang đăng nhập
            return redirect()
                ->route('user.login')
                ->with('success', __('message.register_success') ?? 'Đăng ký thành công, bạn có thể đăng nhập.');
        } catch (Exception $e) {
            // khi có lỗi xảy ra thì rollback dữ liệu
            Log::error($e);
            DB::rollBack();

            return back()->with('error', TextSystemConst::CREATE_FAILED);
        }
    }

    /**
     * Trước đây là view xác thực tài khoản.
     * Hiện đã tắt chức năng verify nên cho về trang chủ.
     */
    public function verifyEmail(User $user)
    {
        return redirect()->route('user.home');
    }

    /**
     * Trước đây dùng để gửi lại email xác thực.
     * Hiện không còn verify nên chỉ redirect về trang chủ.
     */
    public function resendEmail(Request $request)
    {
        return redirect()->route('user.home');
    }

    /**
     * Trước đây hiển thị màn hình xác thực thành công.
     * Giờ không dùng nữa, redirect về trang chủ.
     */
    public function success()
    {
        return redirect()->route('user.home');
    }
}
