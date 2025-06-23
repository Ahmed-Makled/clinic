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
    
    Patient->>+PaymentPage: Access payment page for appointment
    PaymentPage->>+PaymentController: checkout(appointmentId)
    PaymentController->>+Appointment: getAppointmentDetails(appointmentId)
    Appointment->>+Database: Query appointment data
    Database-->>-Appointment: Return appointment with fees
    Appointment-->>-PaymentController: Appointment details
    PaymentController-->>-PaymentPage: Display payment form
    PaymentPage-->>-Patient: Show payment checkout form
    
    Patient->>+PaymentPage: Submit payment
    PaymentPage->>+PaymentController: processPayment(appointmentId)
    PaymentController->>+StripeAPI: createCheckoutSession(paymentData)
    StripeAPI-->>PaymentController: Checkout session URL
    PaymentController-->>PaymentPage: Redirect to Stripe
    PaymentPage-->>Patient: Redirect to Stripe checkout
    
    Patient->>+StripeAPI: Complete payment on Stripe
    StripeAPI->>StripeAPI: Process payment
    
    alt Payment Successful
        StripeAPI-->>Patient: Payment success - redirect back
        Patient->>+PaymentPage: Return to success page
        PaymentPage->>+PaymentController: handleSuccess(sessionId)
        
        PaymentController->>+Payment: create(paymentData)
        Payment->>Payment: setStatus('completed')
        Payment->>+Database: Insert payment record
        Database-->>-Payment: Payment record created
        Payment-->>-PaymentController: Payment confirmed
        
        PaymentController-->>PaymentPage: Payment completed
        PaymentPage-->>-Patient: Payment success message
        
    else Payment Failed
        StripeAPI-->>Patient: Payment failed - redirect back
        Patient->>+PaymentPage: Return to failure page
        PaymentPage->>+PaymentController: handleFailure(appointmentId)
        
        PaymentController->>+Payment: create(failedPaymentData)
        Payment->>Payment: setStatus('failed')
        Payment->>+Database: Insert failed payment record
        Database-->>-Payment: Failed payment recorded
        Payment-->>-PaymentController: Failure recorded
        
        PaymentController-->>PaymentPage: Payment failed
        PaymentPage-->>-Patient: Payment failure message
    end
    
```

## Diagram Explanation

This simplified sequence diagram shows the core payment workflow:

### Key Components:
- **Patient**: User making payment for appointment
- **Payment Page**: Frontend payment interface
- **PaymentController**: Handles payment operations
- **Appointment Model**: Gets appointment details and fees
- **Payment Model**: Records payment transactions
- **Stripe API**: External payment processing
- **Database**: Data storage

### Simple Payment Flow:

1. **Payment Initialization**
   - Patient accesses payment page for appointment
   - System loads appointment details and fees
   - Payment form is displayed

2. **Payment Processing**
   - Patient submits payment
   - System creates Stripe checkout session
   - Patient is redirected to Stripe

3. **Payment Result**
   - **Success**: Payment record created with 'completed' status
   - **Failure**: Failed payment record created with 'failed' status
   - Patient receives appropriate confirmation message

### Payment Statuses:
- **completed**: Payment successful
- **failed**: Payment failed

This simplified flow focuses on the essential payment process without complex error handling or additional features. 
