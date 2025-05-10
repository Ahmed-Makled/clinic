`# Core Module

## Overview
The Core module provides shared functionality, traits, and services that can be used across all other modules in the clinic application. This module centralizes common code to prevent duplication and maintain consistency throughout the application.

## Features
- Shared traits for model functionality (HasStatus, Searchable)
- Common services and helpers
- Base controllers and interfaces

## Directory Structure
```
Core/
  ├── Config/           - Module configuration
  ├── Http/             - Controllers and middleware
  ├── Providers/        - Service providers
  ├── Routes/           - Web routes
  ├── Services/         - Shared services
  └── Traits/           - Common model traits
```

## Available Traits

### HasStatus Trait
Provides status management functionality for Eloquent models.

**Usage:**
```php
use Modules\Core\Traits\HasStatus;

class YourModel extends Model
{
    use HasStatus;
    
    // Optional: Define custom statuses
    const STATUSES = ['active', 'inactive', 'pending'];
    
    // Optional: Define status labels
    const STATUS_LABELS = [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'معلق'
    ];
}
```

### Searchable Trait
Provides search functionality for Eloquent models.

**Usage:**
```php
use Modules\Core\Traits\Searchable;

class YourModel extends Model
{
    use Searchable;
    
    // Define fields to search in
    protected $searchable = ['name', 'description', 'email'];
}
```

## How to Extend
To add new shared functionality to the Core module:

1. Create your service/trait in the appropriate directory
2. Update documentation to reflect the new functionality
3. Use the new functionality in other modules as needed