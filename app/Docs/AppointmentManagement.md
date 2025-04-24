# Appointment Management System

## Overview

The Appointment Management System provides a robust solution for scheduling, managing, and tracking patient appointments in the clinic management system. It implements a service-repository pattern to separate business logic from data access concerns, making the system maintainable and testable.

### Key Features

- Schedule new appointments with conflict detection
- Reschedule existing appointments
- Cancel appointments
- Update appointment status
- View appointments by doctor, patient, date, or status
- Filter upcoming and today's appointments
- Status workflow management

## Architecture

The appointment system is built on three main components:

1. **Appointment Model** (`app/Models/Appointment.php`)
   - Represents appointment data
   - Defines relationships with Doctor and Patient models
   - Implements status configurations and query scopes

2. **Appointment Repository** (`app/Repositories/AppointmentRepository.php`)
   - Handles data access operations
   - Provides methods for querying appointments
   - Implements conflict checking logic

3. **Appointment Service** (`app/Services/AppointmentService.php`)
   - Contains business logic
   - Validates input data
   - Manages appointment workflows
   - Formats responses

## Usage Examples

### Scheduling an Appointment

```php
// In a controller
public function store(Request $request)
{
    $data = $request->validated();
    
    $result = $this->appointmentService->scheduleAppointment($data);
    
    if ($result['success']) {
        return redirect()->route('appointments.index')
            ->with('success', $result['message']);
    }
    
    return back()->withErrors($result['errors'] ?? $result['message']);
}
```

### Rescheduling an Appointment

```php
public function reschedule(Request $request, $id)
{
    $data = $request->validate([
        'scheduled_at' => 'required|date|after:now',
    ]);
    
    $result = $this->appointmentService->rescheduleAppointment($id, $data);
    
    if ($result['success']) {
        return redirect()->route('appointments.show', $id)
            ->with('success', $result['message']);
    }
    
    return back()->withErrors($result['errors'] ?? $result['message']);
}
```

### Cancelling an Appointment

```php
public function cancel(Request $request, $id)
{
    $data = $request->validate([
        'notes' => 'nullable|string|max:1000',
    ]);
    
    $result = $this->appointmentService->cancelAppointment($id, $data);
    
    if ($result['success']) {
        return redirect()->route('appointments.index')
            ->with('success', $result['message']);
    }
    
    return back()->withErrors($result['errors'] ?? $result['message']);
}
```

### Getting Doctor's Appointments

```php
public function doctorAppointments($doctorId)
{
    $result = $this->appointmentService->getDoctorAppointments($doctorId, [
        'per_page' => 10,
        'relations' => ['patient', 'doctor']
    ]);
    
    return view('appointments.index', [
        'appointments' => $result['data']
    ]);
}
```

## Available Methods

### AppointmentService

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `scheduleAppointment(array $data)` | Schedule a new appointment | Appointment data including doctor_id, patient_id, scheduled_at | Response array |
| `rescheduleAppointment(int $id, array $data)` | Reschedule an existing appointment | Appointment ID, new data with scheduled_at | Response array |
| `updateStatus(int $id, string $status)` | Update appointment status | Appointment ID, new status | Response array |
| `cancelAppointment(int $id, array $data = [])` | Cancel an appointment | Appointment ID, optional notes | Response array |
| `getDoctorAppointments(int $doctorId, array $params = [])` | Get appointments for a doctor | Doctor ID, pagination/relation params | Response array |
| `getPatientAppointments(int $patientId, array $params = [])` | Get appointments for a patient | Patient ID, pagination/relation params | Response array |
| `getAppointmentsByDate(string $date)` | Get appointments for a specific date | Date string (YYYY-MM-DD) | Response array |
| `getUpcomingAppointments(int $limit = 10)` | Get upcoming appointments | Optional limit | Response array |
| `getTodayAppointments()` | Get today's appointments | None | Response array |

### AppointmentRepository

