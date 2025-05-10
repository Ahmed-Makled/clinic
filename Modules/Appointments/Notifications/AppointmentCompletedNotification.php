<?php

namespace Modules\Appointments\Notifications;

use Modules\Appointments\Entities\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCompletedNotification extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم اكتمال الحجز')
            ->line('تم اكتمال الحجز التالي:')
            ->line("الطبيب: {$this->appointment->doctor->name}")
            ->line("المريض: {$this->appointment->patient->name}")
            ->line("التاريخ: {$this->appointment->formatted_date}")
            ->line("الوقت: {$this->appointment->formatted_time}")
            ->action('عرض الحجز', route('appointments.show', $this->appointment));
    }

    public function toArray($notifiable): array
    {
        return [
            'id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->name,
            'patient_name' => $this->appointment->patient->name,
            'scheduled_at' => $this->appointment->scheduled_at,
            'type' => 'appointment_completed',
            'message' => "تم اكتمال حجز المريض {$this->appointment->patient->name} مع الدكتور {$this->appointment->doctor->name}"
        ];
    }
}
