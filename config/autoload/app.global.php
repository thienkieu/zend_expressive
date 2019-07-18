<?php

declare(strict_types=1);

use League\OAuth2\Server\Grant;

$dir = realpath('src');

return [
    'environment' => 'dev',
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
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'thienkieu',
    ],
    'nonsqldb_dev' => [
        'mongodb-connection' => 'mongodb://thienkieu:Mlab0958588127@ds243897.mlab.com:43897/onlinetest_dev',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'onlinetest_dev',
    ],
    
    'log' => [
        'writers' => [
            [
                'name' => 'stream',
                'options' => [
                    'stream' => $dir.'/../Logs/'.date('Y-m-d').'.txt',
                    'filters' => [
                        'allMessages' => [
                            'name' => 'priority',
                            'options' => [
                                'operator' => '>=', 
                                'priority' => \Zend\Log\Logger::EMERG,
                            ]
                        ],
                    ],
                ]
            ]
        ] 
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
        
        'grants' => [
            Grant\ClientCredentialsGrant::class => Grant\ClientCredentialsGrant::class,
            Grant\PasswordGrant::class          => Grant\PasswordGrant::class,
            Grant\AuthCodeGrant::class          => Grant\AuthCodeGrant::class,
            Grant\ImplicitGrant::class          => Grant\ImplicitGrant::class,
            Grant\RefreshTokenGrant::class      => Grant\RefreshTokenGrant::class,
            ODMAuth\Grant\SSOGrant::class       => ODMAuth\Grant\SSOGrant::class,            
        ],

    ],

    'i18n' => [
        'default_locale' => 'en_US',
    ],

    'uploadConfig' => [
        'fileTypes' => [
            'image/png',
            'image/gif',
            'image/jpeg',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/x-zip-compressed',
            'application/octet-stream',
            'audio/mp3'
        ],
        'extensions' => [
            'image' =>
                [
                    'png',
                    'gif',
                    'jpg',
                    'jpeg'
                ],
            'radio' => [
                'mp3',
                'avi'
            ]
        ],
        'uploadFolder' => $dir.'/../Uploads',
        'maxUploadSizeAllow' => 10024,
    ],

    'CORS' => [
        'headers.allow' => ['Content-Type','Accept-Language','Authorization']
    ],

    'CRM' => [
        'candidates' => 'http://192.168.190.92:8089/Portal/ExcuteByCommand/GetCandiates'
       // 'candidates' => 'http://localhost:12346/Portal/ExcuteByCommand/GetCandiates'
    ],
    
    'authenticationExcludeUrl' => [
        'exam.doExam',
        'api.publickey',
        'home',
        'api.token',
        'exam.enterPin',
        'exam.updateAnswer',
        'exam.synchronyTime',
        'exam.finish'
    ]
];
