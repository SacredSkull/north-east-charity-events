<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'site' => [
                    'adapter'    => 'mysql',
                    //debugging
                    //'classname'  => 'Propel\Runtime\Connection\DebugPDO',
                    //production
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host=localhost;dbname=site',
                    'user'       => 'root',
                    'password'   => 'vagrant',
                    'attributes' => [],
                ],
            ],
        ],
        'runtime' => [
            'defaultConnection' => 'site',
            'connections' => ['site'],
            'log' => [
                'defaultLogger' => [
                    'type' => 'stream',
                    'path' => 'logs/propel.log',
                    'level' => 100,
                ],
            ],
        ],
        'generator' => [
            'defaultConnection' => 'site',
            'connections' => ['site'],
        ],
    ]
];
