<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'tune_api' => [
        'key' => env('TUNE_API_KEY'),
        'network_id' => env('TUNE_API_NETWORK_ID'),
        'conversions_update_from_last_x_months' => env('TUNE_API_CONVERSIONS_UPDATE_STARTING_FROM_LAST_X_MONTHS'),
        'stats_timezone' => env('TUNE_STATS_TIMEZONE', 'EST'), #TODO add to env.example
    ],
    'yoursurveys_readme_io' => [
        'secret' => env('YOURSURVEYS_README_IO_SECRET'),
        'url' => env('YOURSURVEYS_README_IO_URL'),
    ],
    'dalia' => [
        'publisher_user_uuid' => env('DALIA_PUBLISHER_USER_UUID'),
    ],


];
