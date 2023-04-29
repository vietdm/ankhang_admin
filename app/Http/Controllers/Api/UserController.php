<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function presentName(Request $request) {
        $phone = $request->phone ?? '';
        $userWithPhone = Users::wherePhone($phone)->first();
        if(!$userWithPhone) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Not found!'
            ]);
        }
        return Response::success([
            'success' => true,
            'message' => 'Success!',
            'name' => $userWithPhone->fullname
        ]);
    }
}