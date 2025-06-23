# Admin Login and Dashboard Flow Sequence Diagram

This diagram visualizes the admin login and dashboard management process in our clinic management system.

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant AuthSystem as Authentication System
    participant LoginController
    participant RoleMiddleware
    participant User as User Model
    participant DashboardController
    participant Database
    
    Admin->>+AuthSystem: Visit admin login page
    AuthSystem-->>-Admin: Display login form
    
    Admin->>+AuthSystem: Submit admin credentials
    AuthSystem->>+LoginController: login(email, password)
    LoginController->>+Database: Validate credentials
    Database-->>-LoginController: Return user data
    
    alt Authentication Failed
        LoginController-->>AuthSystem: Return authentication errors
        AuthSystem-->>Admin: Display login errors
    else Authentication Successful
        LoginController->>+User: checkUserRole()
        User-->>-LoginController: Return role ('Admin')
        
        alt Role Verification Failed
            LoginController-->>AuthSystem: Access denied
            AuthSystem-->>Admin: Redirect to login with error
        else Admin Role Verified
            LoginController->>+DashboardController: Redirect to admin dashboard
            
            DashboardController->>+Database: Query users count
            Database-->>-DashboardController: Users statistics
            DashboardController->>+Database: Query doctors count
            Database-->>-DashboardController: Doctors statistics
            DashboardController->>+Database: Query patients count
            Database-->>-DashboardController: Patients statistics
            DashboardController->>+Database: Query appointments stats
            Database-->>-DashboardController: Appointments statistics
            DashboardController->>+Database: Query financial data
            Database-->>-DashboardController: Financial statistics
            DashboardController->>+Database: Query recent activities
            Database-->>-DashboardController: Recent activities log
            
            DashboardController-->>Admin: Display admin dashboard
            
            alt Dashboard Actions
                Admin->>DashboardController: Refresh dashboard data
                DashboardController->>+Database: Re-query all stats
                Database-->>-DashboardController: Updated statistics
                DashboardController-->>Admin: Display updated dashboard
            else Navigate to Management
                Admin->>DashboardController: Select management section
                DashboardController-->>Admin: Redirect to selected module
            end
        end
    end
    
    DashboardController-->>-Admin: Complete dashboard session

## Key Features:

### 🔐 **Authentication & Authorization**
- Multi-step admin credential validation
- Role-based access control with middleware
- Session management and security checks

### 📊 **Dashboard Data**
- Real-time system metrics (users, doctors, patients)
- Appointment statistics and completion rates
- Financial data and revenue tracking
- Activity monitoring and logs

### 🎛️ **Management Controls**
- Quick access to all admin modules
- Real-time data refresh capabilities
- Navigation to specialized management sections 
