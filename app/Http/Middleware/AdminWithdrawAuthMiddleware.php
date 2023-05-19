<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class AdminWithdrawAuthMiddleware
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): Response
    {
        $keySession = config('admin.key_session_withdraw', '');
        $status = session()->get($keySession, '0');

        if ($status === '0') {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
