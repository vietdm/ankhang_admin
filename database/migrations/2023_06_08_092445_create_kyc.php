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
        Schema::create('kyc', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('image_front');
            $table->string('image_back');
            $table->integer('status')->default(0)->comment('0: Created, 1: Accepted, 2: Canceled');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('kyced')->default(0)->after('verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kyced');
        });
    }
};
