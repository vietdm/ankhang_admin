<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(OrderRequest $request): JsonResponse
    {
        $orderData = $request->order[0] ?? [
            'id' => 0,
            'quantity' => 0
        ];

        $productId = (int)$orderData['id'];
        $quantity = (int)$orderData['quantity'];

        $product = Products::whereId($productId)->first();
        if (!$product) {
            return Response::badRequest([
                'message' => 'Sản phẩm không tồn tại!'
            ]);
        }

        $order = new Orders();
        $order->order = $request->order;
        $order->user_id = $request->user_id;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->note = $request->note ?? '';
        $order->quantity = $quantity;
        $order->product_id = $productId;
        $order->total_price = $product->price * $quantity;
        $order->save();

        $user = Users::whereId($request->user_id)->first();
        if ($user) {
            $user->address = $order->address;
            $user->save();
        }

//         $order = array_reduce($request->order, function ($result, $ord) {
//             $result[$ord['id']] = (int)$ord['quantity'];
//             return $result;
//         }, []);
//
//         $products = Products::whereIn('id', array_keys($order))->get()->toArray();
//         $textOrder = '';
//         foreach ($products as $index => $product) {
//             $textOrder .= "=========";
//             $textOrder .= "\r\nĐơn hàng " . ($index + 1);
//             $textOrder .= "\r\nTên sản phẩm: " . $product['title'];
//             $textOrder .= "\r\nSố lượng: " . $order[$product['id']];
//             $textOrder .= "\r\nTổng giá: " . number_format($product['price'] * $order[$product['id']]);
//             $textOrder .= "\r\n";
//         }
//
//         $mgs = <<<text
// Có đơn hàng mới!
// ==============
// Họ tên: $request->name,
// Số điện thoại: $request->phone,
// Địa chỉ: $request->address,
// Ghi chú: $request->note,
// $textOrder
// text;
//
//         Telegram::pushMgs($mgs);
        return Response::success([]);
    }

    public function history(Request $request): JsonResponse
    {
        $products = [];
        $orders = Orders::whereUserId($request->user->id)->orderBy('id', 'DESC')->get()->toArray();
        foreach ($orders as &$order) {
            foreach ($order['order'] as &$or) {
                if (!isset($products[$or['id']])) {
                    $product = Products::whereId($or['id'])->first();
                    $products[$or['id']] = $product;
                }
                $or['product'] = $products[$or['id']];
            }
        }
        return Response::success([
            'history' => $orders
        ]);
    }
}
