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
        Schema::table('mission', function (Blueprint $table) {
            $table->integer('mission_list_id')->after('time_done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission', function (Blueprint $table) {
            $table->dropColumn('mission_list_id');
        });
    }
};
