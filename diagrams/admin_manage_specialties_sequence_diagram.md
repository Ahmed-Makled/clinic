# Admin Manage Specialties Flow Sequence Diagram

This diagram visualizes the admin specialty management process in our clinic management system, including viewing, creating, editing, and managing medical specialties.

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant SpecialtyController
    participant AuthSystem as Authentication System
    participant RoleMiddleware
    participant Category as Category Model
    participant Doctor as Doctor Model
    participant Database
    
    Admin->>+SpecialtyController: Access specialties management
    SpecialtyController->>+AuthSystem: Verify admin session
    AuthSystem->>+RoleMiddleware: Check admin permissions
    RoleMiddleware->>+Database: Verify admin role
    Database-->>-RoleMiddleware: Confirm admin access
    RoleMiddleware-->>-AuthSystem: Permission granted
    AuthSystem-->>-SpecialtyController: Access confirmed
    
    SpecialtyController->>+Category: Get specialties with doctor count
    Category->>+Database: Query specialties with relationships
    Database-->>-Category: Return specialties data
    Category-->>-SpecialtyController: Specialties list with stats
    
    SpecialtyController-->>Admin: Display specialties management interface
    Note over Admin,SpecialtyController: Shows: specialties list, doctor counts,<br/>search functionality, status filters
    
    alt Create New Specialty Flow
        Admin->>SpecialtyController: Create specialty action
        SpecialtyController-->>Admin: Display specialty creation form
        
        Admin->>SpecialtyController: Submit specialty data
        SpecialtyController->>SpecialtyController: Validate specialty data
        SpecialtyController->>+Database: Check name uniqueness
        Database-->>-SpecialtyController: Uniqueness check result
        
        alt Validation Failed
            SpecialtyController-->>Admin: Display validation errors
        else Validation Successful
            SpecialtyController->>SpecialtyController: Generate unique slug
            SpecialtyController->>+Database: Check slug uniqueness
            Database-->>-SpecialtyController: Slug validation result
            
            SpecialtyController->>+Category: Create new specialty
            Category->>+Database: Insert specialty record
            Database-->>-Category: Specialty created successfully
            Category-->>-SpecialtyController: Specialty creation complete
            
            SpecialtyController-->>Admin: Display creation success message
            SpecialtyController-->>Admin: Redirect to specialties list
        end
    end
    
    alt Edit Specialty Flow
        Admin->>SpecialtyController: Select specialty to edit
        SpecialtyController->>+Category: Get specialty details
        Category->>+Database: Query specialty data
        Database-->>-Category: Return specialty details
        Category-->>-SpecialtyController: Specialty data loaded
        
        SpecialtyController-->>Admin: Display edit form with current data
        
        Admin->>SpecialtyController: Submit updated specialty data
        SpecialtyController->>SpecialtyController: Validate updated data
        SpecialtyController->>+Database: Check name uniqueness (excluding current)
        Database-->>-SpecialtyController: Uniqueness validation result
        
        alt Validation Failed
            SpecialtyController-->>Admin: Display validation errors
        else Validation Successful
            alt Name Changed
                SpecialtyController->>SpecialtyController: Generate new slug
                SpecialtyController->>+Database: Verify slug uniqueness
                Database-->>-SpecialtyController: Slug check result
            end
            
            SpecialtyController->>+Category: Update specialty
            Category->>+Database: Update specialty record
            Database-->>-Category: Specialty updated successfully
            Category-->>-SpecialtyController: Update complete
            
            SpecialtyController-->>Admin: Display update success message
            SpecialtyController-->>Admin: Redirect to specialties list
        end
    end
    
    alt Delete Specialty Flow
        Admin->>SpecialtyController: Delete specialty action
        SpecialtyController->>+Category: Check specialty dependencies
        Category->>+Doctor: Check associated doctors
        Doctor->>+Database: Query doctors with this specialty
        Database-->>-Doctor: Return doctors count
        Doctor-->>-Category: Dependency check result
        Category-->>-SpecialtyController: Dependency status
        
        alt Has Associated Doctors
            SpecialtyController-->>Admin: Display dependency error
            Note over Admin,SpecialtyController: Cannot delete specialty<br/>with associated doctors
        else Safe to Delete
            SpecialtyController->>+Category: Delete specialty
            Category->>+Database: Remove specialty record
            Database-->>-Category: Specialty deleted successfully
            Category-->>-SpecialtyController: Deletion complete
            
            SpecialtyController-->>Admin: Display deletion success message
            SpecialtyController-->>Admin: Refresh specialties list
        end
    end
    
    alt Search and Filter Specialties
        Admin->>SpecialtyController: Apply search/filter criteria
        SpecialtyController->>+Category: Query filtered specialties
        Category->>+Database: Execute filtered query
        Database-->>-Category: Return filtered results
        Category-->>-SpecialtyController: Filtered specialties list
        
        SpecialtyController-->>Admin: Display filtered specialty results
        Note over Admin,SpecialtyController: Real-time search by name<br/>and description, status filtering
    end
    
    alt View Specialty Statistics
        Admin->>SpecialtyController: View specialty analytics
        SpecialtyController->>+Category: Get specialty statistics
        Category->>+Doctor: Count active doctors per specialty
        Doctor->>+Database: Query doctor statistics
        Database-->>-Doctor: Return doctor counts
        Doctor-->>-Category: Doctor statistics data
        Category->>+Database: Get appointment statistics per specialty
        Database-->>-Category: Appointment statistics
        Category-->>-SpecialtyController: Complete analytics data
        
        SpecialtyController-->>Admin: Display specialty analytics
        Note over Admin,SpecialtyController: Shows: doctor distribution,<br/>appointment volumes, revenue per specialty
    end
    
    SpecialtyController-->>-Admin: Complete specialty management session

## Key Features:

### 🏥 **Specialty Management Operations**
- Comprehensive specialty listing with doctor counts
- Medical specialty creation and categorization
- Advanced search and filtering capabilities
- Status management (active/inactive)

### 🔗 **Relationship Management**
- Doctor-specialty association tracking
- Dependency checking before deletion
- Automatic slug generation for SEO
- Statistics and analytics per specialty

### ✅ **Data Validation & Integrity**
- Name uniqueness validation
- Integrated slug generation and uniqueness checks
- Description length validation
- Cascading relationship protection

### 📊 **Analytics & Reporting**
- Doctor distribution per specialty
- Appointment volume tracking
- Revenue analysis by specialty
- Real-time statistics updates
``` 
