<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferAkgHistory extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'transfer_akg_history';

    public function to_user()
    {
        return $this->belongsTo(Users::class, 'to_user_id', 'id');
    }
}
