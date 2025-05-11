<?php

if (!function_exists('format_date')) {
    function format_date($date, string $format = 'Y-m-d'): string
    {
        if (!$date) {
            return '';
        }

        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        if ($date instanceof \DateTime || $date instanceof \Carbon\Carbon) {
            return $date->format($format);
        }

        return (string) $date;
    }
}

if (!function_exists('format_time')) {
    function format_time($time, string $format = 'h:i A'): string
    {
        if (!$time) {
            return '';
        }

        if (is_string($time) && strlen($time) <= 8) {
            $time = \Carbon\Carbon::createFromFormat('H:i:s', $time);
        } elseif (is_string($time)) {
            $time = new \DateTime($time);
        }

        if ($time instanceof \DateTime || $time instanceof \Carbon\Carbon) {
            return $time->format($format);
        }

        return (string) $time;
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($datetime, string $format = 'Y-m-d h:i A'): string
    {
        if (!$datetime) {
            return '';
        }

        if (is_string($datetime)) {
            $datetime = new \DateTime($datetime);
        }

        if ($datetime instanceof \DateTime || $datetime instanceof \Carbon\Carbon) {
            return $datetime->format($format);
        }

        return (string) $datetime;
    }
}

if (!function_exists('get_appointment_time_slots')) {
    function get_appointment_time_slots(int $intervalMinutes = 30, string $startTime = '09:00', string $endTime = '17:00'): array
    {
        $slots = [];
        $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i', $endTime);

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($intervalMinutes);
        }

        return $slots;
    }
}

if (!function_exists('get_status_badge_class')) {
    function get_status_badge_class(string $status): string
    {
        $map = [
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'pending' => 'badge-warning',
            'scheduled' => 'badge-primary',
            'completed' => 'badge-info',
            'cancelled' => 'badge-danger',
            'confirmed' => 'badge-success',
        ];

        return $map[$status] ?? 'badge-secondary';
    }
}

if (!function_exists('is_valid_phone')) {
    function is_valid_phone(string $phone): bool
    {
        return true;
    }
}

if (!function_exists('sanitize_phone')) {
    function sanitize_phone(string $phone): string
    {
        return $phone;
    }
}

if (!function_exists('mask_sensitive_data')) {
    function mask_sensitive_data(string $data, int $visibleChars = 4): string
    {
        if (strlen($data) <= $visibleChars) {
            return $data;
        }

        $maskedLength = strlen($data) - $visibleChars;
        $masked = str_repeat('*', $maskedLength);

        return $masked . substr($data, -$visibleChars);
    }
}

if (!function_exists('success_response')) {
    function success_response($data = null, string $message = 'Operation successful', int $statusCode = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode,
        ];
    }
}

if (!function_exists('error_response')) {
    function error_response(string $message = 'An error occurred', $errors = null, int $statusCode = 400): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'status_code' => $statusCode,
        ];
    }
}

if (!function_exists('format_money')) {
    function format_money(float $amount, string $currency = 'USD', int $decimals = 2): string
    {
        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $decimals);

        return $formatter->formatCurrency($amount, $currency);
    }
}

