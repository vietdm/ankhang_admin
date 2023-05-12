<?php

use App\Models\Orders;
use App\Models\Products;
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
            $table->integer('quantity')->after('note')->default(0);
            $table->integer('product_id')->after('note');
        });

        $products = [];

        foreach (Orders::all() as $order) {
            $orderData = json_decode($order->order, 1);
            if (count($orderData) === 1) {
                $orderData = $orderData[0];
                $order->quantity = (int)$orderData['quantity'];
                $order->product_id = (int)$orderData['id'];
                $order->save();
                continue;
            }
            foreach ($orderData as $ord) {
                $quantity = (int)$ord['quantity'];
                $productId = (int)$ord['id'];
                $newOrd = Orders::insert([
                    'user_id' => $order->user_id,
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'note' => $order->note,
                    'payed' => $order->payed,
                    'status' => $order->status,
                    'order' => '[]',
                    'quantity' => $quantity,
                    'product_id' => $productId,
                ]);
                if (!isset($products[$productId])) {
                    $products[$productId] = Products::whereId($productId)->first();
                }
                $newOrd->total_price = $products[$productId]->price * $quantity;
                $newOrd->save();
            }
            $order->delete();
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
