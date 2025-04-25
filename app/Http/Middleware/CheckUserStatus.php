<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->status) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'حسابك غير نشط. يرجى التواصل مع الإدارة.'
            ]);
        }

        return $next($request);
    }
}
