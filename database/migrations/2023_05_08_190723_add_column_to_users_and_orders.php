<?php

use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('total_price')->default(0)->after('order');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->double('total_buy')->default(0)->after('package_joined');
        });

        $userChecked = [];
        $products = [];
        foreach (Orders::all() as $order) {
            //calc total price
            $totalPrice = 0;
            foreach ($order->order as $or) {
                $product = $products[$or['id']] ?? ($products[$or['id']] = Products::whereId($or['id'])->first());
                $totalPrice += $product->price * (int)$or['quantity'];
            }
            $order->total_price = $totalPrice;
            $order->save();

            //update total buy
            if ($order->status == 0 || $order->status == 4) continue;
            $user = $userChecked[$order->user_id] ?? ($userChecked[$order->user_id] = Users::whereId($order->user_id)->first());
            if (!$user) continue;
            $user->total_buy = $totalPrice;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_buy');
        });
    }
};
