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
    participant NotificationSystem as Notification System
    participant Database
    participant PatientUser as Patient User
    
    Doctor-->>+DoctorController: Access appointments dashboard
    DoctorController-->>+AuthSystem: validateDoctorAuth()
    AuthSystem-->>-DoctorController: Doctor authentication confirmed
    
    DoctorController-->>+AppointmentModel: getAppointmentsByDoctor(doctorId, filters)
    AppointmentModel-->>+Database: Query appointments with filters
    Note over AppointmentModel,Database: Filters: status, date range,<br/>patient name, payment status
    Database-->>-AppointmentModel: Return filtered appointments
    AppointmentModel-->>-DoctorController: Appointments list with patient data
    
    DoctorController-->>Doctor: Display appointments dashboard
    Note over Doctor,DoctorController: Shows: scheduled, completed,<br/>cancelled appointments with filters
    
    alt View Appointment Details
        Doctor-->>DoctorController: Select appointment for details
        DoctorController-->>+AppointmentModel: getAppointmentDetails(appointmentId)
        AppointmentModel-->>+Database: Query appointment with relationships
        Database-->>-AppointmentModel: Return appointment with patient/payment data
        AppointmentModel-->>-DoctorController: Complete appointment details
        DoctorController-->>Doctor: Display appointment details modal
        Note over Doctor,DoctorController: Shows: patient info, time,<br/>fees, payment status, notes
    end
    
    alt Complete Appointment Flow
        Doctor-->>DoctorController: Complete appointment action
        DoctorController-->>+AppointmentModel: validateAppointmentOwnership(appointmentId, doctorId)
        AppointmentModel-->>-DoctorController: Ownership validation result
        
        alt Ownership Valid
            DoctorController-->>+AppointmentModel: validateAppointmentTiming(scheduledAt)
            AppointmentModel-->>AppointmentModel: checkIfTimeHasPassed()
            AppointmentModel-->>-DoctorController: Timing validation result
            
            alt Time Valid (appointment time has passed)
                DoctorController-->>+AppointmentModel: updateAppointmentStatus(appointmentId, 'completed')
                AppointmentModel-->>+Database: Update appointment status
                Database-->>-AppointmentModel: Confirm status update
                AppointmentModel-->>-DoctorController: Update successful
                
                DoctorController-->>+NotificationSystem: sendCompletionNotification(appointment)
                NotificationSystem-->>+PatientUser: AppointmentCompletedNotification
                Note over NotificationSystem,PatientUser: Email & Database notification:<br/>appointment completed
                PatientUser-->>-NotificationSystem: Notification delivered
                NotificationSystem-->>-DoctorController: Notification sent
                
                DoctorController-->>Doctor: Display success message
            else Time Invalid (appointment time not yet reached)
                DoctorController-->>Doctor: Display timing error message
            end
        else Ownership Invalid
            DoctorController-->>Doctor: Display access denied error
        end
    end
    
    alt Cancel Appointment Flow
        Doctor-->>DoctorController: Cancel appointment action
        DoctorController-->>+AppointmentModel: validateAppointmentOwnership(appointmentId, doctorId)
        AppointmentModel-->>-DoctorController: Ownership validation result
        
        alt Ownership Valid
            DoctorController-->>+AppointmentModel: updateAppointmentStatus(appointmentId, 'cancelled')
            AppointmentModel-->>+Database: Update appointment status
            Database-->>-AppointmentModel: Confirm status update
            AppointmentModel-->>-DoctorController: Update successful
            
            DoctorController-->>+NotificationSystem: sendCancellationNotification(appointment)
            NotificationSystem-->>+PatientUser: AppointmentCancelledNotification
            Note over NotificationSystem,PatientUser: Email & Database notification:<br/>appointment cancelled
            PatientUser-->>-NotificationSystem: Notification delivered
            NotificationSystem-->>-DoctorController: Notification sent
            
            DoctorController-->>Doctor: Display cancellation success message
        else Ownership Invalid
            DoctorController-->>Doctor: Display access denied error
        end
    end
    
    alt Filter Appointments
        Doctor-->>DoctorController: Apply appointment filters
        Note over Doctor,DoctorController: Filter options: status, date range,<br/>patient name, appointment ID
        DoctorController-->>+AppointmentModel: getFilteredAppointments(doctorId, filterCriteria)
        AppointmentModel-->>+Database: Query with applied filters
        Database-->>-AppointmentModel: Return filtered results
        AppointmentModel-->>-DoctorController: Filtered appointments list
        DoctorController-->>Doctor: Display filtered results
    end
    
    alt Check Payment Status
        Doctor-->>DoctorController: View payment information
        DoctorController-->>+PaymentModel: getPaymentStatus(appointmentId)
        PaymentModel-->>+Database: Query payment records
        Database-->>-PaymentModel: Return payment data
        PaymentModel-->>-DoctorController: Payment status (paid/unpaid)
        DoctorController-->>Doctor: Display payment information
        Note over Doctor,DoctorController: Shows: payment method,<br/>transaction ID, payment status
    end
    
    Doctor-->>DoctorController: Refresh appointments dashboard
    DoctorController-->>+AppointmentModel: getCurrentAppointments(doctorId)
    AppointmentModel-->>+Database: Query latest appointment data
    Database-->>-AppointmentModel: Return updated appointments
    AppointmentModel-->>-DoctorController: Updated appointments list
    DoctorController-->>Doctor: Display refreshed dashboard
    DoctorController-->>-Doctor: Complete appointment management session
    
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
1. **`Doctor-->>+DoctorController`** : الطبيب يرسل طلب للكنترولر ويبدأ تفعيله
2. **`DoctorController-->>-Doctor`** : الكنترولر يرد على الطبيب وينهي التفعيل
3. **`alt View Appointment Details`** : إذا اختار الطبيب عرض تفاصيل الحجز
4. **`Note over Doctor,DoctorController`** : ملاحظة توضيحية تشمل عدة مكونات

