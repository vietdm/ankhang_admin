<?php

namespace App\Console\Commands;

use App\Models\HistoryBonus;
use App\Models\Orders;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDOException;

class CalcBonusMonthy extends Command
{
    protected $signature = 'user:calc-bonus-monthly';
    protected $description = 'Tính toán phát bonus đồng chia + đồng cấp';

    private $orders = null;
    private $minSale = [
        Users::LEVEL_CHUYEN_VIEN => 6000000,
        Users::LEVEL_TRUONG_PHONG => 20000000,
        Users::LEVEL_PHO_GIAM_DOC => 80000000,
        Users::LEVEL_GIAM_DOC => 250000000,
        Users::LEVEL_GIAM_DOC_CAP_CAO => 600000000,
    ];

    public function handle()
    {
        $date = (int)Carbon::now()->format('d');
        if ($date !== 1) {
            return;
        }

        $monthOfToday = Carbon::now()->format('Y-m-d');
        $startDate = $monthOfToday . ' 00:00:00';
        $endDate = $monthOfToday . ' 23:59:59';

        $this->orders = Orders::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->where('payed', 1)->get();
        
        DB::beginTransaction();
        try {
            $this->calcDongCap();
            DB::commit();
        } catch (Exception|PDOException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function calcDongCap()
    {
        $aryUsernameCalc = ['VIP2', 'VIP3', 'VIP4'];
        foreach ($aryUsernameCalc as $username) {
            $totalSale = $this->getTotalSaleOfUser($username);
            $moneyDivided = $totalSale * 0.02;

            $this->countPosition($username, Users::LEVEL_CHUYEN_VIEN, $totalUserChuyenVien);
            if ($totalUserChuyenVien > 0) {
                $moneyDividedChuyenVien = round($moneyDivided / $totalUserChuyenVien);
                $this->loopDividedMoneyDongCap($username, $moneyDividedChuyenVien, Users::LEVEL_CHUYEN_VIEN);
            }

            $this->countPosition($username, Users::LEVEL_TRUONG_PHONG, $totalUserTruongPhong);
            if ($totalUserTruongPhong > 0) {
                $moneyDividedTruongPhong = round($moneyDivided / $totalUserTruongPhong);
                $this->loopDividedMoneyDongCap($username, $moneyDividedTruongPhong, Users::LEVEL_TRUONG_PHONG);
            }
            
            $this->countPosition($username, Users::LEVEL_PHO_GIAM_DOC, $totalUserPhoGiamDoc);
            if ($totalUserPhoGiamDoc > 0) {
                $moneyDividedPhoGiamDoc = round($moneyDivided / $totalUserPhoGiamDoc);
                $this->loopDividedMoneyDongCap($username, $moneyDividedPhoGiamDoc, Users::LEVEL_PHO_GIAM_DOC);
            }
        }
    }

    private function countPosition($username, $position, &$total = 0)
    {
        $user = Users::select(['username', 'level'])->whereUsername($username)->first();
        if (!$user) return;

        if ($user->level === $position) {
            $total += 1;
        }

        foreach (Users::select(['username'])->wherePresentUsername($user->username)->get() as $user) {
            $this->countPosition($user->username, $position, $total);
        }
    }

    private function loopDividedMoneyDongCap($username, $moneyDivided, $position)
    {
        $user = Users::with(['user_money'])->whereUsername($username)->first();
        if (!$user) return;

        if ($user->level !== $position) {
            goto _continue;
        }

        if ($this->getTotalSaleOfUser($username) < $this->minSale[$position]) {
            goto _continue;
        }

        HistoryBonus::insert([
            'user_id' => $user->id,
            'from_user_id' => 0,
            'money_bonus' => $moneyDivided,
            'type' => HistoryBonus::HH_DONG_CAP,
            'date_bonus' => Carbon::now()->format('Y-m-d'),
            'content' => 'Thưởng hoa hồng đồng cấp',
        ]);

        $user->user_money->money_bonus += $moneyDivided;
        $user->user_money->save();

        _continue:
        foreach (Users::select(['username'])->wherePresentUsername($username)->get() as $user) {
            $this->loopDividedMoneyDongCap($user->username, $moneyDivided, $position);
        }
    }

    private function getTotalSaleOfUser($username)
    {
        $user = Users::whereUsername($username)->first();
        $this->getTotalSaleOnMonth($username, $totalSale);
        $totalSale += $this->getTotalBuyOnMonth($user->id);
        return $totalSale;
    }

    private function getTotalSaleOnMonth($username, &$totalSale = 0): void
    {
        $allUser = Users::select(['id', 'username', 'total_buy'])->where('present_username', $username)->get();
        foreach ($allUser as $user) {
            $totalSale += $this->getTotalBuyOnMonth($user->id);
            $this->getTotalSaleOnMonth($user->username, $totalSale);
        }
    }

    private function getTotalBuyOnMonth($userId)
    {
        $filtered = $this->orders->where('user_id', $userId);
        $total = 0;
        foreach ($filtered->all() as $order) {
            $total += $order->total_price_pay;
        }
        return $total;
    }
}
