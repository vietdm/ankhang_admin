<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\HistoryBonus;
use App\Models\Withdraw;
use Illuminate\Http\Request;

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
}
