<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth::passwords.email', [
            'title' => 'نسيت كلمة المرور - Clinic Master'
        ]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني')
                    : back()->withErrors(['email' => 'لم نتمكن من العثور على مستخدم بهذا البريد الإلكتروني']);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(['email' => 'لم نتمكن من العثور على مستخدم بهذا البريد الإلكتروني']);
    }
}
