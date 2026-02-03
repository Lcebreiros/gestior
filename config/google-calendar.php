<?php

return [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/google/callback'),

    'scopes' => [
        'https://www.googleapis.com/auth/calendar',
        'https://www.googleapis.com/auth/calendar.events',
        'https://www.googleapis.com/auth/userinfo.email',
    ],

    'event_settings' => [
        'send_notifications' => true,
        'default_reminder_minutes' => 60,
        'colors' => [
            'order_delivery' => '9',      // Azul
            'payment_deadline' => '11',   // Rojo
            'tax_due' => '4',             // Rosa
            'supply_reorder' => '10',     // Verde
            'expense_payment' => '6',     // Naranja
            'reminder' => '7',            // Cyan
            'custom' => '1',              // Lavanda
        ],
    ],
];
