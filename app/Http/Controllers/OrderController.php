<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Models\Configs;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDOException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Requests\OrderRequest;

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
        $order = Orders::with(['user', 'combo.product', 'product'])->whereId($id)->first();
        if (!$order) {
            return Response::badRequest("Order không tồn tại!");
        }
        if ($order->payed === Orders::NOT_PAY) {
            return Response::badRequest("Đơn hàng cần được xác nhận thanh toán trước!");
        }
        DB::beginTransaction();
        try {
            $order->accept();

            if (Configs::getBoolean('allow_put_telegram', false) === true) {
                $requestOrder = [];
                if ($order->product_id != 0) {
                    $requestOrder[] = [
                        'product' => $order->product,
                        'quantity' => $order->quantity
                    ];
                } else {
                    foreach ($order->combo as $combo) {
                        $requestOrder[] = [
                            'product' => $combo->product,
                            'quantity' => $combo->quantity
                        ];
                    }
                }

                $messageTelegram = view('telegrams.order', [
                    'order' => $order,
                    'user' => $order->user,
                    'requestOrder' => $requestOrder,
                    'isPoint' => false,
                    'deliveryAddressType' => $order->delivery_address_type
                ])->render();

                Telegram::pushMgs($messageTelegram, Telegram::CHAT_STORE);
            }

            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
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
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
            return Response::badRequest('Không thể hủy đơn hàng! Vui lòng thử lại!');
        }
    }

    public function setDeliving($id): JsonResponse
    {
        $order = Orders::whereId($id)->first();
        if (!$order) {
            return Response::badRequest("Order không tồn tại!");
        }
        DB::beginTransaction();
        try {
            $order->status = Orders::STATUS_DELIVE;
            $order->save();
            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
            return Response::badRequest('Không thể xác nhận đang vận chuyên đơn hàng! Vui lòng thử lại!');
        }
    }

    public function setSuccess($id): JsonResponse
    {
        $order = Orders::whereId($id)->first();
        if (!$order) {
            return Response::badRequest("Order không tồn tại!");
        }
        DB::beginTransaction();
        try {
            $order->status = Orders::STATUS_DONE;
            $order->save();
            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
            return Response::badRequest('Không thể xác nhận hoàn thành đơn hàng! Vui lòng thử lại!');
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
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
            return Response::badRequest('Không thể xác nhận thanh toán! Vui lòng thử lại!');
        }
    }

    public function export(Request $request): BinaryFileResponse
    {
        $type = $request->type ?? 'all';
        $date = Carbon::now()->format('Ymd_His');
        return Excel::download(new OrdersExport($type), "order_export_$date.xlsx");
    }

    public function listOrderConfirmed()
    {
        $orders = Orders::with(['user', 'product', 'combo.product'])->whereStatus(1)->orderByDesc('id')->get();
        $html = view('order.table.transfer', compact('orders'))->render();
        return Response::success([
            'html' => $html
        ]);
    }

    public function listOrderDeliving()
    {
        $orders = Orders::with(['user', 'product', 'combo.product'])->whereStatus(2)->orderByDesc('id')->get();
        $html = view('order.table.transfer', compact('orders'))->render();
        return Response::success([
            'html' => $html
        ]);
    }

    public function createOrder()
    {
        $products = Products::select(['id', 'title'])->get();
        $users = Users::select(['id', 'username', 'fullname', 'phone', 'address'])->get();
        return view('order.create', compact('products', 'users'));
    }

    public function createOrderPost(OrderRequest $request)
    {
        $orderControllerApi = new ApiOrderController();
        return $orderControllerApi->order($request);
    }
}
