# Admin Manage Users Flow Sequence Diagram

This diagram visualizes the admin user management process in our clinic management system, including viewing, creating, editing, and managing user accounts.

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant UsersController
    participant AuthSystem as Authentication System
    participant RoleMiddleware
    participant User as User Model
    participant Role as Role Model
    participant Doctor as Doctor Model
    participant Patient as Patient Model
    participant Database
    
    Admin->>+UsersController: Access users management dashboard
    UsersController->>+AuthSystem: Verify admin session
    AuthSystem->>+RoleMiddleware: Check admin permissions
    RoleMiddleware->>+User: hasRole('Admin')
    User-->>-RoleMiddleware: Confirm admin role
    RoleMiddleware-->>-AuthSystem: Permission granted
    AuthSystem-->>-UsersController: Access confirmed
    
    UsersController->>+Database: Query users with filters
    Database-->>-UsersController: Return users list with roles
    UsersController->>+Role: Get all available roles
    Role-->>-UsersController: Return roles list
    
    UsersController-->>Admin: Display users management interface
    
    alt View User Details
        Admin->>UsersController: Select user for details
        UsersController->>+Database: Query specific user data
        Database-->>-UsersController: User details with relationships
        UsersController-->>Admin: Display user details modal
    end
    
    alt Create New User Flow
        Admin->>UsersController: Create new user action
        UsersController->>+Role: Get available roles
        Role-->>-UsersController: Return roles for selection
        UsersController-->>Admin: Display user creation form
        
        Admin->>UsersController: Submit user creation data
        UsersController->>UsersController: Validate user data
        UsersController->>+Database: Check email uniqueness
        Database-->>-UsersController: Uniqueness check result
        
        alt Validation Failed
            UsersController-->>Admin: Display validation errors
        else Validation Successful
            UsersController->>+Database: Create new user record
            Database-->>-UsersController: User created successfully
            
            UsersController->>+Role: Assign selected role
            Role->>+Database: Create user-role relationship
            Database-->>-Role: Role assigned
            Role-->>-UsersController: Role assignment complete
            
            alt Doctor Role Selected
                UsersController->>+Doctor: Create doctor record
                Doctor->>+Database: Insert doctor profile
                Database-->>-Doctor: Doctor record created
                Doctor-->>-UsersController: Doctor profile ready
                UsersController-->>Admin: Redirect to doctor profile completion
            else Patient Role Selected
                UsersController->>+Patient: Create patient record
                Patient->>+Database: Insert patient profile
                Database-->>-Patient: Patient record created
                Patient-->>-UsersController: Patient profile ready
                UsersController-->>Admin: Redirect to patient profile completion
            else Admin Role Selected
                UsersController-->>Admin: Display success message
            end
        end
    end
    
    alt Edit User Flow
        Admin->>UsersController: Select user to edit
        UsersController->>+Database: Query user data
        Database-->>-UsersController: Return user details
        UsersController->>+Role: Get user current roles
        Role-->>-UsersController: Return current roles
        UsersController-->>Admin: Display edit form with current data
        
        Admin->>UsersController: Submit updated user data
        UsersController->>UsersController: Validate updated data
        UsersController->>+Database: Check email uniqueness (excluding current)
        Database-->>-UsersController: Uniqueness validation result
        
        alt Validation Failed
            UsersController-->>Admin: Display validation errors
        else Validation Successful
            UsersController->>+Database: Update user record
            Database-->>-UsersController: User updated successfully
            
            UsersController->>+Role: Sync user roles
            Role->>+Database: Update user-role relationships
            Database-->>-Role: Roles synchronized
            Role-->>-UsersController: Role update complete
            
            alt Role Changed to Doctor
                UsersController->>+Doctor: Check doctor record exists
                Doctor-->>-UsersController: Doctor record status
                alt Doctor Record Missing
                    UsersController-->>Admin: Redirect to doctor profile creation
                end
            else Role Changed to Patient
                UsersController->>+Patient: Check patient record exists
                Patient-->>-UsersController: Patient record status
                alt Patient Record Missing
                    UsersController-->>Admin: Redirect to patient profile creation
                end
            end
            
            UsersController-->>Admin: Display success message
        end
    end
    
    alt Delete User Flow
        Admin->>UsersController: Delete user action
        UsersController->>+Database: Check user dependencies
        Database-->>-UsersController: Return dependency status
        
        alt Has Dependencies
            UsersController-->>Admin: Display dependency warning
        else Safe to Delete
            UsersController->>+Database: Delete user record
            Database->>Database: Cascade delete related records
            Database-->>-UsersController: User deleted successfully
            UsersController-->>Admin: Display deletion success
        end
    end
    
    alt Toggle User Status
        Admin->>UsersController: Toggle user status
        UsersController->>+Database: Update user status
        Database-->>-UsersController: Status updated
        UsersController-->>Admin: Display status change confirmation
    end
    
    alt Filter and Search Users
        Admin->>UsersController: Apply search/filter criteria
        UsersController->>+Database: Query filtered users
        Database-->>-UsersController: Return filtered results
        UsersController-->>Admin: Display filtered user list
    end
    
    UsersController-->>-Admin: Complete user management session

## Key Features:

### 👥 **User Management Operations**
- Comprehensive user listing with advanced filtering
- Role-based user creation and assignment
- Profile completion workflow for doctors/patients
- User status management (active/inactive)

### 🛡️ **Security & Validation**
- Multi-layer permission checks
- Integrated data validation with error handling
- Email uniqueness verification
- Password strength requirements

### 🔄 **Dynamic Role Management**
- Real-time role assignment and synchronization
- Automatic profile creation based on role selection
- Role change handling with profile migration
- Dependency checking before deletion

### 🔍 **Advanced User Operations**
- Search by name, email, or phone number
- Filter by role and status
- Bulk operations support
- Detailed user relationship tracking
``` 
