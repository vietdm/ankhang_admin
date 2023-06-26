<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinCashbackEvent extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'join_cashback_event';

    public function user() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
