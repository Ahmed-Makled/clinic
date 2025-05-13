# Clinic Management System - Class Diagram

This diagram visualizes the main entities in our clinic management system and their relationships.

```mermaid
classDiagram
  class User {
    +id: int
    +name: string
    +email: string
    +phone_number: string
    +password: string
    +status: boolean
    +last_seen: datetime
    +doctor(): Doctor
    +patient(): Patient
    +isPatient(): boolean
    +isDoctor(): boolean
    +isAdmin(): boolean
  }
  
  class Doctor {
    +id: int
    +user_id: int
    +name: string
    +description: string
    +image: string
    +title: string
    +specialization: string
    +consultation_fee: decimal
    +waiting_time: int
    +experience_years: int
    +gender: enum
    +status: boolean
    +address: string
    +governorate_id: int
    +city_id: int
    +category_id: int
    +is_profile_completed: boolean
    +rating_avg: decimal
    +uploadImage(image)
    +deleteImage()
    +isProfileCompleted(): boolean
    +getAvailableSlots(date): array
  }
  
  class Patient {
    +id: int
    +user_id: int
    +date_of_birth: date
    +gender: string
    +address: string
    +medical_history: text
    +emergency_contact: string
    +blood_type: string
    +allergies: text
    +status: boolean
  }
  
  class Appointment {
    +id: int
    +doctor_id: int
    +patient_id: int
    +scheduled_at: datetime
    +notes: text
    +status: enum
    +fees: decimal
    +doctor(): Doctor
    +patient(): Patient
    +payment(): Payment
    +getStatusColorAttribute(): string
    +getStatusTextAttribute(): string
  }
  
  class Category {
    +id: int
    +name: string
    +description: string
    +slug: string
    +status: boolean
    +doctors(): Doctor[]
    +appointments(): Appointment[]
  }
  
  class DoctorSchedule {
    +id: int
    +doctor_id: int
    +day: string
    +start_time: time
    +end_time: time
    +is_active: boolean
    +getAvailableSlots(date): array
  }
  
  class DoctorRating {
    +id: int
    +doctor_id: int
    +patient_id: int
    +appointment_id: int
    +rating: int
    +comment: text
    +doctor(): Doctor
    +patient(): Patient
    +appointment(): Appointment
    +getFormattedDateAttribute(): string
  }
  
  class Payment {
    +id: int
    +appointment_id: int
    +amount: decimal
    +payment_method: string
    +transaction_id: string
    +status: string
    +appointment(): Appointment
  }
  
  class Governorate {
    +id: int
    +name: string
    +cities(): City[]
  }
  
  class City {
    +id: int
    +governorate_id: int
    +name: string
    +governorate(): Governorate
  }
  
  %% Relationships
  User "1" -- "0..1" Doctor : has
  User "1" -- "0..1" Patient : has
  Doctor "1" -- "*" Appointment : has
  Patient "1" -- "*" Appointment : has
  Doctor "1" -- "*" DoctorSchedule : has
  Doctor "*" -- "1" Category : belongs to
  Doctor "1" -- "*" DoctorRating : receives
  Patient "1" -- "*" DoctorRating : gives
  Appointment "1" -- "0..1" Payment : has
  Appointment "1" -- "*" DoctorRating : has
  Governorate "1" -- "*" City : has
  Doctor "*" -- "1" Governorate : located in
  Doctor "*" -- "1" City : located in
```

## Diagram Overview

This class diagram represents the core entities of our clinic management system and their relationships:

### Key Entities:
1. **User**: The base user account that can be either a doctor, patient, or admin
2. **Doctor**: Medical professionals that provide consultations
3. **Patient**: People who book appointments with doctors
4. **Appointment**: Scheduled sessions between doctors and patients
5. **Category**: Medical specialties that doctors belong to
6. **DoctorSchedule**: The available times when doctors can receive appointments
7. **DoctorRating**: Feedback provided by patients after appointments
8. **Payment**: Financial transactions for appointments
9. **Governorate & City**: Location-based entities for organizing doctors geographically

### Key Relationships:
- Users have either a Doctor profile, a Patient profile, or neither (for admins)
- Doctors belong to a specific Category (medical specialty)
- Doctors have multiple DoctorSchedules that define their availability
- Patients book Appointments with Doctors
- Patients can provide DoctorRatings after Appointments
- Each Appointment can have a Payment associated with it
- Doctors are located in specific Governorates and Cities
