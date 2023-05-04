<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    const LEVEL_NOMAL = 'nomal';
    const LEVEL_CHUYEN_VIEN = 'chuyen_vien';
    const LEVEL_TRUONG_PHONG = 'truong_phong';
    const LEVEL_PHO_GIAM_DOC = 'pho_giam_doc';
    const LEVEL_GIAM_DOC = 'giam_doc';
    const LEVEL_GIAM_DOC_CAP_CAO = 'giam_doc_cap_cao';

    protected $table = 'users';
}
