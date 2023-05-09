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
        Schema::table('history_bonus', function (Blueprint $table) {
            $table->integer('from_user_id')->after('user_id')->default(0);
            $table->string('content')->default(null)->after('date_bonus');
            $table->dropColumn('time_bonus');
        });
    }
};
