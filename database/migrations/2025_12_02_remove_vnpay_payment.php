<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Xóa VNPAY payment method từ database (id = 3)
        DB::table('payments')->where('id', 3)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore VNPAY nếu rollback
        DB::table('payments')->insert([
            'id' => 3,
            'name' => 'VNPAY',
            'status' => 1,
            'img' => '1723387097.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
