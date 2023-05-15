<?php

use App\Models\BankInfo;
use App\Models\Users;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Users::all() as $user) {
            if (BankInfo::whereUserId($user->id)->first() != null) {
                continue;
            }
            BankInfo::insert([
                'user_id' => $user->id,
                'bin' => '',
                'account_number' => '',
                'branch' => ''
            ]);
        }
    }
};
