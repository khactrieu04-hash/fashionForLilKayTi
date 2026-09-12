<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::unprepared('
                CREATE TRIGGER group_by_quantity
                after INSERT ON order_details
                FOR EACH ROW 
                BEGIN
                    declare _record int;
                    declare _quantity int;
                    select count(*) into _record from order_details where product_size_id = new.product_size_id and order_id = new.order_id;
                    if (_record >= 2) then
                        select sum(quantity) into _quantity from order_details where product_size_id = new.product_size_id and order_id = new.order_id;
                        update order_details set quantity = _quantity where product_size_id = new.product_size_id and order_id = new.order_id;
                        SIGNAL sqlstate "45001" set message_text = "error";
                    end if;
                END
            ');
        } catch (\Throwable $e) {
            // TiDB Serverless không hỗ trợ triggers, bỏ qua an toàn
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::unprepared('
                drop trigger if exists group_by_quantity
            ');
        } catch (\Throwable $e) {
        }
    }
};
