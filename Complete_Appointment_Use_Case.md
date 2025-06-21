# Complete Appointment Use Case Specification

## Use Case: Complete Appointment

### **Basic Information**
- **Use Case ID**: UC-APP-003
- **Use Case Name**: Complete Appointment
- **Version**: 1.0
- **Date**: 2025-01-28
- **Primary Actor**: Doctor
- **Secondary Actors**: Patient (receives notifications), System (automated processes)
- **Stakeholders**: Doctor, Patient, Clinic Administration

### **Brief Description**
This use case describes the process by which a doctor marks a scheduled appointment as completed after providing medical consultation to the patient. The system validates timing, updates records, sends notifications, and maintains audit trails.

---

## **Pre-Conditions**
1. Doctor must be logged into the system
2. Doctor must have valid authentication and authorization
3. Appointment must exist in the system with "scheduled" status
4. Current time must be at or after the scheduled appointment time
5. Patient must have attended the appointment (physical or virtual)

## **Post-Conditions**
**Success End Condition:**
- Appointment status changed to "completed"
- Completion timestamp recorded
- Patient receives completion notification
- Medical notes saved (if provided)
- Doctor statistics updated
- Payment status updated (if applicable)
- System audit log updated

**Failure End Condition:**
- Appointment status remains unchanged
- Error message displayed to doctor
- Failed completion attempt logged

---

## **Main Success Scenario**

### **Basic Flow:**
1. **Doctor Authentication**
   - Doctor logs into the clinic management system
   - System validates credentials and loads doctor dashboard

2. **Navigate to Appointments**
   - Doctor selects "Appointments" from the main menu
   - System displays today's appointment schedule
   - Doctor views list of scheduled appointments

3. **Select Appointment**
   - Doctor selects specific appointment to complete
   - System displays detailed appointment information:
     - Patient information and contact details
     - Scheduled date and time
     - Appointment duration
     - Current status
     - Payment information
     - Previous medical notes (if any)

4. **Validate Completion Eligibility**
   - System checks current time against appointment time
   - System verifies appointment status is "scheduled"
   - If valid, system enables completion actions

5. **Add Medical Documentation** (Optional)
   - Doctor may add medical notes including:
     - Diagnosis information
     - Treatment provided during consultation
     - Medications prescribed
     - Follow-up instructions
     - Recommendations for future care
   - System saves medical documentation

6. **Complete Appointment**
   - Doctor clicks "Complete Appointment" button
   - System prompts for confirmation
   - Doctor confirms the completion

7. **System Processing**
   - System updates appointment status to "completed"
   - System records completion timestamp
   - System generates completion notification
   - System updates doctor's performance statistics
   - System updates financial records (if payment pending)

8. **Notification and Feedback**
   - System sends completion notification to patient via:
     - Email notification
     - SMS (if configured)
     - In-app notification
   - System enables patient rating/feedback mechanism
   - System displays success message to doctor

9. **Generate Reports**
   - System generates appointment completion report
   - System updates clinic statistics
   - System maintains audit trail

---

## **Alternative Flows**

### **Alternative Flow 1: Future Appointment**
**Condition**: Doctor attempts to complete appointment before scheduled time

4a. **Time Validation Failure**
   - System detects appointment time hasn't arrived
   - System displays error message: "Cannot complete future appointment"
   - System shows remaining time until appointment
   - Doctor returned to appointment list
   - Use case ends

### **Alternative Flow 2: Already Completed Appointment**
**Condition**: Doctor selects already completed appointment

3a. **Status Check**
   - System detects appointment status is "completed"
   - System displays information message
   - System shows completion details:
     - Completion date and time
     - Medical notes provided
     - Treatment summary
   - Doctor can view but not modify
   - Use case ends

### **Alternative Flow 3: Cancelled Appointment**
**Condition**: Doctor attempts to complete cancelled appointment

4b. **Cancelled Status Handling**
   - System detects appointment status is "cancelled"
   - System displays error message
   - System suggests rescheduling options
   - Doctor can initiate rescheduling process
   - Use case ends

### **Alternative Flow 4: Technical Error**
**Condition**: System error during completion process

7a. **System Error Handling**
   - System encounters database or processing error
   - System rolls back any partial changes
   - System logs error details
   - System displays user-friendly error message
   - Doctor can retry the operation
   - Use case resumes from step 6

---

## **Exception Flows**

### **Exception Flow 1: Network Connectivity Issues**
**Trigger**: Loss of network connection during completion

