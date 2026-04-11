<?php

return [
    'paths'                    => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods'          => ['*'],
    'allowed_origins'          => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'http://localhost:5173',
        'http://localhost:5174',
        'http://localhost:5175',
        'http://localhost:3000',
    ],
    'allowed_origins_patterns' => [
        '#^https?://.*\.vercel\.app$#' // Dynamically allow any vercel deployment
    ],
    'allowed_headers'          => ['*'],
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => true,
];
