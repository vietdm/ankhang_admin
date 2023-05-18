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
        Schema::table('join_cashback_event', function (Blueprint $table) {
            $table->integer('cashbacked')->default(0)->after('datetime_join');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('join_cashback_event', function (Blueprint $table) {
            $table->dropColumn('cashbacked');
        });
    }
};