**Response**:
- System queues completion request locally
- System displays "Processing..." indicator
- Upon connection restoration, system processes queued request
- System confirms completion or reports failure

### **Exception Flow 2: Concurrent Modification**
**Trigger**: Another user modifies appointment simultaneously

**Response**:
- System detects concurrent modification
- System displays conflict resolution dialog
- Doctor reviews changes and decides to:
  - Override with their changes
  - Accept other user's changes
  - Cancel their operation

---

## **Business Rules**

### **BR-1: Timing Validation**
- Appointments can only be completed at or after the scheduled time
- Buffer time of 15 minutes before scheduled time may be allowed (configurable)

### **BR-2: Status Transitions**
- Only appointments with "scheduled" status can be completed
- Once completed, appointments cannot be reverted to "scheduled"
- Cancelled appointments cannot be completed directly

### **BR-3: Medical Documentation**
- Medical notes are optional but recommended
- Medical notes must not exceed 2000 characters
- Sensitive medical information must be encrypted

### **BR-4: Notification Requirements**
- Patient must receive completion notification within 5 minutes
- Notification must include basic appointment details
- Medical details in notifications must follow privacy regulations

### **BR-5: Payment Integration**
- If payment is pending, completion triggers payment processing
- Payment failures don't prevent appointment completion
- Financial records must be updated regardless of payment status

---

## **Special Requirements**

### **Performance Requirements**
- Completion process must complete within 3 seconds
- System must handle up to 100 concurrent completions
- Database updates must be atomic and consistent

### **Security Requirements**
- All actions must be logged with user identification
- Medical notes must be encrypted at rest
- Access logs must be maintained for audit purposes

### **Usability Requirements**
- Completion action must be easily accessible
- Confirmation dialog prevents accidental completions
- Clear status indicators show completion progress

### **Reliability Requirements**
- System must ensure data consistency in case of failures
- Completion status must be persistent and recoverable
- Audit trail must be tamper-proof

---

## **Data Requirements**

### **Input Data**
- Appointment ID (required)
- Medical notes (optional, max 2000 characters)
- Completion timestamp (system-generated)
- Doctor ID (from session)

### **Output Data**
- Updated appointment record
- Completion notification content
- Updated statistics
- Audit log entries

### **Data Validations**
- Appointment must exist and be accessible to the doctor
- Medical notes must be valid text without malicious content
- Timestamp must be current server time

---

## **User Interface Requirements**

### **Appointment List View**
- Clear status indicators for each appointment
- One-click access to completion action
- Visual distinction between past, current, and future appointments

### **Appointment Details View**
- Comprehensive patient and appointment information
- Prominent "Complete" button when eligible
- Medical notes input area
- Confirmation dialogs

### **Success/Error Feedback**
- Clear success messages with appointment details
- Descriptive error messages with corrective actions
- Progress indicators during processing

---

## **Integration Requirements**

### **Notification System**
- Email service integration for patient notifications
- SMS gateway integration (optional)
- Push notification service for mobile app

### **Payment System**
- Integration with payment processing module
- Automatic payment status updates
- Financial reporting system updates

### **Audit System**
- Comprehensive logging of all completion actions
- Integration with system audit trail
- Compliance reporting capabilities

---

## **Test Scenarios**

### **Positive Test Cases**
1. Complete scheduled appointment at correct time
2. Complete appointment with medical notes
3. Complete appointment with pending payment
4. Complete multiple appointments in sequence

### **Negative Test Cases**
1. Attempt to complete future appointment
2. Attempt to complete cancelled appointment
3. Attempt to complete already completed appointment
4. Complete appointment with invalid medical notes

### **Edge Cases**
1. Complete appointment exactly at scheduled time
2. Complete appointment with maximum length medical notes
3. Complete appointment during system high load
4. Complete appointment with network interruption

---

## **Success Metrics**
- **Completion Rate**: >95% of eligible appointments completed successfully
- **Response Time**: <3 seconds for completion processing
- **User Satisfaction**: >4.5/5 rating for completion process usability
- **Error Rate**: <1% completion failures due to system errors
- **Notification Delivery**: >99% of completion notifications delivered successfully

---

## **Dependencies**
- Authentication system must be operational
- Database system must be available and responsive
- Notification services must be configured and active
- Payment processing system must be integrated (if applicable)

## **Assumptions**
- Doctors are trained on the completion process
- Network connectivity is generally reliable
- Patients expect and accept completion notifications
- Medical privacy regulations are understood and followed

---

*This use case specification ensures comprehensive coverage of the Complete Appointment functionality while maintaining system reliability, security, and user experience standards.* 