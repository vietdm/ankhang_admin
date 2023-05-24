<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboOrder extends Model
{
    use HasFactory, ModelTrait;
    protected $table = 'combo_order';

    public function product() {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
