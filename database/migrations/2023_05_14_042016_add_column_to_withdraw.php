<?php

use App\Models\Withdraw;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('withdraw', function (Blueprint $table) {
            $table->double('money_real')->after('money')->default(0);
        });
        foreach (Withdraw::all() as $withdraw) {
            $withdraw->money_real = $withdraw->money - $withdraw->money * 0.1;
            $withdraw->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdraw', function (Blueprint $table) {
            //
        });
    }
};
