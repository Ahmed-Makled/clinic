<?php

namespace Modules\Doctors\Notifications;

use Modules\Doctors\Entities\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class IncompleteProfileNotification extends Notification
{
    use Queueable;

    protected $doctor;
    protected $missingData;

    /**
     * Create a new notification instance.
     */
    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->missingData = $doctor->missing_profile_data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $missingFields = implode('، ', $this->missingData);

        return (new MailMessage)
            ->subject('استكمال البيانات الشخصية')
            ->greeting('مرحباً د. ' . $this->doctor->name)
            ->line('نرحب بك في منصتنا الطبية!')
            ->line('لتتمكن من الاستفادة الكاملة من خدمات المنصة، يرجى استكمال ملفك الشخصي.')
            ->line('البيانات المطلوب استكمالها: ' . $missingFields)
            ->action('استكمال البيانات', url('/doctors/profile'))
            ->line('شكرًا لاختيارك منصتنا!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $missingFields = implode('، ', $this->missingData);

        return [
            'id' => $this->doctor->id,
            'name' => $this->doctor->name,
            'type' => 'incomplete_profile',
            'message' => "يرجى استكمال ملفك الشخصي للاستفادة من جميع خدمات المنصة",
            'missing_data' => $this->missingData,
            'action' => url('/doctors/profile')
        ];
    }
}
