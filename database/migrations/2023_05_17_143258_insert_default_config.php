<?php

use App\Helpers\Format;
use App\Models\Configs;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Configs::set("total_akg", 60000000, Format::Double);
        Configs::set("value_of_akg", 4000, Format::Double);
        Configs::set("allow_increase_value_of_akg", true, Format::Boolean);
    }
};
