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

    'whisper' => [
        'url' => env('WHISPER_URL', 'http://localhost:8085/transcribe'),
        // srt | vtt | json | txt
        'format' => env('WHISPER_FORMAT', 'srt'),
        // Kode bahasa, mis. 'id' atau 'en'. Kosongkan untuk deteksi otomatis
        'language' => env('WHISPER_LANGUAGE', 'id'),
        // transcribe | translate (ke bahasa Inggris)
        'task' => env('WHISPER_TASK', ''),
        // small | medium | large-v2 | large-v3 | large-v3-turbo
        'model' => env('WHISPER_MODEL', 'large-v3'),
        'channels' => env('WHISPER_CHANNELS', "otomatis"),
        'timeout' => (int) env('WHISPER_TIMEOUT', 600),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
