<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact',[
            'title' => 'Contact Us',
            'classes' => 'bg-white'
        ]);
    }

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

        // Store the contact message in database
        Contact::create($validated);

        // Send email notification with error handling
        try {
            // Create a more structured email message
            $emailContent = view('emails.contact', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message']
            ])->render();

            Mail::html($emailContent, function($message) use ($validated) {
                $message->to('ahmed.makled@roboost.app')
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
}
