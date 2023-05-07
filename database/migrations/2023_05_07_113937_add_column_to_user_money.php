<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_money', function (Blueprint $table) {
            $table->double('money_bonus')->default(0)->after('akg_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_money', function (Blueprint $table) {
            $table->dropColumn('money_bonus');
        });
    }
};
