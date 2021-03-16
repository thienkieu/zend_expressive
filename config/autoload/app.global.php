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
        'mongodb-connection' => 'mongodb://admin:pass@localhost:27017/onlinetest',
        'document-path' => [
            $dir.'/App/src/Documents',
            $dir.'/Test/src/Documents',
            $dir.'/ODMAuth/src/Documents',
        ],
        'proxy-path' =>  $dir.'/../Proxies',
        'hydrators-path' =>  $dir.'/../Hydrators',
        'dbname' => 'online_test',
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
        'headers.allow' => ['Content-Type','Accept-Language','Authorization','clientBuild']
    ],

    'CRM' => [
        'candidates' => 'http://192.168.190.92:8089/Portal/ExcuteByCommand/GetCandiates'
       // 'candidates' => 'http://localhost:12346/Portal/ExcuteByCommand/GetCandiates'
    ],

    'TRACKING_CONNECT' => [
        'latestDisConnectURL' =>'http://backend.onlinetest.logigear.com:3000/getLatestDisconenct', //'http://localhost:3000/getLatestDisconenct',
        'writeLogURL' => 'http://localhost:3000/writeLog',
        'enableLogFile' => true,
        'enableLogRemote' => false,

       // 'candidates' => 'http://localhost:12346/Portal/ExcuteByCommand/GetCandiates'
    ],
    
    'authenticationExcludeToken' => [
        'Bearer CRMbackend'
    ],

    'clientConfig' => [
        'clientBuild' => '2.0',
        'requestTime' => 3000, // 10 seconds,
		'timeoutRequest' => 1000,
        'refreshAppTime' => 600000,
        'reconnectionAttempts' => 5,
        'reconnectionDelay' => 2000,
		'reconnectionDelayMax' => 2000,
		'autoSaveInterval' => 5000,
        'versionLinkNumber' => 'clientbuild_2.0.0.2',
        'executeJavascriptOnInit' => 'console.log("remote command");'

    ],

    'authenticationExcludeUrl' => [
        'api.publickey',
        'test.verify',
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
        'clientConfig',
        'exam.listeningFinished',
        'exam.clickToListen',
        'verifyODM',
        'test.verifyAudio',
        'platform'
       
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
