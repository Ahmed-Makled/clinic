<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Doctors\Entities\Doctor;
use Modules\Doctors\Notifications\IncompleteProfileNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $redirectTo = '/';

    public function showLoginForm()
    {
        return view('auth::login', [
            'title' => 'تسجيل الدخول - Clinic Master'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'password.required' => 'كلمة المرور مطلوبة'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check if user is a doctor with incomplete profile
            $user = Auth::user();
            if ($user->hasRole('Doctor')) {
                $doctor = Doctor::where('user_id', $user->id)->first();

                if ($doctor && !$doctor->is_profile_completed) {
                    // Update profile completion status by calling the isProfileCompleted method
                    // This will automatically save the doctor model if the status changes
                    $doctor->isProfileCompleted();

                    // If profile is still not completed, redirect to profile page
                    if (!$doctor->is_profile_completed) {
                        // Still send notification for record keeping
                        $user->notify(new IncompleteProfileNotification($doctor));

                        // Redirect directly to profile page with warning message
                        return redirect('/doctors/profile');
                    }
                }
            }

            return redirect()->intended($this->redirectTo);
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'تم تسجيل الخروج بنجاح');
    }

    public function logoutGet(Request $request)
    {
        // Handle GET requests to logout by actually logging out
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('status', 'تم تسجيل الخروج بنجاح');
        }

        return redirect()->route('login')->with('info', 'تم تسجيل الخروج بالفعل');
    }
}
