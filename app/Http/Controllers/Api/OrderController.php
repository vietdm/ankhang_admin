<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Configs;
use App\Models\Orders;
use App\Models\Products;
use App\Models\UserMoney;
use App\Models\Users;
use App\Utils\MoneyUtil;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDOException;

class OrderController extends Controller
{
    public function order(OrderRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $userId = $request->user_id;
            $requestOrder = json_decode($request->order, 1);
            $orderData = $requestOrder[0] ?? [
                'id' => 0,
                'quantity' => 0,
            ];

            $token = $request->bearerToken();
            $payload = JwtHelper::decode($token);

            if ($payload['id'] != $userId) {
                return Response::badRequest('Giả mạo người khác đặt đơn là hành vi phạm pháp!');
            }

            $user = Users::whereId($userId)->first();
            if (!$user) {
                return Response::badRequest('Người dùng không tồn tại!');
            }

            if (!isset($orderData['ignore_image'])) {
                $orderData['ignore_image'] = '0';
            }

            $productId = (int)$orderData['id'];
            $quantity = (int)$orderData['quantity'];

            $product = Products::whereId($productId)->first();
            if (!$product) {
                return Response::badRequest('Sản phẩm không tồn tại!');
            }

            $totalPrice = $product->price * $quantity;
            $isPointPayment = $request->has('point_type');

            if ($isPointPayment) {
                $orderData['ignore_image'] = '1';

                $pointType = $request->point_type;
                if (!in_array($pointType, ['cashback', 'reward'])) {
                    return Response::badRequest('Loại điểm thanh toán không tồn tại!');
                }

                $userMoney = UserMoney::whereUserId($userId)->first();
                $pointStatus = MoneyUtil::checkPointPayment($user, $totalPrice);
                if ($pointType == 'cashback') {
                    if ($pointStatus['cashback']['allow'] == '0') {
                        return Response::badRequest('Điểm CASHBACK không đủ hoặc không đủ điều kiện thanh toán!');
                    }
                    $userMoney->cashback_point -= $totalPrice;
                    $userMoney->save();
                }
                if ($pointType == 'reward') {
                    if ($pointStatus['reward']['allow'] == '0') {
                        return Response::badRequest('Điểm thưởng không đủ hoặc không đủ điều kiện thanh toán!');
                    }
                    $userMoney->reward_point -= $totalPrice;
                    $userMoney->save();
                }
            }

            $ignoreImage = $orderData['ignore_image'] == '1';

            if (!$ignoreImage) {
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
            $order->total_price = $totalPrice;
            $order->payment = $isPointPayment ? 'point' : 'bank';
            $order->image_url = $ignoreImage ? '' : "/bank_result/$newName.$ext";
            $order->save();

            if ($user) {
                $user->address = $order->address;
                $user->save();
            }

            $date = Carbon::now()->format('Y-m-d H:i:s');
            if ($isPointPayment) {
                $order->payed = 1;
                $order->status = 1;
                $order->save();

                $totalPrice = number_format($totalPrice);

                if (Configs::getBoolean('allow_put_telegram', false) === true) {
                    $mgs = <<<text
Có đơn hàng mới!
==============
Thời gian: $date
Họ tên: $order->name
Username: $user->username
Số điện thoại: $order->phone
Địa chỉ: $order->address
Ghi chú: $order->note
=============
Tên sản phẩm: $product->title
Số lượng: $order->quantity
Tổng giá: $totalPrice
============
Sản phẩm đổi bằng điểm
text;

                    Telegram::pushMgs($mgs, Telegram::CHAT_STORE);
                }
            } else {
                if (Configs::getBoolean('allow_put_telegram', false) === true) {
                    $mgs = <<<text
Có đơn hàng mới!
==============
Thời gian: $date
Họ tên: $order->name
Username: $user->username
Số điện thoại: $order->phone
Địa chỉ: $order->address
Ghi chú: $order->note
=============
Tên sản phẩm: $product->title
Số lượng: $order->quantity
Tổng giá: $totalPrice
text;

                    Telegram::pushMgs($mgs, Telegram::CHAT_CHECK_STORE);
                }
            }
            DB::commit();
            return Response::success([]);
        } catch (Exception | PDOException $e) {
            logger($e->getMessage());
            DB::rollBack();
            return Response::badRequest('Có lỗi khi đặt đơn hàng. Vui lòng liên hệ quản trị viên!');
        }
    }

    public function history(Request $request): JsonResponse
    {
        $orders = Orders::with(['product'])->whereUserId($request->user->id)->orderByDesc('id')->get();
        return Response::success([
            'history' => $orders
        ]);
    }
}
