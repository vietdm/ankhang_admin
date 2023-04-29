<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        if (!Hash::check($request->password, $user->password)) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Mật khẩu không chính xác!'
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

        $userWithPresentPhone = Users::wherePhone($request->present_phone)->first();
        if (!$userWithPresentPhone) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Người giới thiệu không tồn tại!'
            ]);
        }

        $newUser = new Users();
        $newUser->username = $request->username;
        $newUser->phone = $request->phone;
        $newUser->fullname = $request->fullname;
        $newUser->password = bcrypt($request->password);
        $newUser->present_phone = $request->present_phone;
        $newUser->save();

        return Response::success([
            'success' => true,
            'message' => 'Tạo tài khoản thành công, vui lòng đăng nhập lại!'
        ], 201);
    }

    public function verifyToken(Request $request) {
        return Response::success([
            'success' => JwtHelper::verify($request->token) ? 1 : 0
        ]);
    }
}
