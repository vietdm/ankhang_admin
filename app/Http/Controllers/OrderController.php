<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Models\Orders;
use App\Models\Users;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class OrderController extends Controller
{
    public function accepts()
    {
        $orders = Orders::whereStatus(0)->get();
        foreach ($orders as $order) {
            $order->accept();
        }
        echo 'Done!';
        die();
    }


    public function accept($id): JsonResponse
    {
        $order = Orders::whereId($id)->first();
        if (!$order) {
            return Response::badRequest("Order không tồn tại!");
        }
        if ($order->payed === Orders::NOT_PAY) {
            return Response::badRequest("Đơn hàng cần được xác nhận thanh toán trước!");
        }
        DB::beginTransaction();
        try {
            $order->accept();
            DB::commit();

            $product = Products::whereIn('id', $order->product_id)->first();
            $totalPrice = number_format($order->total_price);

            $mgs = <<<text
Có đơn hàng mới!
==============
Họ tên: $order->name
Số điện thoại: $order->phone
Địa chỉ: $order->address
Ghi chú: $order->note
=============
Tên sản phẩm: $product->title
Số lượng: $order->quantity
Tổng giá: $totalPrice
text;

            Telegram::pushMgs($mgs);

            return Response::success('Thành công!');
        } catch (Exception|PDOException $e) {
            logger($e);
            DB::rollBack();
            return Response::badRequest('Không thể xác nhận đơn hàng! Vui lòng thử lại!');
        }
    }

    public function cancel($id): JsonResponse
    {
        $order = Orders::whereId($id)->first();
        if (!$order) {
            return Response::badRequest("Order không tồn tại!");
        }
        DB::beginTransaction();
        try {
            $order->status = Orders::STATUS_CANCEL;
            $order->save();
            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception|PDOException $e) {
            logger($e);
            DB::rollBack();
            return Response::badRequest('Không thể hủy đơn hàng! Vui lòng thử lại!');
        }
    }

    public function payed($id): JsonResponse
    {
        $order = Orders::whereId($id)->first();
        if (!$order) {
            return Response::badRequest("Order không tồn tại!");
        }
        DB::beginTransaction();
        try {
            $order->payed = Orders::PAYED;
            $order->save();
            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception|PDOException $e) {
            logger($e);
            DB::rollBack();
            return Response::badRequest('Không thể xác nhận thanh toán! Vui lòng thử lại!');
        }
    }
}
