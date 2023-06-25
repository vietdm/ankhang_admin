<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayProductInMonth extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'pay_product_in_month';

    public function user() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public static function createDefaultRecord($userId, $month = null, $year = null) {
        if (is_null($month)) {
            $month = now()->format('m');
        }
        if (is_null($year)) {
            $year = now()->format('Y');
        }
        return self::insert([
            'user_id' => $userId,
            'month' => $month,
            'year' => $year,
            'money' => 0
        ]);
    }

    public static function add($userId, $money) {
        $month = now()->format('m');
        $year = now()->format('Y');
        $record = self::where([
            'user_id' => $userId,
            'month' => $month,
            'year' => $year,
        ])->first();
        if ($record == null) {
            $record = self::createDefaultRecord($userId, $month, $year);
        }
        $record->money += $money;
        $record->save();
    }

    public static function get($userId, $month = null, $year = null) {
        if (is_null($month)) {
            $month = now()->format('m');
        }
        if (is_null($year)) {
            $year = now()->format('Y');
        }
        $record = self::where([
            'user_id' => $userId,
            'month' => $month,
            'year' => $year,
        ])->first();
        if ($record == null) {
            $record = self::createDefaultRecord($userId, $month, $year);
        }
        return $record;
    }
}
