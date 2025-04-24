# Traits

This directory contains reusable traits that can be applied to models and other classes in the application.

## Available Traits

### HasStatus

The `HasStatus` trait provides methods for managing status fields in Eloquent models.

#### Usage

```php
use App\Traits\HasStatus;

class Appointment extends Model
{
    use HasStatus;
    
    // Define constants for the trait to use
    const STATUS_COLUMN = 'status';
    
    const STATUSES = [
        'scheduled',
        'confirmed',
        'cancelled',
        'completed'
    ];
    
    const STATUS_LABELS = [
        'scheduled' => 'Scheduled',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed'
    ];
    
    const STATUS_COLORS = [
        'scheduled' => 'blue',
        'confirmed' => 'green',
        'cancelled' => 'red',
        'completed' => 'gray'
    ];
}
```

#### Methods

- `hasStatus($status)`: Check if the model has a specific status
- `updateStatus($status)`: Update the model's status
- `scopeWithStatus($query, $status)`: Scope to filter by status
- `scopeWithStatuses($query, $statuses)`: Scope to filter by multiple statuses
- `scopeActive($query)`: Scope to filter active models
- `getStatusLabelAttribute()`: Get a human-readable status label
- `getStatusColorAttribute()`: Get a color associated with the status for UI

### Searchable

The `Searchable` trait adds search functionality to Eloquent models.

#### Usage

```php
use App\Traits\Searchable;

class Doctor extends Model
{
    use Searchable;
    
    // Define searchable fields
    protected $searchable = [
        'name',
        'email',
        'specialty',
        'biography'
    ];
}
```

Then in your controller:

```php
public function index(Request $request)
{
    $searchTerm = $request->get('search');
    
    $doctors = Doctor::when($searchTerm, function ($query) use ($searchTerm) {
        return $query->search($searchTerm);
    })->paginate(10);
    
    return view('doctors.index', compact('doctors'));
}
```

For advanced search:

```php
$advancedSearch = [
    'name' => $request->get('name'),
    'specialty' => $request->get('specialty'),
    'experience' => ['operator' => '>=', 'value' => $request->get('min_experience')]
];

$doctors = Doctor::advancedSearch($advancedSearch)->paginate(10);
```

#### Methods

- `scopeSearch($query, $term)`: Basic search across defined searchable fields
- `scopeAdvancedSearch($query, $searchTerms)`: Advanced search with specific columns and operators
- `scopeFuzzySearch($query, $term)`: Search that splits terms into words for more flexible matching

## Best Practices

1. Keep traits focused on a single responsibility
2. Document any required properties or methods that must be implemented by classes using the trait
3. Use dependency injection when traits need to interact with services
4. Consider using interfaces alongside traits for more complex behavior
5. Test traits in isolation when possible

