<?php

declare(strict_types=1);


$dir = realpath('src');

return [
    'sqldb' => [
        'db' => [
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '',
            'dbname'   => 'onlinetest',
        ],
        'entity-path' => [
            $dir.'/App/src/Entity'
        ],
    ],

    'nonsqldb' => [
        'mongodb-connection' => 'mongodb://thienkieu:Mlab0958588127@ds243963.mlab.com:43963/thienkieu',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents'
        ],
        'proxy-path' =>  $dir.'/Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'thienkieu',
    ],
    
    'log' => [
        'stream' => new Zend\Log\Writer\Stream($dir.'/../Logs/'.date('Y-m-d').'.txt'),        
        'enable'  => true,  
    ],
    
    'authentication' => [
        'private_key'    => __DIR__ . '/../../data/oauth/private.key',
        'public_key'     => __DIR__ . '/../../data/oauth/public.key',
        'encryption_key' => require __DIR__ . '/../../data/oauth/encryption.key',
        'access_token_expire'  => 'P1D',
        'refresh_token_expire' => 'P1M',
        'auth_code_expire'     => 'PT10M',
        'pdo' => [
            'dsn'      => 'mysql:host=localhost;dbname=onlinetest',
            'username' => 'root',
            'password' => ''
        ],
        
    ],

    'i18n' => [
        'default_locale' => 'en_US',
    ]

];
