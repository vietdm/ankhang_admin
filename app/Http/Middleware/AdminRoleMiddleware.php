<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!admin()->allow($role)) {
            return redirect()->to('/');
        }
        return $next($request);
    }
}
