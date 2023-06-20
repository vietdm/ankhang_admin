<?php

namespace App\Console\Commands;

use App\Models\BankInfo;
use App\Models\Orders;
use App\Models\UserMoney;
use App\Models\Users;
use Illuminate\Console\Command;

class RefactorUserDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refactor-user-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all relationship of user removed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = Users::select(['id'])->limit(10)->get()->toArray();
        $aryUserId = array_values(array_column($users, 'id'));
        
        //clear orders
        foreach (Orders::select(['id', 'user_id'])->get() as $order) {
            if (in_array($order->user_id, $aryUserId)) continue;
            $order->delete();
        }

        //clear user money
        foreach(UserMoney::select(['id', 'user_id'])->get() as $userMoney) {
            if (in_array($userMoney->user_id, $aryUserId)) continue;
            $userMoney->delete();
        }

        //clear bank info
        foreach(BankInfo::select(['id', 'user_id'])->get() as $bankInfo) {
            if (in_array($bankInfo->user_id, $aryUserId)) continue;
            $bankInfo->delete();
        }
    }
}
