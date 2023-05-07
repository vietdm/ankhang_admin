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
        $user = Users::select(['id', 'phone', 'fullname'])->whereId($userId)->first()->toArray();
        $userTree = [...$user];

        //get level 1
        $userLevel1 = Users::select(['id', 'phone', 'fullname'])->wherePresentPhone($user['phone'])->get()->toArray();

        //get level2
        foreach ($userLevel1 as $userlv1) {
            $userLevel2 = Users::select(['id', 'phone', 'fullname'])->wherePresentPhone($userlv1['phone'])->get()->toArray();

            //get level 3
            foreach ($userLevel2 as $userlv2) {
                $userLevel3 = Users::select(['id', 'phone', 'fullname'])->wherePresentPhone($userlv2['phone'])->get()->toArray();
                $userLevel2['children'][] = $userLevel3;
            }

            $userLevel1['children'][] = $userLevel2;
        }

        $userTree['children'] = $userLevel1;

        return Response::success([
            'tree' => $userTree
        ]);
    }

    public function getChild($id) {
        $user = Users::select(['phone'])->whereId($id)->first();
        if (!$user) {
            return Response::success(['child' => []]);
        }
        $child = Users::select(['id', 'fullname'])->wherePresentPhone($user->phone)->get()->toArray();
        return Response::success(['child' => $child]);
    }
}
