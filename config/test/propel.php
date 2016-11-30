<?php

return [
    'propel' => [
        'paths' => [
            'phpDir' => 'app/'
        ],
        'database' => [
            'connections' => [
                'scims' => [
                    'adapter'    => 'sqlite',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'sqlite:tests.database',
                    'user'       => '',
                    'password'   => '',
                    'attributes' => []
                ]
            ]
        ]
    ]
];
