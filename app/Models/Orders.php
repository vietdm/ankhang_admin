<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use App\Utils\OrderUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orders extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'orders';

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            0 => '<span class="badge badge-info">Vừa đặt hàng</span>',
            1 => '<span class="badge badge-success">Đã xác nhận</span>',
            2 => '<span class="badge badge-primary">Đang vận chuyển</span>',
            3 => '<span class="badge badge-primary">Hoàn thành</span>',
            4 => '<span class="badge badge-error">Đã hủy</span>',
            default => '',
        };
    }

    public function accept(): void
    {
        //update status order
        $this->status = 1;
        $this->save();

        $totalBonusPercent = 0.46;
        $pricePayed = $this->total_price;

        $userOrder = Users::whereId($this->user_id)->first();
        $userOrder->total_buy += $pricePayed;
        $userOrder->save();

        $levelCalc = Users::LEVEL_NOMAL;
        $percentLevel = 0;

        //$mgs

        //trả thưởng cho F1
        $userParentF1 = Users::with(['user_money'])->wherePhone($userOrder->present_phone)->first();
        if ($userParentF1) {
            OrderUtil::sendBonus(
                $userParentF1,
                $userOrder,
                $pricePayed,
                'F1',
                $totalBonusPercent,
                $levelCalc,
                $percentLevel
            );

            //trả thưởng cho F2
            $userParentF2 = Users::with(['user_money'])->wherePhone($userParentF1->present_phone)->first();
            if ($userParentF2) {
                OrderUtil::sendBonus(
                    $userParentF2,
                    $userOrder,
                    $pricePayed,
                    'F2',
                    $totalBonusPercent,
                    $levelCalc,
                    $percentLevel
                );

                //trả thưởng cho F3
                $userParentF3 = Users::with(['user_money'])->wherePhone($userParentF2->present_phone)->first();
                if ($userParentF3) {
                    OrderUtil::sendBonus(
                        $userParentF3,
                        $userOrder,
                        $pricePayed,
                        'F3',
                        $totalBonusPercent,
                        $levelCalc,
                        $percentLevel
                    );

                    //Trả thưởng cấp bậc từ F4 trở lên
                    if (!empty($userParentF3->present_phone)) {
                        OrderUtil::loopSendBonusLevel(
                            $userParentF3->present_phone,
                            $userOrder,
                            $pricePayed,
                            $totalBonusPercent,
                            $levelCalc,
                            $percentLevel
                        );
                    }
                }
            }
        }

        //

        //trả % cho VIP
        if ($totalBonusPercent > 0) {
            //$userParentVip = Users::with(['user_money'])->whereUsername('VIP')->first();
            //$userParentVip->user_money->money_bonus += $pricePayed * $totalBonusPercent;
            //$userParentVip->user_money->save();
            //HistoryBonus::insert([
            //    'user_id' => $userParentVip->id,
            //    'money_bonus' => $pricePayed * $totalBonusPercent,
            //    'time_bonus' => Carbon::now()->format('Y-m-d H:i:s'),
            //    'date_bonus' => Carbon::now()->format('Y-m-d'),
            //]);
        }
    }
}
