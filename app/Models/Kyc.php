<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'kyc';

    const S_CREATED = 0;
    const S_ACCEPTED = 1;
    const S_CANCELED = 2;

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
