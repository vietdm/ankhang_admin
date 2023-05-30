<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryBonus extends Model
{
    use HasFactory, ModelTrait;

    const HH_TRUC_TIEP = 'truc_tiep';
    const HH_DONG_CAP = 'dong_cap';
    const HH_CAP_BAC = 'cap_bac';

    protected $table = 'history_bonus';

    public function user_from()
    {
        return $this->belongsTo(Users::class, 'from_user_id', 'id');
    }
}
