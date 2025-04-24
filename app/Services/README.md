# Service Layer

The Service Layer in this application provides a structured approach to organizing business logic.

## Purpose

Services are responsible for implementing the business logic of the application, separate from the application's controllers, models, and views. This separation helps to:

- Keep controllers thin
- Make business logic reusable across different parts of the application
- Facilitate testing of business logic in isolation
- Promote single responsibility principle

## Usage Guidelines

### Creating a New Service

1. Create a new service class that extends the `BaseService` class:

```php
namespace App\Services;

class AppointmentService extends BaseService
{
    // Service methods go here
}
```

2. Inject any dependencies through the constructor:

```php
namespace App\Services;

use App\Repositories\AppointmentRepository;

class AppointmentService extends BaseService
{
    protected $appointmentRepository;
    
    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }
    
    // Service methods go here
}
```

3. Implement methods that encapsulate business logic:

```php
public function scheduleAppointment(array $data)
{
    try {
        // Check if the time slot is available
        if (!$this->isTimeSlotAvailable($data['doctor_id'], $data['date'], $data['time'])) {
            return ['success' => false, 'message' => 'This time slot is not available'];
        }
        
        // Create the appointment
        $appointment = $this->appointmentRepository->create($data);
        
        // Send notifications
        // event(new AppointmentScheduled($appointment));
        
        return ['success' => true, 'data' => $appointment];
    } catch (\Exception $e) {
        return $this->handleException($e);
    }
}
```

### Best Practices

1. Keep services focused on a specific domain or entity
2. Use dependency injection for dependencies
3. Return structured responses (consider using DTOs)
4. Use try-catch blocks to handle exceptions
5. Avoid calling services from models
6. Document each service method with PHPDoc comments

## Module Integration

When working with module-specific services, follow the same patterns but place the services within the module's service directory.

For cross-module functionality, consider using the application's central service layer and events/listeners for communication between modules.

