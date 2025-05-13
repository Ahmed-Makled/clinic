<?php

namespace App\Notifications;

use Modules\Appointments\Entities\Appointment;

class AppointmentCompletedNotification extends \Modules\Appointments\Notifications\AppointmentCompletedNotification
{
    // Extending the module notification class to provide an alias
    // This is needed because the frontend is looking for App\Notifications namespace
}
