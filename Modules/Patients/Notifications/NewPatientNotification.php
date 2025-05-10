<?php

namespace Modules\Patients\Notifications;

use Modules\Patients\Entities\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewPatientNotification extends Notification
{
    use Queueable;

    protected $patient;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('مريض جديد')
            ->line('تم إضافة مريض جديد:')
            ->line("الاسم: {$this->patient->user->name}")
            ->action('عرض المريض', route('patients.details', $this->patient->user));
    }

    public function toArray($notifiable): array
    {
        return [
            'id' => $this->patient->id,
            'name' => $this->patient->user->name,
            'type' => 'new_patient',
            'message' => "تم إضافة مريض جديد: {$this->patient->user->name}"
        ];
    }
}
