<?php

namespace App\Utils;

use App\Models\HistoryBonus;
use App\Models\Users;
use Carbon\Carbon;

class OrderUtil
{
    public static function sendBonus(
        $user,
        $userOrder,
        $pricePayed,
        $level,
        &$totalBonusPercent,
        &$levelCalc,
        &$percentLevel
    ): void {
        if ($user->total_buy >= 3000000) {
            $percentDirect = 0;
            if ($level == 'F1') {
                $percentDirect = 0.1;
            } else if ($level == 'F2' || $level == 'F3') {
                $percentDirect = 0.04;
            }

            $bonus = $pricePayed * $percentDirect;
            $totalBonusPercent -= $percentDirect;

            $user->user_money->money_bonus += $bonus;
            $user->user_money->save();

            HistoryBonus::insert([
                'user_id' => $user->id,
                'from_user_id' => $userOrder->id,
                'money_bonus' => $bonus,
                'type' => HistoryBonus::HH_TRUC_TIEP,
                'date_bonus' => Carbon::now()->format('Y-m-d'),
                'content' => 'Thưởng hoa hồng trực tiếp',
            ]);
        }

        self::sendBonusLevel(
            $user,
            $userOrder,
            $pricePayed,
            $totalBonusPercent,
            $levelCalc,
            $percentLevel
        );
    }

    public static function sendBonusLevel(
        $user,
        $userOrder,
        $pricePayed,
        &$totalBonusPercent,
        &$levelCalc,
        &$percentLevel
    ): void {
        $dateNow = Carbon::now()->format('Y-m-d');
        if ($user->level == Users::LEVEL_CHUYEN_VIEN) {
            if (in_array($levelCalc, [
                Users::LEVEL_CHUYEN_VIEN,
                Users::LEVEL_TRUONG_PHONG,
                Users::LEVEL_PHO_GIAM_DOC,
                Users::LEVEL_GIAM_DOC,
                Users::LEVEL_GIAM_DOC_CAP_CAO,
            ])) return;
            $levelCalc = Users::LEVEL_CHUYEN_VIEN;
            $bonus = $pricePayed * 0.04;
            $user->user_money->money_bonus += $bonus;
            $user->user_money->save();
            $totalBonusPercent -= 0.04;
            $percentLevel = 0.04;
            HistoryBonus::insert([
                'user_id' => $user->id,
                'from_user_id' => $userOrder->id,
                'money_bonus' => $bonus,
                'date_bonus' => $dateNow,
                'type' => HistoryBonus::HH_CAP_BAC,
                'content' => 'Thưởng hoa hồng cấp bậc Chuyên Viên'
            ]);
        }
        if ($user->level == Users::LEVEL_TRUONG_PHONG) {
            if (in_array($levelCalc, [
                Users::LEVEL_TRUONG_PHONG,
                Users::LEVEL_PHO_GIAM_DOC,
                Users::LEVEL_GIAM_DOC,
                Users::LEVEL_GIAM_DOC_CAP_CAO,
            ])) return;
            $levelCalc = Users::LEVEL_TRUONG_PHONG;
            $percent = 0.08 - $percentLevel;
            $percentLevel = 0.08;
            $bonus = $pricePayed * $percent;
            $user->user_money->money_bonus += $bonus;
            $user->user_money->save();
            $totalBonusPercent -= 0.08;
            HistoryBonus::insert([
                'user_id' => $user->id,
                'from_user_id' => $userOrder->id,
                'money_bonus' => $bonus,
                'date_bonus' => $dateNow,
                'type' => HistoryBonus::HH_CAP_BAC,
                'content' => 'Thưởng hoa hồng cấp bậc Trưởng Phòng'
            ]);
        }
        if ($user->level == Users::LEVEL_PHO_GIAM_DOC) {
            if (in_array($levelCalc, [
                Users::LEVEL_PHO_GIAM_DOC,
                Users::LEVEL_GIAM_DOC,
                Users::LEVEL_GIAM_DOC_CAP_CAO,
            ])) return;

            UserUtil::getTotalSale($user->username, $totalSale);
            $totalSale += $user->total_buy;

            if ($totalSale < 1000000000) return;

            $levelCalc = Users::LEVEL_PHO_GIAM_DOC;
            $percent = 0.12 - $percentLevel;
            $percentLevel = 0.12;
            $bonus = $pricePayed * $percent;
            $user->user_money->money_bonus += $bonus;
            $user->user_money->save();
            $totalBonusPercent -= 0.12;
            HistoryBonus::insert([
                'user_id' => $user->id,
                'from_user_id' => $userOrder->id,
                'money_bonus' => $bonus,
                'date_bonus' => $dateNow,
                'type' => HistoryBonus::HH_CAP_BAC,
                'content' => 'Thưởng hoa hồng cấp bậc Phó Giám Đốc'
            ]);
        }
        if ($user->level == Users::LEVEL_GIAM_DOC) {
            if (in_array($levelCalc, [
                Users::LEVEL_GIAM_DOC,
                Users::LEVEL_GIAM_DOC_CAP_CAO,
            ])) return;

            UserUtil::getTotalSale($user->username, $totalSale);
            $totalSale += $user->total_buy;

            if ($totalSale < 3000000000) return;

            $levelCalc = Users::LEVEL_GIAM_DOC;
            $percent = 0.14 - $percentLevel;
            $percentLevel = 0.14;
            $bonus = $pricePayed * $percent;
            $user->user_money->money_bonus += $bonus;
            $user->user_money->save();
            $totalBonusPercent -= 0.14;
            HistoryBonus::insert([
                'user_id' => $user->id,
                'from_user_id' => $userOrder->id,
                'money_bonus' => $bonus,
                'date_bonus' => $dateNow,
                'type' => HistoryBonus::HH_CAP_BAC,
                'content' => 'Thưởng hoa hồng cấp bậc Giám Đốc'
            ]);
        }
        if ($user->level == Users::LEVEL_GIAM_DOC_CAP_CAO) {
            if ($levelCalc == Users::LEVEL_GIAM_DOC_CAP_CAO) return;

            UserUtil::getTotalSale($user->username, $totalSale);
            $totalSale += $user->total_buy;

            if ($totalSale < 3000000000) return;

            $levelCalc = Users::LEVEL_GIAM_DOC_CAP_CAO;
            $percent = 0.16 - $percentLevel;
            $percentLevel = 0.16;
            $bonus = $pricePayed * $percent;
            $user->user_money->money_bonus += $bonus;
            $user->user_money->save();
            $totalBonusPercent -= 0.16;
            HistoryBonus::insert([
                'user_id' => $user->id,
                'from_user_id' => $userOrder->id,
                'money_bonus' => $bonus,
                'date_bonus' => $dateNow,
                'type' => HistoryBonus::HH_CAP_BAC,
                'content' => 'Thưởng hoa hồng cấp bậc Giám Đốc Cấp Cao'
            ]);
        }
    }

    public static function loopSendBonusLevel(
        $userPresentUsername,
        $userOrder,
        $pricePayed,
        &$totalBonusPercent,
        &$levelCalc,
        &$percentLevel
    ): void {
        $user = Users::whereUsername($userPresentUsername)->first();
        if (!$user) return;
        self::sendBonusLevel(
            $user,
            $userOrder,
            $pricePayed,
            $totalBonusPercent,
            $levelCalc,
            $percentLevel
        );
        self::loopSendBonusLevel(
            $user->present_username,
            $userOrder,
            $pricePayed,
            $totalBonusPercent,
            $levelCalc,
            $percentLevel
        );
    }
}
