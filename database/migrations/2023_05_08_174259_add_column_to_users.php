<?php

use App\Models\Orders;
use App\Models\Users;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('package_joined')->nullable()->after('level');
        });

        $userChecked = [];
        foreach (Orders::all() as $order) {
            if (in_array($order->user_id, $userChecked)) continue;
            $user = Users::whereId($order->user_id)->first();
            if (!$user) continue;
            $user->package_joined = Users::PACKAGE_STAR;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('package_joined');
        });
    }
};
