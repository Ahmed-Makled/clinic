<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contacts\Entities\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactsController extends Controller
{
    /**
     * عرض صفحة الاتصال
     */
    public function index()
    {
        return view('contacts::index', [
            'title' => 'Contact Us',
            'classes' => 'bg-white'
        ]);
    }

    /**
     * حفظ رسالة اتصال جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'name.required' => 'يرجى إدخال الاسم',
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'subject.required' => 'يرجى إدخال موضوع الرسالة',
            'message.required' => 'يرجى إدخال نص الرسالة',
        ]);

        // حفظ رسالة الاتصال في قاعدة البيانات
        Contact::create($validated);

        // إرسال إشعار بريد إلكتروني مع معالجة الأخطاء
        try {
            // إنشاء رسالة بريد إلكتروني منظمة
            $emailContent = view('emails.contact', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message']
            ])->render();

            Mail::html($emailContent, function($message) use ($validated) {
                $message->to(config('contacts.admin_email', 'ahmed.makled@roboost.app'))
                        ->subject('رسالة جديدة من نموذج الاتصال: ' . $validated['subject'])
                        ->from($validated['email'], $validated['name']);
            });

            \Illuminate\Support\Facades\Log::info('Contact email sent successfully from: ' . $validated['email']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('فشل في إرسال البريد الإلكتروني: ' . $e->getMessage(), [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'error' => $e->getMessage()
            ]);

            $errorMessage = 'عذراً، حدث خطأ أثناء إرسال رسالتك. ';
            if (str_contains($e->getMessage(), 'Connection could not be established')) {
                $errorMessage .= 'تعذر الاتصال بخادم البريد الإلكتروني.';
            } elseif (str_contains($e->getMessage(), 'Invalid credentials')) {
                $errorMessage .= 'خطأ في إعدادات البريد الإلكتروني.';
            }
            $errorMessage .= ' يرجى المحاولة مرة أخرى لاحقاً.';

            return redirect()->back()->with('error', $errorMessage)->withInput();
        }

        return redirect()->back()->with('success', 'تم إرسال رسالتك بنجاح. سنقوم بالرد عليك في أقرب وقت.');
    }

    /**
     * عرض قائمة رسائل الاتصال (للمسؤول)
     */
    public function adminIndex()
    {
        $contacts = Contact::latest()->paginate(10);

        return view('contacts::admin.index', [
            'contacts' => $contacts,
            'title' => 'إدارة رسائل الاتصال'
        ]);
    }
}
