# Admin Workflow Sequence Diagram

This diagram visualizes the key administrative workflows in our clinic management system, focusing on the system administrator's perspective.

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant Dashboard as Admin Dashboard
    participant DoctorController
    participant PatientController
    participant AppointmentController
    participant PaymentController
    participant DoctorModel
    participant PatientModel
    participant AppointmentModel
    participant PaymentModel
    participant NotificationSystem
    
    Admin->>Dashboard: Login with admin credentials
    Dashboard-->>Admin: Display admin dashboard with statistics
    
    Note over Admin,Dashboard: Doctor Management
    Admin->>Dashboard: Navigate to Doctors section
    Dashboard->>DoctorController: getDoctors(filters)
    DoctorController->>DoctorModel: fetchAllDoctors(filters)
    DoctorModel-->>DoctorController: Return doctors list
    DoctorController-->>Dashboard: Return doctors data
    Dashboard-->>Admin: Display doctors list
    
    Admin->>Dashboard: Review new doctor registration
    Dashboard->>DoctorController: getDoctorProfile(id)
    DoctorController->>DoctorModel: findDoctor(id)
    DoctorModel-->>DoctorController: Return doctor details
    DoctorController-->>Dashboard: Display doctor profile
    Dashboard-->>Admin: Show doctor registration details
    
    Admin->>Dashboard: Approve doctor registration
    Dashboard->>DoctorController: approveDoctor(id)
    DoctorController->>DoctorModel: updateStatus(id, 'active')
    DoctorController->>NotificationSystem: sendApprovalNotification(doctorId)
    NotificationSystem-->>DoctorModel: Doctor notified
    DoctorModel-->>DoctorController: Update successful
    DoctorController-->>Dashboard: Return success status
    Dashboard-->>Admin: Display success message
    
    Note over Admin,Dashboard: Patient Management
    Admin->>Dashboard: Navigate to Patients section
    Dashboard->>PatientController: getPatients(filters)
    PatientController->>PatientModel: fetchAllPatients(filters)
    PatientModel-->>PatientController: Return patients list
    PatientController-->>Dashboard: Return patients data
    Dashboard-->>Admin: Display patients list
    
    Note over Admin,Dashboard: Appointment Management
    Admin->>Dashboard: Navigate to Appointments section
    Dashboard->>AppointmentController: getAppointments(filters)
    AppointmentController->>AppointmentModel: fetchAllAppointments(filters)
    AppointmentModel-->>AppointmentController: Return appointments list
    AppointmentController-->>Dashboard: Return appointments data
    Dashboard-->>Admin: Display appointments list
    
    Admin->>Dashboard: View appointment details
    Dashboard->>AppointmentController: getAppointmentDetails(id)
    AppointmentController->>AppointmentModel: findAppointment(id)
    AppointmentModel-->>AppointmentController: Return appointment details
    AppointmentController-->>Dashboard: Return appointment data
    Dashboard-->>Admin: Display appointment details
    
    Note over Admin,Dashboard: Payment & Financial Monitoring
    Admin->>Dashboard: Navigate to Payment Reports
    Dashboard->>PaymentController: getPaymentReports(dateRange)
    PaymentController->>PaymentModel: generateReports(dateRange)
    PaymentModel-->>PaymentController: Return payment statistics
    PaymentController-->>Dashboard: Return financial data
    Dashboard-->>Admin: Display financial reports
```

## Diagram Explanation

This sequence diagram illustrates the key administrative workflows in our clinic system, focusing on the system administrator's responsibilities:

### Key Admin Workflows:

1. **Dashboard Overview**
   - Admin logs in and views system statistics
   - Dashboard provides a comprehensive view of clinic operations

2. **Doctor Management**
   - Viewing and filtering the list of doctors
   - Reviewing new doctor registrations
   - Approving doctor profiles
   - Managing doctor verification and status

3. **Patient Management**
   - Accessing and filtering patient records
   - Overseeing patient information
   - Monitoring patient activities

4. **Appointment Management**
   - Viewing all appointments across the clinic
   - Accessing detailed information about specific appointments
   - Monitoring appointment statuses (scheduled, completed, cancelled)

5. **Financial Monitoring**
   - Accessing payment reports and financial statistics
   - Viewing transaction history
   - Generating financial reports for specific date ranges

### Administrative Capabilities:
- User management (doctors, patients, staff)
- Content management (medical specialties, services)
- System configuration and settings
- Notification management
- Role and permission management
- Reporting and analytics

This workflow ensures proper oversight of the clinic operations, maintaining high-quality healthcare service delivery while ensuring proper record-keeping and administration.
