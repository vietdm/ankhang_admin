<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Users;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = Users::wherePhone($request->phone)->first();
        if (!$user) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Người dùng không tồn tại!'
            ]);
        }
        $token = JwtHelper::encode(['id' => $user->id]);
        return Response::success([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'token' => $token
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $userWithUsername = Users::whereUsername($request->username)->first();
        if (!!$userWithUsername) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Tên tài khoản đã tồn tại!'
            ]);
        }

        $userWithPhone = Users::wherePhone($request->phone)->first();
        if (!!$userWithPhone) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Số điện thoại đã được sử dụng!'
            ]);
        }

        $newUser = new Users();
        $newUser->username = $request->username;
        $newUser->phone = $request->phone;
        $newUser->fullname = $request->fullname;
        $newUser->password = bcrypt($request->password);
        $newUser->save();

        return Response::success([
            'success' => true,
            'message' => 'Tạo tài khoản thành công, vui lòng đăng nhập lại!'
        ], 201);
    }
}
