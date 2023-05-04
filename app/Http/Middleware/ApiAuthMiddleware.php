<?php

namespace App\Http\Middleware;

use App\Helpers\JwtHelper;
use App\Models\Users;
use Closure;
use Illuminate\Http\Request;
use App\Helpers\Response as HelperResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!JwtHelper::verify($token)) {
            return HelperResponse::Unauthorized([
                'success' => 0,
                'message' => 'Token đã hết hạn hoặc không chính xác'
            ]);
        }
        $payload = JwtHelper::decode($token);
        $user = Users::whereId($payload['id'])->first();

        if ($user == null) {
            return HelperResponse::Unauthorized([
                'success' => 0,
                'message' => 'Người dùng không còn tồn tại'
            ]);
        }

        $request->user = $user;
        return $next($request);
    }
}
