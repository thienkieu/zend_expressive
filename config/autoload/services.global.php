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
        ]
    ],
];
