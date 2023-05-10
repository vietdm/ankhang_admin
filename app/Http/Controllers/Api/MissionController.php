<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\UserMoney;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function update(Request $request)
    {
        $missionListId = $request->mission_list_id;
        $userId = $request->user->id;
        $missionWithTypeOfUser = Mission::whereType('video')
            ->whereUserId($userId)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->get();
        if ($missionWithTypeOfUser->count() >= 5) {
            return Response::badRequest(['message' => 'Đã hết lượt nhận thưởng']);
        }
        $mission = new Mission();
        $mission->user_id = $userId;
        $mission->date = Carbon::now()->format('Y-m-d');
        $mission->type = 'video';
        $mission->time_done = Carbon::now()->format('Y-m-d H:i:s');
        $mission->mission_list_id = $missionListId;
        $mission->save();

        $userMoney = UserMoney::getUserMoney($userId);
        $userMoney->reward_point += 1000;
        $userMoney->save();

        return Response::success(['message' => 'Đã nhận được 1000 điểm thưởng', 'limit' => 5 - $missionWithTypeOfUser->count() - 1]);
    }
}
