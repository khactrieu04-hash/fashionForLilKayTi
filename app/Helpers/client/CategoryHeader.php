<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

if (!function_exists('category_header')) {
    function category_header()
    {
        return Cache::remember('client.category_header', now()->addSeconds(60), function () {
            try {
                return DB::table('categories')->where('parent_id', 0)->get();
            } catch (\Throwable $e) {
                return collect();
            }
        });
    }
}

if (!function_exists('setting_website')) {
    function setting_website()
    {
        return Cache::remember('client.website_setting', now()->addSeconds(60), function () {
            try {
                $setting = DB::table('setting')->first();
                if ($setting) {
                    return $setting;
                }
            } catch (\Throwable $e) {
                // Database chưa kết nối hoặc chưa migrate
            }

            return (object)[
                'id' => 1,
                'logo' => '',
                'name' => 'MinMupShop',
                'email' => 'admin@gmail.com',
                'address' => 'Hà Nội, Việt Nam',
                'phone_number' => '0123456789',
                'maintenance' => 2, // 2: bình thường, 1: bảo trì
                'notification' => 'Chào mừng bạn đến với MinMupShop',
                'introduction' => 'Cửa hàng thời trang MinMupShop',
            ];
        });
    }
}
