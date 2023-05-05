<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;

class OrderController extends Controller
{
    /**
     * Model
     *
     * @return Users::class
     */
    public function model()
    {
        return Users::class;
    }

    public function childUsers(Request $request) {
        $userParam = Users::findOrFail($request->userId);

        $childUsers = $userParam->child_users;

        return array_merge((array)$userParam, $childUsers);
    }
}
