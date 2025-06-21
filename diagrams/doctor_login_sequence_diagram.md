# Doctor Login Flow Sequence Diagram

This diagram visualizes the doctor login and profile verification process in our clinic management system.

```mermaid
sequenceDiagram
    autonumber
    actor Doctor
    participant AuthSystem as Authentication System
    participant LoginController
    participant DoctorController
    participant User as User Model
    participant DoctorModel as Doctor Model
    participant DoctorSchedule
    participant CategoryModel as Category Model
    participant Database
    participant AdminUser as Admin
    
    Doctor-->>+AuthSystem: Visit login page
    AuthSystem-->>-Doctor: Display login form
    
    Doctor-->>+AuthSystem: Submit login credentials
    AuthSystem-->>+LoginController: login(email, password)
    
    LoginController-->>+User: verifyCredentials(email, password)
    User-->>+Database: Query user credentials
    Database-->>-User: Return user data
    User-->>-LoginController: Authentication result
    
    alt Authentication Failed
        LoginController-->>AuthSystem: Return authentication errors
        AuthSystem-->>Doctor: Display login errors
    else Authentication Successful
        LoginController-->>+User: checkUserRole()
        User-->>-LoginController: Return role ('Doctor')
        
        LoginController-->>+DoctorModel: getDoctorProfile(userId)
        DoctorModel-->>+Database: Query doctor profile
        Database-->>-DoctorModel: Return doctor data
        DoctorModel-->>-LoginController: Doctor profile data
        
        LoginController-->>+DoctorModel: isProfileCompleted()
        DoctorModel-->>DoctorModel: checkBasicInfo(title, experience, address)
        DoctorModel-->>DoctorModel: checkCategoryAssignment()
        DoctorModel-->>+DoctorSchedule: hasActiveSchedule()
        DoctorSchedule-->>+Database: Query doctor schedules
        Database-->>-DoctorSchedule: Return schedule data
        DoctorSchedule-->>-DoctorModel: Schedule availability status
        DoctorModel-->>-LoginController: Profile completion status
        
        alt Profile Incomplete
            LoginController-->>+DoctorModel: getIncompleteProfileNotification()
            DoctorModel-->>-LoginController: Missing profile data list
            LoginController-->>AuthSystem: Redirect to profile completion
            AuthSystem-->>Doctor: Display profile completion form
            
            Doctor-->>+DoctorController: Access profile completion
            DoctorController-->>+CategoryModel: getAvailableCategories()
            CategoryModel-->>+Database: Query medical categories
            Database-->>-CategoryModel: Return categories list
            CategoryModel-->>-DoctorController: Categories data
            DoctorController-->>-Doctor: Display profile form with categories
            
            Doctor-->>+DoctorController: Submit professional details
            Note over Doctor,DoctorController: Professional info: title, specialty,<br/>experience years, consultation fee,<br/>address, working schedule
            
            DoctorController-->>+DoctorModel: updateProfile(doctorId, professionalData)
            DoctorModel-->>DoctorModel: validateProfessionalData()
            DoctorModel-->>+DoctorSchedule: updateSchedule(scheduleData)
            DoctorSchedule-->>+Database: Update doctor schedules
            Database-->>-DoctorSchedule: Confirm schedule update
            DoctorSchedule-->>-DoctorModel: Schedule update confirmation
            DoctorModel-->>+Database: Update doctor profile
            Database-->>-DoctorModel: Confirm profile update
            DoctorModel-->>DoctorModel: setProfileCompleted(true)
            DoctorModel-->>-DoctorController: Profile completion successful
            
            DoctorController-->>+AdminUser: notifyNewDoctorProfile(doctorData)
            AdminUser-->>-DoctorController: Notification sent
            DoctorController-->>-Doctor: Profile completed successfully
            
        else Profile Complete
            LoginController-->>AuthSystem: Redirect to doctor dashboard
            AuthSystem-->>Doctor: Display doctor dashboard
        end
    end
    LoginController-->>-AuthSystem: Complete login process
    
    Doctor-->>+DoctorController: Access appointments management
    DoctorController-->>+DoctorModel: getScheduledAppointments(doctorId)
    DoctorModel-->>+Database: Query doctor appointments
    Database-->>-DoctorModel: Return appointments data
    DoctorModel-->>-DoctorController: Appointments list
    DoctorController-->>-Doctor: Display appointments interface
    
    Doctor-->>+DoctorController: Update schedule availability
    DoctorController-->>+DoctorSchedule: modifySchedule(doctorId, newSchedule)
    DoctorSchedule-->>+Database: Update schedule data
    Database-->>-DoctorSchedule: Confirm schedule changes
    DoctorSchedule-->>-DoctorController: Schedule updated
    DoctorController-->>-Doctor: Schedule modification successful
    
```

## Mermaid Symbols Legend

