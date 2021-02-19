<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys while generating secure access tokens for
    | your application. By default, the keys are stored as local files but
    | can be set via environment variables when that is more convenient.
    |
    */
    
    'merchant_id' => env('GET_NET_MERCHANT_ID_PRODUCTION'),
    'seller_id' => env('GET_NET_SELLER_ID_PRODUCTION'),
    'client_id' => env('GET_NET_CLIENT_ID_PRODUCTION'),
    'client_secret' => env('GET_NET_CLIENT_SECRET_PRODUCTION'),
];