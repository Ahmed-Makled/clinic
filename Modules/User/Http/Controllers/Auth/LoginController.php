<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('user::auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ])->withInput($request->only('email'));
        }

        Auth::guard('web')->login($user);

        // Delete any existing tokens for this user
        $user->tokens()->delete();

        // Create new token for API authentication
        $token = $user->createToken('auth-token')->plainTextToken;
        session(['auth_token' => $token]);

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        // Revoke all tokens
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->forget('auth_token');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
