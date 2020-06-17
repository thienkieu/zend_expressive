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
    'autoloadPath' => $dir.'/../vendor/autoload.php',
    'nonsqldb' => [
        'mongodb-connection' => 'mongodb://192.168.191.79:27017/demo_2608',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/../Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'demo_2608',
    ],
    'nonsqldb_dev' => [
        'mongodb-connection' => 'mongodb://thienkieu:Mlab0958588127@ds243897.mlab.com:43897/onlinetest_dev',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/../Proxies',
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
        'proxy-path' =>  $dir.'/../Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'onlinetest_demo',
    ],
    
    'log' => [
        'writers' => [
            [
                'name' => 'stream',
                'options' => [
                    'stream' => $dir.'/../Logs/'.date('Y-m-d-H').'.txt',
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
            'audio/mp3',
            'audio/mpeg'
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
        'maxUploadSizeAllow' => 25600,
    ],

    'CORS' => [
        'headers.allow' => ['Content-Type','Accept-Language','Authorization']
    ],

    'CRM' => [
        'candidates' => 'http://192.168.190.92:8089/Portal/ExcuteByCommand/GetCandiates'
       // 'candidates' => 'http://localhost:12346/Portal/ExcuteByCommand/GetCandiates'
    ],

    'TRACKING_CONNECT' => [
        'latestDisConnectURL' => 'http://localhost:3000/getLatestDisconenct',
        'writeLogURL' => 'http://localhost:3000/writeLog',
        'enableLogFile' => true,
        'enableLogRemote' => false,

       // 'candidates' => 'http://localhost:12346/Portal/ExcuteByCommand/GetCandiates'
    ],
    
    'authenticationExcludeToken' => [
        'Bearer CRMbackend'
    ],

    'authenticationExcludeUrl' => [
        'api.publickey',
        'home',
        'api.token',
        'exam.enterPin',
        'exam.synchronyTime',
        'question.types',
        'question.sources',
        'exam.types',
        'exam.viewExamResult',
        'question.export',
        'exam.exportCandidateResult',
        'exam.exportPin',
        'exam.examJoined',
        'exam.latestExamJoined',
        'log',
        'exam.exportExamResultSummary',
        'clientWriteLog',
        'exam.listeningFinished',
        'exam.clickToListen',
       
    ],

    'authenticationRequirePin' => [
        'exam.doExam',
        'user.updateAnswer',
        'exam.synchronyTime',
        'exam.finish',
        'exam.listeningFinished',
        'exam.clickToListen',
    ]

];