## Diagram Explanation

This sequence diagram illustrates the doctor appointment management workflow in our clinic system, covering all major appointment operations:

### Key Components:
- **Doctor**: The healthcare professional managing appointments
- **DoctorController**: Main controller handling doctor operations (`Modules\Doctors\Http\Controllers\DoctorsController`)
- **Authentication System**: Validates doctor access and permissions
- **Appointment Model**: Data model for appointment entities (`Modules\Appointments\Entities\Appointment`)
- **Patient Model**: Data model for patient information (`Modules\Patients\Entities\Patient`)
- **Payment Model**: Handles payment status and information (`Modules\Payments\Entities\Payment`)
- **Notification System**: Manages patient notifications for appointment changes
- **Database**: Persistent data storage system
- **Patient User**: The patient receiving notifications about appointment changes

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
   - Patient receives automatic notification via email and database notification
   - Success confirmation is displayed to doctor

4. **Cancel Appointment Flow**
   - Doctor initiates appointment cancellation
   - System validates appointment ownership
   - Appointment status is updated to 'cancelled'
   - Patient receives automatic cancellation notification
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
- **Notification Tracking**: System confirms successful notification delivery to patients

### Security & Validation:
- **Ownership Validation**: Ensures doctors can only manage their own appointments
- **Timing Constraints**: Prevents completing appointments before scheduled time
- **Authentication Checks**: Continuous validation of doctor permissions
- **Error Handling**: Comprehensive error messages for various failure scenarios

### Status Management:
The system handles three appointment statuses:
- **Scheduled** (قيد الانتظار): Active appointments awaiting completion
- **Completed** (مكتمل): Successfully finished appointments
- **Cancelled** (ملغي): Cancelled appointments with automatic patient notification

### Notification System:
- **AppointmentCompletedNotification**: Sent when appointment is marked complete
- **AppointmentCancelledNotification**: Sent when appointment is cancelled
- **Multi-channel Delivery**: Email and database notifications for reliable delivery

### Integration Points:
- **Payment System**: Real-time payment status from Stripe
- **Patient Management**: Access to patient information and contact details  
- **Schedule Management**: Integration with doctor availability schedules

### Note:
The system ensures data integrity through transaction management and provides comprehensive audit trails for all appointment status changes. All patient communications are automated to maintain consistent service quality. 