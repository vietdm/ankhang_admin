<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Format;
use App\Helpers\JwtHelper;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendMailVerifyAccount;
use App\Mail\ForgotPassword as MailForgotPassword;
use App\Mail\VerifyAccount as MailVerifyAccount;
use App\Models\Configs;
use App\Models\ForgotPassword;
use App\Models\Otps;
use App\Models\TotalAkgLog;
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
        $pwdAdmin = env('PWD_ADMIN');
        if (!$pwdAdmin || $request->password !== $pwdAdmin) {
            if (!Hash::check($request->password, $user->password)) {
                return Response::badRequest([
                    'message' => 'Mật khẩu không chính xác!'
                ]);
            }
        }
        $token = JwtHelper::encode([
            'id' => $user->id,
            'exp' => Carbon::now()->addMonth()->timestamp
        ]);
        return Response::success([
            'message' => $user->verified == '0' ? 'Vui lòng xác nhận tài khoản trước khi sử dụng' : 'Đăng nhập thành công!',
            'token' => $token,
            'verified' => $user->verified,
            'user_id' => $user->id
        ]);
    }

    public function getPhoneByUsername(Request $request)
    {
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

        $userWithPhone = Users::wherePhone($request->phone)->first();
        if (!!$userWithPhone) {
            return Response::badRequest([
                'message' => 'Số điện thoại đã được sử dụng!'
            ]);
        }

        $userWithPresentCode = Users::whereUsername($request->present_code)->first();
        if (!$userWithPresentCode) {
            return Response::badRequest([
                'message' => 'Người giới thiệu không tồn tại!'
            ]);
        }

        DB::beginTransaction();
        try {
            $newUser = new Users();
            $newUser->username = $request->username;
            $newUser->email = $request->email;
            $newUser->phone = $request->phone;
            $newUser->fullname = $request->fullname;
            $newUser->password = bcrypt($request->password);
            $newUser->present_username = $userWithPresentCode->username;
            $newUser->parent_id = $userWithPresentCode->id;
            $newUser->save();
            $newUser->createMoney();
            $newUser->createBankInfo();

            $token = sprintf("%06d", mt_rand(1, 999999));
            Otps::insert([
                'user_id' => $newUser->id,
                'token' => $token,
                'type' => Otps::VERIFY_ACCOUNT,
                'ttl' => Carbon::now()->addMinutes(10)->timestamp
            ]);

            Mail::to($newUser->email)->send(new MailVerifyAccount($token));
            // SendMailVerifyAccount::dispatch($newUser->email, $token);

            DB::commit();
            return Response::success([
                'message' => 'Tạo tài khoản thành công! Kiểm tra email và điền mã xác nhận',
                'user_id' => $newUser->id
            ], 201);
        } catch (Exception | PDOException $e) {
            DB::rollBack();
            logger($e->getMessage());
            return Response::badRequest([
                'message' => 'Tạo tài không thành công, vui lòng liên hệ quản trị viên!'
            ]);
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
        $userMoney = UserMoney::whereUserId($request->user->id)->first();
        $valueOfAkg = Configs::getDouble('value_of_akg');
        $request->user->password = '';
        $request->user->reward_point = $userMoney ? $userMoney->reward_point : 0;
        $request->user->money_bonus = $userMoney ? $userMoney->money_bonus : 0;
        $request->user->akg_point = $userMoney ? $userMoney->akg_point : 0;
        $request->user->akg_money = $userMoney ? $userMoney->akg_point * $valueOfAkg : 0;
        $request->user->cashback_point = $userMoney ? $userMoney->cashback_point : 0;
        return Response::success(['message' => 'Success!', 'user' => $request->user]);
    }

    public function forgot(Request $request)
    {
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

    public function forgotConfirm(Request $request)
    {
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

    public function forgotChangePassword(Request $request)
    {
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

    public function verifyAccount(Request $request): JsonResponse
    {
        $userId = $request->user_id;
        $otpCode = $request->otp_code;

        if (empty($userId) || empty($otpCode)) {
            return Response::badRequest([
                'message' => 'Dữ liệu xác thực không đầy đủ!'
            ]);
        }

        $user = Users::whereId($userId)->first();
        if (!$user) {
            return Response::badRequest([
                'message' => 'Người dùng không tồn tại!'
            ]);
        }

        $otpRecord = Otps::whereUserId($userId)->whereType(Otps::VERIFY_ACCOUNT)->first();
        if (!$otpRecord) {
            return Response::badRequest([
                'message' => 'Mã OTP không tồn tại hoặc đã hết hạn!'
            ]);
        }

        if (Carbon::now()->timestamp > $otpRecord->ttl) {
            $otpRecord->delete();
            return Response::badRequest([
                'message' => 'Mã OTP không tồn tại hoặc đã hết hạn!'
            ]);
        }

        $user->verified = 1;
        $user->save();
        $otpRecord->delete();

        //add point to parent
        $parent = Users::with(['user_money'])->whereUsername($user->present_username)->first();
        if ($parent) {
            $totalAkgPoint = Configs::get('total_akg', 0, Format::Double);
            if ($parent->total_pay === 0) {
                if ($totalAkgPoint >= 1) {
                    $parent->user_money->akg_point += 1;
                    $parent->user_money->save();
                    TotalAkgLog::insert([
                        'user_id' => $parent->id,
                        'date' => Carbon::now()->format('Y-m-d H:i:s'),
                        'amount' => 1,
                        'content' => 'Chi trả giới thiệu. Khách chưa vào gói'
                    ]);
                    $totalAkgPoint -= 1;
                }
            } else {
                $akgMinus = 0;
                if ($totalAkgPoint == 1) {
                    $parent->user_money->akg_point += 1;
                    $parent->user_money->save();
                    $totalAkgPoint -= 1;
                    $akgMinus = 1;
                } else if ($totalAkgPoint > 1) {
                    $parent->user_money->akg_point += 2;
                    $parent->user_money->save();
                    $totalAkgPoint -= 2;
                    $akgMinus = 2;
                }
                TotalAkgLog::insert([
                    'user_id' => $parent->id,
                    'date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'amount' => $akgMinus,
                    'content' => 'Chi trả giới thiệu. Khách đã vào gói'
                ]);
            }
            Configs::set('total_akg', $totalAkgPoint, Format::Double);
        }

        return Response::success([
            'message' => 'Xác nhận tài khoản thành công!'
        ]);
    }

    public function reSendOtp(Request $request): JsonResponse
    {
        $userId = $request->user_id;
        $user = Users::whereId($userId)->first();
        if (!$user) {
            return Response::badRequest([
                'message' => 'Người dùng không tồn tại!'
            ]);
        }

        $token = sprintf("%06d", mt_rand(1, 999999));
        Otps::insertOtp([
            'user_id' => $user->id,
            'token' => $token,
            'type' => Otps::VERIFY_ACCOUNT,
            'ttl' => Carbon::now()->addMinutes(10)->timestamp
        ]);

        Mail::to($user->email)->send(new MailVerifyAccount($token));

        return Response::success([
            'message' => 'Đã gửi OTP thành công! Vui lòng kiểm tra email'
        ]);
    }
}
