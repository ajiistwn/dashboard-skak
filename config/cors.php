<?php

return [
    'paths' => ['api/*', 'auth/google/callback', 'sanctum/csrf-cookie', 'auth/google'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173', 'https://google.com'], // React kamu
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];

