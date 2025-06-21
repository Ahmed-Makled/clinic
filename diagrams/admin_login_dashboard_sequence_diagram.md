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
    participant StatsService as Statistics Service
    participant ChartService as Chart Service
    
    Admin-->>+AuthSystem: Visit admin login page
    AuthSystem-->>-Admin: Display login form
    
    Admin-->>+AuthSystem: Submit admin credentials
    AuthSystem-->>+LoginController: login(email, password)
    LoginController-->>+Database: Validate credentials
    Database-->>-LoginController: Return user data
    
    alt Authentication Failed
        LoginController-->>AuthSystem: Return authentication errors
        AuthSystem-->>Admin: Display login errors
    else Authentication Successful
        LoginController-->>+User: checkUserRole()
        User-->>-LoginController: Return role ('Admin')
        
        alt Role Verification Failed
            LoginController-->>AuthSystem: Access denied
            AuthSystem-->>Admin: Redirect to login with error
        else Admin Role Verified
            LoginController-->>+Database: Update last_seen timestamp
            Database-->>-LoginController: Confirm update
            LoginController-->>AuthSystem: Redirect to admin dashboard
            
            AuthSystem-->>+RoleMiddleware: Check admin permissions
            RoleMiddleware-->>+User: hasRole('Admin')
            User-->>-RoleMiddleware: Confirm admin role
            RoleMiddleware-->>+DashboardController: Access granted
            
            DashboardController-->>+StatsService: Gather system statistics
            StatsService-->>+Database: Query users count
            Database-->>-StatsService: Users statistics
            StatsService-->>+Database: Query doctors count
            Database-->>-StatsService: Doctors statistics
            StatsService-->>+Database: Query patients count
            Database-->>-StatsService: Patients statistics
            StatsService-->>+Database: Query appointments stats
            Database-->>-StatsService: Appointments statistics
            StatsService-->>+Database: Query financial data
            Database-->>-StatsService: Financial statistics
            StatsService-->>-DashboardController: Complete statistics data
            
            DashboardController-->>+ChartService: Generate chart data
            ChartService-->>+Database: Query appointment trends
            Database-->>-ChartService: Appointment chart data
            ChartService-->>+Database: Query specialty distribution
            Database-->>-ChartService: Specialty chart data
            ChartService-->>-DashboardController: Complete chart data
            
            DashboardController-->>+Database: Query recent activities
            Database-->>-DashboardController: Recent activities log
            
            DashboardController-->>Admin: Display admin dashboard
            Note over Admin,DashboardController: Dashboard shows: system stats,<br/>user metrics, financial data,<br/>charts, recent activities
            
            alt Dashboard Actions
                Admin-->>DashboardController: Refresh dashboard data
                DashboardController-->>+StatsService: Update statistics
                StatsService-->>+Database: Re-query all stats
                Database-->>-StatsService: Updated statistics
                StatsService-->>-DashboardController: Fresh statistics
                DashboardController-->>Admin: Display updated dashboard
            else Navigate to Management
                Admin-->>DashboardController: Select management section
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

### 📊 **Dashboard Statistics**
- Real-time system metrics (users, doctors, patients)
- Appointment statistics and completion rates
- Financial data and revenue tracking
- Activity monitoring and logs

### 📈 **Data Visualization**
- Interactive charts for appointment trends
- Specialty distribution graphs
- Financial performance indicators
- Time-based analytics

### 🎛️ **Management Controls**
- Quick access to all admin modules
- Real-time data refresh capabilities
- Navigation to specialized management sections 