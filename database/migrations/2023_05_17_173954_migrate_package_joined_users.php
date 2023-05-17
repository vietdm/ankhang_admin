<?php

use App\Models\Users;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Users::all() as $user) {
            if ($user->total_buy >= 30000000) {
                $user->package_joined = Users::PACKAGE_VIP;
                $user->save();
            } else if($user->total_buy >= 3000000) {
                $user->package_joined = Users::PACKAGE_STAR;
                $user->save();
            }
        }
    }
};
