<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $casts = [
        'order' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function accept()
    {
        $this->status = 1;
        $this->save();

        $totalBonusPercent = 0.46;

        $order = $this->order[0];
        $product = Products::whereId($order['id'])->first();
        $userOrder = Users::whereId($this->user_id)->first();

        $pricePayed = $product->price * (int)$order['quantity'];

        //trả thưởng cho F1
        $userParentF1 = Users::with(['user_money'])->wherePhone($userOrder->present_phone)->first();
        if ($userParentF1) {
            $userParentF1->user_money->money_bonus += $pricePayed * 0.1;
            $userParentF1->user_money->save();
            $totalBonusPercent -= 0.1;
            HistoryBonus::insert([
                'user_id' => $userParentF1->id,
                'money_bonus' => $pricePayed * 0.1,
                'time_bonus' => Carbon::now()->format('Y-m-d H:i:s'),
                'date_bonus' => Carbon::now()->format('Y-m-d'),
            ]);

            //trả thưởng cho F2
            $userParentF2 = Users::with(['user_money'])->wherePhone($userParentF1->present_phone)->first();
            if ($userParentF2) {
                $userParentF2->user_money->money_bonus += $pricePayed * 0.05;
                $userParentF2->user_money->save();
                $totalBonusPercent -= 0.05;
                HistoryBonus::insert([
                    'user_id' => $userParentF2->id,
                    'money_bonus' => $pricePayed * 0.05,
                    'time_bonus' => Carbon::now()->format('Y-m-d H:i:s'),
                    'date_bonus' => Carbon::now()->format('Y-m-d'),
                ]);

                //trả thưởng cho F3
                $userParentF3 = Users::with(['user_money'])->wherePhone($userParentF2->present_phone)->first();
                if ($userParentF3) {
                    $userParentF3->user_money->money_bonus += $pricePayed * 0.05;
                    $userParentF3->user_money->save();
                    $totalBonusPercent -= 0.05;
                    HistoryBonus::insert([
                        'user_id' => $userParentF3->id,
                        'money_bonus' => $pricePayed * 0.05,
                        'time_bonus' => Carbon::now()->format('Y-m-d H:i:s'),
                        'date_bonus' => Carbon::now()->format('Y-m-d'),
                    ]);
                }
            }
        }

        //trả % cho VIP
        if ($totalBonusPercent > 0) {
            $userParentVip = Users::with(['user_money'])->whereUsername('VIP')->first();
            $userParentVip->user_money->money_bonus += $pricePayed * $totalBonusPercent;
            $userParentVip->user_money->save();
            HistoryBonus::insert([
                'user_id' => $userParentVip->id,
                'money_bonus' => $pricePayed * $totalBonusPercent,
                'time_bonus' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
