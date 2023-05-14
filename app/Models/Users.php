<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Users extends Model
{
    use HasFactory;

    const LEVEL_NOMAL = 'nomal';
    const LEVEL_CHUYEN_VIEN = 'chuyen_vien';
    const LEVEL_TRUONG_PHONG = 'truong_phong';
    const LEVEL_PHO_GIAM_DOC = 'pho_giam_doc';
    const LEVEL_GIAM_DOC = 'giam_doc';
    const LEVEL_GIAM_DOC_CAP_CAO = 'giam_doc_cap_cao';
    const PACKAGE_STAR = 'star';
    const PACKAGE_VIP = 'vip';

    protected $table = 'users';

    public function getChildUsersAttribute()
    {
        $allUsers = Users::get();
        return Users::buildTree($allUsers, $this->username);
    }

    public function buildTree($elements, $parentUsername)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->present_username == $parentUsername) {
                $children = Users::buildTree($elements, $element->username);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function user_money(): BelongsTo
    {
        return $this->belongsTo(UserMoney::class, 'id', 'user_id');
    }

    public function createMoney()
    {
        if (UserMoney::whereUserId($this->id)->first() != null) {
            return;
        }
        $newUserMoney = new UserMoney();
        $newUserMoney->user_id = $this->id;
        $newUserMoney->save();
    }
}
