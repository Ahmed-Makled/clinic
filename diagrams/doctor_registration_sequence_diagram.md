# Doctor Registration Sequence Diagram

This diagram visualizes the doctor registration and profile completion process in our clinic management system.

```mermaid
sequenceDiagram
    autonumber
    actor Doctor
    participant AuthSystem as Authentication System
    participant DoctorController
    participant UserModel
    participant DoctorModel
    participant CategoryModel
    participant AdminUser as Admin
    
    Doctor->>AuthSystem: Register account
    AuthSystem->>UserModel: createUser(name, email, password)
    UserModel-->>AuthSystem: Return user with "Doctor" role
    AuthSystem-->>Doctor: Account created successfully
    
    Doctor->>DoctorController: Provide basic information
    DoctorController->>DoctorModel: createDoctorProfile(userId, basicInfo)
    DoctorModel-->>DoctorController: Return profile (incomplete)
    DoctorController-->>Doctor: Profile initialized
    
    Doctor->>DoctorController: Upload qualification documents
    DoctorController->>DoctorModel: storeDocuments(doctorId, documents)
    
    Doctor->>DoctorController: Add specialty/category
    DoctorController->>CategoryModel: getCategories()
    CategoryModel-->>DoctorController: Return available categories
    DoctorController-->>Doctor: Display categories
    Doctor->>DoctorController: Select category
    DoctorController->>DoctorModel: setCategory(doctorId, categoryId)
    
    Doctor->>DoctorController: Set schedule & availability
    DoctorController->>DoctorModel: setSchedule(doctorId, scheduleData)
    
    Doctor->>DoctorController: Set consultation fee
    DoctorController->>DoctorModel: setConsultationFee(doctorId, fee)
    
    DoctorModel->>AdminUser: Notify of new doctor profile
    
    AdminUser->>DoctorController: Review doctor profile
    AdminUser->>DoctorController: Approve doctor profile
    DoctorController->>DoctorModel: updateStatus(doctorId, 'active')
    DoctorModel->>Doctor: Send approval notification
    
    DoctorModel-->>DoctorController: is_profile_completed = true
    DoctorController-->>Doctor: Profile activated & visible to patients
```

## Diagram Explanation

This sequence diagram illustrates the doctor onboarding workflow in our clinic system, from registration to profile activation:

### Key Steps:
1. **Account Registration**
   - Doctor creates a user account in the system
   - Authentication system assigns the "Doctor" role

2. **Profile Creation**
   - Doctor provides basic personal and professional information
   - System initializes an incomplete doctor profile

3. **Profile Completion**
   - Doctor uploads qualification documents and certifications
   - Doctor selects their medical specialty/category
   - Doctor sets their availability schedule
   - Doctor establishes their consultation fee

4. **Admin Review & Approval**
   - Admin is notified about the new doctor profile
   - Admin reviews the profile and submitted documents
   - Admin approves the doctor if qualifications are satisfactory
   - System marks the doctor profile as completed and active

5. **Activation**
   - Doctor receives approval notification
   - Doctor profile becomes visible to patients for appointment booking

### Required Information for Profile Completion:
- Basic personal details (name, contact information)
- Professional qualifications and experience
- Medical specialty/category
- Weekly schedule with available time slots
- Consultation fee
- Profile image and other supporting documents

The system tracks profile completion status to ensure all doctors have provided the necessary information before their profiles are visible to patients.
