<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Settings
    |--------------------------------------------------------------------------
    */

    'access_token_ttl' => env('JWT_ACCESS_TOKEN_TTL', 3600), // 1 hour

    'refresh_token_ttl' => env('JWT_REFRESH_TOKEN_TTL', 604800), // 7 days

    'algorithm' => env('JWT_ALGORITHM', 'HS256'),

    'secret' => env('JWT_SECRET', env('JWT_SECRET_KEY', env('APP_KEY'))),
];
