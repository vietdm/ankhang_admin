<?php

namespace App\Utils;

use App\Models\HistoryBonus;
use App\Models\Orders;
use App\Models\Users;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;
use stdClass;

class DashboardUtil
{
    public static function getDashboard($startDate, $endDate, $format = false)
    {
        $dashboard = new stdClass;

        //set date query
        $dashboard->start_date = $startDate;
        $dashboard->end_date = $endDate;

        $startDate .= ' 00:00:00';
        $endDate .= ' 23:59:59';

        //tổng số người dùng
        $user = Users::select(DB::raw('COUNT(id) as total'));
        $user->where('created_at', '>=', $startDate);
        $user->where('created_at', '<=', $endDate);
        $user = $user->first();
        $dashboard->total_user = $user->total;

        //tổng số đơn hàng và tổng doanh số
        $order = Orders::select(DB::raw('COUNT(id) as total_order, SUM(total_price_pay) as total_sale'));
        $order->where('payed', '1');
        $order->where('created_at', '>=', $startDate);
        $order->where('created_at', '<=', $endDate);
        $order = $order->first();
        $dashboard->total_order = $order->total_order;
        $dashboard->total_sale = $order->total_sale;

        //tổng hoa hồng
        $bonus = HistoryBonus::select(DB::raw('SUM(money_bonus) as total'));
        $bonus->where('created_at', '>=', $startDate);
        $bonus->where('created_at', '<=', $endDate);
        $bonus = $bonus->first();
        $dashboard->total_bonus = $bonus->total;

        //tổng tiền rút
        $withdraw = Withdraw::select(DB::raw('SUM(money) as total'));
        $withdraw->where('status', '1');
        $withdraw->where('created_at', '>=', $startDate);
        $withdraw->where('created_at', '<=', $endDate);
        $withdraw = $withdraw->first();
        $dashboard->total_withdraw = $withdraw->total;

        if ($format) {
            $dashboard->total_bonus = number_format($dashboard->total_bonus);
            $dashboard->total_user = number_format($dashboard->total_user);
            $dashboard->total_order = number_format($dashboard->total_order);
            $dashboard->total_sale = number_format($dashboard->total_sale);
            $dashboard->total_withdraw = number_format($dashboard->total_withdraw);
        }

        return $dashboard;
    }
}
