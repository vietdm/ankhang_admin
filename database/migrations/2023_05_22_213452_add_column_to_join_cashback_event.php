<?php

use App\Models\JoinCashbackEvent;
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
            $table->integer('order')->after('id')->default(0);
        });

        foreach (JoinCashbackEvent::all() as $event) {
            $event->order = $event->id;
            $event->save();
        }
    }
};
