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
                    'dsn'        => 'sqlite:test.db',
                    'user'       => '',
                    'password'   => '',
                    'attributes' => []
                ]
            ]
        ]
    ]
];
