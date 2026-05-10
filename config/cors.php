<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Profile
    |--------------------------------------------------------------------------
    |
    | This profile is used to configure the Cross-Origin Resource Sharing
    | middleware. This middleware is automatically registered for you if
    | you have a 'cors' middleware group in your application kernel.
    |
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
