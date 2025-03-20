<?php
return [
  'paths' => ['api/*', 'auth/*', 'sanctum/csrf-cookie'],
  'allowed_methods' => ['*'],
  'allowed_origins' => env('APP_ENV') === 'production'
    ? ['https://graus.lat', 'https://api.graus.lat']
    : ['*'],
  'allowed_origins_patterns' => [],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  'supports_credentials' => false,
];
