<?php

use function Bytic\Phpqa\PhpCsFixer\config;
use function Bytic\Phpqa\PhpCsFixer\finder;

return config(
    finder([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
);
