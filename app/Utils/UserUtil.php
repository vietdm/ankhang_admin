<?php

namespace App\Utils;

use App\Models\Users;

class UserUtil
{
    public static function getTreeUser($userRoot)
    {
        $allUser = Users::select([
            'id',
            'email',
            'phone',
            'fullname',
            'present_phone'
        ])->where('present_phone', $userRoot['phone'])->get();
        foreach ($allUser as $user) {
            $userRoot['children'][] = self::getTreeUser($user->toArray());
        }
        return $userRoot;
    }

    public static function getTotalChildAndSale($userPhone, &$total = 0, &$totalSale = 0, &$dataTotalSale = []): void
    {
        $allUser = Users::select(['phone', 'total_buy'])->where('present_phone', $userPhone)->get();
        $total += $allUser->count();
        foreach ($allUser as $user) {
            $totalSale += $user->total_buy;
            $dataTotalSale[] = [
                'user_id' => $user->id,
                'username' => $user->username,
                'fullname' => $user->fullname,
                'sale' => $user->total_buy
            ];
            self::getTotalChildAndSale($user->phone, $total, $totalSale, $dataTotalSale);
        }
    }
}
