<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Users\Entities\User;

class ResetPasswordController extends Controller
{
    protected $redirectTo = '/';

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('auth::passwords.reset', [
            'title' => 'إعادة تعيين كلمة المرور - Clinic Master',
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect($this->redirectTo)->with('status', 'تم إعادة تعيين كلمة المرور بنجاح')
                    : back()->withErrors(['email' => 'رابط إعادة تعيين كلمة المرور غير صالح']);
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
