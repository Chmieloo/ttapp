<?php

$settings = [
    'meta' => [
        'entity_path' => [
            'App/Entity'
        ],
        'auto_generate_proxies' => true,
        'proxy_dir' =>  __DIR__.'/../cache/proxies',
        'cache' => null,
    ],
    'connection' => [
        'driver'   => 'pdo_mysql',
        'host'     => '0.0.0.0',
        'dbname'   => 'ttapp',
        'user'     => 'developer',
        'password' => 'abcd1234',
    ]
];
