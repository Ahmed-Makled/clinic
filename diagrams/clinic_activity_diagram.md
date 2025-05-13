# Clinic Management System - Activity Diagram

This diagram visualizes the main workflows and activities in our clinic management system, focusing on the three primary user roles: Patient, Doctor, and Admin.

```mermaid
flowchart TD
    Start([Start]) --> PatientRegistration
    
    subgraph PatientFlow[Patient Flow]
        PatientRegistration[Patient Registration] --> PatientLogin[Patient Login]
        PatientLogin --> SearchDoctors[Search for Doctors]
        SearchDoctors --> FilterDoctors{Filter by Specialty/Location?}
        FilterDoctors -- Yes --> ApplyFilters[Apply Filters]
        ApplyFilters --> ViewDoctors[View Doctor Profiles]
        FilterDoctors -- No --> ViewDoctors
        ViewDoctors --> SelectDoctor[Select Doctor]
        SelectDoctor --> CheckAvailability[Check Availability]
        CheckAvailability --> SlotAvailable{Slot Available?}
        SlotAvailable -- No --> AlternateSlot[Choose Different Slot]
        AlternateSlot --> CheckAvailability
        SlotAvailable -- Yes --> BookAppointment[Book Appointment]
        BookAppointment --> ProcessPayment[Process Payment]
        ProcessPayment --> PaymentSuccessful{Payment Successful?}
        PaymentSuccessful -- No --> RetryPayment[Retry Payment]
        RetryPayment --> ProcessPayment
        PaymentSuccessful -- Yes --> AppointmentConfirmed[Appointment Confirmed]
        AppointmentConfirmed --> AttendAppointment[Attend Appointment]
        AttendAppointment --> ProvideFeedback[Provide Feedback & Rating]
    end
    
    subgraph DoctorFlow[Doctor Flow]
        DoctorRegistration[Doctor Registration] --> ProfileCreation[Create Profile]
        ProfileCreation --> UploadDocuments[Upload Qualifications]
        UploadDocuments --> SetSpecialty[Set Medical Specialty]
        SetSpecialty --> SetSchedule[Set Available Schedule]
        SetSchedule --> SetFees[Set Consultation Fees]
        SetFees --> AdminApproval{Admin Approval}
        AdminApproval -- Rejected --> UpdateProfile[Update Profile]
        UpdateProfile --> AdminApproval
        AdminApproval -- Approved --> ProfileActivated[Profile Activated]
        ProfileActivated --> ViewAppointments[View Scheduled Appointments]
        ViewAppointments --> AcceptAppointment[Accept Appointments]
        AcceptAppointment --> ConductAppointment[Conduct Appointment]
        ConductAppointment --> MarkComplete[Mark as Completed]
    end
    
    subgraph AdminFlow[Admin Flow]
        AdminLogin[Admin Login] --> Dashboard[View Dashboard]
        Dashboard --> ManageDoctors[Manage Doctors]
        Dashboard --> ManagePatients[Manage Patients]
        Dashboard --> ManageAppointments[Manage Appointments]
        Dashboard --> ViewReports[View Reports]
        
        ManageDoctors --> ReviewDoctors[Review Doctor Registrations]
        ReviewDoctors --> ApproveDoctor{Approve?}
        ApproveDoctor -- Yes --> DoctorApproved[Doctor Approved]
        ApproveDoctor -- No --> RejectDoctor[Reject with Reason]
        
        ManagePatients --> ViewPatientsList[View Patients List]
        ViewPatientsList --> PatientDetails[View Patient Details]
        
        ManageAppointments --> ViewAllAppointments[View All Appointments]
        ViewAllAppointments --> AppointmentDetails[View Appointment Details]
        AppointmentDetails --> ModifyAppointment[Modify if Needed]
        
        ViewReports --> FinancialReports[Financial Reports]
        ViewReports --> DoctorPerformance[Doctor Performance]
        ViewReports --> AppointmentStats[Appointment Statistics]
    end
    
    Start --> DoctorRegistration
    Start --> AdminLogin
    
    %% Connecting flows
    AppointmentConfirmed -.-> ViewAppointments
    DoctorApproved -.-> ProfileActivated
    
    %% Styling
    classDef start fill:#6ADA6A,stroke:#033303,stroke-width:2px,color:#033303
    classDef patient fill:#96D6FF,stroke:#0356A8,stroke-width:1px
    classDef doctor fill:#FFB677,stroke:#A84100,stroke-width:1px
    classDef admin fill:#F996FF,stroke:#61045F,stroke-width:1px
    classDef decision fill:#FFE066,stroke:#827700,stroke-width:1px
    
    class Start start
    class PatientRegistration,PatientLogin,SearchDoctors,ApplyFilters,ViewDoctors,SelectDoctor,CheckAvailability,BookAppointment,ProcessPayment,AppointmentConfirmed,AttendAppointment,ProvideFeedback,AlternateSlot,RetryPayment patient
    class DoctorRegistration,ProfileCreation,UploadDocuments,SetSpecialty,SetSchedule,SetFees,ProfileActivated,ViewAppointments,AcceptAppointment,ConductAppointment,MarkComplete,UpdateProfile doctor
    class AdminLogin,Dashboard,ManageDoctors,ManagePatients,ManageAppointments,ViewReports,ReviewDoctors,DoctorApproved,RejectDoctor,ViewPatientsList,PatientDetails,ViewAllAppointments,AppointmentDetails,ModifyAppointment,FinancialReports,DoctorPerformance,AppointmentStats admin
    class FilterDoctors,SlotAvailable,PaymentSuccessful,AdminApproval,ApproveDoctor decision
```

## Diagram Explanation

This activity diagram illustrates the key workflows across all user roles in our clinic management system:

### Patient Flow:
1. **Registration & Authentication**
   - Patients register an account and then login to the system
   - Authentication provides secure access to patient services

2. **Doctor Discovery**
   - Patients can search for doctors based on various criteria
   - Filtering options available for specialty, location, experience, etc.
   - Detailed doctor profiles help patients make informed choices

3. **Appointment Booking**
   - Patients check doctor availability
   - Select a suitable time slot
   - Book appointment and complete payment
   - Receive confirmation of successful booking

4. **Post-Appointment**
   - Attend appointment with the doctor
   - Provide feedback and rate the doctor's service

### Doctor Flow:
1. **Registration & Profile Setup**
   - Create account with professional details
   - Upload qualification documents
   - Set specialty, schedule, and consultation fees

2. **Approval Process**
   - Profile undergoes admin review
   - Rejected profiles can be updated and resubmitted
   - Approved profiles become active and visible to patients

3. **Appointment Management**
   - View scheduled appointments
   - Accept appointment requests 
   - Conduct consultations
   - Mark appointments as completed

### Admin Flow:
1. **Dashboard & Overview**
   - Access to system dashboard with statistics
   - Manage all aspects of the clinic system

2. **User Management**
   - Review and approve doctor registrations
   - Manage patient accounts
   - Handle user-related issues

3. **Appointment Oversight**
   - View all appointments across the system
   - Access detailed information about any appointment
   - Modify appointments if necessary

4. **Reporting & Analytics**
   - Generate financial reports
   - Monitor doctor performance metrics
   - Track appointment statistics

### Connecting Workflows:
- Patient appointment bookings appear in the doctor's appointment list
- Admin approval of doctor profiles enables doctors to start receiving appointments
- System notifications connect the activities between different user roles

This diagram represents the main activities and decision points in our clinic management system, showing how the different user roles interact with the system and with each other.
