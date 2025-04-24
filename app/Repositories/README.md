# Repository Pattern

The Repository Pattern is implemented in this application to abstract the data layer, making the application more maintainable and testable.

## Purpose

Repositories provide a standard interface to data operations, separating the logic that retrieves data from the business logic that acts on it. Benefits include:

- Centralizes data access logic
- Provides a substitution point for unit tests
- Reduces duplicate query logic
- Improves maintainability of database interactions

## Usage Guidelines

### Creating a New Repository

1. Create a new repository class that extends the `BaseRepository` class:

```php
namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository extends BaseRepository
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }
    
    // Add custom query methods here
}
```

2. Register your repository in a service provider (recommended for dependency injection):

```php
// In AppServiceProvider or a dedicated RepositoryServiceProvider
$this->app->bind(
    \App\Repositories\Interfaces\AppointmentRepositoryInterface::class,
    \App\Repositories\AppointmentRepository::class
);
```

3. Add custom methods to extend the base functionality:

```php
/**
 * Find appointments for a specific doctor
 *
 * @param int $doctorId
 * @param string $date YYYY-MM-DD
 * @return Collection
 */
public function findByDoctorAndDate(int $doctorId, string $date)
{
    return $this->model
        ->where('doctor_id', $doctorId)
        ->whereDate('scheduled_date', $date)
        ->orderBy('scheduled_time')
        ->get();
}
```

### Repository Interface (Optional but Recommended)

For better dependency inversion, define interfaces for your repositories:

```php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface AppointmentRepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []): Collection;
    public function findByDoctorAndDate(int $doctorId, string $date);
    // Other methods...
}
```

### Best Practices

1. Keep repositories focused on a single entity or model
2. Use type-hinting and return type declarations
3. Follow consistent naming conventions for methods
4. Consider using interfaces for better testing and dependency inversion
5. Don't put business logic in repositories - they should only handle data access
6. Use collections and query builders when appropriate

## Module Integration

For module-specific repositories, follow the same pattern but place them within the module structure.

When repositories need to interact with models from different modules, consider:
1. Using the application's central repositories
2. Creating module interfaces and binding implementations
3. Using service classes to coordinate between different module repositories

