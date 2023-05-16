<?php

namespace App\Utils;

use App\Models\LevelUpCondition;
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

    public static function upLevelChuyenVien(Users $user)
    {
        $userParent = Users::whereUsername($user->present_username)->first();
        if (!$userParent) {
            return;
        }

        if ($userParent->level !== Users::LEVEL_NOMAL) {
            goto _continue;
        }

        $levelUpCondition = LevelUpCondition::whereUserId($userParent->id)->whereLevelNext(Users::LEVEL_CHUYEN_VIEN)->first();

        if (!$levelUpCondition) {
            LevelUpCondition::insert([
                'user_id' => $userParent->id,
                'level_next' => Users::LEVEL_CHUYEN_VIEN,
                'count_pass' => 1
            ]);
            goto _continue;
        }

        if ($levelUpCondition->count_pass == 1) {
            $levelUpCondition->count_pass = 2;
            $levelUpCondition->save();
            goto _continue;
        }

        //tính tổng doanh số trực tiếp (tổng doanh số F1)
        $totalPayF1 = 0;
        foreach (Users::select(['total_buy'])->wherePresentUsername($userParent->username)->get() as $uF1) {
            $totalPayF1 += $uF1->total_buy;
        }

        if ($totalPayF1 > 30000000 && $userParent->package_joined != null) {
            $userParent->level = Users::LEVEL_CHUYEN_VIEN;
            $userParent->save();
            $levelUpCondition->delete();
            self::upLevel($userParent, Users::LEVEL_CHUYEN_VIEN, Users::LEVEL_TRUONG_PHONG);
            goto _continue;
        }

        $levelUpCondition->count_pass = 3;
        $levelUpCondition->save();

        _continue:
        self::upLevelChuyenVien($userParent);
    }

    public static function upLevel(Users $user, $nowLevel, $nextLevel, $numUserPass = 3)
    {
        $userParent = Users::whereUsername($user->present_username)->first();
        if (!$userParent) {
            return;
        }

        if ($userParent->level !== $nowLevel) {
            goto _continue;
        }

        $levelUpCondition = LevelUpCondition::whereUserId($userParent->id)->whereLevelNext($nextLevel)->first();

        if (!$levelUpCondition) {
            LevelUpCondition::insert([
                'user_id' => $userParent->id,
                'level_next' => $nextLevel,
                'count_pass' => 1
            ]);
            goto _continue;
        }

        if ($numUserPass === 3) {
            if ($levelUpCondition->count_pass == 1) {
                $levelUpCondition->count_pass = 2;
                $levelUpCondition->save();
                goto _continue;
            }
        }

        $userParent->level = $nextLevel;
        $userParent->save();
        $levelUpCondition->delete();

        switch ($nextLevel) {
            case Users::LEVEL_TRUONG_PHONG:
                self::upLevelPhoGiamDoc($userParent);
                break;
            case Users::LEVEL_PHO_GIAM_DOC:
                self::upLevelGiamDoc($userParent);
                break;
        }

        _continue:
        self::upLevel($userParent, $nowLevel, $nextLevel);
    }

    public static function upLevelPhoGiamDoc(Users $user)
    {
        self::upLevel($user, Users::LEVEL_TRUONG_PHONG, Users::LEVEL_PHO_GIAM_DOC);
    }

    public static function upLevelGiamDoc(Users $user)
    {
        self::upLevel($user, Users::LEVEL_PHO_GIAM_DOC, Users::LEVEL_GIAM_DOC, 2);
    }

    public static function upLevelGiamDocCapCao(Users $user)
    {
        self::upLevel($user, Users::LEVEL_GIAM_DOC, Users::LEVEL_GIAM_DOC_CAP_CAO, 2);
    }
}
