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
    ],
];
