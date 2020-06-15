<?php

use Monolog\Handler\StreamHandler;

return [
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['stderr', 'stdout'],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'level' => 'notice',
            'with' => [
                'stream' => 'php://stderr',
                'bubble' => false,
            ],
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/bytic.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'newrelic' => [
            'driver' => 'monolog',
            'handler' => \Nip\Logger\Monolog\Handler\NewRelicHandler::class
        ],

        'stdout' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'level' => 'info',
            'with' => [
                'stream' => 'php://stdout',
                'bubble' => false,
            ],
        ],
    ]
];
