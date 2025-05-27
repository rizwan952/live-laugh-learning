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
    'refund_percentage' => env('REFUND_PERCENTAGE','80'),

    'zoom' => [
        'account_id' => env('ZOOM_ACCOUNT_ID', 'nAzPK_BEQXyDPe8RwCJ-YA'),
        'client_id' => env('ZOOM_CLIENT_ID', '6PARV5NbS8KL7sGjMfO7vA'),
        'client_secret' => env('ZOOM_CLIENT_SECRET', 'HLm4tRAdXVzdXYlqH8M67OI1YDhbzZB2'),
        'api_base_url' => env('ZOOM_API_BASE_URL', 'https://api.zoom.us/v2'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
