<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAccount extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'admin_account';
    protected $casts = [
        'role'  => 'array'
    ];
}
