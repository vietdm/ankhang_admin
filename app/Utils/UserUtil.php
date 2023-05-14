<?php

namespace App\Utils;

use App\Models\Users;

class UserUtil
{
    public static function getTotalChildAndSale($username, &$total = 0, &$totalSale = 0, &$dataTotalSale = []): void
    {
        $allUser = Users::select(['phone', 'total_buy'])->where('present_username', $username)->get();
        $total += $allUser->count();
        foreach ($allUser as $user) {
            $totalSale += $user->total_buy;
            $dataTotalSale[] = [
                'user_id' => $user->id,
                'username' => $user->username,
                'fullname' => $user->fullname,
                'sale' => $user->total_buy
            ];
            self::getTotalChildAndSale($user->username, $total, $totalSale, $dataTotalSale);
        }
    }
}
