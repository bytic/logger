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
