# Doctor Manage Appointments Flow Sequence Diagram

This diagram visualizes the doctor appointment management process in our clinic management system, including viewing, completing, and canceling appointments.

```mermaid
sequenceDiagram
    autonumber
    actor Doctor
    participant DoctorController
    participant AuthSystem as Authentication System
    participant AppointmentModel as Appointment Model
    participant PatientModel as Patient Model
    participant PaymentModel as Payment Model
    participant Database
    
    Doctor->>+DoctorController: Access appointments dashboard
    DoctorController->>+AuthSystem: validateDoctorAuth()
    AuthSystem-->>-DoctorController: Doctor authentication confirmed
    
    DoctorController->>+AppointmentModel: getAppointmentsByDoctor(doctorId, filters)
    AppointmentModel->>+Database: Query appointments with filters
    Database-->>-AppointmentModel: Return filtered appointments
    AppointmentModel-->>-DoctorController: Appointments list with patient data
    
    DoctorController-->>Doctor: Display appointments dashboard
    
    alt View Appointment Details
        Doctor->>DoctorController: Select appointment for details
        DoctorController->>+AppointmentModel: getAppointmentDetails(appointmentId)
        AppointmentModel->>+Database: Query appointment with relationships
        Database-->>-AppointmentModel: Return appointment with patient/payment data
        AppointmentModel-->>-DoctorController: Complete appointment details
        DoctorController-->>Doctor: Display appointment details modal
    end
    
    alt Complete Appointment Flow
        Doctor->>DoctorController: Complete appointment action
        DoctorController->>+AppointmentModel: validateAppointmentOwnership(appointmentId, doctorId)
        AppointmentModel-->>-DoctorController: Ownership validation result
        
        alt Ownership Valid
            DoctorController->>+AppointmentModel: validateAppointmentTiming(scheduledAt)
            AppointmentModel->>AppointmentModel: checkIfTimeHasPassed()
            AppointmentModel-->>-DoctorController: Timing validation result
            
            alt Time Valid (appointment time has passed)
                DoctorController->>+AppointmentModel: updateAppointmentStatus(appointmentId, 'completed')
                AppointmentModel->>+Database: Update appointment status
                Database-->>-AppointmentModel: Confirm status update
                AppointmentModel-->>-DoctorController: Update successful
                
                DoctorController-->>Doctor: Display success message
            else Time Invalid (appointment time not yet reached)
                DoctorController-->>Doctor: Display timing error message
            end
        else Ownership Invalid
            DoctorController-->>Doctor: Display access denied error
        end
    end
    
    alt Cancel Appointment Flow
        Doctor->>DoctorController: Cancel appointment action
        DoctorController->>+AppointmentModel: validateAppointmentOwnership(appointmentId, doctorId)
        AppointmentModel-->>-DoctorController: Ownership validation result
        
        alt Ownership Valid
            DoctorController->>+AppointmentModel: updateAppointmentStatus(appointmentId, 'cancelled')
            AppointmentModel->>+Database: Update appointment status
            Database-->>-AppointmentModel: Confirm status update
            AppointmentModel-->>-DoctorController: Update successful
            
            DoctorController-->>Doctor: Display cancellation success message
        else Ownership Invalid
            DoctorController-->>Doctor: Display access denied error
        end
    end
    
    alt Filter Appointments
        Doctor->>DoctorController: Apply appointment filters
        DoctorController->>+AppointmentModel: getFilteredAppointments(doctorId, filterCriteria)
        AppointmentModel->>+Database: Query with applied filters
        Database-->>-AppointmentModel: Return filtered results
        AppointmentModel-->>-DoctorController: Filtered appointments list
        DoctorController-->>Doctor: Display filtered results
    end
    
    alt Check Payment Status
        Doctor->>DoctorController: View payment information
        DoctorController->>+PaymentModel: getPaymentStatus(appointmentId)
        PaymentModel->>+Database: Query payment records
        Database-->>-PaymentModel: Return payment data
        PaymentModel-->>-DoctorController: Payment status (paid/unpaid)
        DoctorController-->>Doctor: Display payment information
    end
    
    Doctor->>DoctorController: Refresh appointments dashboard
    DoctorController->>+AppointmentModel: getCurrentAppointments(doctorId)
    AppointmentModel->>+Database: Query latest appointment data
    Database-->>-AppointmentModel: Return updated appointments
    AppointmentModel-->>-DoctorController: Updated appointments list
    DoctorController-->>Doctor: Display refreshed dashboard
    DoctorController-->>-Doctor: Complete appointment management session
    
```

## Mermaid Symbols Legend

