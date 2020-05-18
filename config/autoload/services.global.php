<?php

declare(strict_types=1);

use League\OAuth2\Server\Grant;

$dir = realpath('src');

return [
    'resolveService' => [
        \Infrastructure\DataParser\DataParserInterface::class => [
            \Infrastructure\DataParser\ExcelParserService::class,
            \Infrastructure\DataParser\WordParserService::class,      
        ],
        \Infrastructure\Services\Interfaces\LogInterface::class => [
            \Infrastructure\Services\LogService::class,     
        ],

        \Test\Services\Interfaces\TestServiceInterface::class => [
            \Test\Services\TestService::class,
            \Test\Services\AdvanceTestService::class,        
        ], 
        \Test\Services\Interfaces\TestTemplateServiceInterface::class => [
            \Test\Services\TestTemplateService::class,       
        ], 
        \Test\Services\ImportQuestionServiceInterface::class => [
            \Test\Services\ImportQuestionService::class,
        ],
        \Test\Services\CandidateServiceInterface::class => [
            \Test\Services\CandidateService::class,
        ],
        \Test\Services\Interfaces\SourceServiceInterface::class => [
            \Test\Services\SourceService::class,
        ],
        \Test\Services\Interfaces\TypeServiceInterface::class => [
            \Test\Services\LogigearTypeService::class,
            \Test\Services\TypeService::class,
        ],
        \Test\Services\ExamServiceInterface::class => [
            \Test\Services\EmptyTestExamService::class,
            \Test\Services\VerbalExamService::class,
            \Test\Services\LogigearExamService::class,
            \Test\Services\ExamService::class,
        ],
        \Test\Services\DoExamServiceInterface::class => [
            \Test\Services\DoExamService::class,
        ],
        \Test\Services\Interfaces\ExportServiceInterface::class => [
            \Test\Services\ExportService::class,
        ],
        
        \Test\Services\Question\QuestionServiceInterface::class => [
            \Test\Services\Question\ReadingQuestionService::class,
            \Test\Services\Question\WritingQuestionService::class,
            \Test\Services\Question\ListeningQuestionService::class,
            \Test\Services\Question\VerbalQuestionService::class,
            \Test\Services\Question\QuestionService::class,
        ],
        \Test\Services\PinServiceInterface::class => [
            \Test\Services\PinService::class,
        ],

        \Test\Services\DoExamResultServiceInterface::class => [
            \Test\Services\DoExamResultService::class,
            \Test\Services\DoExamResultWritingService::class,
            \Test\Services\DoExamResultRepeatTimesService::class,
            \Test\Services\DoExamResultListeningFinishService::class,
            \Test\Services\DoExamResultListeningClickToListenService::class,
            \Test\Services\DoBaseExamResultService::class,
        ],
        
        \Test\Services\TrackingConnectServiceInterface::class => [
            \Test\Services\TrackingConnectService::class
        ]
    ],
];
