<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function order(OrderRequest $request): JsonResponse
    {
        $requestOrder = json_decode($request->order, 1);
        $orderData = $requestOrder[0] ?? [
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

        if (!$request->has('image')) {
            return Response::badRequest([
                'message' => 'Bạn chưa chọn ảnh kết quả thanh toán!'
            ]);
        }

        //upload image
        $image = $request->file('image');
        $ext = $image->extension();
        $newName = sha1(Carbon::now()->format('Ymd_His'));

        try {
            $image->move('bank_result', "$newName.$ext");
        } catch (Exception $e) {
            logger($e);
            return Response::badRequest([
                'message' => 'Không thể upload ảnh!'
            ]);
        }

        do {
            $code = Str::random(6);
        } while (Orders::whereCode($code)->first() != null);

        $order = new Orders();
        $order->code = $code;
        $order->user_id = $request->user_id;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->note = $request->note ?? '';
        $order->quantity = $quantity;
        $order->product_id = $productId;
        $order->total_price = $product->price * $quantity;
        $order->image_url = "/bank_result/$newName.$ext";
        $order->save();

        $user = Users::whereId($request->user_id)->first();
        if ($user) {
            $user->address = $order->address;
            $user->save();
        }

        return Response::success([]);
    }

    public function history(Request $request): JsonResponse
    {
        $orders = Orders::with(['product'])->whereUserId($request->user->id)->orderByDesc('id')->get();
        return Response::success([
            'history' => $orders
        ]);
    }
}
