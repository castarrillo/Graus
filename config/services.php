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

  'slack' => [
    'notifications' => [
      'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
      'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
    ],
  ],

  'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),     // ID de Cliente de Google
    'client_secret' => env('GOOGLE_CLIENT_SECRET'), // Secreto de Google
    'redirect'      => env('GOOGLE_REDIRECT_URI'),  // URL de Redirección
    'scopes' => [
      'openid',
      'profile',
      'email',
      'https://www.googleapis.com/auth/user.phonenumbers.read'
    ],
  ],

  'twilio' => [
    'sid'   => env('TWILIO_SID'),
    'token' => env('TWILIO_AUTH_TOKEN'),
    'from'  => env('TWILIO_FROM'),
    'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
  ],
];
