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
        Schema::create('lucky_event', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('order_id')->nullable();
            $table->tinyInteger('spinned')->default(0);
            $table->string('gift')->nullable();
            $table->tinyInteger('is_given')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lucky_event');
    }
};
