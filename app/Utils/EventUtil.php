<?php

namespace App\Utils;

use App\Models\Configs;
use App\Models\JoinCashbackEvent;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class EventUtil
{
    public static function joinEventCashback(Users $user)
    {
        DB::beginTransaction();
        try {
            $lastOrderJoin = Configs::getInt('last_order_cashback_event', 0);
            $cashbackEvent = JoinCashbackEvent::insert([
                'user_id' => $user->id,
                'order' => $lastOrderJoin + 1,
                'datetime_join' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            if ($cashbackEvent->order % 11 === 0) {
                $orderMakeCashback = $cashbackEvent->order / 11;
                $rowMakeCashback = JoinCashbackEvent::whereOrder($orderMakeCashback)->first();
                $userCashback = Users::with(['user_money'])->whereId($rowMakeCashback->user_id)->first();
                $userCashback->user_money->cashback_point += 3000000;
                $rowMakeCashback->cashbacked = 1;

                $userCashback->user_money->save();
                $rowMakeCashback->save();
            }
            Configs::setInt('last_order_cashback_event', $lastOrderJoin + 1);
            DB::commit();
            return true;
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
            return false;
        }
    }
}
