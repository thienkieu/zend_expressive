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
        
    ],

    'i18n' => [
        'default_locale' => 'en_US',
    ],

    'convertorDocumentAdapters' => [
        \Test\Convertor\Adapter\Documents\ToListeningDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToReadingDocumentAdapter::class,
        \Test\Convertor\Adapter\Documents\ToWritingDocumentAdapter::class
    ],

    'convertorDTOAdapters' => [
        \Test\Convertor\Adapter\DTOs\ToListeningDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToReadingDTOAdapter::class,
        \Test\Convertor\Adapter\DTOs\ToWritingDTOAdapter::class
    ],

    'convertorDocumentToDTOAdapters' => [
        \Test\Convertor\Adapter\DTOs\FromListeningDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromReadingDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromWritingDocumentAdapter::class,
        
        \Test\Convertor\Adapter\DTOs\FromQuestionDocumentAdapter::class,
        \Test\Convertor\Adapter\DTOs\FromAnswerDocumentAdapter::class
    ],

    'validatorRequestAdapters' => [
        \Test\Validator\CreateReadingSectionValidatorAdapter::class,
        \Test\Validator\CreateSectionValidatorAdapter::class,
        \App\Validator\VerifyValidatorAdapter::class
    ],

    'uploadConfig' => [
        'fileTypes' => ['image/png','image/gif','image/jpeg'],
        'uploadFolder' => $dir.'/../Uploads',
        'maxUploadSizeAllow' => 1024,
    ],

];
