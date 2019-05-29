<?php

declare(strict_types=1);

return [
    'requestToDTO' => [
        'section.create' => \Test\DTOs\SectionDTO::class,
        'test.create'   => \Test\DTOs\TestDTO::class,
    ],
    'validator' => [
        'section.create' => [            
        ]
    ]
];