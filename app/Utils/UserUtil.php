<?php

namespace App\Utils;

use App\Models\LevelUpCondition;
use App\Models\Orders;
use App\Models\Users;

class UserUtil
{
    public static function getTotalChildAndSaleMergeCurrentUser(Users $user, &$totalChild = 0, &$totalSale = 0, &$totalOrder = 0): void
    {
        self::getTotalChildAndSale($user->username, $totalChild, $totalSale, $totalOrder);
        $totalChild += 1;
        $totalSale += $user->total_buy;
        $totalOrder += Orders::whereUserId($user->id)->get()->count();
    }

    //this function not count current user
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

    //this function not count current user
    public static function getTotalSale($username, &$totalSale = 0): void
    {
        $allUser = Users::select(['id', 'username', 'total_buy'])->where('present_username', $username)->get();
        foreach ($allUser as $user) {
            $totalSale += $user->total_buy;
            self::getTotalSale($user->username, $totalSale);
        }
    }

    public static function checkIsSameSystem($userChildCheck, $userParentCheck)
    {
        $user = Users::whereUsername($userChildCheck->present_username)->first();
        if (!$user) return false;
        if ($user->username == $userParentCheck->username) {
            return true;
        }
        return self::checkIsSameSystem($user, $userParentCheck);
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

        $levelUpCondition = LevelUpCondition::whereUserId($userParent->id)
            ->whereFromUserId($user->id)
            ->whereLevelNext(Users::LEVEL_CHUYEN_VIEN)
            ->first();

        if (!$levelUpCondition) {
            LevelUpCondition::insert([
                'user_id' => $userParent->id,
                'from_user_id' => $user->id,
                'level_next' => Users::LEVEL_CHUYEN_VIEN
            ]);
        }

        $countPendingNext = LevelUpCondition::whereUserId($userParent->id)
            ->whereLevelNext(Users::LEVEL_CHUYEN_VIEN)
            ->get()
            ->count();

        if ($countPendingNext >= 3) {
            //tính tổng doanh số của cây
            self::getTotalSale($userParent->username, $totalPayTree);
            if ($totalPayTree > 30000000 && $userParent->package_joined != null) {
                $userParent->level = Users::LEVEL_CHUYEN_VIEN;
                $userParent->save();
                LevelUpCondition::whereUserId($userParent->id)->whereLevelNext(Users::LEVEL_CHUYEN_VIEN)->delete();
                self::upLevel($userParent, Users::LEVEL_CHUYEN_VIEN, Users::LEVEL_TRUONG_PHONG);
            }
        }

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

        $levelUpCondition = LevelUpCondition::whereUserId($userParent->id)
            ->whereFromUserId($user->id)
            ->whereLevelNext($nextLevel)
            ->first();

        if (!$levelUpCondition) {
            LevelUpCondition::insert([
                'user_id' => $userParent->id,
                'from_user_id' => $user->id,
                'level_next' => $nextLevel
            ]);
        }

        $countPendingNext = LevelUpCondition::whereUserId($userParent->id)
            ->whereLevelNext($nextLevel)
            ->get()
            ->count();

        if ($countPendingNext >= $numUserPass) {
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
                case Users::LEVEL_GIAM_DOC:
                    self::upLevelGiamDocCapCao($userParent);
                    break;
            }
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

    public static function getAllParent($presentUsername, &$parents = [])
    {
        $parent = Users::whereUsername($presentUsername)->first();
        if ($parent != null) {
            $parents[] = $parent;
            self::getAllParent($parent->present_username, $parents);
        }
    }
}
