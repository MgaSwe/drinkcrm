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

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'jira' => [
        'base_url'       => env('JIRA_BASE_URL'),
        'user'           => env('JIRA_USER'),
        'token'          => env('JIRA_TOKEN'),
        'auth'           => env('JIRA_AUTH', 'pat'),
        'project_key'    => env('JIRA_PROJECT_KEY', 'DRNK'),
        'issue_type'     => env('JIRA_ISSUE_TYPE', 'Task'),
        'webhook_secret' => env('JIRA_WEBHOOK_SECRET'),
    ],
];
