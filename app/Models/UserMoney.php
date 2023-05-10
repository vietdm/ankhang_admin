<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMoney extends Model
{
    use HasFactory;

    protected $table = 'user_money';

    public static function createNewUserMoney($userId)
    {
        $self = new self;
        $self->user_id = $userId;
        $self->cashback_point = 0;
        $self->save();
        return $self;
    }

    public static function getUserMoney($userId)
    {
        $userMoney = self::whereUserId($userId)->first();
        return $userMoney ? $userMoney : self::createNewUserMoney($userId);
    }
}
