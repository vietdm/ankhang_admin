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
        Schema::table('withdraw', function (Blueprint $table) {
            $table->string('bin')->after('status')->default(null);
            $table->string('account_number')->after('status')->default(null);
            $table->string('branch')->after('status')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdraw', function (Blueprint $table) {
            $table->dropColumn('bin');
            $table->dropColumn('account_number');
            $table->dropColumn('branch');
        });
    }
};
