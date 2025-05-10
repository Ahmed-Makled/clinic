# Doctors Module

## Overview
The Doctors module manages all doctor-related functionality in the clinic application, including profile management, specialization, scheduling, and doctor-specific operations.

## Features
- Doctor profile management
- Specialization and categorization
- Rating and feedback system
- Scheduling and availability
- Integration with appointments system

## Directory Structure
```
Doctors/
  ├── Database/         - Migrations and seeders
  ├── Entities/         - Model definitions
  ├── Http/             - Controllers and requests
  ├── Notifications/    - Doctor-related notifications
  ├── Providers/        - Service providers
  ├── Resources/        - Views and assets
  ├── Routes/           - Web routes
  └── Services/         - Business logic services
```

## Available Notifications

### NewDoctorNotification
Sent when a new doctor is registered in the system.

**Usage:**
```php
use Modules\Doctors\Notifications\NewDoctorNotification;

// Send notification to admin
$admin->notify(new NewDoctorNotification($doctor));
```

### DoctorUpdatedNotification
Sent when a doctor's profile is updated.

### DoctorDeletedNotification
Sent when a doctor is removed from the system.

### IncompleteProfileNotification
Sent to doctors when their profile is missing required information.

## Routes
- `GET /doctors` - List all doctors
- `GET /doctors/create` - Show doctor creation form
- `POST /doctors` - Store new doctor
- `GET /doctors/{id}` - View doctor profile
- `GET /doctors/{id}/edit` - Edit doctor profile form
- `PUT /doctors/{id}` - Update doctor profile
- `DELETE /doctors/{id}` - Remove doctor
- `GET /doctors/{id}/schedule` - View doctor's schedule
- `POST /doctors/{id}/schedule` - Update doctor's schedule

## Models
### Doctor
Main entity representing a healthcare provider.

**Relationships:**
- `user()` - Belongs to User
- `categories()` - Many-to-many with Categories
- `appointments()` - Has many Appointments
- `governorate()` - Belongs to Governorate
- `city()` - Belongs to City

**Attributes:**
- `id` - Primary key
- `user_id` - Foreign key to users table
- `first_name` - Doctor's first name
- `last_name` - Doctor's last name
- `image` - Profile image path
- `description` - Detailed doctor bio
- `degree` - Academic degree
- `title` - Professional title
- `specialization` - Medical specialization
- `address` - Office address
- `governorate_id` - Location governorate
- `city_id` - Location city
- `consultation_fee` - Fee for consultations
- `experience_years` - Years of professional experience
- `gender` - Doctor's gender
- `status` - Account status (active/inactive)
- `waiting_time` - Average wait time in minutes
- `rating_avg` - Average rating
- `is_profile_completed` - Profile completion status