| Method | Description | Parameters | Returns |
|--------|-------------|------------|---------|
| `getDoctorAppointments(int $doctorId, int $perPage = 15, array $columns = ['*'], array $relations = [])` | Get paginated doctor appointments | Doctor ID, pagination/relation params | LengthAwarePaginator |
| `getPatientAppointments(int $patientId, int $perPage = 15, array $columns = ['*'], array $relations = [])` | Get paginated patient appointments | Patient ID, pagination/relation params | LengthAwarePaginator |
| `findByDoctorAndDate(int $doctorId, $date, array $relations = [])` | Find appointments by doctor and date | Doctor ID, date | Collection |
| `findConflictingAppointments(int $doctorId, $scheduledAt, int $durationMinutes = 30, ?int $excludeAppointmentId = null)` | Find conflicting appointments | Doctor ID, datetime, duration, exclude ID | Collection |
| `getUpcomingAppointments(array $relations = [], int $limit = 10)` | Get upcoming appointments | Relations, limit | Collection |
| `getTodayAppointments(array $relations = [])` | Get today's appointments | Relations | Collection |
| `getByStatus(string $status, array $relations = [], int $perPage = 15)` | Get appointments by status | Status, relations, pagination | LengthAwarePaginator |
| `updateStatus(int $id, string $status)` | Update appointment status | Appointment ID, status | bool |

## Status Workflow

Appointments in the system follow a specific status workflow:

1. **Scheduled**: Initial status when an appointment is created
2. **Completed**: Set when an appointment has been fulfilled
3. **Cancelled**: Set when an appointment will not be fulfilled

### Status Transitions

The following transitions are allowed:

- `scheduled` → `completed` (appointment fulfilled)
- `scheduled` → `cancelled` (appointment cancelled)
- `completed` → `cancelled` (marking completed appointment as error)
- `cancelled` → `scheduled` (rescheduling a cancelled appointment)

Invalid transitions will result in an error response.

## Integration Examples

### API Controller Integration

```php
class AppointmentApiController extends Controller
{
    protected $appointmentService;
    
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    
    public function index(Request $request)
    {
        $doctorId = $request->get('doctor_id');
        $patientId = $request->get('patient_id');
        $date = $request->get('date');
        
        if ($doctorId) {
            $result = $this->appointmentService->getDoctorAppointments($doctorId);
        } elseif ($patientId) {
            $result = $this->appointmentService->getPatientAppointments($patientId);
        } elseif ($date) {
            $result = $this->appointmentService->getAppointmentsByDate($date);
        } else {
            $result = $this->appointmentService->getUpcomingAppointments();
        }
        
        return response()->json($result);
    }
    
    public function store(Request $request)
    {
        $result = $this->appointmentService->scheduleAppointment($request->all());
        
        return response()->json($result, $result['status_code'] ?? 200);
    }
    
    // Other CRUD methods...
}
```

### View Integration

```php
// In a Blade view
@if($appointments->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->patient->name }}</td>
                        <td>{{ $appointment->formatted_date }}</td>
                        <td>{{ $appointment->formatted_time }}</td>
                        <td>
                            <span class="badge bg-{{ $appointment->status_color }}">
                                {{ $appointment->status_text }}
                            </span>
                        </td>
                        <td>
                            <!-- Action buttons -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No appointments found.</p>
@endif
```

## Error Handling Guidelines

The appointment system uses standardized error responses provided by the `error_response()` helper function. All service methods return an array with the following structure:

### Success Response

```php
[
    'success' => true,
    'message' => 'Operation successful message',
    'data' => $resultData,
    'status_code' => 200
]
```

### Error Response

```php
[
    'success' => false,
    'message' => 'Error message',
    'errors' => $validationErrors, // optional, present for validation errors
    'status_code' => 400 // or appropriate error code
]
```

### Common Error Status Codes

- `400`: Bad Request - Invalid input or operation
- `404`: Not Found - Appointment, doctor, or patient not found
- `409`: Conflict - Time slot conflict
- `422`: Unprocessable Entity - Validation errors

### Handling Errors in Controllers

```php
$result = $this->appointmentService->scheduleAppointment($data);

if (!$result['success']) {
    if (isset($result['errors'])) {
        // Handle validation errors
        return back()->withErrors($result['errors'])->withInput();
    }
    
    // Handle other errors
    return back()->with('error', $result['message'])->withInput();
}

// Handle success
return redirect()->route('appointments.index')->with('success', $result['message']);
```

## Best Practices

1. Always validate input data before passing it to service methods
2. Check for appointment conflicts when scheduling or rescheduling
3. Use proper status transitions
4. Include appropriate relationships when retrieving appointments
5. Use the available query scopes for filtering
6. Handle success and error responses consistently
7. Implement proper authorization checks before allowing operations

