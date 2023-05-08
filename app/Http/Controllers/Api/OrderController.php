<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        $order = new Orders();
        $order->order = $request->order;
        $order->user_id = $request->user_id;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->note = $request->note ?? '';

        $products = [];
        $totalPrice = 0;
        foreach ($request->order as $or) {
            $product = $products[$or['id']] ?? ($products[$or['id']] = Products::whereId($or['id'])->first());
            $totalPrice += $product->price * (int)$or['quantity'];
        }
        $order->total_price = $totalPrice;

        $order->save();

        $user = Users::whereId($request->user_id)->first();
        if ($user) {
            $user->address = $order->address;
            $user->save();
        }

        $order = array_reduce($request->order, function ($result, $ord) {
            $result[$ord['id']] = (int)$ord['quantity'];
            return $result;
        }, []);

        $products = Products::whereIn('id', array_values($order))->get()->toArray();
        $textOrder = '';
        foreach ($products as $index => $product) {
            $textOrder .= "=========";
            $textOrder .= "\r\nĐơn hàng " . ($index + 1);
            $textOrder .= "\r\nTên sản phẩm: " . $product['title'];
            $textOrder .= "\r\nSố lượng: " . $order[$product['id']];
            $textOrder .= "\r\nTổng giá: " . number_format($product['price'] * $order[$product['id']]);
            $textOrder .= "\r\n";
        }

        $mgs = <<<text
Có đơn hàng mới!
==============
Họ tên: $request->name,
Số điện thoại: $request->phone,
Địa chỉ: $request->address,
Ghi chú: $request->note,
$textOrder
text;

        Telegram::pushMgs($mgs);
        return Response::success([]);
    }

    public function history(Request $request)
    {
        $products = [];
        $orders = Orders::whereUserId($request->user->id)->orderBy('id', 'DESC')->get()->toArray();
        foreach ($orders as &$order) {
            foreach ($order['order'] as &$or) {
                if (isset($products[$or['id']])) {
                    $or['product'] = $products[$or['id']];
                } else {
                    $product = Products::whereId($or['id'])->first();
                    $products[$or['id']] = $product;
                    $or['product'] = $products[$or['id']];
                }
            }
        }
        return Response::success([
            'history' => $orders
        ]);
    }
}
