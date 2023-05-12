<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'withdraw';

    const STATUS_CREATED = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_CANCEL = 3;

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Banks::class, 'bin', 'bin');
    }

    public function getMoneyRealAttribute()
    {
        return $this->money - $this->money * 0.1;
    }

    public function isCreated()
    {
        return $this->status === self::STATUS_CREATED;
    }
}
