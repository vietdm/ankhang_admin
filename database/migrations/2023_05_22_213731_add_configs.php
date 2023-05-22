<?php

use App\Helpers\Format;
use App\Models\Configs;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Configs::set('last_order_cashback_event', 0, Format::Integer);
    }
};
