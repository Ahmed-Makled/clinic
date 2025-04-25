<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('auth::passwords.reset', [
            'title' => 'إعادة تعيين كلمة المرور',
            'token' => $token,
            'email' => $request->email
        ]);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', 'تم إعادة تعيين كلمة المرور بنجاح');
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return back()->withErrors(['email' => 'رابط إعادة تعيين كلمة المرور غير صالح']);
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function validationErrorMessages()
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل',
        ];
    }
}
