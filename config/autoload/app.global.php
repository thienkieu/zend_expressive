<?php

declare(strict_types=1);

use League\OAuth2\Server\Grant;

$dir = realpath('src');

return [
    'environment' => '',
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

    'nonsqldb_demo' => [
        'mongodb-connection' => 'mongodb://thienkieu:Mlab0958588127@ds253017.mlab.com:53017/onlinetest_demo',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'onlinetest_demo',
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

    'i18n' => [
        'default_locale' => 'en_US',
    ],
    'exportTemplateFolder' => $dir.'/../ExportTemplates',
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
        'exam.finish',
        'question.types',
        'question.sources',
        'exam.types',
        'exam.viewExamResult',
        'question.export',
        'exam.exportCandidateResult',
        'exam.exportPin',
        'log'
    ]
];
