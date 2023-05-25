<?php

namespace App\Models;

use App\Models\Trait\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminAccount extends Authenticatable
{
    use HasFactory, ModelTrait;
    
    protected $table = 'admin_account';

    protected $casts = [
        'role'  => 'array'
    ];

    public function allow($name) {
        if (in_array('all', $this->role)) return true;
        return in_array($name, $this->role);
    }
}
