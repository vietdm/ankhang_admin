<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPointHistory extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'product_point_history';

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
