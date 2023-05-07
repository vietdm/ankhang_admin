<?php

use App\Models\UserMoney;
use App\Models\Users;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Users::select(['id'])->get() as $user) {
            DB::statement('ALTER TABLE `user_money` CHANGE COLUMN `akg_point` `akg_point` double NOT NULL DEFAULT 0;');
            if (UserMoney::whereUserId($user->id)->first() == null) {
                $newUserMoney = new UserMoney();
                $newUserMoney->user_id = $user->id;
                $newUserMoney->save();
            }
        }
    }
};
