<?php

use App\Helpers\Format;
use App\Models\Configs;
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
        Configs::set('allow_transfer_akg', true, Format::Boolean);
    }
};
