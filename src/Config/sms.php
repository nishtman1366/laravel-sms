<?php
return [
    'default' => 'MeliPayamak',

    'providers' => [
        'hostIran' => [
            'username' => 'host',
            'password' => 'iran',
            'number' => '987654321'
        ],

        'MeliPayamak' => [
            'username' => 'meli',
            'password' => 'payamak',
            'number' => '123456789'
        ],


        'SmsIr' => [
            'apiKey' => '124142',
            'secretKey' => '421141',
            'lineNumber' => '421141',
        ]
    ]
];