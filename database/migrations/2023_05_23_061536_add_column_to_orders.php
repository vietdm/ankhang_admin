<?php

use App\Models\Orders;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('total_price_pay')->default(0)->after('total_price');
        });

        foreach (Orders::all() as $order) {
            $order->total_price_pay = $order->total_price;
            $order->save();
        }
    }
};
