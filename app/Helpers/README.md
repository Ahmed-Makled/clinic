# Helper Functions

This directory contains global helper functions that can be used throughout the application. These functions provide common utilities for formatting, validation, and other frequently used operations.

## Available Functions

### Date and Time Formatting

- `format_date($date, $format = 'Y-m-d')`: Format a date for display
- `format_time($time, $format = 'h:i A')`: Format a time for display
- `format_datetime($datetime, $format = 'Y-m-d h:i A')`: Format a datetime for display
- `get_appointment_time_slots($intervalMinutes = 30, $startTime = '09:00', $endTime = '17:00')`: Get array of appointment time slots

### Status Helpers

- `get_status_badge_class($status)`: Get CSS class for status badge display

### Validation Helpers

- `is_valid_phone($phone)`: Validate a phone number format
- `sanitize_phone($phone)`: Remove non-numeric characters from a phone number
- `mask_sensitive_data($data, $visibleChars = 4)`: Mask sensitive information for display

### Response Formatting

- `success_response($data = null, $message = 'Operation successful', $statusCode = 200)`: Create a standardized success response
- `error_response($message = 'An error occurred', $errors = null, $statusCode = 400)`: Create a standardized error response

### Formatting

- `format_money($amount, $currency = 'USD', $decimals = 2)`: Format a number as currency

## Usage Examples

### Date Formatting

```php
// Format a date
$formattedDate = format_date($appointment->scheduled_date);

// Format a time with custom format
$formattedTime = format_time($appointment->start_time, 'H:i');
```

### Response Formatting

```php
// In a controller
public function store(Request $request)
{
    try {
        $appointment = $this->appointmentService->create($request->validated());
        return response()->json(success_response($appointment, 'Appointment created successfully'));
    } catch (\Exception $e) {
        return response()->json(error_response($e->getMessage()));
    }
}
```

### Status Badge

```php
// In a Blade template
<span class="badge {{ get_status_badge_class($appointment->status) }}">
    {{ $appointment->status_label }}
</span>
```

### Appointment Time Slots

```php
// Get 15-minute slots from 8 AM to 5 PM
$slots = get_appointment_time_slots(15, '08:00', '17:00');

// Use in a dropdown
<select name="appointment_time">
    @foreach($slots as $slot)
        <option value="{{ $slot }}">{{ format_time($slot) }}</option>
    @endforeach
</select>
```

## Adding New Helper Functions

When adding new helper functions:

1. Add the function to `functions.php`
2. Wrap it in an `if (!function_exists())` check to prevent conflicts
3. Document the function with PHPDoc comments
4. Add usage examples to this README if appropriate

The helper functions are autoloaded via Composer, so no additional steps are required to make them available in your application.

