<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (! $user || ($roles && ! $user->hasAnyRole($roles))) {
            throw new AccessDeniedHttpException(__('คุณไม่มีสิทธิ์เข้าถึงฟังก์ชันนี้'));
        }

        return $next($request);
    }
}
