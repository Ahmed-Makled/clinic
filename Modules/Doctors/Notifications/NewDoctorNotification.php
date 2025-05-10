<?php

namespace Modules\Doctors\Notifications;

use Modules\Doctors\Entities\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewDoctorNotification extends Notification
{
    use Queueable;

    protected $doctor;

    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('طبيب جديد')
            ->line('تم إضافة طبيب جديد:')
            ->line("الاسم: {$this->doctor->name}")
            ->line("التخصص: {$this->doctor->categories->pluck('name')->implode(', ')}")
            ->action('عرض الطبيب', route('doctors.index', ['doctor' => $this->doctor->id]));
    }

    public function toArray($notifiable): array
    {
        return [
            'id' => $this->doctor->id,
            'name' => $this->doctor->name,
            'type' => 'new_doctor',
            'message' => "تم إضافة طبيب جديد: {$this->doctor->name}"
        ];
    }
}
