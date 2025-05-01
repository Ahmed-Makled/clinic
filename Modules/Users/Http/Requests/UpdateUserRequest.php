<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'required', // Removed string|max:20 validation
            'role' => 'required|exists:roles,name',
            'status' => 'boolean',
            'password' => 'nullable|string|min:6|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'role.required' => 'الدور مطلوب',
            'role.exists' => 'الدور غير موجود',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
            'password.confirmed' => 'كلمة المرور غير متطابقة'
        ];
    }
}
