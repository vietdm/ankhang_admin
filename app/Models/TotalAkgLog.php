<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalAkgLog extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'total_akg_log';

    const TYPE_GIOI_THIEU = 'gioi_thieu';
    const TYPE_MUA_HANG = 'mua_hang';
}
