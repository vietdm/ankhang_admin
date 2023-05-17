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
        Schema::table('mission_list', function (Blueprint $table) {
            $table->integer('order')->after('active');
        });

        $order = 1;
        foreach (\App\Models\MissionList::all() as $mission) {
            $mission->order = $order++;
            $mission->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_list', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
