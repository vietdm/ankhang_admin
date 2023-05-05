<?php

namespace App\Utils;

use App\Models\Users;

class User {
    public static function getTreeUser($userRoot) {
        $allUser = Users::select(['id', 'email', 'phone', 'fullname', 'present_phone'])->where('present_phone', $userRoot['phone'])->get();
        foreach ($allUser as $user) {
            $userRoot['children'][] = self::getTreeUser($user->toArray());
        }
        return $userRoot;
    }
}
