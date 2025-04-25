<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('auth::passwords.email', [
            'title' => 'نسيت كلمة المرور'
        ]);
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
