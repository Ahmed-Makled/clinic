# Patient Payment Flow Sequence Diagram

This diagram visualizes the patient payment process for appointments in our clinic management system.

```mermaid
sequenceDiagram
    autonumber
    actor Patient
    participant PaymentPage as Payment Page
    participant PaymentController
    participant Appointment as Appointment Model
    participant Payment as Payment Model
    participant StripeAPI as Stripe API
    participant Database
    participant NotificationSystem as Notification System
    
    Patient-->>+PaymentPage: Access payment checkout page
    PaymentPage-->>+PaymentController: checkout(appointmentId)
    PaymentController-->>+Appointment: getAppointmentDetails(appointmentId)
    Appointment-->>+Database: Query appointment with doctor
    Database-->>-Appointment: Return appointment data
    Appointment-->>-PaymentController: Appointment details with fees
    PaymentController-->>-PaymentPage: Payment form with appointment info
    PaymentPage-->>-Patient: Display payment checkout form
    
    Patient-->>+PaymentPage: Initiate payment process
    PaymentPage-->>+PaymentController: createSession(appointmentId)
    PaymentController-->>+StripeAPI: createCheckoutSession(paymentData)
    
    alt Stripe Session Creation Failed
        StripeAPI-->>-PaymentController: API Error
        PaymentController-->>-PaymentPage: Payment session failed
        PaymentPage-->>Patient: Error message - try again
    else Stripe Session Created Successfully
        StripeAPI-->>-PaymentController: Checkout session URL
        PaymentController-->>-PaymentPage: Redirect to Stripe
        PaymentPage-->>Patient: Redirect to Stripe checkout
        
        Patient-->>+StripeAPI: Complete payment on Stripe
        StripeAPI-->>StripeAPI: Process payment
        
        alt Payment Successful
            StripeAPI-->>-Patient: Payment success - redirect to success URL
            Patient-->>+PaymentPage: Return to success page
            PaymentPage-->>+PaymentController: success(sessionId)
            
            PaymentController-->>+StripeAPI: retrieveSession(sessionId)
            StripeAPI-->>-PaymentController: Session details with metadata
            
            PaymentController-->>+Appointment: findAppointment(appointmentId)
            Appointment-->>+Database: Query appointment
            Database-->>-Appointment: Return appointment data
            Appointment-->>-PaymentController: Appointment details
            
            PaymentController-->>+Payment: create(paymentData)
            Payment-->>Payment: generateTransactionId()
            Payment-->>Payment: setStatus('completed')
            Payment-->>+Database: Insert payment record
            Database-->>-Payment: Payment record created
            Payment-->>-PaymentController: Payment confirmation
            
            PaymentController-->>+NotificationSystem: sendPaymentConfirmation(appointment)
            NotificationSystem-->>+Database: Store payment notification
            Database-->>-NotificationSystem: Notification stored
            NotificationSystem-->>-PaymentController: Notification sent
            
            PaymentController-->>-PaymentPage: Payment completed successfully
            PaymentPage-->>-Patient: Payment success confirmation
            
        else Payment Failed
            StripeAPI-->>-Patient: Payment failed - redirect to cancel URL
            Patient-->>+PaymentPage: Return to cancel page
            PaymentPage-->>+PaymentController: cancel(appointmentId)
            
            PaymentController-->>+Payment: create(failedPaymentData)
            Payment-->>Payment: setStatus('failed')
            Payment-->>+Database: Insert failed payment record
            Database-->>-Payment: Failed payment recorded
            Payment-->>-PaymentController: Failure recorded
            
            PaymentController-->>-PaymentPage: Payment cancelled/failed
            PaymentPage-->>-Patient: Payment failure message with retry option
        end
    end
    
    Patient-->>+PaymentPage: Create appointment with immediate payment
    PaymentPage-->>+PaymentController: createAppointmentAndCheckout(appointmentData)
    PaymentController-->>+Appointment: create(appointmentData)
    Appointment-->>Appointment: setFees(doctor.consultation_fee)
    Appointment-->>Appointment: setStatus('scheduled')
    Appointment-->>+Database: Insert new appointment
    Database-->>-Appointment: Appointment created
    Appointment-->>-PaymentController: New appointment details
    
    PaymentController-->>+StripeAPI: createCheckoutSession(appointmentPayment)
    StripeAPI-->>-PaymentController: Checkout session URL
    PaymentController-->>-PaymentPage: Redirect to Stripe payment
    PaymentPage-->>Patient: Redirect to Stripe for payment
    
    Patient-->>+PaymentPage: View payment history
    PaymentPage-->>+PaymentController: getPaymentHistory(patientId)
    PaymentController-->>+Payment: getPatientPayments(patientId)
    Payment-->>+Database: Query patient payment records
    Database-->>-Payment: Return payment history
    Payment-->>-PaymentController: Payment history data
    PaymentController-->>-PaymentPage: Payment history
    PaymentPage-->>-Patient: Display payment history
    
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

### Activation Symbols (رموز التفعيل):
- **`+`** : Activate lifeline (تفعيل خط الحياة) - بداية معالجة في المكون
- **`-`** : Deactivate lifeline (إلغاء تفعيل خط الحياة) - انتهاء المعالجة في المكون

### Practical Examples من المخطط:
1. **`Patient-->>+PaymentPage`** : المريض يطلب صفحة الدفع ويبدأ تفعيلها
2. **`PaymentPage-->>-Patient`** : الصفحة ترد على المريض وتنهي التفعيل
3. **`alt Payment Successful`** : إذا نجحت عملية الدفع
4. **`else Payment Failed`** : وإلا إذا فشلت عملية الدفع
5. **`Payment-->>Payment`** : عملية داخلية في نموذج الدفع

## Diagram Explanation

This sequence diagram illustrates the patient payment workflow in our clinic system using Stripe integration:

### Key Components:
- **Patient**: The end user making payment for appointments
- **Payment Page**: The frontend payment interface (`Modules\Payments\Resources\views\checkout.blade.php`)
- **PaymentController**: Handles payment operations (`Modules\Payments\Http\Controllers\PaymentController`)
- **Appointment Model**: Data model for appointment entities (`Modules\Appointments\Entities\Appointment`)
- **Payment Model**: Data model for payment entities (`Modules\Payments\Entities\Payment`)
- **Stripe API**: External payment processing service
- **Database**: Persistent data storage system
- **Notification System**: Handles payment notifications

### Key Steps:

1. **Payment Checkout Initialization**
   - Patient accesses payment page for specific appointment
   - System loads appointment details and consultation fees
   - Payment form is displayed with appointment information

2. **Stripe Session Creation**
   - System creates Stripe checkout session with appointment metadata
   - Patient is redirected to secure Stripe payment interface
   - Payment processing is handled by Stripe's secure servers

3. **Payment Processing**
   - Patient completes payment on Stripe platform
   - Stripe processes the payment and returns success/failure status
   - System handles both successful and failed payment scenarios

4. **Payment Success Flow**
   - System retrieves payment session details from Stripe
   - Creates payment record in database with 'completed' status
   - Generates unique transaction ID for record keeping
   - Sends payment confirmation notifications

5. **Payment Failure Flow**
   - System records failed payment attempt
   - Provides retry options for the patient
   - Maintains appointment status for future payment attempts

6. **Combined Appointment & Payment Flow**
   - Allows creating appointment and payment in single transaction
   - Ensures appointment is created before payment processing
   - Provides seamless user experience

7. **Payment History**
   - Patients can view their payment history
   - System provides comprehensive payment tracking
   - Includes transaction details and status information

### Payment Statuses:
- **pending**: Payment initiated but not completed (معلق)
- **completed**: Payment successfully processed (مكتمل)
- **failed**: Payment failed or cancelled (فاشل)

### Payment Methods:
- **stripe**: Credit/Debit card payments via Stripe
- **cash**: Cash payments (handled offline)
- **bank_transfer**: Bank transfer payments

### Required Information for Payment:
- **Valid Appointment**: Must have existing appointment
- **Payment Amount**: Automatically calculated from consultation fee
- **Payment Method**: Stripe for online payments
- **Patient Authentication**: Must be logged in patient

### Security Features:
- **Stripe Integration**: PCI-compliant payment processing
- **Transaction IDs**: Unique identifiers for all payments
- **Metadata Tracking**: Appointment linking in Stripe sessions
- **Error Handling**: Comprehensive error management
- **Notification System**: Automated payment confirmations

### Stripe Configuration:
- **Currency**: Egyptian Pound (EGP)
- **Payment Methods**: Card payments
- **Webhook Support**: Real-time payment status updates
- **Session Management**: Secure checkout sessions

### Interactive Features:
- Real-time payment status updates
- Automatic fee calculation from doctor profiles
- Payment retry functionality for failed transactions
- Comprehensive payment history tracking
- Secure redirect flow with Stripe

The system ensures secure payment processing by leveraging Stripe's robust infrastructure while maintaining complete transaction records and providing excellent user experience for patients. 