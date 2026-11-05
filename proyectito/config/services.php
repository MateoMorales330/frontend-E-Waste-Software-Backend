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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // GHOST DEMO: no se persiste en la base; cambiar o eliminar este bloque
    // cuando el perfil fantasma deje de ser necesario.
    'ghost_demo' => [
        'enabled' => env('GHOST_DEMO_ENABLED', false),
        'email' => env('GHOST_DEMO_EMAIL', 'demo@zonamovil.com'),
        'password' => env('GHOST_DEMO_PASSWORD', 'demo1234'),
        'token' => env('GHOST_DEMO_TOKEN', 'ghost-demo-token-zonamovil'),
        'name' => env('GHOST_DEMO_NAME', 'Usuario Demo'),
    ],

];
