<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Users;
use App\Models\Withdraw;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PDOException;

class WithdrawController extends Controller
{
    public function accept($id): JsonResponse
    {
        $withdraw = Withdraw::whereId($id)->first();
        if (!$withdraw) {
            return Response::badRequest("Yêu cầu rút tiền không tồn tại!");
        }
        if ($withdraw->status !== Withdraw::STATUS_CREATED) {
            return Response::badRequest("Yêu cầu rút tiền đã được duyệt trước đó!");
        }
        DB::beginTransaction();
        try {
            $withdraw->status = Withdraw::STATUS_ACCEPTED;
            $withdraw->save();
            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            logger($e);
            DB::rollBack();
            return Response::badRequest('Không thể xác nhận yêu cầu! Vui lòng thử lại!');
        }
    }

    public function cancel($id): JsonResponse
    {
        $withdraw = Withdraw::whereId($id)->first();
        if (!$withdraw) {
            return Response::badRequest("Yêu cầu rút tiền không tồn tại!");
        }
        DB::beginTransaction();
        try {
            $withdraw->status = Withdraw::STATUS_CANCEL;
            $withdraw->save();

            $user = Users::with(['user_money'])->whereId($withdraw->user_id)->first();
            $user->user_money->money_bonus += $withdraw->money;
            $user->user_money->save();

            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            logger($e);
            DB::rollBack();
            return Response::badRequest('Không thể hủy yêu cầu! Vui lòng thử lại!');
        }
    }
}
