<?php

return [
    
    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],

    ],

    'migrations' => 'migrations',
    'schedule' => [   
                            'pagi' => [
                                        'start' => env('PAGI_START', '01:00'),
                                        'end' => env('PAGI_END', '03:00'),
                                    ],
                            'sore' => [
                                        'start' => env('SORE_START', '14:00'),
                                        'end' => env('SORE_END', '17:00'),
                                    ],
                            'malam' => [
                                        'start' => env('MALAM_START', '21:00'),
                                        'end' => env('MALAM_END', '23:00'),
                                    ],

                        ],


];
