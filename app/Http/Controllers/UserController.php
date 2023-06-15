<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Users;
use App\Utils\UserUtil;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function all()
    {
        $users = Users::all();
        return view('users.list', compact('users'));
    }

    public function detail($id)
    {
        //get user info
        $user = Users::with(['_parent', 'user_money'])->whereId($id)->first();
        if ($user == null) {
            return redirect()->to('/user/all');
        }

        //get list parent
        UserUtil::getAllParent($user->present_username, $parents);

        //get total child and sale
        UserUtil::getTotalChildAndSaleMergeCurrentUser($user, $totalChild, $totalSale, $totalOrder);

        return view('users.detail', compact([
            'user',
            'parents',
            'totalChild',
            'totalSale',
            'totalOrder',
        ]));
    }

    public function children($id)
    {
        $user = Users::whereId($id)->first();
        if ($user == null) {
            return Response::badRequest('Người dùng không tồn tại!');
        }
        $users = Users::wherePresentUsername($user->username)->get();
        $html = view('users.table-data', ['users' => $users, 'canShowLine' => true])->render();
        return Response::success([
            'html' => $html,
            'user' => [
                'fullname' => $user->fullname,
                'username' => $user->username,
            ]
        ]);
    }
}
