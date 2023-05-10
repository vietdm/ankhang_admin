<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otps extends Model
{
    use HasFactory, ModelTrait;

    const VERIFY_ACCOUNT = 'verify_account';
    const WITHDRAW = 'withdraw';
    protected $table = 'otps';

    public static function insertOtp($params = [])
    {
        if (isset($params['user_id']) && isset($params['type'])) {
            self::whereUserId($params['user_id'])->whereType($params['type'])->delete();
        }
        self::insert($params);
    }
}
