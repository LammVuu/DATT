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

    'facebook' => [
        'client_id' => '186623080040346',
        'client_secret' => '778d952e526d81b40ec3fef4da5cf0c0',
        'redirect' => 'http://localhost:8000/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => '285654392090-o4fnoj0jamm1dmakjloo3n4go803qomm.apps.googleusercontent.com',
        'client_secret' => '3U-Cvx1lY0P8pwRjRkceOYez',
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],

];
