<?php

namespace App\Utils;

use App\Models\Orders;
use App\Models\Users;

class UserUtil
{
    public static function getTotalChildAndSale($username, &$total = 0, &$totalSale = 0, &$totalOrder = 0): void
    {
        $allUser = Users::select(['id', 'username', 'total_buy'])->where('present_username', $username)->get();
        $total += $allUser->count();
        foreach ($allUser as $user) {
            $totalSale += $user->total_buy;
            $totalOrder += Orders::whereUserId($user->id)->get()->count();
            self::getTotalChildAndSale($user->username, $total, $totalSale, $totalOrder);
        }
    }
}
