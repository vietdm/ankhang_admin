<?php

namespace App\Utils;

use App\Models\UserMoney;
use App\Models\Users;

class MoneyUtil
{
    public static function checkPointPayment(Users $user, $priceCheck = 9999999999)
    {
        $userMoney = UserMoney::whereUserId($user->id)->first();
        $rewardPoint = $userMoney->reward_point;
        $cashbackPoint = $userMoney->cashback_point;
        $pricePay = (float)$priceCheck;

        $allowRewardPoint = false;
        $allowCashbackPoint = false;
        if ($cashbackPoint >= $pricePay) {
            $allowCashbackPoint = true;
        }

        if ($user->total_buy > 0) {
            $allF1 = Users::wherePresentUsername($user->username)->get();
            if ($allF1->count() > 5) {
                $totalF1BuyProduct = 0;
                foreach ($allF1 as $f1) {
                    if ($f1->total_buy > 0) {
                        $totalF1BuyProduct += 1;
                    }
                }
                if ($totalF1BuyProduct >= 5) {
                    $allowRewardPoint = true;
                }
            }
        }

        return [
            'cashback' => [
                'point' => $userMoney->cashback_point,
                'allow' => $allowCashbackPoint === false ? '0' : '1'
            ],
            'reward' => [
                'point' => $rewardPoint,
                'allow' => $allowRewardPoint === false ? '0' : '1'
            ],
            'product' => [
                'point' => $userMoney->product_point,
                'allow' => $userMoney->product_point <= 0 ? '0' : '1'
            ]
        ];
    }
}
