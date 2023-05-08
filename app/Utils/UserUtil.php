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

    public static function getTotalChild($userPhone, &$total = 0)
    {
        $allUser = Users::select(['phone'])->where('present_phone', $userPhone)->get();
        $total += $allUser->count();
        foreach ($allUser as $user) {
            self::getTotalChild($user->phone, $total);
        }
    }
}
