<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\HistoryBonus;
use App\Models\UserMoney;
use App\Models\Users;
use App\Utils\UserUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function presentName(Request $request)
    {
        $phone = $request->phone ?? '';
        $userWithPhone = Users::wherePhone($phone)->first();
        if (!$userWithPhone) {
            return Response::badRequest([
                'message' => 'Not found!'
            ]);
        }
        return Response::success([
            'message' => 'Success!',
            'name' => $userWithPhone->fullname
        ]);
    }

    public function getTree(Request $request)
    {
        $userId = $request->user->id;
        $user = Users::select(['id', 'phone', 'username'])->whereId($userId)->first()->toArray();
        $userTree = [...$user];

        //get level 1
        $userLevel1 = Users::select(['id', 'phone', 'username'])->wherePresentPhone($user['phone'])->get()->toArray();

        //get level2
        foreach ($userLevel1 as $key1 => $userlv1) {
            $userLevel2 = Users::select(['id', 'phone', 'username'])->wherePresentPhone($userlv1['phone'])->get()->toArray();

            //get level 3
            foreach ($userLevel2 as $key2 => $userlv2) {
                $userLevel3 = Users::select(['id', 'phone', 'username'])->wherePresentPhone($userlv2['phone'])->get()->toArray();
                $userLevel2[$key2]['children'] = [...$userLevel3];
            }

            $userLevel1[$key1]['children'] = [...$userLevel2];
        }

        $userTree['children'] = $userLevel1;

        return Response::success([
            'tree' => $userTree
        ]);
    }

    public function getChild($id)
    {
        $user = Users::select(['phone'])->whereId($id)->first();
        if (!$user) {
            return Response::success(['child' => []]);
        }
        $child = Users::select(['id', 'fullname'])->wherePresentPhone($user->phone)->get()->toArray();
        return Response::success(['child' => $child]);
    }

    public function getDashboardData(Request $request)
    {
        $userId = $request->user->id;
        $userMoney = UserMoney::whereUserId($userId)->first();
        $historyBonus = HistoryBonus::select([
            DB::raw('SUM(money_bonus) AS money_bonus_day')
        ])->whereUserId($userId)
            ->whereDateBonus(Carbon::now()->format('Y-m-d'))
            ->groupBy('user_id')
            ->first();

        UserUtil::getTotalChild($request->user->phone, $total);

        return Response::success([
            'money_bonus' => $userMoney->money_bonus,
            'money_bonus_day' => $historyBonus->money_bonus_day ?? 0,
            'total_child' => $total
        ]);
    }
}
