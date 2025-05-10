<?php

namespace Modules\Doctors\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorDeletedNotification extends Notification
{
    use Queueable;

    protected $doctorName;

    public function __construct(string $doctorName)
    {
        $this->doctorName = $doctorName;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'doctor_deleted',
            'message' => "تم حذف الطبيب: {$this->doctorName}"
        ];
    }
}
