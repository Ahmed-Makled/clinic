<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة.');
        }

        if (!$request->user()->hasRole($role)) {
            return redirect()->back()
                ->with('error', 'عذراً، ليس لديك صلاحية الوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
