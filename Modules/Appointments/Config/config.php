<?php

return [
    'name' => 'Appointments',
    'description' => 'Manage clinic appointments',
    'options' => [
        'time_slots' => [
            'interval' => 30, // minutes
            'start_time' => '09:00',
            'end_time' => '17:00'
        ],
        'status_options' => [
            'scheduled',
            'completed',
            'cancelled'
        ]
    ]
];
