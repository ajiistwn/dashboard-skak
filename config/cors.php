<?php

return [
    'paths' => ['api/*', 'auth/google/callback', 'sanctum/csrf-cookie', 'auth/google'],
    'allowed_methods' => ['*'],
    // 'allowed_origins' => ['http://localhost:5173', 'https://google.com', 'http://localhost:5175', 'http://localhost:8080'], // React kamu
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

