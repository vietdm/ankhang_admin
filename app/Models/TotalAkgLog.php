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

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public static function listType($ignore = [])
    {
        $query = self::select(['type', 'content'])->groupBy(['type', 'content']);
        foreach ($ignore as $type) {
            $query->where('type', '!=', $type);
        }
        return $query->get()->toArray();
    }
}
