<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'scims' => [
                    'adapter'    => 'sqlite',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'sqlite:' . __DIR__ . '/../scims.sqlite' ,
                    'user'       => '',
                    'password'   => '',
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'scims',
            'connections' => ['scims']
        ],
        'generator' => [
            'defaultConnection' => 'scims',
            'connections' => ['scims']
        ],
        'paths' => [
          'phpDir'       => 'app/',
          'phpConfDir'   => 'config',
          'sqlDir'       => 'sql',
          'migrationDir' => 'sql/migrations'
        ]
    ]
];
