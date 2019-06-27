<?php

declare(strict_types=1);

use League\OAuth2\Server\Grant;

$dir = realpath('src');

return [
    'resolveService' => [
        \Test\Services\TestServiceInterface::class => [
            \Test\Services\TestService::class,
            \Test\Services\AdvanceTestService::class,        
        ],
        \Test\Services\SectionServiceInterface::class => [
            \Test\Services\SectionService::class,
        ],
        \Test\Services\ImportQuestionServiceInterface::class => [
            \Test\Services\ImportQuestionService::class,
        ],
        \Test\Services\CandidateServiceInterface::class => [
            \Test\Services\CandidateService::class,
        ],
        \Test\Services\SourceServiceInterface::class => [
            \Test\Services\SourceService::class,
        ],
        \Test\Services\ExamServiceInterface::class => [
            \Test\Services\ExamService::class,
        ],
        \Test\Services\DoExamServiceInterface::class => [
            \Test\Services\DoExamService::class,
        ],
        \Test\Services\Question\QuestionServiceInterface::class => [
            \Test\Services\Question\QuestionService::class,
            \Test\Services\Question\ReadingQuestionService::class,
            \Test\Services\Question\WritingQuestionService::class,
            \Test\Services\Question\ListeningQuestionService::class,
        ],
        \Test\Services\PinServiceInterface::class => [
            \Test\Services\PinService::class,
        ],
        \Test\Services\DoExamResultServiceInterface::class => [
            \Test\Services\DoExamResultService::class,
            \Test\Services\DoExamResultWritingService::class,
            \Test\Services\DoExamResultRepeatTimesService::class,
            \Test\Services\DoBaseExamResultService::class,
        ]
    ],
];
