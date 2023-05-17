<?php

namespace App\Models;

use App\Helpers\Format;
use App\Models\Trait\ModelTrait;
use App\Utils\OrderUtil;
use App\Utils\UserUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orders extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'orders';

    const STATUS_CREATE = 0;
    const STATUS_ACCEPT = 1;
    const STATUS_DELIVE = 2;
    const STATUS_DONE = 3;
    const STATUS_CANCEL = 4;
    const PAYED = 1;
    const NOT_PAY = 0;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function getPayedTextAttribute()
    {
        return $this->payed === 0 ? 'Chưa thanh toán' : 'Đã thanh toán';
    }

    public function getStatusTextAttribute()
    {
        if ($this->status === self::STATUS_CREATE) return 'Chưa xác nhận';
        if ($this->status === self::STATUS_CANCEL) return 'Đã hủy';
        return 'Đã xác nhận';
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            0 => '<span class="badge badge-info">Vừa đặt hàng</span>',
            1 => '<span class="badge badge-success">Đã xác nhận</span>',
            2 => '<span class="badge badge-primary">Đang vận chuyển</span>',
            3 => '<span class="badge badge-primary">Hoàn thành</span>',
            4 => '<span class="badge badge-danger">Đã hủy</span>',
            default => '',
        };
    }

    public function isCreated()
    {
        return $this->status === self::STATUS_CREATE;
    }

    public function accept(): void
    {
        //update status order
        $this->status = 1;
        $this->save();

        $totalBonusPercent = 0.46;
        $pricePayed = $this->total_price;

        $userOrder = Users::whereId($this->user_id)->first();
        $totalBuyBeforeAdd = $userOrder->total_buy;
        $userOrder->total_buy += $pricePayed;
        $totalBuyAfterAdd = $userOrder->total_buy;
        $userOrder->save();

        $levelCalc = Users::LEVEL_NOMAL;
        $percentLevel = 0;

        //trả thưởng cho P1
        $userParentP1 = Users::with(['user_money'])->whereUsername($userOrder->present_username)->first();
        if ($userParentP1) {
            OrderUtil::sendBonus(
                $userParentP1,
                $userOrder,
                $pricePayed,
                'F1',
                $totalBonusPercent,
                $levelCalc,
                $percentLevel
            );

            //trả thưởng cho P2
            $userParentP2 = Users::with(['user_money'])->whereUsername($userParentP1->present_username)->first();
            if ($userParentP2) {
                OrderUtil::sendBonus(
                    $userParentP2,
                    $userOrder,
                    $pricePayed,
                    'F2',
                    $totalBonusPercent,
                    $levelCalc,
                    $percentLevel
                );

                //trả thưởng cho P3
                $userParentP3 = Users::with(['user_money'])->whereUsername($userParentP2->present_username)->first();
                if ($userParentP3) {
                    OrderUtil::sendBonus(
                        $userParentP3,
                        $userOrder,
                        $pricePayed,
                        'F3',
                        $totalBonusPercent,
                        $levelCalc,
                        $percentLevel
                    );

                    //Trả thưởng cấp bậc từ P4 trở lên
                    if (!empty($userParentP3->present_username)) {
                        OrderUtil::loopSendBonusLevel(
                            $userParentP3->present_username,
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

        //tính toán lên cấp cho user
        $minPriceUpLevel = 3000000;
        if ($pricePayed >= $minPriceUpLevel) {
            UserUtil::upLevelChuyenVien($userOrder);
        }

        //tính toán tăng điểm AKG
        if ($totalBuyAfterAdd >= 30000000) {
            $priceCalcAkgPoint = $totalBuyBeforeAdd < 30000000 ? $totalBuyAfterAdd : $pricePayed;
            $valueOfAkg = Configs::getDouble('value_of_akg', 1);
            $point = round($priceCalcAkgPoint / $valueOfAkg);
            $userMoneyOfUserOrder = UserMoney::whereUserId($this->user_id)->first();
            $userMoneyOfUserOrder->akg_point += $point;
            $userMoneyOfUserOrder->save();
        }
    }
}
