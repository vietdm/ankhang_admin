<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ForgotPassword as MailForgotPassword;
use App\Models\ForgotPassword;
use App\Models\UserMoney;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PDOException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = Users::wherePhone($request->phone)->orWhere('username', $request->phone)->first();
        if (!$user) {
            return Response::badRequest([
                'message' => 'Người dùng không tồn tại!'
            ]);
        }
        if (!Hash::check($request->password, $user->password)) {
            return Response::badRequest([
                'message' => 'Mật khẩu không chính xác!'
            ]);
        }
        $token = JwtHelper::encode(['id' => $user->id]);
        return Response::success([
            'message' => 'Đăng nhập thành công!',
            'token' => $token
        ]);
    }

    public function getPhoneByUsername(Request $request) {
        $username = $request->username;
        $user = Users::whereUsername($username)->first();
        if (!$user) {
            return Response::badRequest([
                'message' => 'Người giới thiệu không tồn tại'
            ]);
        }
        return Response::success([
            'phone' => $user->phone
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $userWithUsername = Users::whereUsername($request->username)->first();
        if (!!$userWithUsername) {
            return Response::badRequest([
                'message' => 'Tên tài khoản đã tồn tại!'
            ]);
        }

        $userWithEmail = Users::whereEmail($request->email)->first();
        if (!!$userWithEmail) {
            return Response::badRequest([
                'message' => 'Email đã được sử dụng!'
            ]);
        }

        $userWithCCCD = Users::whereCccd($request->cccd)->first();
        if (!!$userWithCCCD) {
            return Response::badRequest([
                'message' => 'Số CMT/CCCD đã được sử dụng!'
            ]);
        }

        $userWithPhone = Users::wherePhone($request->phone)->first();
        if (!!$userWithPhone) {
            return Response::badRequest([
                'message' => 'Số điện thoại đã được sử dụng!'
            ]);
        }

        $userWithPresentPhone = Users::wherePhone($request->present_phone)->first();
        if (!$userWithPresentPhone) {
            return Response::badRequest([
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
            $newUser->parent_id = $userWithPresentPhone->id;
            $newUser->save();

            DB::commit();
            return Response::success([
                'message' => 'Tạo tài khoản thành công, vui lòng đăng nhập lại!'
            ], 201);
        } catch  (Exception|PDOException $e) {
            DB::rollBack();
            return Response::badRequest([
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
        $userMoneu = UserMoney::whereUserId($request->user->id)->first();
        $request->user->password = '';
        $request->user->akg_point = $userMoneu ? $userMoneu->akg_point : 0;
        return Response::success(['message' => 'Success!', 'user' => $request->user]);
    }

    public function forgot(Request $request) {
        $phone = $request->phone;
        $user = Users::wherePhone($phone)->orWhere('email', $phone)->first();
        if (!$user) {
            return Response::badRequest(['message' => 'Người dùng không tồn tại!']);
        }
        if (empty($user->email)) {
            return Response::badRequest(['message' => 'Không tìm thấy email của người dùng này!']);
        }

        $token = sprintf("%06d", mt_rand(1, 999999));

        $oldRequestForgot = ForgotPassword::whereUserId($user->id);
        if ($oldRequestForgot != null) {
            $oldRequestForgot->delete();
        }

        $newForgotPassword = new ForgotPassword();
        $newForgotPassword->user_id = $user->id;
        $newForgotPassword->token = $token;
        $newForgotPassword->ttl = Carbon::now()->addMinutes(10)->timestamp;
        $newForgotPassword->save();

        try {
            Mail::to($user->email)->send(new MailForgotPassword($token));
            return Response::success(['message' => 'Vui lòng kiểm tra email!']);
        } catch (Exception $exception) {
            return Response::badRequest(['message' => 'Gửi mail không thành công!']);
        }
    }

    public function forgotConfirm(Request $request) {
        $token = $request->get('token');
        $phone = $request->get('phone');

        $user = Users::wherePhone($phone)->orWhere('email', $phone)->first();
        if (!$user) {
            return Response::badRequest(['message' => 'Người dùng không tồn tại!']);
        }
        if (empty($user->email)) {
            return Response::badRequest(['message' => 'Không tìm thấy email của người dùng này!']);
        }

        $recordForgot = ForgotPassword::whereUserId($user->id)->first();
        if ($recordForgot == null) {
            return Response::badRequest([
                'message' => 'Yêu cầu không tồn tại, vui lòng thực hiện lại',
                'step' => 1
            ]);
        }

        if ($token != $recordForgot->token) {
            return Response::badRequest([
                'message' => 'Mã xác nhận không chính xác!'
            ]);
        }

        if (Carbon::now()->timestamp > $recordForgot->ttl) {
            $recordForgot->delete();
            return Response::badRequest([
                'message' => 'Mã xác nhận đã hết hiệu lực',
                'step' => 1
            ]);
        }

        $tokenChangePassword = sha1($recordForgot->token . $recordForgot->ttl);

        $recordForgot->token_change_password = $tokenChangePassword;
        $recordForgot->save();

        return Response::success([
            'message' => 'Mã xác nhận chính xác!',
            'token' => $tokenChangePassword
        ]);
    }

    public function forgotChangePassword(Request $request) {
        $token = $request->token;
        $newPassword = $request->new_password;

        $recordForgot = ForgotPassword::whereTokenChangePassword($token)->first();
        if ($recordForgot == null) {
            return Response::badRequest([
                'message' => 'Yêu cầu không tồn tại, vui lòng thực hiện lại',
                'step' => 1
            ]);
        }

        $user = Users::whereId($recordForgot->user_id)->first();
        if (!$user) {
            return Response::badRequest([
                'message' => 'Người dùng không tồn tại!',
                'step' => 1
            ]);
        }

        $user->password = bcrypt($newPassword);
        $user->save();

        $recordForgot->delete();
        return Response::success([
            'message' => 'Thay đổi mật khẩu thành công, vui lòng đăng nhập lại!'
        ]);
    }
}
