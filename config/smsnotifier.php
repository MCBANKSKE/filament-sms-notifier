<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Gateway Settings
    |--------------------------------------------------------------------------
    |
    | These credentials will be used by the default TextSmsGatewayDriver.
    | You can change these via environment variables or hardcode here for testing.
    |
    */

    'api_key' => env('SMSNOTIFIER_API_KEY', ''),
    'partner_id' => env('SMSNOTIFIER_PARTNER_ID', ''),
    'shortcode' => env('SMSNOTIFIER_SHORTCODE', 'TextSMS'),

];
