<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Lấy user đang sửa từ route (admin.users_update, param {user})
        $user = $this->route('user'); // Model User được bind từ route

        return [
            'name' => 'required|string|min:1|max:30',

            // Email đúng cấu trúc + unique, bỏ qua user đang sửa, bỏ record đã xóa mềm
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                Rule::unique('users', 'email')
                    ->ignore($user?->id)
                    ->whereNull('deleted_at'),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:24',
                'regex:/[a-z]/',        // ít nhất 1 chữ thường
                'regex:/[A-Z]/',        // ít nhất 1 chữ hoa
                'regex:/[0-9]/',        // ít nhất 1 số
                'regex:/[@$!%*#?&]/',   // ít nhất 1 ký tự đặc biệt
            ],

            'phone_number' => 'required',
            'city'          => 'required|integer',
            'district'      => 'required|integer',
            'ward'          => 'required|integer',
            'apartment_number' => 'required|string|min:1|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'name.required' => __('message.required', ['attribute' => 'họ và tên']),
            'name.max'      => __('message.max', ['attribute' => 'Họ và tên']),
            'name.min'      => __('message.min', ['attribute' => 'Họ và tên']),

            'phone_number.required' => __('message.required', ['attribute' => 'số điện thoại']),
            'phone_number.min'      => __('message.required', ['attribute' => 'số điện thoại']),
            'phone_number.max'      => __('message.min_max_length', ['attribute' => 'số điện thoại']),

            'city.required'     => __('message.required', ['attribute' => 'tỉnh, thành phố']),
            'district.required' => __('message.required', ['attribute' => 'quận, huyện']),
            'ward.required'     => __('message.required', ['attribute' => 'phường, xã']),
            'apartment_number.required' => __('message.required', ['attribute' => 'số nhà']),

            'password.min'   => __('message.password_invalidator', ['attribute' => 'mật khẩu']),
            'password.max'   => __('message.password_invalidator', ['attribute' => 'mật khẩu']),
            'password.regex' => __('message.password_invalidator', ['attribute' => 'Mật khẩu']),

            'email.unique' => __('message.unique', ['attribute' => 'Email']),
            'email.email'  => __('message.email'),
        ];
    }
}
