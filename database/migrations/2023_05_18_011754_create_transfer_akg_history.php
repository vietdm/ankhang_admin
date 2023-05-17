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
        Schema::create('transfer_akg_history', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('to_user_id');
            $table->double('point_send');
            $table->date('date_send');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_akg_history');
    }
};
