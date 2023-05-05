<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Utils\UserUtil;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function presentName(Request $request) {
        $phone = $request->phone ?? '';
        $userWithPhone = Users::wherePhone($phone)->first();
        if(!$userWithPhone) {
            return Response::badRequest([
                'message' => 'Not found!'
            ]);
        }
        return Response::success([
            'message' => 'Success!',
            'name' => $userWithPhone->fullname
        ]);
    }

    public function getTree(Request $request) {
        $userId = $request->user->id;
        $user = Users::select(['id', 'email', 'phone', 'fullname', 'present_phone'])->whereId(1)->first()->toArray();
        $userTree = UserUtil::getTreeUser($user);
        return Response::success([
            'tree' => $userTree
        ]);
    }
}
