<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use App\Models\Users;

class OrderController extends Controller
{
    public function order(OrderRequest $request) {
        $userOrder = gettype($request->order) == 'string' ? $request->order : json_encode($request->order);
        $order = new Orders();
        $order->order = gettype($request->order) == 'string' ? $request->order : json_encode($request->order);
        $order->user_id = $request->user_id;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->note = $request->note ?? '';
        $order->save();

        $user = Users::whereId($request->user_id)->first();
        if ($user) {
            $user->address = $order->address;
            $user->save();
        }

        $mgs = <<<text
Có đơn hàng mới!
==============
Họ tên: $request->name,
Số điện thoại: $request->phone,
Địa chỉ: $request->address,
Ghi chú: $request->note,
Đơn hàng: $userOrder
text;

        Telegram::pushMgs($mgs);
        return Response::success([]);
    }
}
