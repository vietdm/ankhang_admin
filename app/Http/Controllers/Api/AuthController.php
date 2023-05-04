<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Users;
use App\Models\UserTree;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDOException;

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

        $userWithEmail = Users::whereEmail($request->email)->first();
        if (!!$userWithEmail) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Email đã được sử dụng!'
            ]);
        }

        $userWithCCCD = Users::whereCccd($request->cccd)->first();
        if (!!$userWithCCCD) {
            return Response::badRequest([
                'success' => false,
                'message' => 'Số CMT/CCCD đã được sử dụng!'
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

        DB::beginTransaction();
        try {
            $newUser = new Users();
            $newUser->username = $request->username;
            $newUser->email = $request->email;
            $newUser->cccd = $request->cccd;
            $newUser->phone = $request->phone;
            $newUser->fullname = $request->fullname;
            $newUser->password = bcrypt($request->password);
            $newUser->present_phone = $request->present_phone;
            $newUser->save();

            //$newTree = new UserTree();
            //$newTree->user_id = $newUser->id;
            //$newTree->user_parent_id = $userWithPresentPhone->id;
            //$newTree->tree = json_encode([]);
            //$newTree->save();

            DB::commit();
            return Response::success([
                'success' => true,
                'message' => 'Tạo tài khoản thành công, vui lòng đăng nhập lại!'
            ], 201);
        } catch  (Exception|PDOException $e) {
            DB::rollBack();
            return Response::badRequest([
                'success' => false,
                'message' => 'Tạo tài không thành công, vui lòng liên hệ quản trị viên!'
            ], 201);
        }
    }

    public function verifyToken(Request $request): JsonResponse
    {
        return Response::success([
            'success' => JwtHelper::verify($request->token) ? 1 : 0
        ]);
    }

    public function info(Request $request): JsonResponse
    {
        if (!JwtHelper::verify($request->token)) {
            return Response::badRequest(['success' => 0, 'message' => 'Token đã hết hạn hoặc không chính xác']);
        }
        $payload = JwtHelper::decode($request->token);
        $user = Users::select([
            'username',
            'email',
            'cccd',
            'phone',
            'fullname',
            'id',
            'present_phone',
            'address'
        ])->whereId($payload['id'])->first();
        if (!$user) {
            return Response::badRequest(['success' => 0, 'message' => 'Người dùng không tồn tại!']);
        }
        return Response::success(['success' => 1, 'message' => 'Success!', 'user' => $user]);
    }
}
