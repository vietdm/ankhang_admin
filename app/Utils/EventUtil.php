<?php

namespace App\Utils;

use App\Models\JoinCashbackEvent;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class EventUtil
{
    public static function joinEventCaaashback(Users $user)
    {
        $loopTime = $user->total_buy >= 30000000 ? 10 : 1;
        $aryIdInsert = [];

        DB::beginTransaction();
        for ($i = 1; $i <= $loopTime; $i++) {
            $cashbackEvent = JoinCashbackEvent::insert([
                'user_id' => $user->id,
                'datetime_join' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            $aryIdInsert[] = $cashbackEvent->id;
        }
        DB::commit();

        DB::beginTransaction();
        try {
            foreach ($aryIdInsert as $id) {
                if ($id % 11 !== 0) {
                    continue;
                }
                $idMakeCashback = $id / 11;
                $rowMakeCashback = JoinCashbackEvent::whereId($idMakeCashback)->first();
                $userCashback = Users::with(['user_money'])->whereId($rowMakeCashback->user_id)->first();
                $userCashback->user_money->cashback_point += 3000000;
                $rowMakeCashback->cashbacked = 1;

                $userCashback->user_money->save();
                $rowMakeCashback->save();
            }
            DB::commit();
            return true;
        } catch (Exception | PDOException $e) {
            logger($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
