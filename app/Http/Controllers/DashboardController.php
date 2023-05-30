<?php

namespace App\Http\Controllers;

use App\Models\HistoryBonus;
use App\Models\Orders;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class DashboardController extends Controller
{
    public function home() {
        $dashboard = new stdClass;

        $user = Users::select(DB::raw('COUNT(id) as total'))->first();
        $dashboard->total_user = $user->total;

        $order = Orders::select(DB::raw('COUNT(id) as total'))->first();
        $dashboard->total_order = $order->total;

        $bonus = HistoryBonus::select(DB::raw('SUM(money_bonus) as total'))->first();
        $dashboard->total_bonus = $bonus->total;

        return view('dashboard.index', compact('dashboard'));
    }

    public function bonus(Request $request) {
        return view('dashboard.bonus.index');
    }
}
