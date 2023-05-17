<?php

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
        Schema::table('user_money', function (Blueprint $table) {
            $table->double('akg_point')->default(0)->after('money_bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_money', function (Blueprint $table) {
            $table->dropColumn('akg_point');
        });
    }
};
