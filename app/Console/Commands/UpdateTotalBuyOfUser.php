<?php

namespace App\Console\Commands;

use App\Models\Orders;
use App\Models\Users;
use Exception;
use Illuminate\Console\Command;

class UpdateTotalBuyOfUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-total-buy-of-user {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update total_buy of user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('id');
        if ($userId == null) {
            throw new Exception('User Id for command app:update-total-buy-of-user not found!');
        }

        $user = Users::whereId($userId)->first();
        if ($user == null) {
            throw new Exception('User command app:update-total-buy-of-user not found!');
        }

        $totalPriceBuy = 0;
        $orders = Orders::select(['total_price_pay'])->whereUserId($userId)->whereIn('status', [1, 2, 3])->get();
        foreach ($orders as $order) {
            $totalPriceBuy += $order->total_price_pay;
        }

        $user->total_buy = $totalPriceBuy;
        $user->save();
    }
}
