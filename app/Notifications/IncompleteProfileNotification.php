<?php

namespace App\Notifications;

use Modules\Doctors\Entities\Doctor;

class IncompleteProfileNotification extends \Modules\Doctors\Notifications\IncompleteProfileNotification
{
    // Extending the module notification class to provide an alias
    // This is needed because some parts of the application might be looking for App\Notifications namespace
}
