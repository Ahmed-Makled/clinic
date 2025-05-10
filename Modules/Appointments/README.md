# Appointments Module

## Overview
The Appointments module handles all appointment-related functionality in the clinic application, including creating, managing, and tracking appointments between doctors and patients.

## Features
- Appointment scheduling
- Status management (scheduled, completed, cancelled)
- Notifications for appointment events
- Reporting and analytics

## Directory Structure
```
Appointments/
  ├── Database/         - Migrations and seeders
  ├── Entities/         - Model definitions
  ├── Http/             - Controllers and requests
  ├── Notifications/    - Appointment-related notifications
  ├── Providers/        - Service providers
  ├── Resources/        - Views and assets
  ├── Routes/           - Web routes
  └── Services/         - Business logic services
```

## Available Notifications

### NewAppointmentNotification
Sent when a new appointment is created.

**Usage:**
```php
use Modules\Appointments\Notifications\NewAppointmentNotification;

// Send notification to admin
$admin->notify(new NewAppointmentNotification($appointment));
```

### AppointmentCancelledNotification
Sent when an appointment is cancelled.

### AppointmentCompletedNotification
Sent when an appointment is marked as completed.

## Routes
- `GET /appointments` - List all appointments
- `GET /appointments/create` - Show appointment creation form
- `POST /appointments` - Store new appointment
- `GET /appointments/{id}` - View appointment details
- `GET /appointments/{id}/edit` - Edit appointment form
- `PUT /appointments/{id}` - Update appointment
- `DELETE /appointments/{id}` - Cancel appointment

## Models
### Appointment
Main entity representing a scheduled appointment between a doctor and a patient.

**Relationships:**
- `doctor()` - Belongs to Doctor
- `patient()` - Belongs to Patient

**Attributes:**
- `id` - Primary key
- `doctor_id` - Foreign key to doctors table
- `patient_id` - Foreign key to patients table
- `scheduled_at` - DateTime of appointment
- `status` - Enum ('scheduled', 'completed', 'cancelled')
- `notes` - Text notes about the appointment
- `created_at` - Timestamp of creation
- `updated_at` - Timestamp of last update
