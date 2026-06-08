<?php

return [
    'providers' => [
        'ahamove' => [
            'api_key' => env('AHAMOVE_API_KEY'),
            'endpoint' => 'https://api.ahamove.com/v1',
        ],
        'grab' => [
            'api_key' => env('GRAB_EXPRESS_API_KEY'),
            'endpoint' => 'https://api.grab.com/grabexpress/v1',
        ],
        'lalamove' => [
            'api_key' => env('LALAMOVE_API_KEY'),
            'endpoint' => 'https://rest.lalamove.com/v2',
        ],
    ],
];
