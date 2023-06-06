<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\ComboOrder;
use App\Models\Configs;
use App\Models\Orders;
use App\Models\ProductPointHistory;
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
        if (!json_validator($request->order)) {
            return Response::badRequest('Lệnh order không hợp lệ!');
        }
        DB::beginTransaction();
        try {
            $userId = $request->user_id;
            $requestOrder = json_decode($request->order, 1);
            $isComboOrder = false;
            $totalPrice = 0;
            $ignoreImage = false;
            $newUuidComboOrder = Str::uuid();

            if (count($requestOrder) > 1) {
                $isComboOrder = true;
            }

            foreach ($requestOrder as &$_order) {
                $product = Products::whereId($_order['id'])->first();
                if ($product == null) {
                    return Response::badRequest('Có sản phẩm không tồn tại. Vui lòng kiểm tra lại hoặc liên hệ quản trị viên!');
                }
                $_order['product'] = $product;
                $totalPrice += (int)$_order['quantity'] * $product->price;
                if ($isComboOrder) {
                    ComboOrder::insert([
                        'hash' => $newUuidComboOrder,
                        'product_id' => $_order['id'],
                        'quantity' => $_order['quantity']
                    ]);
                }
            }

            $token = $request->bearerToken();
            $payload = JwtHelper::decode($token);

            if ($payload['id'] != $userId) {
                return Response::badRequest('Giả mạo người khác đặt đơn là hành vi phạm pháp!');
            }

            $user = Users::whereId($userId)->first();
            if (!$user) {
                return Response::badRequest('Người dùng không tồn tại!');
            }

            $productId = $isComboOrder ? 0 : (int)$requestOrder[0]['id'];
            $quantity = $isComboOrder ? 0 : (int)$requestOrder[0]['quantity'];

            $totalPricePay = (int)($request->total_price_pay ?? 0);
            $isPointPayment = $request->has('point_type');

            if ($isPointPayment) {
                $ignoreImage = true;
                $totalPricePay = $totalPrice;

                $pointType = $request->point_type;
                if (!in_array($pointType, ['cashback', 'reward', 'product'])) {
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
                if ($pointType == 'product') {
                    if ($pointStatus['product']['allow'] == '0') {
                        return Response::badRequest('Điểm mua hàng không đủ!');
                    }
                    $userMoney->product_point -= $totalPrice;
                    $userMoney->save();
                }
            } else {
                if ($totalPricePay < $totalPrice) {
                    return Response::badRequest('Số tiền thanh toán ít nhất phải bằng giá trị đơn hàng!');
                }
            }

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
                    ReportHandle($e);
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
            $order->combo_hash = $isComboOrder ? $newUuidComboOrder : null;
            $order->total_price = $totalPrice;
            $order->total_price_pay = $totalPricePay;
            $order->payment = $isPointPayment ? 'point' : 'bank';
            $order->image_url = $ignoreImage ? '' : "/bank_result/$newName.$ext";
            $order->save();

            if ($user) {
                $user->address = $order->address;
                $user->save();
            }

            $messageTelegram = view('telegrams.order', [
                'order' => $order,
                'user' => $user,
                'requestOrder' => $requestOrder,
                'isPoint' => $isPointPayment
            ])->render();

            if ($isPointPayment) {
                $order->payed = 1;
                $order->status = 1;
                $order->save();

                if (Configs::getBoolean('allow_put_telegram', false) === true) {
                    Telegram::pushMgs($messageTelegram, Telegram::CHAT_STORE);
                }

                if ($request->point_type == 'product') {
                    ProductPointHistory::insert([
                        'user_id' => $userId,
                        'order_id' => $order->id,
                        'type' => ProductPointHistory::TYPE_OUT,
                        'money' => $totalPrice
                    ]);
                }
            } else {
                if (Configs::getBoolean('allow_put_telegram', false) === true) {
                    Telegram::pushMgs($messageTelegram, Telegram::CHAT_CHECK_STORE, Telegram::BOT_TOKEN_REPORT_BUG);
                }
            }
            DB::commit();
            return Response::success([]);
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
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
