<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyEvent extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'lucky_event';

    public function user() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function order() {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
