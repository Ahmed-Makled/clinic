# Patients Module

## Overview
The Patients module manages all patient-related functionality in the clinic application, including patient profiles, medical history, and patient-specific operations.

## Features
- Patient profile management
- Medical history tracking
- Integration with appointments system
- Patient notifications

## Directory Structure
```
Patients/
  ├── Database/         - Migrations and seeders
  ├── Entities/         - Model definitions
  ├── Http/             - Controllers and requests
  ├── Notifications/    - Patient-related notifications
  ├── Providers/        - Service providers
  ├── Resources/        - Views and assets
  ├── Routes/           - Web routes
  └── Services/         - Business logic services
```

## Available Notifications

### NewPatientNotification
Sent when a new patient is registered in the system.

**Usage:**
```php
use Modules\Patients\Notifications\NewPatientNotification;

// Send notification to admin
$admin->notify(new NewPatientNotification($patient));
```

### PatientUpdatedNotification
Sent when a patient's profile is updated.

### PatientDeletedNotification
Sent when a patient is removed from the system.

## Routes
- `GET /patients` - List all patients
- `GET /patients/create` - Show patient creation form
- `POST /patients` - Store new patient
- `GET /patients/{id}` - View patient profile
- `GET /patients/{id}/edit` - Edit patient profile form
- `PUT /patients/{id}` - Update patient profile
- `DELETE /patients/{id}` - Remove patient
- `GET /patients/{id}/appointments` - View patient's appointments

## Models
### Patient
Main entity representing a patient in the clinic.

**Relationships:**
- `user()` - Belongs to User
- `appointments()` - Has many Appointments

**Attributes:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `date_of_birth` - Patient's date of birth
- `gender` - Patient's gender
- `phone_number` - Contact phone number
- `address` - Patient's address
- `blood_type` - Blood type
- `medical_history` - Notes on medical history
- `created_at` - Timestamp of creation
- `updated_at` - Timestamp of last update