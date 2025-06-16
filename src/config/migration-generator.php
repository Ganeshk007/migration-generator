<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Excluded Tables
    |--------------------------------------------------------------------------
    |
    | Define tables that should be ignored during migration generation.
    | Example: ['jobs', 'failed_jobs', 'password_resets']
    |
    */

    'exclude_tables' => [
        'jobs',
        'failed_jobs',
        'password_resets',
        'oauth*'
    ],
];