### Arrow Types (أنواع الأسهم):
- **`-->>`** : Dashed arrow (سهم منقط) - للرسائل غير المتزامنة أو المعلوماتية
- **`->>`** : Solid arrow (سهم متصل) - للرسائل المتزامنة أو الطلبات المباشرة
- **`-->>-`** : Dashed arrow with deactivation (سهم منقط مع إنهاء التفعيل) - إرجاع النتيجة وإنهاء العملية
- **`->>+`** : Solid arrow with activation (سهم متصل مع تفعيل) - بداية عملية جديدة

### Control Flow (تحكم في التدفق):
- **`alt`** : Alternative (البديل) - يمثل شرط if/else
- **`else`** : Otherwise (وإلا) - الحالة البديلة في الشرط
- **`end`** : End block (نهاية الكتلة) - إنهاء كتلة التحكم
- **`Note over`** : Note (ملاحظة) - لإضافة معلومات توضيحية

### Activation Symbols (رموز التفعيل):
- **`+`** : Activate lifeline (تفعيل خط الحياة) - بداية معالجة في المكون
- **`-`** : Deactivate lifeline (إلغاء تفعيل خط الحياة) - انتهاء المعالجة في المكون

### Practical Examples من المخطط:
1. **`Doctor-->>+AuthSystem`** : الطبيب يرسل طلب للنظام ويبدأ تفعيله
2. **`AuthSystem-->>-Doctor`** : النظام يرد على الطبيب وينهي التفعيل
3. **`alt Authentication Failed`** : إذا فشل تسجيل الدخول
4. **`else Authentication Successful`** : وإلا إذا نجح تسجيل الدخول
5. **`DoctorModel-->>DoctorModel`** : عملية داخلية في المكون نفسه (self-call)

## Diagram Explanation

This sequence diagram illustrates the doctor login workflow in our clinic system, from authentication to profile verification and dashboard access:

### Key Components:
- **Doctor**: The healthcare professional logging into the system
- **Authentication System**: Handles user authentication and session management
- **LoginController**: Manages user login operations (`Modules\Auth\Http\Controllers\LoginController`)
- **DoctorController**: Handles doctor-specific functionality (`Modules\Doctors\Http\Controllers\DoctorsController`)
- **User Model**: Data model for user entities (`Modules\Users\Entities\User`)
- **Doctor Model**: Data model for doctor entities (`Modules\Doctors\Entities\Doctor`)
- **DoctorSchedule**: Manages doctor availability schedules (`Modules\Doctors\Entities\DoctorSchedule`)
- **Category Model**: Handles medical specialties (`Modules\Specialties\Entities\Category`)
- **Database**: Persistent data storage system
- **Admin**: System administrator for profile approval notifications

### Key Steps:

1. **Initial Authentication**
   - Doctor visits the login page and receives the login form
   - Doctor submits credentials (email and password)
   - LoginController validates credentials against database records

2. **Role Verification & Profile Check**
   - System confirms the user has 'Doctor' role
   - System retrieves doctor profile information from database
   - DoctorModel checks profile completion status by verifying:
     - Basic professional information (title, experience, address)
     - Medical specialty/category assignment
     - Active working schedule availability

3. **Profile Completion Flow (if incomplete)**
   - System redirects to profile completion interface
   - Doctor provides missing professional details:
     - Medical title (استشاري، أخصائي، طبيب)
     - Medical specialty from available categories
     - Years of experience
     - Consultation fee
     - Practice address and location
     - Weekly availability schedule
   - System validates and saves professional data
   - Admin receives notification of new doctor profile
   - Profile is marked as completed

4. **Dashboard Access (if profile complete)**
   - Doctor is redirected to personalized dashboard
   - System displays upcoming appointments and schedule
   - Doctor can access appointment management features

5. **Ongoing Operations**
   - Doctor can view and manage scheduled appointments
   - Doctor can modify availability schedule as needed
   - System maintains real-time schedule synchronization

### Interactive Lifelines:
The diagram uses activation boxes (the vertical bars) to show when each component is actively processing requests, providing a clear visual representation of the system's interaction flow and component lifecycle during the doctor login process.

### Required Information for Profile Completion:
- **Professional Details**: Medical title, specialty category, years of experience
- **Practice Information**: Consultation fees, average waiting time, practice address
- **Location Data**: Governorate and city selection
- **Schedule Management**: Weekly availability with specific time slots
- **Contact Information**: Phone number and professional description
- **Optional Data**: Profile image, medical degree information

### Key Differences from Patient Flow:
- **Role-Based Redirection**: Doctors require professional profile completion
- **Admin Notifications**: New doctor profiles trigger admin review notifications
- **Schedule Management**: Doctors must set up availability schedules
- **Professional Verification**: Additional validation for medical credentials
- **Specialty Assignment**: Doctors must be assigned to medical categories

### Note:
The system automatically checks profile completion status on each login to ensure doctors maintain up-to-date professional information. Incomplete profiles are redirected to completion forms to maintain system data integrity and patient trust. 