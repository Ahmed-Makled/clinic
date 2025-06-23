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
    
    Admin->>+ContactsController: Access contact messages management
    ContactsController->>+AuthSystem: Verify admin session
    AuthSystem->>+RoleMiddleware: Check admin permissions
    RoleMiddleware->>+Database: Verify admin role
    Database-->>-RoleMiddleware: Confirm admin access
    RoleMiddleware-->>-AuthSystem: Permission granted
    AuthSystem-->>-ContactsController: Access confirmed
    
    ContactsController->>+Contact: Get contact messages with pagination
    Contact->>+Database: Query contact messages (latest first, paginated)
    Database-->>-Contact: Return paginated contact messages
    Contact-->>-ContactsController: Paginated contact messages data
    
    ContactsController-->>Admin: Display contact messages interface
    
    alt View Contact Message Details
        Admin->>ContactsController: Select message for full view
        ContactsController->>+Contact: Get complete message details
        Contact->>+Database: Query specific message
        Database-->>-Contact: Return full message data
        Contact-->>-ContactsController: Complete message details
        
        ContactsController-->>Admin: Display full message modal
    end
    
    alt Navigate Through Messages
        Admin->>ContactsController: Request different page
        ContactsController->>+Contact: Get messages for specific page
        Contact->>+Database: Query paginated messages for page
        Database-->>-Contact: Return page results
        Contact-->>-ContactsController: Paginated message data
        
        ContactsController-->>Admin: Display requested page
    end
    
    alt Review Message Statistics
        Admin->>ContactsController: Request message analytics
        ContactsController->>+Contact: Get message statistics
        Contact->>+Database: Query message counts by date
        Database-->>-Contact: Daily/weekly/monthly counts
        Contact->>+Database: Query common subjects
        Database-->>-Contact: Subject frequency data
        Contact-->>-ContactsController: Complete statistics
        
        ContactsController-->>Admin: Display message analytics
    end
    
    alt Export Messages Data
        Admin->>ContactsController: Request data export
        ContactsController->>+Contact: Get messages for export
        Contact->>+Database: Query all/filtered messages
        Database-->>-Contact: Complete message dataset
        Contact-->>-ContactsController: Export-ready data
        
        ContactsController->>ContactsController: Format data (CSV/Excel)
        ContactsController-->>Admin: Download exported file
    end
    
    alt View System Logs
        Admin->>ContactsController: Access contact system logs
        ContactsController->>+Database: Query system logs
        Database-->>-ContactsController: Contact form logs
        
        ContactsController-->>Admin: Display system activity logs
    end
    
    ContactsController-->>-Admin: Complete contact management session

## Key Features:

### 📧 **Contact Message Management**
- Comprehensive message listing with preview
- Full message details with sender information
- Chronological organization (latest first)
- Efficient pagination for large volumes

### 🔍 **Analytics**
- Message volume analytics and trends
- Common inquiry type identification
- Export functionality for data analysis

### 📊 **System Monitoring**
- System activity logs and debugging
- Direct database logging access
- Form submission tracking

### ⚙️ **Administration Tools**
- Direct contact data management
- Export capabilities for analysis
- Simplified system log access
``` 
