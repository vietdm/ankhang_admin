<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otps extends Model
{
    use HasFactory, ModelTrait;

    const VERIFY_ACCOUNT = 'verify_account';
    protected $table = 'otps';
}