### Arrow Types (أنواع الأسهم):
- **`->>`** : Solid arrow (سهم متصل) - للطلبات والاستدعاءات (Requests/Calls)
- **`-->>`** : Dashed arrow (سهم منقط) - للاستجابات وإرجاع النتائج (Responses/Returns)
- **`->>+`** : Solid arrow with activation (سهم متصل مع تفعيل) - بداية عملية جديدة
- **`-->>-`** : Dashed arrow with deactivation (سهم منقط مع إنهاء التفعيل) - إرجاع النتيجة وإنهاء العملية

### Control Flow (تحكم في التدفق):
- **`alt`** : Alternative (البديل) - يمثل شرط if/else
- **`else`** : Otherwise (وإلا) - الحالة البديلة في الشرط  
- **`end`** : End block (نهاية الكتلة) - إنهاء كتلة التحكم

### Activation Symbols (رموز التفعيل):
- **`+`** : Activate lifeline (تفعيل خط الحياة) - بداية معالجة في المكون
- **`-`** : Deactivate lifeline (إلغاء تفعيل خط الحياة) - انتهاء المعالجة في المكون

### Practical Examples من المخطط:
1. **`Doctor->>+DoctorController`** : الطبيب يرسل طلب للكنترولر ويبدأ تفعيله (Request)
2. **`DoctorController-->>Doctor`** : الكنترولر يرد على الطبيب (Response)
3. **`alt View Appointment Details`** : إذا اختار الطبيب عرض تفاصيل الحجز
4. **`AppointmentModel->>AppointmentModel`** : عملية داخلية في نفس المكون (self-call)
5. **`Database-->>-AppointmentModel`** : إرجاع البيانات وإنهاء التفعيل

## Diagram Explanation

This sequence diagram illustrates the doctor appointment management workflow in our clinic system, covering all major appointment operations:

### Key Components:
- **Doctor**: The healthcare professional managing appointments
- **DoctorController**: Main controller handling doctor operations (`Modules\Doctors\Http\Controllers\DoctorsController`)
- **Authentication System**: Validates doctor access and permissions
- **Appointment Model**: Data model for appointment entities (`Modules\Appointments\Entities\Appointment`)
- **Patient Model**: Data model for patient information (`Modules\Patients\Entities\Patient`)
- **Payment Model**: Handles payment status and information (`Modules\Payments\Entities\Payment`)
- **Database**: Persistent data storage system

### Key Workflows:

1. **Dashboard Access & Filtering**
   - Doctor accesses the appointments dashboard
   - System validates doctor authentication and permissions
   - Appointments are retrieved with optional filters (status, date, patient name, payment status)
   - Dashboard displays organized appointment information with filtering capabilities

2. **Appointment Details Viewing**
   - Doctor selects specific appointment for detailed view
   - System retrieves complete appointment information including patient details and payment status
   - Modal displays comprehensive appointment information

3. **Complete Appointment Flow**
   - Doctor initiates appointment completion
   - System validates appointment ownership and timing constraints
   - Only appointments whose scheduled time has passed can be completed
   - Status is updated to 'completed' in database
   - Success confirmation is displayed to doctor

4. **Cancel Appointment Flow**
   - Doctor initiates appointment cancellation
   - System validates appointment ownership
   - Appointment status is updated to 'cancelled'
   - Cancellation confirmation is displayed to doctor

5. **Payment Status Monitoring**
   - Doctor can view payment information for appointments
   - System retrieves payment status from Stripe integration
   - Payment details including method and transaction ID are displayed

6. **Real-time Dashboard Updates**
   - Dashboard can be refreshed to show latest appointment status
   - Filters can be applied for specific appointment searches
   - System maintains real-time synchronization with database

### Interactive Features:
- **Advanced Filtering**: By status, date range, patient name, appointment ID, and payment status
- **Bulk Operations**: Viewing multiple appointments with status indicators
- **Real-time Updates**: Dashboard reflects immediate changes after actions

### Security & Validation:
- **Ownership Validation**: Ensures doctors can only manage their own appointments
- **Timing Constraints**: Prevents completing appointments before scheduled time
- **Authentication Checks**: Continuous validation of doctor permissions
- **Error Handling**: Comprehensive error messages for various failure scenarios

### Status Management:
The system handles three appointment statuses:
- **Scheduled** (قيد الانتظار): Active appointments awaiting completion
- **Completed** (مكتمل): Successfully finished appointments
- **Cancelled** (ملغي): Cancelled appointments

### Integration Points:
- **Payment System**: Real-time payment status from Stripe
- **Patient Management**: Access to patient information and contact details  
- **Schedule Management**: Integration with doctor availability schedules

### Note:
The system ensures data integrity through transaction management and provides comprehensive audit trails for all appointment status changes. Patient notifications are handled by background services separate from the doctor workflow. 
