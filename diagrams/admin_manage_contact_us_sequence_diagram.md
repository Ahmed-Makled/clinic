# Admin Manage Contact Us Flow Sequence Diagram

This diagram visualizes the admin contact message management process in our clinic management system, including viewing, reviewing, and managing contact form submissions.

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant ContactsController
    participant AuthSystem as Authentication System
    participant RoleMiddleware
    participant Contact as Contact Model
    participant Database
    participant EmailService as Email Service
    participant LoggingService as Logging Service
    
    Admin-->>+ContactsController: Access contact messages management
    ContactsController-->>+AuthSystem: Verify admin session
    AuthSystem-->>+RoleMiddleware: Check admin permissions
    RoleMiddleware-->>+Database: Verify admin role
    Database-->>-RoleMiddleware: Confirm admin access
    RoleMiddleware-->>-AuthSystem: Permission granted
    AuthSystem-->>-ContactsController: Access confirmed
    
    ContactsController-->>+Contact: Get contact messages with pagination
    Contact-->>+Database: Query contact messages (latest first, paginated)
    Database-->>-Contact: Return paginated contact messages
    Contact-->>-ContactsController: Paginated contact messages data
    
    ContactsController-->>Admin: Display contact messages interface
    Note over Admin,ContactsController: Shows: messages list with name,<br/>email, subject, message preview,<br/>date received, pagination
    
    alt View Contact Message Details
        Admin-->>ContactsController: Select message for full view
        ContactsController-->>+Contact: Get complete message details
        Contact-->>+Database: Query specific message
        Database-->>-Contact: Return full message data
        Contact-->>-ContactsController: Complete message details
        
        ContactsController-->>Admin: Display full message modal
        Note over Admin,ContactsController: Shows: sender info, complete message,<br/>timestamp, any system notes
    end
    
    alt Navigate Through Messages
        Admin-->>ContactsController: Request different page
        ContactsController-->>+Contact: Get messages for specific page
        Contact-->>+Database: Query paginated messages for page
        Database-->>-Contact: Return page results
        Contact-->>-ContactsController: Paginated message data
        
        ContactsController-->>Admin: Display requested page
        Note over Admin,ContactsController: Laravel pagination handles<br/>page navigation automatically
    end
    
    alt Review Message Statistics
        Admin-->>ContactsController: Request message analytics
        ContactsController-->>+Contact: Get message statistics
        Contact-->>+Database: Query message counts by date
        Database-->>-Contact: Daily/weekly/monthly counts
        Contact-->>+Database: Query common subjects
        Database-->>-Contact: Subject frequency data
        Contact-->>-ContactsController: Complete statistics
        
        ContactsController-->>Admin: Display message analytics
        Note over Admin,ContactsController: Shows: message volume trends,<br/>common inquiry types, response patterns
    end
    
    alt Search and Filter Messages
        Admin-->>ContactsController: Apply search criteria
        ContactsController-->>+Contact: Filter messages
        Contact-->>+Database: Execute filtered query
        Note over Contact,Database: Search by: sender name, email,<br/>subject, date range
        Database-->>-Contact: Return filtered results
        Contact-->>-ContactsController: Filtered message list
        
        ContactsController-->>Admin: Display filtered messages with pagination
    end
    
    alt Export Messages Data
        Admin-->>ContactsController: Request data export
        ContactsController-->>+Contact: Get messages for export
        Contact-->>+Database: Query all/filtered messages
        Database-->>-Contact: Complete message dataset
        Contact-->>-ContactsController: Export-ready data
        
        ContactsController-->>ContactsController: Format data (CSV/Excel)
        ContactsController-->>Admin: Download exported file
        Note over Admin,ContactsController: Export includes: all message fields,<br/>timestamps, formatted for analysis
    end
    
    alt Monitor Email Integration
        ContactsController-->>+EmailService: Check email service status
        EmailService-->>+LoggingService: Get recent email logs
        LoggingService-->>+Database: Query email delivery logs
        Database-->>-LoggingService: Email delivery status
        LoggingService-->>-EmailService: Email service health
        EmailService-->>-ContactsController: Service status report
        
        ContactsController-->>Admin: Display email service status
        Note over Admin,ContactsController: Shows: email delivery success rate,<br/>failed deliveries, service health
    end
    
    alt View System Logs
        Admin-->>ContactsController: Access contact system logs
        ContactsController-->>+LoggingService: Get contact-related logs
        LoggingService-->>+Database: Query system logs
        Database-->>-LoggingService: Contact form logs
        LoggingService-->>-ContactsController: Formatted log data
        
        ContactsController-->>Admin: Display system activity logs
        Note over Admin,ContactsController: Shows: form submissions,<br/>email delivery attempts, errors
    end
    
    alt Manage Email Configuration
        Admin-->>ContactsController: Access email settings
        ContactsController-->>+EmailService: Get current email config
        EmailService-->>EmailService: Read configuration
        EmailService-->>-ContactsController: Current email settings
        
        ContactsController-->>Admin: Display email configuration
        Note over Admin,ContactsController: Shows: recipient email,<br/>SMTP settings, notification preferences
        
        alt Update Email Settings
            Admin-->>ContactsController: Submit updated email config
            ContactsController-->>+EmailService: Validate and update config
            EmailService-->>EmailService: Test email connectivity
            
            alt Configuration Valid
                EmailService-->>EmailService: Save new configuration
                EmailService-->>ContactsController: Configuration updated
                ContactsController-->>Admin: Display success message
            else Configuration Invalid
                EmailService-->>ContactsController: Configuration errors
                ContactsController-->>Admin: Display configuration errors
            end
            EmailService-->>-ContactsController: Complete email configuration process
        end
    end
    
    ContactsController-->>-Admin: Complete contact management session

## Key Features:

### 📧 **Contact Message Management**
- Comprehensive message listing with preview
- Full message details with sender information
- Chronological organization (latest first)
- Efficient pagination for large volumes

### 🔍 **Search and Analytics**
- Advanced search by sender, subject, or date
- Message volume analytics and trends
- Common inquiry type identification
- Export functionality for data analysis

### 📊 **System Monitoring**
- Email service health monitoring
- Delivery success rate tracking
- System activity logs and debugging
- Configuration management interface

### ⚙️ **Administration Tools**
- Email configuration management
- SMTP settings validation
- Notification preferences control
- Service integration monitoring
``` 