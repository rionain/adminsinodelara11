<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Recommended Versions
    |--------------------------------------------------------------------------
    |
    | These versions are used to compare against the current server environment.
    | Update these periodically to ensure the application stays secure and up-to-date.
    |
    */
    'recommended' => [
        'nginx'   => '1.26.0',
        'php'     => '8.3.0',
        'laravel' => '11.0.0',
        'mariadb' => '10.11.0', // LTS
        'mysql'   => '8.0.0',
        'alpine'  => '3.20.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Critical/Obsolete Thresholds
    |--------------------------------------------------------------------------
    |
    | If the current version is below these thresholds, a "Critical/Obsolete" 
    | alert will be displayed to warn the user of potential security risks.
    |
    */
    'obsolete' => [
        'php'     => '8.1.0',
        'laravel' => '10.0.0',
        'mariadb' => '10.5.0',
    ],
];
