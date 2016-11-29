<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'scims' => [
                    'adapter'    => 'pgsql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'pgsql:host=localhost;dbname=scims' ,
                    'user'       => 'scims',
                    'password'   => 'scims',
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
          'phpDir'       => 'app',
          'phpConfDir'   => 'config',
          'sqlDir'       => 'sql',
          'migrationDir' => 'sql/migrations'
        ]
    ]
];
