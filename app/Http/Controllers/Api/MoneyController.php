<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Configs;
use App\Models\HistoryBonus;
use App\Models\Otps;
use App\Models\TransferAkgHistory;
use App\Models\UserMoney;
use App\Models\Users;
use App\Models\Withdraw;
use App\Utils\MoneyUtil;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class MoneyController extends Controller
{
    public function getMoneyHistory(Request $request)
    {
        $withDrawHistory = Withdraw::whereUserId($request->user->id)->get();
        $bonusHistory = HistoryBonus::with(['user_from'])->whereUserId($request->user->id)->get();

        $withDrawHistory = $withDrawHistory->map(function ($withDraw) {
            $withDraw->history_type = 'withdraw';
            return $withDraw;
        });

        $bonusHistory = $bonusHistory->map(function ($bonus) {
            $bonus->history_type = 'bonus';
            return $bonus;
        });

        $withDrawHistory = collect($withDrawHistory);
        $bonusHistory = collect($bonusHistory);

        $histories = $bonusHistory->merge($withDrawHistory)->sortByDesc('created_at')->values();

        return Response::success([
            'histories' => $histories
        ]);
    }

    public function transferAkg(Request $request)
    {
        $username = $request->username;
        $pointSend = (float)$request->point_send;

        if (empty($username)) {
            return Response::badRequest('Mã người dùng không thấy!');
        }

        $userReceive = Users::with(['user_money'])->whereUsername($username)->first();
        if (!$userReceive) {
            return Response::badRequest('Người nhận không tồn tại!');
        }

        if ($userReceive->total_buy < 3000000) {
            return Response::badRequest('Người nhận không đủ điều kiện nhận điểm AKG!');
        }

        $userMoney = UserMoney::whereUserId($request->user->id)->first();

        if ($userMoney->akg_point < $pointSend) {
            return Response::badRequest('Số điểm bạn chuyển nhiều hơn số điểm bạn có!');
        }

        $otpCode = $request->otp_code;

        if (empty($otpCode)) {
            return Response::badRequest([
                'message' => 'Không tìm thấy Mã OTP!'
            ]);
        }

        $otpRecord = Otps::whereUserId($request->user->id)->whereType(Otps::TRANSFER_AKG)->first();
        if (!$otpRecord) {
            return Response::badRequest([
                'message' => 'Mã OTP không tồn tại hoặc đã hết hạn!'
            ]);
        }

        if (Carbon::now()->timestamp > $otpRecord->ttl) {
            $otpRecord->delete();
            return Response::badRequest([
                'message' => 'Mã OTP không tồn tại hoặc đã hết hạn!'
            ]);
        }

        $otpRecord->delete();

        DB::beginTransaction();
        try {
            $userReceive->user_money->akg_point += $pointSend;
            $userReceive->user_money->save();

            $userMoney->akg_point -= $pointSend;
            $userMoney->save();

            TransferAkgHistory::insert([
                'user_id' => $request->user->id,
                'to_user_id' => $userReceive->id,
                'point_send' => $pointSend,
                'date_send' => Carbon::now()->format('Y-m-d')
            ]);

            DB::commit();
            return Response::success([
                'message' => 'Chuyển điểm thành công thành công!'
            ]);
        } catch (Exception | PDOException $e) {
            DB::rollBack();
            return Response::badRequest([
                'message' => 'Có lỗi khi chuyển điểm. Vui lòng liên hệ quản trị viên!'
            ]);
        }
    }

    public function transferAkgHistory(Request $request)
    {
        $histories = TransferAkgHistory::with(['to_user'])->whereUserId($request->user->id)->orderByDesc('date_send')->get();
        return Response::success([
            'histories' => $histories
        ]);
    }

    public function getValueOfAkg()
    {
        $valueOfAkg = Configs::getDouble('value_of_akg');
        return Response::success([
            'value' => $valueOfAkg
        ]);
    }

    public function checkPointPayment(Request $request)
    {
        return Response::success(
            MoneyUtil::checkPointPayment($request->user, $request->price)
        );
    }
}
