<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\JoinCashbackEvent;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function joinCashback(Request $request)
    {
        $loopTime = $request->user->total_buy >= 30000000 ? 10 : 1;
        $aryIdInsert = [];

        DB::beginTransaction();
        for ($i = 1; $i <= $loopTime; $i++) {
            $cashbackEvent = JoinCashbackEvent::insert([
                'user_id' => $request->user->id,
                'datetime_join' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            $aryIdInsert[] = $cashbackEvent->id;
        }
        DB::commit();

        foreach ($aryIdInsert as $id) {
            if ($id % 11 !== 0) {
                continue;
            }
            $idMakeCashback = $id / 11;
            $rowMakeCashback = JoinCashbackEvent::whereId($idMakeCashback)->first();
            $user = Users::with(['user_money'])->whereId($rowMakeCashback->user_id)->first();
            $user->user_money->cashback_point += 3000000;
            $user->user_money->save();
        }

        return Response::success('Tham gia thành công!');
    }
}
