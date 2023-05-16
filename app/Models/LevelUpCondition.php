<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelUpCondition extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'level_up_condition';
}
