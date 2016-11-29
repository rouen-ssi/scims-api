<?php

return [
    'propel' => [
        'paths' => [
            'phpDir' => 'app/'
        ],
        'database' => [
            'connections' => [
                'scims' => [
                    'adapter'    => 'pgsql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'pgsql:host=localhost;dbname=scims',
                    'user'       => 'scims',
                    'password'   => 'scims',
                    'attributes' => []
                ]
            ]
        ]
    ]
];
