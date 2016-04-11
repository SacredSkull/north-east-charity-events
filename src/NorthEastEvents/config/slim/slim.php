<?php

return [
    'settings' => [
        'displayErrorDetails' => \NorthEastEvents\Bootstrap::DEBUG,
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../../templates',
            'cache' => __DIR__ . '/../../cache',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'north-east-charity',
            'path' => __DIR__ . '/../../../../logs/app.log',
        ],
        'debug' => [
            'revealHttpVariables' => \NorthEastEvents\Bootstrap::DEBUG
        ],
    ],
];
