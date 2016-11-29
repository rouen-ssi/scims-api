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
                    'dsn'        => 'sqlite:scims_test.database' ,
                    'user'       => '',
                    'password'   => '',
                    'attributes' => []
                ]
            ]
        ]
    ]
];
