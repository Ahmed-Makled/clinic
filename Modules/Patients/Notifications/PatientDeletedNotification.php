<?php

namespace Modules\Patients\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PatientDeletedNotification extends Notification
{
    use Queueable;

    protected $patientName;

    public function __construct(string $patientName)
    {
        $this->patientName = $patientName;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'patient_deleted',
            'message' => "تم حذف المريض: {$this->patientName}"
        ];
    }
}
