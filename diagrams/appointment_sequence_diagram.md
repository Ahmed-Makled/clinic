# Appointment Booking Sequence Diagram

This diagram visualizes the appointment booking process in our clinic management system, showing interactions between patients, doctors, and the system components.

```mermaid
sequenceDiagram
    autonumber
    actor Patient
    participant PatientApp as Patient Portal
    participant AppController as Appointment Controller
    participant Doctor
    participant Appointment
    participant Payment
    
    Patient->>PatientApp: Search for doctors
    PatientApp->>Doctor: searchDoctors(criteria)
    Doctor-->>PatientApp: Return matching doctors
    PatientApp-->>Patient: Display doctor list
    
    Patient->>PatientApp: Select doctor
    PatientApp->>Doctor: getDoctorProfile(id)
    Doctor-->>PatientApp: Return doctor details
    PatientApp->>Doctor: getAvailableSlots(date)
    Doctor-->>PatientApp: Return available time slots
    PatientApp-->>Patient: Show availability
    
    Patient->>PatientApp: Select appointment time
    PatientApp->>AppController: createAppointment(data)
    AppController->>Appointment: create(doctorId, patientId, time)
    Appointment-->>AppController: Return appointment details
    
    AppController->>Payment: initiatePayment(appointmentId)
    Payment-->>Patient: Redirect to payment gateway
    Patient->>Payment: Complete payment
    Payment->>Appointment: updatePaymentStatus(id, 'completed')
    
    Appointment->>Doctor: Send appointment notification
    Appointment->>Patient: Send confirmation notification
    
    Note over Patient,Doctor: On appointment day
    
    Doctor->>AppController: Mark appointment as completed
    AppController->>Appointment: updateStatus(id, 'completed')
    
    Patient->>PatientApp: Submit doctor rating
    PatientApp->>Doctor: updateRating(id, rating, comment)
```

## Diagram Explanation

This sequence diagram illustrates the complete appointment booking workflow in our clinic system, from doctor search to post-appointment rating:

### Key Steps:
1. **Doctor Search & Selection**
   - Patient searches for doctors based on criteria (specialty, location, etc.)
   - System displays matching doctors
   - Patient selects a doctor to view their profile and availability

2. **Appointment Booking**
   - Patient selects a convenient date and time slot
   - System creates an appointment record
   - Patient is redirected to the payment process

3. **Payment Processing**
   - Patient completes the payment
   - System updates the appointment payment status
   - Confirmation notifications are sent to both patient and doctor

4. **Appointment Completion & Feedback**
   - Doctor marks the appointment as completed after the consultation
   - Patient provides a rating and feedback about the doctor's service

### Key Interactions:
- The Patient Portal serves as the user interface for patients
- The Appointment Controller handles the business logic
- The Doctor component manages doctor information and availability
- The Appointment component stores appointment details
- The Payment component handles the financial transaction

This workflow ensures a smooth appointment booking experience while maintaining proper record-keeping and communication between all parties involved.
