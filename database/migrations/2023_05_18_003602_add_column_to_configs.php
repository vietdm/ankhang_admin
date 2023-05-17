<?php

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
        Schema::table('configs', function (Blueprint $table) {
            $table->string('comment')->default('')->after('value');
        });

        $config = Configs::whereName('total_akg')->first();
        $config->comment = 'Tổng số lượng AKG còn lại';
        $config->save();

        $config = Configs::whereName('value_of_akg')->first();
        $config->comment = 'Giá trị của 1 AKG ở thời điểm hiện tại';
        $config->save();

        $config = Configs::whereName('allow_increase_value_of_akg')->first();
        $config->comment = 'Có cho phép tự động tăng giá trị đồng AKG mỗi 3 ngày hay không ( 1 or 0 )';
        $config->save();

        $config = Configs::whereName('allow_put_telegram')->first();
        $config->comment = 'Có cho phép gửi tin nhắn qua telegram không ( 1 or 0 )';
        $config->save();
    }
};
