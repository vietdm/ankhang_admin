<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(): View|Application|Factory
    {
        return view('auth0.login');
    }

    public function loginPost(Request $request): JsonResponse
    {
        $password = $request->password;
        if (empty($password)) {
            return Response::badRequest([
                'message' => 'Không tìm thấy mật khẩu'
            ]);
        }
        if ($password === env('PWD_ADMIN_DASHBOARD')) {
            $keySession = config('admin.key_session', '');
            session()->put($keySession, '1');
            return Response::success([
                'message' => 'Đăng nhập thành công',
            ]);
        }
        if ($password === env('PWD_ADMIN_WITHDRAW')) {
            $keySession = config('admin.key_session_withdraw', '');
            session()->put($keySession, '1');
            return Response::success([
                'message' => 'Đăng nhập thành công',
                'next' => '/w'
            ]);
        }
        return Response::badRequest([
            'message' => 'Mật khẩu không chính xác'
        ]);
    }

    public function logout(): RedirectResponse
    {
        $keySession = config('admin.key_session', '');
        session()->remove($keySession);
        return redirect()->to('/auth0/login');
    }
}
