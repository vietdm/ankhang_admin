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
        Schema::table('level_up_condition', function (Blueprint $table) {
            $table->integer('from_user_id')->default(0)->after('user_id');
            $table->dropColumn('count_pass');
        });
    }
};
