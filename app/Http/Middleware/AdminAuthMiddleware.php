<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): Response
    {
        $keySession = config('admin.key_session', '');
        $status = session()->get($keySession, '0');

        if ($status === '0') {
            return redirect()->to('/auth0/login');
        }

        return $next($request);
    }
}
