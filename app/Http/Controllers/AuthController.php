<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(): View|Application|Factory
    {
        return view('auth0.login');
    }

    public function loginPost(Request $request): JsonResponse
    {
        $username = $request->get('username', '');
        $password = $request->get('password', '');

        if (empty($username) || empty($password)) {
            return Response::badRequest('Thông tin đăng nhập không đúng!');
        }

        $credentials = [
            'username' => $username,
            'password' => $password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return Response::success('Đăng nhập thành công');
        }
 
        return Response::badRequest('Thông tin đăng nhập không đúng!');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->to('/auth0/login');
    }
}
